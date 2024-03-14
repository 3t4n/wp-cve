<?php

namespace Modular\ConnectorDependencies\Illuminate\Http;

use Modular\ConnectorDependencies\Symfony\Component\HttpFoundation\File\File as SymfonyFile;
/** @internal */
class File extends SymfonyFile
{
    use FileHelpers;
}
