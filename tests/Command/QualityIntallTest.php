<?php

namespace Twc\Plugin\Composer\Tests;

use Composer\Util\Filesystem;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Finder\Finder;
use Twc\Plugin\Composer\Command\MakefileInstall;
use Twc\Plugin\Composer\Command\QualityInstall;

use function Symfony\Component\DependencyInjection\Loader\Configurator\iterator;

class QualityIntallTest extends TestCase
{
    const DIR_QUALITY_SOURCE = __DIR__.'/../../quality';
    const DIR_QUALITY = __DIR__.'/../qualitytest';

    public function testCreateDir()
    {
        $makeCommand = $this->createCommand(self::DIR_QUALITY);
        $return = $makeCommand->execute([]);

        $this->assertTrue(is_dir(self::DIR_QUALITY));

        $finder = new Finder();
        $finder->files()->in(self::DIR_QUALITY_SOURCE);

        foreach ($finder as $file) {
            $this->assertTrue(file_exists(self::DIR_QUALITY.'/'.$file->getFilename()));
        }
    }

    public function testDoNothingIfDirExist()
    {
        $fileSystem = new \Symfony\Component\Filesystem\Filesystem();
        $fileSystem->mkdir(self::DIR_QUALITY);

        $makeCommand = $this->createCommand(self::DIR_QUALITY);
        $return = $makeCommand->execute([]);

        $finder = new Finder();
        $finder->files()->in(self::DIR_QUALITY);

        $this->assertSame(0, iterator_count($finder));
    }

    public function testOverwriteIfDirExist()
    {
        $fileSystem = new \Symfony\Component\Filesystem\Filesystem();
        $fileSystem->mkdir(self::DIR_QUALITY);

        $makeCommand = $this->createCommand(self::DIR_QUALITY);
        $return = $makeCommand->execute(['--mode' => 'force']);

        $finder = new Finder();
        $finder->files()->in(self::DIR_QUALITY_SOURCE);
        $nombreFileResult = iterator_count($finder);

        $finder = new Finder();
        $finder->files()->in(self::DIR_QUALITY);
        $nombreFileFound = iterator_count($finder);

        $this->assertSame($nombreFileResult, $nombreFileFound);
    }

    public function tearDown()
    {
        if (is_dir(self::DIR_QUALITY)) {
            $fileSystem = new Filesystem();
            $fileSystem->removeDirectory(self::DIR_QUALITY);
        }
    }


    private function createCommand($dir)
    {
        $command = new QualityInstall($dir);

        $application = new Application();
        $application->add($command);

        $command = $application->find('twc:quality:install');

        return new CommandTester($command);
    }
}