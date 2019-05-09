<?php

namespace Husky\Plugins;

use Composer\Plugin\Capability\CommandProvider as CommandProviderCapability;
use Husky\Commands\InstallCommand;
use Husky\Commands\RunCommand;
use Husky\Commands\UninstallCommand;

class CommandProvider implements CommandProviderCapability
{
    /**
     * @return array|\Composer\Command\BaseCommand[]
     */
    public function getCommands()
    {
        return [
            new InstallCommand(),
            new RunCommand(),
            new UninstallCommand()
        ];
    }
}