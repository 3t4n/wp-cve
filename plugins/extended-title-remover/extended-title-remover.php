<?php
/*
Plugin Name: Extended Title Remover
Plugin URI: http://wpgurus.net/extended-title-remover/
Description: Extended Title Remover gives you the ability to hide the title of any post, page or custom post type item without affecting menus or titles from the admin area.
Version: 1
Author: Amit Mittal
Author URI: 
*/

/*--------------------------------------------------
	Title Remover
----------------------------------------------------*/

class WpEtrTitleRemove
{
    /**
     * Start up
     */
    public function __construct()
    {
        add_filter( 'the_title', array($this,'wpetr_supress_title'), 10, 2 );
        add_action( 'load-post.php',  array($this,'wpetr_post_meta_boxes_setup') );
		add_action( 'load-post-new.php',  array($this,'wpetr_post_meta_boxes_setup') );
    }

    /**
     * Add options page
     */
    public function wpetr_supress_title( $title, $post_id = 0 ) {
		if ( ! $post_id ) {
			return $title;
		}
		
		$extended_title_remover = get_option('extended_title_remover');
		if(is_array($extended_title_remover) && array_key_exists(get_post_type($post_id),$extended_title_remover) && ! is_admin() && !is_singular() && in_the_loop()){
			return '';
		}
		
		$hide_title = get_post_meta( $post_id, 'wpetr_hide_title', true );
		if ( ! is_admin() && !is_singular() && intval( $hide_title ) && in_the_loop() ) {
			return '';
		}

		return $title;
	}
	
	public function wpetr_post_meta_boxes_setup() {
		/* Add meta boxes on the 'add_meta_boxes' hook. */
		add_action( 'add_meta_boxes', array($this,'wpetr_add_post_meta_boxes') );

		/* Save post meta on the 'save_post' hook. */
		add_action( 'save_post', array($this,'wpetr_save_meta'), 10, 2 );
	}

	public function wpetr_add_post_meta_boxes() {
		add_meta_box(
			'wpetr-hide-title',        // Unique ID
			'Hide Title?',            // Title
			array($this,'wpetr_render_metabox'),    // Callback function
			null,                    // Admin page
			'side',                    // Context
			'core'                    // Priority
		);
	}
	
	public function wpetr_render_metabox( $post ) {
		$curr_value = get_post_meta( $post->ID, 'wpetr_hide_title', true );
		wp_nonce_field( basename( __FILE__ ), 'wpetr_meta_nonce' );
		?>
		<input type="hidden" name="wpetr-hide-title-checkbox" value="0"/>
		<input type="checkbox" name="wpetr-hide-title-checkbox" id="wpetr-hide-title-checkbox"
			   value="1" <?php checked( $curr_value, '1' ); ?> />
		<label for="wpetr-hide-title-checkbox">Hide the title for this item</label>
		<?php
	}	
		
	public function wpetr_save_meta( $post_id, $post ) {

		/* Verify the nonce before proceeding. */
		if ( ! isset( $_POST['wpetr_meta_nonce'] ) || ! wp_verify_nonce( $_POST['wpetr_meta_nonce'], basename( __FILE__ ) ) ) {
			return;
		}

		/* Get the post type object. */
		$post_type = get_post_type_object( $post->post_type );

		/* Check if the current user has permission to edit the post. */
		if ( ! current_user_can( $post_type->cap->edit_post, $post_id ) ) {
			return;
		}

		/* Get the posted data and sanitize it for use as an HTML class. */
		$form_data = ( isset( $_POST['wpetr-hide-title-checkbox'] ) ? intval($_POST['wpetr-hide-title-checkbox']) : '0' );
		update_post_meta( $post_id, 'wpetr_hide_title', $form_data );
	}
}
$wp_etr_title_remove = new WpEtrTitleRemove();

class WpEtrSettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin',
            'Extended Title Remover',
            'manage_options',
            'etr-setting-admin',
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'extended_title_remover' );
        ?>
        <div class="wrap">
            <h1>Extended Title Remover Settings</h1>
            <form method="post" action="options.php">
                <?php
                // This prints out all hidden setting fields
                settings_fields( 'my_option_group' );
                do_settings_sections( 'etr-setting-admin' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'my_option_group', // Option group
            'extended_title_remover', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Global Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'etr-setting-admin' // Page
        );

        add_settings_field(
            'id_number', // ID
            '', // Title
            array( $this, 'id_number_callback' ), // Callback
            'etr-setting-admin', // Page
            'setting_section_id' // Section
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $inputs )
    {
        $new_input = array();
        foreach($inputs as $key=>$input){
            $new_input[$key] = intval($input);
        }
        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        print "Select the post types where you want to hide the titles.<br/>(This option will override the indivial post type settings.)";
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function id_number_callback()
    {
        $args = array(
            'public'   => true
        );
        $output = 'names'; // names or objects, note names is the default
        $operator = 'and'; // 'and' or 'or'
        $post_types = get_post_types( $args, $output, $operator );
        echo '<table><thead><tr><th>Post type</th><th>Enable</th></tr></thead>';
        foreach ( $post_types  as $post_type ) {
            printf(
                '<tr><td>'.ucfirst($post_type) . '</td><td><input type="checkbox" name="extended_title_remover[' . $post_type . ']" value="1" %s />'."</td></tr>",
                isset($this->options[$post_type]) && intval($this->options[$post_type]) ? 'checked="checked"' : ''
            );
        }
        echo '</table>';
    }

}

if( is_admin() )
    $wp_etr_settings_page = new WpEtrSettingsPage();