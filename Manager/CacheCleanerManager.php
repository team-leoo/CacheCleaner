<?php
/**
 * @author: Aleksandr Daniloff <adaniloff.dev@gmail.com>
 */

/** Namespaces */
namespace LeooTeam\CacheCleanerBundle\Manager;

/** Usages */
use Symfony\Component\Yaml\Yaml;

/**
 * Class CacheCleanerManager
 * @package LeooTeam\CacheCleanerBundle\Manager
 * @todo: do not keep more than 20 previous version in versions.yml
 */
class CacheCleanerManager
{
    const FILE_PATH = __DIR__ . '/../Resources/config';
    const FILE_NAME = 'versions.yml';

    /** @var array $config */
    private $config;

    /**
     * CacheCleanerManager constructor.
     * Initialize $config
     */
    public function __construct()
    {
        $this->getConfig();
    }

    /**
     * @return string
     */
    public function getCurrentVersion()
    {
        return $this->getConfig()['framework']['assets']['version'];
    }

    /**
     * @param null $version
     * @return string
     */
    public function setCurrentVersion($version, $savePrevious = true)
    {
        if (true === $savePrevious) {
            $this->setPreviousVersion();
        }
        $config = $this->getConfig();
        $config['framework']['assets']['version'] = $version;

        $this->setConfig($config)->persistConfig();
        
        return $this->getCurrentVersion();
    }

    /**
     * @param int $value
     * @return string
     */
    public function incrementVersion($value = 1)
    {
        $this->setCurrentVersion((int)$this->getCurrentVersion() + (int)$value);
        
        return $this->getCurrentVersion();
    }
    
    /**
     * @param int $value
     * @return string
     */
    public function decrementVersion($value = -1)
    {
        $this->setCurrentVersion((int)$this->getPreviousVersion($value), false);

        return $this->getCurrentVersion();
    }

    /**
     * @param null|integer $version
     * @return string
     */
    private function getPreviousVersion($version = null)
    {
        $config = $this->getConfig();
        
        if (null !== $version and $key = array_search($version, $config['leoo_team_cache_cleaner']['previous'])) {
            $config['leoo_team_cache_cleaner']['previous'] = array_slice(
                $config['leoo_team_cache_cleaner']['previous'],
                0,
                $key + 1
            );
        }

        if (0 < count($config['leoo_team_cache_cleaner']['previous'])) {
            $versionValue = array_pop($config['leoo_team_cache_cleaner']['previous']);
        }
        
        if (isset($versionValue)) {
            $this->setConfig($config)->persistConfig();
            return $versionValue;
        }
        
        return null;
    }

    /**
     * @return CacheCleanerManager
     */
    private function setPreviousVersion()
    {
        $config = $this->getConfig();
        $config['leoo_team_cache_cleaner']['previous'][] = $config['framework']['assets']['version'];

        return $this->setConfig($config);
    }

    /**
     * @return array
     */
    private function getConfig()
    {
        if (null !== $this->config) {
            return $this->config;
        }
        
        /** @var array $config */
        if (file_exists(self::FILE_PATH . '/' . self::FILE_NAME)) {
            $config = Yaml::parse(file_get_contents(self::FILE_PATH . '/' . self::FILE_NAME));
        }
        if (!isset($config['framework'])
            or !isset($config['framework']['assets'])
            or !isset($config['framework']['assets']['version'])
        ) {
            $config = array(
                'framework' => array('assets' => array('version' => 0)),
                'leoo_team_cache_cleaner' => array('previous' => array()),
            );
        }

        return $this->setConfig($config);
    }

    /**
     * @param $config
     * @return CacheCleanerManager
     */
    private function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return CacheCleanerManager
     */
    private function persistConfig()
    {
        file_put_contents(self::FILE_PATH . '/' . self::FILE_NAME, Yaml::dump($this->config));

        return $this;
    }
}