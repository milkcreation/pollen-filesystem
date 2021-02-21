<?php

declare(strict_types=1);

namespace Pollen\Filesystem;

use League\Flysystem\DirectoryListing;
use League\Flysystem\FilesystemOperator;

interface FilesystemInterface extends FilesystemOperator
{
    /**
     * Affecte le système de fichier en tant que système par défaut dans le gestionnaire.
     *
     * @return static
     */
    public function asDefault(): FilesystemInterface;

    /**
     * Liste les fichiers d'un dossier en incluant les informations de type mime de chacun des fichier
     *
     * @param string $location
     * @param bool $deep
     *
     * @return DirectoryListing
     */
    public function listContentsWithMimeType(string $location, bool $deep = self::LIST_SHALLOW): DirectoryListing;

    /**
     * Définition du gestionnaire de systèmes de fichier associé.
     *
     * @param StorageManagerInterface $storageManager
     *
     * @return static
     */
    public function setStoreManager(StorageManagerInterface $storageManager): FilesystemInterface;
}