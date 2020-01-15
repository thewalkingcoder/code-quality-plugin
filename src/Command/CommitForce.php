<?php

declare(strict_types=1);

namespace Twc\Plugin\Composer\Command;

use Composer\Command\BaseCommand;
use Composer\Util\Filesystem;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class CommitForce extends BaseCommand
{
    private $hooksDir = __DIR__ . '/../../../../../.git/hooks/';

    protected function configure()
    {
        $this->setName('twc:commit:force');
        $this->addArgument('message', InputArgument::REQUIRED, 'skip rules and quality check.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $message = $input->getArgument('message');

        $this->renameTempo(['pre-commit', 'commit-msg']);

        $process = new Process('git commit -m "[commit-forced] ' . $message . '"');
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $this->restore(['pre-commit.tempo', 'commit-msg.tempo']);
        $output->write($process->getOutput());
    }

    private function getHooksDir()
    {
        return rtrim($this->hooksDir, '/');
    }

    private function renameTempo(array $cibles, $argRename = '.tempo')
    {
        $fileSystem = new Filesystem();

        foreach ($cibles as $cible) {
            $source = $this->getHooksDir() . "/$cible";
            $target = $source . $argRename;
            if (\file_exists($source) === false) {
                continue;
            }

            $fileSystem->rename($source, $target);
        }
    }

    private function restore(array $cibles, $argRename = '.tempo')
    {
        $fileSystem = new Filesystem();

        foreach ($cibles as $cible) {
            $source = $this->getHooksDir() . "/$cible";
            $target = str_replace($argRename, '', $source);
            if (\file_exists($source) === false) {
                continue;
            }

            $fileSystem->rename($source, $target);
        }
    }
}
