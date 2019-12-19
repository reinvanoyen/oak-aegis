<?php

namespace ReinVanOyen\OakAegis\Loader;

use Aegis\Contracts\LoaderInterface;
use Oak\Contracts\Filesystem\FilesystemInterface;

class FilesystemLoader implements LoaderInterface
{
    /**
     * @var FilesystemInterface $filesystem
     */
    private $filesystem;

    /**
     * @var string $directory
     */
    private $directory;

    /**
     * @var string $cacheDirectory
     */
    private $cacheDirectory;

    /**
     * FilesystemLoader constructor.
     * @param FilesystemInterface $filesystem
     */
    public function __construct(FilesystemInterface $filesystem, string $directory, string $cacheDirectory)
    {
        $this->filesystem = $filesystem;
        $this->directory  = $directory;
        $this->cacheDirectory  = $cacheDirectory;
    }

    /**
     * @param string $key
     * @return string
     */
    public function get(string $key): string
    {
        return $this->filesystem->get($this->directory.'/'.$key.'.tpl');
    }

    /**
     * @param string $key
     * @return bool
     */
    public function isCached(string $key): bool
    {
        $filename = $this->directory.'/'.$key.'.tpl';
        $cacheFilename = $this->cacheDirectory.'/'.md5($key).'.tpl.php';

        return false;
        return (
            $this->filesystem->exists($cacheFilename) ||
            $this->filesystem->modificationTime($filename) >= $this->filesystem->modificationTime($cacheFilename)
        );
    }

    /**
     * @param string $key
     * @return string
     */
    public function getCache(string $key): string
    {
        $cacheFilename = $this->cacheDirectory.'/'.md5($key).'.tpl.php';

        return $this->filesystem->get($cacheFilename);
    }

    /**
     * @param string $key
     * @param string $contents
     * @return mixed|void
     */
    public function setCache(string $key, string $contents)
    {
        $cacheFilename = $this->cacheDirectory.'/'.md5($key).'.tpl.php';

        $this->filesystem->put($cacheFilename, $contents);
    }

    /**
     * @param string $key
     * @return mixed|void
     */
    public function getCacheKey(string $key)
    {
        return $this->cacheDirectory.'/'.md5($key).'.tpl.php';
    }
}