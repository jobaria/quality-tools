<?php

declare(strict_types=1);

namespace Jobaria\QualityTools\Installer;

use Composer\Composer;
use Composer\EventDispatcher\EventSubscriberInterface;
use Composer\Installer\PluginInstaller;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Script\Event;
use Composer\Script\ScriptEvents;

final class QualityToolsInstallerPlugin implements PluginInterface, EventSubscriberInterface
{
    private const TEMPLATE_ROOT_PATH = 'vendor/jobaria/quality-tools/resources/templates';
    private const PHIVE_XML_FILE     = '.phive/phars.xml';

    private PluginInstaller $installer;

    /**
     * @return array<string,array>
     */
    public static function getSubscribedEvents() : array
    {
        return [
            ScriptEvents::POST_INSTALL_CMD => ['install', 10],
            ScriptEvents::POST_UPDATE_CMD  => ['install', 10],
        ];
    }

    public function activate(Composer $composer, IOInterface $io) : void
    {
        $this->installer = new QualityToolsInstaller($io, $composer);

        $composer->getInstallationManager()->addInstaller($this->installer);
    }

    public function install(Event $event) : void
    {
        $this->output($event->getIO(), 'Checking installation...');

        if ($this->isPhiveInstalled() === false) {
            $this->output(
                $event->getIO(),
                '<fg=red>Phive is not installed, please install Phive https://phar.io</fg=red>'
            );

            return;
        }

        $this->copyPhiveConfiguration($event);
        $this->copyQualityToolsConfiguration($event);

        $this->output($event->getIO(), 'Installation succeeded!');
        $this->output(
            $event->getIO(),
            '<fg=red>(1) Please run `phive install` to install the necessary quality tools</fg=red>'
        );
        $this->output(
            $event->getIO(),
            '<fg=red>(2) Please run `tools/grumphp git:init` to enable GrumPHP and sniff your commits</fg=red>'
        );
    }

    public function deactivate(Composer $composer, IOInterface $io) : void
    {
        $composer->getInstallationManager()->removeInstaller($this->installer);
    }

    public function uninstall(Composer $composer, IOInterface $io) : void
    {
    }

    private function isPhiveInstalled() : bool
    {
        $output = [];

        exec('phive version', $output);

        if (count($output) === 0) {
            return false;
        }

        return preg_match('/^Phive\s\d{1,}.\d{1,}.\d{1,}\s/', array_values($output)[0]) === 1;
    }

    private function copyPhiveConfiguration(Event $event) : void
    {
        $projectFolderPath = $this->getProjectFilePath(sprintf('/%s', dirname(self::PHIVE_XML_FILE)));
        $projectFilePath   = $this->getProjectFilePath(sprintf('/%s', self::PHIVE_XML_FILE));
        $templateFilePath  = sprintf('%s/%s', self::TEMPLATE_ROOT_PATH, self::PHIVE_XML_FILE);

        if (file_exists($projectFilePath) === true) {
            $this->output($event->getIO(), sprintf(
                '<fg=yellow>%s</fg=yellow> already exists in your directory, ' .
                'please copy the necessary lines if needed from <fg=yellow>%s</fg=yellow> ' .
                'to your <fg=yellow>%s</fg=yellow>',
                self::PHIVE_XML_FILE,
                $templateFilePath,
                self::PHIVE_XML_FILE
            ));

            return;
        }

        // Create folder if not it does not exist yet
        if (file_exists($projectFolderPath) === false) {
            exec(sprintf('mkdir %s', $projectFolderPath));
        }

        copy($templateFilePath, $projectFilePath);

        $this->output($event->getIO(), sprintf('Copied <fg=yellow>%s</fg=yellow>', self::PHIVE_XML_FILE));
    }

    private function copyQualityToolsConfiguration(Event $event) : void
    {
        /** @var string[] $templates */
        $templates = glob(self::TEMPLATE_ROOT_PATH . '/{,.}*', GLOB_BRACE);
        foreach ($templates as $template) {
            $baseName        = $this->getTemplateBaseName($template);
            $projectFilePath = $this->getProjectFilePath($baseName);

            if (is_file($template) === false) {
                continue;
            }

            if (file_exists($projectFilePath) === true) {
                $this->output(
                    $event->getIO(),
                    sprintf('<fg=yellow>%s</fg=yellow> already exists, skipping', $baseName)
                );

                continue;
            }

            copy($template, $projectFilePath);

            $this->output($event->getIO(), sprintf('Copied <fg=yellow>%s</fg=yellow>', $baseName));
        }
    }

    private function getTemplateBaseName(string $template) : string
    {
        return basename($template);
    }

    private function getProjectFilePath(string $template) : string
    {
        return implode('/', [getcwd(), $template]);
    }

    private function output(IOInterface $output, string $message) : void
    {
        $output->write(sprintf('<fg=green>jobaria/quality-tools:</fg=green> %s', $message));
    }
}
