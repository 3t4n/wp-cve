<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */

class Quform_Zapier_Integration_Controller
{
    /**
     * @var Quform_Zapier_Integration_Repository
     */
    protected $integrationRepository;

    /**
     * @var Quform_Zapier_Integration_Factory
     */
    protected $integrationFactory;

    /**
     * @var Quform_Zapier_Options
     */
    protected $options;

    /**
     * @param  Quform_Zapier_Integration_Repository  $integrationRepository
     * @param  Quform_Zapier_Integration_Factory     $integrationFactory
     * @param  Quform_Zapier_Options                 $options
     */
    public function __construct(
        Quform_Zapier_Integration_Repository $integrationRepository,
        Quform_Zapier_Integration_Factory $integrationFactory,
        Quform_Zapier_Options $options
    ) {
        $this->integrationRepository = $integrationRepository;
        $this->integrationFactory = $integrationFactory;
        $this->options = $options;
    }

    /**
     * Run any integrations for the given form
     *
     * @param   array        $result  The result to return to the form processor, an empty array by default
     * @param   Quform_Form  $form    The form that is currently being processed
     * @return  array
     */
    public function process(array $result, Quform_Form $form)
    {
        if ( ! $this->options->get('enabled')) {
            return $result;
        }

        $integrations = $this->integrationRepository->getIntegrationsByFormId($form->getId());

        foreach ($integrations as $integration) {
            $integration = $this->integrationFactory->create($integration, $form);

            if ( ! $integration->config('active')) {
                continue;
            }

            if ($integration->config('logicEnabled') && count($integration->config('logicRules'))) {
                if ($form->checkLogicAction($integration->config('logicAction'), $integration->config('logicMatch'), $integration->config('logicRules'))) {
                    $result = $integration->run($result);
                }
            } else {
                $result = $integration->run($result);
            }
        }

        return $result;
    }
}
