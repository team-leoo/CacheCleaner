<?php
/**
 * @author: Aleksandr Daniloff <adaniloff.dev@gmail.com>
 */

/** Namespaces */
namespace LeooTeam\CacheCleanerBundle\DependencyInjection;

/** Usages */
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package LeooTeam\CacheCleanerBundle\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('leoo_team_cache_cleaner');
        $rootNode
            ->children()
                ->variableNode('commands')
            ->end();

        return $treeBuilder;
    }
}
