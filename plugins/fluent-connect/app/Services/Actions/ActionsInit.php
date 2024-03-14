<?php

namespace FluentConnect\App\Services\Actions;


use FluentConnect\App\Services\ConnectStores;

class ActionsInit
{
    public function init()
    {
        if(defined('FLUENTCRM')) {

            ConnectStores::addActionProvider('fluentcrm', [
                'title'                        => 'FluentCRM',
                'logo'                         => FLUENT_CONNECT_PLUGIN_URL . 'assets/images/fluentcrm.png',
                'description'                  => 'Add contact, tags, lists and other actions on FluentCRM',
                'require_integration_selector' => false,
                'enabled'                      => true
            ]);

            ConnectStores::addAction('fluentcrm', 'fconnect_fluentcrm_add_tag', \FluentConnect\App\Services\Actions\FluentCRM\AddToTag::class);
            ConnectStores::addAction('fluentcrm', 'fconnect_fluentcrm_add_to_list', \FluentConnect\App\Services\Actions\FluentCRM\AddToList::class);
            ConnectStores::addAction('fluentcrm', 'fconnect_fluentcrm_remove_tag', \FluentConnect\App\Services\Actions\FluentCRM\RemoveFromTag::class);
            ConnectStores::addAction('fluentcrm', 'fconnect_fluentcrm_remove_from_list', \FluentConnect\App\Services\Actions\FluentCRM\RemoveFromList::class);
        }
    }
}
