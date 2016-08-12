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
    const FILE_PATH = __DIR__ . '/../Resources/config';
    const FILE_NAME = 'versions.yml';

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
     * @param ConsoleCommandEvent $event
     */
    public function run(ConsoleCommandEvent $event)
    {
        $output = $event->getOutput();
        $command = $event->getCommand();

        if (in_array($command->getName(), $this->allowedCommands)) {
            $filename = self::FILE_PATH . '/' . self::FILE_NAME;

            if (file_exists($filename)) {
                $config = Yaml::parse(file_get_contents($filename));
            }

            if (
                !isset($config['framework'])
                or !isset($config['framework']['assets'])
                or !isset($config['framework']['assets']['version'])
            ) {
                $config = array('framework' => array('assets' => array('version' => 0)));
            }
            $oldVersion = $config['framework']['assets']['version']++;
            
            $output->writeln("Updating asset_version from <info>$oldVersion</info>"
                . " to <info>{$config['framework']['assets']['version']}</info>");

            file_put_contents($filename, Yaml::dump($config));
        }
    }
}
