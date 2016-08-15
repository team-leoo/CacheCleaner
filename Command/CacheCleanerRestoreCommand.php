<?php
/**
 * @author: Aleksandr Daniloff <adaniloff.dev@gmail.com>
 */

/** Namespaces */
namespace LeooTeam\CacheCleanerBundle\Command;

/** Usages */
use LeooTeam\CacheCleanerBundle\Manager\CacheCleanerManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\CommandNotFoundException;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CacheCleanerRestoreCommand
 * @package LeooTeam\CacheCleanerBundle\Command
 */
class CacheCleanerRestoreCommand extends Command
{
    const COMMAND_NAME = 'ccleaner:restore';

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
     * CacheCleanerRestoreCommand configuration
     */
    protected function configure()
    {
        $this
            ->setName(self::COMMAND_NAME)
            ->setDescription('This command will restore the framework_assets_version'
                .' to a specific version.')
            ->addOption('cache-version', '-c', InputOption::VALUE_REQUIRED, 'Choose the id of the version.')
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
        $newVersion = $input->getOption('cache-version');

        if (is_null($newVersion)) {
            throw new InvalidArgumentException('Number of version is missing.');
        }

        if ($newVersion[0] == '=') {
            $newVersion = substr($newVersion, 1);
        }
        $newVersion = $this->cacheCleanerManager->restoreVersion($newVersion);

        $output->writeln("Restored asset_version from <info>$oldVersion</info>"
            . " to <info>{$newVersion}</info>");

        return null;
    }

}
