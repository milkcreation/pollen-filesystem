<?php

declare(strict_types=1);

namespace Pollen\Filesystem;

use League\Flysystem\Local\LocalFilesystemAdapter as BaseLocalFilesystemAdapter;
use League\Flysystem\PathPrefixer;
use League\Flysystem\UnixVisibility\VisibilityConverter;
use League\MimeTypeDetection\MimeTypeDetector;

class LocalFilesystemAdapter extends AbstractFilesystemAdapter implements LocalFilesystemAdapterInterface
{
    /**
     * @var PathPrefixer
     */
    protected $dedicatedPrefixer;

    /**
     * @param string $location
     * @param VisibilityConverter|null $visibility
     * @param int $writeFlags
     * @param int $linkHandling
     * @param MimeTypeDetector|null $mimeTypeDetector
     */
    public function __construct(
        string $location,
        VisibilityConverter $visibility = null,
        int $writeFlags = LOCK_EX,
        int $linkHandling = BaseLocalFilesystemAdapter::DISALLOW_LINKS,
        MimeTypeDetector $mimeTypeDetector = null
    ) {
        $this->delegateAdapter = new BaseLocalFilesystemAdapter(
            $location,
            $visibility,
            $writeFlags,
            $linkHandling,
            $mimeTypeDetector
        );

        $this->dedicatedPrefixer = new PathPrefixer($location, DIRECTORY_SEPARATOR);
    }

    /**
     * @inheritDoc
     */
    public function getAbsolutePath(string $path = '/'): string
    {
        return $this->dedicatedPrefixer->prefixPath($path);
    }
}