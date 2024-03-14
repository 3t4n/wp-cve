<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */

class Quform_Zapier_Admin_Page_Integrations_Edit extends Quform_Zapier_Admin_Page
{
    /**
     * @var Quform_Zapier_Integration_Builder
     */
    protected $integrationBuilder;

    /**
     * @var Quform_Zapier_Integration_Repository
     */
    protected $integrationRepository;

    /**
     * @var Quform_Zapier_Options
     */
    protected $options;

    /**
     * @param  Quform_ViewFactory                       $viewFactory
     * @param  Quform_Repository                        $repository
     * @param  Quform_Zapier_Integration_Builder     $integrationBuilder
     * @param  Quform_Zapier_Integration_Repository  $integrationRepository
     * @param  Quform_Zapier_Options                 $options
     */
    public function __construct(
        Quform_ViewFactory $viewFactory,
        Quform_Repository $repository,
        Quform_Zapier_Integration_Builder $integrationBuilder,
        Quform_Zapier_Integration_Repository $integrationRepository,
        Quform_Zapier_Options $options
    ) {
        parent::__construct($viewFactory, $repository);

        $this->integrationBuilder = $integrationBuilder;
        $this->integrationRepository = $integrationRepository;
        $this->options = $options;
    }

    public function init()
    {
        $this->template = QUFORM_ZAPIER_TEMPLATE_PATH . '/admin/integrations/edit.php';
    }

    protected function enqueueStyles()
    {
        parent::enqueueStyles();

        wp_enqueue_style('quform-zapier-admin', Quform_Zapier::adminUrl('css/admin.min.css'), array(), QUFORM_ZAPIER_VERSION, 'all');
    }

    protected function enqueueScripts()
    {
        parent::enqueueScripts();

        wp_enqueue_script('quform-zapier-integrations-edit', Quform_Zapier::adminUrl('js/integrations.edit.min.js'), array('jquery'), QUFORM_ZAPIER_VERSION, true);

        wp_localize_script('quform-zapier-integrations-edit', 'quformZapierIntegrationsEditL10n', array(
            'unsavedChanges' => __('You have unsaved changes.', 'quform-zapier'),
            'saveIntegrationNonce' => wp_create_nonce('quform_zapier_save_integration'),
            'integrationSaved' => __('Integration saved', 'quform-zapier'),
            'errorSavingIntegration' => __('Error saving the integration', 'quform-zapier'),
            'correctHighlightedFields' => __('Please correct the highlighted fields and save the integration again', 'quform-zapier'),
            'pleaseSelect' => __('Please select', 'quform-zapier'),
            'pleaseSelectAFormFirst' => __('Please select a form first', 'quform-zapier'),
            'defaultIntegrationConfig' => Quform_Zapier_Integration::getDefaultConfig(),
            'runThisIntegration' => __('Run this integration', 'quform-zapier'),
            'doNotRunThisIntegration' => __('Do not run this integration', 'quform-zapier'),
            'ifAllOfTheseRulesMatch' => __('if all of these rules match', 'quform-zapier'),
            'ifAnyOfTheseRulesMatch' => __('if any of these rules match', 'quform-zapier'),
            'noLogicElements' => __('There are no elements available to use for logic rules.', 'quform-zapier'),
            'noLogicRules' => __('There are no logic rules yet, click "Add logic rule" to add one.', 'quform-zapier'),
            'logicRuleHtml' => $this->integrationBuilder->getLogicRuleHtml(),
            /* translators: %1$s: element admin label, %2$s: element unique ID */
            'adminLabelElementId' => __('%1$s (%2$s)', 'quform-zapier'),
            'is' => __('is', 'quform-zapier'),
            'isNot' => __('is not', 'quform-zapier'),
            'isEmpty' => __('is empty', 'quform-zapier'),
            'isNotEmpty' => __('is not empty', 'quform-zapier'),
            'greaterThan' => __('is greater than', 'quform-zapier'),
            'lessThan' => __('is less than', 'quform-zapier'),
            'contains' => __('contains', 'quform-zapier'),
            'startsWith' => __('starts with', 'quform-zapier'),
            'endsWith' => __('ends with', 'quform-zapier'),
            'enterValue' => __('Enter a value', 'quform-zapier'),
            'additionalFieldHtml' => $this->integrationBuilder->getAdditionalFieldHtml(),
        ));
    }

    /**
     * Process this page and send data to the view
     */
    public function process()
    {
        if ( ! current_user_can('quform_zapier_edit_integrations')) {
            wp_die(__( 'You do not have sufficient permissions to access this page.', 'quform-zapier'), 403);
        }

        if ( ! isset($_GET['id']) || ! is_array($config = $this->integrationRepository->getConfig((int) $_GET['id']))) {
            wp_die(__("You attempted to edit an item that doesn't exist. Perhaps it was deleted?", 'quform-zapier'));
        }

        if ($config['trashed']) {
            wp_die(__("You can't edit this item because it is in the Trash. Please restore it and try again.", 'quform-zapier'));
        }

        $orderBy = get_user_meta(get_current_user_id(), 'quform_zapier_integrations_order_by', true);
        $order = get_user_meta(get_current_user_id(), 'quform_zapier_integrations_order', true);

        $forms = $this->repository->formsToSelectArray(null, $orderBy, $order);

        $this->view->with(array(
            'integrationBuilder' => $this->integrationBuilder,
            'integration' => $config,
            'forms' => $forms,
            'mdiPrefix' => apply_filters('quform_zapier_mdi_icon_prefix', 'qfb-mdi'),
        ));
    }

    /**
     * Set the page title
     *
     * @return string
     */
    protected function getAdminTitle()
    {
        return __('Edit Zapier Integration', 'quform-zapier');
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
        $extra[40] = sprintf('<div class="qfb-nav-item qfb-nav-page-info"><i class="qfb-nav-page-icon qfb-nav-zapier-icon"></i><span class="qfb-nav-page-title">%s</span></div>', esc_html__('Edit Zapier Integration', 'quform-zapier'));

        return parent::getNavHtml($currentForm, $extra);
    }
}
