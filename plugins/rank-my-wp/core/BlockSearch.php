<?php
defined('ABSPATH') || die('Cheatin\' uh?');

class RKMW_Core_BlockSearch extends RKMW_Classes_BlockController {

    public function hookGetContent() {
        RKMW_Classes_ObjController::getClass('RKMW_Classes_DisplayController')->loadMedia('search');

        echo $this->getView('Blocks/Search');
    }

    public function action() {
        switch (RKMW_Classes_Helpers_Tools::getValue('action')) {
            case 'rkmw_ajax_search':

                //RKMW_Classes_Helpers_Tools::setHeader('json');
                $search_query = RKMW_Classes_Helpers_Tools::getValue('search_query', '');

                $args = array();
                $args['action'] = 'lsvr-lore-ajax-search';
                $args['nonce'] = 'plugin_search';
                $args['search_query'] = $search_query;

                $parameters = "";
                foreach ($args as $key => $value) {
                    if ($value <> '') {
                        $parameters .= ($parameters == "" ? "" : "&") . $key . "=" . urlencode($value);
                    }
                }
                $url = 'https://howto.rankmywp.com/wp-admin/admin-ajax.php' . "?" . $parameters;
                echo RKMW_Classes_RemoteController::rkmw_wpcall($url, array('sslverify' => false, 'timeout' => 10));
                exit();
        }
    }

}
