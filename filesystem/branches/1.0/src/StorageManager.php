<?php

declare(strict_types=1);

namespace Pollen\Filesystem;

use League\Flysystem\Local\LocalFilesystemAdapter as BaseLocalFilesystemAdapter;
use League\Flysystem\UnixVisibility\PortableVisibilityConverter;
use League\Flysystem\UnixVisibility\VisibilityConverter;
use Pollen\Support\Concerns\ConfigBagTrait;
use Pollen\Support\Concerns\ContainerAwareTrait;
use Psr\Container\ContainerInterface as Container;
use RuntimeException;

class StorageManager implements StorageManagerInterface
{
    use ConfigBagTrait;
    use ContainerAwareTrait;

    /**
     * @var FilesystemInterface[]|array
     */
    private $disks = [];

    /**
     * @var FilesystemInterface|null
     */
    private $defaultDisk;

    /**
     * @param array $config
     * @param Container|null $container
     *
     * @return void
     */
    public function __construct(array $config = [], ?Container $container = null)
    {
        $this->setConfig($config);

        if (!is_null($container)) {
            $this->setContainer($container);
        }
    }

    /**
     * @inheritDoc
     */
    public function addDisk(string $name, FilesystemInterface $disk): StorageManagerInterface
    {
        $this->disks[$name] = $disk->setStoreManager($this);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function addLocalDisk(string $name, LocalFilesystemInterface $disk): StorageManagerInterface
    {
        return $this->addDisk($name, $disk);
    }

    /**
     * @inheritDoc
     */
    public function createLocalAdapter(string $root, array $config = []): LocalFilesystemAdapterInterface
    {
        $visibility = $config['visibility'] ?? null;
        if (!$visibility instanceof VisibilityConverter) {
            $visibility = is_array($visibility)? PortableVisibilityConverter::fromArray($visibility) : null;
        }

        $writeFlags = (int)($config['write_flags'] ?? LOCK_EX);

        $linkHandling = ($config['links'] ?? null) === 'skip'
            ? BaseLocalFilesystemAdapter::SKIP_LINKS : BaseLocalFilesystemAdapter::DISALLOW_LINKS;

        return new LocalFilesystemAdapter($root, $visibility, $writeFlags, $linkHandling);
    }

    /**
     * @inheritDoc
     */
    public function disk(?string $name = null): ?FilesystemInterface
    {
        if ($name === null) {
            return $this->getDefaultDisk();
        }

        return $this->disks[$name] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getDefaultDisk(): ?FilesystemInterface
    {
        return $this->defaultDisk;
    }

    /**
     * @inheritDoc
     */
    public function registerLocalDisk(string $name, string $root, array $config = []): LocalFilesystemInterface
    {
        $adapter = $this->createLocalAdapter($root, $config);
        $disk = new LocalFilesystem($adapter);

        $this->addLocalDisk($name, $disk);

        $exists = $this->disk($name);
        if ($exists instanceof LocalFilesystemInterface) {
            return $exists;
        }
        throw new RuntimeException(sprintf('StorageManager unable to register local disk [%s]', $name));
    }

    /**
     * @inheritDoc
     */
    public function registerLocalImageDisk(string $name, string $root, array $config = []): LocalImageFilesystemInterface
    {
        $adapter = $this->createLocalAdapter($root, $config);
        $disk = new LocalImageFilesystem($adapter);

        $this->addLocalDisk($name, $disk);

        $exists = $this->disk($name);
        if ($exists instanceof LocalImageFilesystemInterface) {
            return $exists;
        }
        throw new RuntimeException(sprintf('StorageManager unable to register local image disk [%s]', $name));
    }

    /**
     * @inheritDoc
     */
    public function setDefaultDisk(FilesystemInterface $defaultDisk): StorageManagerInterface
    {
        $this->defaultDisk = $defaultDisk;

        return $this;
    }
}