<?php

namespace Flamix\Plugin\Queue\Interfaces;

use Flamix\Plugin\Queue\SQL;

interface ShouldQueue
{
    public function sqlClosure(): SQL;
    public function query(string $query);
}