<?php

declare(strict_types=1);

namespace Pollen\Filesystem;

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
}