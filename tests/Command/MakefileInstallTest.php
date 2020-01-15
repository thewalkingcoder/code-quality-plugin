<?php

namespace Twc\Plugin\Composer\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Twc\Plugin\Composer\Command\MakefileInstall;

class MakefileInstallTest extends TestCase
{
    const MAKEFILE = __DIR__.'/../Makefiletest';

    public function testCreateNewFile()
    {
        $makeCommand = $this->createCommand(self::MAKEFILE);
        $return  = $makeCommand->execute([]);

        $this->assertTrue(file_exists(self::MAKEFILE));
    }

    public function testCreateNewLineIfExist()
    {
        file_put_contents(self::MAKEFILE, "monscript:\n     pwd");
        $makeCommand = $this->createCommand(self::MAKEFILE);
        $return  = $makeCommand->execute([]);

        $content = file_get_contents(self::MAKEFILE);
        $content = explode("\n", $content);

        $this->assertTrue(in_array('include vendor/twc/code-quality-plugin/make/quality.mk', $content));
    }

    public function tearDown()
    {
        if (file_exists(self::MAKEFILE)) {
            unlink(self::MAKEFILE);
        }
    }

    private function createCommand($file)
    {
        $command = new MakefileInstall($file);

        $application = new Application();
        $application->add($command);

        $command = $application->find('twc:make:install');

        return new CommandTester($command);
    }
}