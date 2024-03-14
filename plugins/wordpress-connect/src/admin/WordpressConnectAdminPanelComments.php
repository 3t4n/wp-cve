<?php

/**
 * @author Tomas Vorobjov
 * @version 2.0
 * @date 19 Apri 2011
 *
 * @file WordpressConnectAdminPanelComments.php
 *
 * This class provides functionality for the wordpress dashboard admin
 * panel for the Wordpress Connect Comments facebook plugin
 */
class WordpressConnectAdminPanelComments {


	/**
	 * Creates a new instance of WordpressConnectAdminPanelComments
	 *
	 * @since	2.0
	 *
	 */
	function WordpressConnectAdminPanelComments(){

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

		if ( !current_user_can( 'manage_options' ) ){ return;	}		
		
		register_setting( WPC_OPTIONS_COMMENTS, WPC_OPTIONS_COMMENTS, array( &$this, 'admin_comments_settings_validate' ) );

		// adds sections
		add_settings_section( WPC_SETTINGS_SECTION_COMMENTS, __( 'Plugin Options', WPC_TEXT_DOMAIN ), array( &$this, 'admin_section_comments' ), WPC_SETTINGS_COMMENTS_PAGE );
		add_settings_section( WPC_SETTINGS_SECTION_COMMENTS_POSITION, __( 'Position Settings', WPC_TEXT_DOMAIN ), array( &$this, 'admin_section_comments_position' ), WPC_SETTINGS_COMMENTS_PAGE );
		add_settings_section( WPC_SETTINGS_SECTION_COMMENTS_ENABLED, __( 'Enable Settings', WPC_TEXT_DOMAIN ), array( &$this, 'admin_section_comments_enable' ), WPC_SETTINGS_COMMENTS_PAGE );
		add_settings_section( WPC_SETTINGS_SECTION_COMMENTS_DISPLAY, __( 'Display Settings', WPC_TEXT_DOMAIN ), array( &$this, 'admin_section_comments_display' ), WPC_SETTINGS_COMMENTS_PAGE );

		// comments settings
		add_settings_field( WPC_OPTIONS_COMMENTS_NUMBER, __( 'Number of Comments', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_comments_number' ), WPC_SETTINGS_COMMENTS_PAGE, WPC_SETTINGS_SECTION_COMMENTS );
		add_settings_field( WPC_OPTIONS_COMMENTS_WIDTH, __( 'Width', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_comments_width' ), WPC_SETTINGS_COMMENTS_PAGE, WPC_SETTINGS_SECTION_COMMENTS );

		// comments position settings
		add_settings_field( WPC_OPTIONS_COMMENTS_POSITION, __( 'Default Position', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_comments_position_default' ), WPC_SETTINGS_COMMENTS_PAGE, WPC_SETTINGS_SECTION_COMMENTS_POSITION );

		// comments enable settings
		add_settings_field( WPC_OPTIONS_COMMENTS_ENABLED, __( 'Enabled', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_comments_enable_enabled' ), WPC_SETTINGS_COMMENTS_PAGE, WPC_SETTINGS_SECTION_COMMENTS_ENABLED );

		// comments display settings
		add_settings_field( WPC_OPTIONS_DISPLAY_EVERYWHERE, __( 'Everywhere', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_comments_enable_everywhere' ), WPC_SETTINGS_COMMENTS_PAGE, WPC_SETTINGS_SECTION_COMMENTS_DISPLAY );
		add_settings_field( WPC_OPTIONS_DISPLAY_HOMEPAGE, __( 'Homepage', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_comments_enable_homepage' ), WPC_SETTINGS_COMMENTS_PAGE, WPC_SETTINGS_SECTION_COMMENTS_DISPLAY );
		add_settings_field( WPC_OPTIONS_DISPLAY_POSTS, __( 'Single Post', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_comments_enable_post' ), WPC_SETTINGS_COMMENTS_PAGE, WPC_SETTINGS_SECTION_COMMENTS_DISPLAY );
		add_settings_field( WPC_OPTIONS_DISPLAY_PAGES, __( 'Single Page', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_comments_enable_page' ), WPC_SETTINGS_COMMENTS_PAGE, WPC_SETTINGS_SECTION_COMMENTS_DISPLAY );
		add_settings_field( WPC_OPTIONS_DISPLAY_CATEGORIES, __( 'Categories', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_comments_enable_categories' ), WPC_SETTINGS_COMMENTS_PAGE, WPC_SETTINGS_SECTION_COMMENTS_DISPLAY );
		add_settings_field( WPC_OPTIONS_DISPLAY_TAGS, __( 'Tags', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_comments_enable_tags' ), WPC_SETTINGS_COMMENTS_PAGE, WPC_SETTINGS_SECTION_COMMENTS_DISPLAY );
		add_settings_field( WPC_OPTIONS_DISPLAY_SEARCH, __( 'Search', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_comments_enable_search' ), WPC_SETTINGS_COMMENTS_PAGE, WPC_SETTINGS_SECTION_COMMENTS_DISPLAY );
		add_settings_field( WPC_OPTIONS_DISPLAY_ARCHIVE, __( 'Archive', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_comments_enable_archive' ), WPC_SETTINGS_COMMENTS_PAGE, WPC_SETTINGS_SECTION_COMMENTS_DISPLAY );
		add_settings_field( WPC_OPTIONS_DISPLAY_NOWHERE, __( 'Nowhere', WPC_TEXT_DOMAIN ), array( &$this, 'admin_setting_comments_enable_nowhere' ), WPC_SETTINGS_COMMENTS_PAGE, WPC_SETTINGS_SECTION_COMMENTS_DISPLAY );

	}

	/**
	 * Validates comments settings
	 * @param	$input the settings value
	 */
	function admin_comments_settings_validate( $input ){

		$input = apply_filters( WPC_OPTIONS_COMMENTS, $input ); // filter to let sub-plugins validate their options too
		return $input;
	}

	/**
	 */
	function admin_section_comments(){}

	/**
	 */
	function admin_section_comments_position(){}

	/**
	 */
	function admin_section_comments_enable(){}

	/**
	 */
	function admin_section_comments_display(){

		$options = get_option( WPC_OPTIONS_COMMENTS );
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
	 * Renders the comments numbers field
	 */
	function admin_setting_comments_number(){

		$options = get_option( WPC_OPTIONS_COMMENTS );
?>
		<input type="text" id="<?php echo WPC_OPTIONS_COMMENTS_NUMBER; ?>" name="<?php echo WPC_OPTIONS_COMMENTS,'[',WPC_OPTIONS_COMMENTS_NUMBER,']'; ?>" value="<?php echo $options[ WPC_OPTIONS_COMMENTS_NUMBER ]; ?>" size="6" />
		<span class="description"><?php _e( "(required)", WPC_TEXT_DOMAIN ); ?></span>
<?php
	}

	/**
	 * Renders the comments width field
	 */
	function admin_setting_comments_width(){

		$options = get_option( WPC_OPTIONS_COMMENTS );
?>
		<input type="text" id="<?php echo WPC_OPTIONS_COMMENTS_WIDTH;?>" name="<?php echo WPC_OPTIONS_COMMENTS,'[',WPC_OPTIONS_COMMENTS_WIDTH,']'; ?>" value="<?php echo $options[ WPC_OPTIONS_COMMENTS_WIDTH ]; ?>" size="6" />
		<p><span class="description"><?php
			_e( 'The width of the comments box in pixels. The minimum recommended value is 400px.', WPC_TEXT_DOMAIN );
		?></span></p>
<?php
	}

	/**
	 * Renders the comments default position
	 */
	function admin_setting_comments_position_default(){

		$options = get_option( WPC_OPTIONS_COMMENTS );
		$position_value = $options[ WPC_OPTIONS_COMMENTS_POSITION ];

		$positions = array(
			WPC_CUSTOM_FIELD_VALUE_POSITION_TOP => __( 'Top', WPC_TEXT_DOMAIN ),
			WPC_CUSTOM_FIELD_VALUE_POSITION_BOTTOM => __( 'Bottom', WPC_TEXT_DOMAIN ),
			WPC_CUSTOM_FIELD_VALUE_POSITION_CUSTOM => __( 'Custom', WPC_TEXT_DOMAIN )
		);
?>
			<select id="<?php echo WPC_OPTIONS_COMMENTS_POSITION; ?>" name="<?php echo WPC_OPTIONS_COMMENTS,'[',WPC_OPTIONS_COMMENTS_POSITION,']'; ?>">
<?php
		 foreach ( $positions as $value => $position ) : ?>
		 		<!-- <?php echo '$value: ', $value, ' $position_value: ', $position_value; ?> -->
				<option <?php echo ( $value == $position_value ) ? 'selected="selected"' : ''; ?> value="<?php echo $value; ?>"><?php echo $position; ?>&nbsp;&nbsp;&nbsp;</option>
<?php 	endforeach; ?>
			</select>
			<p><span class="description"><?php
				_e( 'The default position of the comments plugin within a post/page. This value can be changed for every post/page individually.', WPC_TEXT_DOMAIN );
			?></span></p>
<?php



	}

	/**
	 * Renders the comments enable default field
	 */
	function admin_setting_comments_enable_enabled(){

		$options = get_option( WPC_OPTIONS_COMMENTS );
		$default_value = $options[ WPC_OPTIONS_COMMENTS_ENABLED ];

?>
			<select id="<?php echo WPC_OPTIONS_COMMENTS; ?>-enabled" name="<?php echo WPC_OPTIONS_COMMENTS,'[',WPC_OPTIONS_COMMENTS_ENABLED,']'; ?>">
				<option <?php echo ( $default_value == WPC_OPTION_ENABLED ) ? 'selected="selected"' : ''; ?> value="<?php echo WPC_OPTION_ENABLED; ?>"><?php _e( 'Enabled', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
				<option <?php echo ( $default_value == WPC_OPTION_DISABLED ) ? 'selected="selected"' : ''; ?> value="<?php echo WPC_OPTION_DISABLED; ?>"><?php _e( 'Disabled', WPC_TEXT_DOMAIN ); ?>&nbsp;&nbsp;&nbsp;</option>
			</select>
			<p><span class="description"><?php _e( 'Specifies whether or not Comments are enabled on posts and pages by default.', WPC_TEXT_DOMAIN ); ?></span></p>
			<p><span class="description"><?php _e( 'This is the default value that will be selected in the Wordpress Connect box in the right side of add/edit posts/pages. The value can be changed for every individual post/page.', WPC_TEXT_DOMAIN ); ?></span></p>
<?php

	}

	/**
	 * Renders the comments enable everywhere field
	 */
	function admin_setting_comments_enable_everywhere(){

		$options = get_option( WPC_OPTIONS_COMMENTS );
		$checked = ( !empty( $options[ WPC_OPTIONS_DISPLAY_EVERYWHERE ] ) ) ? ' checked="checked"' : '';
?>
		<input type="checkbox" id="<?php echo WPC_OPTIONS_DISPLAY_EVERYWHERE; ?>" name="<?php echo WPC_OPTIONS_COMMENTS,'[',WPC_OPTIONS_DISPLAY_EVERYWHERE,']'; ?>"<?php echo $checked; ?> />
		<span class="description"><?php _e( "Display comments box everywhere (by default).", WPC_TEXT_DOMAIN ); ?></span>
<?php
	}

	/**
	 * Renders the comments enable on homepage field
	 */
	function admin_setting_comments_enable_homepage(){

		$description = __( 'Display comments box(es) on the homepage.', WPC_TEXT_DOMAIN );
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
	 * Renders the comments enable on single post field
	 */
	function admin_setting_comments_enable_post(){
		
		$description = __( 'Display comments box(es) on a single post.', WPC_TEXT_DOMAIN );
		
		$this->aux_print_enabled_position(
			WPC_OPTIONS_DISPLAY_POSTS,
			$description
		);		
		
	}
	
	/**
	 * Renders the comments enable on single page field
	 */
	function admin_setting_comments_enable_page(){
		
		$description = __( 'Display comments box(es) on a single page (that is not the homepage).', WPC_TEXT_DOMAIN );
		
		$this->aux_print_enabled_position(
			WPC_OPTIONS_DISPLAY_PAGES,
			$description
		);			
	}
		
	
	/**
	 * Renders the comments enable on categories field
	 */
	function admin_setting_comments_enable_categories(){

		$description = __( 'Display comments box(es) on the category archive pages.', WPC_TEXT_DOMAIN );
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
	 * Renders the comments enable on tags field
	 */
	function admin_setting_comments_enable_tags(){

		$description = __( 'Display comments box(es) on the tags archive pages.', WPC_TEXT_DOMAIN );
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
	 * Renders the comments enable on search field
	 */
	function admin_setting_comments_enable_search(){

		$description = __( 'Display comments box(es) on the search result page.', WPC_TEXT_DOMAIN );
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
	 * Renders the comments enable on archive field
	 */
	function admin_setting_comments_enable_archive(){

		$description = __( 'Display comments box(es) on the archive pages.', WPC_TEXT_DOMAIN );
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
	 * Renders the comments enable on single page field
	 */
	function admin_setting_comments_enable_nowhere(){

		$description = __( 'Disables comments box everywhere (by default).', WPC_TEXT_DOMAIN );  

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
		
		$options = get_option( WPC_OPTIONS_COMMENTS );
		$checked = ( !empty( $options[ $option ] ) ) ? 'checked="checked" ' : '';
?>
		<input type="checkbox" id="<?php echo $option; ?>" name="<?php echo WPC_OPTIONS_COMMENTS,'[',$option,']'; ?>"<?php echo $checked; ?> />
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

		global $wpc_comments_manage_page;

		$wpc_comments_manage_page = add_submenu_page(
			WPC_SETTINGS_PAGE,
			__( 'Comments', WPC_TEXT_DOMAIN ),
			__( 'Comments', WPC_TEXT_DOMAIN ),
			'manage_options',
			WPC_SETTINGS_COMMENTS_PAGE,
			array( &$this, 'admin_section_comments_page' )
		);
	}

	/**
	 *
	 */
	function admin_section_comments_page(){

?>
		<div class="wrap" style="width:70%">
			<div id="icon-options-general" class="icon32"><br></div>
			<h2><?php _e('Facebook Comments Settings', WPC_TEXT_DOMAIN ) ?></h2>
			<form method="post" action="options.php">
			<?php settings_fields( WPC_OPTIONS_COMMENTS ); ?>
				<table><tr><td>
				<?php do_settings_sections( WPC_SETTINGS_COMMENTS_PAGE ); ?>
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