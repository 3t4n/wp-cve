<?php

namespace HQRentalsPlugin\HQRentalsElementor;

class HQRentalsElementorBoostrap
{
    protected $dependencies = array(
        ABSPATH . 'wp-admin/includes/plugin.php'
    );
    public function __construct()
    {
        $this->requireDependencies();
        $this->theme = wp_get_theme();
    }
    public function boostrapElementor()
    {
        if (is_plugin_active('elementor/elementor.php')) {
            HQRentalsElementorExtension::instance();
            $this->resolveFileForAucapinaTheme();
        }
    }
    public function requireDependencies()
    {
        foreach ($this->dependencies as $file) {
            if (file_exists($file)) {
                require_once($file);
            }
        }
    }
    public function resolveFileForAucapinaTheme()
    {
        if (
            $this->theme->stylesheet === 'aucapina' or
            $this->theme->stylesheet === 'aucapina-child' or
            $this->theme->stylesheet === 'aucapina_child'
        ) {
            $themeDeps = array(
                plugin_dir_path(__FILE__) . 'aucapina/HQRentalsElementorAucapinaReservationForm.php',
                plugin_dir_path(__FILE__) . 'aucapina/templates/page-vehicle-class.php',
            );
            foreach ($themeDeps as $file) {
                if (file_exists($file)) {
                    require_once($file);
                }
            }
        }
    }
}
