<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */

class Quform_Zapier_Admin_Page_Factory
{
    /**
     * @var Quform_ViewFactory
     */
    protected $viewFactory;

    /**
     * @var Quform_Zapier_Integration_Repository
     */
    protected $integrationRepository;

    /**
     * @var Quform_Repository
     */
    protected $repository;

    /**
     * @var Quform_Options
     */
    protected $options;

    /**
     * @var Quform_Form_Factory
     */
    protected $formFactory;

    /**
     * @var Quform_Builder
     */
    protected $builder;

    /**
     * @var Quform_Zapier_Options
     */
    protected $zapierOptions;

    /**
     * @var Quform_Zapier_Integration_Builder
     */
    protected $integrationBuilder;
    /**
     * @var Quform_Zapier_Permissions
     */
    protected $permissions;

    /**
     * @param  Quform_ViewFactory                       $viewFactory
     * @param  Quform_Zapier_Integration_Repository  $integrationRepository
     * @param  Quform_Repository                        $repository
     * @param  Quform_Options                           $options
     * @param  Quform_Form_Factory                      $formFactory
     * @param  Quform_Builder                           $builder
     * @param  Quform_Zapier_Options                 $zapierOptions
     * @param  Quform_Zapier_Integration_Builder     $integrationBuilder
     * @param  Quform_Zapier_Permissions             $permissions
     */
    public function __construct(
        Quform_ViewFactory $viewFactory,
        Quform_Zapier_Integration_Repository $integrationRepository,
        Quform_Repository $repository,
        Quform_Options $options,
        Quform_Form_Factory $formFactory,
        Quform_Builder $builder,
        Quform_Zapier_Options $zapierOptions,
        Quform_Zapier_Integration_Builder $integrationBuilder,
        Quform_Zapier_Permissions $permissions
    ) {
        $this->viewFactory = $viewFactory;
        $this->integrationRepository = $integrationRepository;
        $this->repository = $repository;
        $this->options = $options;
        $this->formFactory = $formFactory;
        $this->builder = $builder;
        $this->zapierOptions = $zapierOptions;
        $this->integrationBuilder = $integrationBuilder;
        $this->permissions = $permissions;
    }

    /**
     * @param   string                    $page  The name of the page
     * @return  Quform_Admin_Page                The page class instance
     * @throws  InvalidArgumentException         If the page class does not exist
     */
    public function create($page)
    {
        $method = 'create' . $page . 'Page';

        if (method_exists($this, $method)) {
            return $this->$method();
        }

        throw new InvalidArgumentException('Method not found to create page: ' . $page);
    }

    /**
     * @return Quform_Admin_Page
     */
    protected function createZapierPage()
    {
        switch ($this->getSubPage()) {
            case 'list':
            default:
                $page = new Quform_Zapier_Admin_Page_Integrations_List($this->viewFactory, $this->repository, $this->zapierOptions, new Quform_Zapier_Integration_List_Table($this->integrationRepository, $this->repository, $this->options), $this->integrationRepository);
                break;
            case 'add':
                $page = new Quform_Zapier_Admin_Page_Integrations_Add($this->viewFactory, $this->repository);
                break;
            case 'edit':
                $page = new Quform_Zapier_Admin_Page_Integrations_Edit($this->viewFactory, $this->repository, $this->integrationBuilder, $this->integrationRepository, $this->zapierOptions);
                break;
            case 'settings':
                $page = new Quform_Zapier_Admin_Page_Settings($this->viewFactory, $this->repository, $this->zapierOptions, $this->permissions);
                break;
        }

        return $page;
    }

    /**
     * Get the sub page query var
     *
     * @return string|null
     */
    protected function getSubPage()
    {
        return Quform::get($_GET, 'sp');
    }
}
