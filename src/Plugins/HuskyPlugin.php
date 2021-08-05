<?php

namespace Husky\Plugins;

use Composer\Composer;
use Composer\DependencyResolver\GenericRule;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PackageEvent;
use Composer\Installer\PackageEvents;
use Composer\IO\ConsoleIO;
use Composer\IO\IOInterface;
use Composer\Plugin\Capability\CommandProvider;
use Composer\Plugin\Capable;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;
use Husky\Payloads\BehaviorPayload;
use Husky\Payloads\FlagPayload;
use Husky\Payloads\HuskyCodePayload;
use Husky\Utils\Util;

class HuskyPlugin implements PluginInterface, EventSubscriberInterface, Capable
{
    const HUSKY = 'husky-php';

    private $marked;

    private $binFile = 'husky-php';

    protected $composer;
    protected $io;

    /**
     * @param Composer               $composer
     * @param IOInterface| ConsoleIO $io
     */
    public function activate(Composer $composer, IOInterface $io)
    {
        $this->composer = $composer;
        $this->io = $io;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            PackageEvents::POST_PACKAGE_INSTALL   => [
                ['onPostPackageInstall', 0]
            ],
            PackageEvents::POST_PACKAGE_UPDATE    => [
                ['onPostPackageUpdate', 0],
            ],
            PackageEvents::POST_PACKAGE_UNINSTALL => [
                ['onPostPackageUninstall', 0]
            ],
            ScriptEvents::POST_AUTOLOAD_DUMP      => [
                ['onPostAutoLoadDump', 0]
            ]
        ];
    }

    /**
     * @param Event $event
     */
    public function onPostAutoLoadDump(Event $event)
    {
        if ($this->marked === BehaviorPayload::INSTALL) {

            $composerJson = Util::getPhpDir() . DIRECTORY_SEPARATOR . 'composer.json';

            $composerJson = json_decode(file_get_contents($composerJson), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $composerJson = [];
            }

            if (isset($composerJson['config']['vendor-dir'])) {
                Util::$vendor = $composerJson['config']['vendor-dir'];
            }

            $huskyPath = Util::$vendor . '/bin/' . $this->binFile;

            $cmd = "{$huskyPath} husky:install";

            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                $cmd = "bash {$cmd}";
            }

            exec($cmd, $output, $code);

            if ($code === HuskyCodePayload::SUCCESS) {
                $flags = FlagPayload::SUCCESS;
            } else {
                $flags = FlagPayload::FAIL;
            }

            $this->io->write(FlagPayload::COMMENT[0] . '---------- composer-husky-plugin -----------' . FlagPayload::COMMENT[1]);
            $this->io->write($flags[0] . implode($output, PHP_EOL) . $flags[1]);
        }
    }

    /**
     * @param PackageEvent $event
     *
     * @return bool
     */
    public function onPostPackageInstall(PackageEvent $event)
    {
        $packageName = $event->getOperation()->getPackage()->getName();

        if (strpos($packageName, self::HUSKY) === false) {
            return false;
        }

        $this->marked = BehaviorPayload::INSTALL;

        $flagSuccess = FlagPayload::SUCCESS;
        $flagComment = FlagPayload::COMMENT;

        $this->io->write('  - composer-husky-plugin ' . $flagSuccess[0] . $packageName . "({$flagComment[0]}Installed$flagComment[1])" . $flagSuccess[1] . ': Ready');

        return true;
    }

    /**
     * @param PackageEvent $event
     *
     * @return bool
     */
    public function onPostPackageUpdate(PackageEvent $event)
    {

        // do update

        return true;
    }

    /**
     * @param PackageEvent $event
     *
     * @return bool
     */
    public function onPostPackageUninstall(PackageEvent $event)
    {
        // do uninstall

        return true;
    }

    /**
     * @return array|string[]
     */
    public function getCapabilities()
    {
        return [
            CommandProvider::class => \Husky\Plugins\CommandProvider::class
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function deactivate(Composer $composer, IOInterface $io)
    {
        // Nothing to deactivate.
    }

    /**
     * {@inheritdoc}
     */
    public function uninstall(Composer $composer, IOInterface $io)
    {
        // Nothing to uninstall.
    }
}