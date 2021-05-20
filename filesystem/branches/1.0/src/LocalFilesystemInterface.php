<?php

declare(strict_types=1);

namespace Pollen\Filesystem;

use League\Flysystem\DirectoryAttributes;
use League\Flysystem\FileAttributes;
use League\Flysystem\StorageAttributes;
use SplFileInfo;

interface LocalFilesystemInterface extends FilesystemInterface, FilesystemHttpAwareTraitInterface
{
    /**
     * Récupération du contenu d'un fichier.
     *
     * @param string $path Chemin relatif vers le fichier.
     *
     * @return string|null
     */
    public function __invoke(string $path): ?string;

    /**
     * Récupération du chemin absolu vers une ressource.
     *
     * @param string $path
     *
     * @return string
     */
    public function getAbsolutePath(string $path = '/'): string;

    /**
     * Récupération de l'instance SplFileInfo d'une ressource.
     *
     * @param string $path
     *
     * @return SplFileInfo
     */
    public function getSplFileInfo(string $path = '/'): SplFileInfo;

    /**
     * Récupération des attributs d'une ressource.
     *
     * @param string $path
     *
     * @return StorageAttributes|DirectoryAttributes|FileAttributes
     */
    public function getStorageAttributes(string $path = '/'): StorageAttributes;
}