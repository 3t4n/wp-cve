<?php
/**
 * SicialFlow Admin categories
 *
 * @package SocialFlow
 */

/**
 *  SocialFlow_Admin_Settings_Categories.
 */
class SocialFlow_Admin_Settings_Categories extends SocialFlow_Admin_Settings_Page {

	/**
	 * Add actions to manipulate messages
	 *
	 * @since 2.1
	 * @access public
	 */
	public function __construct() {
		global $socialflow;

		// Add menu page only if we have connected to socialflow.
		if ( ! $socialflow->is_authorized() ) {
			return;
		}

		$this->slug = 'categories';

		// Store current page object.
		$socialflow->pages[ $this->slug ] = $this;

		// Add action to add menu page.
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		// Add update notice.
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );

		// ajax listner to connect or disconnect user and term.
		add_action( 'wp_ajax_sf_term_account', array( $this, 'ajax_connect_term_account' ) );

		// Add js object with term account relations.
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 20 );
	}

	/**
	 * This is callback for admin_menu action fired in construct
	 *
	 * @since 2.1
	 * @access public
	 */
	public function admin_menu() {

		add_submenu_page(
			'socialflow',
			esc_attr__( 'Category Routing', 'socialflow' ),
			esc_attr__( 'Category Routing', 'socialflow' ),
			'manage_options',
			$this->slug,
			array( $this, 'page' )
		);
	}

	/**
	 * Render admin page with all accounts
	 *
	 * @since 2.1
	 * @access public
	 */
	public function page() {
		?>
		<div class="wrap socialflow" id="socialflow-<?php echo esc_attr( $this->slug ); ?>">
			<h2><?php esc_html_e( 'Category Routing', 'socialflow' ); ?> <img class="sf-loader" style="display:none;" src="<?php echo esc_url( plugins_url( 'assets/images/wpspin.gif', SF_FILE ) ); ?>" alt=""> </h2>

			<?php $this->display_list(); ?>
			<?php $this->display_insert_form(); ?>
		</div>
		<?php

		wp_localize_script( 'socialflow-categories', 'sf_categories', array( 'security' => wp_create_nonce( SF_ABSPATH ) ) );
	}

	/**
	 * Outputs html list of of all categories and connected users
	 *
	 * @since 2.1
	 * @access public
	 */
	public function display_list() {
		// get array of categories.
		$terms = get_terms(
			'category', array(
				'hide_empty' => false,
			)
		);

		// Render empty message if there are no categories.
		if ( empty( $terms ) ) :
		?>
			<p><?php esc_html_e( 'There are no categories.', 'socialflow' ); ?></p>
		<?php
		return;
endif;
?>

		<table cellspacing="0" class="wp-list-table widefat fixed sf-accounts">
			<thead><tr>
				<th style="width:200px" class="manage-column column-username" id="username" scope="col">
					<span><?php esc_html_e( 'Category', 'socialflow' ); ?></span>
				</th>
				<th class="manage-column column-account-type" id="account-type" scope="col">
					<span><?php esc_html_e( 'Accounts', 'socialflow' ); ?></span>
				</th>
			</tr></thead>

			<tbody id="accounts-categories-list" class="list:term-accounts">
				<?php
				if ( is_array( $terms ) ) {
					foreach ( $terms as $term ) {
						$this->term_content( $term );
					}
				}
?>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Output single category item with the list of connected accounts
	 *
	 * @since 2.1
	 * @access public
	 *
	 * @param object $term wp term object.
	 */
	public function term_content( $term ) {
		// Get all accounts in current category.
		if ( ! ( $term instanceof WP_Term ) ) {
			return;
		}
		$accounts = $this->get_term_accounts( $term->term_id, $term->taxonomy );
		// If there are no connected accounts return.
		if ( empty( $accounts ) ) {
			return;
		}
		?>
		<tr class="alternate">
			<td class="category column-category"><?php echo esc_html( $term->name ); ?></td>
			<td data-term_id="<?php echo esc_attr( $term->term_id ); ?>" data-taxonomy="<?php echo esc_attr( $term->taxonomy ); ?>" class="accounts column-accounts catetory-accounts" id="js-term-accounts-<?php echo esc_attr( $term->term_id ); ?>">
				<?php
				foreach ( $accounts as $account_id ) {
					$this->account_content( $account_id );
				}
?>
			</td>
		</tr>
		<?php
	}

	/**
	 * Output single account which is connected to passed term
	 *
	 * @since 2.1
	 * @access public
	 *
	 * @param int $account_id account id.
	 */
	public function account_content( $account_id ) {
		global $socialflow;
		?>
		<span data-account_id="<?php echo esc_attr( $account_id ); ?>" class="category-account-item"><?php echo esc_attr( $socialflow->accounts->get_display_name( $account_id ) ); ?> <b class="js-remove-connection close">x</b></span>
		<?php
	}

	/**
	 * Output form capable for adding new connection between category and account
	 *
	 * @since 2.1
	 * @access public
	 */
	public function display_insert_form() {
		global $socialflow;
		?>
		<form id="sf-account-category-form" action="options.php" method="post">
			<select id="sf-select-account" name="account_id">
				<option value="-1"><?php esc_html_e( 'Select Account', 'socialflow' ); ?></option>

				<?php foreach ( $socialflow->accounts->get_enabled_accounts() as $account_id => $account ) : ?>
					<option value="<?php echo esc_attr( $account_id ); ?>" >
						<?php echo esc_html( $account->get_display_name() ); ?>
					</option>
				<?php endforeach ?>

			</select>
			<?php
			wp_dropdown_categories(
				array(
					'hide_empty'       => 0,
					'name'             => 'term_id',
					'id'               => 'sf-term-id',
					'show_option_none' => esc_attr__( 'Select Category' ),
				)
			);
				?>
			<input class="button" type="submit" value="<?php esc_attr_e( 'Add', 'socialflow' ); ?>" />
			<img class="sf-loader" style="display:none;" src="<?php echo esc_url( plugins_url( 'assets/images/wpspin.gif', SF_FILE ) ); ?>" alt="">
		</form>
		<?php
	}

	/**
	 * Callback method for ajax request to connect category and account
	 *
	 * @since 2.1
	 * @access public
	 *
	 * @return void output json with result status
	 */
	public function ajax_connect_term_account() {
		check_ajax_referer( SF_ABSPATH, 'security' );
		$socialflow_params = filter_input_array( INPUT_GET );
		$account_id        = (int) $socialflow_params['account_id'];
		$term_id           = (int) $socialflow_params['term_id'];
		$taxonomy          = 'category';
		$render            = isset( $socialflow_params['render'] ) ? $socialflow_params['render'] : false;

		// Result data array.
		$data = array();

		// Check if we have valid parametrs.
		if ( $term_id > 0 && $account_id > 0 ) {
			if ( 'connect' === $socialflow_params['method'] ) {
				$status = $this->connect_term_account( $term_id, $account_id, $taxonomy );
			} elseif ( 'disconnect' === $socialflow_params['method'] ) {
				$status = $this->disconnect_term_account( $term_id, $account_id, $taxonomy );
			}
		}

		$data['status'] = isset( $status ) ? (int) $status : 0;

		if ( $render && $data['status'] ) {
			ob_start();
			switch ( $render ) {
				case 'term':
					$func = function_exists( 'wpcom_vip_get_term_by' ) ? 'wpcom_vip_get_term_by' : 'get_term_by';
					$term = call_user_func( $func, 'id', $term_id, $taxonomy );
					$this->term_content( $term );
					break;
				case 'account':
					$this->account_content( $account_id );
					break;
			}
			$data['html'] = ob_get_clean();
		}

		wp_send_json( $data );
	}

	/**
	 * Add connection between account and term
	 *
	 * @since 2.1
	 * @access public
	 *
	 * @param int    $term_id term id.
	 * @param int    $account_id account_user_id.
	 * @param string $taxonomy term taxonomy.
	 * @return bool if term and account are already connected return false
	 */
	public function connect_term_account( $term_id, $account_id, $taxonomy ) {
		global $socialflow;

		// get connections for current taxonomy and search maybe it alreade exists.
		$connections = $socialflow->options->get( 'term_account_' . $taxonomy, array() );

		// Check if connection already exists.
		if ( ! empty( $connections ) ) {
			foreach ( $connections as $connection ) {
				if ( $term_id === $connection[0] && $account_id === $connection[1] ) {
					return false;
				}
			}
		}

		// Update option.
		$connections[] = array( $term_id, $account_id );
		$socialflow->options->set( 'term_account_' . $taxonomy, $connections );
		$socialflow->options->save();

		return true;
	}

	/**
	 * Remove connection between account and term
	 *
	 * @since 2.1
	 * @access public
	 *
	 * @param int    $term_id term id.
	 * @param int    $account_id account_user_id.
	 * @param string $taxonomy term taxonomy.
	 * @return bool
	 */
	public function disconnect_term_account( $term_id, $account_id, $taxonomy ) {
		global $socialflow;

		// get connections for current taxonomy and search maybe it alreade exists.
		$connections = $socialflow->options->get( 'term_account_' . $taxonomy, array() );

		// Check if connection already exists.
		if ( ! empty( $connections ) ) {
			foreach ( $connections as $key => $connection ) {
				if ( $term_id === $connection[0] && $account_id === $connection[1] ) {

					unset( $connections[ $key ] );

					// Update option.
					$socialflow->options->set( 'term_account_' . $taxonomy, $connections );
					$socialflow->options->save();

					return true;
				}
			}
		}

		return false;

		// loop throug.
	}

	/**
	 * Get array of connected objects to passed object
	 *
	 * @since 2.1
	 * @access public
	 *
	 * @param int    $object_id object id.
	 * @param string $taxonomy term taxonomy.
	 * @param string $object_type current object type ( term | account ).
	 * @return array of connected accounts, may be empty if none objects are connected
	 */
	public function get_term_accounts( $object_id, $taxonomy, $object_type = 'term' ) {
		global $socialflow;
		$connected = array();

		$object_key = ( 'term' === $object_type ) ? 0 : 1;
		$target_key = ( 'term' === $object_type ) ? 1 : 0;

		// retrieve all connections for apripriate taxonomy.
		$connections = $socialflow->options->get( 'term_account_' . $taxonomy );
		if ( ! empty( $connections ) ) {
			// collect all account ids for passed term_id.
			foreach ( $connections as $connection ) {
				if ( $object_id === $connection[ $object_key ] ) {
					$connected[] = $connection[ $target_key ];
				}
			}
		}

		return $connected;
	}

	/**
	 * Output js object
	 */
	public function admin_enqueue_scripts() {
		global $socialflow, $pagenow, $post_type;

		if ( 'post' === $post_type && in_array( $pagenow, array( 'post.php', 'post-new.php' ), true ) ) {
			// get all connections.
			$connections = $socialflow->options->get( 'term_account_category' );

			if ( empty( $connections ) ) {
				return;
			}

			$grouped = array();

			// groupt connections by category.
			foreach ( $connections as $connection ) {
				if ( array_key_exists( $connection[0], $grouped ) ) {
					$grouped[ $connection[0] ][] = $connection[1];
				} else {
					$grouped[ $connection[0] ] = array( $connection[1] );
				}
			}

			wp_localize_script( 'socialflow-categories', 'sf_terms_accounts', array( 'category' => $grouped ) );

		}
	}
}
