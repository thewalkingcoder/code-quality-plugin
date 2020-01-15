<?php

declare(strict_types=1);

namespace Twc\Plugin\Composer;

use Composer\Command\GlobalCommand;
use Composer\Composer;
use Composer\Console\Application;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer;
use Composer\IO\IOInterface;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;
use Twc\Plugin\Composer\Command\TotoInstall;
use Twc\Plugin\Composer\Provider\TwcCommandProvider;

class CodeQualityPlugin implements PluginInterface, EventSubscriberInterface, Capable
{
    /** @var \Composer\Composer */
    private $composer;
    /** @var \Composer\IO\IOInterface */
    private $io;

    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    public static function getSubscribedEvents()
    {
        return [
            'post-install-cmd'     => 'postInstall',
            'post-autoload-dump' => 'postInstall',
        ];
    }

    public function getCapabilities()
    {
        return [
            'Composer\Plugin\Capability\CommandProvider' => TwcCommandProvider::class,
        ];
    }

    public function postInstall()
    {
        $this->io->write('<info>twc/code-quality-plugin:</info> Installation');

        $process = new Process('composer twc:install');
        $process->run();
        
        $this->io->write($process->getOutput());
        $this->io->write('<info>twc/code-quality-plugin:</info> ...installation terminée');
    }
}
