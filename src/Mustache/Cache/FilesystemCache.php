<?php

/*
 * This file is part of Mustache.php.
 *
 * (c) 2010-2014 Justin Hileman
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Mustache Cache filesystem implementation.
 *
 * A FilesystemCache instance caches Mustache Template classes from the filesystem by name:
 *
 *     $cache = new Mustache_Cache_FilesystemCache(dirname(__FILE__).'/cache');
 *     $cache->cache($className, $compiledSource);
 *
 * The FilesystemCache benefits from any opcode caching that may be setup in your environment. So do that, k?
 */
namespace Mustache\Cache;

use Mustache\Exception;
use Mustache\Logger;

class FilesystemCache extends AbstractCache
{
    private $baseDir;
    private $fileMode;

    /**
     * Filesystem cache constructor.
     *
     * @param string $baseDir Directory for compiled templates.
     * @param int $fileMode Override default permissions for cache files. Defaults to using the system-defined umask.
     */
    public function __construct($baseDir, $fileMode = null)
    {
        $this->baseDir = $baseDir;
        $this->fileMode = $fileMode;
    }

    /**
     * Load the class from cache using `require_once`.
     *
     * @param string $key
     *
     * @return bool
     */
    public function load($key)
    {
        $fileName = $this->getCacheFilename($key);
        if (!is_file($fileName)) {
            return false;
        }

        require_once $fileName;

        return true;
    }

    /**
     * Cache and load the compiled class.
     *
     * @param string $key
     * @param string $value
     */
    public function cache($key, $value)
    {
        $fileName = $this->getCacheFilename($key);

        $this->log(
            Logger::DEBUG,
            'Writing to template cache: "{fileName}"',
            array('fileName' => $fileName)
        );

        $this->writeFile($fileName, $value);
        $this->load($key);
    }

    /**
     * Build the cache filename.
     * Subclasses should override for custom cache directory structures.
     *
     * @param string $name
     *
     * @return string
     */
    protected function getCacheFilename($name)
    {
        return sprintf('%s/%s.php', $this->baseDir, $name);
    }

    /**
     * Create cache directory.
     * @param $fileName
     * @throws Exception\MustacheRuntimeException
     * @return string
     */
    private function buildDirectoryForFilename($fileName)
    {
        $dirName = dirname($fileName);
        if (!is_dir($dirName)) {
            $this->log(
                Logger::INFO,
                'Creating Mustache template cache directory: "{dirName}"',
                array('dirName' => $dirName)
            );

            @mkdir($dirName, 0777, true);
            if (!is_dir($dirName)) {
                throw new Exception\MustacheRuntimeException(sprintf('Failed to create cache directory "%s".', $dirName));
            }
        }

        return $dirName;
    }

    /**
     * Write cache file.
     *
     * @throws Mustache_Exception_RuntimeException If unable to write file
     *
     * @param string $fileName
     * @param string $value
     */
    private function writeFile($fileName, $value)
    {
        $dirName = $this->buildDirectoryForFilename($fileName);

        $this->log(
            Logger::DEBUG,
            'Caching compiled template to "{fileName}"',
            array('fileName' => $fileName)
        );

        $tempFile = tempnam($dirName, basename($fileName));
        if (false !== @file_put_contents($tempFile, $value)) {
            if (@rename($tempFile, $fileName)) {
                $mode = isset($this->fileMode) ? $this->fileMode : (0666 & ~umask());
                @chmod($fileName, $mode);

                return;
            }

            $this->log(
                Logger::ERROR,
                'Unable to rename Mustache temp cache file: "{tempName}" -> "{fileName}"',
                array('tempName' => $tempFile, 'fileName' => $fileName)
            );
        }

        throw new Exception\MustacheRuntimeException(sprintf('Failed to write cache file "%s".', $fileName));
    }
}
