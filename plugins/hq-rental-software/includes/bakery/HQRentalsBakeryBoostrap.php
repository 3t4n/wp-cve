<?php

namespace HQRentalsPlugin\HQRentalsBakery;

class HQRentalsBakeryBoostrap
{
    protected $dependencies = array(
        ABSPATH . 'wp-admin/includes/plugin.php',
    );
    protected $bakeryDeps = ABSPATH . 'wp-content/plugins/js_composer/js_composer.php';

    public function __construct()
    {
        $this->requireDependencies();
        $this->theme = wp_get_theme();
        add_filter('theme_page_templates', array($this, 'addWheelsberryTemplateFiles'), 10, 4);
        add_filter('page_template', array($this, 'loadWheelsberryTemplateFiles'), 20, 5);
    }
    public function boostrapBakery()
    {
        if (is_plugin_active('js_composer/js_composer.php')) {
            require_once($this->bakeryDeps);
            $this->resolveBakeryItems();
            $this->resolveFileForMotorsTheme();
            $this->resolveFileForRentitTheme();
            $this->resolveFileForWheelsberryTheme();
            $this->resolveTurbolTheme();
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
    public function resolveBakeryItems()
    {
        $deps = array(
            plugin_dir_path(__FILE__) . 'shortcodes/HQRentalsBakeryReservationFormShortcode.php',
            plugin_dir_path(__FILE__) . 'shortcodes/HQRentalsBakeryReservationsShortcode.php',
            plugin_dir_path(__FILE__) . 'shortcodes/HQRentalsBakeryVehicleGridShortcode.php',
            plugin_dir_path(__FILE__) . 'shortcodes/HQRentalsBakeryPlacesReservationForm.php',
            plugin_dir_path(__FILE__) . 'shortcodes/HQRentalsBakerySimpleReservationForm.php'
        );
        $this->addFiles($deps);
    }
    public function resolveFileForMotorsTheme()
    {
        if (
            $this->theme->stylesheet === 'motors' or
            $this->theme->stylesheet === 'motors-child' or
            $this->theme->stylesheet === 'motors_child'
        ) {
            $themeDeps = array(
                plugin_dir_path(__FILE__) . 'motors/HQRentalBakeryMotorsVehicleGridShortcode.php',
                plugin_dir_path(__FILE__) . 'motors/HQRentalBakeryMotorsReservationFormShortcode.php',
            );
            $this->addFiles($themeDeps);
        }
    }
    public function resolveFileForRentitTheme()
    {
        if (
            $this->theme->stylesheet === 'rentit' or
            $this->theme->stylesheet === 'rentit-child' or
            $this->theme->stylesheet === 'rentit_child'
        ) {
            $themeDeps = array(
                plugin_dir_path(__FILE__) . 'rentit/HQRentalBakeryRentitReservationFormShortcode.php',
                plugin_dir_path(__FILE__) . 'rentit/HQRentalBakeryRentitSliderShortcode.php',
            );
            $this->addFiles($themeDeps);
        }
    }
    public function resolveFileForWheelsberryTheme()
    {
        $themeDeps = array(
            plugin_dir_path(__FILE__) . 'wheelsberry/HQRentalBakeryWheelsberryReservationFormShortcode.php',
            plugin_dir_path(__FILE__) . 'wheelsberry/HQRentalBakeryWheelsberryLocationsMap.php',
        );
        $this->addFiles($themeDeps);
    }
    public function resolveFiles()
    {
        return array_merge(
            $this->dependencies,
            array(
                plugin_dir_path(__FILE__) . 'shortcodes/HQRentalsBakeryVehicleGridShortcode.php',
                plugin_dir_path(__FILE__) . 'shortcodes/HQRentalsBakeryReservationsShortcode.php',
                plugin_dir_path(__FILE__) . 'shortcodes/HQRentalsBakeryReservationFormShortcode.php',
            )
        );
    }
    public function addWheelsberryTemplateFiles($templates)
    {
        $templates['hq-wheelsberry-homepage.php'] = __('Homepage - HQ Wheelsberry', 'hq-wordpress');
        return $templates;
    }

    public function loadWheelsberryTemplateFiles($page_template)
    {

        if (get_page_template_slug() == 'hq-wheelsberry-homepage.php') {
            $page_template = plugin_dir_path(__FILE__) . 'wheelsberry/templates/hq-wheelsberry-homepage.php';
        }
        return $page_template;
    }
    public function resolveTurbolTheme()
    {
        if (
            $this->theme->stylesheet === 'turbo' or
            $this->theme->stylesheet === 'turbo-child' or
            $this->theme->stylesheet === 'turbo_child'
        ) {
            $themeDeps = array(
                plugin_dir_path(__FILE__) . 'turbo/HQRentalBakeryTurboReservationForm.php',
            );
            $this->addFiles($themeDeps);
        }
    }
    private function addFiles($arrayOfFiles)
    {
        if (is_array($arrayOfFiles) and count($arrayOfFiles)) {
            foreach ($arrayOfFiles as $file) {
                require_once($file);
            }
        }
    }
}
