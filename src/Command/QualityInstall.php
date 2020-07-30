<?php

declare(strict_types=1);

namespace Twc\Plugin\Composer\Command;

use Composer\Command\BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;

class QualityInstall extends BaseCommand
{
    private $dirQuality;

    public function __construct(string $dirQuality)
    {
        parent::__construct();
        $this->dirQuality = $dirQuality;
    }

    protected function configure()
    {
        $this->setName('twc:quality:install');
        $this->addOption('mode', null, InputOption::VALUE_OPTIONAL, 'Force to install directory quality', false);
        $this->addOption(
            'skip-if-exist',
            null,
            InputOption::VALUE_OPTIONAL,
            'Skip if dir exist and inform user to install manually',
            false
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $fileSystem = new Filesystem();
        $composerSystem = new \Composer\Util\Filesystem();

        $mode = $input->getOption('mode');
        $skip = $input->getOption('skip-if-exist');

        $message = "<info>twc/code-quality-plugin:</info> <error>Le dossier quality existe déjà ! si vous souhaitez l'écraser utiliser --mode=force</error>";

        if (\is_dir($this->dirQuality) && ($skip !== false)) {
            $message = "<info>twc/code-quality-plugin:</info> [skip] Le dossier quality existe déjà ! si vous souhaitez l'écraser utiliser twc:quality:install --mode=force";
        }

        if (\is_dir($this->dirQuality) && ($mode !== 'force')) {
            $output->writeln($message);

            return false;
        }

        $composerSystem->copy(__DIR__ . '/../../quality', $this->dirQuality);
        $fileSystem->chmod($this->dirQuality . '/commit-rules.sh', 0777);
        $output->writeln('<info>twc/code-quality-plugin:</info> Dossier de configuration créé');
    }
}
