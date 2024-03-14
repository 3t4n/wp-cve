<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */

class Quform_Mailchimp_Admin_Page_Integrations_Edit extends Quform_Mailchimp_Admin_Page
{
    /**
     * @var Quform_Mailchimp_Integration_Builder
     */
    protected $integrationBuilder;

    /**
     * @var Quform_Mailchimp_Integration_Repository
     */
    protected $integrationRepository;

    /**
     * @var Quform_Mailchimp_Options
     */
    protected $options;

    /**
     * @param  Quform_ViewFactory                       $viewFactory
     * @param  Quform_Repository                        $repository
     * @param  Quform_Mailchimp_Integration_Builder     $integrationBuilder
     * @param  Quform_Mailchimp_Integration_Repository  $integrationRepository
     * @param  Quform_Mailchimp_Options                 $options
     */
    public function __construct(
        Quform_ViewFactory $viewFactory,
        Quform_Repository $repository,
        Quform_Mailchimp_Integration_Builder $integrationBuilder,
        Quform_Mailchimp_Integration_Repository $integrationRepository,
        Quform_Mailchimp_Options $options
    ) {
        parent::__construct($viewFactory, $repository);

        $this->integrationBuilder = $integrationBuilder;
        $this->integrationRepository = $integrationRepository;
        $this->options = $options;
    }

    public function init()
    {
        $this->template = QUFORM_MAILCHIMP_TEMPLATE_PATH . '/admin/integrations/edit.php';
    }

    protected function enqueueStyles()
    {
        wp_enqueue_style('quform-select2', Quform::url('css/select2.min.css'), array(), '4.0.13');

        parent::enqueueStyles();

        wp_enqueue_style('quform-mailchimp-admin', Quform_Mailchimp::adminUrl('css/admin.min.css'), array(), QUFORM_MAILCHIMP_VERSION, 'all');
    }

    protected function enqueueScripts()
    {
        wp_enqueue_script('quform-select2', Quform::url('js/select2.min.js'), array('jquery'), '4.0.13', true);

        parent::enqueueScripts();

        wp_enqueue_script('quform-mc-integrations-edit', Quform_Mailchimp::adminUrl('js/integrations.edit.min.js'), array('jquery', 'quform-select2'), QUFORM_MAILCHIMP_VERSION, true);

        wp_localize_script('quform-mc-integrations-edit', 'quformMailchimpIntegrationsEditL10n', array(
            'unsavedChanges' => __('You have unsaved changes.', 'quform-mailchimp'),
            'saveIntegrationNonce' => wp_create_nonce('quform_mc_save_integration'),
            'integrationSaved' => __('Integration saved', 'quform-mailchimp'),
            'errorSavingIntegration' => __('Error saving the integration', 'quform-mailchimp'),
            'correctHighlightedFields' => __('Please correct the highlighted fields and save the integration again', 'quform-mailchimp'),
            'pleaseSelect' => __('Please select', 'quform-mailchimp'),
            'selectAMailchimpTag' => __('Select a Mailchimp tag', 'quform-mailchimp'),
            'pleaseSelectAFormFirst' => __('Please select a form first', 'quform-mailchimp'),
            'pleaseSelectAListFirst' => __('Please select a list first', 'quform-mailchimp'),
            'defaultIntegrationConfig' => Quform_Mailchimp_Integration::getDefaultConfig(),
            'mergeFieldHtml' => $this->integrationBuilder->getMergeFieldHtml(),
            'runThisIntegration' => __('Run this integration', 'quform-mailchimp'),
            'doNotRunThisIntegration' => __('Do not run this integration', 'quform-mailchimp'),
            'ifAllOfTheseRulesMatch' => __('if all of these rules match', 'quform-mailchimp'),
            'ifAnyOfTheseRulesMatch' => __('if any of these rules match', 'quform-mailchimp'),
            'noLogicElements' => __('There are no elements available to use for logic rules.', 'quform-mailchimp'),
            'noLogicRules' => __('There are no logic rules yet, click "Add logic rule" to add one.', 'quform-mailchimp'),
            'logicRuleHtml' => $this->integrationBuilder->getLogicRuleHtml(),
            /* translators: %1$s: element admin label, %2$s: element unique ID */
            'adminLabelElementId' => __('%1$s (%2$s)', 'quform-mailchimp'),
            'is' => __('is', 'quform-mailchimp'),
            'isNot' => __('is not', 'quform-mailchimp'),
            'isEmpty' => __('is empty', 'quform-mailchimp'),
            'isNotEmpty' => __('is not empty', 'quform-mailchimp'),
            'greaterThan' => __('is greater than', 'quform-mailchimp'),
            'lessThan' => __('is less than', 'quform-mailchimp'),
            'contains' => __('contains', 'quform-mailchimp'),
            'startsWith' => __('starts with', 'quform-mailchimp'),
            'endsWith' => __('ends with', 'quform-mailchimp'),
            'enterValue' => __('Enter a value', 'quform-mailchimp'),
        ));
    }

    /**
     * Process this page and send data to the view
     */
    public function process()
    {
        if ( ! current_user_can('quform_mailchimp_edit_integrations')) {
            wp_die(__( 'You do not have sufficient permissions to access this page.', 'quform-mailchimp'), 403);
        }

        if ( ! isset($_GET['id']) || ! is_array($config = $this->integrationRepository->getConfig((int) $_GET['id']))) {
            wp_die(__("You attempted to edit an item that doesn't exist. Perhaps it was deleted?", 'quform-mailchimp'));
        }

        if ($config['trashed']) {
            wp_die(__("You can't edit this item because it is in the Trash. Please restore it and try again.", 'quform-mailchimp'));
        }

        $orderBy = get_user_meta(get_current_user_id(), 'quform_mailchimp_integrations_order_by', true);
        $order = get_user_meta(get_current_user_id(), 'quform_mailchimp_integrations_order', true);

        $forms = $this->repository->formsToSelectArray(null, $orderBy, $order);

        if ( ! Quform::isNonEmptyString($this->options->get('apiKey'))) {
            /* translators: %1$s: open link tag, %2$s: close link tag */
            $this->addMessage('error', '<strong>' . sprintf(esc_html__('The integration will not function until a Mailchimp API key is verified on the %1$splugin settings page%2$s.', 'quform-mailchimp'), '<a href="' . esc_url(admin_url('admin.php?page=quform.mailchimp&sp=settings')) .'">', '</a>') . '</strong>');
        }

        $this->view->with(array(
            'integrationBuilder' => $this->integrationBuilder,
            'integration' => $config,
            'forms' => $forms,
            'mdiPrefix' => apply_filters('quform_mailchimp_mdi_icon_prefix', 'qfb-mdi'),
        ));
    }

    /**
     * Set the page title
     *
     * @return string
     */
    protected function getAdminTitle()
    {
        return __('Edit Mailchimp Integration', 'quform-mailchimp');
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
        $extra[40] = sprintf('<div class="qfb-nav-item qfb-nav-page-info"><i class="qfb-nav-page-icon qfb-nav-mailchimp-icon"></i><span class="qfb-nav-page-title">%s</span></div>', esc_html__('Edit Mailchimp Integration', 'quform-mailchimp'));

        return parent::getNavHtml($currentForm, $extra);
    }
}
