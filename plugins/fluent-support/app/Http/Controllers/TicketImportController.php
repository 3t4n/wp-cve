<?php
namespace FluentSupport\App\Http\Controllers;

use FluentSupport\App\Services\Tickets\Importer\MigratorService;
use FluentSupport\Framework\Request\Request;
use FluentSupport\App\Services\Tickets\Importer\BaseImporter;


class TicketImportController
{
    public function getStats ( MigratorService $importService )
    {
        $stats = $importService->getStats();
        if(!$stats) {
            return [];
        }
        return $stats;
    }

    public function importTickets ( MigratorService $importService, Request $request )
    {
        return $importService->handleImport( $request->getSafe('page', 'intval'), $request->getSafe('handler'), $request->getSafe('query', []) );
    }

    public function deleteTickets (MigratorService $importService, Request $request)
    {
        return $importService->deleteTickets($request->getSafe('page', 'intval'), $request->getSafe('handler'));
    }
}
