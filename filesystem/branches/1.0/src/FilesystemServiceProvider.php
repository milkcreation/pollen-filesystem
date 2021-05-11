<?php

declare(strict_types=1);

namespace Pollen\Filesystem;

use Pollen\Container\BootableServiceProvider;

class FilesystemServiceProvider extends BootableServiceProvider
{
    /**
     * Liste des services fournis.
     * @var array
     */
    protected $provides = [
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
    }
}