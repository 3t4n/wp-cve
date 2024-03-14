<?php

namespace FluentSupport\App\Http\Controllers;

use FluentSupport\App\Modules\Reporting\Reporting;
use FluentSupport\App\Modules\StatModule;
use FluentSupport\App\Services\Helper;
use FluentSupport\Framework\Request\Request;

/**
 * ReportingController class for REST API
 * This class is responsible for getting data for all request related to report
 * @package FluentSupport\App\Http\Controllers
 *
 * @version 1.0.0
 */
class ReportingController extends Controller
{
    /**
     * getOverallReports method will return the overall statistics of all ticket by ticket statuses
     * The response will have an array with ticket number by ticket status
     * @param Request $request
     * @return array
     */
    public function getOverallReports(Request $request)
    {
        return [
            'overall_reports' => StatModule::getOverAllStats(),
            'today_reports' => StatModule::getTodayStats(),
        ];
    }

    public function getActiveTicketsByProduct()
    {
        return [
            'stats' => StatModule::getActiveTicketsByProductStats()
        ];
    }

    /**
     * getTicketsChart method will generate statistics for all tickets within a date range and return ticket number by date
     * @param Request $request
     * @param Reporting $reporting
     * @return array
     */
    public function getTicketsChart(Request $request, Reporting $reporting)
    {
        list($from, $to) = $request->getSafe('date_range', 'sanitize_text_field') ?: ['', ''];

        $filter = [
            'agent_id' => $request->getSafe('agent_id', 'intval') ?: null,
            'product_id' => $request->getSafe('product_id', 'intval') ?: null,
            'mailbox_id' => $request->getSafe('mailbox_id', 'intval') ?: null,
        ];

        $stats = $reporting->getTicketsGrowth($from, $to, $filter);

        return [
            'stats' => $stats
        ];
    }

    /**
     * getResolveChart method will generate statistics for closed tickets within a date range and return ticket number by date
     * @param Request $request
     * @param Reporting $reporting
     * @return array
     */
    public static function getResolveChart(Request $request, Reporting $reporting): array
    {
        $type = $request->getSafe('type', 'sanitize_text_field');
        list($from, $to) = $request->getSafe('date_range', 'sanitize_text_field') ?: ['', ''];

        $filter = [
            'agent_id' => $request->getSafe('agent_id', 'intval') ?: null,
            'product_id' => $request->getSafe('product_id', 'intval') ?: null,
            'mailbox_id' => $request->getSafe('mailbox_id', 'intval') ?: null,
        ];

        $stats = $reporting->getTicketResolveGrowth($from, $to, $filter,$type);

        return [
            'stats' => $stats
        ];
    }

    /**
     * getResponseChart method will generate response statistics for ticket by date range
     * @param Request $request
     * @param Reporting $reporting
     * @return array
     */
    public function getResponseChart(Request $request, Reporting $reporting)
    {
        list($from, $to) = $request->getSafe('date_range', 'sanitize_text_field') ?: ['', ''];
        $filter = [];
        $stats = $reporting->getResponseGrowth($from, $to);

        if($person_id = $request->getSafe('agent_id', 'intval')) {
            $filter['person_id'] = $person_id;
            $stats = $reporting->getResponseGrowth($from, $to, $filter);
        }

        return [
            'stats' => $stats
        ];
    }

    /**
     * getAgentsSummary method will generate summary for agent
     * This method will count closed tickets, open tickets, responses/interactions with ticket by agent within a date range
     * @param Request $request
     * @param Reporting $reporting
     * @return array
     */
    public function getAgentsSummary(Request $request, Reporting $reporting)
    {
        return [
          'summary' =>  $reporting->agentSummary($request->getSafe('from'), $request->getSafe('to'))
        ];
    }

    /**
     * getAgentOverallReports method will return the overall statistics report for logged-in agent
     * @param Request $request
     * @return array
     */
    public function getAgentOverallReports(Request $request): array
    {
        $agent =  Helper::getAgentByUserId(get_current_user_id());

        return [
            'overall_reports' => StatModule::getAgentOverallStats($agent->id),
            'today_reports' => StatModule::getTodayStats($agent->id)
        ];
    }

    /**
     * getResponseGrowthChart method will generate response statistics for ticket by date range for product or mailbox
     * @param Request $request
     * @param Reporting $reporting
     * @return array
     */
    public static function getResponseGrowthChart(Request $request,Reporting $reporting): array
    {
        $type = $request->getSafe('type', 'sanitize_text_field');
        list($from, $to) = $request->getSafe('date_range', 'sanitize_text_field') ?: ['', ''];

        $filter = [
            'product_id' => $request->getSafe('product_id', 'intval') ?: null,
            'mailbox_id' => $request->getSafe('mailbox_id', 'intval') ?: null,
        ];

        $stats = $reporting->getResponseGrowthChart($from, $to, $filter,$type);

        return [
            'stats' => $stats
        ];
    }

    /**
     * getProductsSummary method will generate summary for product
     * This method will count closed tickets, open tickets, responses, interactions with ticket by agent within a date range
     * @param Request $request
     * @param Reporting $reporting
     * @return array
     */
    public static function getProductsSummary(Request $request,Reporting $reporting): array
    {
        return [
            'summary' =>  $reporting->getSummary('product',$request->getSafe('from', 'sanitize_text_field'), $request->getSafe('to', 'sanitize_text_field'))
        ];

    }

    /**
     * getMailBoxesSummary method will generate summary for mailbox
     * This method will count closed tickets, open tickets, responses, interactions with ticket by agent within a date range
     * @param Request $request
     * @param Reporting $reporting
     * @return array
     */
    public static function getMailBoxesSummary(Request $request,Reporting $reporting): array
    {
        return [
            'summary' =>  $reporting->getSummary('mailbox',$request->getSafe('from'), $request->getSafe('to'))
        ];
    }

    /**
     * getAgentResolveChart method will generate ticket data for resolved ticket
     * @param Request $request
     * @param Reporting $reporting
     * @return array
     */
    public function getAgentResolveChart(Request $request, Reporting $reporting)
    {
        //Get logged in agent information
        $agent =  Helper::getAgentByUserId(get_current_user_id());
        list($from, $to) = $request->getSafe('date_range', 'sanitize_text_field') ?: ['', ''];

        return [
            'stats' => $reporting->getTicketResolveGrowth($from, $to, ['agent_id' => $agent->id])
        ];
    }

    /**
     * getAgentResponseChart method will generate the statistics of response by agent in tickets within date range
     * @param Request $request
     * @param Reporting $reporting
     * @return array
     */
    public function getAgentResponseChart(Request $request, Reporting $reporting)
    {
        $agent =  Helper::getAgentByUserId(get_current_user_id());
        list($from, $to) = $request->getSafe('date_range', 'sanitize_text_field') ?: ['', ''];

        return [
            'stats' => $reporting->getResponseGrowth($from, $to, ['person_id' => $agent->id])
        ];
    }

    /**
     * getPersonalSummary method will generate summary for specific agent
     * This method will count closed tickets, open tickets, responses/interactions with ticket by agent within a date range
     * @param Reporting $reporting
     * @param Request $request
     * @return array
     */
    public function getPersonalSummary(Reporting $reporting, Request $request)
    {
        $agent =  Helper::getAgentByUserId(get_current_user_id());

        return [
            'summary' =>  $reporting->agentSummary($request->getSafe('from', 'sanitize_text_field'), $request->getSafe('to', 'sanitize_text_field'), $agent->id)
        ];
    }
}
