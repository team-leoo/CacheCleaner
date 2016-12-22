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
 */
class CacheCleanerManager
{
    const HISTORY_LIMIT = 20;

    /** @var string $fileName */
    private $fileName;
    
    /** @var string $filePath */
    private $filePath;
    
    /** @var array $config */
    private $config;

    /**
     * CacheCleanerManager constructor.
     * Initialize $config
     */
    public function __construct()
    {
        $this->fileName = 'versions.yml';
        $this->filePath = __DIR__ . '/../Resources/config';
        $this->getConfig();
    }

    /**
     * @param bool $dirOnly
     * @return string
     */
    public function getFilePath($dirOnly = false)
    {
        if (false == $dirOnly) {
            return $this->filePath . '/' . $this->fileName;
        }

        return $this->filePath;
    }
    
    /**
     * @return string
     */
    public function listPreviousVersions()
    {
        $versions = $this->getConfig()['leoo_team_cache_cleaner']['previous'];
        krsort($versions);
        return print_r($versions, true);
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
     * @param bool $savePrevious
     * @return string
     */
    public function setCurrentVersion($version, $savePrevious = true)
    {
        if (true === $savePrevious) {
            $this->setPreviousVersion();
        }
        $config = $this->getConfig();
        $config['framework']['assets']['version'] = (int)$version;

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
     * @param int $value
     * @return string
     */
    public function restoreVersion($value)
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
        
        if (null !== $version) {
            if ($version < 0 and count($config['leoo_team_cache_cleaner']['previous']) > -$version) {
                $config['leoo_team_cache_cleaner']['previous'] = array_slice(
                    $config['leoo_team_cache_cleaner']['previous'],
                    0,
                    count($config['leoo_team_cache_cleaner']['previous']) + $version + 1
                );
            } elseif (false !== ($key = array_search($version, $config['leoo_team_cache_cleaner']['previous']))) {
                $config['leoo_team_cache_cleaner']['previous'] = array_slice(
                    $config['leoo_team_cache_cleaner']['previous'],
                    0,
                    $key + 1
                );
            }
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
        $config['leoo_team_cache_cleaner']['previous'][] = (int)$config['framework']['assets']['version'];
        if (count($config['leoo_team_cache_cleaner']['previous']) >= self::HISTORY_LIMIT) {
            $config['leoo_team_cache_cleaner']['previous'] = array_slice(
                $config['leoo_team_cache_cleaner']['previous'],
                count($config['leoo_team_cache_cleaner']['previous']) - self::HISTORY_LIMIT,
                self::HISTORY_LIMIT
            );
        }

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
        if (file_exists($this->getFilePath())) {
            $config = Yaml::parse(file_get_contents($this->getFilePath()));
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
        file_put_contents($this->getFilePath(), Yaml::dump($this->config));

        return $this;
    }
}