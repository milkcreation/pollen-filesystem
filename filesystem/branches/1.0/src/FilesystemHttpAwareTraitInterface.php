<?php

declare(strict_types=1);

namespace Pollen\Filesystem;

use Pollen\Http\BinaryFileResponseInterface;
use Pollen\Http\StreamedResponseInterface;
use Pollen\Support\Proxy\HttpRequestProxyInterface;

interface FilesystemHttpAwareTraitInterface extends HttpRequestProxyInterface
{
    /**
     * Génération de la réponse HTTP d'un fichier statique.
     *
     * @param string $path
     * @param string|null $name
     * @param array $headers
     * @param int $expires Délai d'expiration du cache en secondes. 1 an par défaut.
     * @param array $cache Paramètres complémentaire du cache.
     *
     * @return BinaryFileResponseInterface
     */
    public function binaryFileResponse(
        string $path,
        ?string $name = null,
        array $headers = [],
        int $expires = 31536000,
        array $cache = []
    ): BinaryFileResponseInterface;

    /**
     * Génération de la réponse HTTP de téléchargement d'un fichier.
     *
     * @param string $path
     * @param string|null $name
     * @param array|null $headers
     *
     * @return StreamedResponseInterface
     */
    public function downloadResponse(
        string $path,
        ?string $name = null,
        array $headers = []
    ): StreamedResponseInterface;

    /**
     * Récupération de l'url d'une ressource.
     *
     * @param string $path
     *
     * @return string|null
     */
    public function getUrl(string $path): ?string;

    /**
     * Génération de la réponse HTTP associée à un fichier.
     *
     * @param string $path
     * @param string|null $name
     * @param array|null $headers
     * @param string|null $disposition
     *
     * @return StreamedResponseInterface
     */
    public function response(
        string $path,
        ?string $name = null,
        array $headers = [],
        ?string $disposition = 'inline'
    ): StreamedResponseInterface;

    /**
     * Définition de l'url vers le répertoire racine du système de fichier.
     *
     * @param string $baseUrl
     *
     * @return FilesystemHttpAwareTrait
     */
    public function setBaseUrl(string $baseUrl): FilesystemHttpAwareTrait;
}