<?php

namespace AForms\App\Front;

use Aura\Payload_Interface\PayloadStatus as Status;

class ResultRef 
{
    protected $orderRepo;
    protected $formRepo;
    protected $session;
    protected $urlHelper;
    protected $options;

    public function __construct($orderRepo, $formRepo, $session, $urlHelper, $options) 
    {
        $this->orderRepo = $orderRepo;
        $this->formRepo = $formRepo;
        $this->session = $session;
        $this->urlHelper = $urlHelper;
        $this->options = $options;
    }

    protected function dim($order) 
    {
        $len = count($order->attrs);
        for ($i = 0; $i < $len; $i++) {
            if ($order->attrs[$i]->type == 'reCAPTCHA3') {
                $order->attrs[$i]->value = null;
            }
        }
        
        $this->options->publishOrder($order);
    }

    public function __invoke($_inputs, $payload) 
    {
        // no authentication

        // no authorization
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

        $this->dim($order);

        $output = array(
            'order' => $order, 
            'form' => $form
        );
        return $payload->setStatus(Status::SUCCESS)
                       ->setOutput($output);
    }
}