<?php

declare(strict_types=1);

namespace Pollen\Filesystem;

use League\Flysystem\FilesystemAdapter as BaseFilesystemAdapterInterface;
use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\StorageAttributes;

interface FilesystemAdapterInterface extends BaseFilesystemAdapterInterface
{
    /**
     * Récupération des attributs d'une ressource.
     *
     * @param string $path
     *
     * @return StorageAttributes|DirectoryAttributes|FileAttributes
     */
    public function getStorageAttributes(string $path = '/'): StorageAttributes;
}