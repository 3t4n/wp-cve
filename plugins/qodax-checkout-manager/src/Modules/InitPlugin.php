<?php

namespace Qodax\CheckoutManager\Modules;

use Qodax\CheckoutManager\DB\Migrations\CreateDisplayRulesTable_202310120109;
use Qodax\CheckoutManager\DB\Migrations\CreateFieldsTable;
use Qodax\CheckoutManager\DB\Migrations\UpdateFieldsTable_202310120137;
use Qodax\CheckoutManager\DB\Migrator;

if ( ! defined('ABSPATH')) {
    exit;
}

class InitPlugin extends AbstractModule
{
    public function boot(): void
    {
        add_action('plugins_loaded', [ $this, 'activate' ]);
        register_activation_hook(QODAX_CHECKOUT_MANAGER_PLUGIN_ENTRY, [ $this, 'activate' ]);
    }

    public function activate()
    {
        $migrator = new Migrator();
        $migrator->addMigration(new CreateFieldsTable());
        $migrator->addMigration(new CreateDisplayRulesTable_202310120109());
        $migrator->addMigration(new UpdateFieldsTable_202310120137());
        $migrator->run();
    }
}