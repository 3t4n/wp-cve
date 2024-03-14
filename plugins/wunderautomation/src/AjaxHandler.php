<?php

namespace WunderAuto;

use WC_Data_Store;

/**
 * Class AjaxHandler
 *
 * Plugin specific Ajax-calls
 */
class AjaxHandler
{
    /**
     * @param Loader $loader
     *
     * @return void
     */
    public function register($loader)
    {
        $loader->addAction('wp_ajax_wa_search_users', $this, 'searchWpUsers');
        $loader->addAction('wp_ajax_wa_search_tags', $this, 'searchTags');
        $loader->addAction('wp_ajax_wa_search_categories', $this, 'searchCategories');
        $loader->addAction('wp_ajax_wa_search_terms', $this, 'searchTerms');
        $loader->addAction('wp_ajax_wa_search_wooproducts', $this, 'searchWooProducts');
        $loader->addAction('wp_ajax_wa_search_wooproduct_tags', $this, 'searchWooProductTags');
        $loader->addAction('wp_ajax_wa_search_wooproduct_cats', $this, 'searchWooProductCategories');
        $loader->addAction('wp_ajax_wa_search_emails', $this, 'searchAutomationEmails');
        $loader->addAction('wp_ajax_wa_logdata', $this, 'searchLogData');
        $loader->addAction('wp_ajax_wa_queuedata', $this, 'searchQueueData');
        $loader->addAction('wp_ajax_wa_cancelqueueditem', $this, 'cancelQueuedItem');
        $loader->addAction('wp_ajax_wa_runqueueditem', $this, 'runQueuedItem');
        $loader->addAction('wp_ajax_wa_wizard_data', $this, 'updateWizardData');
        $loader->addAction('wp_ajax_wa_dismiss_notice', $this, 'dismissAdminNotice');
    }

    /**
     * Ajax hook for wp_ajax_wa_search_users
     *
     * @return void
     */
    public function searchWpUsers()
    {
        header("Content-Type: application/json; charset=UTF-8");
        $ret   = [];
        $nonce = wp_unslash(sanitize_key($_REQUEST['security']));
        $nonce = is_array($nonce) ? (string)$nonce[0] : $nonce;
        if (!wp_verify_nonce($nonce, 'search-users')) {
            $this->returnJsonError();
        }

        $search = sanitize_text_field($_REQUEST['term']);
        $users  = new \WP_User_Query([
            'search'         => "*$search*",
            'search_columns' => [
                'user_login',
                'user_nicename',
                'user_email',
            ],
        ]);

        $usersFound = $users->get_results();
        foreach ($usersFound as $user) {
            $ret[$user->ID] = $user->display_name;
        }

        echo json_encode($ret);
        wp_die();
    }

    /**
     * Woo products search
     *
     * @throws \Exception
     *
     * @return void
     */
    public function searchWooProducts()
    {
        header("Content-Type: application/json; charset=UTF-8");
        $products = [];
        $nonce    = wp_unslash(sanitize_key($_REQUEST['security']));
        $nonce    = is_array($nonce) ? (string)$nonce[0] : $nonce;
        if (!wp_verify_nonce($nonce, 'search-products')) {
            $this->returnJsonError();
        }

        $search = sanitize_text_field($_REQUEST['term']);

        $data_store = WC_Data_Store::load('product');
        $ids        = $data_store->search_products($search, '', true); // @phpstan-ignore-line

        $productObjects = array_filter(
            array_map('wc_get_product', $ids),
            'wc_products_array_filter_readable'   // @phpstan-ignore-line
        );
        foreach ($productObjects as $productObject) {
            if (!($productObject instanceof \WC_Product)) {
                continue;
            }
            $products[$productObject->get_id()] = rawurldecode($productObject->get_formatted_name());
        }

        echo json_encode($products);
        wp_die();
    }

    /**
     * Ajax hook to return logdata
     *
     * @return void
     */
    public function searchLogData()
    {
        $wpdb = wa_get_wpdb();

        //header("Content-Type: application/json; charset=UTF-8");
        $data  = [];
        $nonce = wp_unslash(sanitize_key($_REQUEST['security']));
        $nonce = is_array($nonce) ? (string)$nonce[0] : $nonce;
        if (!wp_verify_nonce($nonce, 'search-logdata')) {
            $this->returnJsonError();
        }

        $sql = "select * from {$wpdb->prefix}wa_log ORDER BY id ASC";

        /** @var array<int, \stdClass> $rows */
        $rows = $wpdb->get_results($sql, 'OBJECT_K');
        foreach ($rows as $row) {
            $data[] = (object)[
                'id'      => $row->id,
                'date'    => date('Y-m-d', strtotime($row->time)),
                'time'    => date('H:i:s', strtotime($row->time)),
                'session' => $row->session,
                'level'   => $row->level,
                'message' => $row->message,
                'details' => $row->context,
            ];
        }

        echo json_encode((object)['data' => $data]);
        wp_die();
    }

    /**
     * Ajax hook to return the queue
     *
     * @return void
     */
    public function searchQueueData()
    {
        $wpdb = wa_get_wpdb();

        header("Content-Type: application/json; charset=UTF-8");
        $data  = [];
        $nonce = wp_unslash(sanitize_key($_REQUEST['security']));
        $nonce = is_array($nonce) ? (string)$nonce[0] : $nonce;
        if (!wp_verify_nonce($nonce, 'search-queuedata')) {
            $this->returnJsonError();
        }

        $sql = "SELECT q.*, p.post_title 
                FROM {$wpdb->prefix}wa_queue q 
                  LEFT OUTER JOIN {$wpdb->prefix}posts p ON p.id = q.workflow_id 
                ORDER BY q.id ASC;";

        /** @var array<int, \stdClass> $rows */
        $rows = $wpdb->get_results($sql, 'OBJECT_K');
        foreach ($rows as $row) {
            $row->objects = json_decode($row->args);
            $data[]       = (object)[
                'id'       => $row->id,
                'created'  => date('Y-m-d H:i:s', strtotime($row->created)),
                'workflow' => sprintf(
                    '<a href="%s">%s</a>',
                    get_edit_post_link($row->workflow_id),
                    $row->post_title
                ),
                'objects'  => $this->getQueuedObjects($row->objects),
                'runsOn'   => date('Y-m-d H:i:s', strtotime($row->time)),
                'actions'  => sprintf(
                    '<a href="#" class="%s" data-id="%s">%s | <a href="#" class="%s" data-id="%s">%s',
                    'wa_queue_runnow',
                    $row->id,
                    __('Run now', 'wunderauto'),
                    'wa_queue_cancel',
                    $row->id,
                    __('Cancel', 'wunderauto')
                ),
            ];
        }

        echo json_encode((object)['data' => $data]);
        wp_die();
    }

    /**
     * Cancel an item currently in the tasks queue
     *
     * @return void
     */
    public function cancelQueuedItem()
    {
        $wpdb = wa_get_wpdb();

        header("Content-Type: application/json; charset=UTF-8");
        $nonce = wp_unslash(sanitize_key($_REQUEST['security']));
        $nonce = is_array($nonce) ? (string)$nonce[0] : $nonce;
        if (wp_verify_nonce($nonce, 'search-queuedata')) {
            $queueId = (int)$_REQUEST['id'];
            if ($queueId > 0) {
                /** @var string $sql */
                $sql = $wpdb->prepare("DELETE from {$wpdb->prefix}wa_queue WHERE id=%s", [$queueId]);
                $wpdb->query($sql);
            }
        }
        wp_die();
    }

    /**
     * @return void
     */
    public function runQueuedItem()
    {
        $wpdb = wa_get_wpdb();

        header("Content-Type: application/json; charset=UTF-8");
        $nonce = wp_unslash(sanitize_key($_REQUEST['security']));
        $nonce = is_array($nonce) ? (string)$nonce[0] : $nonce;
        if (wp_verify_nonce($nonce, 'search-queuedata')) {
            $queueId = (int)$_REQUEST['id'];
            if ($queueId > 0) {
                /** @var string $sql */
                $sql = $wpdb->prepare(
                    "UPDATE {$wpdb->prefix}wa_queue SET time=%s WHERE id=%s",
                    [
                        date('Y-m-d H:i:s', time() - 1),
                        $queueId,
                    ]
                );
                $wpdb->query($sql);
            }
        }
        wp_die();
    }

    /**
     * Ajax hook for wp_ajax_wa_search_tags
     *
     * @return void
     */
    public function searchTerms()
    {
        $taxonomy = sanitize_text_field($_REQUEST['term2']);
        $this->searchTaxonomy($taxonomy);
    }

    /**
     * Ajax hook for wp_ajax_wa_search_tags
     *
     * @return void
     */
    public function searchTags()
    {
        $this->searchTaxonomy('post_tag');
    }

    /**
     * Ajax hook for wp_ajax_wa_search_categories
     *
     * @return void
     */
    public function searchCategories()
    {
        $this->searchTaxonomy('category');
    }

    /**
     * Ajax hook for wp_ajax_wa_search_wooproduct_tags
     *
     * @return void
     */
    public function searchWooProductTags()
    {
        $this->searchTaxonomy('product_tag');
    }

    /**
     * Ajax hook for wp_ajax_wa_search_wooproduct_categories
     *
     * @return void
     */
    public function searchWooProductCategories()
    {
        $this->searchTaxonomy('product_cat');
    }

    /**
     * Updates settings with data collected in the getting started wizard
     *
     * @return void
     */
    public function updateWizardData()
    {
        header("Content-Type: application/json; charset=UTF-8");
        $ret   = [];
        $nonce = wp_unslash(sanitize_key($_REQUEST['security']));
        $nonce = is_array($nonce) ? (string)$nonce[0] : $nonce;
        if (!wp_verify_nonce($nonce, 'wizard-data')) {
            echo json_encode($ret);
            wp_die();
        }

        if (!isset($_REQUEST['state'])) {
            echo json_encode($ret);
            wp_die();
        }

        $enableWebhooks = !empty($_REQUEST['state']['enableWebhooks']) ?
            (bool)$_REQUEST['state']['enableWebhooks'] :
            false;

        $webhookSlug = !empty($_REQUEST['state']['webhookSlug']) ?
            sanitize_text_field($_REQUEST['state']['webhookSlug']) :
            'wa-hook';

        $signUp = !empty($_REQUEST['state']['signUp']) ?
            (bool)$_REQUEST['state']['signUp'] :
            false;

        $email = !empty($_REQUEST['state']['email']) ?
            sanitize_email($_REQUEST['state']['email']) :
            '';

        $options = get_option('wunderauto-general');

        $options['enable_webhook_trigger'] = $enableWebhooks;
        $options ['webhookslug']           = $webhookSlug;
        update_option('wunderauto-general', $options);

        // If signup was checked,
        if ($signUp) {
            $tags = join(',', ['wunderautomation', 'wizard']);
            $url  = "https://www.wundermatics.com/wa-hook/318a1fac2630?email={$email}&tags={$tags}";
            if (defined('WA_DEBUG') && WA_DEBUG === true) {
                update_option('wunderauto-test-wizard-email-subscribe', $url);
            } else {
                wp_remote_get($url);
            }
        }
        echo json_encode($ret);
        wp_die();
    }

    /**
     * Handle dismissed admin notices
     *
     * @return void
     */
    public function dismissAdminNotice()
    {
        header("Content-Type: application/json; charset=UTF-8");
        $ret = [];

        $nonce = wp_unslash(sanitize_key($_REQUEST['security']));
        $nonce = is_array($nonce) ? (string)$nonce[0] : $nonce;
        if (!wp_verify_nonce($nonce, 'dismiss-admin-notice')) {
            echo json_encode($ret);
            wp_die();
        }

        if (isset($_REQUEST['id'])) {
            $id            = sanitize_key($_REQUEST['id']);
            $noticeHandler = AdminNotice::getInstance();
            $noticeHandler->dismiss($id);
        }
    }

    /**
     * @param array<string, mixed> $ret
     *
     * @return void
     */
    private function returnJsonError($ret = [])
    {
        echo json_encode($ret);
        wp_die();
    }

    /**
     * Returns a comma separated string of links to queued objects
     *
     * @param array<int, \stdClass> $objects
     *
     * @return string
     */
    private function getQueuedObjects($objects)
    {
        global $wp_post_types;

        $links = [];
        foreach ($objects as $object) {
            if ((int)$object->id === 0) {
                continue;
            }
            switch ($object->type) {
                case 'post':
                case 'order':
                    $postType = get_post_type($object->id);
                    $postType = isset($wp_post_types[$postType]) ?
                        $wp_post_types[$postType]->name :
                        __('Post', 'wunderauto');

                    $title = get_the_title($object->id);
                    $title = strlen(trim($title)) == 0 ?
                        $postType . ':' . $object->id :
                        "$title($postType)";

                    $links[] = sprintf(
                        '<a href="%s">%s</a>',
                        get_edit_post_link($object->id),
                        $title
                    );
                    break;
                case 'user':
                    $links[] = sprintf(
                        '<a href="%s">%s(%s)</a>',
                        get_edit_user_link($object->id),
                        get_the_author_meta('user_login', $object->id),
                        __('User', 'wunderauto')
                    );
                    break;
            }
        }

        return join(', ', $links);
    }

    /**
     * @param string $taxonomy
     *
     * @return void
     */
    private function searchTaxonomy($taxonomy)
    {
        header("Content-Type: application/json; charset=UTF-8");
        $ret   = [];
        $nonce = wp_unslash(sanitize_key($_REQUEST['security']));
        $nonce = is_array($nonce) ? (string)$nonce[0] : $nonce;
        if (!wp_verify_nonce($nonce, 'search-taxonomies')) {
            $this->returnJsonError();
        }

        $search = sanitize_text_field($_REQUEST['term']);
        $terms  = get_terms($taxonomy, ['hide_empty' => false]);
        if (is_array($terms)) {
            foreach ($terms as $term) {
                if (!($term instanceof \WP_Term)) {
                    continue;
                }
                $include = false;
                $include = stripos($term->name, $search) !== false ? true : $include;
                $include = stripos($term->slug, $search) !== false ? true : $include;
                if ($include) {
                    $ret[$term->term_id] = $term->name . " ({$term->slug})";
                }
            }
        }

        echo json_encode($ret);
        wp_die();
    }
}
