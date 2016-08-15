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
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CacheCleanerRollbackCommand
 * @package LeooTeam\CacheCleanerBundle\Command
 */
class CacheCleanerRollbackCommand extends Command
{
    const COMMAND_NAME = 'ccleaner:rollback';

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
     * CacheCleanerRollbackCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription('This command will rollback the framework_assets_version'
                .' to its previous version.')
            ->addOption(
                'cache-version-number',
                '-c',
                InputOption::VALUE_OPTIONAL,
                'Choose a number of version you want to skip.'
            )
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

        if ($input->getOption('cache-version-number')) {
            $newVersion = $input->getOption('cache-version-number');
            if ($newVersion[0] == '=') {
                $newVersion = substr($newVersion, 1);
            }
            $newVersion = $this->cacheCleanerManager->decrementVersion(-$newVersion);
        } else {
            $newVersion = $this->cacheCleanerManager->decrementVersion();
        }

        $output->writeln("Rollback asset_version from <info>$oldVersion</info>"
            . " to <info>{$newVersion}</info>");

        return null;
    }

}
