<?php
/**
 * @author: Aleksandr Daniloff <adaniloff.dev@gmail.com>
 */

/** Namespaces */
namespace LeooTeam\CacheCleanerBundle\Command;

/** Usages */
use LeooTeam\CacheCleanerBundle\Manager\CacheCleanerManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CacheCleanerUpdateCommand
 * @package LeooTeam\CacheCleanerBundle\Command
 */
class CacheCleanerUpdateCommand extends Command
{
    const COMMAND_NAME = 'ccleaner:update';

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
     * CacheCleanerUpdateCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription('This command will change the framework_assets_version'
                .', making the client reload its cache')
            ->addOption('cache-version', '-c', InputOption::VALUE_OPTIONAL, 'Choose a specific version.')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $oldVersion = $this->cacheCleanerManager->getCurrentVersion();
        
        if ($input->getOption('cache-version')) {
            $newVersion = $input->getOption('cache-version');
            if ($newVersion[0] == '=') {
                $newVersion = substr($newVersion, 1);
            }
            if (!is_numeric($newVersion)) {
                throw new InvalidArgumentException('Version must be an integer.');
            }
            $newVersion = $this->cacheCleanerManager->setCurrentVersion($newVersion);
        } else {
            $newVersion = $this->cacheCleanerManager->incrementVersion();
        }

        $output->writeln("Updating asset_version from <info>$oldVersion</info>"
            . " to <info>{$newVersion}</info>");
        
        return null;
    }
}
