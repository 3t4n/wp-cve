<?php
/**
 * Functions for adding custom actions and bulk edit options in post list table.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.8
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class customizes the post list screen.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      2.0.8
 */
class Nelio_Content_Post_List_Page {

	public function init() {

		add_action( 'admin_enqueue_scripts', array( $this, 'maybe_enqueue_assets' ) );

		add_filter( 'manage_pages_columns', array( $this, 'add_page_column_for_auto_share' ), 10, 2 );
		add_action( 'manage_pages_custom_column', array( $this, 'add_value_in_column_for_auto_share' ), 10, 2 );

		add_filter( 'manage_posts_columns', array( $this, 'add_post_column_for_auto_share' ), 10, 2 );
		add_action( 'manage_posts_custom_column', array( $this, 'add_value_in_column_for_auto_share' ), 10, 2 );

		add_filter( 'post_class', array( $this, 'add_class_with_auto_share_info' ), 10, 3 );
		add_action( 'bulk_edit_custom_box', array( $this, 'maybe_add_quick_or_bulk_edit_for_auto_share' ), 10, 2 );
		add_action( 'quick_edit_custom_box', array( $this, 'maybe_add_quick_or_bulk_edit_for_auto_share' ), 10, 2 );
		add_action( 'save_post', array( $this, 'update_auto_share_on_quick_or_bulk_edit' ), 10, 2 );

		add_filter( 'post_row_actions', array( $this, 'customize_row_actions' ), 10, 2 );
		add_filter( 'page_row_actions', array( $this, 'customize_row_actions' ), 10, 2 );

	}//end init()

	public function maybe_enqueue_assets() {

		if ( ! $this->is_current_screen_this_page() ) {
			return;
		}//end if

		$custom_css  = '';
		$custom_css .= '.column-nc_auto_share { width: 10% !important; }';
		$custom_css .= '.nc-auto-share { color:grey; }';
		$custom_css .= '.nc-auto-share--is-enabled { color:green; font-weight:bold; }';
		$custom_css .= 'input[name="nc_auto_share"] + label + div.nc-auto-share-end { display: none; }';
		$custom_css .= 'input[name="nc_auto_share"]:checked + label + div.nc-auto-share-end { display: block; }';
		$custom_css .= 'label[for=nc_auto_share] { display: inline !important; }';
		wp_add_inline_style( 'list-tables', $custom_css );

		wp_add_inline_script(
			'inline-edit-post',
			'jQuery && jQuery(document).ready( function($) {' .
			'  ied = inlineEditPost.edit;' .
			'  inlineEditPost.edit = function(pid) { ' .
			'    ied.apply( this, arguments );' .
			'    if ( "object" === typeof pid ) pid = this.getId( pid );' .
			'    $field = $("input[name=nc_auto_share]");' .
			'    checked = $("#post-"+pid).hasClass("nc-is-auto-shared");' .
			'    $field.prop( "checked", checked );' .
			'    ' .
			'    post = document.getElementById("post-"+pid);' .
			'    re = /^.*(nc-auto-share-end--is-([^ ]+)).*$/;' .
			'    val = re.test( post.className ) ? post.className.replace( re, "$2" ) : "default";' .
			'    select = document.querySelector("div.nc-auto-share-end select");' .
			'    select.value = val;' .
			'  };' .
			'} );'
		);

		wp_enqueue_style(
			'nelio-content-post-list-page',
			nelio_content()->plugin_url . '/assets/dist/css/post-list-page.css',
			array( 'nelio-content-components' ),
			nc_get_script_version( 'post-list-page' )
		);
		nc_enqueue_script_with_auto_deps( 'nelio-content-post-list-page', 'post-list-page', true );

	}//end maybe_enqueue_assets()

	public function add_page_column_for_auto_share( $columns ) {
		return $this->add_post_column_for_auto_share( $columns, 'page' );
	}//end add_page_column_for_auto_share()

	public function add_post_column_for_auto_share( $columns, $post_type ) {
		$settings   = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types', array() );

		if ( ! in_array( $post_type, $post_types, true ) ) {
			return $columns;
		}//end if

		$columns['nc_auto_share'] = _x( 'Auto Share', 'text', 'nelio-content' );
		return $columns;
	}//end add_post_column_for_auto_share()

	public function add_value_in_column_for_auto_share( $column, $post_id ) {

		if ( 'nc_auto_share' !== $column ) {
			return;
		}//end if

		$aux = Nelio_Content_Post_Helper::instance();
		if ( ! $aux->is_auto_share_enabled( $post_id ) ) {
			printf(
				'<span class="%1$s">%2$s</span>',
				esc_attr( 'nc-auto-share nc-auto-share--is-disabled' ),
				esc_html_x( 'Disabled', 'text (auto share)', 'nelio-content' )
			);
			return;
		}//end if

		$end_date = $aux->get_auto_share_end_date( $post_id );
		$cur_date = gmdate( 'Y-m-d' );

		if ( 'never' === $end_date ) {
			printf(
				/* translators: classname */
				_x( '<span class="%s">Enabled</span><br>forever', 'text (auto share)', 'nelio-content' ), // phpcs:ignore
				esc_attr( 'nc-auto-share nc-auto-share--is-enabled' )
			);
		} elseif ( 'unknown' === $end_date ) {
			printf(
				'<span class="%1$s">%2$s</span>',
				esc_attr( 'nc-auto-share nc-auto-share--is-enabled' ),
				esc_html_x( 'Enabled', 'text (auto share)', 'nelio-content' )
			);
		} elseif ( $cur_date <= $end_date ) {
			printf(
				/* translators: 1 -> classname, 2 -> date */
				_x( '<span class="%1$s">Enabled</span><br>until %2$s', 'text (auto share)', 'nelio-content' ), // phpcs:ignore
				esc_attr( 'nc-auto-share nc-auto-share--is-enabled' ),
				esc_html( $end_date )
			);
		} else {
			printf(
				'<span class="%1$s">%2$s</span>',
				esc_attr( 'nc-auto-share nc-auto-share--is-finished' ),
				sprintf(
					/* translators: date */
					_x( 'Finished<br>on %s', 'text (auto share)', 'nelio-content' ), // phpcs:ignore
					esc_html( $end_date )
				)
			);
		}//end if
	}//end add_value_in_column_for_auto_share()

	public function add_class_with_auto_share_info( $classes, $class, $post_id ) {

		if ( ! is_admin() ) {
			return $classes;
		}//end if

		$aux = Nelio_Content_Post_Helper::instance();
		if ( $aux->is_auto_share_enabled( $post_id ) ) {
			$end_mode = $aux->get_auto_share_end_mode( $post_id );
			array_push( $classes, 'nc-is-auto-shared', "nc-auto-share-end--is-{$end_mode}" );
		}//end if

		return $classes;

	}//end add_class_with_auto_share_info()

	public function maybe_add_quick_or_bulk_edit_for_auto_share( $column, $post_type ) {

		if ( 'nc_auto_share' !== $column ) {
			return;
		}//end if

		$settings   = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types', array() );
		if ( ! in_array( $post_type, $post_types, true ) ) {
			return;
		}//end if

		echo '<fieldset class="inline-edit-col-left clear">';
		echo '<div class="inline-edit-group wp-clearfix">';
		wp_nonce_field( 'nelio_content_quick_edit_post', 'nelio-content-quick-edit-post-nonce' );
		printf(
			'<div><input type="checkbox" name="nc_auto_share" %s /> <label for="nc_auto_share">%s</label>%s</div>',
			checked( 'include-in-auto-share', $settings->get( 'auto_share_default_mode' ), false ),
			esc_html_x( 'Auto share on social media with Nelio Content', 'command', 'nelio-content' ),
			$this->get_auto_share_end_select() // phpcs:ignore
		);
		echo '</div></fieldset>';

	}//end maybe_add_quick_or_bulk_edit_for_auto_share()

	public function update_auto_share_on_quick_or_bulk_edit( $post_id, $post ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}//end if

		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}//end if

		$settings   = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types', array() );
		if ( ! in_array( $post->post_type, $post_types, true ) ) {
			return;
		}//end if

		if ( ! isset( $_REQUEST['nelio-content-quick-edit-post-nonce'] ) ) { // phpcs:ignore
			return;
		}//end if

		$nonce = sanitize_text_field( wp_unslash( $_REQUEST['nelio-content-quick-edit-post-nonce'] ) ); // phpcs:ignore
		if ( ! wp_verify_nonce( $nonce, 'nelio_content_quick_edit_post' ) ) {
			return;
		}//end if

		$auto_share = false;
		if ( isset( $_REQUEST['nc_auto_share'] ) ) { // phpcs:ignore
			$auto_share = 'on' === sanitize_text_field( $_REQUEST['nc_auto_share'] ); // phpcs:ignore
		}//end if

		$end_mode = 'default';
		if ( isset( $_REQUEST['nc_auto_share_end_mode'] ) ) { // phpcs:ignore
			$end_mode = sanitize_text_field( $_REQUEST['nc_auto_share_end_mode'] ); // phpcs:ignore
		}//end if

		$aux = Nelio_Content_Post_Helper::instance();
		$aux->enable_auto_share( $post_id, $auto_share );
		$aux->update_auto_share_end_mode( $post_id, $end_mode );

	}//end update_auto_share_on_quick_or_bulk_edit()

	public function customize_row_actions( $actions, $post ) {
		$settings   = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types', array() );

		if ( ! in_array( $post->post_type, $post_types, true ) ) {
			return $actions;
		}//end if

		if ( ! current_user_can( 'edit_post', $post->ID ) ) {
			return $actions;
		}//end if

		$label      = _x( 'Share on social media', 'command', 'nelio-content' );
		$attr_label = esc_attr( $label );
		$html_label = esc_html( $label );

		if ( ! nc_is_subscribed() ) {
			$actions['nc-share'] = $html_label;
			return $actions;
		}//end if

		$actions['nc-share'] = <<<EOF
			<span
				class="nelio-content-share-post"
				data-post-id="{$post->ID}"
				title="{$attr_label}"
			>
				{$html_label}
			</span>
EOF;
		return $actions;
	}//end customize_row_actions()

	private function get_auto_share_end_select() {
		$options = nc_get_auto_share_end_modes();

		$res = '<div class="nc-auto-share-end"><select name="nc_auto_share_end_mode">';
		foreach ( $options as $option ) {
			$res .= sprintf(
				'<option value="%s">%s</option>',
				esc_attr( $option['value'] ),
				esc_html( $option['label'] )
			);
		}//end foreach
		$res .= '</select></div>';
		return $res;
	}//end get_auto_share_end_select()

	private function is_current_screen_this_page() {

		$screen = get_current_screen();
		if ( ! isset( $screen->id ) ) {
			return false;
		}//end if

		$screen = $screen->id;

		if ( strpos( $screen, 'edit-' ) !== 0 ) {
			return false;
		}//end if

		$settings   = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types', array() );

		$screen = preg_replace( '/^edit-/', '', $screen );
		if ( ! in_array( $screen, $post_types, true ) ) {
			return false;
		}//end if

		return true;

	}//end is_current_screen_this_page()

}//end class
