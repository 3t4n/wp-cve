<?php

namespace FluentSupport\App\Services\Tickets\Importer;

class MigratorService
{
    /**
     * This `getStats` method will fetch available other systems data
     */
    public function getStats()
    {
        $stats = [];

        if (defined('WPAS_VERSION')) {
            $stats[] = $this->mapClassWithHandler('awesome-support')->stats();
        }

        if (defined('WPSC_VERSION')) {
            $stats[] = $this->mapClassWithHandler('support-candy')->stats();
        }

        if (defined('JSST_PLUGIN_PATH')) {
            $stats[] = $this->mapClassWithHandler('js-helpdesk')->stats();
        }

        if (defined('FLUENTSUPPORTPRO')) {
            $stats[] = $this->mapClassWithHandler('helpscout')->stats();
        }

        if (defined('FLUENTSUPPORTPRO')) {
            $stats[] = $this->mapClassWithHandler('freshdesk')->stats();
            $stats[] = $this->mapClassWithHandler('zendesk')->stats();
        }

        return [
            'stats' => $stats
        ];
    }

    /**
     * This `handleImport` method will handle the ticket import
     * @param int $page // It can be page number or ticket id for inserting ticket
     * @param string $handler
     */
    public function handleImport($page, $handler, $query=[])
    {
        if($query){
            $class = $this->mapClassWithHandler($handler);
            $class->setAccessToken($query['access_token']);
            isset($query['mailbox']) ? $class->setMailboxId($query['mailbox']) : '';
            isset($query['domain']) ? $class->setDomain($query['domain']) : '';
            isset($query['email']) ? $class->setEmail($query['email']) : '';

            return $class->doMigration($page, $handler);
        }

        return $this->mapClassWithHandler($handler)->doMigration($page, $handler);
    }

    public function deleteTickets($page, $handler)
    {
        return $this->mapClassWithHandler($handler)->deleteTickets($page);
    }

    // This method is a helper method of `handleImport` method it's responsible for calling a class by $handler
    private function mapClassWithHandler($handler)
    {
        $namespace = $this->handleNamespace($handler);

        $classMapper = apply_filters('fluent_support/migrator_class_mapper', [
            'awesome-support' => 'AwesomeSupportTickets',
            'support-candy'   => 'SupportCandyTickets',
            'js-helpdesk'     => 'JSHelpdeskTickets',
        ]);

        if(defined('FLUENTSUPPORTPRO')){
            $classMapper['helpscout'] = 'HelpScoutTickets';
            $classMapper['freshdesk'] = 'FreshDeskTickets';
            $classMapper['zendesk'] = 'ZendeskTickets';
        }

        $class = $namespace . $classMapper[$handler];

        return new $class();
    }

    private function handleNamespace($handler)
    {
        $proHandlers = ['helpscout', 'freshdesk','zendesk'];

        $namespace = "FluentSupport\App\Services\Tickets\Importer\\";

        if(defined('FLUENTSUPPORTPRO') && in_array($handler, $proHandlers)){
            $namespace = "FluentSupportPro\App\Services\TicketImporter\\";
        }

        return $namespace;
    }
}
