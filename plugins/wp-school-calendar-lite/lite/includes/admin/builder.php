<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class WP_School_Calendar_Lite_Builder {

    private static $_instance = NULL;

    /**
     * Initialize all variables, filters and actions
     */
    public function __construct() {
        add_action( 'wpsc_builder_options', array( $this, 'builder_options' ) );
    }

    /**
     * retrieve singleton class instance
     * @return instance reference to plugin
     */
    public static function instance() {
        if ( NULL === self::$_instance ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    private function show_upgrade_message() {
        ?>
        <div class="wpsc-upgrade-panel">
            <div class="wpsc-upgrade-panel__description"><?php echo __( "Please upgrade to the PRO plan to unlock these features.", 'wp-school-calendar' ) ?></div>
            <div class="wpsc-upgrade-panel__button"><a href="<?php echo wpsc_fs()->get_trial_url() ?>"><?php echo __( 'Upgrade to Pro', 'wp-school-calendar' ) ?></a></div>
        </div>
        <?php
    }
    
    public function builder_options( $calendar ) {
        ?>
        <div class="wpsc-builder-option-group">
            <div class="wpsc-builder-option-heading"><button type="button" data-target="wpsc-builder-range-navigation-option-items"><?php echo __( 'Range Navigation', 'wp-school-calendar' ) ?><span class="wpsc-builder-option-icon"></span></button></div>
            <div id="wpsc-builder-range-navigation-option-items" class="wpsc-builder-option-items" style="display:none">
                <div class="wpsc-builder-option">
                    <div class="wpsc-builder-option-field">
                        <?php $this->show_upgrade_message() ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="wpsc-builder-option-group">
            <div class="wpsc-builder-option-heading"><button type="button" data-target="wpsc-builder-filter-navigation-option-items"><?php echo __( 'Filter Navigation', 'wp-school-calendar' ) ?><span class="wpsc-builder-option-icon"></span></button></div>
            <div id="wpsc-builder-filter-navigation-option-items" class="wpsc-builder-option-items" style="display:none">
                <div class="wpsc-builder-option">
                    <div class="wpsc-builder-option-field">
                        <?php $this->show_upgrade_message() ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="wpsc-builder-option-group">
            <div class="wpsc-builder-option-heading"><button type="button" data-target="wpsc-builder-download-navigation-option-items"><?php echo __( 'Download Navigation', 'wp-school-calendar' ) ?><span class="wpsc-builder-option-icon"></span></button></div>
            <div id="wpsc-builder-download-navigation-option-items" class="wpsc-builder-option-items" style="display:none">
                <div class="wpsc-builder-option">
                    <div class="wpsc-builder-option-field">
                        <?php $this->show_upgrade_message() ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="wpsc-builder-option-group">
            <div class="wpsc-builder-option-heading"><button type="button" data-target="wpsc-builder-subscribe-navigation-option-items"><?php echo __( 'Subscribe Navigation', 'wp-school-calendar' ) ?><span class="wpsc-builder-option-icon"></span></button></div>
            <div id="wpsc-builder-subscribe-navigation-option-items" class="wpsc-builder-option-items" style="display:none">
                <div class="wpsc-builder-option">
                    <div class="wpsc-builder-option-field">
                        <?php $this->show_upgrade_message() ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="wpsc-builder-option-group">
            <div class="wpsc-builder-option-heading"><button type="button" data-target="wpsc-builder-tooltip-option-items"><?php echo __( 'Tooltip / Popup', 'wp-school-calendar' ) ?><span class="wpsc-builder-option-icon"></span></button></div>
            <div id="wpsc-builder-tooltip-option-items" class="wpsc-builder-option-items" style="display:none">
                <div class="wpsc-builder-option">
                    <div class="wpsc-builder-option-field">
                        <?php $this->show_upgrade_message() ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="wpsc-builder-option-group">
            <div class="wpsc-builder-option-heading"><button type="button" data-target="wpsc-builder-pdf-settings-option-items"><?php echo __( 'PDF Settings', 'wp-school-calendar' ) ?><span class="wpsc-builder-option-icon"></span></button></div>
            <div id="wpsc-builder-pdf-settings-option-items" class="wpsc-builder-option-items" style="display:none">
                <div class="wpsc-builder-option">
                    <div class="wpsc-builder-option-field">
                        <?php $this->show_upgrade_message() ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
}

WP_School_Calendar_Lite_Builder::instance();