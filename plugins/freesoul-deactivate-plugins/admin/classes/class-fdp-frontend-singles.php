<?php
/**
 * Class for the Frontend Singles.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * FDP_Frontend_Singles_Page
 */

class FDP_Frontend_Singles extends Eos_Fdp_Matrix_Page {

	/**
	 * Is home.
	 *
	 * @var bool $is_home True if homepage.
	 */
	public $is_home;

	/**
	 * Default language.
	 *
	 * @var string $default_language Default languaage.
	 */
	public $default_language;

	/**
	 * Home URL.
	 *
	 * @var string $home_url Homepage URL.
	 */
	public $home_url;

	/**
	 * Plugins Table.
	 *
	 * @var string $plugins_table Plugins Table.
	 */
	public $plugins_table;

	/**
	 * Posts.
	 *
	 * @var object $posts Posts.
	 */
	public $posts;

	/**
	 * Posts per page.
	 *
	 * @var int $posts_per_page Posts per page.
	 */
	public $posts_per_page;

	/**
	 * Show on front.
	 *
	 * @var string $show_on_front Show on front.
	 */
	public $show_on_front;

	/**
	 * Post Type.
	 *
	 * @var string $post_type Post Type.
	 */
	public $post_type;

	/**
	 * Active Label.
	 *
	 * @var string $active_label Active Label.
	 */
	public $active_label;

	/**
	 * Important Pages.
	 *
	 * @var array $important_pages Important Pages.
	 */
	public $important_pages;

	/**
	 * Is relevant page.
	 *
	 * @var bool $is_relevant_pages True if it's a relevant page.
	 */
	public $is_relevant_pages;

	/**
	 * Order by.
	 *
	 * @var string $orderby Order by.
	 */
	public $orderby;

	/**
	 * Order.
	 *
	 * @var string $order Order.
	 */
	public $order;

	/**
	 * Is single post.
	 *
	 * @var bool $fdp_is_single_post True if it's a single post.
	 */
	public $fdp_is_single_post;

	/**
	 * Single post ID.
	 *
	 * @var int $single_post_id Single post ID.
	 */
	public $single_post_id = false;

	/**
	 * Page on front.
	 *
	 * @var string $page_on_front Page on front.
	 */
	public $page_on_front;

	/**
	 * Overridable.
	 *
	 * @var bool $overridable True if overridable.
	 */
	public $overridable;

	/**
	 * Title.
	 *
	 * @var string $title Title.
	 */
	public $title;

	/**
	 * is_hierarchical.
	 *
	 * @var string $is_hierarchical.
	 */
	public $is_hierarchical;

	/**
	 * Post Types Matrix.
	 *
	 * @var array $post_types_matrix Post types matrix.
	 */
	public $post_types_matrix;

	public $post_types_matrix_pt;

	public function before_section( $page_slug ) {
		wp_nonce_field( 'eos_dp_key', 'eos_dp_key' );
		$this->default_language = eos_dp_default_language();
		$this->home_url         = get_option( 'home' );
		$this->plugins_table    = eos_dp_plugins_table();
		$this->orderby = 'title';
		$this->order = 'ASC';
		$default_posts_per_page = ! wp_is_mobile() ? 30 : 15;
		if( isset( $_REQUEST['posts_per_page'] ) && absint( $_REQUEST['posts_per_page'] ) > 0 ) {
			$this->posts_per_page = esc_attr( sanitize_text_field( $_REQUEST['posts_per_page'] ) );
		}
		elseif( isset( $_COOKIE['fdp_posts_per_page'] ) && absint( $_COOKIE['fdp_posts_per_page'] ) > 0 ) {
			$this->posts_per_page = absint( sanitize_text_field( $_COOKIE['fdp_posts_per_page'] ) );
		}
		else{
			$this->posts_per_page = $default_posts_per_page;
		}
		$this->post_type        = isset( $_GET['eos_dp_post_type'] ) ? esc_attr( sanitize_text_field( $_GET['eos_dp_post_type'] ) ) : 'page';
		$this->title            = isset( $_GET['eos_dp_relevant_pages'] ) && 'true' === $_GET['eos_dp_relevant_pages'] ? esc_html__( 'Relevant pages', 'freesoul-deactivate-plugins' ) : $this->active_label;
		$this->dataset          = ' data-post_type="' . esc_attr( $this->post_type ) . '"';
		$this->title            = isset( $_GET['eos_dp_home'] ) && 'true' === $_GET['eos_dp_home'] ? esc_html__( 'Homepage', 'freesoul-deactivate-plugins' ) : $this->title;
		$labsObj                = get_post_type_object( $this->post_type );
		if ( isset( $labsObj->labels ) ) {
			$labs               = $labsObj->labels;
			$this->active_label = isset( $labs->name ) ? $labs->name : esc_html__( 'posts', 'freesoul-deactivate-plugins' );
		}
		$eos_dp_need_custom_url     = eos_dp_get_option( 'eos_dp_need_custom_url' );
		$this->post_types_matrix    = eos_dp_get_option( 'eos_post_types_plugins' );
		$this->post_types_matrix_pt = isset( $this->post_types_matrix[ $this->post_type ] ) ? $this->post_types_matrix[ $this->post_type ] : array();
		$post_types_plugins_pt      = isset( $this->plugins_table[ $this->post_type ] ) ? $this->plugins_table[ $this->post_type ] : false;
		$this->overridable          = $post_types_plugins_pt && isset( $post_types_plugins_pt[0] ) ? $post_types_plugins_pt[0] : false;
		$current_labels_name        = '';
		$need_custom_url            = array();
		$ofs                        = isset( $_GET['eos_page'] ) && absint( $_GET['eos_page'] ) > 0 ? ( absint( $_GET['eos_page'] ) - 1 ) * $this->posts_per_page : 0;
		$args                       = array(
			'post_type'      => $this->post_type,
			'post_status'    => 'any',
			'posts_per_page' => $this->posts_per_page,
			'offset'         => $ofs,
		);
		if( isset( $_REQUEST['orderby'] ) ) {
			$args['orderby'] = $this->orderby = esc_attr( sanitize_text_field( $_REQUEST['orderby'] ) );
		}
		elseif( isset( $_COOKIE['fdp_orderby'] ) ) {
			$args['orderby'] = $this->orderby = esc_attr( sanitize_text_field( $_COOKIE['fdp_orderby'] ) );
		}
		else {
			$args['orderby'] = 'title';
		}

		if( isset( $_REQUEST['order'] ) ) {
			$args['order'] =$this->order = esc_attr( sanitize_text_field( $_REQUEST['order'] ) );
		}
		elseif( isset( $_COOKIE['fdp_order'] ) ) {
			$args['order'] = $this->order = esc_attr( sanitize_text_field( $_COOKIE['fdp_order'] ) );
		}
		else {
			$args['order'] = 'ASC';
		}
		if ( isset( $_GET['lang'] ) ) {
			$args['suppress_filters'] = false;
		}
		$this->is_hierarchical = is_post_type_hierarchical( $this->post_type );
		if ( isset( $_GET['eos_dp_post_in'] ) && '' !== $_GET['eos_dp_post_in'] ) {
			$args['post__in'] = explode( '-', esc_attr( sanitize_text_field( $_GET['eos_dp_post_in'] ) ) );
		}
		if ( isset( $_GET['eos_post_title'] ) ) {
			?><h2><?php printf( esc_html__( 'Results for %s', 'freesoul-deactivate-plugins' ), esc_html( sanitize_text_field( $_GET['eos_post_title'] ) ) ); ?></h2>
			<?php
			$args['s'] = esc_attr( urldecode( $_GET['eos_post_title'] ) ); //@codingStandardsIgnoreLine.
			// No need for sanitization, esc_attr used after urldecode.
		}
		if ( isset( $_GET['eos_cat'] ) ) {
			$cat = get_term( (int) $_GET['eos_cat'] );
			$cat = $cat && ! is_wp_error( $cat ) ? sprintf( esc_html__( 'the category "%s"', 'freesoul-deactivate-plugins' ), $cat->name ) : sprintf( esc_html__( 'Category id %s', 'freesoul-deactivate-plugins' ), (int) $_GET['eos_cat'] );
			?>
		<h2><?php printf( esc_html__( 'Results for %s', 'freesoul-deactivate-plugins' ), esc_html( $cat ) ); ?></h2>
			<?php
			$args['category'] = sanitize_title( $_GET['eos_cat'] );
		}
		if ( isset( $_GET['tax_name'] ) && isset( $_GET['term_slug'] ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => esc_attr( sanitize_text_field( $_GET['tax_name'] ) ),
					'field'    => 'slug',
					'terms'    => esc_attr( sanitize_text_field( $_GET['term_slug'] ) ),
				),
			);
		}
		if ( in_array( $this->post_type, array( 'post', 'page' ) ) ) {
			if ( function_exists( 'eos_scfm_get_mobile_ids' ) ) {
				if ( isset( $_GET['device'] ) && 'mobile' === $_GET['device'] ) {
					$args['post__in'] = eos_scfm_get_mobile_ids();
				} elseif ( isset( $_GET['device'] ) && 'desktop' === $_GET['device'] ) {
					$args['post__not_in'] = eos_scfm_get_mobile_ids();
				}
			}
		}
		$this->is_home           = false;
		$this->is_relevant_pages = false;
		$this->important_pages   = eos_dp_important_pages();
		
		$pagination = isset( $_GET['eos_page'] ) && absint( $_GET['eos_page'] ) > 0 ? absint( $_GET['eos_page'] ) : 1;
		if ( isset( $_GET['eos_dp_home'] ) && 'true' === $_GET['eos_dp_home'] ) {
			$this->show_on_front = eos_dp_get_option( 'show_on_front' );
			if ( 'page' === $this->show_on_front ) {
				$this->is_home = true;
			}
		} elseif ( isset( $_GET['eos_dp_relevant_pages'] ) && 'true' === $_GET['eos_dp_relevant_pages'] ) {
			$this->is_relevant_pages = true;
			$args['post__in']        = $this->important_pages['ids'];
		}
		$this->page_on_front = eos_dp_get_option( 'page_on_front' );
		$page_for_posts      = eos_dp_get_option( 'page_for_posts' );
		if ( 0 === absint( $this->page_on_front ) && absint( $page_for_posts ) > 0 ) {
			$this->page_on_front = absint( $page_for_posts );
		}
		$this->posts              = ! $this->is_home ? get_posts( $args ) : array( get_post( $this->page_on_front ) );
		if( 'custom_order' === $this->orderby && isset( $_COOKIE['fdp_single_rows_order_' . esc_attr( $pagination )] ) && ! empty( $_COOKIE['fdp_single_rows_order_' . esc_attr( $pagination )] ) ) {
			$saved_order = explode( ',', sanitize_text_field( $_COOKIE['fdp_single_rows_order_' . esc_attr( $pagination )] ) );
			$new_order = $new_posts = array();
			$found = $not_found = 0;
			
			foreach( $this->posts as $post ) {
				if( in_array( $post->ID, $saved_order ) ) {
					$new_order[array_search( $post->ID, $saved_order )] = $post;
					++$found;
				}
				else{
					$new_posts[$not_found] = $post;
					++$not_found;
				}
			}
			for( $n = 0; $n < $not_found; ++$n  ) {
				$new_order[$found + $n] = $new_posts[$n];
			}
			ksort( $new_order );
			$this->posts = $new_order;
		}
		
		$this->fdp_is_single_post = 1 === count( $this->posts ) || ( isset( $_GET['eos_dp_post_in'] ) && absint( $_GET['eos_dp_post_in'] ) > 0 && isset( $_GET['single_post'] ) && 1 === absint( $_GET['single_post'] ) );
		if ( $this->fdp_is_single_post && ! isset( $_GET['eos_dp_post_in'] ) ) {
			$single_post          = $this->posts[0];
			$this->single_post_id = $single_post->ID;
		} else {
			$this->single_post_id = isset( $_GET['eos_dp_post_in'] ) ? absint( $_GET['eos_dp_post_in'] ) : false;
			$taxs                 = get_taxonomies( array(), 'objects' );
		}
		?>
	<script> var is_single_post = <?php echo $this->fdp_is_single_post ? 'true' : 'false'; ?>;</script>
		<?php
		if ( ! $this->is_home && ! $this->is_relevant_pages ) {
			$count_posts     = wp_count_posts( $this->post_type );
			$published_posts = $count_posts->publish + $count_posts->future + $count_posts->draft + $count_posts->pending + $count_posts->private;
			$pagesN          = ceil( $published_posts / max( 1, $this->posts_per_page ) );
			$current_page    = isset( $_GET['eos_page'] ) ? sanitize_text_field( $_GET['eos_page'] ) : 1;
			?>
	  <div id="eos-dp-pagination-wrp" class="right">
			<?php
	
				require EOS_DP_PLUGIN_DIR . '/admin/templates/partials/eos-dp-singles-pagination.php';
				?>
			<div id="fdp-s-t-i">
				<span id="eos-dp-toggle-search" class="hover dashicons dashicons-search" style="padding:10px"></span>
				<span id="eos-dp-toggle-pagination" class="hover dashicons dashicons-editor-ul" style="padding:10px"></span>
			</div>
		
			<div id="eos-dp-order-wrp" class="eos-hidden">
				<div class="eos-dp-display-data" style="display:inline-block">
					<h4 style="margin-bottom:-1px"><?php esc_html_e( 'Number of posts', 'freesoul-deactivate-plugins' ); ?></h4>
					<input id="eos-dp-posts-per-page" type="number" min="10" max="200" value="<?php echo esc_attr( $this->posts_per_page ); ?>" />
				</div>
				<div class="eos-dp-display-data" style="display:inline-block">
					<h4 style="margin-bottom:0"><?php esc_html_e( 'Order by', 'freesoul-deactivate-plugins' ); ?></h4>
					<select id="eos-dp-orderby-sel">
						<option value="title"<?php echo $this->orderby === 'title' ? ' selected' : ''; ?>><?php esc_html_e( 'Title', 'freesoul-deactivate-plugins' ); ?></option>
						<option value="ID"<?php echo $this->orderby === 'ID' ? ' selected' : ''; ?>><?php esc_html_e( 'Post id', 'freesoul-deactivate-plugins' ); ?></option>
						<option value="author"<?php echo $this->orderby === 'author' ? ' selected' : ''; ?>><?php esc_html_e( 'Author', 'freesoul-deactivate-plugins' ); ?></option>
						<option value="date"<?php echo $this->orderby === 'date' ? ' selected' : ''; ?>><?php esc_html_e( 'Date', 'freesoul-deactivate-plugins' ); ?></option>
						<option value="modified"<?php echo $this->orderby === 'modified' ? ' selected' : ''; ?>><?php esc_html_e( 'Last modified date', 'freesoul-deactivate-plugins' ); ?></option>
						<option value="menu_order"<?php echo $this->orderby === 'menu_order' ? ' selected' : ''; ?>><?php esc_html_e( 'Menu order', 'freesoul-deactivate-plugins' ); ?></option>
						<option value="custom_order"<?php echo $this->orderby === 'custom_order' ? ' selected' : ''; ?>><?php esc_html_e( 'Custom order', 'freesoul-deactivate-plugins' ); ?></option>
					</select>
				</div>
				<div class="eos-dp-display-data" style="display:inline-block">
					<h4 style="margin-bottom:0"><?php esc_html_e( 'Order', 'freesoul-deactivate-plugins' ); ?></h4>
					<select id="eos-dp-order-sel">
						<option value="ASC"<?php echo strtolower( $this->order ) === 'asc' ? ' selected' : ''; ?>><?php esc_html_e( 'Ascending', 'freesoul-deactivate-plugins' ); ?></option>
						<option value="DESC"<?php echo strtolower( $this->order ) === 'desc' ? ' selected' : ''; ?>><?php esc_html_e( 'Descending', 'freesoul-deactivate-plugins' ); ?></option>
					</select>
				</div>
			<?php
			if ( function_exists( 'eos_scfm_get_mobile_ids' ) && in_array( $this->post_type, array( 'post', 'page' ) ) ) {
				?>
				<div class="eos-dp-display-data" style="display:inline-block">
					<h4 style="margin-bottom:0"><?php esc_html_e( 'Device', 'freesoul-deactivate-plugins' ); ?></h4>
					<select id="eos-dp-device">
						<option value="all"<?php echo isset( $_REQUEST['device'] ) && strtolower( sanitize_text_field( $_REQUEST['device'] ) ) === 'all' ? ' selected' : ''; ?>><?php esc_html_e( 'All devices', 'freesoul-deactivate-plugins' ); ?></option>
						<option value="desktop"<?php echo isset( $_REQUEST['device'] ) && strtolower( sanitize_text_field( $_REQUEST['device'] ) ) === 'desktop' ? ' selected' : ''; ?>><?php esc_html_e( 'Desktop devices', 'freesoul-deactivate-plugins' ); ?></option>
						<option value="mobile"<?php echo isset( $_REQUEST['device'] ) && strtolower( sanitize_text_field( $_REQUEST['device'] ) ) === 'mobile' ? ' selected' : ''; ?>><?php esc_html_e( 'Mobile devices', 'freesoul-deactivate-plugins' ); ?></option>
					</select>
				</div>
				<?php
			}
			?>
				<div class="eos-dp-display-data" style="margin-top:16px">
					<a href="
					<?php
					echo esc_url(
						add_query_arg(
							array(
								'posts_per_page' => 30,
								'orderby'        => 'title',
								'order'          => 'ASC',
							),
							admin_url( 'admin.php?page=eos_dp_menu&eos_dp_post_type=' . $this->post_type )
						)
					);
					?>
		" class="button" id="eos-dp-order-refresh"><?php esc_html_e( 'Show', 'freesoul-deactivate-plugins' ); ?></a>
				</div>
			</div>

			<div class="eos-dp-search-wrp eos-hidden">
				<div class="eos-dp-search-box" style="margin-bottom:8px">
					<label class="screen-reader-text" for="post-search-input"><?php esc_html_e( 'Search', 'freesoul-deactivate-plugins' ); ?></label>
					<input type="search" id="eos-dp-post-search" value="">
					<input type="submit" id="eos-dp-post-search-submit" data-url="<?php echo esc_url( add_query_arg( 'eos_post_title', '', admin_url( 'admin.php?page=eos_dp_menu&eos_dp_post_type=' . $this->post_type ) ) ); ?>" class="button" value="<?php esc_attr_e( 'Search', 'freesoul-deactivate-plugins' ); ?>" />
				</div>
				<div class="eos-dp-search-box">
					<label class="screen-reader-text" for="post-search-input"><?php esc_html_e( 'Search By Category', 'freesoul-deactivate-plugins' ); ?></label>
					<div id="eos-dp-by-cat-search" style="display:inline-block"><?php wp_dropdown_categories(); ?></div>
					<input type="submit" id="eos-dp-by-cat-search-submit" data-url="<?php echo esc_url( add_query_arg( 'eos_cat', '', admin_url( 'admin.php?page=eos_dp_menu&eos_dp_post_type=' . $this->post_type ) ) ); ?>" class="button" value="<?php esc_html_e( 'Search By Category', 'freesoul-deactivate-plugins' ); ?>" />
				</div>
			</div>

		</div>
			  <?php

		}
		$count_posts     = wp_count_posts( $this->post_type );
		$published_posts = $count_posts->publish + $count_posts->future + $count_posts->draft + $count_posts->pending + $count_posts->private;
		$pagesN          = ceil( $published_posts / max( 1, $this->posts_per_page ) );
		$current_page    = isset( $_GET['eos_page'] ) ? sanitize_text_field( $_GET['eos_page'] ) : 1;
		if ( $this->fdp_is_single_post ) {
			add_filter( 'admin_footer_text', '__return_false', 999999 );
			add_filter( 'update_footer', '__return_false', 999999 );
		}
		if ( defined( 'ICL_SITEPRESS_VERSION' ) || function_exists( 'pll_languages_list' ) ) {
			echo wp_kses_post( eos_dp_wpml_switcher() );
		}
		?>
	<h2 id="eos-dp-singles-title" style="margin-bottom:16px" data-post-type="<?php echo esc_attr( $this->post_type ); ?>"><?php echo esc_html( $this->title ); ?></h2>
		<div>
		<?php if ( $this->is_home ) { ?>
			<span id="eos-dp-autosuggest-all" class="button" title="<?php esc_attr_e( 'Suggest plugins', 'freesoul-deactivate-plugins' ); ?>"><?php esc_html_e( 'Suggest plugins', 'freesoul-deactivate-plugins' ); ?></span>
			<p><?php echo wp_kses_post( sprintf( __( 'Pressing the button above, FDP will suggest you the needed plugins. However, check the page preview with the lens icon %s before to save the options.', 'freesoul-deactivate-plugins' ), '<span class="dashicons dashicons-search"></span>' ) ); ?></p>
			<p><?php echo wp_kses_post( sprintf( __( 'With the %s you can have the auto-suggestion also on other pages.', 'freesoul-deactivate-plugins' ), '<a style="color:inherit" href="https://freesoul-deactivate-plugins.com/" target="_blank">' . esc_attr__( 'PRO version', 'freesoul-deactivate-plugins' ) . '</a>' ) ); ?></p>

			<?php
			if ( eos_dp_get_option( 'eos_dp_critical_css' ) ) {
				wp_nonce_field( 'fdp_generate_critical_css', 'fdp_generate_critical_css' );
				$onclick = 'eos_dp_send_ajax(jQuery(this),{"nonce" : document.getElementById("fdp_generate_critical_css").value,"url" : this.dataset.url,"action" : "eos_dp_generate_critical_css"});return false;';
				?>
	  <p><span id="fdp-generate-critical-css" class="button" title="<?php esc_attr__( 'Generate Critical CSS', 'freesoul-deactivate-plugins' ); ?>" data-url="<?php echo esc_url( get_home_url() ); ?>" onclick="<?php echo esc_js( $onclick ); ?>"><?php esc_html_e( 'Generate Critical CSS', 'freesoul-deactivate-plugins' ); ?></span></p>
				<?php
			}
		}
		?>
			<p id="eos-dp-autosuggest-msg" class="eos-hidden notice notice-warning"><?php esc_html_e( 'It may take a couple of minutes, drink a coffee while we try to understand which plugins you need on this page', 'freesoul-deactivate-plugins' ); ?></p>
			<p id="eos-dp-autosuggest-msg-error" class="notice notice-error eos-hidden"><?php esc_html_e( 'Unable to finish the tests to suggest the unused plugins. Probably the server is too busy.', 'freesoul-deactivate-plugins' ); ?></p>
		</div>
		<?php do_action( 'eos_dp_after_singles_title' ); ?>
		<div id="eos-dp-table-head-actions"><?php do_action( 'eos_dp_pre_table_head' ); ?></div>
		<?php
	}

	public function tableBody( $page_slug ) {
		if ( $this->posts && ! empty( $this->posts ) ) {
			$ids = array();
			foreach ( $this->posts as $post ) {
				if ( $post && is_object( $post ) ) {
					$ids[] = $post->ID;
				}
			}
			$meta_valuesObj = eos_dp_get_multiple_metadata( '_eos_deactive_plugins_key', $ids );
			$meta_values    = array();
			if ( ! empty( $meta_valuesObj ) ) {
				foreach ( $meta_valuesObj as $obj ) {
					$meta_values[ sanitize_key( $obj->post_id ) ] = sanitize_text_field( $obj->meta_value );
				}
			}
			$status_icons = array(
				'public'  => '',
				'private' => '<span title="' . esc_attr__( 'Private', 'freesoul-deactivate-plugins' ) . '" class="dashicons dashicons-privacy"></span>',
				'draft'   => '<span title="' . esc_attr__( 'Draft', 'freesoul-deactivate-plugins' ) . '" class="dashicons dashicons-hidden"></span>',
			);
			$row          = 1;
			foreach ( $this->posts as $post ) {
				if ( $post && is_object( $post ) && $post->post_type === $this->post_type ) {
					$extra_class = esc_attr( apply_filters( 'fdp_singles_row_class', '', $post ) );
					$desktop_id  = 0;
					$mobileClass = '';
					if ( in_array( $this->post_type, array( 'post', 'page' ) ) ) {
						if ( function_exists( 'eos_scfm_related_desktop_id' ) ) {
							$desktop_id = eos_scfm_related_desktop_id( $post->ID );
							if ( $desktop_id > 0 ) {
								$mobileClass = ' eos-dp-mobile';
							}
						}
					}
					$loc     = false;
					$flag    = '';
					$post_id = $post->ID;
					if ( function_exists( 'pll_get_post_language' ) ) {
						$loc = pll_get_post_language( $post->ID );
						if ( $loc && '' !== $loc ) {
							$flag = defined( 'POLYLANG_FILE' ) && defined( 'POLYLANG_DIR' ) && file_exists( POLYLANG_DIR . '/flags/' . str_replace( 'en.png', 'england.png', $loc . '.png' ) ) ? '<img src="' . esc_url( plugins_url( '/flags/' . str_replace( 'en.png', 'england.png', $loc . '.png' ), POLYLANG_FILE ) ) . '" />' : esc_html( strtoupper( $loc ) );
						}
						if ( function_exists( 'pll_get_post' ) && ! isset( $_GET['lang'] ) ) {
							$post_id = absint( pll_get_post( $post->ID, $this->default_language ) );
						}
					} elseif ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
						$loc = apply_filters(
							'wpml_element_language_code',
							null,
							array(
								'element_id'   => $post->ID,
								'element_type' => 'post',
							)
						);
						if ( $loc && '' !== $loc ) {
							$flag = defined( 'ICL_PLUGIN_URL' ) && defined( 'WPML_PLUGIN_PATH' ) && file_exists( WPML_PLUGIN_PATH . '/res/flags/' . $loc . '.png' ) ? '<img src="' . esc_url( ICL_PLUGIN_URL . '/res/flags/' . $loc . '.png' ) . '" />' : esc_html( strtoupper( $loc ) );
						}
					}
					$values_string = isset( $meta_values[ $post_id ] ) ? $meta_values[ $post_id ] : '';
					$values        = explode( ',', $values_string );
					$bin           = substr( implode( '', array_map( 'eos_dp_is_not_empty_string', $values ) ), 1 );
					$needCustom    = false;
					if ( $loc && strtolower( substr( $loc, 0, 2 ) ) !== strtolower( substr( $this->default_language, 0, 2 ) ) ) {
						$need_custom_url[ $post->ID ] = get_permalink( $post->ID );
						$needCustom                   = true;
					}
					$locked = '';
					if ( ! $this->overridable || ( isset( $this->post_types_matrix_pt[3] ) && is_array( $this->post_types_matrix_pt[3] ) && ! empty( $this->post_types_matrix_pt[3] ) && in_array( $post->ID, $this->post_types_matrix_pt[3] ) ) ) {
						$locked = ' eos-post-locked';
					}
					if ( $needCustom && ! empty( $eos_dp_need_custom_url ) && ! in_array( $post->ID, array_keys( $eos_dp_need_custom_url ) ) ) {
						$locked = '';
					}
					$child_class = isset( $post->post_parent ) && absint( $post->post_parent ) > 0 ? ' fdp-row-is-child' : ' fdp-top-level-page';
					$args        = array(
						'test_id'     => time(),
						'fdp_post_id' => $post->ID,
					);
					$home_class  = '';
					if ( absint( $this->page_on_front ) === $post->ID ) {
						$args['is_home'] = '1';
						$home_class      = ' fdp-row-is-home';
					}
					$extra_dataset = '';
					if ( $post_id !== $post->ID ) {
						$extra_dataset .= ' data-default-lang-ID="' . esc_attr( $post->ID ) . '"';
					}
					if ( $flag && '' !== $flag && $loc !== $this->default_language ) {
						$extra_class .= ' fdp-translated-page';
					}
					if ( $this->fdp_is_single_post ) {
						$extra_class .= ' fdp-actions-on';
					}
					$woo = isset( $this->important_pages['woo_ids'] ) && ! empty( $this->important_pages['woo_ids'] ) && in_array( $post->ID, $this->important_pages['woo_ids'] );
					?>
		  <tr<?php echo $extra_dataset; //phpcs:ignore WordPress.Security.EscapeOutput -- The escaping was already applied on while building $extra_dataset. ?> data-url="<?php echo esc_attr( str_replace( $this->home_url, '', get_permalink( $post->ID ) ) ); ?>" data-active-plugins="<?php echo esc_attr( substr_count( $bin, '1' ) ); ?>" data-disabled-plugins="<?php echo esc_attr( substr_count( $bin, '0' ) ); ?>"  data-bin="<?php echo esc_attr( $bin ); ?>" class="fdp-row-<?php echo esc_attr( $row ); ?> eos-dp-post-row eos-dp-post-
						<?php
						echo esc_attr( $this->post_type . $mobileClass . $locked . $child_class . $home_class . $extra_class );
						echo $woo ? ' eos-dp-woo-row' : '';
						echo isset( $status_icons[ $post->post_status ] ) ? ' fdp-row-' . esc_attr( $post->post_status ) : '';
						echo isset( $this->important_pages['nav_ids'] ) && ! empty( $this->important_pages['nav_ids'] ) && in_array( $post->ID, $this->important_pages['nav_ids'] ) ? ' fdp-in-nav' : '';
						?>
						" data-post-id="<?php echo esc_attr( $post->ID ); ?>" data-row_id="<?php echo esc_attr( $post->ID ); ?>">
					<?php
					if ( isset( $post->post_title ) ) {
						?>
			  <td class="eos-dp-post-name-wrp">
				<span class="fdp-row-actions-ico dashicons dashicons-plus" title="<?php esc_attr__( 'Action buttons', 'freesoul-deactivate-plugins' ); ?>"></span>
				<span class="eos-dp-lock-post-wrp"><input data-row="<?php echo esc_attr( $row ); ?>" class="eos-dp-lock-post" type="checkbox" /></span>
				<span class="eos-dp-not-active-wrp"><input title="<?php printf( esc_attr__( 'Activate/deactivate all plugins in %s', 'freesoul-deactivate-plugins' ), esc_attr( $post->post_title ) ); ?>" data-row="<?php echo esc_attr( $row ); ?>" class="eos-dp-global-chk-row" type="checkbox" /></span>
				<span class="eos-dp-title">
						<?php
						if ( 1 === $row ) {
							$this->resize_col();
						}
						echo '<span class="fdp-title-text">';
						echo '' !== $post->post_title ? esc_html( $post->post_title ) : sprintf( esc_html__( 'Untitled (post id:%s)', 'freesoul-deactivate-plugins' ), esc_html( $post->ID ) );
						echo '</span>';
						echo isset( $status_icons[ $post->post_status ] ) ? $status_icons[ $post->post_status ] : ''; //phpcs:ignore WordPress.Security.EscapeOutput -- The escaping was already applied while building $status_icons.
						echo $woo ? '<span class="dashicons dashicons-cart"></span>' : '';
						echo isset( $this->important_pages['nav_ids'] ) && ! empty( $this->important_pages['nav_ids'] ) && in_array( $post->ID, $this->important_pages['nav_ids'] ) ? '<span title="' . esc_attr__( 'Included in the navigation', 'freesoul-deactivate-plugins' ) . '" class="dashicons dashicons-menu-alt2"></span>' : '';
						if ( $desktop_id > 0 ) {
							$desktop_title = get_the_title( $desktop_id );
							?>
					<span class="eos-dp-mobile dashicons dashicons-smartphone"></span>
							<?php
						}
						?>
				  <span><?php echo $flag; //phpcs:ignore WordPress.Security.EscapeOutput -- The escaping was already applied while building $flag. ?></span>
				</span>
						<?php if ( strlen( $post->post_title ) > 46 ) { ?>
				<span class="fdp-long-title"><?php echo esc_html( $post->post_title ); ?></span>
				<?php } ?>
						<?php
						if ( ' fdp-row-is-home' === $home_class ) {
							?>
				  <span class="dashicons dashicons-admin-home"></span>
							<?php
						}
						if ( $this->is_hierarchical ) {
							if ( isset( $post->post_parent ) && absint( $post->post_parent ) > 0 ) {
								?>
				  <span class="fdp-child-page dashicons dashicons-networking"></span>
							  <?php } else { ?>
				  <span style="display:none" class="fdp-parent-page dashicons dashicons-networking"></span>
								<?php
							  }
						}
						?>
						<?php include EOS_DP_PLUGIN_DIR . '/admin/templates/partials/eos-dp-action-buttons.php'; ?>
			  </td>
						<?php
						$k = 0;
						foreach ( $this->active_plugins as $plugin ) {
							if ( in_array( $plugin, array_keys( $this->plugins_by_dirs ) ) ) {
								?>
				<td data-path="<?php echo esc_attr( $plugin ); ?>" class="<?php echo ! in_array( $plugin, $values ) ? 'eos-dp-active' : ''; ?>">
				  <div class="eos-dp-td-chk-wrp">
					<input class="eos-dp-row-<?php echo esc_attr( $row ); ?> eos-dp-col-<?php echo esc_attr( $k + 1 ); ?>" data-checked="<?php echo in_array( $plugin, $values ) ? 'checked' : 'not-checked'; ?>" type="checkbox"<?php echo in_array( $plugin, $values ) ? ' checked' : ''; ?> />
				  </div>
				</td>
								<?php
								++$k;
							}
						}
					}
					?>
		  </tr>
					<?php
					++$row;
				}
			}
		}
	}

	public function legend() {
		?>
	<span>
	  <span class="eos-dp-locked-wrp eos-dp-icon-wrp" style="position:relative"><span class="eos-post-locked-icon" style="width:40px;height:21px"></span>
	  <span class="eos-dp-no-decoration fdp-has-tooltip">
		<span class="dashicons dashicons-editor-help" style="font-size:24px"></span>
		<p class="fdp-tooltip" style="width:auto;white-space:inherit"><?php echo wp_kses_post( sprintf( __( 'The row settings will override the %1$sPost Types settings%2$s.', 'freesoul-deactivate-plugins' ), '<a href="' . esc_url( admin_url( '?page=eos_dp_by_post_type' ) ) . '" target="_fdp_post_types">', '</a>' ) ); ?></p>
	  </span>
	</span>
	<span>&nbsp;&nbsp;&nbsp;</span>
	<span>
	  <span class="eos-dp-unlocked-wrp eos-dp-icon-wrp" style="position:relative"><span class="eos-post-unlocked-icon" style="width:40px;height:19px"></span>
	  <span class="eos-dp-no-decoration fdp-has-tooltip">
		<span class="dashicons dashicons-editor-help" style="font-size:24px"></span>
		<p class="fdp-tooltip" style="width:auto;white-space:inherit"><?php echo wp_kses_post( sprintf( __( 'The %1$sPost Types settings%2$s will override the row settings.', 'freesoul-deactivate-plugins' ), '<a href="' . esc_url( admin_url( '?page=eos_dp_by_post_type' ) ) . '" target="_fdp_post_types">', '</a>' ) ); ?></p>
	  </span>
	</span>
		<?php if ( ! $this->fdp_is_single_post ) { ?>
	<div style="margin-top:16px">
	  <span id="eos-dp-lock-all" class="button<?php echo $this->is_home ? ' eos-hidden' : ''; ?>">
			<?php esc_html_e( 'Activate all rows', 'eos-dp-pro' ); ?>
	  </span>
	  <span id="eos-dp-unlock-all" class="button<?php echo $this->is_home ? ' eos-hidden' : ''; ?>">
			<?php esc_html_e( 'Disable all rows', 'eos-dp-pro' ); ?>
	  </span>
	</div>
			<?php do_action( 'fdp_before_row_filters' ); ?>
	<div id="fdp-show-page-filters-wrp" style="margin-top:16px"><span id="fdp-show-page-filters" class="button"><?php esc_html_e( 'Page Filters', 'freesoul-deactivate-plugins' ); ?><span class="dashicons dashicons-arrow-down" style="font-size:28px"></span></span></div>
	<div id="fdp-singles-filter" class="eos-hidden" style="position:absolute">
	  <p style="margin-top:6px">
		<span title="<?php esc_attr_e( 'Show all', 'freesoul-deactivate-plugins' ); ?>" style="color:initial;border-color:initial" class="eos-active button fdp-filter-all"><?php esc_html_e( 'Show all', 'freesoul-deactivate-plugins' ); ?></span>
	  </p>
	  <div>
		<span title="<?php esc_attr_e( 'Homepage', 'freesoul-deactivate-plugins' ); ?>" class="eos-dp-active hover dashicons dashicons-admin-home" data-class=".fdp-row-is-home"></span>
		<span title="<?php esc_attr_e( 'Active rows', 'freesoul-deactivate-plugins' ); ?>" class="eos-dp-active hover dashicons dashicons-yes" data-class=".eos-post-locked"></span>
		<span title="<?php esc_attr_e( 'Not active rows', 'freesoul-deactivate-plugins' ); ?>" class="eos-dp-active hover dashicons dashicons-no-alt" data-class=".eos-dp-post-row:not(.eos-post-locked)"></span>
		<span title="<?php esc_attr_e( 'Included in the navigation', 'freesoul-deactivate-plugins' ); ?>" class="eos-dp-active hover dashicons dashicons-menu-alt2" data-class=".fdp-in-nav"></span>
		<span title="<?php esc_attr_e( 'Private', 'freesoul-deactivate-plugins' ); ?>" class="eos-dp-active hover dashicons dashicons-privacy" data-class=".fdp-row-private"></span>
		<br/>
			<?php if ( $this->is_hierarchical ) { ?>
		<span title="<?php esc_attr_e( 'Child page', 'freesoul-deactivate-plugins' ); ?>" class="eos-dp-active hover fdp-child-page dashicons dashicons-networking" data-class=".fdp-row-is-child"></span>
		<span title="<?php esc_attr_e( 'Top level page', 'freesoul-deactivate-plugins' ); ?>" class="eos-dp-active hover fdp-parent-page dashicons dashicons-networking" data-class=".fdp-top-level-page"></span>
		<?php } ?>
		<span title="<?php esc_attr_e( 'Plugins all active', 'freesoul-deactivate-plugins' ); ?>" class="eos-dp-active hover fdp-all-active dashicons dashicons-plugins-checked" data-class="[data-disabled-plugins='0']"></span>
		<span title="<?php esc_attr_e( 'Plugins all disabled', 'freesoul-deactivate-plugins' ); ?>" class="eos-dp-active hover fdp-all-disabled dashicons dashicons-admin-plugins" data-class="[data-active-plugins='0']"></span>
			<?php
			if ( function_exists( 'eos_scfm_post_types' ) ) {
				?>
		  <span title="<?php esc_attr_e( 'Mobile', 'freesoul-deactivate-plugins' ); ?>" class="eos-dp-active hover dashicons dashicons-smartphone" data-class=".eos-dp-mobile"></span>
			<?php } ?>
			<?php if ( class_exists( 'WooCommerce' ) && 'page' === $this->post_type ) { ?>
		<span title="<?php esc_attr_e( 'WooCommerce pages', 'freesoul-deactivate-plugins' ); ?>" class="eos-dp-active hover dashicons dashicons-cart" data-class=".eos-dp-woo-row"></span>
	  <?php } ?>
	  </div>
	</div>
			<?php do_action( 'fdp_after_row_filters' ); ?>
	<?php } else { ?>
	<div style="clear:both"></div>
			<?php
	}
	}
}
