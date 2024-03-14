<?php

namespace CODNetwork\Repositories;

use WP_Queue\Connections\DatabaseConnection;

class CODN_Custom_Queue_Connection extends DatabaseConnection
{
    /** @var CodNetworkRepository  */
    private $codNetworkRepository;

    public function __construct()
    {
        global $wpdb;
        parent::__construct($wpdb);

        $this->codNetworkRepository = CodNetworkRepository::get_instance();
        $this->failures_table = $this->codNetworkRepository->get_table_name_queue_failures();
        $this->jobs_table = $this->codNetworkRepository->get_table_name_queue_job();
    }
}

