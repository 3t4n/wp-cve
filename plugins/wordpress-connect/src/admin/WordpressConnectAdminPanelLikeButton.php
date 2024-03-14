<?php

/**
 * @author Tomas Vorobjov
 * @version 2.0
 * @date 29 April 2011
 *
 * @file WordpressConnectAdminPanelLikeButton.php
 *
 * This class provides functionality for the wordpress dashboard admin
 * panel for the Wordpress Connect Like Button facebook plugin
 */
class WordpressConnectAdminPanelLikeButton {


	/**
	 * Creates a new instance of WordpressConnectAdminPanelLikeButton
	 *
	 * @since	2.0
	 *
	 */
	function WordpressConnectAdminPanelLikeButton(){

		add_action( 'admin_init', array( &$this, 'add_admin_settings' ), 9 );
		add_action( 'admin_menu', array( &$this, 'add_admin_panel' ) );

	}

	/**
	 * Adds plugin's admin panel to the wp dashboard
	 *
	 * @private
	 * @since	2.0
	 */
	function add_admin_settings(){

		if ( !current_user_can( 'manage_options' ) ) { return;	}
				
		register_setting( WPC_OPTIONS_LIKE_BUTTON, WPC_OPTIONS_LIKE_BUTTON, array( &$this, 'admin_like_button_settings_validate' ) );

		// adds sections
		add_settings_section( WPC_SETTINGS_SECTION_LIKE_BUTTON, __( 'Plugin Options', WPC_TEXT_DOMAIN ), array( &$this, 'admin_section_like_button' ), WPC_SETTINGS_LIKE_BUTTON_PAGE );
		add_settings_section( WPC_SETTINGS_SECTION_LIKE_BUTTON_POSITION, __( 'Position Settings', WPC_TEXT_DOMAIN ), array( &$this, 'admin_section_like_button_position' ), WPC_SETTINGS_LIKE_BUTTON_PAGE );
		add_settings_section( WPC_SETTINGS_SECTION_LIKE_BUTTON_ENABLED, __( 'Enable Settings', WPC_TEXT_DOMAIN ), array( &$this, 'admin_section_like_button_enable' ), WPC_SETTINGS_LIKE_BUTTON_PAGE );
		add_settings_section( WPC_SETTINGS_SECTION_LIKE_BUTTON_DISPLAY, __( 'Display Settings', WPC_TEXT_DOMAIN ), array( &$this, 'admin_section_like_button_display' ), WPC_SETTINGS_LIKE_BUTTON_PAGE );

		// like button settings
		add_settings_field( WPC_OPTIONS_LIKE_BUTTON_SEND, __( 'Send Button', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_like_button_send' ), WPC_SETTINGS_LIKE_BUTTON_PAGE, WPC_SETTINGS_SECTION_LIKE_BUTTON );
		add_settings_field( WPC_OPTIONS_LIKE_BUTTON_LAYOUT, __( 'Layout Style', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_like_button_layout' ), WPC_SETTINGS_LIKE_BUTTON_PAGE, WPC_SETTINGS_SECTION_LIKE_BUTTON );
		add_settings_field( WPC_OPTIONS_LIKE_BUTTON_WIDTH, __( 'Width', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_like_button_width' ), WPC_SETTINGS_LIKE_BUTTON_PAGE, WPC_SETTINGS_SECTION_LIKE_BUTTON );
		add_settings_field( WPC_OPTIONS_LIKE_BUTTON_FACES, __( 'Show Faces', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_like_button_faces' ), WPC_SETTINGS_LIKE_BUTTON_PAGE, WPC_SETTINGS_SECTION_LIKE_BUTTON );
		add_settings_field( WPC_OPTIONS_LIKE_BUTTON_VERB, __( 'Verb to display', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_like_button_verb' ), WPC_SETTINGS_LIKE_BUTTON_PAGE, WPC_SETTINGS_SECTION_LIKE_BUTTON );
		add_settings_field( WPC_OPTIONS_LIKE_BUTTON_FONT, __( 'Font', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_like_button_font' ), WPC_SETTINGS_LIKE_BUTTON_PAGE, WPC_SETTINGS_SECTION_LIKE_BUTTON );
		add_settings_field( WPC_OPTIONS_LIKE_BUTTON_REF, __( 'Ref', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_like_button_ref' ), WPC_SETTINGS_LIKE_BUTTON_PAGE, WPC_SETTINGS_SECTION_LIKE_BUTTON );

		// like button position settings
		add_settings_field( WPC_OPTIONS_LIKE_BUTTON_POSITION, __( 'Default Position', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_like_button_position_default' ), WPC_SETTINGS_LIKE_BUTTON_PAGE, WPC_SETTINGS_SECTION_LIKE_BUTTON_POSITION );

		// like button enable settings
		add_settings_field( WPC_OPTIONS_LIKE_BUTTON_ENABLED, __( 'Enabled', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_like_button_enable_enabled' ), WPC_SETTINGS_LIKE_BUTTON_PAGE, WPC_SETTINGS_SECTION_LIKE_BUTTON_ENABLED );

		// like button display settings
		add_settings_field( WPC_OPTIONS_DISPLAY_EVERYWHERE, __( 'Everywhere', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_like_button_enable_everywhere' ), WPC_SETTINGS_LIKE_BUTTON_PAGE, WPC_SETTINGS_SECTION_LIKE_BUTTON_DISPLAY );
		add_settings_field( WPC_OPTIONS_DISPLAY_HOMEPAGE, __( 'Homepage', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_like_button_enable_homepage' ), WPC_SETTINGS_LIKE_BUTTON_PAGE, WPC_SETTINGS_SECTION_LIKE_BUTTON_DISPLAY );
		add_settings_field( WPC_OPTIONS_DISPLAY_POSTS, __( 'Single Post', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_like_button_enable_post' ), WPC_SETTINGS_LIKE_BUTTON_PAGE, WPC_SETTINGS_SECTION_LIKE_BUTTON_DISPLAY );
		add_settings_field( WPC_OPTIONS_DISPLAY_PAGES, __( 'Single Page', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_like_button_enable_page' ), WPC_SETTINGS_LIKE_BUTTON_PAGE, WPC_SETTINGS_SECTION_LIKE_BUTTON_DISPLAY );
		add_settings_field( WPC_OPTIONS_DISPLAY_CATEGORIES, __( 'Categories', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_like_button_enable_categories' ), WPC_SETTINGS_LIKE_BUTTON_PAGE, WPC_SETTINGS_SECTION_LIKE_BUTTON_DISPLAY );
		add_settings_field( WPC_OPTIONS_DISPLAY_TAGS, __( 'Tags', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_like_button_enable_tags' ), WPC_SETTINGS_LIKE_BUTTON_PAGE, WPC_SETTINGS_SECTION_LIKE_BUTTON_DISPLAY );
		add_settings_field( WPC_OPTIONS_DISPLAY_SEARCH, __( 'Search', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_like_button_enable_search' ), WPC_SETTINGS_LIKE_BUTTON_PAGE, WPC_SETTINGS_SECTION_LIKE_BUTTON_DISPLAY );
		add_settings_field( WPC_OPTIONS_DISPLAY_ARCHIVE, __( 'Archive', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_like_button_enable_archive' ), WPC_SETTINGS_LIKE_BUTTON_PAGE, WPC_SETTINGS_SECTION_LIKE_BUTTON_DISPLAY );
		add_settings_field( WPC_OPTIONS_DISPLAY_NOWHERE, __( 'Nowhere', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_like_button_enable_nowhere' ), WPC_SETTINGS_LIKE_BUTTON_PAGE, WPC_SETTINGS_SECTION_LIKE_BUTTON_DISPLAY );

	}

	/**
	 * Validates like button settings
	 * @param	$input the settings value
	 */
	function admin_like_button_settings_validate( $input ){

		$input = apply_filters( WPC_OPTIONS_LIKE_BUTTON, $input ); // filter to let sub-plugins validate their options too
		return $input;
	}

	/**
	 */
	function admin_section_like_button(){}

	/**
	 */
	function admin_section_like_button_position(){}

	/**
	 */
	function admin_section_like_button_enable(){}

	/**
	 */
	function admin_section_like_button_display(){

		$options = get_option( WPC_OPTIONS_LIKE_BUTTON );
		$everywhere_checked = !empty( $options[ WPC_OPTIONS_DISPLAY_EVERYWHERE ] ) ? 'true' : 'false';
		$nowhere_checked = !empty( $options[ WPC_OPTIONS_DISPLAY_NOWHERE ] ) ? 'true' : 'false';
?>
	<script language="javascript">
	( function( $ ) {
		WPC_ENABLED = {};
		WPC_ENABLED.Application = function(){
			var tmp = {};
			/**
			 * handling attr and prop changes in jquery 1.6.1
			 */
			tmp.isChecked = function( checkbox ){
				var checked = false;
				try {
					checked = checkbox.prop( 'checked' );
				}
				catch(e){
					checked = checkbox.attr( 'checked' );
				}
				return checked;
			}
			tmp.everywhere_checked = <?php echo $everywhere_checked; ?>;
			tmp.nowhere_checked = <?php echo $nowhere_checked; ?>;

			tmp.enable_fields = function( flag ){
				for ( var i = 0; i < tmp.enable_inputs.length; i++ ){
					var field = tmp.enable_inputs[i];
					field.attr( 'disabled', flag );
				}
			}
			tmp.enable_everywhere = function(){
				var checked = tmp.isChecked( tmp.everywhere_cb );
				tmp.enable_fields( checked );
				tmp.nowhere_cb.attr( 'checked', false );
			}
			tmp.enable_nowhere = function(){
				var checked = tmp.isChecked( tmp.nowhere_cb );
				tmp.enable_fields( checked );
				tmp.everywhere_cb.attr( 'checked', false );
			}
			var pub = {};
			pub.initialize = function(){

				tmp.enable_inputs = [
     				$('#<?php echo WPC_OPTIONS_DISPLAY_HOMEPAGE; ?>')
     				,$('#<?php echo WPC_OPTIONS_DISPLAY_POSTS; ?>')
     				,$('#<?php echo WPC_OPTIONS_DISPLAY_PAGES; ?>')
     				,$('#<?php echo WPC_OPTIONS_DISPLAY_CATEGORIES; ?>')
     				,$('#<?php echo WPC_OPTIONS_DISPLAY_TAGS; ?>')
     				,$('#<?php echo WPC_OPTIONS_DISPLAY_SEARCH; ?>')
     				,$('#<?php echo WPC_OPTIONS_DISPLAY_ARCHIVE; ?>')
     			];

				tmp.everywhere_cb = $('#<?php echo WPC_OPTIONS_DISPLAY_EVERYWHERE;?>');
				tmp.nowhere_cb = $('#<?php echo WPC_OPTIONS_DISPLAY_NOWHERE;?>');

				tmp.everywhere_cb.click( function(){
					tmp.enable_everywhere();
				});
				tmp.nowhere_cb.click( function(){
					tmp.enable_nowhere();
				});
				if ( tmp.everywhere_checked ){
					tmp.enable_everywhere();
				}
				else if ( tmp.nowhere_checked ){
					tmp.enable_nowhere();
				}
			}
			return pub;
		}();

	})( jQuery );

	if ( document.readyState === 'complete' ) {
		WPC_ENABLED.Application.initialize();
	}
	else {
		jQuery(document).ready(function(){
			WPC_ENABLED.Application.initialize();
		});
	}

	</script>
<?php
	}

	/**
	 * Renders the like button send button field
	 */
	function admin_setting_like_button_send(){

		$options = get_option( WPC_OPTIONS_LIKE_BUTTON );
		$default_value = $options[ WPC_OPTIONS_LIKE_BUTTON_SEND ];

?>
			<select id="<?php echo WPC_OPTIONS_LIKE_BUTTON_SEND; ?>-enabled" name="<?php echo WPC_OPTIONS_LIKE_BUTTON,'[',WPC_OPTIONS_LIKE_BUTTON_SEND,']'; ?>">
				<option <?php echo ( $default_value == WPC_OPTION_ENABLED ) ? 'selected="selected"' : ''; ?> value="<?php echo WPC_OPTION_ENABLED; ?>"><?php _e( 'Enabled', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $default_value == WPC_OPTION_DISABLED ) ? 'selected="selected"' : ''; ?> value="<?php echo WPC_OPTION_DISABLED; ?>"><?php _e( 'Disabled', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
			</select>
			<p><span class="description"><?php _e( 'Include a Send Button', WPC_TEXT_DOMAIN ); ?></span></p>
<?php

	}

	/**
	 * Renders the like button layout style field
	 */
	function admin_setting_like_button_layout(){

		$options = get_option( WPC_OPTIONS_LIKE_BUTTON );
		$default_value = $options[ WPC_OPTIONS_LIKE_BUTTON_LAYOUT ];

?>
			<select id="<?php echo WPC_OPTIONS_LIKE_BUTTON_LAYOUT; ?>" name="<?php echo WPC_OPTIONS_LIKE_BUTTON,'[',WPC_OPTIONS_LIKE_BUTTON_LAYOUT,']'; ?>">
				<option <?php echo ( $default_value == WPC_LAYOUT_STANDARD ) ? 'selected="selected"' : ''; ?> value="<?php echo WPC_LAYOUT_STANDARD; ?>"><?php echo WPC_LAYOUT_STANDARD; ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $default_value == WPC_LAYOUT_BUTTON_COUNT ) ? 'selected="selected"' : ''; ?> value="<?php echo WPC_LAYOUT_BUTTON_COUNT; ?>"><?php echo WPC_LAYOUT_BUTTON_COUNT; ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $default_value == WPC_LAYOUT_BOX_COUNT ) ? 'selected="selected"' : ''; ?> value="<?php echo WPC_LAYOUT_BOX_COUNT; ?>"><?php echo WPC_LAYOUT_BOX_COUNT; ?>&nbsp;&nbsp;&nbsp;</option>
			</select>
			<p><span class="description"><?php _e( 'Determines the size and amount of social context next to the button.', WPC_TEXT_DOMAIN ); ?></span></p>
<?php

	}

	/**
	 * Renders the like button width field
	 */
	function admin_setting_like_button_width(){

		$options = get_option( WPC_OPTIONS_LIKE_BUTTON );
?>
		<input type="text" id="<?php echo WPC_OPTIONS_LIKE_BUTTON_WIDTH; ?>" name="<?php echo WPC_OPTIONS_LIKE_BUTTON,'[',WPC_OPTIONS_LIKE_BUTTON_WIDTH.']'; ?>" value="<?php echo $options[ WPC_OPTIONS_LIKE_BUTTON_WIDTH ]; ?>" size="6" />
		<p><span class="description"><?php _e( 'The width of the plugin, in pixels.', WPC_TEXT_DOMAIN ); ?></span></p>
<?php
	}

	/**
	 * Renders the like button faces field
	 */
	function admin_setting_like_button_faces(){

		$options = get_option( WPC_OPTIONS_LIKE_BUTTON );
		$default_value = $options[ WPC_OPTIONS_LIKE_BUTTON_FACES ];

?>
			<select id="<?php echo WPC_OPTIONS_LIKE_BUTTON_FACES; ?>-enabled" name="<?php echo WPC_OPTIONS_LIKE_BUTTON,'[',WPC_OPTIONS_LIKE_BUTTON_FACES,']'; ?>">
				<option <?php echo ( $default_value == WPC_OPTION_ENABLED ) ? 'selected="selected"' : ''; ?> value="<?php echo WPC_OPTION_ENABLED; ?>"><?php _e( 'Enabled', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $default_value == WPC_OPTION_DISABLED ) ? 'selected="selected"' : ''; ?> value="<?php echo WPC_OPTION_DISABLED; ?>"><?php _e( 'Disabled', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
			</select>
			<p><span class="description"><?php _e( 'Show profile pictures below the button.', WPC_TEXT_DOMAIN ); ?></span></p>
<?php

	}

	/**
	 * Renders the like button verb field
	 */
	function admin_setting_like_button_verb(){

		$options = get_option( WPC_OPTIONS_LIKE_BUTTON );
		$default_value = $options[ WPC_OPTIONS_LIKE_BUTTON_VERB ];

?>
			<select id="<?php echo WPC_OPTIONS_LIKE_BUTTON_VERB; ?>" name="<?php echo WPC_OPTIONS_LIKE_BUTTON,'[',WPC_OPTIONS_LIKE_BUTTON_VERB,']'; ?>">
				<option <?php echo ( $default_value == WPC_ACTION_LIKE ) ? 'selected="selected"' : ''; ?> value="<?php echo WPC_ACTION_LIKE; ?>"><?php echo WPC_ACTION_LIKE; ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $default_value == WPC_ACTION_RECOMMEND ) ? 'selected="selected"' : ''; ?> value="<?php echo WPC_ACTION_RECOMMEND; ?>"><?php echo WPC_ACTION_RECOMMEND; ?>&nbsp;&nbsp;&nbsp;</option>
			</select>
			<p><span class="description"><?php _e( 'The verb to display in the button. Currently only "like" and "recommend" are supported.', WPC_TEXT_DOMAIN ); ?></span></p>
<?php
	}

	/**
	 * Renders the like button font field
	 */
	function admin_setting_like_button_font(){

		$options = get_option( WPC_OPTIONS_LIKE_BUTTON );
		$default_value = $options[ WPC_OPTIONS_LIKE_BUTTON_FONT ];

?>
			<select id="<?php echo WPC_OPTIONS_LIKE_BUTTON_FONT; ?>" name="<?php echo WPC_OPTIONS_LIKE_BUTTON,'[',WPC_OPTIONS_LIKE_BUTTON_FONT,']'; ?>">
				<option <?php echo ( $default_value == WPC_FONT_ARIAL ) ? 'selected="selected"' : ''; ?> value="<?php echo WPC_FONT_ARIAL; ?>"><?php echo WPC_FONT_ARIAL; ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $default_value == WPC_FONT_LUCIDA_GRANDE ) ? 'selected="selected"' : ''; ?> value="<?php echo WPC_FONT_LUCIDA_GRANDE; ?>"><?php echo WPC_FONT_LUCIDA_GRANDE; ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $default_value == WPC_FONT_SEGOE_UI ) ? 'selected="selected"' : ''; ?> value="<?php echo WPC_FONT_SEGOE_UI; ?>"><?php echo WPC_FONT_SEGOE_UI; ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $default_value == WPC_FONT_TAHOMA ) ? 'selected="selected"' : ''; ?> value="<?php echo WPC_FONT_TAHOMA; ?>"><?php echo WPC_FONT_TAHOMA; ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $default_value == WPC_FONT_TREBUCHET_MS ) ? 'selected="selected"' : ''; ?> value="<?php echo WPC_FONT_TREBUCHET_MS; ?>"><?php echo WPC_FONT_TREBUCHET_MS; ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $default_value == WPC_FONT_VERDANA ) ? 'selected="selected"' : ''; ?> value="<?php echo WPC_FONT_VERDANA; ?>"><?php echo WPC_FONT_VERDANA; ?>&nbsp;&nbsp;&nbsp;</option>
			</select>
			<p><span class="description"><?php _e( 'The font of the plugin', WPC_TEXT_DOMAIN ); ?></span></p>
<?php

	}

	/**
	 * Renders the like button ref field
	 */
	function admin_setting_like_button_ref(){

		$options = get_option( WPC_OPTIONS_LIKE_BUTTON );
?>
		<input type="text" id="<?php echo WPC_OPTIONS_LIKE_BUTTON_REF;?>" name="<?php echo WPC_OPTIONS_LIKE_BUTTON,'[',WPC_OPTIONS_LIKE_BUTTON_REF,']';?>" value="<?php echo $options[ WPC_OPTIONS_LIKE_BUTTON_REF ]; ?>" size="52" />
		<p><span class="description"><?php _e( "A label for tracking referrals; must be less than 50 characters and can contain alphanumeric characters and some punctuation (currently +/=-.:_).", WPC_TEXT_DOMAIN ); ?></span></p>
<?php
	}

	/**
	 * Renders the like button default position
	 */
	function admin_setting_like_button_position_default(){

		$options = get_option( WPC_OPTIONS_LIKE_BUTTON );
		$position_value = $options[ WPC_OPTIONS_LIKE_BUTTON_POSITION ];

		$positions = array(
			WPC_CUSTOM_FIELD_VALUE_POSITION_TOP => __( 'Top', WPC_TEXT_DOMAIN ),
			WPC_CUSTOM_FIELD_VALUE_POSITION_BOTTOM => __( 'Bottom', WPC_TEXT_DOMAIN ),
			WPC_CUSTOM_FIELD_VALUE_POSITION_CUSTOM => __( 'Custom', WPC_TEXT_DOMAIN )
		);
?>
			<select id="<?php echo WPC_OPTIONS_LIKE_BUTTON_POSITION; ?>" name="<?php echo WPC_OPTIONS_LIKE_BUTTON,'[',WPC_OPTIONS_LIKE_BUTTON_POSITION,']'; ?>">
<?php
		 foreach ( $positions as $value => $position ) : ?>
				<option <?php echo ( $value == $position_value ) ? 'selected="selected"' : ''; ?> value="<?php echo $value; ?>"><?php echo $position; ?>&nbsp;&nbsp;&nbsp;</option>
<?php 	endforeach; ?>
			</select>
			<p><span class="description"><?php _e( 'The default position of the like button plugin within a post/page. This value can be changed for every post/page individually.', WPC_TEXT_DOMAIN ); ?></span></p>
<?php



	}

	/**
	 * Renders the like button enable default field
	 */
	function admin_setting_like_button_enable_enabled(){

		$options = get_option( WPC_OPTIONS_LIKE_BUTTON );
		$default_value = $options[ WPC_OPTIONS_LIKE_BUTTON_ENABLED ];

?>
			<select id="<?php echo WPC_OPTIONS_LIKE_BUTTON; ?>-enabled" name="<?php echo WPC_OPTIONS_LIKE_BUTTON,'[',WPC_OPTIONS_LIKE_BUTTON_ENABLED,']'; ?>">
				<option <?php echo ( $default_value == WPC_OPTION_ENABLED ) ? 'selected="selected"' : ''; ?> value="<?php echo WPC_OPTION_ENABLED; ?>"><?php _e( 'Enabled', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $default_value == WPC_OPTION_DISABLED ) ? 'selected="selected"' : ''; ?> value="<?php echo WPC_OPTION_DISABLED; ?>"><?php _e( 'Disabled', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
			</select>
			<p><span class="description"><?php _e( 'This value specifies whether or not Like Button is enabled on posts and pages by default', WPC_TEXT_DOMAIN ); ?></span></p>
			<p><span class="description"><?php _e( 'This is the default value that will be selected in the Wordpress Connect box in the right side of add/edit posts/pages. The value can be changed for every individual post/page.', WPC_TEXT_DOMAIN ); ?></span></p>
<?php

	}

	/**
	 * Renders the like button enable everywhere field
	 */
	function admin_setting_like_button_enable_everywhere(){

		$options = get_option( WPC_OPTIONS_LIKE_BUTTON );
		$checked = ( !empty( $options[ WPC_OPTIONS_DISPLAY_EVERYWHERE ] ) ) ? ' checked="checked"' : '';
?>
		<input type="checkbox" id="<?php echo WPC_OPTIONS_DISPLAY_EVERYWHERE; ?>" name="<?php echo WPC_OPTIONS_LIKE_BUTTON,'[',WPC_OPTIONS_DISPLAY_EVERYWHERE,']'; ?>"<?php echo $checked; ?> />
		<span class="description"><?php _e( "Displays like button everywhere (by default).", WPC_TEXT_DOMAIN ); ?></span>
<?php
	}

	/**
	 * Renders the like button enable on homepage field
	 */
	function admin_setting_like_button_enable_homepage(){

		$description = __( 'Displays like button on the homepage.', WPC_TEXT_DOMAIN );
		$description .= sprintf( __(
			'See more <a href="%s" target="_blank">here</a> ', WPC_TEXT_DOMAIN ),
			'http://codex.wordpress.org/Function_Reference/is_home'
		);
		$description .= sprintf( __(
			'and <a href="%s" target="_blank">here</a>.', WPC_TEXT_DOMAIN ),
			'http://codex.wordpress.org/Function_Reference/is_front_page'
		);
		
		$this->aux_print_enabled_position(
			WPC_OPTIONS_DISPLAY_HOMEPAGE,
			$description
		);
	}

	/**
	 * Renders the like button enable on single post field
	 */
	function admin_setting_like_button_enable_post(){
		
		$description = __( 'Display like button on a single post.', WPC_TEXT_DOMAIN );
		
		$this->aux_print_enabled_position(
			WPC_OPTIONS_DISPLAY_POSTS,
			$description
		);		
		
	}
	
	/**
	 * Renders the like button enable on single page field
	 */
	function admin_setting_like_button_enable_page(){
		
		$description = __( 'Display like button on a single page (that is not the homepage).', WPC_TEXT_DOMAIN );
		
		$this->aux_print_enabled_position(
			WPC_OPTIONS_DISPLAY_PAGES,
			$description
		);			
	}	
	
	/**
	 * Renders the like button enable on categories field
	 */
	function admin_setting_like_button_enable_categories(){

		$description = __( 'Displays like button on the category archive pages.', WPC_TEXT_DOMAIN );
		$description .= sprintf( __(
			'See more <a href="%s" target="_blank">here</a>.', WPC_TEXT_DOMAIN ),
			'http://codex.wordpress.org/Function_Reference/is_category'
		);
		
		$this->aux_print_enabled_position(
			WPC_OPTIONS_DISPLAY_CATEGORIES,
			$description
		);		
	}

	/**
	 * Renders the like button enable on tags field
	 */
	function admin_setting_like_button_enable_tags(){

		$description = __( 'Displays like button on the tags archive pages.', WPC_TEXT_DOMAIN );
		$description .= sprintf( __(
			'See more <a href="%s" target="_blank">here</a>.', WPC_TEXT_DOMAIN ),
			'http://codex.wordpress.org/Function_Reference/is_tag'
		);		
		
		$this->aux_print_enabled_position(
			WPC_OPTIONS_DISPLAY_TAGS,
			$description
		);		
	}

	/**
	 * Renders the like button enable on search field
	 */
	function admin_setting_like_button_enable_search(){

		$description = __( 'Displays like button on the search result page.', WPC_TEXT_DOMAIN );
		$description .= sprintf( __(
			'See more <a href="%s" target="_blank">here</a>.', WPC_TEXT_DOMAIN ),
			'http://codex.wordpress.org/Function_Reference/is_search'
		);		
		
		$this->aux_print_enabled_position(
			WPC_OPTIONS_DISPLAY_SEARCH,
			$description
		);		
	}

	/**
	 * Renders the like button enable on archive field
	 */
	function admin_setting_like_button_enable_archive(){

		$description = __( 'Displays like button on the archive pages.', WPC_TEXT_DOMAIN );
		$description .= sprintf( __(
			'See more <a href="%s" target="_blank">here</a>.', WPC_TEXT_DOMAIN ),
			'http://codex.wordpress.org/Function_Reference/is_archive'
		);

		$this->aux_print_enabled_position(
			WPC_OPTIONS_DISPLAY_ARCHIVE,
			$description
		);			
	}

	/**
	 * Renders the like button enable on single page field
	 */
	function admin_setting_like_button_enable_nowhere(){

		$description = __( 'Disables like button everywhere (by default).', WPC_TEXT_DOMAIN );  

		$this->aux_print_enabled_position(
			WPC_OPTIONS_DISPLAY_NOWHERE,
			$description
		);
	}

	/**
	 * Auxilliary function to print position enable fields
	 * 
	 * @param string $option
	 * @param string $description
	 */
	function aux_print_enabled_position( $option, $description ){
		
		$options = get_option( WPC_OPTIONS_LIKE_BUTTON );
		$checked = ( !empty( $options[ $option ] ) ) ? 'checked="checked" ' : '';
?>
		<input type="checkbox" id="<?php echo $option; ?>" name="<?php echo WPC_OPTIONS_LIKE_BUTTON,'[',$option,']'; ?>"<?php echo $checked; ?> />
		<span class="description"><?php echo $description ?></span>
<?php
	}	
	
	/**
	 * Adds plugin's admin panel to the wp dashboard
	 *
	 * @private
	 * @since	2.0
	 */
	function add_admin_panel(){

		global $wpc_like_button_manage_page;

		$wpc_like_button_manage_page = add_submenu_page(
			WPC_SETTINGS_PAGE,
			__( 'Like Button', WPC_TEXT_DOMAIN ),
			__( 'Like Button', WPC_TEXT_DOMAIN ),
			'manage_options',
			WPC_SETTINGS_LIKE_BUTTON_PAGE,
			array( &$this, 'admin_section_like_button_page' )
		);
	}

	/**
	 *
	 */
	function admin_section_like_button_page(){

?>
		<div class="wrap" style="width:70%">
			<div id="icon-options-general" class="icon32"><br></div>
			<h2><?php _e('Facebook Like Button Settings', WPC_TEXT_DOMAIN ) ?></h2>
			<form method="post" action="options.php">
			<?php settings_fields( WPC_OPTIONS_LIKE_BUTTON ); ?>
				<table><tr><td>
				<?php do_settings_sections( WPC_SETTINGS_LIKE_BUTTON_PAGE ); ?>
				</td></tr></table>
				<p class="submit">
					<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
				</p>
			</form>
		</div>
<?php

	}



	/**
	 * Restores default configuration
	 */
	public static function restoreDefaults(){

		// set the settings controlled by this class to their default values
	}
}

?>