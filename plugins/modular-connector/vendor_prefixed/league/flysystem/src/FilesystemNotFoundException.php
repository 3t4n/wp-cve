<?php

namespace Modular\ConnectorDependencies\League\Flysystem;

use LogicException;
/**
 * Thrown when the MountManager cannot find a filesystem.
 * @internal
 */
class FilesystemNotFoundException extends LogicException implements FilesystemException
{
}
