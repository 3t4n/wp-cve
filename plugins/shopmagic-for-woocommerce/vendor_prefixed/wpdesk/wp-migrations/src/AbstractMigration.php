<?php

declare (strict_types=1);
namespace ShopMagicVendor\WPDesk\Migrations;

use ShopMagicVendor\Psr\Log\LoggerInterface;
abstract class AbstractMigration
{
    /** @var \wpdb */
    protected $wpdb;
    /** @var LoggerInterface */
    protected $logger;
    public function __construct(\wpdb $wpdb, LoggerInterface $logger)
    {
        $this->wpdb = $wpdb;
        $this->logger = $logger;
    }
    public abstract function up() : bool;
    public function down() : void
    {
    }
}
