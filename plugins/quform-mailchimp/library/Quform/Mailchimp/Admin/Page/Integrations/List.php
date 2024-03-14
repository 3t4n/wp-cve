<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */

class Quform_Mailchimp_Admin_Page_Integrations_List extends Quform_Mailchimp_Admin_Page
{
    /**
     * @var Quform_Mailchimp_Options
     */
    protected $mailchimpOptions;

    /**
     * @var Quform_Mailchimp_Integration_List_Table
     */
    protected $table;
    /**
     * @var Quform_Mailchimp_Integration_Repository
     */
    protected $integrationRepository;

    /**
     * @param  Quform_ViewFactory                       $viewFactory
     * @param  Quform_Repository                        $repository
     * @param  Quform_Mailchimp_Options                 $mailchimpOptions
     * @param  Quform_Mailchimp_Integration_List_Table  $table
     * @param  Quform_Mailchimp_Integration_Repository  $integrationRepository
     */
    public function __construct(
        Quform_ViewFactory $viewFactory,
        Quform_Repository $repository,
        Quform_Mailchimp_Options $mailchimpOptions,
        Quform_Mailchimp_Integration_List_Table $table,
        Quform_Mailchimp_Integration_Repository $integrationRepository
    ) {
        parent::__construct($viewFactory, $repository);

        $this->mailchimpOptions = $mailchimpOptions;
        $this->table = $table;
        $this->integrationRepository = $integrationRepository;
    }

    public function init()
    {
        $this->template = QUFORM_MAILCHIMP_TEMPLATE_PATH . '/admin/integrations/list.php';
    }

    protected function enqueueStyles()
    {
        parent::enqueueStyles();

        wp_enqueue_style('quform-mailchimp-admin', Quform_Mailchimp::adminUrl('css/admin.min.css'), array(), QUFORM_MAILCHIMP_VERSION, 'all');
    }

    protected function enqueueScripts()
    {
        parent::enqueueScripts();

        wp_enqueue_script('quform-mc-integrations-list', Quform_Mailchimp::adminUrl('js/integrations.list.min.js'), array('jquery'), QUFORM_MAILCHIMP_VERSION, true);

        wp_localize_script('quform-mc-integrations-list', 'quformMailchimpIntegrationsListL10n', array(
            'singleConfirmDelete' => __('Are you sure you want to delete this integration? All saved settings will be lost and this cannot be undone.', 'quform-mailchimp'),
            'pluralConfirmDelete' => __('Are you sure you want to delete these integrations? All saved settings will be lost and this cannot be undone.', 'quform-mailchimp'),
            'saveTableSettingsNonce' => wp_create_nonce('quform_mc_save_integrations_table_settings'),
            'addNewNonce' => wp_create_nonce('quform_add_mc_integration'),
            'errorAdding' => __('An error occurred adding the integration', 'quform-mailchimp')
        ));
    }

    /**
     * Process this page and send data to the view
     */
    public function process()
    {
        if ( ! current_user_can('quform_mailchimp_list_integrations')) {
            wp_die(__( 'You do not have sufficient permissions to access this page.', 'quform-mailchimp'), 403);
        }

        $this->processActions();

        $this->addPageMessages();

        if ( ! Quform::isNonEmptyString($this->mailchimpOptions->get('apiKey'))) {
            $this->addMessage(
                'info',
                sprintf(
                    /* translators: %1$s: open link tag, %2$s: close link tag */
                    __('Please verify a Mailchimp API key on the %1$splugin settings page%2$s.', 'quform-mailchimp'),
                    sprintf('<a href="%s">', esc_url(admin_url('admin.php?page=quform.mailchimp&sp=settings'))),
                    '</a>'
                )
            );
        }

        $this->table->prepare_items();

        $perPage = get_user_meta(get_current_user_id(), 'quform_mailchimp_integrations_per_page', true);
        if ( ! is_numeric($perPage)) {
            $perPage = 20;
        }

        $this->view->with(array(
            'table' => $this->table,
            'perPage' => $perPage,
            'mdiPrefix' => apply_filters('quform_mailchimp_mdi_icon_prefix', 'qfb-mdi'),
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
            $nonceAction = 'bulk-qfb-mailchimp-integrations';
        } else {
            $nonceAction = 'quform_mailchimp_activate_integration_' . $ids;
            $ids = array($ids);
        }

        if ( ! $nonce ||  ! count($ids)) {
            return array('error' => self::BAD_REQUEST);
        }

        if ( ! current_user_can('quform_mailchimp_edit_integrations')) {
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
            $nonceAction = 'bulk-qfb-mailchimp-integrations';
        } else {
            $nonceAction = 'quform_mailchimp_deactivate_integration_' . $ids;
            $ids = array($ids);
        }

        if ( ! $nonce ||  ! count($ids)) {
            return array('error' => self::BAD_REQUEST);
        }

        if ( ! current_user_can('quform_mailchimp_edit_integrations')) {
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
            $nonceAction = 'bulk-qfb-mailchimp-integrations';
        } else {
            $nonceAction = 'quform_mailchimp_duplicate_integration_' . $ids;
            $ids = array($ids);
        }

        if ( ! $nonce ||  ! count($ids)) {
            return array('error' => self::BAD_REQUEST);
        }

        if ( ! current_user_can('quform_mailchimp_add_integrations')) {
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
            $nonceAction = 'bulk-qfb-mailchimp-integrations';
        } else {
            $nonceAction = 'quform_mailchimp_trash_integration_' . $ids;
            $ids = array($ids);
        }

        if ( ! $nonce || ! count($ids)) {
            return array('error' => self::BAD_REQUEST);
        }

        if ( ! current_user_can('quform_mailchimp_delete_integrations')) {
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
            $nonceAction = 'bulk-qfb-mailchimp-integrations';
        } else {
            $nonceAction = 'quform_mailchimp_untrash_integration_' . $ids;
            $ids = array($ids);
        }

        if ( ! $nonce || ! count($ids)) {
            return array('error' => self::BAD_REQUEST);
        }

        if ( ! current_user_can('quform_mailchimp_delete_integrations')) {
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
            $nonceAction = 'bulk-qfb-mailchimp-integrations';
        } else {
            $nonceAction = 'quform_mailchimp_delete_integration_' . $ids;
            $ids = array($ids);
        }

        if ( ! $nonce || ! count($ids)) {
            return array('error' => self::BAD_REQUEST);
        }

        if ( ! current_user_can('quform_mailchimp_delete_integrations')) {
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
            $this->addMessage('success', sprintf(_n('%s integration activated', '%s integrations activated', $activated, 'quform-mailchimp'), number_format_i18n($activated)));
        }

        $deactivated = (int) Quform::get($_GET, 'deactivated');
        if ($deactivated > 0) {
            /* translators: %s: the number of integrations */
            $this->addMessage('success', sprintf(_n('%s integration deactivated', '%s integrations deactivated', $deactivated, 'quform-mailchimp'), number_format_i18n($deactivated)));
        }

        $duplicated = (int) Quform::get($_GET, 'duplicated');
        if ($duplicated > 0) {
            /* translators: %s: the number of integrations */
            $this->addMessage('success', sprintf(_n('%s integration duplicated', '%s integrations duplicated', $duplicated, 'quform-mailchimp'), number_format_i18n($duplicated)));
        }

        $trashed = (int) Quform::get($_GET, 'trashed');
        if ($trashed > 0) {
            /* translators: %s: the number of integrations */
            $this->addMessage('success', sprintf(_n('%s integration moved to the Trash', '%s integrations moved to the Trash', $trashed, 'quform-mailchimp'), number_format_i18n($trashed)));
        }

        $untrashed = (int) Quform::get($_GET, 'untrashed');
        if ($untrashed > 0) {
            /* translators: %s: the number of integrations */
            $this->addMessage('success', sprintf(_n('%s integration restored', '%s integrations restored', $untrashed, 'quform-mailchimp'), number_format_i18n($untrashed)));
        }

        $deleted = (int) Quform::get($_GET, 'deleted');
        if ($deleted > 0) {
            /* translators: %s: the number of integrations */
            $this->addMessage('success', sprintf(_n('%s integration deleted', '%s integrations deleted', $deleted, 'quform-mailchimp'), number_format_i18n($deleted)));
        }

        switch ((int) Quform::get($_GET, 'error')) {
            case self::BAD_REQUEST:
                $this->addMessage('error', __('Bad request.', 'quform-mailchimp'));
                break;
            case self::NO_PERMISSION:
                $this->addMessage('error', __('You do not have permission to do this.', 'quform-mailchimp'));
                break;
            case self::NONCE_CHECK_FAILED:
                $this->addMessage('error', __('Nonce check failed.', 'quform-mailchimp'));
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
        return __('Mailchimp Integrations', 'quform-mailchimp');
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
        $extra[40] = sprintf('<div class="qfb-nav-item qfb-nav-page-info"><i class="qfb-nav-page-icon qfb-nav-mailchimp-icon"></i><span class="qfb-nav-page-title">%s</span></div>', esc_html__('Mailchimp Integrations', 'quform-mailchimp'));

        $extra[50] = sprintf('<div class="qfb-nav-item qfb-nav-item-right"><a id="qfb-show-mc-integration-table-settings" class="qfb-nav-item-link"><i class="%1$s %1$s-settings"></i></a></div>', esc_attr(apply_filters('quform_mailchimp_mdi_icon_prefix', 'qfb-mdi')));

        return parent::getNavHtml($currentForm, $extra);
    }
}
