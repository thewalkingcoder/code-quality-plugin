<?php

namespace Twc\Plugin\Composer\Tests;

use Composer\Console\Application;
use Composer\Util\Filesystem;
use PHPUnit\Framework\TestCase;

use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Finder\Finder;
use Twc\Plugin\Composer\Command\HooksInstall;
use Twc\Plugin\Composer\Command\MakefileInstall;

class HooksInstallTest extends TestCase
{
    const DIR_HOOKS = __DIR__.'/../';

    public function testCreateHooks()
    {
        $makeCommand = $this->createCommand(self::DIR_HOOKS);
        $return = $makeCommand->execute([]);


        foreach ($this->getHooksName() as $name) {
            $this->assertTrue(file_exists(self::DIR_HOOKS.'/hooks/'.$name));
        }
    }

    public function testCreateOneHook()
    {
        $makeCommand = $this->createCommand(self::DIR_HOOKS);
        $return = $makeCommand->execute(['--only-hook' => 'pre-commit']);

        $this->assertTrue(file_exists(self::DIR_HOOKS.'/hooks/pre-commit'));
    }

    public function tearDown()
    {
        $dirHook = self::DIR_HOOKS.'/hooks/';
        if (is_dir($dirHook)) {
            $fileSystem = new Filesystem();
            $fileSystem->removeDirectory($dirHook);
        }
    }

    private function getHooksName()
    {
        $finder = new Finder();
        $finder->files()->in(__DIR__.'/../../hooks');
        $files = [];
        foreach ($finder as $file) {
            $files[] = $file->getFilename();
        }

        return $files;
    }

    private function createCommand($dir)
    {
        $command = new HooksInstall($dir);

        $application = new Application();
        $application->add($command);

        $command = $application->find('twc:hooks:install');

        return new CommandTester($command);
    }
}