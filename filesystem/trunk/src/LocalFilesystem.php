<?php

declare(strict_types=1);

namespace Pollen\Filesystem;

use Exception;
use League\Flysystem\AdapterInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Pollen\Support\DateTime;
use Pollen\Support\Str;

//use tiFy\Support\Proxy\Url;

class LocalFilesystem extends Filesystem implements LocalFilesystemInterface
{
    /**
     * @inheritDoc
     */
    public function __invoke(string $path): ?string
    {
        if ($this->has($path)) {
            try {
                if ($this->getMimetype($path) !== 'dir') {
                    return $this->read($path);
                }
            } catch (Exception $e) {
                return '';
            }
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function binary(
        string $path,
        ?string $name = null,
        array $headers = [],
        int $expires = 31536000,
        array $cache = []
    ): BinaryFileResponse {
        BinaryFileResponse::trustXSendfileTypeHeader();
        $response = new BinaryFileResponse($this->path($path));
        $filename = $name ?? basename($path);

        $disposition = $response->headers->makeDisposition('inline', $filename, Str::ascii($name));

        $response->headers->replace(
            [
                'Content-Type'        => $this->getMimeType($path),
                'Content-Length'      => $this->getSize($path),
                'Content-Disposition' => $disposition,
            ] + $headers
        );

        $response->setCache(
            array_merge(
                [
                    'last_modified' => (new DateTime())->setTimestamp($this->getTimestamp($path)),
                    's_maxage'      => $expires,
                ],
                $cache
            )
        );

        $response->setExpires((new DateTime())->modify("+ {$expires} seconds"));

        return $response;
    }

    /**
     * {@inheritDoc}
     *
     * @return LocalAdapter
     */
    public function getRealAdapter(): AdapterInterface
    {
        return parent::getRealAdapter();
    }

    /**
     * @inheritDoc
     */
    public function path(string $path = '/'): ?string
    {
        $adapter = $this->getRealAdapter();

        return $adapter->applyPathPrefix($path);
    }

    /**
     * @inheritDoc
     */
    public function rel(string $path = '/'): ?string
    {
        if (($dir = $this->path($path))) {
            return ($rp = realpath($dir))
                ? '/' . ltrim(
                    preg_replace('/^' . preg_quote(/*getcwd()*/ ROOT_PATH, DIRECTORY_SEPARATOR) . '/', '', $rp),
                    '/'
                )
                : null;
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function url(string $path = '/'): ?string
    {
        if ($rel = $this->rel($path)) {
            return Url::root($rel)->render();
        }
        return null;
    }
}