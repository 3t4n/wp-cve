<?php

/**
 * Controller for fetching surveys in the Surveys, Pages & Forms block.
 *
 * @package    Dotdigital_WordPress
 */
namespace Dotdigital_WordPress\Includes\Rest;

use Dotdigital_WordPress\Includes\Client\Dotdigital_WordPress_Surveys;
class Dotdigital_WordPress_Surveys_Controller
{
    /**
     * @var Dotdigital_WordPress_Surveys
     */
    private $dotdigital_surveys;
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->dotdigital_surveys = new Dotdigital_WordPress_Surveys();
    }
    /**
     * Register the routes for the objects of the controller.
     */
    public function register()
    {
        register_rest_route('dotdigital/v1', '/surveys/', array('methods' => 'GET', 'callback' => array($this, 'fetch_surveys'), 'permission_callback' => function () {
            return current_user_can('manage_options');
        }));
    }
    /**
     * Return content
     *
     * @return \WP_Error|\WP_HTTP_Response|\WP_REST_Response
     */
    public function fetch_surveys()
    {
        $surveys = array();
        try {
            $surveys = $this->dotdigital_surveys->list_surveys();
        } catch (\Exception $e) {
            \error_log($e->getMessage());
        }
        $filtered_surveys = \array_map(function ($survey) {
            if ($survey->getState() == 'Active' && \strpos($survey->getUrl(), '/p/')) {
                return array('label' => $survey->getName(), 'value' => $survey->getUrl());
            }
            return \false;
        }, $surveys);
        \array_unshift($filtered_surveys, array('label' => '-- Please select --', 'value' => ''));
        $filtered_surveys = \array_values(\array_filter($filtered_surveys));
        return rest_ensure_response($filtered_surveys);
    }
}
