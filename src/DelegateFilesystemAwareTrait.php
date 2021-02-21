<?php

declare(strict_types=1);

namespace Pollen\Filesystem;

use League\Flysystem\DirectoryListing;
use League\Flysystem\Filesystem as DelegateFilesystem;
use League\Flysystem\FilesystemException;

trait DelegateFilesystemAwareTrait
{
    /**
     * Instance du système de fichier de délégation.
     * @var DelegateFilesystem
     */
    protected $delegateFilesystem;

    /**
     * @param string $location
     *
     * @return bool
     * @throws FilesystemException
     */
    public function fileExists(string $location): bool
    {
        return $this->delegateFilesystem->fileExists($location);
    }

    /**
     * @param string $location
     * @param string $contents
     * @param array $config
     *
     * @throws FilesystemException
     */
    public function write(string $location, string $contents, array $config = []): void
    {
        $this->delegateFilesystem->write($location, $contents, $config);
    }

    /**
     * @param string $location
     * @param $contents
     * @param array $config
     *
     * @throws FilesystemException
     */
    public function writeStream(string $location, $contents, array $config = []): void
    {
        $this->delegateFilesystem->writeStream($location, $contents, $config);
    }

    /**
     * @param string $location
     *
     * @return string
     * @throws FilesystemException
     */
    public function read(string $location): string
    {
        return $this->delegateFilesystem->read($location);
    }

    /**
     * @param string $location
     *
     * @return resource
     *
     * @throws FilesystemException
     */
    public function readStream(string $location)
    {
        return $this->delegateFilesystem->readStream($location);
    }

    /**
     * @param string $location
     *
     * @throws FilesystemException
     */
    public function delete(string $location): void
    {
        $this->delegateFilesystem->delete($location);
    }

    /**
     * @param string $location
     *
     * @throws FilesystemException
     */
    public function deleteDirectory(string $location): void
    {
        $this->delegateFilesystem->deleteDirectory($location);
    }

    /**
     * @param string $location
     * @param array $config
     *
     * @throws FilesystemException
     */
    public function createDirectory(string $location, array $config = []): void
    {
        $this->delegateFilesystem->createDirectory($location, $config);
    }

    /**
     * @param string $location
     * @param bool $deep
     *
     * @return DirectoryListing
     *
     * @throws FilesystemException
     */
    public function listContents(string $location, bool $deep = self::LIST_SHALLOW): DirectoryListing
    {
        return $this->delegateFilesystem->listContents($location, $deep);
    }

    /**
     * @param string $source
     * @param string $destination
     * @param array $config
     *
     * @throws FilesystemException
     */
    public function move(string $source, string $destination, array $config = []): void
    {
        $this->delegateFilesystem->move($source,$destination, $config);
    }

    /**
     * @param string $source
     * @param string $destination
     * @param array $config
     *
     * @throws FilesystemException
     */
    public function copy(string $source, string $destination, array $config = []): void
    {
        $this->delegateFilesystem->copy($source,$destination, $config);
    }

    /**
     * @param string $path
     *
     * @return int
     *
     * @throws FilesystemException
     */
    public function lastModified(string $path): int
    {
        return $this->delegateFilesystem->lastModified($path);
    }

    /**
     * @param string $path
     *
     * @return int
     *
     * @throws FilesystemException
     */
    public function fileSize(string $path): int
    {
        return $this->delegateFilesystem->fileSize($path);
    }

    /**
     * @param string $path
     *
     * @return string
     *
     * @throws FilesystemException
     */
    public function mimeType(string $path): string
    {
        return $this->delegateFilesystem->mimeType($path);
    }

    /**
     * @param string $path
     * @param string $visibility
     *
     * @throws FilesystemException
     */
    public function setVisibility(string $path, string $visibility): void
    {
        $this->delegateFilesystem->setVisibility($path, $visibility);
    }

    /**
     * @param string $path
     *
     * @return string
     *
     * @throws FilesystemException
     */
    public function visibility(string $path): string
    {
        return $this->delegateFilesystem->visibility($path);
    }
}