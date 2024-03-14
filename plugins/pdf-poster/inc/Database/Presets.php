<?php

namespace PDFPro\Database;
// require_once(__DIR__.'/Table.php');
// use PDFPro\Database\Table;

class Presets
{
    protected $table;
    protected $version = 8;
    protected $name = 'pdfposter_presets';

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
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name VARCHAR(256) NOT NULL,
            preset text NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
            updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
            PRIMARY KEY (`id`)
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

$obj = new Presets(new Table());
$obj->install();



// titleFontSize VARCHAR(256) NULL DEFAULT '16px',
//             height VARCHAR(100) NULL DEFAULT '1122px',
//             width VARCHAR(100) NULL DEFAULT '100%',
//             showName VARCHAR(100) NULL DEFAULT 1,
//             print VARCHAR(100) NULL DEFAULT 0,
//             onlyPDF VARCHAR(100) NULL DEFAULT 0,
//             defaultBrowser VARCHAR(100) NULL DEFAULT 0,
//             downloadButton VARCHAR(100) NULL DEFAULT 0,
//             downloadButtonText VARCHAR(256) NULL DEFAULT 'Download File',
//             fullscreenButton VARCHAR(100) NULL DEFAULT 0,
//             fullscreenButtonText VARCHAR(256) NULL DEFAULT 'View Fullscreen',
//             newWindow VARCHAR(100) NULL DEFAULT 0,
//             protect VARCHAR(100) NULL DEFAULT 0,
//             thumbMenu VARCHAR(100) NULL DEFAULT 0,
//             initialPage VARCHAR(100) NULL DEFAULT 0,
//             zoomLevel VARCHAR(100) NULL DEFAULT 'auto',
//             alert VARCHAR(100) NULL DEFAULT 0,
//             lastVersion VARCHAR(100) NULL DEFAULT 0,
//             hrScroll VARCHAR(100) NULL DEFAULT 0,
//             additional VARCHAR(256) NULL,
//             adobeEmbedder VARCHAR(100) NULL DEFAULT 0,
//             adobeOptions VARCHAR(256) NULL DEFAULT '',
//             popupBtnStyle VARCHAR(256) NULL DEFAULT '',
//             popupBtnText VARCHAR(256) NULL DEFAULT '',