<?php 

namespace ADTW;

defined('ABSPATH') || exit;

class AdminTweaks {
    /**
     * Plugin current version
     */
    const VERSION = "3.1";

    /**
     * Plugin name
     */
    const NAME = 'Admin Tweaks';
    
    /**
     * Plugin instance
     *
     * @var object
     */
    private static $_instance = null;

    /**
     * Holds the plugin options
     *
     * @var array
     */
    public $ops = null;

	/**
     * The Singleton's constructor should always be private to prevent direct
     * construction calls with the `new` operator.
	 */
    public function __construct() {
		$this->autoloader();
	}
    
	public static function getInstance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
		}
		return self::$_instance;
	}

    public function init() {
        global $adtw_option;
        if (file_exists(ADTW_PATH . '/inc/config.php')) {
            require_once (ADTW_PATH . '/inc/config.php');
        }
        $this->ops = get_option( $adtw_option, array() );
        SettingsPage::init();
        new HooksAdminBar();
        new HooksAdminMenu();
        new HooksAppearance();
        new HooksDashboard();
        new HooksGeneral();
        new HooksListings();
        new HooksMedia();
        new HooksMediaColumns();
        new HooksPlugins();
        new HooksPluginsExtras();
        new HooksUsers();
        new HooksLogin();
        new HooksMaintenance();
    }

    /**
     * Get option key validating existence
     *
     * @param string $key
     * @return boolean:string:array:object
     */
    public function getop( string $key ) {
        $result = !empty($this->ops[$key]) ? $this->ops[$key] : false;
        return $result;
    }
    
    /**
     * Store values not caught by plugins_loaded
     * - admin menus, cpts, status
     *
     * @return array
     */
    public function setSupport() {
        global $adtw_option, $admin_page_hooks;
        $opt = "$adtw_option-support";
        $support = get_option($opt, array());
        $support['current_menus'] = $this->_buildMenus();
        $support['current_submenus'] = $this->_buildSubMenus();
        $support['current_cpts'] = $this->_buildCPTs();
        $support['current_status'] = $this->_buildStatus();
        $support['current_snippets_name'] = isset($admin_page_hooks['snippets'])
            ? $admin_page_hooks['snippets'] : false;
        update_option($opt, $support);
    }

    /**
     * Store values not caught by plugins_loaded
     * - admin bar
     *
     * @return array
     */
    public function setSupportBar($bar) {
        global $adtw_option;
        $opt = "$adtw_option-support";
        $support = get_option($opt, array());
        $support['current_adminbar'] = $this->_buildAdminbar($bar);
        update_option($opt, $support);
    }

    /**
     * Get stored slug for Snippets
     *
     * @return array
     */
    public function getSnippetsSlug() {
        global $adtw_option;
        $support = get_option("$adtw_option-support", array());
        $current = isset($support['current_snippets_name']) ? $support['current_snippets_name'] : 'snippets';
        return $current;
    }

    /**
     * Get stored Menus
     *
     * @return array
     */
    public function getMenus() {
        global $adtw_option;
        $support = get_option("$adtw_option-support", array());
        $current = isset($support['current_menus']) ? $support['current_menus'] : [];
        return $current;
    }

    /**
     * Get stored CPTs
     *
     * @return array
     */
    public function getCPTs() {
        global $adtw_option;
        $support = get_option("$adtw_option-support", array());
        $current = isset($support['current_cpts']) ? $support['current_cpts'] : [];
        return $current;
    }

    /**
     * Get stored Status
     *
     * @return array
     */
    public function getStatus() {
        global $adtw_option;
        $support = get_option("$adtw_option-support", array());
        $current = isset($support['current_status']) ? $support['current_status'] : [];
        return $current;
    }


    /**
     * Get stored Admin Bar items
     *
     * @return array
     */
    public function getAdminBar() {
        global $adtw_option;
        $support = get_option("$adtw_option-support", array());
        $current = isset($support['current_adminbar']) ? $support['current_adminbar'] : [];
        return $current;
    }


    /**
     * Get option with network (sub)menus
     *
     * @return boolean:string:array:object
     */
    public function getSubMenus() {
        global $adtw_option;
        $all = get_site_option( "$adtw_option-support", [] );
        $ritorna = [];
        $empty = 0;
        do_action( 'qm/debug', ['getSubmenus', $all['current_submenus']] );
        foreach( $all['current_submenus'] as $menu => $submenu ) 
        {
            $count = 0;
            # do not add single submenus
            if ( count($submenu) < 2 ) continue;

            # itererate submenu
            foreach( $submenu as $items ) 
            {
                // Removes digits (update buble, mainly) and html tags
                $title = preg_replace('/[[:digit:]]/','', wp_strip_all_tags($items[0]));    

                $key = strtoupper( str_replace('.php', '', $menu) );
                if ( $key === 'INDEX' ) $key = 'DASHBOARD';
                if ( count(explode('TYPE=',$key)) > 1) {
                    $key = explode('TYPE=',$key)[1];
                }

                if ( $count === 0 ) 
                {
                    $ritorna["titolo-$empty"] = "<span class='dontscape titles'>$key</span>";
                    $empty++;
                }
                
                $newmenu = str_replace('.php', '_php', $menu);
                $newmenu = str_replace('?', '___', $newmenu);
                $newmenu = str_replace('=', '____', $newmenu);
                $submenu = str_replace('.php', '_php', $items[2]);
                $submenu = str_replace('?', '___', $submenu);
                $submenu = str_replace('=', '____', $submenu);
                $ritorna[$newmenu.'__'.$submenu] = "<span class='dontscape'>$title</span>";
                $count++;
            }
        }
        
        return $ritorna;
    }


	/**
	 * Custom function for the callback validation referenced above
	 *
	 * @param array $field          Field array.
	 * @param mixed $value          New value.
	 * @param mixed $existing_value Existing value.
	 *
	 * @return mixed
	 */
	public function validate_submenus( $field, $value, $existing_value ) 
    {
        if ( !empty($value) ) 
        {
            foreach ( $value as $k => $check )
            {
                if ( strpos($check, 'empty' ) === 0 ) {
                    unset($value[$k]);
                }
            }
        }
        $return['value'] = $value;
        return $return;
	}


    /**
     * Add file modification date as timestamp as cache buster
     *
     * @param string $file
     * @return int|bool
     */
    public function cache($file) {
        $path = ADTW_PATH.$file;
        $fmtime = filemtime ($path);

        return $fmtime ? $fmtime : false;
    }
    /**
     * Build Statuses
     *
     * @return array
     */
    private function _buildStatus() {
        $statuses = get_post_stati();
		unset($statuses['auto-draft'], $statuses['inherit']);
        $statuses = array_filter($statuses, function($stat) {
            return strpos($stat, 'request-') === false;
        });
        return $statuses;
    }

    /**
     * Build Post Types
     * Same update as getMenus()
     *
     * @return array
     */
    private function _buildCPTs() {
		$args = array( 'public'	 => true, 'show_ui'	 => true );
		$cpts = get_post_types( $args );
		unset( $cpts['attachment'] );
        return $cpts;
    }

    /**
     * Updated constantly by SettingsPage
     * $submenu is not populated by plugins when Sections are defined in config.php
     *
     * @return void
     */
    private function _buildSubMenus() 
    {
        global $submenu;

        do_action( 'qm/debug', ['Setting submenus', $submenu['tools.php']] );
        return $submenu;
    }

    /**
     * Updated constantly by SettingsPage
     * $menu is not populated by plugins when Sections are defined in config.php
     *
     * @return void
     */
    private function _buildMenus() {
        global $menu;
        $default_menus = [
            'index.php', 
            'edit.php', 
            'upload.php', 
            'edit.php?post_type=page', 
            'edit-comments.php', 
            'themes.php', 
            'plugins.php', 
            'users.php', 
            'tools.php', 
            'options-general.php',
            /* this is a bizarre bug after enabling/disabling Old Links (???) */
            'edit-tags.php?taxonomy=link_category'
        ];
        $all_menus = [
            'index.php'  => esc_html__( 'Dashboard' ), 
            'edit.php'  => esc_html__( 'Posts' ), 
            'upload.php'  => esc_html__( 'Media' ), 
            'edit.php?post_type=page'  => esc_html__( 'Pages' ), 
            'edit-comments.php'  => esc_html__( 'Comments' ), 
            'themes.php'  => esc_html__( 'Appearence' ), 
            'plugins.php'  => esc_html__( 'Plugins' ), 
            'users.php'  => esc_html__( 'Users' ), 
            'tools.php'  => esc_html__( 'Tools' ), 
        ];
		foreach ( $menu as $m ) {
			if ( !in_array( $m[2], $default_menus ) 
				&& false === strpos( $m[2], 'separator' ) 
			) {
				$all_menus[ $m[2] ] = empty($m[3]) ? $m[0] : $m[3];  
			}
		}
        return $all_menus;
    }

    /**
     * Current admin bar items
     * Updated constantly by SettingsPage
     *
     * @return void
     */
    private function _buildAdminbar($bar) {
        if ( !is_object( $bar ) )
            return;
        $default = [
            'wp-logo'       => __('WP logo'),
            'site-name'     => __('Site name'),
            'updates'       => __('Updates'),
            'comments'      => __('Comments'),
            'new-content'   => __('New content'),
            'theme-options' => __('Theme options'),
            'my-account'    => __('My account'),
            'top-secondary' => __('User'),
        ];

        $nodes = $bar->get_nodes();
        $novo = [];
        foreach ($nodes as $node => $data) {
            if (!$data->parent) {
                $title = (isset( $data->title ) && wp_strip_all_tags($data->title)!='') ? $data->title : 'Empty';
                $novo[$data->id] = isset($default[$data->id]) 
                    ? $default[$data->id] : wp_strip_all_tags($title);
            }
        }

        if ( isset($novo['menu-toggle']) ) unset($novo['menu-toggle']);
        
        return $novo;
    }

    public function makeTipCredit($name, $url) {
        return "<a href='$url' target='_blank'>$name</a>";
    }

   	/**
	 * Check ending of string
	 *  
	 * @param string $string
	 * @param string $end
	 * @return boolean
	 */
	public function endswith( $string, $end ) {
		$strlen	 = strlen( $string );
		$testlen = strlen( $end );
		if( $testlen > $strlen )
			return false;
		return substr_compare( $string, $end, -$testlen ) === 0;
	}
    
    /**
	 * Current user has role
	 * Modified to work with Array
	 * http://wordpress.stackexchange.com/q/53675/12615
	 * 
	 * @param array $role
	 * @return boolean
	 */
	public function current_user_has_role_array( $role ) {
		$current_user	 = new \WP_User( wp_get_current_user()->ID );
		$user_roles		 = $current_user->roles;
		$arrtolower		 = array_map( 'strtolower', $role );
		$search			 = array_intersect( $user_roles, $arrtolower );
		if( count( $search ) > 0 ) return true;
		return false;
	}

    /**
     * !!!! Função sem lógica finalizada
     *
     * @param [type] $field
     * @param [type] $value
     * @param [type] $existing_value
     * @return void
     */
    public function validateURL( $field, $value, $existing_value ) {
        $error   = false;
        $warning = false;
        
        $valid = filter_var($value, FILTER_VALIDATE_URL) !== false;
        // Do your validation.
        if ( 1 === $value ) {
            $error = true;
            $value = $existing_value;
        } elseif ( 2 === $value ) {
            $warning = true;
            $value   = $existing_value;
        }

        $return['value'] = $value;

        if ( true === $error ) {
            $field['msg']    = 'your custom error message';
            $return['error'] = $field;
        }

        if ( true === $warning ) {
            $field['msg']      = 'your custom warning message';
            $return['warning'] = $field;
        }

        return $return;
    }

    /**
     * Search array recursively
	 * 
	 * @param string $needle
	 * @param array $haystack
	 * @return boolean or current_key
	 */
	public function recursiveArraySearch( $needle, $haystack ) {
		foreach( $haystack as $key => $value ) {
			$current_key = $key;
			if( 
				$needle === $value 
				OR ( 
					is_array( $value )
					&& $this->recursiveArraySearch( $needle, $value ) !== false 
				)
			) {
				return $current_key;
			}
		}
		return false;
	}

    	/**
	 * Position element at the end of array
	 * 
	 * @param array $src
	 * @param array $in
	 * @param number $pos
	 * @return array
	 */
	public function array_push_after( $src, $in, $pos ) {
		if( is_int( $pos ) )
			$R = array_merge( array_slice( $src, 0, $pos + 1 ), $in, array_slice( $src, $pos + 1 ) );
		else
		{
			foreach( $src as $k => $v )
			{
				$R[$k]	 = $v;
				if( $k == $pos )
					$R		 = array_merge( $R, $in );
			}
		}
		return $R;
	}

    /**
     * Formats the size into human readable
     * 
     * @param integer $size
     * @return string
     * 
     * @since 2.3.7
     * @access public
     */
    public function format_size( $size ) {
        $units = explode( ' ', 'B KB MB GB TB PB' );
        $mod = 1024;
        for( $i = 0; $size > $mod; $i++ )
            $size /= $mod;

        $endIndex = strpos( $size, "." ) + 3;
        return substr( $size, 0, $endIndex ) . ' ' . $units[$i];
    }
	
    /**
     * Social profiles array
     * used by profile.php and HooksUsers.php
     * 
     */
    public function getSocials() {
        return array(
            'twitter'   => esc_html__( 'Twitter', 'mtt' ),
            'linkedin'  => esc_html__( 'Linkedin', 'mtt' ),
            'tiktok'    => esc_html__( 'TikTok', 'mtt' ),                
            'youtube'   => esc_html__( 'YouTube', 'mtt' ),
            'facebook'  => esc_html__( 'Facebook', 'mtt' ),
            'instagram' => esc_html__( 'Instagram', 'mtt' ),                
            'whatsapp'  => esc_html__( 'WhatsApp', 'mtt' ),                
            'telegram'  => esc_html__( 'Telegram', 'mtt' ),                
            'github'    => esc_html__( 'Github', 'mtt' ),                
            'Stack Exchange' => esc_html__( 'Stack Exchange', 'mtt' ),                
        );
    }


    /**
     * Prepare images for hints
     *
     * @param string $img
     * @param string $size
     * @return string
     */
    public function renderHintImg( $img, $size='' ) {
        $size = empty($size) ? 'help' : "help-$size";
        $img = ADTW_URL . '/assets/images/' . $img;
        return "<div class='img-$size'><img src='$img' /></div>";
    }

    /**
     * Uses Query Monitor and debug.log via $query flag
     *
     * @param string $titulo
     * @param array  $objeto
     * @param boolean $query
     * @return void
     */
	public function debug($titulo, $objeto=[], $query=true) {
        if ( $query ) {
            if (empty($objeto)) {
                do_action( 'qm/debug', $titulo );
            } else {
                do_action( 'qm/debug', [$titulo, $objeto] );
            }
        } else {
            error_log("###################### $titulo ##############");
            if (!empty($objeto)) error_log(print_r($objeto, true));
        }
	}
    
    /**
     * Plugin activate method
     *
     * @return void
     */
    public function activate() {
        Activate::activate();
    }    
    
    /**
     * Plugin deactivate method
     *
     * @return void
     */
    public function deactivate() {
        Deactivate::deactivate();
    }    
    
    /**
     * Plugin autoloader method
     *
     * @return void
     */
    private function autoloader() {
		require_once ADTW_PATH . '/inc/Autoloader.php';

		Autoloader::exec();
	}

}