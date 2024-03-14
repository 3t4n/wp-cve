<?php

namespace AForms\App\Admin;

use Aura\Payload_Interface\PayloadStatus as Status;

class RestrictionSet 
{
    protected $restrictionRepo;
    protected $urlHelper;

    public function __construct($restrictionRepo, $urlHelper) {
        $this->restrictionRepo = $restrictionRepo;
        $this->urlHelper = $urlHelper;
    }

    public function __invoke($inputs, $payload) 
    {
        list($postId, $post, $isUpdate) = $inputs;

        // no authentication
        // no authorization
        if (! isset($_REQUEST['aforms_restricted_hidden'])) {
            // not a metabox save; then do nothing
            return $payload->setOUtput(null);
        }
        if (! $this->urlHelper->testMetaboxNonce('aforms_restricted_save')) {
            // not-verified; then do nothing
            return $payload->setOutput(null);
        }
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $payload->setOutput(null);
        }
        
        $restricted = (isset($_POST['aforms_restricted']) && $_POST['aforms_restricted']);
        $this->restrictionRepo->save($postId, $restricted);

        return $payload->setOutput(null);
    }
}