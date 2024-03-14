<?php

namespace YTP\Database;

class Presets
{
    protected $table;
    protected $version = 2;
    protected $name = 'yt_player_presets';

    public function __construct(Table $table){
        $this->table = $table;
    }

    public function getName(){
        return $this->name;
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
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name VARCHAR(256) NOT NULL,
            preset text NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
            PRIMARY KEY (`id`),
            UNIQUE KEY `name` (`name`)
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


