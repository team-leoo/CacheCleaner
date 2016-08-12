<?php
/**
 * @author: Aleksandr Daniloff <adaniloff.dev@gmail.com>
 */

/** Namespaces */
namespace LeooTeam\CacheCleanerBundle\DependencyInjection;

/** Usages */
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class LeooTeamCacheCleanerExtension
 * @package LeooTeam\CacheCleanerBundle\DependencyInjection
 */
class LeooTeamCacheCleanerExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        
        if (!isset($config['commands'])) {
            $config['commands'] = array();
        }
        $container->setParameter('leoo_team_cache_cleaner.commands', $config['commands']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
}
