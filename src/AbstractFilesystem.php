<?php

declare(strict_types=1);

namespace Pollen\Filesystem;

use League\Flysystem\DirectoryListing;
use League\Flysystem\FileAttributes;
use League\Flysystem\Filesystem as BaseFilesystem;
use League\Flysystem\FilesystemException;
use League\Flysystem\PathNormalizer;
use League\Flysystem\StorageAttributes;
use RuntimeException;

/**
 * @mixin BaseFilesystem
 */
abstract class AbstractFilesystem implements FilesystemInterface
{
    use DelegateFilesystemAwareTrait;

    /**
     * @var FilesystemAdapterInterface
     */
    protected $adapter;

    /**
     * Instance du gestionnaire des systèmes de fichier.
     * @var StorageManagerInterface
     */
    protected $storageManager;

    /**
     * @param FilesystemAdapterInterface $adapter
     * @param array $config
     * @param PathNormalizer|null $pathNormalizer = null
     */
    public function __construct(
        FilesystemAdapterInterface $adapter,
        array $config = [],
        PathNormalizer $pathNormalizer = null
    ) {
        $this->adapter = $adapter;
        $this->delegateFilesystem = new BaseFilesystem($this->adapter, $config, $pathNormalizer);
    }

    /**
     * @inheritDoc
     */
    public function asDefault(): FilesystemInterface
    {
        if (!$this->storageManager instanceof StorageManagerInterface) {
            throw new RuntimeException('Unable to detect associated StoreManager');
        }
        $this->storageManager->setDefaultDisk($this);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function listContentsWithMimeType(string $location, bool $deep = self::LIST_SHALLOW): DirectoryListing
    {
        try {
            $listing = $this->listContents($location, $deep);

            $listing = $listing
                ->map(
                    function (StorageAttributes $attributes) {
                        if ($attributes instanceof FileAttributes) {
                            try {
                                $mimeType = $this->mimeType($attributes->path());

                                return new FileAttributes(
                                    $attributes->path(),
                                    $attributes->fileSize(),
                                    $attributes->visibility(),
                                    $attributes->lastModified(),
                                    $mimeType
                                );
                            } catch (FilesystemException $exception) {
                                return $attributes;
                            }
                        }
                        return $attributes;
                    }
                );
            return $listing;
        } catch (FilesystemException $e) {
            throw new RuntimeException('LocalFilesystem unable to list contents directory with mime');
        }
    }

    /**
     * @inheritDoc
     */
    public function setStoreManager(StorageManagerInterface $storageManager): FilesystemInterface
    {
        $this->storageManager = $storageManager;

        return $this;
    }
}