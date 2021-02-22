<?php

declare(strict_types=1);

namespace Pollen\Filesystem;

use InvalidArgumentException;
use League\Flysystem\AdapterInterface;
use League\Flysystem\Cached\CachedAdapter;
use League\Flysystem\Cached\CacheInterface;
use League\Flysystem\Cached\Storage\Memory as MemoryStore;
use League\Flysystem\FilesystemInterface;
use League\Flysystem\FilesystemNotFoundException;
use League\Flysystem\MountManager;
use Pollen\Support\Concerns\ConfigBagTrait;
use Pollen\Support\Concerns\ContainerAwareTrait;
use Psr\Container\ContainerInterface as Container;
use tiFy\Contracts\Kernel\Path;

class StorageManager extends MountManager implements StorageManagerInterface
{
    use ConfigBagTrait;
    use ContainerAwareTrait;

    /**
     * CONSTRUCTEUR.
     *
     * @param FilesystemInterface[] $filesystems [:prefix => Filesystem,]
     * @param Container|null $container Instance du conteneur d'injection de dépendances.
     *
     * @return void
     */
    public function __construct(array $filesystems = [], ?Container $container = null)
    {
        if (!is_null($container)) {
            $this->setContainer($container);
        }

        parent::__construct($filesystems);
    }

    /**
     * @inheritDoc
     */
    public function disk(?string $name = null): ?FilesystemContract
    {
        try {
            return is_null($name) ? $this->getDefault() : $this->getFilesystem($name);
        } catch (FilesystemNotFoundException $e) {
            return null;
        }
    }

    /**
     * @inheritDoc
     */
    public function getContainer(): ?Container
    {
        return $this->container;
    }

    /**
     * @inheritDoc
     */
    public function getDefault(): ?FilesystemContract
    {
        return $this->system();
    }

    /**
     * @inheritDoc
     */
    public function getFilesystem($prefix)
    {
        return parent::getFilesystem($prefix);
    }

    /**
     * @inheritDoc
     */
    public function img(string $root, array $config = []): ImgFilesystemContract
    {
        return $this->getContainer() && $this->getContainer()->has(ImgFilesystemContract::class)
            ? $this->getContainer()->get(ImgFilesystemContract::class, [$root, $config])
            : new ImgFilesystem($this->localAdapter($root, $config));
    }

    /**
     * @inheritDoc
     */
    public function imgAdapter(string $root, array $config = []): AdapterInterface
    {
        $permissions = $config['permissions'] ?? [];
        $links = ($config['links'] ?? null) === 'skip' ? ImgAdapter::SKIP_LINKS : ImgAdapter::DISALLOW_LINKS;

        $adapter = ($this->getContainer() && $this->getContainer()->has(ImgAdapterContract::class))
            ? $this->getContainer()->get(ImgAdapterContract::class, [$root, LOCK_EX, $links, $permissions])
            : new ImgAdapter($root, LOCK_EX, $links, $permissions);

        if ($cache = $config['cache'] ?? true) {
            $adapter = $cache instanceof CacheInterface
                ? new CachedAdapter($adapter, $cache)
                : new CachedAdapter($adapter, new MemoryStore());
        }

        return $adapter;
    }

    /**
     * @inheritDoc
     */
    public function local(string $root, array $config = []): LocalFilesystemContract
    {
        return $this->getContainer() && $this->getContainer()->has(LocalFilesystemContract::class)
            ? $this->getContainer()->get(LocalFilesystemContract::class, [$root, $config])
            : new LocalFilesystem($this->localAdapter($root, $config));
    }

    /**
     * @inheritDoc
     */
    public function localAdapter(string $root, array $config = []): AdapterInterface
    {
        $permissions = $config['permissions'] ?? [];
        $links = ($config['links'] ?? null) === 'skip' ? LocalAdapter::SKIP_LINKS : LocalAdapter::DISALLOW_LINKS;

        $adapter = ($this->getContainer() && $this->getContainer()->has(LocalAdapterContract::class))
            ? $this->getContainer()->get(LocalAdapterContract::class, [$root, LOCK_EX, $links, $permissions])
            : new LocalAdapter($root, LOCK_EX, $links, $permissions);

        if ($cache = $config['cache'] ?? true) {
            $adapter = $cache instanceof CacheInterface
                ? new CachedAdapter($adapter, $cache)
                : new CachedAdapter($adapter, new MemoryStore());
        }

        return $adapter;
    }

    /**
     * @inheritDoc
     */
    public function mountFilesystem($name, FilesystemInterface $filesystem)
    {
        if ($filesystem instanceof FilesystemContract) {
            return parent::mountFilesystem($name, $filesystem);
        }

        throw new InvalidArgumentException(sprintf(
            __(
                'Impossible de monter le disque [%s]. Le gestionnaire de fichiers doit une instance de [%s].',
                'tify'
            ), $name, FilesystemContract::class)
        );
    }

    /**
     * @inheritDoc
     */
    public function register(string $name, $attrs): ?FilesystemContract
    {
        if ($attrs instanceof Filesystem) {
            $filesystem = $attrs;
        } elseif (is_array($attrs) || is_string($attrs)) {
            $filesystem = $this->registerLocal($name, $attrs);
        } else {
            throw new InvalidArgumentException(sprintf(
                __('Les arguments fournis ne permettent pas de définir le système de fichiers [%s].', 'tify'), $name
            ));
        }

        return $this->set($name, $filesystem)->disk($name);
    }

    /**
     * @inheritDoc
     */
    public function registerImg(string $name, $attrs): ?FilesystemContract
    {
        if ($attrs instanceof ImgFilesystemContract) {
            $filesystem = $attrs;
        } elseif (is_array($attrs)) {
            $filesystem = $this->img($attrs['root'] ?? '', $attrs);
        } elseif (is_string($attrs)) {
            $filesystem = $this->img($attrs);
        } else {
            throw new InvalidArgumentException(sprintf(
                __('Impossible de déclarer le système de fichiers image [%s].', 'tify'), $name
            ));
        }

        return $this->register($name, $filesystem);
    }

    /**
     * @inheritDoc
     */
    public function registerLocal(string $name, $attrs): ?FilesystemContract
    {
        if ($attrs instanceof LocalFilesystemContract) {
            $filesystem = $attrs;
        } elseif (is_array($attrs)) {
            $filesystem = $this->local($attrs['root'] ?? '', $attrs);
        } elseif (is_string($attrs)) {
            $filesystem = $this->local($attrs);
        } else {
            throw new InvalidArgumentException(sprintf(
                __('Impossible de déclarer le système de fichiers local [%s].', 'tify'), $name
            ));
        }

        return $this->register($name, $filesystem);
    }

    /**
     * @inheritDoc
     */
    public function set(string $name, FilesystemContract $filesystem): StorageManagerContract
    {
        return $this->mountFilesystem($name, $filesystem);
    }

    /**
     * @inheritDoc
     */
    public function setContainer(Container $container): StorageManagerContract
    {
        $this->container = $container;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function system(?string $name = null): ?LocalFilesystemContract
    {
        /** @var Path $path */
        return ($path = $this->container->get('path')) instanceof Path ? $path->disk($name) : null;
    }
}