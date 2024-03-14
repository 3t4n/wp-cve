<?php

if ( ! class_exists( 'ContentAd__Includes__Post_Type' ) ) {

	class ContentAd__Includes__Post_Type {

		function __construct() {
			add_action( 'init', array( $this, 'content_ad_post_type' ) );
			add_action( 'save_post', array( $this, 'save_post' ) );
		}

		function content_ad_post_type() {
			global $wp_version;
			$singular = __( 'Widget', 'contentad' );
			$plural = __( 'Widgets', 'contentad' );
			register_post_type( 'content_ad_widget', array(
				'public' => true,
				'label' => $plural,
				'labels' => array(
					'name' => $plural,
					'singular_name' => $singular,
					'add_new' => sprintf( __( 'Add New %s', 'contentad' ), $singular ),
					'add_new_item' => sprintf( __( 'Add New %s', 'contentad' ), $singular ),
					'edit_item' => sprintf( __( 'Edit %s', 'contentad' ), $singular ),
					'new_item' => sprintf( __( 'New %s', 'contentad' ), $singular ),
					'view_item' => sprintf( __( 'View %s', 'contentad' ), $singular ),
					'search_items' => sprintf( __( 'Search %s', 'contentad' ), $plural ),
					'not_found' => sprintf( __( 'No %s found', 'contentad' ), strtolower( $plural ) ),
					'not_found_in_trash' => sprintf( __( 'No %s found in trash', 'contentad' ), strtolower( $plural ) ),
				),
				'exclude_from_search' => 'true',
				'show_ui' => true,
				'show_in_menu' => CONTENTAD_SLUG,
				'show_in_nav_menus' => false,
				'menu_icon' => plugins_url( 'images/', CONTENTAD_FILE ).'ca_icon.png',
				'can_export' => false,
				'rewrite' => false,
				'supports'	=> array( 'title' ),
			) );

			remove_post_type_support( 'content_ad_widget', 'revisions' );
			remove_post_type_support( 'content_ad_widget', 'comments' );

			add_filter( 'manage_edit-content_ad_widget_columns', array( $this, 'manage_edit_content_ad_widget_columns' ) );
			if( version_compare( $wp_version, '3.1', '<' ) ) {
				add_filter( 'manage_content_ad_widget_posts_columns', array( $this, 'legacy_add_custom_columns' ) );
				add_action( 'manage_posts_custom_column', array( $this, 'manage_content_ad_widget_posts_custom_column' ), 10, 2 );
			} else {
				// Old WP 3.0 method for adding custom column content
				add_filter( 'manage_content_ad_widget_posts_custom_column', array( $this, 'manage_content_ad_widget_posts_custom_column' ), 10, 2 );
			}
			
			add_action( 'quick_edit_custom_box', array( $this, 'quick_edit_custom_box' ), 10, 3 );
			
			// Upgrade Content.ad by plugin version number
			$previous_version = get_option( 'contentad_version' );
			if( $previous_version != CONTENTAD_VERSION ) {
				update_option( 'contentad_version', CONTENTAD_VERSION );
			}
		}

		function legacy_add_custom_columns( $posts_columns ) {
			return $this->manage_edit_content_ad_widget_columns( $posts_columns );
		}

		function manage_edit_content_ad_widget_columns( $columns ) {
			$columns = array(
				'cb'			=> '<input type="checkbox" />',
				'widget_title'	=>	__( 'Name', 'contentad' ),
				'placement'		=>	__( 'WordPress Placement', 'contentad' ),
				'exc_cats'		=>  __( 'Excluded Categories', 'contentad' ),
				'exc_tags'		=>  __( 'Excluded Tags', 'contentad' ),
				'last_edited'	=>	__( 'Last Edited', 'contentad' ),
				'widget_stats'  =>  __( 'Reports', 'contentad' ),
				'widget_active' =>  __( 'Active', 'contentad' ),
			);
			return $columns;
		}

		function manage_content_ad_widget_posts_custom_column( $column_name, $post_id ) {
			global $wp_version;
			$post = get_post( $post_id );
			switch ( $column_name ) {
				case 'widget_title':
					$edit_widget_query = http_build_query( array(
						'wid' => get_post_meta( $post_id, '_widget_guid', true ),
						'aid' => ContentAd__Includes__API::get_api_key(),
						'installKey' => ContentAd__Includes__API::get_installation_key(),
						'TB_iframe' => 'true',
					) );
					
					$edit_widget_url = CONTENTAD_REMOTE_URL . 'Publisher/Widgets/Edit?' . $edit_widget_query;

					//Recreates WP_Posts_Lists_Table::single_row() case 'title', also replacing WP_List_Table::row_actions(), and get_inline_data
					echo '<strong><a class="row-title thickbox" href="' . $edit_widget_url . '" title="' . esc_attr( sprintf( __( 'Edit Placement of %s', 'contentad'  ), $post->post_title ) ) . '">' . $post->post_title . '</a></strong>';
					_post_states( $post );

					$actions['edit-ad-widget hide-if-no-js'] = '<a href="'.$edit_widget_url.'" class="thickbox" title="' . esc_attr( __( 'Edit this widget', 'contentad'  ) ) . '">' . __( 'Edit', 'contentad' ) . '</a>';
					$actions['edit-wp-placement inline hide-if-no-js'] = '<a href="#" class="editinline" title="' . esc_attr( __( 'Edit this widget\'s placement', 'contentad'  ) ) . '">' . __( 'Placement', 'contentad' ) . '</a>';

					if( get_post_meta( $post_id, '_ca_widget_inactive', true ) ) {
						$text = __( 'Activate this widget', 'contentad' );
						$actions['activate'] = "<a href=\"#\" class=\"paused toggle-status\" title=\"{$text}\" data-postid=\"{$post->ID}\">{$text}</a>";
					} else {
						$text = __( 'Pause this widget', 'contentad' );
						$actions['pause'] = "<a href=\"#\" class=\"active toggle-status\" title=\"{$text}\" data-postid=\"{$post->ID}\">{$text}</a>";
					}

					$actions['trash'] = '<a class="submitdelete" title="' . esc_attr( __( 'Delete this widget', 'contentad' ) ) . '" href="#" data-postid="' . $post_id . '">' . __( 'Delete', 'contentad' ) . '</a><div id="deleteConfirmation_' .$post_id . '"><div class="delete-confirmation"><p>Are you sure you want to delete this widget?</p><p><strong>' . esc_attr( sprintf( __( ' %s', 'contentad'  ), $post->post_title ) ) . '</strong></p><p><button class="cad-delete" data-postid="' . $post_id . '">Delete</button><button class="cad-cancel">Cancel</button></p></div></div>';
					$always_visible = false;
					$action_count = count( $actions );
					$i = 0;

					if ( !$action_count ) {} else {
						$out = '<div class="' . ( $always_visible ? 'row-actions-visible' : 'row-actions' ) . '">';
						foreach ( $actions as $action => $link ) {
							++$i;
							( $i == $action_count ) ? $sep = '' : $sep = ' | ';
							$out .= "<span class='$action'>$link$sep</span>";
						}
						$out .= '<button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>';
						$out .= '</div>';
						echo $out;
					}

					$post_type_object = get_post_type_object($post->post_type);
					if ( ! current_user_can($post_type_object->cap->edit_post, $post->ID) )
							break;

					$title = trim( $post->post_title );

					$excluded_categories = get_post_meta( $post_id, '_excluded_categories', true );
					if( is_array( $excluded_categories ) ) {
						$excluded_categories = join( ',', $excluded_categories );
					}

					$jquery_handle = (version_compare($wp_version, '3.6-alpha1', '>=') ) ? 'jquery-core' : 'jquery';
					// Get the WP built-in version
					$wp_jquery_ver = $GLOBALS['wp_scripts']->registered[$jquery_handle]->ver;
					$jquery_ver_good = version_compare($wp_jquery_ver, '1.7', '>=');

					echo '
						<div class="hidden" id="inline_' . $post->ID . '">
						<div class="jquery_version_good">' . $jquery_ver_good . '</div>
						<div class="post_title">' . $title . '</div>
						<div class="post_name">' . $post->post_name . '</div>
						<div class="widget_id">' . get_post_meta( $post->ID, '_widget_id', true ) . '</div>
						<div class="exit_pop">' . get_post_meta( $post->ID, '_widget_exit_pop', true ) . '</div>
						<div class="mobile_exit_pop">' . get_post_meta( $post->ID, '_widget_mobile_exit_pop', true ) . '</div>
						<div class="placement">' . get_post_meta( $post->ID, 'placement', true ) . '</div>
						<div class="ca_display_home">' . get_post_meta( $post->ID, '_ca_display_home', true ) . '</div>
						<div class="ca_display_cat_tag">' . get_post_meta( $post->ID, '_ca_display_cat_tag', true ) . '</div>
						<div class="excluded_categories">' . $excluded_categories . '</div>
						<div class="excluded_tags">' . get_post_meta( $post_id, '_excluded_tags', true ) . '</div>';
					$exclude_tags = get_post_meta( $post->ID, 'exclude_tags' );
					if ( ! empty( $exclude_tags[0] ) ) {
						foreach ( $exclude_tags as $tag) {
							$tag = get_taxonomy( $tag );
							echo '<div class="tags_input" id="'.$tag.'_'.$post->ID.'">' . esc_html( str_replace( ',', ', ', get_terms_to_edit($post->ID, $tag) ) ) . '</div>';
						}
					}
					$exclude_cats = get_post_meta( $post->ID, 'exclude_cats' );
					if ( ! empty( $exclude_cats[0] ) ) {
						foreach ( $exclude_cats as $cat ) {
							echo '<div class="post_category" id="'.$cat.'_'.$post->ID.'">' . implode( ',', wp_get_object_terms( $post->ID, $cat, array('fields'=>'ids') ) ) . '</div>';
						}
					}
					echo '</div>';

					break;
				case 'last_edited':
					echo '<abbr title="' . $post->post_modified . ' UTC">' . $post->post_modified . ' UTC</abbr>';
					break;
				case 'placement':
					$possible_placements = array(
						'after_post_content' => __('After post content', 'contentad'),
						'before_post_content' => __('Before post content', 'contentad'),
						'in_shortcode' => __('In post shortcode', 'contentad'),
						'in_widget' => __('In widget', 'contentad'),
						'in_function' => __('In a template tag', 'contentad'),
						'popup_or_mobile_slidup' => __('Popup or mobile slideup', 'contentad'),
						'in_exit_pop' => __('Exit Pop', 'contentad'),
						'in_mobile_exit_pop' => __('Mobile Slideup widget', 'contentad')
					);
					$actual_placement = get_post_meta( $post_id, 'placement', true );
					if( isset( $possible_placements[$actual_placement] ) ) {
						echo $possible_placements[$actual_placement];
					}
					break;
				case 'exc_cats':
					$cats = get_post_meta( $post_id, '_excluded_categories', true );
					$categories = array();
					if( $cats && is_array( $cats ) ) {
						foreach( $cats as $cat ) {
							$term = get_cat_name( $cat );
							if( $term ) {
								$categories[] = $term;
							}
						}
						if( $categories ) {
							echo join( ', ', $categories );
							break;
						}
					}
					_e('No exclusions', 'contentad');
					break;
				case 'exc_tags':
					if( $tags = get_post_meta( $post_id, '_excluded_tags', true ) ) {
						echo $tags;
					} else {
						_e('No exclusions', 'contentad');
					}
					break;
				case 'widget_stats':
					$widget_report_query = http_build_query( array(
						'wid' => get_post_meta( $post_id, '_widget_guid', true ),
						'aid' => ContentAd__Includes__API::get_api_key(),
						'installKey' => ContentAd__Includes__API::get_installation_key(),
						'TB_iframe' => 'true',
					) );
					$widget_report_url = CONTENTAD_REMOTE_URL . "Publisher/AggregateReport?$widget_report_query";
					echo '<a href="'.$widget_report_url.'" class="thickbox">View</a>';
					break;
				case 'widget_active':
					if( get_post_meta( $post_id, '_ca_widget_inactive', true ) ) {
						echo '<span class="contentad-inactive-state paused toggle-status" data-postid="'.$post_id.'">Paused</span>';
					} else {
						echo '<span class="contentad-active-state active toggle-status" data-postid="'.$post_id.'">Active</span>';
					}
					break;
			}
		}

		function save_post( $post_id ) {
			if ( 'content_ad_widget' == get_post_type( $post_id )  ) {
				if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
					return $post_id;
				}
				if( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
					contentAd_append_to_log( 'AJAX "QUICK EDIT" SAVE' );
					if( isset( $_POST['action'] ) && isset( $_POST['screen'] ) && 'inline-save' == $_POST['action'] && 'edit-content_ad_widget' == $_POST['screen'] ) {

						// If Template Tag or Shortcode placements are selected, then all other placement options are reset since they may otherwise conflict with the user's custom placements
						if( isset( $_POST['placement'] ) && ( $_POST['placement'] == 'in_function' || $_POST['placement'] == 'in_shortcode' ) ) {
							$_POST['_ca_display_home'] = 1;
							$_POST['_ca_display_cat_tag']  = 1;
							unset($_POST['post_category']); $_POST['post_category'] = array();
							unset($_POST['post_tag']); $_POST['post_tag'] = array();
							
						}

						if ( isset( $_POST['_ca_display_home'] ) ) {
							contentAd_append_to_log( '    UPDATING _ca_display_home FOR POST ('.$post_id.') TO: ' . $_POST['_ca_display_home'] );
							update_post_meta( $post_id, '_ca_display_home', strip_tags( $_POST['_ca_display_home'] ) );
						} else {
							contentAd_append_to_log( '    UPDATING _ca_display_home FOR POST ('.$post_id.') TO: 0' );
							delete_post_meta( $post_id, '_ca_display_home' );
						}

						if ( isset( $_POST['_ca_display_cat_tag'] ) ) {
							contentAd_append_to_log( '    UPDATING _ca_display_cat_tag PLACEMENT FOR POST ('.$post_id.') TO: ' . $_POST['_ca_display_cat_tag'] );
							update_post_meta( $post_id, '_ca_display_cat_tag', strip_tags( $_POST['_ca_display_cat_tag'] ) );
						} else {
							contentAd_append_to_log( '    UPDATING _ca_display_cat_tag FOR POST ('.$post_id.') TO: 0' );
							delete_post_meta( $post_id, '_ca_display_cat_tag' );
						}

						if ( isset( $_POST['placement'] ) ) {
							contentAd_append_to_log( '    UPDATING PLACEMENT FOR POST ('.$post_id.') TO: ' . $_POST['placement'] );
							update_post_meta( $post_id, 'placement', strip_tags( $_POST['placement'] ) );
						}

						if( isset( $_POST['post_category'] ) && is_array( $_POST['post_category'] ) ) {
							foreach( $_POST['post_category'] as $key => $cat_id ) {
								if( empty( $cat_id ) ) {
									unset( $_POST['post_category'][$key] );
								} else {
									$_POST['post_category'][$key] = (int) $cat_id;
								}
							}
							contentAd_append_to_log( '    UPDATING EXCLUSION CATEGORIES FOR POST ('.$post_id.') TO: ' . join(', ', $_POST['post_category']) );
							update_post_meta( $post_id, '_excluded_categories', $_POST['post_category'] );
						}
						
						echo $_POST['post_tag'];

						if( isset( $_POST['post_tag'] ) ) {
							$tags = explode(',', preg_replace( '/, /', ',', strip_tags( implode(" ", $_POST['post_tag']) ) ));
							$terms = array();
							$terms = array_unique($terms);
							foreach( $tags as $tag ) {
								$term = get_term_by( 'name', $tag, 'post_tag' );
								if( $term ) {
									$terms[] = $term->name;
								} else {
									wp_insert_term( $tag, 'post_tag' );
									contentAd_append_to_log( '    ADDING NEW EXCLUSION TAGS: ' . join( ', ', $return ) );
									$terms[] = $tag;
									$new_terms[] = $tag;
								}
							}
							if($new_terms) {
								contentAd_append_to_log( '    ADDING NEW EXCLUSION TAGS: ' . join( ', ', $new_terms ) );
							}
							contentAd_append_to_log( '    UPDATING EXCLUSION TAGS FOR POST ('.$post_id.') TO: ' . join( ', ', $terms ) );
							update_post_meta( $post_id, '_excluded_tags', join( ', ', $terms ) );
						}
					}
				}
			}
		}

		function quick_edit_custom_box( $column_name, $post_type ) {
			if( 'content_ad_widget' != $post_type ) {
				return;
			}
			switch( $column_name ) {
				case 'widget_title':
					echo '<h4 class="contentad-widget-title"></h4>';
					break;
				case 'placement':
					$options = array(
                        'after_post_content' => __('After the post content', 'contentad'),
                        'before_post_content' => __('Before the post content', 'contentad'),
						'in_shortcode' => __('In post shortcode', 'contentad'),
						'in_widget' => __('In a widget (for use in a sidebar or footer)', 'contentad'),
						'in_function' => __('In a template tag', 'contentad'),
						'popup_or_mobile_slidup' => __('Popup or Mobile Slideup widget', 'contentad'),
						'in_exit_pop' => __('Exit Pop', 'contentad'),
						'in_mobile_exit_pop' => __('Mobile Slideup widget', 'contentad')
					); ?>
					<fieldset class="inline-edit-col-left inline-edit-placement">
						<div class="inline-edit-col">
							<span class="title inline-edit-placement-label"><?php _e('Place widgets', 'contentad') ?></span>
							<div><?php foreach( $options as $key => $value ): ?>
                            	<div class="option <?php esc_attr_e($key) ?>">
                                    <label for="<?php esc_attr_e($key) ?>">
                                        <input id="<?php esc_attr_e($key) ?>" type="radio" value="<?php esc_attr_e($key) ?>" name="placement" />
                                        &nbsp;<?php echo $value ?>
                                    </label><?php
									if( 'in_shortcode' == $key ) { ?>
										<div class="ca-indent-section section_in_shortcode">
											<input style="padding: 1em 1.5em;text-align:center;" type="text" value='[contentad widget=""]' size="30" />
											<p style="max-width: 400px;"><?php _e( 'Copy the shortcode above and paste it in your page or post. Your Content.Ad widgets will then load in your custom location.', 'contentad' ); ?></p>
										</div><?php
									}
									if( 'in_widget' == $key ) { ?>
										<div class="ca-indent-section section_in_widget">
										</div><?php
									}
									if( 'in_function' == $key ) { ?>
										<div class="ca-indent-section section_in_function">
											<input style="padding: 1em 1.5em;width:100%;font-size:1em;" type="text" value="&lt;?php do_action('contentad', array('tag'=>'')); ?&gt;" />
											<p style="max-width: 400px;"><?php _e( 'Copy the template tag above. Then edit your theme code and paste the Content.ad tag. Your Content.Ad widgets will then load in your custom location.', 'contentad' ); ?></p>
										</div><?php
									}
									if( 'popup_or_mobile_slidup' == $key ) { ?>
										<div class="ca-indent-section section-popup-or-mobile-slideup">
											<p>Change the widget type to change its placement.</p>
										</div><?php
									}
									if( 'in_exit_pop' == $key ) { ?>
                                        <div class="ca-indent-section section-in_exit_pop">
                                            <p>Change the widget type to change its placement.</p>
                                        </div><?php
                                    }
									if( 'in_mobile_exit_pop' == $key ) { ?>
                                        <div class="ca-indent-section section-in_mobile_exit_pop">
                                            <p>Change the widget type to change its placement.</p>
                                        </div><?php
                                    } ?>
                                </div>
							<?php endforeach; ?></div>
						</div>
					</fieldset><?php

					$taxonomy_names = array('category', 'post_tag');
					$cats = array();
					$tags = array();
					foreach ( $taxonomy_names as $taxonomy_name ) {
						$taxonomy = get_taxonomy( $taxonomy_name );
						if ( ! $taxonomy->show_ui )
							continue;
						if ( $taxonomy->hierarchical )
							$cats[] = $taxonomy;
						else
							$tags[] = $taxonomy;
					}

					if ( count( $cats ) ) : ?>

						<fieldset class="inline-edit-col-center inline-edit-categories">
							<div class="inline-edit-col">

								<?php foreach ( $cats as $cat ):
									$name = ( $cat->name == 'category' ) ? 'post_category[]' : '' . esc_attr( $cat->name ) . '[]'; ?>

									<span class="title inline-edit-categories-label">
										<?php _e('Exclude widget from categories', 'contentad'); ?>
									</span>

									<input type="hidden" name="<?php echo $name; ?>" value="" />
									<ul class="cat-checklist <?php esc_attr_e( $cat->name ); ?>-checklist">
										<?php wp_terms_checklist( null, array( 'taxonomy' => $cat->name ) ); ?>
									</ul>

								<?php endforeach; ?>

							</div>
						</fieldset>

					<?php endif;

					if ( count( $tags ) ) : ?>

						<fieldset class="inline-edit-col-right inline-edit-tags">
							<div class="inline-edit-col">

								<?php foreach ( $tags as $tag ) :
									if ( current_user_can( $tag->cap->assign_terms ) ) : ?>
										<label class="inline-edit-tags">
											<span class="title"><?php _e('Exclude widget from tags', 'contentad'); ?></span>
											<textarea cols="22" rows="1" name="<?php esc_attr_e( $tag->name ); ?>[]" class="contentad_exc_tags <?php esc_attr_e( $tag->name ); ?>"></textarea>
										</label>
									<?php endif;
								endforeach; ?>

							</div>
						</fieldset>

					<?php endif;
					break;
			}

		}

	}

}