<?php
/**
 * User: truongsa
 * Date: 4/4/16
 * Time: 7:29 PM
 */

class Magazine_Mega_Menu_WP_Admin {
	function __construct(){

		add_action( 'customize_controls_print_footer_scripts', array( $this, 'item_settings_tpl' ) , 65 );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'customize_enqueue' )  );
		add_filter( 'customize_save_response', array( $this, 'amend_customize_save_response' ), 999, 2 );
	
		add_action( 'wp_ajax_mega_menu_load_setting', array( $this, 'ajax_load_item_mega' ) );
		// Load item data when init
		add_action( 'wp_ajax_mega_menu_load_item_data', array( $this, 'ajax_load_item_data' ) );
		add_action( 'wp_ajax_mega_menu_load_terms', array( $this, 'ajax_load_terms' ) );
		add_filter( 'wp_get_nav_menu_items', array( $this, 'filter_wp_get_nav_menu_items' ), 99, 3 );

	}

	function ajax_load_terms(){
		//$post_type = $_REQUEST['post_type'];
		$tax = sanitize_text_field( $_REQUEST['tax'] );

	

			if ( ! in_array( $tax, array( 'category', 'post_tag', 'post_format' ) ) ){
				wp_send_json_error();
			} else {
				$terms = get_terms(
					array(
						'taxonomy' => $tax,
						'orderby' => 'name',
						'hide_empty' => true,
					)
				);
				wp_send_json_success( $terms );
			}
		


		die();
	}

	function filter_wp_get_nav_menu_items(  $items, $menu, $args = array() ){
		foreach( $items as $index => $item ){
			$items[ $index ] = $this->filter_nav_menu_item( $item );
		}
		return $items;
	}

	function get_mega_keys(){
		$keys = array(
			'mega_menu_layout'   => '_mega_layout',
			'mega_menu_settings' => '_mega_settings',
			'mega_menu_post'     => '_mega_post',
			'mega_enable'        => '_mega_enable',
		);
		return $keys;
	}

	function get_term_keys()
	{
		$keys = array(
			'mega_enable'   => '_mega_enable',
		);
		return $keys;
	}

	function ajax_load_item_data( ) {
		$menu_id = absint( $_REQUEST['menu_id'] );
		$keys = $this->get_mega_keys();
		$menu_data = array();
		foreach ( $keys as $key => $meta_key ) {
			$menu_data[ $key ] = get_post_meta( $menu_id, $meta_key, true );
		}

		if ( $menu_data['mega_enable'] ) {
			$menu_data['has_children'] = 1;
		}

        if ( isset( $menu_data['mega_menu_post']['terms'] ) && ! empty( $menu_data['mega_menu_post']['terms']  ) && $menu_data['mega_menu_post']['tax'] ) {

            $terms = array();
            $_t = current( $menu_data['mega_menu_post']['terms'] );
            reset( $menu_data['mega_menu_post']['terms'] );
            if ( is_object( $_t ) ) {
                $terms = wp_list_pluck( $menu_data['mega_menu_post']['terms'], 'term_id' );
            } else {
                $terms = array_map( 'absint', $menu_data['mega_menu_post']['terms'] );
            }

            $ts = get_terms( array(
                'taxonomy' => $menu_data['mega_menu_post']['tax'],
                'include' => $terms,
                'orderby' => 'include',
            ) );

            $menu_data['mega_menu_post']['terms'] = $ts;
        }


		wp_send_json_success( $menu_data );
		die();
	}

	/**
	 * Get data for previewing items
	 *
	 * @param $menu_data
	 * @param $menu_id
	 * @return mixed
	 */
	function setup_mega_menu_data( $menu_data, $menu_id ) {

		$keys = $this->get_mega_keys();
		$setting_id = 'nav_menu_item['.$menu_id.']';
		$is_object = false;

		if ( is_object( $menu_data ) ) {
			$is_object = true;
			foreach ( $keys as $key => $meta_key ) {
				$is_previewing =  false;
				$key_check =  array( $setting_id, $key );
				if ( MegaMenu_WP::is_preview( $key_check ) ) {
					$is_previewing = true;
					$menu_data->{ $key } = MegaMenu_WP::get_previewing_data( $key_check );
				}

				if ( ! $is_previewing ) {
					$menu_data->{ $key } = get_post_meta( $menu_id, $meta_key, true );
				}

			}

			if ( $menu_data->mega_enable ) {
				$menu_data->has_children = 1;
			}

		} else {
			foreach ( $keys as $key => $meta_key ) {

				$is_previewing =  false;
				$key_check =  array( $setting_id, $key );
				if ( MegaMenu_WP::is_preview( $key_check ) ) {
					$is_previewing = true;
					$menu_data[ $key ] = MegaMenu_WP::get_previewing_data( $key_check );
				}

				if ( ! $is_previewing ) {
					$menu_data[ $key ] = get_post_meta( $menu_id, $meta_key, true );
				}
			}

			if ( $menu_data['mega_enable'] ) {
				$menu_data['has_children'] = 1;
			}

		}

		if ( $is_object ) {
			if ( isset( $menu_data->mega_menu_post['terms']  ) && ! empty( $menu_data->mega_menu_post['terms'] )  && $menu_data->mega_menu_post['tax'] ) {
				$terms = array();
				$_t = current( $menu_data->mega_menu_post['terms'] );
				reset( $menu_data->mega_menu_post['terms'] );
				if ( is_object( $_t ) ) {
					$terms = wp_list_pluck( $menu_data->mega_menu_post['terms'], 'term_id' );
				} else {
					$terms = array_map( 'absint', $menu_data->mega_menu_post['terms'] );
				}

				$ts = get_terms( array(
					'taxonomy' => $menu_data->mega_menu_post['tax'],
					'include' => $terms,
					'orderby' => 'include',
				) );

				$menu_data->mega_menu_post['terms'] = $ts;
			}
		} else {
			if ( isset( $menu_data['mega_menu_post']['terms'] ) && ! empty( $menu_data['mega_menu_post']['terms']  ) && $menu_data['mega_menu_post']['tax'] ) {

				$terms = array();
				$_t = current( $menu_data['mega_menu_post']['terms'] );
				reset( $menu_data['mega_menu_post']['terms'] );
				if ( is_object( $_t ) ) {
					$terms = wp_list_pluck( $menu_data['mega_menu_post']['terms'], 'term_id' );
				} else {
					$terms = array_map( 'absint', $menu_data['mega_menu_post']['terms'] );
				}

				$ts = get_terms( array(
					'taxonomy' => $menu_data['mega_menu_post']['tax'],
					'include' => $terms,
					'orderby' => 'include',
				) );

				$menu_data['mega_menu_post']['terms'] = $ts;
			}
		}

		return $menu_data;

	}


	/**
	 * Filter data for menu items data
	 *
	 * @param $menu
	 * @return mixed
	 */
	function filter_nav_menu_item( $menu ){
		$menu->_id = $menu->db_id;
		$menu = $this->setup_mega_menu_data( $menu, $menu->db_id );
		return $menu;
	}

	/**
	 * This load item settings when update menu data
	 */
	function ajax_load_item_mega(){
		$menu_id = absint( $_REQUEST['menu_id'] );
		wp_send_json_success( $this->setup_mega_menu_data( array(), $menu_id )  );
		die();
	}

	function ajax_load_widget(){
		$id_base = $_POST['widget_id'];
		$form_id = $_POST['form_id'];
		$data = $_POST['settings'];
		global $wp_widget_factory;
		//new WP_Widget_Factory();

		ob_start();
		foreach ( $wp_widget_factory->widgets as $widget_class => $settings ) {
			if ( $settings->id_base == $id_base ) {
				if ( class_exists( $widget_class ) ) {
					$widget = new $widget_class;
					$widget->number = $form_id;
					$data = wp_unslash( $data );
					$widget->form( $data );
				}
			}
		}

		$form = ob_get_clean();
		wp_send_json_success( $form );
		die( '' );
	}

	public function amend_customize_save_response( $data, $settings )
	{
		if ( ! isset( $_POST['customized'] ) ) {
			return $data;
		}
		$customized = json_decode( wp_unslash( $_POST['customized'] ), true );

		if ( isset( $data['nav_menu_item_updates'] ) ) {

			foreach ( $data['nav_menu_item_updates'] as $d ) {

				if ( $d['status'] != 'error' ) {
					$menu_id = $d['post_id'];
					if ($d['previous_post_id'] && $d['previous_post_id'] != $d['post_id']) {
						$menu_id = $d['previous_post_id'];
					}
					$post_data_name = sprintf('nav_menu_item[%s]', $menu_id );
					$item_data = array();
					if ( isset( $customized[ $post_data_name ] ) ) {
						$item_data = $customized[ $post_data_name ];
					}
					foreach ( $this->get_mega_keys() as $key => $meta_key ) {
						$submit_data = false;
						if ( isset( $item_data[ $key ] ) ) {
							$submit_data = $item_data[ $key ];
						}
						update_post_meta( $d['post_id'], $meta_key, $submit_data );
					}

				}

			}

		}

		if ( isset( $data['nav_menu_updates'] ) && ! empty( $data['nav_menu_updates'] ) ) {
			foreach ( $data['nav_menu_updates'] as $menu_index => $menu ) {

				if ( $menu['status'] != 'error' ) {
					$menu_id = $menu['term_id'];
					if ( $menu['previous_term_id'] && $menu['previous_term_id'] != $menu['term_id']) {
						$menu_id = $menu['previous_term_id'];
					}

					$post_data_name = sprintf('nav_menu[%s]', $menu_id);
					$item_data = array();
					if (isset($customized[ $post_data_name ])) {
						$item_data = $customized[ $post_data_name ];
					}
					if ( ! isset( $menu['saved_value'] ) ) {
						$menu['saved_value'] = array();
					}
					foreach ( $this->get_term_keys() as $key => $meta_key ) {
						$submit_data = false;
						if ( isset( $item_data[ $key ] ) ) {
							$submit_data = $item_data[ $key ];
						}
						update_term_meta( $menu['term_id'], $meta_key, $submit_data );
						$data[ 'nav_menu_updates' ][ $menu_index ]['saved_value'][ $key ] = $submit_data;
					}

				}

			}

		}

		return $data;

	}


	function customize_enqueue(){
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'jquery-ui-draggable' );
		wp_enqueue_media();

		wp_enqueue_script( 'megamenu-wp-admin', MAGAZINE_MEGA_MENU_URL.'assets/js/megamenu-wp-customizer.js',array( 'jquery', 'customize-controls' ), false, true );
		wp_enqueue_script( 'megamenu-wp-media', MAGAZINE_MEGA_MENU_URL.'assets/js/media.js',array( 'jquery', 'customize-controls' ), false, true );
		wp_enqueue_style( 'megamenu-wp-admin', MAGAZINE_MEGA_MENU_URL.'assets/css/customizer.css' );

		$mega_active = array();
		$menus = get_terms( array(
			'taxonomy' => 'nav_menu'
		) );
		if ( $menus && ! is_wp_error( $menus ) ) {
			foreach ( $menus  as $menu ){
				$mega_active[ $menu->term_id ] = get_term_meta( $menu->term_id, '_mega_enable', true);
			}
		}

		$post_types = get_post_types( array(
			'public' => true,
		), 'object' );

		$data_posts = array();

		foreach ( $post_types  as $post_type ) {
			$data_posts[ $post_type->name ] = array(
				'name' => $post_type->name,
				'label' => $post_type->label,
				'taxs' => array(),
			);

			$taxs = get_object_taxonomies( $post_type->name, 'objects' );
			foreach ( $taxs as $t ) {
				$tdata = array(
					'name' => $t->name,
					'label' => $t->label,
				);
				$data_posts[$post_type->name]['taxs'][ $t->name ] = $tdata;
			}
		}

		$args =  array(
			'single_col' => esc_html__( '1 Column', 'megamenu-wp' ),
			'plural_col' => esc_html__( '%s Columns', 'megamenu-wp' ),
			'mega' => esc_html__( 'Mega Activated', 'megamenu-wp' ),
			'mega_settings_label' => esc_html__( 'Mega Menu Settings', 'megamenu-wp' ),
			'enable_mega' => esc_html__( 'Enable Mega Menu', 'megamenu-wp' ),
			'mega_menus' => $mega_active,
			'posts' => $data_posts,
			'_nonce' => wp_create_nonce( 'megamenuwp_nonce' ),
		);

	
			$args['limit_post_type_msg'] = __( 'Please upgrade to <a target="_blank" href="'.esc_url( MegaMenu_WP::get_pro_url() ).'">PRO version</a> to unlock this feature.', 'megamenu-wp' );
			$args['limit_widget_msg'] = __( 'Please upgrade to <a target="_blank" href="'.esc_url( MegaMenu_WP::get_pro_url() ).'">PRO version</a> to add widgets.', 'megamenu-wp' );
			$args['limit_number_msg'] = __( 'Please upgrade to <a target="_blank" href="'.esc_url( MegaMenu_WP::get_pro_url() ).'">PRO version</a> to add more items.', 'megamenu-wp' );
		

		wp_localize_script( 'megamenu-wp-admin', 'megamenuSettings', $args );


	}

	function get_taxonomies(){
		return get_taxonomies(  array( 'object_type' => array( 'post' ) ), 'objects' );
	}


	/**
	 * @see Walker_Nav_Menu_Edit
	 * @param $hook
	 */
	function item_settings_tpl(  ){
		// $taxs =  $this->get_taxonomies();
		?>
		<script type="text/html" id="megamenu-wp-col-tpl">
			<div data-col="4" class="col mega-col-widgets">
				<div class="col-inner">
					<div class="col-actions action-top">
						<span data-act="left" class="col-resize dashicons dashicons-arrow-left"></span>
						<span data-act="right" class="col-resize dashicons dashicons-arrow-right"></span>
						<div class="heading-input"><input type="text" class="column-heading" value="{{ data.heading }}" placeholder="<?php esc_attr_e( 'Column heading', 'megamenu-wp' ); ?>"></div>
					</div>
					<div class="sortable"></div>
				</div>
			   <div class="add-actions action-footer">
				   <a href="#" class="add-menu"><?php esc_html_e( 'Add menu', 'megamenu-wp' ); ?></a>
				   <a href="#" class="add-item"><?php
					  
						   esc_html_e( 'Add widget (Pro)', 'megamenu-wp' );
					   
					   ?></a>
			   </div>
			</div>
		</script>


		<script type="text/html" id="mm-item-settings-tpl">
			<div class="megamenu-wp" id="megamenu-id-{{ data.menu_id }}">
				<div class="megamenu-wp-inner">

					<div class="megamenu-drag"></div>

					<a href="#" class="close-mega-panel"><span class="dashicons dashicons-no-alt"></span></a>
					<ul class="megamenu-tabs">
						<li class="editing-current-menu panel-heading"><?php esc_html_e( 'Editing', 'megamenu-wp' ); ?> <a class="mega-open-control" href="#">{{ data.title }}</a></li>
						<li data-tab="settings" class="active"><?php esc_html_e( 'Settings', 'megamenu-wp' ); ?></li>
						<li data-tab="post" class="setting-tab <# if ( data.settings.menu_type != 'post' ) { #> hide <# } #>" <# if ( data.settings.enable != 1 ) { #> style="display: none;" <# } #> ><?php esc_html_e( 'Content Grid Mega Menu', 'megamenu-wp' ); ?></li>
						<li data-tab="layout" class="setting-tab <# if ( data.settings.menu_type != 'layout' ) { #> hide <# } #>" <# if ( data.settings.enable != 1 ) { #> style="display: none;" <# } #>><?php esc_html_e( 'Columnize Mega Menu Content', 'megamenu-wp' ); ?></li>
						<li data-tab="style" class="setting-tab" <# if ( data.settings.enable != 1 ) { #> style="display: none;" <# } #>><?php esc_html_e( 'Styling', 'megamenu-wp' ); ?></li>
						<li class="no-action panel-live-view" title="<?php esc_attr_e( 'Toggle Preview', 'megamenu-wp' ); ?>"> <span class="live-preview-toggle-icon"><span><?php esc_html_e( 'Live Preview', 'megamenu-wp' ) ?></span></span> </li>
					</ul>
					<div class="megamenu-contents">
						<div class="megamenu-contents-inner">
							<div class="megamenu-content tab-settings active">
								<form class="mega-form mega-settings-form">

									<div class="field enable-field">
										<div class="field_label">
											<label for="enable">
												<span><?php esc_html_e( 'Enable Mega Menu', 'megamenu-wp' ); ?></span>
											</label>
											<p class="field_desc">Check to enable Mega Menu for this menu item</p>

										</div>
										<div class="field_input">
											<input id="enable" type="checkbox" <# if ( data.settings.enable == 1 ) { #> checked="checked" <# } #> name="enable" value="1">
										</div>
									</div>

									<div class="field">
										<div class="field_label">
											<label for="menu_type">
												<span><?php esc_html_e( 'Mega Menu Type', 'megamenu-wp' ); ?></span>
											</label>
										</div>
										<div class="field_input">
												<select id="menu_type" name="menu_type">
													<option value="layout"><?php esc_html_e( 'Columnize Mega Menu', 'megamenu-wp' ); ?></option>
													<option <# if ( data.settings.menu_type == 'post' ) { #> selected="selected" <# } #> value="post"><?php esc_html_e( 'Content Grid Mega Menu', 'megamenu-wp' ); ?></option>
												</select>
										</div>
									</div>

									<div class="field">
										<div class="field_label">
											<label for="layout">
												<span class="label"><?php esc_html_e( 'Mega Menu Layout', 'megamenu-wp' ); ?></span>
											</label>
										</div>
										<div class="field_input">
											<select id="layout" name="layout">
												<option <# if ( data.settings.layout == 'boxed' ) { #> selected="selected" <# } #> value="boxed"><?php esc_html_e( 'Boxed', 'megamenu-wp' ); ?></option>
														<option <# if ( data.settings.layout == 'full' ) { #> selected="selected" <# } #> value="full"><?php esc_html_e( 'Full width', 'megamenu-wp' ); ?></option>
											</select>
										</div>
									</div>

									<div class="field">
										<div class="field_label">
											<label for="content_layout">
												<span class="label"><?php esc_html_e( 'Mega Content Layout', 'megamenu-wp' ); ?></span>
											</label>
										</div>
										<div class="field_input">
											<select id="content_layout" name="content_layout">
												<option <# if ( data.settings.content_layout == 'boxed' ) { #> selected="selected" <# } #> value="boxed"><?php esc_html_e( 'Boxed', 'megamenu-wp' ); ?></option>
														<option <# if ( data.settings.content_layout == 'full' ) { #> selected="selected" <# } #> value="full"><?php esc_html_e( 'Full width', 'megamenu-wp' ); ?></option>
											</select>
										</div>
									</div>


									<div class="field">
										<div class="field_label">
											<label for="column_heading">
												<span class="label"><?php esc_html_e( 'Column heading', 'megamenu-wp' ); ?></span>
											</label>
										</div>
										<div class="field_input">
											<select id="column_heading" name="column_heading">
												<option <# if ( data.settings.column_heading == 'always_show' ) { #> selected="selected" <# } #> value="always_show"><?php esc_html_e( 'Always show', 'megamenu-wp' ); ?></option>
                                                <option <# if ( data.settings.column_heading == 'hide_on_mobile' ) { #> selected="selected" <# } #> value="hide_on_mobile"><?php esc_html_e( 'Only show on mobile mod', 'megamenu-wp' ); ?></option>
											</select>
										</div>
									</div>

									<div class="field">
										<div class="field_label">
											<label for="content_width">
												<span class="label"><?php esc_html_e( 'Content Width', 'megamenu-wp' ); ?></span>
											</label>
										</div>
										<div class="field_input">
											<input id="content_width" type="text" name="content_width" value="{{ data.settings.content_width }}">
										</div>
									</div>

									<div class="field">
										<div class="field_label">
											<label for="content_position">
												<span class="label"><?php esc_html_e( 'Content Align', 'megamenu-wp' ); ?></span>
											</label>
										</div>
										<div class="field_input">
											<select id="content_position" name="content_position">
												<option <# if ( data.settings.content_position == 'left' ) { #> selected="selected" <# } #> value="left"><?php esc_html_e( 'Left', 'megamenu-wp' ); ?></option>
												<option <# if ( data.settings.content_position == 'right' ) { #> selected="selected" <# } #> value="right"><?php esc_html_e( 'Right', 'megamenu-wp' ); ?></option>
												<option <# if ( data.settings.content_position == 'center' ) { #> selected="selected" <# } #> value="center"><?php esc_html_e( 'Center', 'megamenu-wp' ); ?></option>
											</select>
										</div>
									</div>
								</form>
							</div>

							<div class="megamenu-content tab-post ">
								<form class="mega-form mega-post-form">
									<input type="hidden" name="cats">

									<div class="field post_types">
										<div class="field_label">
											<label for="post_type">
												<span class="label"><?php esc_html_e( 'Content source', 'megamenu-wp' ); ?></span>
											</label>
										</div>
										<div class="field_input">
											<div class="post_type field-setting">
												<?php
												$class = 'post_type';
											
												?>
												<select id="post_type" class="<?php echo esc_attr( $class ); ?>" name="post_type">
													<#
                                                    if ( ! data.postSettings.post_type ) {
                                                        data.postSettings.post_type = 'post';
                                                    }
                                                    #>
                                                    <?php
                                                   
                                                        ?>
                                                            <#  _.each( megamenuSettings.posts, function( post ) {  #>
                                                            <option <# if ( 'post' != post.name ) { #> disabled="disabled" <# } #> <# if ( data.postSettings.post_type == post.name ) { #> selected="selected" <# } #> value="{{ post.name }}">{{ post.label }} <# if ( 'post' != post.name ) { #> <?php echo _e( '(Pro only)', 'megamenu-wp' ); ?> <# } #></option>
                                                            <# } ); #>
                                                        <?php
                                                    

                                                    ?>

												</select>


											</div>
										</div>


									</div>


									<div class="field tax-type">
										<div class="field_label">
											<label for="tax">
												<span class="label"><?php esc_html_e( 'Taxomomy', 'megamenu-wp' ); ?></span>
											</label>
										</div>
										<div class="field_input">
											<select id="tax" class="dynamic-tax" name="tax">
											</select>
										</div>
									</div>

									<div class="field cats">
										<div class="field_label">
											<label for="cat">
												<span class="label"><?php esc_html_e( 'Terms', 'megamenu-wp' ); ?></span>
											</label>
										</div>
										<div class="field_input">
											<div class="repeatable-cat field-setting">
												<div class="list-sortable"></div>
												<select id="cat" name="cat" class="select-terms list-cate-tpl no-change"></select>
												<a href="#" class="add-item-cat"><?php esc_html_e( 'Add', 'megamenu-wp' ); ?></a>
											</div>
										</div>

									</div>

                                    <div class="field">
                                        <div class="field_label">
                                            <label for="tabs_layout">
                                                <span class="label"><?php esc_html_e( 'Tab Layout', 'megamenu-wp' ); ?></span>
                                            </label>
                                            <p class="field_desc"><?php esc_html_e( 'Display tab layout from Taxomomy you add above.', 'megamenu-wp' ); ?></p>
                                        </div>
                                        <div class="field_input">
                                            <select id="tabs_layout" name="tabs_layout">
                                                <option <# if ( data.postSettings.tabs_layout == 'left' ) { #> selected="selected" <# } #> value="left"><?php esc_html_e( 'Tabs Left', 'megamenu-wp' ); ?></option>
                                                <option <# if ( data.postSettings.tabs_layout == 'right' ) { #> selected="selected" <# } #> value="right"><?php esc_html_e( 'Tabs Right', 'megamenu-wp' ); ?></option>
                                                <option <# if ( data.postSettings.tabs_layout == 'no-tabs' ) { #> selected="selected" <# } #> value="no-tabs"><?php esc_html_e( 'No Tabs', 'megamenu-wp' ); ?></option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="field show_all_link" style="display: none;">
                                        <div class="field_label">
                                            <label for="show_all_link">
                                                <span class="label"><?php esc_html_e( 'Show all content tab', 'megamenu-wp' ); ?></span>
                                            </label>
                                        </div>
                                        <div class="field_input">
                                            <select id="show_all_link" name="show_all_link">
                                                <option <# if ( data.postSettings.show_all_link == 'no' ) { #> selected="selected" <# } #> value="no"><?php esc_html_e( 'No', 'megamenu-wp' ); ?></option>
                                                <option <# if ( data.postSettings.show_all_link == 'top' ) { #> selected="selected" <# } #> value="top"><?php esc_html_e( 'Show on the top of tabs', 'megamenu-wp' ); ?></option>
                                                <option <# if ( data.postSettings.show_all_link == 'bottom' ) { #> selected="selected" <# } #> value="bottom"><?php esc_html_e( 'Show at the bottom of tabs', 'megamenu-wp' ); ?></option>
                                            </select>
                                        </div>
                                        <label>
                                        </label>
                                    </div>

                                    <div class="field all_link_more_settings" style="display: none;">
                                        <div class="field_label">
                                            <label for="all_item_text">
                                                <span class="label"><?php esc_html_e( 'Show all tab: text', 'megamenu-wp' ); ?></span>
                                            </label>
                                        </div>
                                        <div class="field_input">
                                            <input id="all_item_text" name="all_item_text" value="{{ data.postSettings.all_item_text }}" type="text">
                                        </div>
                                    </div>

                                    <div class="field all_link_more_settings" style="display: none;">
                                        <div class="field_label">
                                            <label for="all_item_link">
                                                <span class="label"><?php esc_html_e( 'Show all tab: link', 'megamenu-wp' ); ?></span>
                                            </label>
                                        </div>
                                        <div class="field_input">
                                            <input id="all_item_link" name="all_item_link" value="{{ data.postSettings.all_item_link }}" type="text">
                                        </div>
                                    </div>

									<div class="field">
										<div class="field_label">
											<label for="columns">
												<span class="label"><?php esc_html_e( 'Content Grid Column', 'megamenu-wp' ); ?></span>
											</label>
										</div>
										<div class="field_input">
											<select id="columns" name="columns">
												<option  value=""><?php esc_html_e( 'Default', 'megamenu-wp' ); ?></option>
												<option <# if ( data.postSettings.columns == 2 ) { #> selected="selected" <# } #> value="2"><?php esc_html_e( '2 Columns', 'megamenu-wp' ); ?></option>
														<option <# if ( data.postSettings.columns == 3 ) { #> selected="selected" <# } #> value="3"><?php esc_html_e( '3 Columns', 'megamenu-wp' ); ?></option>
																<option <# if ( data.postSettings.columns == 4 ) { #> selected="selected" <# } #> value="4"><?php esc_html_e( '4 Columns', 'megamenu-wp' ); ?></option>
																		<option <# if ( data.postSettings.columns == 6 ) { #> selected="selected" <# } #> value="6"><?php esc_html_e( '6 Columns', 'megamenu-wp' ); ?></option>
											</select>
										</div>
									</div>

									<div class="field">
										<div class="field_label">
											<label for="posts_per_page">
												<span class="label"><?php esc_html_e( 'Number post to show', 'megamenu-wp' ); ?></span>
											</label>
										</div>
										<div class="field_input">
											<input id="posts_per_page" name="posts_per_page" value="{{ data.postSettings.posts_per_page }}"  type="text">
										</div>
									</div>

									<div class="field">
										<div class="field_label">
											<label for="post__in">
												<span class="label"><?php esc_html_e( 'Include Post', 'megamenu-wp' ); ?></span>
											</label>
											<p class="field_desc">
												<?php esc_html_e( 'Post ids, separated by commas', 'megamenu-wp' ); ?>
											</p>
										</div>
										<div class="field_input">
											<input id="post__in" name="post__in" value="{{ data.postSettings.post__in }}" type="text">
										</div>
									</div>

									<div class="field">
										<div class="field_label">
											<label for="post__not_in">
												<span class="label"><?php esc_html_e( 'Exclude Post', 'megamenu-wp' ); ?></span>
											</label>
											<p class="field_desc">
												<?php esc_html_e( 'Post ids, separated by commas', 'megamenu-wp' ); ?>
											</p>
										</div>
										<div class="field_input">
											<input id="post__not_in" name="post__not_in" value="{{ data.postSettings.post__not_in }}" type="text">
										</div>
										<label>



										</label>
									</div>

									<div class="field">
										<div class="field_label">
											<label for="orderby">
												<span class="label"><?php esc_html_e( 'Order By', 'megamenu-wp' ); ?></span>
											</label>
										</div>
										<div class="field_input">
											<select id="orderby" name="orderby">
												<option <# if ( data.postSettings.orderby == 'default' ) { #> selected="selected" <# } #> value=""><?php esc_html_e( 'Default', 'megamenu-wp' ); ?></option>
														<option <# if ( data.postSettings.orderby == 'title' ) { #> selected="selected" <# } #> value="title"><?php esc_html_e( 'Title', 'megamenu-wp' ); ?></option>
																<option <# if ( data.postSettings.orderby == 'date' ) { #> selected="selected" <# } #> value="date"><?php esc_html_e( 'Date', 'megamenu-wp' ); ?></option>
																		<option <# if ( data.postSettings.orderby == 'rand' ) { #> selected="selected" <# } #> value="rand"><?php esc_html_e( 'Rand', 'megamenu-wp' ); ?></option>
																				<option <# if ( data.postSettings.orderby == 'comment_count' ) { #> selected="selected" <# } #> value="comment_count"><?php esc_html_e( 'Comment count', 'megamenu-wp' ); ?></option>
																						<option <# if ( data.postSettings.orderby == 'post__in' ) { #> selected="selected" <# } #> value="post__in"><?php esc_html_e( 'Post inlcude', 'megamenu-wp' ); ?></option>
											</select>
										</div>
									</div>

									<div class="field">
										<div class="field_label">
											<label for="order">
												<span class="label"><?php esc_html_e( 'Order', 'megamenu-wp' ); ?></span>
											</label>
										</div>
										<div class="field_input">
											<select id="order" name="order">
												<option <# if ( data.postSettings.order == 'default' ) { #> selected="selected" <# } #> value=""><?php esc_html_e( 'Default', 'megamenu-wp' ); ?></option>
														<option <# if ( data.postSettings.order == 'asc' ) { #> selected="selected" <# } #> value="asc"><?php esc_html_e( 'ASC', 'megamenu-wp' ); ?></option>
																<option <# if ( data.postSettings.order == 'desc' ) { #> selected="selected" <# } #> value="desc"><?php esc_html_e( 'DESC', 'megamenu-wp' ); ?></option>
											</select>
										</div>
									</div>



									<div class="field">
										<div class="field_label">
											<label for="show_when">
												<span class="label"><?php esc_html_e( 'Show Posts When', 'megamenu-wp' ); ?></span>
											</label>
										</div>
										<div class="field_input">
											<select id="show_when" name="show_when">
												<option <# if ( data.postSettings.show_when == 'left' ) { #> selected="selected" <# } #> value="hover"><?php esc_html_e( 'Hover', 'megamenu-wp' ); ?></option>
														<option <# if ( data.postSettings.show_when == 'right' ) { #> selected="selected" <# } #> value="click"><?php esc_html_e( 'Click', 'megamenu-wp' ); ?></option>
											</select>
										</div>
									</div>
								</form>
							</div>

							<div class="megamenu-content tab-layout">

								<div class="megamenu-layout-area">
									<div class="row" data-col="3">
										<ul class="row-actions">
											<li class="num-col">
												<span data-act="decrement" class="col-change dashicons dashicons-arrow-left"></span>
												<span class="action-label"></span>
												<span data-act="increment" class="col-change dashicons dashicons-arrow-right"></span>
											</li>
										</ul>
										<div class="row-inner">
										</div>
									</div>
								</div>
							</div><!-- /.tab-layout -->


							<div class="megamenu-content tab-style ">
								<form class="mega-form mega-style-form">
									<?php
										?>
										<div class="megamenu-wp-msg">
											<?php printf( __( 'This feature only available for %1$s', 'megamenu-wp' ), '<a target="_blank" href="'.esc_url( MegaMenu_WP::get_pro_url() ).'">'.__( 'Pro version' ).'</a>' ); ?>
										</div>
										<?php
									
									?>
								</form>
							</div>


						</div>
					</div>
				</div>
			</div>
		</script>

		<script type="text/html" id="megamenu-wp-settings">

			<div class="customize-control">
				<span class="customize-control-title"><?php esc_html_e( 'Mega Menu Settings', 'megamenu-wp' ); ?></span>
			</div>

			<p style="margin: 10px 0px 12px 24px;" class="customize-control customize-control-checkbox mega-menu-settings">
				<label>
					<input class="menu-mega-enable" value="1" <# if ( megamenuSettings.mega_menus[ data.menu_id ] == 1 ) { #> checked="checked" <#  } #> data-setting-name="mega_enable" type="checkbox">
					<span class="field-label"><?php esc_html_e( 'Activate mega menu features', 'megamenu-wp' ); ?></span>
				</label>
			</p>

		</script>
		<?php
	}


}

new Magazine_Mega_Menu_WP_Admin();
