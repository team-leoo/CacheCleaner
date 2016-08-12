<?php
/**
 * @author: Aleksandr Daniloff <adaniloff.dev@gmail.com>
 */

/** Namespaces */
namespace LeooTeam\CacheCleanerBundle\Event;

/** Usages */
use LeooTeam\CacheCleanerBundle\Manager\CacheCleanerManager;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;

/**
 * Class CacheCleanerListener
 * @package LeooTeam\CacheCleanerBundle\Event
 */
class CacheCleanerListener
{
    /** @var array $allowedCommands */
    private $allowedCommands;
    
    /** @var CacheCleanerManager $cacheCleanerManager */
    private $cacheCleanerManager;

    /**
     * CacheCleanerListener constructor.
     * @param CacheCleanerManager $cacheCleanerManager
     * @param array $allowedCommands
     */
    public function __construct(CacheCleanerManager $cacheCleanerManager, $allowedCommands)
    {
        $this->cacheCleanerManager = $cacheCleanerManager;
        $this->allowedCommands = $allowedCommands;
    }

    /**
     * @param ConsoleTerminateEvent $event
     */
    public function run(ConsoleTerminateEvent $event)
    {
        $output = $event->getOutput();
        $command = $event->getCommand();

        if (in_array($command->getName(), $this->allowedCommands)) {
            $oldVersion = $this->cacheCleanerManager->getCurrentVersion();
            $newVersion = $this->cacheCleanerManager->incrementVersion();
            
            $output->writeln("Updating asset_version from <info>$oldVersion</info>"
                . " to <info>{$newVersion}</info>");
        }
    }
}
