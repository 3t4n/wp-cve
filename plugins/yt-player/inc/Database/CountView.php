<?php

namespace YTP\Database;

class CountViews
{
    protected $table;
    protected $version = 1;
    protected $name = 'h5vp_views';

    public function __construct(Table $table)
    {
        $this->table = $table;
    }

    public function getName()
    {
        global $wpdb;
        return $wpdb->prefix . $this->name;
    }

    /**
     * Add videos table
     * This is used for global video analytics
     *
     * @return void
     */
    public function install()
    {
        return $this->table->create($this->name, "
            id bigint(20) unsigned NOT NULL auto_increment,
            user_id bigint(20) unsigned NULL,
            duration bigint(20) unsigned NOT NULL,
            video_id bigint(20) unsigned NOT NULL,
            ip_address varchar(39) DEFAULT '' NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
            updated_at TIMESTAMP NOT NULL,
            PRIMARY KEY  (id),
            KEY user_id (user_id),
            KEY video_id (video_id),
            KEY ip_address (ip_address),
            KEY created_at (created_at),
            KEY updated_at (updated_at)
            ", $this->version);
    }

    /**
     * Uninstall tables
     *
     * @return void
     */
    public function uninstall()
    {
        $this->table->drop($this->getName());
    }
}
