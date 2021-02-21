<?php

declare(strict_types=1);

namespace Pollen\Filesystem;

interface LocalFilesystemAdapterInterface extends FilesystemAdapterInterface
{
    /**
     * Récupération du chemin absolu vers une ressource.
     *
     * @param string $path
     *
     * @return string
     */
    public function getAbsolutePath(string $path = '/'): string;
}