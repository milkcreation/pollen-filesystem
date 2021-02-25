<?php

declare(strict_types=1);

namespace Pollen\Filesystem;

/**
 * @mixin \Pollen\Support\Concerns\ConfigBagAwareTrait
 * @mixin \Pollen\Support\Concerns\ContainerAwareTrait
 */
interface StorageManagerInterface
{
    /**
     * Ajout d'une instance de système de gestion de fichier.
     *
     * @param string|null $name
     * @param FilesystemInterface $disk
     *
     * @return StorageManagerInterface
     */
    public function addDisk(string $name, FilesystemInterface $disk): StorageManagerInterface;

    /**
     * Ajout d'une instance de système de gestion de fichier local.
     *
     * @param string|null $name
     * @param LocalFilesystemInterface $disk
     *
     * @return StorageManagerInterface
     */
    public function addLocalDisk(string $name, LocalFilesystemInterface $disk): StorageManagerInterface;

    /**
     * Création d'une instance d'adapter de système de fichiers local.
     *
     * @param string $root
     * @param array $config
     *
     * @return LocalFilesystemAdapterInterface
     */
    public function createLocalAdapter(string $root, array $config = []): LocalFilesystemAdapterInterface;

    /**
     * Récupération d'une instance de système de gestion de fichier.
     *
     * @param string|null $name
     *
     * @return FilesystemInterface|null
     */
    public function disk(?string $name = null): ?FilesystemInterface;

    /**
     * Récupération de l'instance de système de gestion de fichier déclaré par défaut.
     *
     * @return FilesystemInterface|null
     */
    public function getDefaultDisk(): ?FilesystemInterface;

    /**
     * Déclaration d'un système de fichiers local.
     *
     * @param string $name
     * @param string $root
     * @param array $config
     *
     * @return LocalFilesystemInterface
     */
    public function registerLocalDisk(string $name, string $root, array $config = []): LocalFilesystemInterface;

    /**
     * Déclaration d'un système de fichiers images local.
     *
     * @param string $name
     * @param string $root
     * @param array $config
     *
     * @return LocalImageFilesystemInterface
     */
    public function registerLocalImageDisk(string $name, string $root, array $config = []): LocalImageFilesystemInterface;

    /**
     * Définition de l'instance de système de gestion de fichier déclaré par défaut.
     *
     * @param FilesystemInterface $defaultDisk
     *
     * @return StorageManagerInterface
     */
    public function setDefaultDisk(FilesystemInterface $defaultDisk): StorageManagerInterface;
}