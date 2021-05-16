<?php

declare(strict_types=1);

namespace Pollen\Filesystem;

use League\Flysystem\FilesystemException;
use SplFileInfo;

class LocalFilesystem extends AbstractFilesystem implements LocalFilesystemInterface
{
    use FilesystemHttpAwareTrait;

    /**
     * @var LocalFilesystemAdapterInterface
     */
    protected $adapter;

    /**
     * @inheritDoc
     */
    public function __invoke(string $path): ?string
    {
        try {
            if ($this->fileExists($path)) {
                return $this->read($path);
            }
            return null;
        } catch (FilesystemException $exception) {
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public function getAbsolutePath(string $path = '/'): string
    {
        return $this->adapter->getAbsolutePath($path);
    }

    /**
     * @inheritDoc
     */
    public function getSplFileInfo(string $path = '/'): SplFileInfo
    {
        return $this->adapter->getSplFileInfo($path);
    }
}