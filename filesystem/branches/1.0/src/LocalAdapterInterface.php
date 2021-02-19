<?php

declare(strict_types=1);

namespace Pollen\Filesystem;

use League\Flysystem\AdapterInterface;
use League\Flysystem\Adapter\Local;

/**
 * @mixin Local
 */
interface LocalAdapterInterface extends AdapterInterface
{

}