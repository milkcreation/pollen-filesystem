<?php

declare(strict_types=1);

namespace Pollen\Filesystem;

use Exception;
use Pollen\Support\HtmlAttrs;
//use Pollen\Support\MimeTypes;

class ImgFilesystem extends LocalFilesystem implements ImgFilesystemInterface
{
    /**
     * @inheritDoc
     */
    public function __invoke(string $path, array $attrs = []): ?string
    {
        return $this->render($path, $attrs);
    }

    /**
     * @inheritDoc
     */
    public function src(string $path): ?string
    {
        if ($this->has($path)) {
            try {
                $p = $this->path($path);
                $c = $this->read($path);

                if (MimeTypes::inType($p, ['svg', 'image'])) {
                    $m = mime_content_type($p);

                    return sprintf('data:%s;base64,%s', $m === 'image/svg' ? 'image/svg+xml' : $m, base64_encode($c));
                }
            } catch (Exception $e) {
                return null;
            }
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function render(string $path, ?array $attrs = null): ?string
    {
        if ($this->has($path)) {
            try {
                $filename = $this->path($path);
                $content = $this->read($path);

                if (MimeTypes::inType($filename, 'svg')) {
                    return is_null($attrs)
                        ? $content : '<div ' . HtmlAttrs::createFromAttrs(array_merge(['class' => ''], $attrs)) . '>' .
                            $content . '</div>';
                } elseif ($src = $this->src($path)) {
                     return '<img ' . HtmlAttrs::createFromAttrs(array_merge([
                             'alt'   => basename($filename)
                         ], $attrs ?? [], ['src' => $src])) . '/>';
                } else {
                    return null;
                }
            } catch (Exception $e) {
                return null;
            }
        }

        return null;
    }
}