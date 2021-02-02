<?php

declare(strict_types=1);

namespace Twc\Plugin\Composer\Command;

use Composer\Command\BaseCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class AllInstall extends BaseCommand
{
    protected function configure()
    {
        $this->setName('twc:install');
        $this->addOption('mode', null, InputOption::VALUE_OPTIONAL, 'Force install', false);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mode = $input->getOption('mode');

        $commands = [
            'twc:quality:install',
            'twc:make:install',
            'twc:hooks:install',
        ];
        $arguments = $mode === 'force' ? ['--mode' => 'force'] : ['--skip-if-exist' => true];

        foreach ($commands as $command) {
            $this->runCommand($command, $output, $arguments);
        }

        $process = new Process('make twc.fixdroits');
        $process->run();

        $this->clearModels();

        return 0;
    }

    private function clearModels()
    {
        $dir = __DIR__.'/../../../../../';
        $files = ['.php_cs.dist', 'phpcs.xml.dist'];

        foreach ($files as $file) {
            $absolute = $dir.$file;
            if (file_exists($absolute)) {
                unlink($absolute);
            }
        }
    }

    private function runCommand($commandName, $output, $arguments)
    {
        $command = $this->getApplication()->find($commandName);
        $commandInput = new ArrayInput($arguments);
        $command->run($commandInput, $output);
    }
}
