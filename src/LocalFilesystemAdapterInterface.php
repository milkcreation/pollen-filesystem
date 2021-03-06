<?php

declare(strict_types=1);

namespace Pollen\Filesystem;

use SplFileInfo;

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

    /**
     * Récupération de l'instance SplFileInfo d'une ressource.
     *
     * @param string $path
     *
     * @return SplFileInfo
     */
    public function getSplFileInfo(string $path = '/'): SplFileInfo;
}