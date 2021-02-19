<?php

declare(strict_types=1);

namespace Pollen\Filesystem;

use League\Flysystem\AdapterInterface;
use Pollen\Container\BaseServiceProvider;

class FilesystemServiceProvider extends BaseServiceProvider
{
    /**
     * Liste des services fournis.
     * @var array
     */
    protected $provides = [
        FilesystemInterface::class,
        ImgAdapterInterface::class,
        ImgFilesystemInterface::class,
        LocalAdapterInterface::class,
        LocalFilesystemInterface::class,
        StorageManagerInterface::class,
    ];

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->getContainer()->share(StorageManagerInterface::class, function () {
            return new StorageManager([], $this->getContainer());
        });

        $this->registerAdapter();
        $this->registerFilesystem();
    }

    /**
     * @inheritDoc
     */
    public function registerAdapter(): void
    {
        $this->getContainer()->add(
            ImgAdapterInterface::class,
            function (string $root, int $writeFlags, int $linkHandling, array $permissions = []) {
                return new ImgAdapter($root, $writeFlags, $linkHandling, $permissions);
            }
        );

        $this->getContainer()->add(
            LocalAdapterInterface::class,
            function (string $root, int $writeFlags, int $linkHandling, array $permissions = []) {
                return new LocalAdapter($root, $writeFlags, $linkHandling, $permissions);
            }
        );
    }

    /**
     * @inheritDoc
     */
    public function registerFilesystem(): void
    {
        $this->getContainer()->add(FilesystemInterface::class, function (AdapterInterface $adapter) {
            return new Filesystem($adapter);
        });

        $this->getContainer()->add(ImgFilesystemInterface::class, function (string $root, array $config = []) {
            return new ImgFilesystem($this->getContainer()->get('storage')->imgAdapter($root, $config), [
                'disable_asserts' => true,
                'case_sensitive'  => true,
            ]);
        });

        $this->getContainer()->add(LocalFilesystemInterface::class, function (string $root, array $config = []) {
            return new LocalFilesystem($this->getContainer()->get('storage')->localAdapter($root, $config), [
                'disable_asserts' => true,
                'case_sensitive'  => true,
            ]);
        });
    }
}