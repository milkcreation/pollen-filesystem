<?php

declare(strict_types=1);

namespace Pollen\Filesystem;

use League\Flysystem\AdapterInterface;
use League\Flysystem\FileNotFoundException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

interface LocalFilesystemInterface extends FilesystemInterface
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
     * Génération de la réponse statique d'un fichier.
     *
     * @param string $path Chemin relatif vers un fichier du disque.
     * @param string|null $name Nom de qualification du fichier.
     * @param array|null $headers Liste des entêtes de la réponse.
     * @param int $expires Délai d'expiration du cache en secondes. 1 an par défaut.
     * @param array $cache Paramètres complémentaire du cache.
     *
     * @return BinaryFileResponse
     *
     * @throws FileNotFoundException
     * @see \Symfony\Component\HttpFoundation\BinaryFileResponse::setCache()
     *
     */
    public function binary(
        string $path,
        ?string $name = null,
        array $headers = [],
        int $expires = 31536000,
        array $cache = []
    ): BinaryFileResponse;

    /**
     * {@inheritDoc}
     *
     * @return LocalAdapter
     */
    public function getRealAdapter(): AdapterInterface;

    /**
     * Récupération du chemin absolu associé au chemin d'une ressource de destination.
     *
     * @param string $path Chemin relatif.
     *
     * @return string|null
     */
    public function path(string $path = '/'): ?string;

    /**
     * Récupération du chemin relatif associé au chemin d'une ressource de destination.
     *
     * @param string $path Chemin relatif.
     *
     * @return string|null
     */
    public function rel(string $path = '/'): ?string;

    /**
     * Récupération de l'url associée au chemin d'une ressource de destination.
     *
     * @param string $path Chemin relatif.
     *
     * @return string|null
     */
    public function url(string $path = '/'): ?string;
}