<?php

namespace Modular\ConnectorDependencies\League\Flysystem\Plugin;

use Modular\ConnectorDependencies\League\Flysystem\FilesystemInterface;
use Modular\ConnectorDependencies\League\Flysystem\PluginInterface;
/** @internal */
abstract class AbstractPlugin implements PluginInterface
{
    /**
     * @var FilesystemInterface
     */
    protected $filesystem;
    /**
     * Set the Filesystem object.
     *
     * @param FilesystemInterface $filesystem
     */
    public function setFilesystem(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }
}
