<?php
/*
Plugin Name: Post Editor Buttons Fork
Plugin URI: http://trepmal.com/plugins/post-editor-buttons-fork/
Description: This plugin allows you to add buttons to the HTML post editor.
Version: 2.4
Author: Kailey Lampert
Author URI: http://kaileylampert.com
Original: Oren Yomtov

Copyright (C) 2011  Kailey Lampert

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

	load_plugin_textdomain( 'post-editor-buttons-fork', false, dirname( plugin_basename( __FILE__ ) ) .  '/lang' );

	add_action('init', 'peb_init');

	/**
	 * Get hooked in
	 *
	 * @return void
	 */
	function peb_init() {
		add_filter( 'plugin_action_links',   'peb_actions',        10, 2 );

		add_filter( 'admin_init',            'peb_reg_settings' );
		add_action( 'admin_menu',            'peb_config_page' );

		add_action( 'admin_enqueue_scripts', 'peb_admin_enqueue_scripts' );
	}

	/**
	 * Add a 'Configure' button to the links row on plugins.php
	 *
	 * Links to the PEB's configuration page
	 *
	 * @return array $links
	 */
	function peb_actions( $links, $file ) {
		$this_plugin = plugin_basename(__FILE__);
		if ( $file == $this_plugin ) {
			$settings_link = '<a href="'. esc_url( admin_url( 'options-general.php?page=peb' ) ) .'">'. __( 'Configure', 'post-editor-buttons-fork' ) .'</a>';
			array_unshift( $links, $settings_link );
		}
		return $links;
	}

	/**
	 * Register options
	 *
	 * @return void
	 */
	function peb_reg_settings() {

		register_setting( 'peb',  'peb_caption', 'sanitize_title_array' );
		register_setting( 'peb',  'peb_before',  'wp_filter_post_kses_array' );
		register_setting( 'peb',  'peb_after',   'wp_filter_post_kses_array' );

		register_setting( 'peb', 'peb_remove' );
	}

	/**
	 * Apply esc_attr to an array
	 *
	 * @param array $input
	 * @return array
	 */
	function esc_attr_array( $input ) {
		return array_map( 'esc_attr', $input );
	}

	/**
	 * Apply sanitize_title to an array
	 *
	 * @param array $input
	 * @return array
	 */
	function sanitize_title_array( $input ) {
		return array_map( 'sanitize_title', $input );
	}

	/**
	 * Apply wp_filter_post_kses to an array
	 * Apply peb_quote_replace to an array
	 *
	 * @param array $input
	 * @return array
	 */
	function wp_filter_post_kses_array( $input ) {
		$input = array_map( 'wp_filter_post_kses', $input );
		$input = array_map( 'peb_quote_replace',   $input );
		$input = array_map( 'stripslashes',        $input );
		return $input;
	}

	/**
	 * Replace " with ' in array
	 *
	 * @param array $input
	 * @return string
	 */
	function peb_quote_replace( $input ) {
		// return str_replace( '"', '\"', $input );
		return str_replace( "'", "\'", $input );
	}

	/**
	 * Wrap each item in array in single quotes
	 *
	 * @param array $input
	 * @return string
	 */
	function peb_quote_wrap( $input ) {
		return "'$input'";
	}

	/**
	 * Create menu items
	 *
	 * Create PEB configuration menu item
	 * Create PEB JS menu item
	 * @return void
	 */
	function peb_config_page() {
		$peb_admin_page = add_options_page(
			__( 'PEB Options', 'post-editor-buttons-fork' ),
			__( 'Post Editor Buttons', 'post-editor-buttons-fork' ),
			'administrator',
			'peb',
			'peb_config'
		);
		add_action("load-$peb_admin_page", 'peb_help_tab');
	}

	/**
	 * Create Help Tabs
	 *
	 * @return void
	 */
	function peb_help_tab() {

		$screen = get_current_screen();

		$screen->add_help_tab( array(
			'id'      => 'allowed_post_tags',
			'title'   => __('Allowed Tags/Attributes', 'post-editor-buttons-fork' ),
			'content' => peb_list_allowed_tags(),
		) );

		$screen->add_help_tab( array(
			'id'      => 'adding_allowed_post_tags',
			'title'   => __('Allow More Tags', 'post-editor-buttons-fork' ),
			'content' => peb_more_allowed_tags(),
		) );

	}

	/**
	 * Show all allowed tags and attributes
	 *
	 * @return string HTML
	 */
	function peb_list_allowed_tags() {
		global $allowedposttags;
		$help = '<h3>' . __( 'Allowed tags and attributes', 'post-editor-buttons-fork' ). '</h3>';
		$help .= '<ul style="width:33%;float:left;">';
		$br = ceil( count( $allowedposttags ) / 3 );
		$i = 1;
		foreach ( $allowedposttags as $tag => $atts ) {
			$atts = implode( ', ', array_keys( $atts ) );
			$help .= "<li><strong>{$tag}:</strong> {$atts}</li>";
			if ( $br == $i ) {
				$help .= '</ul><ul style="width:33%;float:left;">';
				$i = 0;
			}
			++$i;
		}
		$help .= '</ul><div style="clear:both;"></div>';
		return $help;
	}

	/**
	 * Show how to expand allowed tags/attributes
	 *
	 * @return string HTML
	 */
	function peb_more_allowed_tags() {
		$help = '<p>' . __( "To enable additional tags, you'll need to add some code:", 'post-editor-buttons-fork' ) . '</p>';

$help .= '<pre>add_filter( "admin_init", "allowed_tags" );
function allowed_tags() {
	global $allowedposttags;
	$allowedposttags["video"] = array();
}</pre>';

$help .= __( 'To allow additional attributes, use the following code:', 'post-editor-buttons-fork' );

$help .= '<pre>add_filter( "admin_init", "allowed_tags" );
function allowed_tags() {
	global $allowedposttags;
	$allowedposttags["video"]["src"] = array();
	$allowedposttags["video"]["type"] = array();
	$allowedposttags["video"]["poster"] = array();
}</code></pre>';

		return $help;
	}


	/**
	 * Write the JS for adding/removing buttons
	 *
	 * @return void
	 */
	function peb_admin_enqueue_scripts( $hook ) {

		if ( 'post.php' != $hook && 'post-new.php' != $hook ) {
			return;
		}

		$caption = get_option( 'peb_caption', array() );
		$before  = get_option( 'peb_before',  array() );
		$after   = get_option( 'peb_after',   array() );

		$script1 = '';
		foreach ( $caption as $id => $val ) {
			if ( empty( $caption[ $id ] ) ) {
				continue;
			}
			$a = array( $caption[ $id ], $before[ $id ], $after[ $id ] );
			$a = array_filter( $a ); //remove empties
			$a = array_map( 'peb_quote_wrap', $a );
			$v = implode( ', ', $a );
			$script1 .= "QTags.addButton('peb_$id', $v);\n";
		}
		$script1 .= "var peb_remove_buttons = ". json_encode( get_option( 'peb_remove', array() ) ) .";";

		$script2 =
'edButtons = _.pick( edButtons, function( value, key, object ) {
	return ( _.indexOf( peb_remove_buttons, value.id ) == -1 );
});';

		wp_add_inline_script( 'quicktags',  $script1 );
		wp_add_inline_script( 'underscore', $script2 );
	}

	/**
	 * The configuration page
	 *
	 * @return void
	 */
	function peb_config() {
		$delete_text = __( 'Delete', 'post-editor-buttons-fork' );
		wp_enqueue_script( 'manage_quicktag_rows', plugins_url( 'mng_rows.js', __FILE__ ) );
		wp_localize_script( 'manage_quicktag_rows', 'PEB', array(
			'deleteText' => $delete_text,
		) );
		?>
		<div class="wrap">
		<h2><?php _e( 'Post Editor Buttons', 'post-editor-buttons-fork' ); ?></h2>

		<form method="post" action="options.php">
			<h3><?php _e( 'Add new buttons', 'post-editor-buttons-fork' ); ?></h3>
			<p class="description"><?php _e( 'Remember, these buttons will only be added to the TEXT view of the editor.', 'post-editor-buttons-fork' ); ?></p>

			<table class="form-table" id="op_table"><thead>
			<tr valign="top">
				<th scope="row"><?php _e( 'Caption', 'post-editor-buttons-fork' ); ?></th>
				<th scope="row"><?php _e( 'Before', 'post-editor-buttons-fork' ); ?></th>
				<th scope="row"><?php _e( 'After', 'post-editor-buttons-fork' ); ?></th>
				<th scope="row"><?php _e( 'Delete', 'post-editor-buttons-fork' ); ?></th>
			</tr>
			</thead>
			<tbody>
			<tr valign="top">
				<td><input type="text" readonly value="H3" /></td>
				<td><input type="text" readonly value="<?php echo htmlentities( '<h3>' ); ?>" /></td>
				<td><input type="text" readonly value="<?php echo htmlentities( '</h3>' ); ?>" /></td>
				<td><?php _e( '&larr; Example', 'post-editor-buttons-fork' ); ?></td>
			</tr>
			<?php
				$caption = get_option('peb_caption', array() );
				$before  = get_option('peb_before',  array() );
				$after   = get_option('peb_after',   array() );

				for ( $i = 0; $i < count( $caption ); $i++ ) { ?>
					<tr valign="top" id="row<?php echo $i; ?>">
						<td><input type="text" name="peb_caption[]" value="<?php echo esc_attr( stripslashes( $caption[ $i ] ) ); ?>" /></td>
						<td><input type="text" name="peb_before[]"  value="<?php echo esc_attr( stripslashes( $before[ $i ] ) ); ?>"  /></td>
						<td><input type="text" name="peb_after[]"   value="<?php echo esc_attr( stripslashes( $after[ $i ] ) ); ?>"   /></td>
						<td><a  href="#" onclick="return peb_deleteRow('<?php echo $i; ?>');"><?php echo $delete_text; ?></a></td>
					</tr>
				<?php }
			?>
			</tbody>
			</table>

			<p><button class="button button-small" onclick="return peb_addMore();"><?php echo _e( 'New row', 'post-editor-buttons-fork' ) ?></button></p>

			<h3><?php _e( 'Remove core buttons', 'post-editor-buttons-fork' ); ?></h3>
			<?php
				$core_buttons = array( 'strong', 'em', 'block', 'del', 'ins', 'ul', 'ol', 'li', 'code', 'more', 'link', 'img', 'spell', 'close' );
				echo '<input type="hidden" name="peb_remove[]" value="" />';
				echo '<ul>';
				foreach( $core_buttons as $btn ) {
					$c = checked( in_array( $btn, get_option( 'peb_remove', array() ) ), true, false );
					echo "<li><label><input type='checkbox' name='peb_remove[]' value='$btn'$c /> $btn</label></li>";
				}
				echo '</ul>';
			?>
			<p class="submit"><?php
				settings_fields( 'peb' );
				submit_button( __('Save Changes', 'post-editor-buttons-fork'), 'primary', 'submit', false );
				echo ' <span class="description">';
				_e( 'Be sure to hit save after deleting any rows.', 'post-editor-buttons-fork' );
				echo '</span>';
			?></p>
		</form>

		</div>
		<?php
	}
