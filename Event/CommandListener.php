<?php

namespace Daniloff\CacheCleanerBundle\Event;

use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Yaml\Yaml;

class CommandListener
{
    const FILE_PATH = 'src/Daniloff/CacheCleanerBundle/Resources/config/versions.yml';

    private static $allowedCommands = [
        'cache:clear',
        'assets:install',
        'assetic:dump',
    ];

    /**
     * @param ConsoleCommandEvent $event
     */
    public function onExec(ConsoleCommandEvent $event)
    {
        /**
         * @todo: add parameters?
         */
        $input = $event->getInput();
        $output = $event->getOutput();
        $command = $event->getCommand();

        /**
         * @todo: make FILE_PATH and allowedCommands config parameters and get them
         * @todo: check if directories and file exists
         */
        if (in_array($command->getName(), self::$allowedCommands)) {
            $config = @Yaml::parse(file_get_contents(self::FILE_PATH));
            $oldVersion = (int)@$config['framework']['assets']['version']++;

            $output->writeln("Updating asset_version from <info>$oldVersion</info>"
                . " to <info>{$config['framework']['assets']['version']}</info>");

            file_put_contents(self::FILE_PATH, Yaml::dump($config));
        }
    }
}
