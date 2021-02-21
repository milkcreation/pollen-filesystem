<?php

declare(strict_types=1);

namespace Pollen\Filesystem;

use League\Flysystem\FilesystemException;

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
}