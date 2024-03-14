<?php
/**
 * @package   WCMND_Options_Framework
 * @author    Devin Price <devin@wptheming.com>
 * @license   GPL-2.0+
 * @link      http://wptheming.com
 * @copyright 2010-2014 WP Theming
 */
class WCMND_Options_Framework_Admin {

	/**
     * Page hook for the options screen
     *
     * @since 1.7.0
     * @type string
     */
    protected $options_screen = null;

    /**
     * Hook in the scripts and styles
     *
     * @since 1.7.0
     */
    public function init() {

		// Gets options to load
    	$options = & WCMND_Options_Framework::_wcmnd_optionsframework_options();

		// Checks if options are available
    	if ( $options ) {

			// Add the options page and menu item.
			add_action( 'admin_menu', array( $this, 'add_custom_options_page' ) );

			// Add the required scripts and styles
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

			// Settings need to be registered after admin_init
			add_action( 'admin_init', array( $this, 'settings_init' ) );

			// Adds options menu to the admin bar
			add_action( 'wp_before_admin_bar_render', array( $this, 'wcmnd_optionsframework_admin_bar' ) );

			add_action( 'wp_ajax_wcmnd_ajax_products', array( $this, 'wcmnd_get_products' ) );

			}

    }

    function wcmnd_get_products() {
    	global $wpdb;
        $post_types = array( 'product' );
        ob_start();

        if ( empty( $term ) ) {
            $term = wc_clean( stripslashes( $_GET['q'] ) );
        } else {
            $term = wc_clean( $term );
        }


        if ( empty( $term ) ) {
            die();
        }

        $like_term = '%' . $wpdb->esc_like( $term ) . '%';

        if ( is_numeric( $term ) ) {
            $query = $wpdb->prepare( "
                SELECT ID FROM {$wpdb->posts} posts LEFT JOIN {$wpdb->postmeta} postmeta ON posts.ID = postmeta.post_id
                WHERE posts.post_status = 'publish'
                AND (
                    posts.post_parent = %s
                    OR posts.ID = %s
                    OR posts.post_title LIKE %s
                    OR (
                        postmeta.meta_key = '_sku' AND postmeta.meta_value LIKE %s
                    )
                )
            ", $term, $term, $term, $like_term );
        } else {
            $query = $wpdb->prepare( "
                SELECT ID FROM {$wpdb->posts} posts LEFT JOIN {$wpdb->postmeta} postmeta ON posts.ID = postmeta.post_id
                WHERE posts.post_status = 'publish'
                AND (
                    posts.post_title LIKE %s
                    or posts.post_content LIKE %s
                    OR (
                        postmeta.meta_key = '_sku' AND postmeta.meta_value LIKE %s
                    )
                )
            ", $like_term, $like_term, $like_term );
        }

        $query .= " AND posts.post_type IN ('" . implode( "','", array_map( 'esc_sql', $post_types ) ) . "')";

        if ( ! empty( $_GET['exclude'] ) ) {
            $query .= " AND posts.ID NOT IN (" . implode( ',', array_map( 'intval', explode( ',', $_GET['exclude'] ) ) ) . ")";
        }

        if ( ! empty( $_GET['include'] ) ) {
            $query .= " AND posts.ID IN (" . implode( ',', array_map( 'intval', explode( ',', $_GET['include'] ) ) ) . ")";
        }

        if ( ! empty( $_GET['limit'] ) ) {
            $query .= " LIMIT " . intval( $_GET['limit'] );
        }

        $posts          = array_unique( $wpdb->get_col( $query ) );
        $found_products = array();

        if ( ! empty( $posts ) ) {
            foreach ( $posts as $post ) {
                $product = wc_get_product( $post );

                if ( ! current_user_can( 'read_product', $post ) ) {
                    continue;
                }

                if ( ! $product || ( $product->is_type( 'variation' ) && empty( $product->parent ) ) ) {
                    continue;
                }

                $found_products[ $post ] = rawurldecode( $product->get_formatted_name() );
            }
        }

        wp_send_json( $found_products );
    }

	/**
     * Registers the settings
     *
     * @since 1.7.0
     */
    function settings_init() {

    	// Load Options Framework Settings
        $wcmnd_optionsframework_settings = get_option( 'wcmnd_optionsframework' );

		// Registers the settings fields and callback
		register_setting( 'wcmnd_optionsframework', $wcmnd_optionsframework_settings['id'],  array ( $this, 'validate_options' ) );

		// Displays notice after options save
		add_action( 'wcmnd_optionsframework_after_validate', array( $this, 'save_options_notice' ) );

    }

	/*
	 * Define menu options
	 *
	 * Examples usage:
	 *
	 * add_filter( 'wcmnd_optionsframework_menu', function( $menu ) {
	 *     $menu['page_title'] = 'The Options';
	 *	   $menu['menu_title'] = 'The Options';
	 *     return $menu;
	 * });
	 *
	 * @since 1.7.0
	 *
	 */
	static function menu_settings() {

		$menu = array(

			// Modes: submenu, menu
            'mode' => 'menu',

            // Submenu default settings
            'page_title' => __( 'Theme Options', 'textdomain'),
			'menu_title' => __('Theme Options', 'textdomain'),
			'capability' => 'edit_theme_options',
			'menu_slug' => 'options-framework',
            'parent_slug' => 'themes.php',

            // Menu default settings
            'icon_url' => 'dashicons-tickets-alt',
            'position' => '61'

		);

		return apply_filters( 'wcmnd_optionsframework_menu', $menu );
	}

	/**
     * Add a subpage called "Theme Options" to the appearance menu.
     *
     * @since 1.7.0
     */
	function add_custom_options_page() {

	$menu = $this->menu_settings();

        switch( $menu['mode'] ) {

            case 'menu':
            	// http://codex.wordpress.org/Function_Reference/add_menu_page
                $this->options_screen = add_menu_page(
                	$menu['page_title'],
                	$menu['menu_title'],
                	$menu['capability'],
                	$menu['menu_slug'],
                	array( $this, 'options_page' ),
                	$menu['icon_url'],
                	$menu['position']
                );

                	add_action("load-{$this->options_screen}", array($this, 'woocommerce_mailchimp_subscibe_discount_screen_options'));

                break;

            default:
            	// http://codex.wordpress.org/Function_Reference/add_submenu_page
                $this->options_screen = add_submenu_page(
                	$menu['parent_slug'],
                	$menu['page_title'],
                	$menu['menu_title'],
                	$menu['capability'],
                	$menu['menu_slug'],
                	array( $this, 'options_page' ) );
                break;
       	}

}

	public function woocommerce_mailchimp_subscibe_discount_screen_options() {


		$screen = get_current_screen();

		// get out of here if we are not on our settings page
		if(!is_object($screen) || $screen->id != $this->options_screen)
			return;

		$screen->add_help_tab( array(
        'id'	=> 'wcmsd_help',
        'title'	=> __('Shortcode Help'),
        'content'	=> '<p>' . __( 'Use shortcode [wc_mailchimp_subscribe_discount width="100%" btn_width="auto" layout="vertical"] ' ) . '</p>',
    ) );
	}

	/**
     * Loads the required stylesheets
     *
     * @since 1.7.0
     */

	function enqueue_admin_styles( $hook ) {

		if ( $this->options_screen != $hook )
	        return;

		wp_enqueue_style( 'wcmnd_optionsframework', WCMND_OPTIONS_FRAMEWORK_DIRECTORY . 'css/wcmnd_optionsframework.css', array(),  WCMND_Options_Framework::VERSION );
		wp_enqueue_style( 'wp-color-picker' );
	}

	/**
     * Loads the required javascript
     *
     * @since 1.7.0
     */
	function enqueue_admin_scripts( $hook ) {

		if ( $this->options_screen != $hook )
	        return;

	    //Moment js
	    wp_enqueue_script( 'momentjs', WCMND_OPTIONS_FRAMEWORK_DIRECTORY . 'js/moment.min.js', WCMND_Options_Framework::VERSION );

		  wp_enqueue_style('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css' );
	    wp_enqueue_script('select2', 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js', array('jquery') );
      wp_enqueue_script('ace-editor', 'https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.4/ace.js', array('jquery') );

		// Enqueue custom option panel JS
		wp_enqueue_script( 'options-custom', WCMND_OPTIONS_FRAMEWORK_DIRECTORY . 'js/options-custom.js', array( 'jquery','wp-color-picker','select2' ), WCMND_Options_Framework::VERSION );

		$get_settings = get_option( 'wcmnd_options' );
		$coupon_disabled = isset($get_settings['disable_discount_feature']) ? $get_settings['disable_discount_feature'] : 'no';
		$selected_newsletter = isset($get_settings['display_configuration']) ? $get_settings['display_configuration'] : array();


		$option_var = array(
			'options_path' 			=> WCMND_OPTIONS_FRAMEWORK_DIRECTORY,
			'ajax_url' 					=> admin_url( 'admin-ajax.php' ),
			'nonce'  						=> wp_create_nonce( "wcmnd-search-products" ),
			'please_wait' 			=> __('Please Wait', 'wc_newsletter_discounts'),
			'mailchimp_api_key_missing' => __('Please Enter Your Mailchimp API Key First', 'wc_newsletter_discounts'),
			'coupon_disabled'		=> $coupon_disabled,
			'selected_newsletter' => $selected_newsletter
		);
		wp_localize_script( 'options-custom', 'wcmndOption' , $option_var );


		// Inline scripts from options-interface.php
		add_action( 'admin_head', array( $this, 'wcmnd_of_admin_head' ) );
	}

	function wcmnd_of_admin_head() {
		// Hook to add custom scripts
		do_action( 'wcmnd_optionsframework_custom_scripts' );
	}

	/**
     * Builds out the options panel.
     *
	 * If we were using the Settings API as it was intended we would use
	 * do_settings_sections here.  But as we don't want the settings wrapped in a table,
	 * we'll call our own custom wcmnd_optionsframework_fields.  See options-interface.php
	 * for specifics on how each individual field is generated.
	 *
	 * Nonces are provided using the settings_fields()
	 *
     * @since 1.7.0
     */
	 function options_page() { ?>

		<div id="wcmnd_optionsframework-wrap" class="wrap">

		<?php $menu = $this->menu_settings(); ?>
		<h2><?php echo esc_html( $menu['page_title'] ); ?></h2>

		<!-- Help Section -->
		<div class="zetamatic_support" style = "background: #ffffff94; border: 1px solid #ccc; border-radius: 2px; padding: 0 0.6rem;">
			<h3 style = "margin-bottom: 0 ;"> <a href="https://zetamatic.com" target = "_blank" style = "text-decoration:none; color:black;"class="dashicons  dashicons-editor-help" title = "<?php echo __('Need help?', 'wc_mailchimp_newsletter_discount'); ?>" ></a>
			<a href="https://zetamatic.com/" style = "text-decoration:none; color:black;" target = "_blank" title = "<?php echo __('Need help? We are happy to help!', 'wc_mailchimp_newsletter_discount'); ?>"><?php echo __('Need help?', 'wc_mailchimp_newsletter_discount'); ?></a></h3>

			<div class = "zetamatic_support_container" style = "display:flex;">
				<div class = "zetamatic_support_setup_directions" style = "margin-right: 0.625rem;">
					<h4>
					<a href="https://zetamatic.com/docs/woocommerce-mailchimp-newsletter-discount/" target = "_blank" style = "text-decoration:none; color:black;"class="dashicons dashicons-admin-generic" title = "<?php echo __('Setup Directions', 'wc_mailchimp_newsletter_discount'); ?>" ></a>
					<a href="https://zetamatic.com/docs/woocommerce-mailchimp-newsletter-discount/setup/" target = "_blank" style = "text-decoration:none;" title = "<?php echo __('A step-by-step guide on how to setup and use the plugin.', 'wc_mailchimp_newsletter_discount'); ?>"><?php echo __(' Setup Directions', 'wc_mailchimp_newsletter_discount'); ?></a>
					</h4>
				</div>
				
				<div class = "zetamatic_support_docs" style = "margin-right: 0.625rem;">
					<h4 style = "margin-bottom:2;">
					<a href="https://zetamatic.com/docs/woocommerce-mailchimp-newsletter-discount/" target = "_blank" style = "text-decoration:none; color:black;"class="dashicons  dashicons-media-document" title = "<?php echo __('Documentation', 'wc_mailchimp_newsletter_discount'); ?>" ></a>
					<a href="https://zetamatic.com/docs/woocommerce-mailchimp-newsletter-discount" target = "_blank" style = "text-decoration:none;" title = "<?php echo __('View our expansive library of documentation to help solve your problem as quickly as possible.', 'wc_mailchimp_newsletter_discount'); ?>"><?php echo __(' Documentation', 'wc_mailchimp_newsletter_discount'); ?></a>
					</h4>
				
				</div>

				<div class = "zetamatic_support_faqs" style = "margin-right: 0.625rem;">
					<h4 style = "margin-bottom:2;">
					
					</a> <a href = "https://zetamatic.com/docs/woocommerce-mailchimp-newsletter-discount/faqs/" target = "_blank"  style = "text-decoration: none;" title = "<?php echo __(' FAQs', 'wc_mailchimp_newsletter_discount'); ?>"><img style = "height: 14px; width: 17px;" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjwhRE9DVFlQRSBzdmcgIFBVQkxJQyAnLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4nICAnaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkJz48c3ZnIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDQ4IDQ4IiBoZWlnaHQ9IjQ4cHgiIGlkPSJMYXllcl8zIiB2ZXJzaW9uPSIxLjEiIHZpZXdCb3g9IjAgMCA0OCA0OCIgd2lkdGg9IjQ4cHgiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPjxwYXRoIGQ9Ik0yNCwwLjEyNUMxMC44MTQsMC4xMjUsMC4xMjUsMTAuODE0LDAuMTI1LDI0YzAsMTMuMTg3LDEwLjY4OSwyMy44NzUsMjMuODc1LDIzLjg3NSAgYzEzLjE4NiwwLDIzLjg3NS0xMC42ODgsMjMuODc1LTIzLjg3NUM0Ny44NzUsMTAuODE0LDM3LjE4NiwwLjEyNSwyNCwwLjEyNXogTTIzLjQ0OSwxNC4xNjJjMC4wOTgtMC41NzYsMC4zODctMS4wNjIsMC44NjEtMS40NjYgIGMwLjQ2OS0wLjQwNywwLjk5My0wLjYwOSwxLjU2OS0wLjYwOWMwLjU3MywwLDEuMDM3LDAuMjAyLDEuMzkyLDAuNjA5YzAuMzU2LDAuNDAzLDAuNDc4LDAuODksMC4zOCwxLjQ2NiAgYy0wLjEwNCwwLjU3Mi0wLjM5MSwxLjA2Mi0wLjg2LDEuNDY3Yy0wLjQ3MywwLjQwMy0wLjk5OCwwLjYwNS0xLjU3LDAuNjA1Yy0wLjU3MiwwLTEuMDI5LTAuMjAyLTEuMzY3LTAuNjA1ICBDMjMuNTE1LDE1LjIyNSwyMy4zNzgsMTQuNzM0LDIzLjQ0OSwxNC4xNjJ6IE0zMC4wODQsMzEuNDQ1YzAsMC0wLjAyLDAuMDI5LTAuMDUsMC4wNzJjLTAuMDQsMC4wNTItMC4wODIsMC4xMDgtMC4xMzEsMC4xNjcgIGMtMC41NTEsMC42OTUtMi4zNzQsMi43NzItNS4xNDYsMy44MDRjLTAuMDk3LDAuMDM3LTAuMTk1LDAuMDc0LTAuMjkyLDAuMTA1Yy0wLjAzOSwwLjAxNC0wLjA3NiwwLjAyOS0wLjExNiwwLjA0MyAgYy0wLjAwNC0wLjAwMi0wLjAwNi0wLjAwNC0wLjAxLTAuMDA0Yy0wLjU4NiwwLjE4MS0xLjE2LDAuMjgxLTEuNzE2LDAuMjgxYy0xLjIyOCwwLTEuODQzLTAuNTYyLTEuODQzLTEuNjg1ICBjMC0wLjQ3NiwwLjI1Mi0xLjkzNywwLjc1Ni00LjM3OWwwLjk4Ni00LjcyOGwwLjI1MS0xLjE5NWwwLjIwMi0wLjk1N2MwLjA4NS0wLjQwNiwwLjEyNy0wLjc0NSwwLjEyNy0xLjAyMyAgYzAtMC4zMDktMC4wOS0wLjUwNi0wLjIyNy0wLjY0NmMtMC4xOS0wLjE1Ny0wLjQ4OC0wLjEzLTAuNjQ1LTAuMDk0Yy0wLjMzNywwLjA4OS0wLjcyMSwwLjI0Ny0wLjg3MiwwLjMxMiAgYy0xLjk5NiwwLjk5OC0zLjE4NSwyLjUzMS0zLjE4NSwyLjUzMWMtMC4zMTYtMC4xOTYtMC40NzYtMC4zOTQtMC40NzYtMC41OWMwLTAuMjUxLDAuMTI0LTAuNTMzLDAuMzYtMC44NDNsLTAuMDAyLDAuMDAxICBsMC4wMDktMC4wMTFjMC4xODItMC4yMzcsMC40MjYtMC40OSwwLjc0NS0wLjc2MmMwLjQxNS0wLjM4MywxLjAxMy0wLjg1OCwxLjc2Mi0xLjMzMmMwLjAzNy0wLjAyNCwwLjA2MS0wLjA0MywwLjEwMS0wLjA2OSAgYzIuNzY2LTEuODIzLDQuNjMzLTEuNjgyLDQuNjMzLTEuNjgybC0wLjAwNSwwLjAwOGMwLjI4NCwwLjAyMywwLjY5NSwwLjEyNywxLjEzNywwLjQ4N2MwLjA0MiwwLjAzNCwwLjA1OCwwLjA2NCwwLjA4OCwwLjA5OCAgYzAuMDI4LDAuMDI4LDAuMDU2LDAuMDU4LDAuMDgyLDAuMDg5YzAuMDI5LDAuMDQyLDAuMDUxLDAuMDgyLDAuMDY2LDAuMTIyYzAuMTc3LDAuMjc0LDAuMjg3LDAuNjExLDAuMjg3LDEuMDU1ICBjMCwwLjIzMi0wLjA2OCwwLjcwOC0wLjIwMSwxLjQyN2wtMC4yMjYsMS4xNjJjLTAuMDIxLDAuMDkzLTAuMTEsMC41NjItMC4yNzgsMS40MDhsLTEuMDM1LDQuOTY5bC0wLjI1MywxLjExMSAgYy0wLjIyMSwxLjEwMi0wLjMzLDEuODM0LTAuMzMsMi4yMDVjMCwwLjQ0OSwwLjE4NCwwLjY4NywwLjUyMywwLjczYzAuMTItMC4wMSwwLjQ5Mi0wLjA0NiwwLjg5MS0wLjE4OCAgYzAuMTM1LTAuMDYyLDAuMjcxLTAuMTE0LDAuNDAxLTAuMTg1YzAuMDA2LTAuMDA2LDAuMDE1LTAuMDEsMC4wMjQtMC4wMTRjMS41MTEtMC44MzcsMi42NzItMi4yMzYsMi45ODQtMi42MzIgIGMwLjA1Mi0wLjA3NiwwLjEwNC0wLjE0MSwwLjE1Ni0wLjIxNmMwLjQ0NiwwLjE4NywwLjY3NywwLjM2NSwwLjY3NywwLjU0YzAsMC4xMjgtMC4wNzYsMC4yOTktMC4yMTcsMC41MDVIMzAuMDg0eiIgZmlsbD0iIzI0MUYyMCIvPjwvc3ZnPg==" alt="Need Help?"></a>

					<a href="https://zetamatic.com/docs/woocommerce-mailchimp-newsletter-discount/faqs/" target = "_blank" style = "text-decoration:none;" title = "<?php echo __('Please browse the Frequently Asked Questions to see if your query has already been answered.', 'wc_mailchimp_newsletter_discount'); ?>"><?php echo __(' FAQs', 'wc_mailchimp_newsletter_discount'); ?></a>
					</h4>
				
				</div>

				<div class = "zetamatic_support_livechat" style = "margin-right: 0.625rem;">
					<h4 style = "margin-bottom:2;">
					</a> <a href = "https://zetamatic.com/" target = "_blank"  style = "text-decoration: none;" title = "<?php echo __(' Live Chat', 'wc_mailchimp_newsletter_discount'); ?>"><img style = "height: 14px; width: 17px;" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjwhRE9DVFlQRSBzdmcgIFBVQkxJQyAnLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4nICAnaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkJz48c3ZnIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDUwIDUwIiBoZWlnaHQ9IjUwcHgiIGlkPSJMYXllcl8xIiB2ZXJzaW9uPSIxLjEiIHZpZXdCb3g9IjAgMCA1MCA1MCIgd2lkdGg9IjUwcHgiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPjxyZWN0IGZpbGw9Im5vbmUiIGhlaWdodD0iNTAiIHdpZHRoPSI1MCIvPjxwYXRoIGQ9Ik00NCwyMGMwLTEuMTA0LTAuODk2LTItMi0ycy0yLDAuODk2LTIsMiAgYzAsMC40NzYsMCwxNC41MjQsMCwxNWMwLDEuMTA0LDAuODk2LDIsMiwyczItMC44OTYsMi0yQzQ0LDM0LjUyNCw0NCwyMC40NzYsNDQsMjB6IiBmaWxsPSJub25lIiBzdHJva2U9IiMwMDAwMDAiIHN0cm9rZS1taXRlcmxpbWl0PSIxMCIgc3Ryb2tlLXdpZHRoPSIyIi8+PHBhdGggZD0iTTI4LDQ3YzEuMTA0LDAsMi0wLjg5NiwyLTJzLTAuODk2LTItMi0yICBjLTAuNDc2LDAtNC41MjQsMC01LDBjLTEuMTA0LDAtMiwwLjg5Ni0yLDJzMC44OTYsMiwyLDJDMjMuNDc2LDQ3LDI3LjUyNCw0NywyOCw0N3oiIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzAwMDAwMCIgc3Ryb2tlLW1pdGVybGltaXQ9IjEwIiBzdHJva2Utd2lkdGg9IjIiLz48cGF0aCBkPSJNOCwxOUM4LDkuNjExLDE1LjYxMSwyLDI1LDJzMTcsNy42MTEsMTcsMTciIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzAwMDAwMCIgc3Ryb2tlLW1pdGVybGltaXQ9IjEwIiBzdHJva2Utd2lkdGg9IjIiLz48cGF0aCBkPSJNNDQsMjBjMi43NjIsMCw1LDMuMzU3LDUsNy41ICBjMCw0LjE0MS0yLjIzOCw3LjUtNSw3LjUiIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzAwMDAwMCIgc3Ryb2tlLW1pdGVybGltaXQ9IjEwIiBzdHJva2Utd2lkdGg9IjIiLz48cGF0aCBkPSJNNiwyMGMwLTEuMTA0LDAuODk2LTIsMi0yczIsMC44OTYsMiwyICBjMCwwLjQ3NiwwLDE0LjUyNCwwLDE1YzAsMS4xMDQtMC44OTYsMi0yLDJzLTItMC44OTYtMi0yQzYsMzQuNTI0LDYsMjAuNDc2LDYsMjB6IiBmaWxsPSJub25lIiBzdHJva2U9IiMwMDAwMDAiIHN0cm9rZS1taXRlcmxpbWl0PSIxMCIgc3Ryb2tlLXdpZHRoPSIyIi8+PHBhdGggZD0iTTYsMjBjLTIuNzYxLDAtNSwzLjM1Ny01LDcuNSAgQzEsMzEuNjQxLDMuMjM5LDM1LDYsMzUiIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzAwMDAwMCIgc3Ryb2tlLW1pdGVybGltaXQ9IjEwIiBzdHJva2Utd2lkdGg9IjIiLz48cGF0aCBkPSJNNDIsMzdjMCw1LTMsOC04LDhoLTQiIGZpbGw9Im5vbmUiIHN0cm9rZT0iIzAwMDAwMCIgc3Ryb2tlLW1pdGVybGltaXQ9IjEwIiBzdHJva2Utd2lkdGg9IjIiLz48L3N2Zz4=" alt="LiveChat"></a>
					<a href="https://zetamatic.com/" target = "_blank" style = "text-decoration:none;" title = "<?php echo __('Chat with our expert team for any help or queries.', 'wc_mailchimp_newsletter_discount'); ?>"><?php echo __(' Live Chat', 'wc_mailchimp_newsletter_discount'); ?></a>
					</h4>
				
				</div>

				<div class = "zetamatic_support_livechat" style = "margin-right: 0.625rem;">
					<h4 style = "margin-bottom:2;">
					</a> <a href = "https://wordpress.org/plugins/woo-mailchimp-newsletter-discount/" target = "_blank"  style = "text-decoration: none;" title = "<?php echo __(' Open a Ticket', 'wc_mailchimp_newsletter_discount'); ?>"><img style = "height: 14px; width: 17px;" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjwhRE9DVFlQRSBzdmcgIFBVQkxJQyAnLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4nICAnaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkJz48c3ZnIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDY0IDY0IiBoZWlnaHQ9IjY0cHgiIGlkPSJMYXllcl8xIiB2ZXJzaW9uPSIxLjEiIHZpZXdCb3g9IjAgMCA2NCA2NCIgd2lkdGg9IjY0cHgiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPjxwYXRoIGQ9Ik02Mi45ODEsMjAuNTQ5bC0zLjU1Ny0zLjU3MWMtMS40MzEtMS40MzctMy42MzYtMC4yNjUtMy42MzYtMC4yNjVsLTAuMDA2LTAuMDA0ICBjLTIuMjk4LDAuOTU3LTUuMDQ0LDAuNTA2LTYuOTEtMS4zNjdjLTEuODY3LTEuODc2LTIuMzA4LTQuNjI4LTEuMzM3LTYuOTIzYzAuMDA5LTAuMDIyLDAuMDA5LTAuMDM2LDAuMDE5LTAuMDU5ICBjMC44My0yLjE5Ni0wLjM1LTMuNjM3LTAuNjk4LTQuMDAxbC0zLjU3NC0zLjU4OGMtMS41NjEtMS41NjctMy4yNTYtMC4xNDItMy42NzEsMC4yNTdMMS4wMzUsMzkuNDUgIGMtMi4xNDMsMi4xMzItMC4zNzYsMy44MzUtMC4zNzYsMy44MzVsNC4xMzksNC4xNTVjMCwwLDEuMDIxLDEuMTA0LDMuMDAzLDAuMzA0YzAuMDYzLTAuMDI1LDAuMTExLTAuMDM2LDAuMTY2LTAuMDUzICBjMi4zNTYtMS4xMzUsNS4yNjEtMC43NDQsNy4yMSwxLjIxMmMxLjg1NiwxLjg2NSwyLjMwNyw0LjU5NCwxLjM1OSw2Ljg4MmgwLjAwM2MwLDAtMS4yMDQsMi4yMzUsMC4yNDQsMy42ODhsMy42MjUsMy42NCAgYzAuMjU4LDAuMjU0LDIuMTYzLDEuOTY1LDQuNTQxLTAuNDAxbDM4LjMyOS0zOC4xNzNDNjMuODU2LDIzLjg2Niw2NC43NTgsMjIuMzMzLDYyLjk4MSwyMC41NDl6IE0yMy40NTYsNDUuMTk5bC0wLjMyOSwwLjMyNiAgYy0wLjM2OSwwLjM2OC0wLjc0NywwLjEzMi0wLjkzOS0wLjAzOWwtNC4yNDktNC4yNjdsLTEuMDcsMS4wNjdjLTAuNTQyLDAuNTQxLTEuMDA1LTAuMDE3LTEuMDA1LTAuMDE3bC0wLjE0Mi0wLjE0MyAgYzAsMC0wLjM4NS0wLjM0NiwwLjIwMS0wLjkyOGwzLjI1NC0zLjI0MWMwLDAsMC41MTItMC41NjYsMS0wLjA3OGwwLjExMSwwLjExMWMwLjM2NSwwLjM4OCwwLjEzNywwLjczOC0wLjAyLDAuOTA4bC0xLjEwNCwxLjEwMiAgbDQuMDg1LDQuMTAzQzIzLjc1Niw0NC42MTEsMjMuNjEzLDQ0Ljk5MywyMy40NTYsNDUuMTk5eiBNMjcuMDczLDQxLjU5NmwtMC4yNjYsMC4yNjVjLTAuNDE0LDAuNDEzLTAuOTU2LTAuMDYzLTEuMDg4LTAuMTg5ICBsLTQuNDg3LTQuNTA3YzAsMC0wLjYxOS0wLjYzNy0wLjExMS0xLjE0MWwwLjE4OS0wLjE4OGMwLDAsMC41MzgtMC41MDEsMS4yNzgsMC4yNDNsNC40MDUsNC40MjIgIEMyNy40OCw0MC45OTEsMjcuMjM1LDQxLjQwOSwyNy4wNzMsNDEuNTk2eiBNMzIuNTQ3LDM1Ljg3M2MtMC4yNzEsMC41MzUtMC42MiwxLjA0My0xLjA5NywxLjUyICBjLTAuODQsMC44MzctMS43MzYsMS4yMjEtMi42ODgsMS4xNTRjLTAuOTUzLTAuMDY0LTEuOTA0LTAuNTc0LTIuODU0LTEuNTI3Yy0wLjU5Ny0wLjYwMS0xLjAxMi0xLjIzMy0xLjI0My0xLjkwNSAgYy0wLjIzMS0wLjY2OS0wLjI2Mi0xLjMyNi0wLjA5Mi0xLjk3NnMwLjUyOS0xLjI0NiwxLjA3OS0xLjc5NWMwLjQ0LTAuNDM4LDAuOTkyLTAuNzcyLDEuNjAzLTEuMDQ1ICBjMC4yMDMtMC4wMzMsMC41NzctMC4wMzYsMC43NTUsMC4zNzlsMC4wNjksMC4xNjJjMC4zMDQsMC43MS0wLjU0NiwwLjk0Ny0wLjU0NiwwLjk0N2wwLjAwMSwwLjAwMyAgYy0wLjAzNSwwLjAxNy0wLjA3NCwwLjAzMS0wLjExLDAuMDQ3Yy0wLjI5NCwwLjEzOS0wLjU0NiwwLjMxNS0wLjc1NywwLjUyN2MtMC40NjEsMC40NTctMC42NDUsMC45ODctMC41NTMsMS41ODUgIGMwLjA5MiwwLjU5NywwLjQ0NiwxLjIwNCwxLjA2MiwxLjgyM2MxLjI4MiwxLjI4NiwyLjQwOCwxLjQ1LDMuMzc0LDAuNDg3YzAuMjgxLTAuMjc4LDAuNTUzLTAuNjg2LDAuODItMS4xNDYgIGMwLjAwMywwLDAuMDIsMC4wMDYsMC4wMiwwLjAwNnMwLjM1Mi0wLjYwNywwLjY4My0wLjI3NmwwLjIwNSwwLjIwN0MzMi42MjUsMzUuMzcxLDMyLjYwNCwzNS42ODEsMzIuNTQ3LDM1Ljg3M3ogTTM4LjM4OCwzMC4zMjYgIGwtMC41ODUsMC41ODNjLTAuMzY0LDAuMzYyLTAuOTM2LDAuMjI2LTAuOTM2LDAuMjI2bC0zLjM2Mi0wLjc5MmwtMC4xNDYsMC44ODZsMS4zODgsMS4zOTRjMC40OTgsMC40OTksMC4zODcsMC44ODQsMC4yMywxLjEwNCAgbC0wLjMwMywwLjI5OGMtMC40MjYsMC40MjctMC45MDcsMC4wNTQtMS4wODgtMC4xMTlsLTQuNTE3LTQuNTM1YzAsMC0wLjYzNi0wLjYzMi0wLjE3Ny0xLjA4N2wwLjI4Mi0wLjI4MSAgYzAuMTc3LTAuMTQ4LDAuNTk4LTAuMzc0LDEuMTUxLDAuMTgxbDEuOTY4LDEuOTc2TDMyLjA5NywyOWwtMC4zNC0zLjA2YzAsMC0wLjA1My0wLjQ1NywwLjI5NC0wLjgwM2wwLjQ1OC0wLjQ1NyAgYzAsMCwwLjU1NS0wLjU2LDAuNjQ3LDAuMjQ1YzAsMCwwLjAwMiwwLDAuMDAyLDAuMDAzbDAuNDM4LDMuNzZsNC41MywxLjAzN0MzOC4zNjgsMjkuNzkxLDM4Ljc1MywyOS45NjEsMzguMzg4LDMwLjMyNnogICBNNDIuNTI4LDI2LjMzbC0yLjIxMywyLjIwNWMtMC41NzYsMC41NzQtMS4xMTMsMC4xMzctMS4yNjYtMC4wMTFsLTQuNDY4LTQuNDg0YzAsMC0wLjU1NS0wLjc0NCwwLjAyOS0xLjMyN2wyLjE2NC0yLjE1NCAgYzAsMCwwLjQ1Ni0wLjUxLDAuODMzLTAuMTMxbDAuMTczLDAuMTcyYzAsMCwwLjUzMSwwLjQyNy0wLjAwNywwLjk2MmwtMS4xNzksMS4xNzNjLTAuNDUxLDAuNDUxLTAuMDAzLDAuODc1LTAuMDAzLDAuODc1ICBsMC4zNDgsMC4zNDZjMCwwLDAuNDcxLDAuNDkzLDEuMDA3LTAuMDQybDAuOTQ4LTAuOTQ1YzAsMCwwLjQ3MS0wLjQ4NSwwLjgzMy0wLjEyM2wwLjE0MSwwLjE0MiAgYzAuMzk2LDAuNDMyLDAuMjE5LDAuNzY3LDAuMDg0LDAuOTI1bC0wLjkxMiwwLjkwOWMtMC41NzEsMC41NjgtMC4yNDksMC45ODktMC4xNDcsMS4wOTVsMC42MjQsMC42MjcgIGMwLjE4OCwwLjE0NywwLjQ5NSwwLjI3NiwwLjgyMi0wLjA1bDEuMTY0LTEuMTZjMCwwLDAuNDc4LTAuNTE2LDAuNzkxLTAuMjAzbDAuMTk4LDAuMTk4QzQyLjkxMywyNS43NjQsNDIuNjg3LDI2LjE0OCw0Mi41MjgsMjYuMzMgIHogTTQ1LjcwNCwyMy4wNDFsLTAuMTkyLDAuMTg5Yy0wLjM4MSwwLjM3OS0wLjg1LTAuMDE3LTEuMDAyLTAuMTY1bC0zLjYyNS0zLjYzOWMtMC40Ny0wLjQ3MS0wLjg3NS0wLjE4LTEuMDE2LTAuMDQ5bC0wLjY1NywwLjY1NiAgYy0wLjQ1OCwwLjQ1Ny0wLjg4Ni0wLjAzOS0wLjg4Ni0wLjAzOWwtMC4xNzQtMC4xNzZjMCwwLTAuMzgyLTAuMzgsMC4wNzYtMC44MzdsMy4zNjktMy4zNTRjMC4yMDMtMC4xODMsMC42MTItMC40NTYsMC45NjMtMC4xMDQgIGwwLjEwOSwwLjExMWMwLjM4OSwwLjQxLDAuMTY1LDAuNzcyLDAuMDI0LDAuOTMxbC0wLjY3OCwwLjY3NGMtMC4wOTEsMC4wOTUtMC4zODksMC40ODIsMC4xNTYsMS4wMzFsMy41NjQsMy41NzkgIEM0NS45MzIsMjIuMDcxLDQ2LjIxMywyMi41NDIsNDUuNzA0LDIzLjA0MXoiIGZpbGw9IiMyNDFGMjAiLz48L3N2Zz4=" alt="LiveChat"></a>
					<a href="https://wordpress.org/support/plugin/woo-mailchimp-newsletter-discount/" target = "_blank" style = "text-decoration:none;" title = "<?php echo __('Still need help? Submit a ticket and one of our support experts will get back to you as soon as possible.', 'wc_mailchimp_newsletter_discount'); ?>"><?php echo __(' Open a Ticket', 'wc_mailchimp_newsletter_discount'); ?></a>
					</h4>
				
				</div>

			</div>

		</div>

	    <h2 class="nav-tab-wrapper">
	        <?php echo WCMND_Options_Framework_Interface::wcmnd_optionsframework_tabs(); ?>
	    </h2>

	    <?php settings_errors( 'options-framework' ); ?>

	    <div id="wcmnd_optionsframework-metabox" class="metabox-holder">
		    <div id="wcmnd_optionsframework" class="postbox">
				<form action="options.php" method="post">
				<?php settings_fields( 'wcmnd_optionsframework' ); ?>
				<?php WCMND_Options_Framework_Interface::wcmnd_optionsframework_fields(); /* Settings */ ?>
				<div id="wcmnd_optionsframework-submit">
					<input type="submit" class="button-primary" name="update" value="<?php esc_attr_e( 'Save Options', 'textdomain' ); ?>" />
					<input type="submit" class="reset-button button-secondary" name="reset" value="<?php esc_attr_e( 'Restore Defaults', 'textdomain' ); ?>" onclick="return confirm( '<?php print esc_js( __( 'Click OK to reset. Any theme settings will be lost!', 'textdomain' ) ); ?>' );" />
					<div class="clear"></div>
				</div>
				</form>

			</div> <!-- / #container -->
      <div class="upgrade-to-pro-section"><a target="_blank" href="<?php echo esc_url('https://zetamatic.com/downloads/woocommerce-mailchimp-newsletter-discount/?utm_src=woo-mailchimp-newsletter-discount/'); ?>"><img src="<?php echo esc_url (WCMND_OPTIONS_FRAMEWORK_DIRECTORY. 'images/upgrade-to-pro.png'); ?>" alt="Upgrade to Pro"></a>  <a target="_blank" href="<?php echo esc_url('https://zetamatic.com/downloads/extra-fields-for-mailchimp-newsletter-discount/?utm_src=extra-fields-for-mailchimp-newsletter-discount/'); ?>"><img src="<?php echo esc_url (WCMND_OPTIONS_FRAMEWORK_DIRECTORY. 'images/extra-fields-addon.png'); ?>" alt="Extra Fields Addon"></a></div>
		</div>
		<?php do_action( 'wcmnd_optionsframework_after' ); ?>
		</div> <!-- / .wrap -->

	<?php
	}

	/**
	 * Validate Options.
	 *
	 * This runs after the submit/reset button has been clicked and
	 * validates the inputs.
	 *
	 * @uses $_POST['reset'] to restore default options
	 */
	function validate_options( $input ) {

		/*
		 * Restore Defaults.
		 *
		 * In the event that the user clicked the "Restore Defaults"
		 * button, the options defined in the theme's options.php
		 * file will be added to the option for the active theme.
		 */

		if ( isset( $_POST['reset'] ) ) {
			add_settings_error( 'options-framework', 'restore_defaults', __( 'Default options restored.', 'textdomain' ), 'updated fade' );
			return $this->get_default_values();
		}

		/*
		 * Update Settings
		 *
		 * This used to check for $_POST['update'], but has been updated
		 * to be compatible with the theme customizer introduced in WordPress 3.4
		 */

		$clean = array();
		$options = & WCMND_Options_Framework::_wcmnd_optionsframework_options();
		foreach ( $options as $option ) {

			if ( ! isset( $option['id'] ) ) {
				continue;
			}

			if ( ! isset( $option['type'] ) ) {
				continue;
			}

			$id = preg_replace( '/[^a-zA-Z0-9._\-]/', '', strtolower( $option['id'] ) );

			// Set checkbox to false if it wasn't sent in the $_POST
			if ( 'checkbox' == $option['type'] && ! isset( $input[$id] ) ) {
				$input[$id] = false;
			}

      // Set onoff to false if it wasn't sent in the $_POST
			if ( 'onoff' == $option['type'] && ! isset( $input[$id] ) ) {
				$input[$id] = false;
			}

			// Set each item in the multicheck to false if it wasn't sent in the $_POST
			if ( 'multicheck' == $option['type'] && ! isset( $input[$id] ) ) {
				foreach ( $option['options'] as $key => $value ) {
					$input[$id][$key] = false;
				}
			}

			// For a value to be submitted to database it must pass through a sanitization filter
			if ( has_filter( 'wcmnd_of_sanitize_' . $option['type'] ) ) {
				$clean[$id] = apply_filters( 'wcmnd_of_sanitize_' . $option['type'], $input[$id], $option );
			}
		}

		// Hook to run after validation
		do_action( 'wcmnd_optionsframework_after_validate', $clean );

		return $clean;
	}

	/**
	 * Display message when options have been saved
	 */

	function save_options_notice() {
		add_settings_error( 'options-framework', 'save_options', __( 'Alright Sparky! Options Saved For MailChimp Newsletter Discounts.', 'wc_newsletter_discounts' ), 'updated fade' );
	}

	/**
	 * Get the default values for all the theme options
	 *
	 * Get an array of all default values as set in
	 * options.php. The 'id','std' and 'type' keys need
	 * to be defined in the configuration array. In the
	 * event that these keys are not present the option
	 * will not be included in this function's output.
	 *
	 * @return array Re-keyed options configuration array.
	 *
	 */

	function get_default_values() {
		$output = array();
		$config = & WCMND_Options_Framework::_wcmnd_optionsframework_options();
		foreach ( (array) $config as $option ) {
			if ( ! isset( $option['id'] ) ) {
				continue;
			}
			if ( ! isset( $option['std'] ) ) {
				continue;
			}
			if ( ! isset( $option['type'] ) ) {
				continue;
			}
			if ( has_filter( 'wcmnd_of_sanitize_' . $option['type'] ) ) {
				$output[$option['id']] = apply_filters( 'wcmnd_of_sanitize_' . $option['type'], $option['std'], $option );
			}
		}
		return $output;
	}

	/**
	 * Add options menu item to admin bar
	 */

	function wcmnd_optionsframework_admin_bar() {

		$menu = $this->menu_settings();

		global $wp_admin_bar;

		if ( 'menu' == $menu['mode'] ) {
			$href = admin_url( 'admin.php?page=' . $menu['menu_slug'] );
		} else {
			$href = admin_url( 'themes.php?page=' . $menu['menu_slug'] );
		}

		$args = array(
			'parent' => 'appearance',
			'id' => 'wcmnd_of_theme_options',
			'title' => $menu['menu_title'],
			'href' => $href
		);

		$wp_admin_bar->add_menu( apply_filters( 'wcmnd_optionsframework_admin_bar', $args ) );
	}

}
