<?php

declare(strict_types=1);

namespace Pollen\Filesystem;

class AbstractFilesystemAdapter implements FilesystemAdapterInterface
{
    use DelegateFilesystemAdapterAwareTrait;
}