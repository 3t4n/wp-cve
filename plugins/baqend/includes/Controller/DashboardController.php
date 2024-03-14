<?php

namespace Baqend\WordPress\Controller;

use Baqend\WordPress\Admin\View;
use Baqend\WordPress\Loader;
use Baqend\WordPress\OptionEnums;
use Baqend\WordPress\Plugin;
use Psr\Log\LoggerInterface;

/**
 * Dashboard Controller for Wordpress dashboard
 * Date: 03.07.2018
 * @author Brigitte Kwasny
 * @package Baqend\WordPress\Controller
 */
class DashboardController extends Controller {

    /**
     * @var View
     */
    private $view;

    public function __construct( Plugin $plugin, LoggerInterface $logger ) {
        parent::__construct( $plugin, $logger );
        $this->view = new View();
    }

    public function register( Loader $loader ) {
        $loader->add_action( 'wp_dashboard_setup', [ $this, 'add_widget' ] );
    }

    public function add_widget() {
        $logged_in = $this->plugin->app_name !== null;
        $stats     = $this->plugin->stats_service->load_stats();
        if ( ! $logged_in || $stats && $stats->is_plesk_user() ) {
            return;
        }

        $is_exceeded = $stats ? $stats->is_exceeded() : false;
        $speed_kit  = $this->plugin->options->get( OptionEnums::SPEED_KIT_ENABLED );
        $comparison = $this->plugin->analyzer_service->load_latest_comparison( $speed_kit, $is_exceeded );

        $fields_array = null;
        if ( $comparison !== null ) {
            $fields = $comparison->getFields();
            $ttfb   = $fields['ttfb'];
            $values = $fields['firstMeaningfulPaint'];

            $fields_array = [ 'firstMeaningfulPaint' => $values, 'ttfb' => $ttfb ];
        }

        add_meta_box( 'baqend_widget', 'Speed Kit', function () use ( $speed_kit, $is_exceeded, $fields_array, $stats ) {
            $this->view->set_template( 'dashboardWidget.php' )
                       ->assign( 'speed_kit', $speed_kit )
                       ->assign( 'exceeded', $is_exceeded )
                       ->assign( 'fields_array', $fields_array )
                       ->assign( 'stats', $stats )
                       ->render();
        }, 'dashboard', 'normal', 'high' );
    }
}
