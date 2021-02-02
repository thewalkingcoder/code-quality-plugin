<?php

declare(strict_types=1);

namespace Twc\Plugin\Composer\Command;

use Composer\Command\BaseCommand;
use Composer\Util\Filesystem;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class MakefileInstall extends BaseCommand
{
    private $makefile;

    public function __construct(string $makefile)
    {
        parent::__construct();
        $this->makefile = $makefile;
    }

    protected function configure()
    {
        $this->setName('twc:make:install');
        $this->addOption('mode', null, InputOption::VALUE_OPTIONAL, 'Force to install Makefile', false);
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
        $fileSystem = new Filesystem();
        $mode = $input->getOption('mode');
        $skip = $input->getOption('skip-if-exist');
        $model = 'include vendor/twc/code-quality-plugin/make/quality.mk';

        if (\file_exists($this->makefile) === false) {
            $fileSystem->copy(__DIR__ . '/../../make/Makefile', $this->makefile);
            $output->writeln('<info>twc/code-quality-plugin:</info> Create Makefile');

            return 0;
        }

        $content = file_get_contents($this->makefile);
        $lines = explode("\n", $content);

        if (in_array($model, $lines)) {
            $output->writeln('<info>twc/code-quality-plugin:</info> [skip] Makefile is up to date');

            return 0;
        }

        file_put_contents($this->makefile, "\n\n$model", FILE_APPEND);
        $output->writeln('<info>twc/code-quality-plugin:</info> Makefile updated');

        return 0;
    }
}
