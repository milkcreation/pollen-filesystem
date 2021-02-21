<?php

declare(strict_types=1);

namespace Pollen\Filesystem;

interface LocalImageFilesystemInterface extends LocalFilesystemInterface
{
    /**
     * Récupération du rendu d'affichage d'une image.
     *
     * @param string $path Chemin relatif vers la ressource image.
     * @param array|null $attrs Liste des attributs de balise HTML. Si null, pas d'encapsulation (.svg uniquement).
     *
     * @return string
     */
    public function HtmlRender(string $path, ?array $attrs = []): ?string;

    /**
     * Récupération de la valeur de l''attribut src de la balise HTML img.
     *
     * @param string $path
     * @param bool $forceBase64
     *
     * @return string
     */
    public function getImgSrc(string $path, bool $forceBase64 = false): string;
}