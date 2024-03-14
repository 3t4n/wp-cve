<?php

namespace AForms\App\Front;

use Aura\Payload_Interface\PayloadStatus as Status;

class Restrict 
{
    protected $urlHelper;
    protected $orderRepo;
    protected $formRepo;
    protected $restrictionRepo;

    public function __construct($urlHelper, $orderRepo, $formRepo, $restrictionRepo) 
    {
        $this->urlHelper = $urlHelper;
        $this->orderRepo = $orderRepo;
        $this->formRepo = $formRepo;
        $this->restrictionRepo = $restrictionRepo;
    }
    
    public function __invoke($postId, $payload) 
    {
        // no authentication

        // no authorization
        $restricted = $this->restrictionRepo->load($postId);
        if (! $restricted) {
            return $payload->setStatus(Status::SUCCESS);
        }

        $id = $this->urlHelper->getAuthorizedAction('order_id');
        if ($id === false) {
            return $payload->setStatus(Status::NOT_AUTHORIZED);
        }

        $order = $this->orderRepo->findById($id);
        if (! $order) {
            return $payload->setStatus(Status::NOT_FOUND);
        }
        $form = $this->formRepo->findById($order->formId);
        if (! $form) {
            return $payload->setStatus(Status::NOT_FOUND);
        }
        
        return $payload->setStatus(Status::SUCCESS);
    }
}