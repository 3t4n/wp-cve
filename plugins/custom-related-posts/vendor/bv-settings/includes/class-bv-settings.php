<?php
/**
 * The core component class.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.0.0
 *
 * @package    BV_Settings
 */

/**
 * The core component class.
 *
 * @since      1.0.0
 * @package    BV_Settings
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */
class BV_Settings {
    public $atts;
    public $helpers;

	/**
	 * Make sure all is set up for the component to load.
	 *
	 * @since   1.0.0
	 */
	public function __construct( $atts = array() ) {
        // Set defaults.
        $atts = shortcode_atts( array(
            'uid' => '',
            'menu_priority' => 10,
            'menu_title' => __( 'Settings', 'bv-settings' ),
            'menu_parent' => 'options-general.php',
            'page_title' => __( 'Settings', 'bv-settings' ),
            'page_slug' => false,
            'required_capability' => 'manage_options',
            'settings' => array(),
            'required_addons' => array(),
        ), $atts );

        // Make sure required fields are set.
        $atts['uid'] = sanitize_title( $atts['uid'] );
        if ( ! $atts['uid'] ) {
            throw new Exception( 'You need to initialize the settings with a UID.' );
        }

        // Calculated defaults.
        if ( ! $atts['page_slug'] ) {
            $atts['page_slug'] = 'bv_settings_' . $atts['uid'];
        }
        
        // Save attributes and load helpers.
        $this->atts = $atts;
		$this->load_helpers();
	}

	/**
	 * Load helper classes.
	 *
	 * @since   1.0.0
	 */
	private function load_helpers() {
        require_once( BVS_DIR . 'includes/class-bvs-api.php' );
        $this->helpers['api'] = new BV_API( $this );

        require_once( BVS_DIR . 'includes/class-bvs-menu.php' );
        $this->helpers['menu'] = new BV_Menu( $this );

        require_once( BVS_DIR . 'includes/class-bvs-saver.php' );
        $this->helpers['saver'] = new BV_Saver( $this );

        require_once( BVS_DIR . 'includes/class-bvs-structure.php' );
        $this->helpers['structure'] = new BV_Structure( $this );
    }

    /**
	 * Get the settings structure.
	 *
	 * @since   1.0.0
	 * @param   mixed $resolve_callbacks Wether to resolve the callbacks.
	 */
	public function get_structure( $resolve_callbacks = false ) {
		return $this->helpers['structure']->get_structure( $resolve_callbacks );
	}
    
    /**
	 * Get the value for a specific setting.
	 *
	 * @since   1.0.0
	 * @param   mixed $setting Setting to get the value for.
	 */
	public function get( $setting ) {
		return $this->helpers['structure']->get( $setting );
    }
    
    /**
	 * Get all the settings.
	 *
	 * @since   1.0.0
	 */
	public function get_settings() {
		return $this->helpers['structure']->get_settings();
    }

    /**
	 * Get all the settings with defaults if not set.
	 *
	 * @since   1.0.0
	 */
	public function get_settings_with_defaults() {
		return $this->helpers['structure']->get_settings_with_defaults();
	}
    
    /**
	 * Get the default for a specific setting.
	 *
	 * @since   1.0.0
	 * @param   mixed $setting Setting to get the default for.
	 */
	public function get_default( $setting ) {
		return $this->helpers['structure']->get_default( $setting );
    }
    
	/**
	 * Get the default settings.
	 *
	 * @since   1.0.0
	 * @param	boolean $force_update Wether to force an update of the cache.
	 */
	public function get_defaults( $force_update = false ) {
		return $this->helpers['structure']->get_defaults( $force_update );
    }
    
    /**
	 * Update the settings.
	 *
	 * @since	1.0.0
	 * @param	array $settings_to_update Settings to update.
	 */
	public function update_settings( $settings_to_update ) {
		return $this->helpers['saver']->update_settings( $settings_to_update );
	}
}
