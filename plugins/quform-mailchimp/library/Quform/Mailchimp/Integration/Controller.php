<?php

/**
 * @copyright Copyright (c) 2009-2020 ThemeCatcher (https://www.themecatcher.net)
 */

class Quform_Mailchimp_Integration_Controller
{
    /**
     * @var Quform_Mailchimp_Integration_Repository
     */
    protected $integrationRepository;

    /**
     * @var Quform_Mailchimp_Integration_Factory
     */
    protected $integrationFactory;

    /**
     * @var Quform_Mailchimp_Options
     */
    protected $options;

    /**
     * @param  Quform_Mailchimp_Integration_Repository  $integrationRepository
     * @param  Quform_Mailchimp_Integration_Factory     $integrationFactory
     * @param  Quform_Mailchimp_Options                 $options
     */
    public function __construct(
        Quform_Mailchimp_Integration_Repository $integrationRepository,
        Quform_Mailchimp_Integration_Factory $integrationFactory,
        Quform_Mailchimp_Options $options
    ) {
        $this->integrationRepository = $integrationRepository;
        $this->integrationFactory = $integrationFactory;
        $this->options = $options;
    }

    /**
     * Run any integrations for the given form
     *
     * @param   array        $result
     * @param   Quform_Form  $form
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
                    $integration->run();
                }
            } else {
                $integration->run();
            }
        }

        return $result;
    }
}
