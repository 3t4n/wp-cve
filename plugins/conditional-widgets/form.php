<?php
/**
 * @author    Jason LeMahieu and Kevin Graeme
 * @copyright Copyright (c) 2011 - 2021 Jason LeMahieu and Kevin Graeme
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 * @package   CETS\Conditional_Widgets
 */

add_action( 'in_widget_form'        , 'conditional_widgets_form'  , 10, 3 );
add_filter( 'widget_update_callback', 'conditional_widgets_update', 10, 2 );

/**
 * Display the form at the bottom of each widget.
 */
function conditional_widgets_form( $widget, $return, $instance ) {

	/** always show the form */
	if ( $return == 'noform' ) {
		$return = true;
	}

	//prefill variables
	$instance = conditional_widgets_init_instance( $instance );
	//whether to display ON or OFF so users can easily see which widgets are conditional
	$active   = cets_conditional_widgets_instance_is_active( $instance );
	?>
	<div class="cets-conditional-widget">
		<div class="conditional-widget-top">
        	<div class="conditional-widget-title-action">
            	<a href="#" id="conditional-widget-toggle-wrap-<?php print $widget->id ?>" onclick="conditional_widgets_form_toggle('conditional-widget-form-<?php print $widget->id; ?>'); return false;"></a>
            </div>

            <div class="conditional-widget-title">
                <h5><?php _e( 'Widget Display Control', 'conditional-widgets' ); ?>
                <?php
                if ( $active ) {
					?>
					<span class="conditional-widgets-active"><?php _e( 'ON', 'conditional-widgets' ); ?></span>
					<?php
                } else {
					?>
                    <span class="conditional-widgets-inactive"><?php _e( 'OFF', 'conditional-widgets' ); ?></span>
					<?php }
				?>
                </h5>
            </div>
		</div>

		<div class="conditional-widget-form" id="conditional-widget-form-<?php print $widget->id; ?>">
			<p class='cw-instructions'>
				<?php _e( 'Select a combination of options to control on which sections of your site this widget is shown.', 'conditional-widgets' ); ?>
			</p>
			<p>
				<input type="checkbox" name="cw_home_enable_checkbox" id="cw_home_enable_checkbox_<?php print $widget->id; ?>" <?php checked( $instance['cw_home_enable_checkbox'] ); ?>>
				<?php conditional_widgets_form_show_hide_select( 'cw_select_home_page', $instance['cw_select_home_page'], true, true ); ?>
				<label for="cw_home_enable_checkbox_<?php print $widget->id; ?>"><?php _e( 'on Front Page', 'conditional-widgets' ); ?></label>
			</p>
			<?php
				$type_tax_pairs = apply_filters( 'conditional_widgets_type_tax_pairs', array() );

				if ( $type_tax_pairs ) {
					foreach( $type_tax_pairs as $pair ) {
					
						$tax          = $pair['tax'];
						$type         = $pair['type'];
						$selected_ids = array();

						// valid taxonomy
						$taxonomy_object = get_taxonomy( $tax );
						if ( $taxonomy_object == false ) {
							continue;
						}

						// valid type
						$post_type_object = get_post_type_object( $type );
						if ( ! $post_type_object ) {
							continue;
						}

						// taxonomy applied to type
						// @TODO - validate
					
						// for prefilling form fields..
						if ( isset( $instance['cw_custom'][$type][$tax] ) ) {
							$custom_subdata = $instance['cw_custom'][$type][$tax];
						} else {
							$custom_subdata = conditional_widgets_get_default_custom_subdata();
						}

						if ( isset( $custom_subdata['selected_ids'] ) ) {
							$selected_ids = $custom_subdata['selected_ids'];
						}
						echo "<h6 class='conditional-widget-header conditional-widget-sub-heading'>{$taxonomy_object->labels->name}</h6>";

						printf(
							__( '%s and %s', 'conditional-widgets' ),
							"<input type='checkbox' name='cw_custom[{$type}][{$tax}][enable]' value='1' "
								. checked($custom_subdata['enable'], 1, 0 ) . '>'
								. sprintf( _x( 'Enable %s Logic', '(Custom) Post Type will fill the placeholder', 'conditional-widgets' ), $post_type_object->labels->singular_name )
								. '</label>',
							conditional_widgets_form_show_hide_select( "cw_custom[{$type}][{$tax}][select]", $custom_subdata['select'] )
								. sprintf( __( 'on %1$s in selected %2$s:', 'conditional-widgets' ), $post_type_object->labels->name, $taxonomy_object->labels->name )
						);

						echo "<p>";
							echo "<span class='cw_sub_checkbox'><label>";
								echo "<input type='checkbox' name='cw_custom[{$type}][{$tax}][all]' value='1' " . checked( $custom_subdata['all'], 1, 0 ) . " >";
								printf( __( 'ALL %s (or select below)', 'conditional-widgets' ), $taxonomy_object->labels->name );
							echo "</label></span>";

							if ( is_taxonomy_hierarchical( $tax ) ) {
								echo "<span class='cw_sub_checkbox'><label>";
									echo "<input type='checkbox' name='cw_custom[{$type}][{$tax}][sub]' " . checked( $custom_subdata['sub'], 1, 0 ) . ">";
									printf( __( 'Include sub-%s automatically', 'conditional-widgets' ), $taxonomy_object->labels->name );
								echo "</label></span>";
							}

						echo "</p>";

						echo "<div class='conditional-widgets-checkbox-wrapper'>";
							conditional_widgets_term_checkboxes( $tax, $post_type_object->name, $selected_ids );
						echo "</div>";

					} // /foreach
				}
			?>
			<h6 class="conditional-widget-header conditional-widget-sub-heading"><?php _e( 'Pages', 'conditional-widgets' ); ?></h6>
			<p>
				<?php
				printf(
					__( '%s and %s', 'conditional-widgets' ),
					'<label><input type="checkbox" name="cw_pages_enable_checkbox" '
						. checked( $instance['cw_pages_enable_checkbox'], 1, false )
						. '>'
						. sprintf( _x( 'Enable %s Logic', '(Custom) Post Type will fill the placeholder', 'conditional-widgets' ), __( 'Page', 'conditional-widgets' ) )
						. '</label>',
					conditional_widgets_form_show_hide_select( 'cw_select_pages', $instance['cw_select_pages'], false, false )
						. __( 'on selected Pages:', 'conditional-widgets' )
				);
				if ( ! isset( $instance['cw_pages_all'] ) ) {
					$instance['cw_pages_all'] = 0;
				}
				?>
				<span class="cw_sub_checkbox">
					<label>
						<input type="checkbox" name="cw_pages_all" value="1" <?php checked( $instance['cw_pages_all'] ); ?>>
						<?php _e( 'ALL pages (or select below)', 'conditional-widgets' ); ?>
					</label>
				</span>

				<span class="cw_sub_checkbox">
					<label>
						<input type="checkbox" name="cw_pages_sub_checkbox" <?php checked( $instance['cw_pages_sub_checkbox'] ); ?>>
						<?php _e( 'Include sub-pages automatically', 'conditional-widgets' ); ?>
					</label>
				</span>
			</p>

			<div class="conditional-widgets-checkbox-wrapper">
			<?php
			$selected_pages = array();
			if ( isset( $instance['cw_selected_pages'] ) && is_array( $instance['cw_selected_pages'] ) ) {
				$selected_pages = $instance['cw_selected_pages'];
			}
			conditional_widgets_page_checkboxes( $selected_pages );
			?>
			</div>

			<h6 class="conditional-widget-header conditional-widget-sub-heading"><?php _e( 'Special Page Options', 'conditional-widgets' ); ?></h6>
			<ul class="conditional-widgets-special-page-option-list">
				<!-- posts page -->
				<li>
					<label>
						<input type="checkbox" name="cw_posts_page_hide_checkbox" <?php checked( $instance['cw_posts_page_hide'] ); ?>>
						<?php _e( 'Hide on Posts Page (when using a static front page)', 'conditional-widgets' ); ?>
					</label>
				</li>

				<!-- 404 -->
				<li>
					<label>
						<input type="checkbox" name="cw_404_hide_checkbox" <?php checked( $instance['cw_404_hide'] ); ?>>
						<?php _e( 'Hide on 404s (Page Not Found)', 'conditional-widgets' ); ?>
					</label>
				</li>

				<!-- search results -->
				<li>
					<label>
						<input type="checkbox" name="cw_search_hide_checkbox" <?php checked( $instance['cw_search_hide'] ); ?>>
						<?php _e( 'Hide when displaying Search Results', 'conditional-widgets' ); ?>
					</label>
				</li>

				<!-- archives -->
				<li>
					<label>
						<input type="checkbox" name="cw_author_archive_hide_checkbox" <?php checked( $instance['cw_author_archive_hide'] ); ?>>
						<?php _e( 'Hide on Author Archives', 'conditional-widgets' ); ?>
					</label>
				</li>
				<li>
					<label>
						<input type="checkbox" name="cw_date_archive_hide_checkbox" <?php checked( $instance['cw_date_archive_hide'] ); ?>>
						<?php _e( 'Hide on Date Archives', 'conditional-widgets' ); ?>
					</label>
				</li>
				<li>
					<label>
						<input type="checkbox" name="cw_tag_archive_hide_checkbox" <?php checked( $instance['cw_tag_archive_hide'] ); ?>>
						<?php _e( 'Hide on Tag Archives', 'conditional-widgets' ); ?>
					</label>
				</li>
			</ul>

			<h6 class="conditional-widget-header conditional-widget-sub-heading"><?php _e( 'Other Options', 'conditional-widgets' ); ?></h6>
			<p><?php 
				_e('Note: The following options will take precendence over those above.', 'conditional-widgets');
			?></p>
			<ul class="conditional-widgets-special-page-option-list">
				<li>
					<label>
						<input type="checkbox" name="cw_mobile_hide_checkbox" <?php checked( $instance['cw_mobile_hide'] ); ?>>
						<?php _e( 'Hide on Mobile', 'conditional-widgets' ); ?>
					</label>
				</li>
				<li>
					<label>
						<input type="checkbox" name="cw_desktop_hide_checkbox" <?php checked( $instance['cw_desktop_hide'] ); ?>>
						<?php _e( 'Hide on Desktop', 'conditional-widgets' ); ?>
					</label>
				</li>
			</ul>

			
			

		</div> <!-- toggled div -->

	</div> <!-- /.cets-conditional-widgets -->
	<?php
}  // /function conditional_widgets_form()

/**
 * Process the form submission. (Save settings.)
 */
function conditional_widgets_update( $new_instance, $old_instance ) {

	$instance = $new_instance;  //save old data, and only change the following stuff:

	// home
	$instance['cw_home_enable_checkbox'] = isset($_POST['cw_home_enable_checkbox']) ? 1 : 0;
	$instance['cw_select_home_page']     = $_POST['cw_select_home_page'];


	// custom types, including posts and categories - since 1.9
	$type_tax_pairs = apply_filters( 'conditional_widgets_type_tax_pairs', array() );

	foreach ( $type_tax_pairs as $pair ) {

		// todo - validate
		$type = $pair['type'];
		$tax  = $pair['tax'];

		$custom_subdata = $_POST['cw_custom'][$type][$tax];

		if ( ! isset( $instance['cw_custom'] ) ) {
			$instance['cw_custom'] = array();
		}
		if ( ! isset( $instance['cw_custom'][$type] ) ) {
			$instance['cw_custom'][$type] = array();
		}
		if ( ! isset( $instance['cw_custom'][$type][$tax] ) ) {
			$instance['cw_custom'][$type][$tax] = array();
		}

		$instance['cw_custom'][$type][$tax]['enable'] = isset( $custom_subdata['enable'] ) ? 1 : 0;
		$instance['cw_custom'][$type][$tax]['all']    = isset( $custom_subdata['all']    ) ? 1 : 0;
		$instance['cw_custom'][$type][$tax]['sub']    = isset( $custom_subdata['sub']    ) ? 1 : 0;
		$instance['cw_custom'][$type][$tax]['select'] = $custom_subdata['select'];

		if ( isset( $custom_subdata['selected_ids'] ) ) {
			$instance['cw_custom'][$type][$tax]['selected_ids'] = $custom_subdata['selected_ids'];
		} else {
			$instance['cw_custom'][$type][$tax]['selected_ids'] = '';
		}

	}

	//pages
	$instance['cw_pages_enable_checkbox'] = isset( $_POST['cw_pages_enable_checkbox'] ) ? 1 : 0;
	$instance['cw_select_pages']          = $_POST['cw_select_pages'];
	$instance['cw_pages_sub_checkbox']    = isset( $_POST['cw_pages_sub_checkbox']    ) ? 1 : 0;

	if ( isset( $_POST['cw_selected_pages'] ) ) {
		$instance['cw_selected_pages'] = $_POST['cw_selected_pages'];
	} else {
		$instance['cw_selected_pages'] = '';
	}

	$instance['cw_pages_all'] = isset( $_POST['cw_pages_all'] ) ? 1 : 0;

	// utility - since 1.0.4
	//404, search, archive
	$instance['cw_posts_page_hide']     = isset( $_POST['cw_posts_page_hide_checkbox']     ) ? 1 : 0;
	$instance['cw_404_hide']            = isset( $_POST['cw_404_hide_checkbox']            ) ? 1 : 0;
	$instance['cw_search_hide']         = isset( $_POST['cw_search_hide_checkbox']         ) ? 1 : 0;
	$instance['cw_date_archive_hide']   = isset( $_POST['cw_date_archive_hide_checkbox']   ) ? 1 : 0;
	$instance['cw_author_archive_hide'] = isset( $_POST['cw_author_archive_hide_checkbox'] ) ? 1 : 0;
	$instance['cw_tag_archive_hide']    = isset( $_POST['cw_tag_archive_hide_checkbox']    ) ? 1 : 0;
	$instance['cw_mobile_hide']    = isset( $_POST['cw_mobile_hide_checkbox']    ) ? 1 : 0;
	$instance['cw_desktop_hide']    = isset( $_POST['cw_desktop_hide_checkbox']    ) ? 1 : 0;

	return $instance;

} // /function conditional_widgets_update()

function cets_conditional_widgets_instance_is_active( $instance ) {

	$type_tax_pairs = apply_filters( 'conditional_widgets_type_tax_pairs', array() );

	if (
		$instance['cw_home_enable_checkbox']
		|| $instance['cw_pages_enable_checkbox']
		|| $instance['cw_404_hide']
		|| $instance['cw_search_hide']
		|| $instance['cw_author_archive_hide']
		|| $instance['cw_date_archive_hide']
		|| $instance['cw_tag_archive_hide']
		|| $instance['cw_posts_page_hide']
		|| $instance['cw_mobile_hide']
		|| $instance['cw_desktop_hide']
	) {
		return true;
	}

	foreach ( $type_tax_pairs as $pair ) {
		$tax  = $pair['tax'];
		$type = $pair['type'];
		if ( isset( $instance['cw_custom'][$type][$tax]['enable'] ) && $instance['cw_custom'][$type][$tax]['enable'] == 1 ) {
			return true;
		}
	}

	return false;

} // /function cets_conditional_widgets_instance_is_active()
