<?php
/**
 * Third Party Plugins hooks
 *
 * @package AdminTweaks
 */

namespace ADTW;

class HooksPluginsExtras {
	/**
	 * Check options and dispatch hooks
	 * 
	 * @param  array $options
	 * @return void
	 */
	public function __construct() 
    {
        # SNIPPETS: FILTER BY 
		if( ADTW()->getop('plugins_snippets_filter') ) {
            add_action( 
                'admin_print_footer_scripts-toplevel_page_snippets', 
                [$this, 'printSnippetsScripts']
            );
        }

        # NOTIFICATION CENTER: RENAME ADMINBAR 
		if( ADTW()->getop('plugins_notices_rename') ) 
        {
            add_action( 
                'wp_after_admin_bar_render', 
                [$this, 'changeNoticesName'] 
            );
        }
        
        # NOTIFICATION CENTER: MOVE MENU 
		if( ADTW()->getop('plugins_hide_notices') ) 
        {
            add_action( 
                'admin_init', 
                [$this, 'adminitNotices'],
                99998
            );
            add_action( 
                'admin_menu', 
                [$this, 'menuNotices'],
                99998
            );
            add_action(
                'admin_footer-toplevel_page_wp-admin-notification-center',
                [$this, 'scriptNotices']
            );
        }
        # ACF: MOVE MENU 
		if( ADTW()->getop('plugins_acf_move_menu') ) 
        {
            add_action( 
                'admin_init', 
                [$this, 'adminitACF'],
                99998
            );
            add_action( 
                'admin_menu', 
                [$this, 'menuACF'],
                99998
            );
            foreach( ['edit.php', 'post-new.php', 'post.php', 'acf_page_acf-tools'] as $page )
            {
                add_action(
                    "admin_footer-$page",
                    [$this, 'scriptACF']
                );
            }
        }

        # SNIPPETS: MOVE MENU 
		if( ADTW()->getop('plugins_snippets_move_menu') 
            && !is_network_admin() ) 
        {
            add_action( 
                'admin_init', 
                [$this, 'adminitSnip'],
                99998
            );
            add_action( 
                'admin_menu', 
                [$this, 'menuSnip'],
                99998
            );
            # Adiciona botões no topo
            add_action( 
                'code_snippets/admin/manage/before_list_table', 
                [$this, 'snippetsScreenButtons'] 
            );
            add_action( 
                'code_snippets/admin/before_title_input', 
                [$this, 'snippetsScreenButtons'] 
            );
            add_action( 
                'admin_head-toplevel_page_snippets', 
                [$this, 'cleanUp'] 
            );
            $hook = ADTW()->getSnippetsSlug();
            add_action( 
                "admin_footer-{$hook}_page_snippets-settings", 
                [$this, 'snippetsScreenButtons'],
                99999
            );
            foreach (['edit-snippet','snippets-settings','import-code-snippets','add-snippet'] as $page )
                add_action( 
                    "admin_head-{$hook}_page_$page", 
                    [$this, 'cleanUp'],
                    99999
                );
        }



	}


	/**
	 * Remove Action Links
	 * 
	 * @return empty
	 */
	public function remove_action_links() {
		return;
	}


    /**
	 * Snippets Plugin
     * CSS and JS for Filter By
	 */
	public function printSnippetsScripts() {
		wp_register_style( 
            'mtt-filterby-snippets', 
            ADTW_URL . '/assets/filter-listings.css', 
            [], 
            ADTW()->cache('/assets/filter-listings.css')  
		);
		wp_register_script( 
            'mtt-filterby-snippets', 
            ADTW_URL . '/assets/filter-snippets.js', 
            [], 
            ADTW()->cache('/assets/filter-snippets.js')  
		);
		wp_enqueue_style( 'mtt-filterby-snippets' );
		wp_enqueue_script( 'mtt-filterby-snippets' );
        wp_add_inline_script( 
            'mtt-filterby-snippets', 
            'const ADTW = ' . json_encode([
                'html' => $this->_snippetsHtml(),
            ]), 
            'before' 
        );
	}

    private function _snippetsHtml()
    {
        return sprintf(
            '<div class="mysearch-wrapper">
            <span class="dashicons dashicons-buddicons-forums b5f-icon" 
                title="%1$s">
            </span> 
            <button id="hide-desc" class="button b5f-button" 
                title="%2$s" 
                data-title-hide="%2$s" 
                data-title-show="%3$s">
            %4$s</button> 
            <button id="hide-active" class="button b5f-button b5f-btn-status" 
                title="%6$s" 
                data-title-hide="%5$s" 
                data-title-show="%6$s">
            %7$s</button> 
            <button id="hide-inactive" class="button b5f-button b5f-btn-status" 
                title="%8$s" 
                data-title-hide="%5$s" 
                data-title-show="%8$s">
            %9$s</button>
            <input type="text" id="b5f-plugins-filter" class="mysearch-box" 
                name="focus" value="" placeholder="%10$s" 
                title="%11$s" />
            <button class="close-icon" type="reset"></button>
            </div>',
            'by '.AdminTweaks::NAME,                #1
            esc_html__('Hide descriptions', 'mtt'), #2
            esc_html__('Show descriptions', 'mtt'), #3
            esc_html__('Description', 'mtt'),
            esc_html__('Show all', 'mtt'), #5
            esc_html__('Show active', 'mtt'), #6
            esc_html__('Active', 'mtt'),
            esc_html__('Show inactive', 'mtt'), #9
            esc_html__('Inactive', 'mtt'),
            esc_html__('filter by keyword', 'mtt'),
            esc_html__('enter a string to filter the list', 'mtt'),
        );
    }

    public function changeNoticesName() 
    {
        $text = ADTW()->getop('plugins_notices_rename_text')
            ? ADTW()->getop('plugins_notices_rename_text')
            : 'Notices';
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('#wp-admin-bar-wanc_display_notification > a').text('<?php echo $text ?>');
            });
        </script>
        <?php
    }
    /**
     * Remove Menu Notification Center
     *
     * @return void
     */
    public function adminitNotices() {
        remove_menu_page('wp-admin-notification-center');
    }

    /**
     * Re-add Menu Notification Center as submenu
     *
     * @return void
     */
    public function menuNotices() {
        add_submenu_page(
            'tools.php',
            'Notification Center', 
            'Notification Center', 
            'manage_options', 
            'admin.php?page=wp-admin-notification-center', 
            '',
            999
        );
    }
    
    public function scriptNotices()
    {
        ?>
        <script type="text/javascript">
            jQuery(document).ready( function($) {
                $('#menu-tools > ul a[href^="admin.php?page=wp-admin-notification-center"]').addClass('current').parent().addClass('current');
                $('#menu-tools').removeClass('wp-not-current-submenu').addClass('wp-has-current-submenu wp-menu-open');
                $('#menu-tools >a').removeClass('wp-not-current-submenu').addClass('wp-has-current-submenu wp-menu-open');
            });
        </script>
        <?php
    }    


    /**
     * Remove Menu ACF
     *
     * @return void
     */
    public function adminitACF() {
        remove_menu_page('edit.php?post_type=acf-field-group');
    }

    /**
     * Re-add Menu Snippets as submenu
     *
     * @return void
     */
    public function menuACF() {
        add_submenu_page(
            'tools.php',
            'Custom Fields', 
            'Custom Fields', 
            'manage_options', 
            'edit.php?post_type=acf-field-group', 
            '',
            999
        );
    }
    
    public function scriptACF()
    {
        if ( in_array(get_current_screen()->post_type, ['acf-taxonomy', 'acf-post-type', 'acf-field-group']) || get_current_screen()->id == 'acf_page_acf-tools' ) {   
            $css = apply_filters('mtt_css_acf_plugin', '');
            echo "<style>$css</style>";
            ?>
            <script type="text/javascript">
                jQuery(document).ready( function($) {
                    $('#menu-tools > ul a[href^="edit.php?post_type=acf-field-group"]').addClass('current').parent().addClass('current');
                    $('#menu-tools').removeClass('wp-not-current-submenu').addClass('wp-has-current-submenu wp-menu-open');
                    $('#menu-tools >a').removeClass('wp-not-current-submenu').addClass('wp-has-current-submenu wp-menu-open');

                    $('.acf-tab.acf-header-tab-acf-tools').attr('href', '/wp-admin/admin.php?page=acf-tools');
                });
            </script>
            <?php
        }
    }    

    /**
     * Remove Menu Snippets
     *
     * @return void
     */
    public function adminitSnip() {
        remove_menu_page('snippets');
    }

    /**
     * Re-add Menu Snippets as submenu
     *
     * @return void
     */
    public function menuSnip() {
        add_submenu_page(
            'tools.php',
            'Snippets', 
            'Snippets', 
            'manage_options', 
            'admin.php?page=snippets', 
            '',
            999
        );
    }
    
    public function snippetsScreenButtons (){
        $make_menu = [
            'sp-todos'  => [
                esc_html__('All Snippets', 'mtt'), 
                admin_url('admin.php?page=snippets')
            ],
            'sp-config' => [
                esc_html__('Settings', 'mtt'), 
                admin_url('admin.php?page=snippets-settings')
            ]
        ];
        echo '<div id="the-menu-b5f" style="display:none">';
        foreach( $make_menu as $k => $v ){
            printf( '<a href="%s" class="page-title-action add-new-h2">%s</a>', $v[1], $v[0] );
        }
        echo '</div>';
        echo <<<HTML
        <style>

        </style>
        <script type="text/javascript">
        jQuery(document).ready(function($) {   
            $('#the-menu-b5f a').appendTo('.wrap h1');
        });             
        </script>
HTML;
    }
    
    public function cleanUp(){
        if ( in_array($_GET['page'], ['edit-snippet', 'snippets', 'snippets-settings', 'import-code-snippets','add-snippet']) ) {   
            $css = apply_filters('mtt_css_snippets_plugin', '');
            echo "<style>$css</style>";
            ?>
            <script type="text/javascript">
                jQuery(document).ready( function($) {
                    $('#menu-tools > ul a[href^="admin.php?page=snippets"]').addClass('current').parent().addClass('current');
                    $('#menu-tools').removeClass('wp-not-current-submenu').addClass('wp-has-current-submenu wp-menu-open');
                    $('#menu-tools >a').removeClass('wp-not-current-submenu').addClass('wp-has-current-submenu wp-menu-open');
                });
            </script>
            <?php
        }
    }
  
}