<?php

declare(strict_types=1);

namespace Twc\Plugin\Composer\Command;

use Composer\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class HooksInstall extends BaseCommand
{
    private $output;
    private $input;

    public function __construct($girDir)
    {
        parent::__construct();
        $this->gitDir = $girDir;
    }

    protected function configure()
    {
        $this->setName('twc:hooks:install');
        $this->addOption('mode', null, InputOption::VALUE_OPTIONAL, 'Force to install hooks', false);
        $this->addOption('only-hook', null, InputOption::VALUE_OPTIONAL, 'Specify the hook copy', false);
        $this->addOption(
            'skip-if-exist',
            null,
            InputOption::VALUE_OPTIONAL,
            'Skip if file exist and inform user to install manually',
            false
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->input = $input;
        $skip = $this->input->getOption('skip-if-exist');

        if (\is_dir($this->getDirGit()) === false) {
            if ($skip === false) {
                throw new \LogicException("Git n'est pas initialisé sur votre projet");
            }
            $this->output->writeln('<info>[skip] Git n\'est pas initialisé sur votre projet</info>');

            return 0;
        }

        $hooks = $this->getHooks();

        foreach ($hooks as $hook) {
            $this->copy($hook);
        }

        return 0;
    }

    private function getHooks()
    {
        $onlyHook = $this->input->getOption('only-hook');
        $hooks = ['commit-msg', 'pre-commit'];
        if ($onlyHook === false) {
            return $hooks;
        }

        $hooks = array_intersect($hooks, [$onlyHook]);

        if (empty($hooks)) {
            throw new \LogicException("Ce hook n'est pas géré le plugin");
        }

        return $hooks;
    }

    protected function copy($hook)
    {
        $fileSystem = new Filesystem();
        $mode = $this->input->getOption('mode');
        $skip = $this->input->getOption('skip-if-exist');
        $message = "<info>twc/code-quality-plugin:</info> <error> $hook existe déjà ! si vous souhaitez l'écraser utiliser --mode=force</error>";

        if (\file_exists($this->getAbsoluteHook($hook)) && ($skip !== false)) {
            $message = "<info>twc/code-quality-plugin:</info> [skip] $hook existe déjà ! si vous souhaitez l'écraser utiliser composer twc:hooks:install --only-hook={$hook} --mode=force";
        }

        if (\file_exists($this->getAbsoluteHook($hook)) && ($mode !== 'force')) {
            $this->output->writeln($message);

            return false;
        }

        $commitMsgSource = __DIR__ . "/../../hooks/$hook";
        $fileSystem->copy($commitMsgSource, $this->getAbsoluteHook($hook));
        $fileSystem->chmod($this->getAbsoluteHook($hook), 0777);
        $this->output->writeln("<info>twc/code-quality-plugin:</info> Hook $hook créé");
    }

    private function getAbsoluteHook($hook)
    {
        return rtrim($this->getDirGit()) . '/hooks/' . $hook;
    }

    private function getDirGit()
    {
        return $this->gitDir;
    }
}
