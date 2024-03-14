<?php

namespace FluentSupport\App\Hooks\Handlers;

use FluentSupport\App\Modules\PermissionManager;
use FluentSupport\App\Services\Helper;
use FluentSupport\App\Modules\Reporting\Reporting;
use FluentSupport\App\Services\Csv\CsvWriter;

class DataExporter
{
    public function exportReport()
    {
        $this->verifyRequest();
        $csvWriter = new CsvWriter();
        $request = Helper::FluentSupport('request');
        $from_date = $request->getSafe('from_date', 'sanitize_text_field');
        $to_date = $request->getSafe('to_date', 'sanitize_text_field');
        $columns = $request->get('columns', []);
        $agents = $request->get('agents', []);
        if (empty($columns)) {
            $columns = Helper::getExportOptions();
        }
        $columns = $this->defineColumns($columns);

        $reporting = new Reporting();
        $data = $reporting->agentSummary($from_date, $to_date, $agents);

        $csvWriter->insertOne(array_keys($columns));

        $rows = [];
        foreach ($data as $summary) {
            $row = [];
            foreach ($columns as $key => $column) {
                if (isset($summary[$column])) {
                    $row[$key] = $summary[$column];
                } else if (isset($summary['stats'][$column])) {
                    $row[$key] = $summary['stats'][$column];
                } else if (isset($summary['active_stat'][$column])) {
                    $row[$key] = $summary['active_stat'][$column];
                }
            }
            $rows[] = $row;
        }

        $csvWriter->insertAll($rows);
        $csvWriter->output('agent_report-' . date('Y-m-d_H-i-s') . '.csv');
        die();
    }

    private function defineColumns($columns)
    {
        $arr = [
            'Agent First Name' => 'first_name',
            'Agent Last Name'  => 'last_name',
            'Agent Full Name'  => 'full_name',
            'Responses'        => 'responses',
            'Interactions'     => 'interactions',
            'Open Tickets'     => 'opens',
            'Closed'           => 'closed',
            'Waiting Tickets'  => 'waiting_tickets',
            'Average Waiting'  => 'average_waiting',
            'Max Waiting'      => 'max_waiting',
        ];

        if (Helper::isAgentFeedbackEnabled()) {
            $arr['Likes'] = 'likes';
            $arr['Dislikes'] = 'dislikes';
        }

        return array_filter($arr, function ($column, $key) use ($columns) {
            return in_array($key, $columns);
        }, ARRAY_FILTER_USE_BOTH);
    }

    private function getCsvWriter()
    {
        if (!class_exists('\League\Csv\Writer')) {
            include FLUENT_SUPPORT_PLUGIN_PATH . 'app/Services/Libs/csv/autoload.php';
        }

        return \League\Csv\Writer::createFromFileObject(new \SplTempFileObject());
    }

    private function verifyRequest()
    {
        $permission = 'fst_view_all_reports';
        if (PermissionManager::currentUserCan($permission)) {
            return true;
        }

        die('You do not have permission');
    }
}
