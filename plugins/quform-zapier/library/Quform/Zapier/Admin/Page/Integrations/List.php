<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */

class Quform_Zapier_Admin_Page_Integrations_List extends Quform_Zapier_Admin_Page
{
    /**
     * @var Quform_Zapier_Options
     */
    protected $zapierOptions;

    /**
     * @var Quform_Zapier_Integration_List_Table
     */
    protected $table;
    /**
     * @var Quform_Zapier_Integration_Repository
     */
    protected $integrationRepository;

    /**
     * @param  Quform_ViewFactory                       $viewFactory
     * @param  Quform_Repository                        $repository
     * @param  Quform_Zapier_Options                 $zapierOptions
     * @param  Quform_Zapier_Integration_List_Table  $table
     * @param  Quform_Zapier_Integration_Repository  $integrationRepository
     */
    public function __construct(
        Quform_ViewFactory $viewFactory,
        Quform_Repository $repository,
        Quform_Zapier_Options $zapierOptions,
        Quform_Zapier_Integration_List_Table $table,
        Quform_Zapier_Integration_Repository $integrationRepository
    ) {
        parent::__construct($viewFactory, $repository);

        $this->zapierOptions = $zapierOptions;
        $this->table = $table;
        $this->integrationRepository = $integrationRepository;
    }

    public function init()
    {
        $this->template = QUFORM_ZAPIER_TEMPLATE_PATH . '/admin/integrations/list.php';
    }

    protected function enqueueStyles()
    {
        parent::enqueueStyles();

        wp_enqueue_style('quform-zapier-admin', Quform_Zapier::adminUrl('css/admin.min.css'), array(), QUFORM_ZAPIER_VERSION, 'all');
    }

    protected function enqueueScripts()
    {
        parent::enqueueScripts();

        wp_enqueue_script('quform-zapier-integrations-list', Quform_Zapier::adminUrl('js/integrations.list.min.js'), array('jquery'), QUFORM_ZAPIER_VERSION, true);

        wp_localize_script('quform-zapier-integrations-list', 'quformZapierIntegrationsListL10n', array(
            'singleConfirmDelete' => __('Are you sure you want to delete this integration? All saved settings will be lost and this cannot be undone.', 'quform-zapier'),
            'pluralConfirmDelete' => __('Are you sure you want to delete these integrations? All saved settings will be lost and this cannot be undone.', 'quform-zapier'),
            'saveTableSettingsNonce' => wp_create_nonce('quform_zapier_save_integrations_table_settings'),
            'addNewNonce' => wp_create_nonce('quform_zapier_add_integration'),
            'errorAdding' => __('An error occurred adding the integration', 'quform-zapier')
        ));
    }

    /**
     * Process this page and send data to the view
     */
    public function process()
    {
        if ( ! current_user_can('quform_zapier_list_integrations')) {
            wp_die(__( 'You do not have sufficient permissions to access this page.', 'quform-zapier'), 403);
        }

        $this->processActions();

        $this->addPageMessages();

        $this->table->prepare_items();

        $perPage = get_user_meta(get_current_user_id(), 'quform_zapier_integrations_per_page', true);
        if ( ! is_numeric($perPage)) {
            $perPage = 20;
        }

        $this->view->with(array(
            'table' => $this->table,
            'perPage' => $perPage,
            'mdiPrefix' => apply_filters('quform_zapier_mdi_icon_prefix', 'qfb-mdi'),
        ));

        add_filter('removable_query_args', array($this, 'removableQueryArgs'));
    }

    /**
     * Process actions on the integration list
     */
    protected function processActions()
    {
        $nonce = Quform::get($_GET, '_wpnonce');
        $action = null;

        if (isset($_GET['id'])) {
            $action = Quform::get($_GET, 'action');
            $ids = (int) $_GET['id'];
        } elseif (isset($_GET['ids'])) {
            $action = $this->getBulkAction();
            $ids = (array) $_GET['ids'];
            $ids = array_map('intval', $ids);
        }

        if ($action == null) {
            if (Quform::get($_GET, '_wp_http_referer')) {
                wp_safe_redirect(esc_url_raw(remove_query_arg(array('_wp_http_referer', '_wpnonce'), wp_unslash($_SERVER['REQUEST_URI']))));
                exit;
            }

            return;
        }

        $returnUrl = remove_query_arg(array('action', 'action2', 'id', 'ids', 'activated', 'deactivated', 'duplicated', 'trashed', 'restored', 'deleted', 'error'), wp_get_referer());

        switch ($action) {
            case 'activate':
                $result = $this->processActivateAction($ids, $nonce);
                $returnUrl = add_query_arg($result, $returnUrl);
                break;
            case 'deactivate':
                $result = $this->processDeactivateAction($ids, $nonce);
                $returnUrl = add_query_arg($result, $returnUrl);
                break;
            case 'duplicate':
                $result = $this->processDuplicateAction($ids, $nonce);
                $returnUrl = add_query_arg($result, $returnUrl);
                break;
            case 'trash':
                $result = $this->processTrashAction($ids, $nonce);
                $returnUrl = add_query_arg($result, $returnUrl);
                break;
            case 'untrash':
                $result = $this->processUntrashAction($ids, $nonce);
                $returnUrl = add_query_arg($result, $returnUrl);
                break;
            case 'delete':
                $result = $this->processDeleteAction($ids, $nonce);
                $returnUrl = add_query_arg($result, $returnUrl);
                break;
        }

        wp_safe_redirect(esc_url_raw($returnUrl));
        exit;
    }

    /**
     * Process activating integrations
     *
     * @param   int|array  $ids    The integration ID or array of IDs
     * @param   string     $nonce  The nonce to check for validity
     * @return  array              The result message
     */
    protected function processActivateAction($ids, $nonce)
    {
        if (is_array($ids)) {
            $nonceAction = 'bulk-qfb-zapier-integrations';
        } else {
            $nonceAction = 'quform_zapier_activate_integration_' . $ids;
            $ids = array($ids);
        }

        if ( ! $nonce ||  ! count($ids)) {
            return array('error' => self::BAD_REQUEST);
        }

        if ( ! current_user_can('quform_zapier_edit_integrations')) {
            return array('error' => self::NO_PERMISSION);
        }

        if ( ! wp_verify_nonce($nonce, $nonceAction)) {
            return array('error' => self::NONCE_CHECK_FAILED);
        }

        $count = $this->integrationRepository->activateIntegrations($ids);

        return array('activated' => $count);
    }

    /**
     * Process deactivating integrations
     *
     * @param   int|array  $ids    The integration ID or array of IDs
     * @param   string     $nonce  The nonce to check for validity
     * @return  array              The result message
     */
    protected function processDeactivateAction($ids, $nonce)
    {
        if (is_array($ids)) {
            $nonceAction = 'bulk-qfb-zapier-integrations';
        } else {
            $nonceAction = 'quform_zapier_deactivate_integration_' . $ids;
            $ids = array($ids);
        }

        if ( ! $nonce ||  ! count($ids)) {
            return array('error' => self::BAD_REQUEST);
        }

        if ( ! current_user_can('quform_zapier_edit_integrations')) {
            return array('error' => self::NO_PERMISSION);
        }

        if ( ! wp_verify_nonce($nonce, $nonceAction)) {
            return array('error' => self::NONCE_CHECK_FAILED);
        }

        $count = $this->integrationRepository->deactivateIntegrations($ids);

        return array('deactivated' => $count);
    }

    /**
     * Process duplicating integrations
     *
     * @param   int|array  $ids    The integration ID or array of IDs
     * @param   string     $nonce  The nonce to check for validity
     * @return  array              The result message
     */
    protected function processDuplicateAction($ids, $nonce)
    {
        if (is_array($ids)) {
            $nonceAction = 'bulk-qfb-zapier-integrations';
        } else {
            $nonceAction = 'quform_zapier_duplicate_integration_' . $ids;
            $ids = array($ids);
        }

        if ( ! $nonce ||  ! count($ids)) {
            return array('error' => self::BAD_REQUEST);
        }

        if ( ! current_user_can('quform_zapier_add_integrations')) {
            return array('error' => self::NO_PERMISSION);
        }

        if ( ! wp_verify_nonce($nonce, $nonceAction)) {
            return array('error' => self::NONCE_CHECK_FAILED);
        }

        $newIds = $this->integrationRepository->duplicateIntegrations($ids);

        return array('duplicated' => count($newIds));
    }

    /**
     * Process trashing integrations
     *
     * @param   int|array  $ids    The integration ID or array of IDs
     * @param   string     $nonce  The nonce to check for validity
     * @return  array              The result message
     */
    protected function processTrashAction($ids, $nonce)
    {
        if (is_array($ids)) {
            $nonceAction = 'bulk-qfb-zapier-integrations';
        } else {
            $nonceAction = 'quform_zapier_trash_integration_' . $ids;
            $ids = array($ids);
        }

        if ( ! $nonce || ! count($ids)) {
            return array('error' => self::BAD_REQUEST);
        }

        if ( ! current_user_can('quform_zapier_delete_integrations')) {
            return array('error' => self::NO_PERMISSION);
        }

        if ( ! wp_verify_nonce($nonce, $nonceAction)) {
            return array('error' => self::NONCE_CHECK_FAILED);
        }

        $count = $this->integrationRepository->trashIntegrations($ids);

        return array('trashed' => $count);
    }

    /**
     * Process un-trashing integrations
     *
     * @param   int|array  $ids    The integration ID or array of IDs
     * @param   string     $nonce  The nonce to check for validity
     * @return  array              The result message
     */
    protected function processUntrashAction($ids, $nonce)
    {
        if (is_array($ids)) {
            $nonceAction = 'bulk-qfb-zapier-integrations';
        } else {
            $nonceAction = 'quform_zapier_untrash_integration_' . $ids;
            $ids = array($ids);
        }

        if ( ! $nonce || ! count($ids)) {
            return array('error' => self::BAD_REQUEST);
        }

        if ( ! current_user_can('quform_zapier_delete_integrations')) {
            return array('error' => self::NO_PERMISSION);
        }

        if ( ! wp_verify_nonce($nonce, $nonceAction)) {
            return array('error' => self::NONCE_CHECK_FAILED);
        }

        $count = $this->integrationRepository->untrashIntegrations($ids);

        return array('untrashed' => $count);
    }

    /**
     * Process deleting integrations
     *
     * @param   int|array  $ids    The integration ID or array of IDs
     * @param   string     $nonce  The nonce to check for validity
     * @return  array              The result message
     */
    protected function processDeleteAction($ids, $nonce)
    {
        if (is_array($ids)) {
            $nonceAction = 'bulk-qfb-zapier-integrations';
        } else {
            $nonceAction = 'quform_zapier_delete_integration_' . $ids;
            $ids = array($ids);
        }

        if ( ! $nonce || ! count($ids)) {
            return array('error' => self::BAD_REQUEST);
        }

        if ( ! current_user_can('quform_zapier_delete_integrations')) {
            return array('error' => self::NO_PERMISSION);
        }

        if ( ! wp_verify_nonce($nonce, $nonceAction)) {
            return array('error' => self::NONCE_CHECK_FAILED);
        }

        $count = $this->integrationRepository->deleteIntegrations($ids);

        return array('deleted' => $count);
    }

    /**
     * @return string|null
     */
    protected function getBulkAction()
    {
        $action = null;

        $a1 = Quform::get($_GET, 'action', '-1');
        $a2 = Quform::get($_GET, 'action2', '-1');

        if ($a1 != '-1') {
            $action = $a1;
        } elseif ($a2 != '-1') {
            $action = $a2;
        }

        return $action;
    }

    protected function addPageMessages()
    {
        $activated = (int) Quform::get($_GET, 'activated');
        if ($activated > 0) {
            /* translators: %s: the number of integrations */
            $this->addMessage('success', sprintf(_n('%s integration activated', '%s integrations activated', $activated, 'quform-zapier'), number_format_i18n($activated)));
        }

        $deactivated = (int) Quform::get($_GET, 'deactivated');
        if ($deactivated > 0) {
            /* translators: %s: the number of integrations */
            $this->addMessage('success', sprintf(_n('%s integration deactivated', '%s integrations deactivated', $deactivated, 'quform-zapier'), number_format_i18n($deactivated)));
        }

        $duplicated = (int) Quform::get($_GET, 'duplicated');
        if ($duplicated > 0) {
            /* translators: %s: the number of integrations */
            $this->addMessage('success', sprintf(_n('%s integration duplicated', '%s integrations duplicated', $duplicated, 'quform-zapier'), number_format_i18n($duplicated)));
        }

        $trashed = (int) Quform::get($_GET, 'trashed');
        if ($trashed > 0) {
            /* translators: %s: the number of integrations */
            $this->addMessage('success', sprintf(_n('%s integration moved to the Trash', '%s integrations moved to the Trash', $trashed, 'quform-zapier'), number_format_i18n($trashed)));
        }

        $untrashed = (int) Quform::get($_GET, 'untrashed');
        if ($untrashed > 0) {
            /* translators: %s: the number of integrations */
            $this->addMessage('success', sprintf(_n('%s integration restored', '%s integrations restored', $untrashed, 'quform-zapier'), number_format_i18n($untrashed)));
        }

        $deleted = (int) Quform::get($_GET, 'deleted');
        if ($deleted > 0) {
            /* translators: %s: the number of integrations */
            $this->addMessage('success', sprintf(_n('%s integration deleted', '%s integrations deleted', $deleted, 'quform-zapier'), number_format_i18n($deleted)));
        }

        switch ((int) Quform::get($_GET, 'error')) {
            case self::BAD_REQUEST:
                $this->addMessage('error', __('Bad request.', 'quform-zapier'));
                break;
            case self::NO_PERMISSION:
                $this->addMessage('error', __('You do not have permission to do this.', 'quform-zapier'));
                break;
            case self::NONCE_CHECK_FAILED:
                $this->addMessage('error', __('Nonce check failed.', 'quform-zapier'));
                break;
        }
    }

    /**
     * Additional query arguments that can be hidden by history.replaceState
     *
     * @param   array  $args
     * @return  array
     */
    public function removableQueryArgs($args)
    {
        $args[] = 'deactivated';
        $args[] = 'duplicated';

        return $args;
    }

    /**
     * Set the page title
     *
     * @return string
     */
    protected function getAdminTitle()
    {
        return __('Zapier Integrations', 'quform-zapier');
    }

    /**
     * Get the HTML for the admin navigation menu
     *
     * @param   array|null  $currentForm  The data for the current form (if any)
     * @param   array       $extra        Extra HTML to add to the nav, the array key is the hook position
     * @return  string
     */
    public function getNavHtml(array $currentForm = null, array $extra = array())
    {
        $extra[40] = sprintf('<div class="qfb-nav-item qfb-nav-page-info"><i class="qfb-nav-page-icon qfb-nav-zapier-icon"></i><span class="qfb-nav-page-title">%s</span></div>', esc_html__('Zapier Integrations', 'quform-zapier'));

        $extra[50] = sprintf('<div class="qfb-nav-item qfb-nav-item-right"><a id="qfb-zapier-show-integration-table-settings" class="qfb-nav-item-link"><i class="%1$s %1$s-settings"></i></a></div>', esc_attr(apply_filters('quform_zapier_mdi_icon_prefix', 'qfb-mdi')));

        return parent::getNavHtml($currentForm, $extra);
    }
}
