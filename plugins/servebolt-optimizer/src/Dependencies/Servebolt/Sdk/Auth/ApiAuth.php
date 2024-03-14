<?php

namespace Servebolt\Optimizer\Dependencies\Servebolt\Sdk\Auth;

interface ApiAuth
{
    public function getAuthHeaders(): array;
}
