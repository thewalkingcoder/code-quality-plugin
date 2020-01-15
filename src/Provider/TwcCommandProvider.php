<?php

declare(strict_types=1);

namespace Twc\Plugin\Composer\Provider;

use Composer\Plugin\Capability\CommandProvider;
use Twc\Plugin\Composer\Command\AllInstall;
use Twc\Plugin\Composer\Command\CommitForce;
use Twc\Plugin\Composer\Command\HooksInstall;
use Twc\Plugin\Composer\Command\MakefileInstall;
use Twc\Plugin\Composer\Command\QualityInstall;

class TwcCommandProvider implements CommandProvider
{
    public function getCommands()
    {
        return [
            new MakefileInstall(__DIR__.'/../../../../../Makefile'),
            new HooksInstall(__DIR__ . '/../../../../../.git'),
            new QualityInstall(__DIR__.'/../../../../../quality'),
            new AllInstall(),
            new CommitForce(),
        ];
    }
}
