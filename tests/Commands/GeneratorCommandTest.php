<?php

declare(strict_types=1);

namespace Tests\Commands;

use Myerscode\Acorn\Testing\Interactions\InteractsWithCommands;
use Myerscode\Utilities\Files\Utility as FileService;
use Tests\BaseTestCase;

/**
 * Covers the shared GeneratorCommand behaviour through its make:* subclasses.
 */
final class GeneratorCommandTest extends BaseTestCase
{
    use InteractsWithCommands;

    private string $outputDir = '';

    protected function tearDown(): void
    {
        if ($this->outputDir !== '' && is_dir($this->outputDir)) {
            FileService::make($this->outputDir)->delete();
        }

        parent::tearDown();
    }

    public function testCreatesACommandFileFromTheStub(): void
    {
        $this->useTempOutputFor('executing.dir.commands');

        $output = $this->call('make:command', ['name' => 'rupert']);

        $this->assertStringContainsString('Created', $output);
        $this->assertFileExists($this->outputDir . '/RupertCommand.php');
        $this->assertStringContainsString('class RupertCommand', (string) file_get_contents($this->outputDir . '/RupertCommand.php'));
    }

    public function testDoesNotOverwriteExistingFileWithoutForce(): void
    {
        $this->useTempOutputFor('executing.dir.commands');

        $target = $this->outputDir . '/GeraldCommand.php';
        file_put_contents($target, '<?php // original');

        $output = $this->call('make:command', ['name' => 'gerald']);

        $this->assertStringContainsString('already exists', $output);
        // Original content is preserved.
        $this->assertSame('<?php // original', file_get_contents($target));
    }

    public function testForceOverwritesExistingFile(): void
    {
        $this->useTempOutputFor('executing.dir.commands');

        $target = $this->outputDir . '/ArthurCommand.php';
        file_put_contents($target, '<?php // original');

        $this->call('make:command', ['name' => 'arthur', '--force' => true]);

        $this->assertStringContainsString('class ArthurCommand', (string) file_get_contents($target));
    }

    public function testListenerEventClassIsRenderedAsClassConstant(): void
    {
        $this->useTempOutputFor('executing.dir.listeners');

        $this->call('make:listener', [
            'name' => 'rupert',
            '--events' => 'App\\Events\\WalkiesEvent',
        ]);

        $content = (string) file_get_contents($this->outputDir . '/RupertListener.php');
        $this->assertStringContainsString('App\\Events\\WalkiesEvent::class', $content);
    }

    public function testListenerEventInjectionIsNeutralisedAsAString(): void
    {
        $this->useTempOutputFor('executing.dir.listeners');

        // A value that is not a valid class reference must be emitted as a
        // quoted string literal, never interpolated as executable PHP.
        $this->call('make:listener', [
            'name' => 'gerald',
            '--events' => '[]; system("boom"); //',
        ]);

        $content = (string) file_get_contents($this->outputDir . '/GeraldListener.php');
        $rendered = $this->phpAfterListensFor($content);

        // The payload is emitted as a single-quoted string literal, so it is
        // inert data, not executable code: the assignment is a quoted string
        // and nothing escapes it (no unescaped quote, no statement separator).
        $this->assertSame(1, preg_match("/^'(?:[^'\\\\]|\\\\.)*'$/", $rendered), 'listensFor should be a single quoted string literal, got: ' . $rendered);

        // The generated file is syntactically valid PHP with no injected
        // statements (lint via php -l).
        $this->assertTrue($this->isValidPhp($this->outputDir . '/GeraldListener.php'));
    }

    public function testListenerWithoutEventsDefaultsToEmptyArray(): void
    {
        $this->useTempOutputFor('executing.dir.listeners');

        $this->call('make:listener', ['name' => 'arthur']);

        $content = (string) file_get_contents($this->outputDir . '/ArthurListener.php');
        $this->assertStringContainsString('$listensFor = [];', $content);
    }

    public function testNameIsSanitisedAndCannotTraverse(): void
    {
        $this->useTempOutputFor('executing.dir.commands');

        $this->call('make:command', ['name' => '../../etc/passwd']);

        // The name is PascalCased to alphanumerics, so the file stays inside
        // the output directory and no traversal occurs.
        $this->assertFileExists($this->outputDir . '/EtcPasswdCommand.php');
        $this->assertFileDoesNotExist('/etc/passwdCommand.php');
    }

    private function isValidPhp(string $file): bool
    {
        $php = PHP_BINARY;
        exec(escapeshellarg($php) . ' -l ' . escapeshellarg($file) . ' 2>&1', $out, $code);

        return $code === 0;
    }

    /**
     * Isolate the PHP that was substituted into the $listensFor assignment so
     * an injection attempt can be asserted against as rendered code.
     */
    private function phpAfterListensFor(string $content): string
    {
        if (preg_match('/\$listensFor\s*=\s*(.*);/', $content, $m) === 1) {
            return $m[1];
        }

        return $content;
    }

    private function useTempOutputFor(string $configKey): void
    {
        $this->outputDir = sys_get_temp_dir() . '/acorn_cli_gen_' . uniqid();
        mkdir($this->outputDir, 0700, true);

        // Build the application (binding the container's config) then point
        // the generator's output location at a temp dir so tests never write
        // into the real app/ tree. `base` must point at the real cli app so
        // stubDirectory() (config('base').'/Resources/Stubs') resolves.
        $config = $this->application()->container()->get('config');
        $config->store()->set('base', dirname(__DIR__, 2) . '/app');
        $config->store()->set($configKey, $this->outputDir);
    }
}
