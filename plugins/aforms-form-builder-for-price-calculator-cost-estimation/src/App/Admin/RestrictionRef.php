<?php

namespace AForms\App\Admin;

use Aura\Payload_Interface\PayloadStatus as Status;

class RestrictionRef 
{
    protected $restrictionRepo;

    public function __construct($restrictionRepo) 
    {
        $this->restrictionRepo = $restrictionRepo;
    }

    public function __invoke($inputs, $payload) 
    {
        list($post, $callbackInfo) = $inputs;

        // no authentication
        // no authorization

        $restricted = $this->restrictionRepo->load($post->ID);

        return $payload->setStatus(Status::SUCCESS)
                       ->setOutput($restricted);
    }
}