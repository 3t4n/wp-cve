<?php
/**
 * Profile hooks
 *
 * @package AdminTweaks
 */

namespace ADTW;

class HooksUsers {
	/**
	 * Check options and dispatch hooks
	 * 
	 * @param  array $options
	 * @return void
	 */
	public function __construct() {

        # FILTER BY 
		if( ADTW()->getop('users_live_filter') ) {
            add_action( 
                'admin_print_footer_scripts-users.php', 
                [$this, 'printScripts']
            );
        }

        # ADD ID COLUMN
		if( ADTW()->getop('users_id_column') ) {
            add_action( 
                'manage_users_custom_column', 
                [$this, 'renderIDColumn'],
                10, 3
            );
            add_filter(
                'manage_users_columns',
                [$this, 'addIDColumn'] 
            );
            add_action(
                'admin_head-users.php',
                [$this, 'idColumnWidth']
            );
        }

        # CONTACT METHODS (FB, AIM..)
		if( ADTW()->getop('profile_social') ) {
			add_filter( 
                'user_contactmethods', 
                [$this, 'contact_metods'] 
            );
        }
		
        # CSS
        add_action( 
            'admin_head-profile.php', 
            [$this, 'profileCSS']
        );
        add_action( 
            'admin_head-user-edit.php', 
            [$this, 'profileCSS']
        );
	}

    public function idColumnWidth(){
        echo '<style>.column-user_id{width: 5%}</style>';
    }

    public function addIDColumn( $columns )
    {
		$in = ['user_id' => 'ID'];
		$columns = ADTW()->array_push_after( $columns, $in, 0 );
        return $columns;
    }

    public function renderIDColumn( $value, $column_name, $user_id )
    {
        if ( 'user_id' == $column_name )
            return $user_id;
        return $value;
    }
    /**
	 * Utility Filter Users By
     * print JS and CSS
	 */
	public function printScripts() {
        $html = sprintf(
            '<div class="mysearch-wrapper">
            <span class="dashicons dashicons-buddicons-forums b5f-icon" 
                title="%1$s">
            </span> 
            <input type="text" id="b5f-plugins-filter" class="mysearch-box" 
                name="focus" value="" placeholder="%2$s" 
                title="%3$s" />
            <button class="close-icon" type="reset"></button>
            </div>',
            'by '.AdminTweaks::NAME, 
            esc_html__('filter by user, email, role...', 'mtt'),
            esc_html__('enter a string to filter the list', 'mtt'),
        );
		wp_register_style( 
				'mtt-filterby', 
				ADTW_URL . '/assets/filter-listings.css', 
				[], 
				ADTW()->cache('/assets/filter-listings.css')  
		);
		wp_register_script( 
				'mtt-filterby-users', 
				ADTW_URL . '/assets/filter-users.js', 
				[], 
				ADTW()->cache('/assets/filter-users.js')  
		);
		wp_enqueue_style( 'mtt-filterby' );
		wp_enqueue_script( 'mtt-filterby-users' );
        wp_add_inline_script( 
            'mtt-filterby-users', 
            'const ADTW = ' . json_encode([
                'html' => $html,
            ]), 
            'before' 
        );
	}

	/**
	 * Change or remove contact methods
	 * 
	 * @param type $contactmethods
	 * @return string
	 */
	public function contact_metods( $contactmethods ) {
        $ops = ADTW()->getop('profile_social');
        $all = ADTW()->getSocials();
        if ($ops) {
            foreach ($ops as $op) {
                $contactmethods[$op] = $all[$op];
            }
        }
		return $contactmethods;
	}


	/**
	 * CSS for Profile and User pages
	 * 
	 */
	public function profileCSS() {
		$style = '';
        $tohide = [];
        $listtohide = '';
		if( ADTW()->getop('profile_css') ) {
			$style = ADTW()->getop('profile_css');
        }
		if( ADTW()->getop('profile_h2_titles') )
			$tohide[] = '#your-profile h2';

		if( ADTW()->getop('profile_app_pw') )
			$tohide[] = '#application-passwords-section';

        $tohide = array_merge(
            $tohide,
            $this->_doCSS( ADTW()->getop('profile_personal_options') ),
            $this->_doCSS( ADTW()->getop('profile_name') ),
            $this->_doCSS( ADTW()->getop('profile_contact_info') ),
            $this->_doCSS( ADTW()->getop('profile_about_yourself') )
        );
        if ( !empty($tohide) ) {
            $listtohide = sprintf(
                '%s { display: none !important; }',
                implode(",", $tohide)
            );
        }
        
        if( !empty($style) || !empty($listtohide) ) {
			printf (
                '<style type="text/css">
                    %s
                    %s
                </style>',
                $style,
                $listtohide
            );
        }
        
	}

    private function _doCSS( $opt ) {
        if ( $opt === false ) return [];
        $ritorna = [];
        foreach ( $opt as $class ) {
            $ritorna[] = ".$class";
        }
        return $ritorna;
    }

}