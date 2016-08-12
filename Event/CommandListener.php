<?php
/**
 * @author: Aleksandr Daniloff <adaniloff.dev@gmail.com>
 */

/** Namespaces */
namespace LeooTeam\CacheCleanerBundle\Event;

/** Usages */
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Yaml\Yaml;

/**
 * Class CommandListener
 * @package LeooTeam\CacheCleanerBundle\Event
 */
class CommandListener
{
    const FILE_PATH = __DIR__ . '/../Resources/config/versions.yml';

    /** @var array $allowedCommands */
    private $allowedCommands;

    /**
     * CommandListener constructor.
     * @param array $allowedCommands
     */
    public function __construct($allowedCommands)
    {
        $this->allowedCommands = $allowedCommands;
    }

    /**
     * @todo: add input parameters ?
     * @todo: check if directories and file exists
     * @param ConsoleCommandEvent $event
     */
    public function run(ConsoleCommandEvent $event)
    {
        $output = $event->getOutput();
        $command = $event->getCommand();

        if (in_array($command->getName(), $this->allowedCommands)) {
            $config = @Yaml::parse(file_get_contents(self::FILE_PATH));
            $oldVersion = (int)@$config['framework']['assets']['version']++;

            $output->writeln("Updating asset_version from <info>$oldVersion</info>"
                . " to <info>{$config['framework']['assets']['version']}</info>");

            file_put_contents(self::FILE_PATH, Yaml::dump($config));
        }
    }
}
