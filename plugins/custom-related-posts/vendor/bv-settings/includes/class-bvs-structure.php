<?php
/**
 * Handle the settings structure.
 *
 * @link       http://bootstrapped.ventures
 * @since      1.0.0
 *
 * @package    BV_Settings
 * @author     Brecht Vandersmissen <brecht@bootstrapped.ventures>
 */

class BV_Structure {
	private $bvs;
	
	private $structure = array();
	private $defaults = array();
	private $settings = array();

	/**
	 * Store main instance and initialize.
	 *
	 * @since   1.0.0
	 */
	public function __construct( $bvs ) {
		$this->bvs = $bvs;
		$this->set_structure( $this->bvs->atts['settings'] );
	}

	/**
	 * Set the settings structure.
	 *
	 * @since   1.0.0
     * @param   array $settings_structure Settings structure.
	 */
	public function set_structure( $settings_structure ) { 
        // Associate IDs.
        $structure = array();

        $index = 1;
        foreach ( $settings_structure as $group ) {
            if ( isset( $group['id'] ) ) {
                $id = $group['id'];
            } else {
                $id = 'group_' . $index;
                $index++;
            }

            $structure[ $id ] = $group;
        }

        $this->structure = $structure;
    }

    /**
	 * Get the settings structure.
	 *
	 * @since   1.0.0
	 * @param   mixed $resolve_callbacks Wether to resolve the callbacks.
	 */
	public function get_structure( $resolve_callbacks = false ) {
		$structure = apply_filters( $this->bvs->atts['uid'] . '_settings_structure', $this->structure );

		if ( $resolve_callbacks ) {
			// Loop over structure to find settings with callback.
			foreach ( $structure as $group_id => $group ) {
				if ( isset( $group['settings'] ) ) {
					foreach ( $group['settings'] as $setting_id => $setting ) {
						if ( isset( $setting['optionsCallback'] ) ) {
							$structure[ $group_id ]['settings'][ $setting_id ]['options'] = call_user_func( $setting['optionsCallback'], $setting );
						}
					}
				}

				if ( isset( $group['subGroups'] ) ) {
					foreach ( $group['subGroups'] as $sub_group_id => $sub_group ) {
						if ( isset( $sub_group['settings'] ) ) {
							foreach ( $sub_group['settings'] as $setting_id => $setting ) {
								if ( isset( $setting['optionsCallback'] ) ) {
									$structure[ $group_id ]['subGroups'][ $sub_group_id ]['settings'][ $setting_id ]['options'] = call_user_func( $setting['optionsCallback'], $setting );
								}
							}
						}
					}
				}
			}

			apply_filters( $this->bvs->atts['uid'] . '_settings_structure_callbacks', $structure );
		}

		return $structure;
	}
    
    /**
	 * Get the value for a specific setting.
	 *
	 * @since   1.0.0
	 * @param   mixed $setting Setting to get the value for.
	 */
	public function get( $setting ) {
		$settings = $this->get_settings();

		if ( isset( $settings[ $setting ] ) ) {
			return $settings[ $setting ];
		} else {
			return $this->get_default( $setting );
		}
    }
    
    /**
	 * Get all the settings.
	 *
	 * @since   1.0.0
	 */
	public function get_settings() {
		// Lazy load settings.
		if ( empty( $this->settings ) ) {
			$this->set_settings( apply_filters( $this->bvs->atts['uid'] . '_settings', get_option( $this->bvs->atts['uid'] . '_settings', array() ) ) );
		}

		return $this->settings;
	}

	/**
	 * Set the settings.
	 *
	 * @since   1.0.0
	 * @param   array $settings Settings to set.
	 */
	public function set_settings( $settings ) {
		$this->settings = $settings;
    }

    /**
	 * Get all the settings with defaults if not set.
	 *
	 * @since   1.0.0
	 */
	public function get_settings_with_defaults() {
		$settings = $this->get_settings();
		$defaults = $this->get_defaults();

		return array_merge( $defaults, $settings );
	}
    
    /**
	 * Get the default for a specific setting.
	 *
	 * @since   1.0.0
	 * @param   mixed $setting Setting to get the default for.
	 */
	public function get_default( $setting ) {
		$defaults = $this->get_defaults();
		if ( isset( $defaults[ $setting ] ) ) {
			return $defaults[ $setting ];
		} else {
			// Force defaults cache update.
			$defaults = $this->get_defaults( true );
			if ( isset( $defaults[ $setting ] ) ) {
				return $defaults[ $setting ];
			} else {
				return false;
			}
		}
    }
    
	/**
	 * Get the default settings.
	 *
	 * @since   1.0.0
	 * @param	boolean $force_update Wether to force an update of the cache.
	 */
	public function get_defaults( $force_update = false ) {
		if ( $force_update || empty( $this->defaults ) ) {
			$defaults = array();
			$structure = $this->get_structure();

			// Loop over structure to find settings and defaults.
			foreach ( $structure as $group ) {
				if ( isset( $group['settings'] ) ) {
					foreach ( $group['settings'] as $setting ) {
						if ( isset( $setting['id'] ) && isset( $setting['default'] ) ) {
							$defaults[ $setting['id'] ] = $setting['default'];
						}
					}
				}

				if ( isset( $group['subGroups'] ) ) {
					foreach ( $group['subGroups'] as $sub_group ) {
						if ( isset( $sub_group['settings'] ) ) {
							foreach ( $sub_group['settings'] as $setting ) {
								if ( isset( $setting['id'] ) && isset( $setting['default'] ) ) {
									$defaults[ $setting['id'] ] = $setting['default'];
								}
							}
						}
					}
				}
            }

			$this->defaults = $defaults;
		}

		return $this->defaults;
	}

	/**
	 * Get the settings details.
	 *
	 * @since	1.0.0
	 */
	public function get_details() {
		$details = array();
		$structure = $this->get_structure();

		// Loop over structure to find settings.
		foreach ( $structure as $group ) {
			if ( isset( $group['settings'] ) ) {
				foreach ( $group['settings'] as $setting ) {
					if ( isset( $setting['id'] ) ) {
						$details[ $setting['id'] ] = $setting;
					}
				}
			}

			if ( isset( $group['subGroups'] ) ) {
				foreach ( $group['subGroups'] as $sub_group ) {
					if ( isset( $sub_group['settings'] ) ) {
						foreach ( $sub_group['settings'] as $setting ) {
							if ( isset( $setting['id'] ) ) {
								$details[ $setting['id'] ] = $setting;
							}
						}
					}
				}
			}
		}

		return $details;
	}
}
