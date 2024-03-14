<?php
/**
 * WPFactory Helper - Admin Site Key Manager
 *
 * @version 1.5.9
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_WPCodeFactory_Helper_Site_Key_Manager' ) ) :

class Alg_WPCodeFactory_Helper_Site_Key_Manager {

	/**
	 * Constructor.
	 *
	 * @version 1.2.0
	 * @since   1.0.0
	 */
	function __construct() {
		add_action( 'admin_menu',    array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init',    array( $this, 'set_item_site_key' ) );
		add_action( 'admin_init',    array( $this, 'update_item_list' ) );
		add_action( 'admin_init',    array( $this, 'check_item_site_key' ) );
		add_action( 'admin_notices', array( $this, 'admin_notice_site_key_status' ) );
	}

	/**
	 * check_item_site_key.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function check_item_site_key() {
		if ( isset( $_GET['alg_check_item_site_key'] ) ) {
			alg_wpcfh_check_site_key( $_GET['alg_check_item_site_key'] );
			wp_safe_redirect( remove_query_arg( 'alg_check_item_site_key' ) );
			exit;
		}
	}

	/**
	 * update_item_list.
	 *
	 * @version       1.2.0
	 * @since         1.2.0
	 *
	 * @todo    (dev) add "Item list successfully updated" message
	 */
	function update_item_list() {
		if ( isset( $_GET['alg_update_item_list'] ) ) {
			do_action( 'alg_get_plugins_list' );
			do_action( 'alg_get_themes_list' );
			wp_safe_redirect( remove_query_arg( 'alg_update_item_list' ) );
			exit;
		}
	}

	/**
	 * set_item_site_key.
	 *
	 * @version 1.0.0
	 * @since   1.0.0
	 */
	function set_item_site_key() {
		if ( isset( $_POST['alg_set_site_key'] ) ) {
			$item_slug            = $_POST['alg_item_slug'];
			$site_key             = $_POST['alg_site_key'];
			$keys                 = get_option( 'alg_site_keys', array() );
			$keys[ $item_slug ]   = $site_key;
			update_option( 'alg_site_keys', $keys );
			alg_wpcfh_check_site_key( $item_slug );
			alg_wpcodefactory_helper()->plugins_updater->update_checkers[ $item_slug ]->checkForUpdates();
		}
	}

	/**
	 * admin_notice_site_key_status.
	 *
	 * @version 1.5.3
	 * @since   1.0.0
	 */
	function admin_notice_site_key_status() {
		if ( isset( $_GET['page'] ) && 'wpcodefactory-helper' === $_GET['page'] && isset( $_GET['item_slug'] ) ) {
			$item_slug = sanitize_text_field( $_GET['item_slug'] );
			$site_key_status = alg_wpcfh_get_site_key_status( $item_slug );
			if ( false !== $site_key_status ) {
				$class   = ( alg_wpcfh_is_site_key_valid( $item_slug ) ? 'notice notice-success is-dismissible' : 'notice notice-error' );
				$message = alg_wpcfh_get_site_key_status_message( $item_slug );
				printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
			}
		}
	}

	/**
	 * add_admin_menu.
	 *
	 * @version 1.3.0
	 * @since   1.0.0
	 */
	function add_admin_menu() {
		add_options_page(
			__( 'WPFactory Helper', 'wpcodefactory-helper' ),
			__( 'WPFactory', 'wpcodefactory-helper' ),
			'manage_options',
			'wpcodefactory-helper',
			array( $this, 'output_admin_menu' )
		);
	}

	/**
	 * get_table_html.
	 *
	 * @version 1.5.8
	 * @since   1.0.0
	 */
	function get_table_html( $data, $args = array() ) {
		$args = array_merge( array(
			'table_class'        => '',
			'table_style'        => '',
			'row_styles'         => '',
			'table_heading_type' => 'horizontal',
			'columns_classes'    => array(),
			'columns_styles'     => array(),
		), $args );
		$table_class = ( '' == $args['table_class'] ) ? '' : ' class="' . $args['table_class'] . '"';
		$table_style = ( '' == $args['table_style'] ) ? '' : ' style="' . $args['table_style'] . '"';
		$row_styles  = ( '' == $args['row_styles'] )  ? '' : ' style="' . $args['row_styles']  . '"';

		$html = '';
		$html .= '<table style="margin-left:-1px"' . $table_class . $table_style . '>';
		end( $data );
		$lasy_key = key( $data );
		foreach( $data as $row_nr => $row ) {
			$html .= 0 === $row_nr ? '<thead>' : ( 1 === $row_nr ? '<tbody>' : '' );
			$html .= '<tr' . $row_styles . '>';
			foreach( $row as $column_nr => $value ) {
				$th_or_td = ( ( 0 === $row_nr && 'horizontal' === $args['table_heading_type'] ) || ( 0 === $column_nr && 'vertical' === $args['table_heading_type'] ) ) ? 'th' : 'td';
				$column_class = ( ! empty( $args['columns_classes'][ $column_nr ] ) ) ? ' class="' . $args['columns_classes'][ $column_nr ] . '"' : '';
				$column_style = ( ! empty( $args['columns_styles'][ $column_nr ] ) )  ? ' style="' . $args['columns_styles'][ $column_nr ]  . '"' : '';
				$html .= '<' . $th_or_td . $column_class . $column_style . '>' . $value . '</' . $th_or_td . '>';
			}
			$html .= '</tr>';
			$html .= 0 === $row_nr ? '</thead>' : ( $lasy_key === $row_nr ? '</tbody>' : '' );
		}
		$html .= '</table>';
		return $html;
	}

	/**
	 * get_site_item_key_column.
	 *
	 * @version 1.2.0
	 * @since   1.2.0
	 */
	function get_site_item_key_column( $item_site_key, $item_slug ) {
		return ( '' === $item_site_key ? '' :
			'<span title="' . strip_tags( alg_wpcfh_get_site_key_status_message( $item_slug ) ) . '" style="color:' .
				( alg_wpcfh_is_site_key_valid( $item_slug ) ? 'green' : 'red' ) . ';">' . $item_site_key . '</span>'
		);
	}

	/**
	 * get_site_url_html.
	 *
	 * @version 1.5.8
	 * @since   1.5.8
	 *
	 * @return false|string
	 */
	function get_site_url_html() {
		ob_start();
		?>
        <table class="form-table" role="presentation">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="wpfh_api_access_method"><?php _e( 'Site URL', 'wpcodefactory-helper' ); ?></label>
                </th>
                <td>
                    <code> <?php echo esc_url( alg_wpcodefactory_helper()->site_url ) ?> </code>
                </td>
            </tr>
            </tbody>
        </table>
		<?php
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
	}

	/**
	 * get_key_setting_input_html.
	 *
	 * @version 1.5.8
	 * @since   1.5.8
	 *
	 * @param $all_plugins
	 * @param $all_themes
	 *
	 * @return false|string
	 */
	function get_key_setting_input_html( $all_plugins, $all_themes ) {
		ob_start();
		if ( isset( $_GET['item_slug'] ) ) {
			$item_slug       = sanitize_text_field( $_GET['item_slug'] );
			$key             = alg_wpcfh_get_site_key( $item_slug );
			$item_type_label = 'plugin' === $_GET['item_type'] ? __( 'Plugin', 'wpcodefactory-helper' ) : __( 'Theme', 'wpcodefactory-helper' );
			if ( isset( $_GET['item_type'] ) && 'theme' == $_GET['item_type'] ) {
				$item_label = ( '' != $all_themes[ $item_slug ]->get( 'Name' ) ? $all_themes[ $item_slug ]->get( 'Name' ) : esc_html( $item_slug ) );
			} else {
				$plugin_file = $item_slug . '/' . $item_slug . '.php';
				$item_label  = ( isset( $all_plugins[ $plugin_file ]['Name'] ) ? $all_plugins[ $plugin_file ]['Name'] : esc_html( $item_slug ) );
			}
			?>
            <table class="form-table" role="presentation">
                <tbody>
                <tr>
                    <th scope="row">
                        <label for="wpfh_api_access_method">
							<?php echo sprintf( __( '%s key', 'wpcodefactory-helper' ), $item_type_label ); ?>
                        </label>
                    </th>
                    <td>
                        <form method="post">
                            <input style="min-width:300px;" type="text" name="alg_site_key"
                                   value="<?php echo esc_attr( $key ); ?>">
                            <input type="hidden" name="alg_item_slug" value="<?php echo esc_attr( $item_slug ) ?>">
                            <input class="button-primary" type="submit" name="alg_set_site_key"
                                   value="<?php echo __( 'Set key', 'wpcodefactory-helper' ) ?>">
                            <p class="description">
								<?php echo sprintf(
									__( 'Key for %s %s' ),
									'<strong>' . esc_html( $item_label ) . '</strong>',
									esc_html( strtolower( $item_type_label ) )
								); ?>
                            </p>
                        </form>
                    </td>
                </tr>
                </tbody>
            </table>
			<?php
		}
		$html = ob_get_contents();
		ob_end_clean();

		return $html;
	}

	/**
	 * output_admin_menu.
	 *
	 * @version       1.5.8
	 * @since         1.0.0
	 *
	 * @todo    (dev) restyle
	 */
	function output_admin_menu() {
		$all_plugins = get_plugins();
		$all_themes  = wp_get_themes();
		$html = '';
		$html .= '<div class="wrap">';
		$html .= '<h2>' . __( 'WPFactory Helper', 'wpcodefactory-helper' ) . '</h2>';
		$html .= $this->get_site_url_html();
		$html .= apply_filters('wpfactory_helper_plugins_table_html_before','');
		$html .= $this->get_key_setting_input_html( $all_plugins, $all_themes );
		$html .= '<h2 style="margin-bottom:23px;">' . __( 'WPFactory items', 'wpcodefactory-helper' ) . '</h2>';
		$table_data = array();
		foreach ( alg_wpcodefactory_helper()->plugins_updater->plugins_to_update as $plugin_slug ) {
			$plugin_file   = $plugin_slug . '/' . $plugin_slug . '.php';
			$item_site_key = alg_wpcfh_get_site_key( $plugin_slug );
			$table_data[]  = array(
				__( 'Plugin', 'wpcodefactory-helper' ),
				( isset( $all_plugins[ $plugin_file ]['Name'] ) ? $all_plugins[ $plugin_file ]['Name'] : $plugin_slug ),
				$this->get_site_item_key_column( $item_site_key, $plugin_slug ),
				'<a class="button button-secondary" href="' . add_query_arg( array( 'item_slug' => $plugin_slug, 'item_type' => 'plugin' ) ) . '">' .
					__( 'Set key', 'wpcodefactory-helper' ) . '</a>' .
						( '' != $item_site_key ? ' | ' . '<a href="' . add_query_arg( array( 'alg_check_item_site_key' => $plugin_slug ) ) . '">' .
							__( 'Check key', 'wpcodefactory-helper' ) . '</a>' : '' ),
			);
		}
		foreach ( alg_wpcodefactory_helper()->plugins_updater->themes_to_update as $theme_slug ) {
			$item_site_key = alg_wpcfh_get_site_key( $theme_slug );
			$table_data[]  = array(
				__( 'Theme', 'wpcodefactory-helper' ),
				( '' != $all_themes[ $theme_slug ]->get( 'Name' ) ? $all_themes[ $theme_slug ]->get( 'Name' ) : $theme_slug ),
				$this->get_site_item_key_column( $item_site_key, $theme_slug ),
				'<a href="' . add_query_arg( array( 'item_slug' => $theme_slug, 'item_type' => 'theme' ) ) . '">' .
					__( 'Set key', 'wpcodefactory-helper' ) . '</a>' .
						( '' != $item_site_key ? ' | ' . '<a href="' . add_query_arg( array( 'alg_check_item_site_key' => $theme_slug ) ) . '">' .
							__( 'Check key', 'wpcodefactory-helper' ) . '</a>' : '' ),
			);
		}
		if ( ! empty( $table_data ) ) {
			$table_data = array_merge(
				array( array(
					__( 'Type', 'wpcodefactory-helper' ),
					__( 'Item', 'wpcodefactory-helper' ),
					__( 'Key', 'wpcodefactory-helper' ),
					__( 'Actions', 'wpcodefactory-helper' ) ) ),
				$table_data
			);
			$html .= '<div class="wrap">' . $this->get_table_html( $table_data, array( 'table_class' => 'widefat striped' ) ) . '</div>';
		} else {
			$html .= '<p style="font-style:italic;">' . sprintf( __( 'You have no items from %s installed.', 'wpcodefactory-helper' ),
				'<a target="_blank" href="' . alg_wpcodefactory_helper()->update_server . '">' . alg_wpcodefactory_helper()->update_server_text . '</a>' ) . '</p>';
		}
		$html .= '<p>' . '<a class="button button-secondary" style="margin-top:18px;" href="' . add_query_arg( array( 'alg_update_item_list' => '1' ) ) . '">' . __( 'Update item list manually', 'wpcodefactory-helper' ) . '</a>';
		$html .= '</div>';
		echo $html;
	}

}

endif;

return new Alg_WPCodeFactory_Helper_Site_Key_Manager();
