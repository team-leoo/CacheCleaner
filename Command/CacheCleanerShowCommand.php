<?php
/**
 * @author: Aleksandr Daniloff <adaniloff.dev@gmail.com>
 */

/** Namespaces */
namespace LeooTeam\CacheCleanerBundle\Command;

/** Usages */
use LeooTeam\CacheCleanerBundle\Manager\CacheCleanerManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CacheCleanerShowCommand
 * @package LeooTeam\CacheCleanerBundle\Command
 */
class CacheCleanerShowCommand extends Command
{
    const COMMAND_NAME = 'ccleaner:show';

    /** @var CacheCleanerManager $cacheCleanerManager */
    private $cacheCleanerManager;

    /**
     * CacheCleanerUpdateCommand constructor.
     * @param CacheCleanerManager $cacheCleanerManager
     * @param null|string $name
     */
    public function __construct(CacheCleanerManager $cacheCleanerManager, $name = null)
    {
        $this->cacheCleanerManager = $cacheCleanerManager;
        parent::__construct($name);
    }
    /**
     * CacheCleanerShowCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription('This command will rollback the framework_assets_version'
                .' to its previous version.')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $newVersion = $this->cacheCleanerManager->getCurrentVersion();

        $output->writeln("Current cache version is <info>{$newVersion}</info>");

        return null;
    }

}
