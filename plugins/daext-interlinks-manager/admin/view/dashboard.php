<?php

if ( ! current_user_can( 'edit_posts' ) ) {
	wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'daext-interlinks-manager') );
}

?>

<!-- process data -->
<?php

//Sanitization -------------------------------------------------------------------------------------------------

//Filter and sort data
$data       = [];
$data['s']  = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : null;
$data['op'] = isset( $_GET['op'] ) ? sanitize_text_field( $_GET['op'] ) : null;
$data['sb'] = isset( $_GET['sb'] ) ? sanitize_text_field( $_GET['sb'] ) : null;
$data['or'] = isset( $_GET['or'] ) ? intval( $_GET['or'], 10 ) : null;

?>

<!-- output -->

<div class="wrap">

    <h2><?php esc_html_e( 'Interlinks Manager - Dashboard', 'daext-interlinks-manager'); ?></h2>

    <div id="daext-menu-wrapper" class="daext-clearfix">

        <!-- list of subscribers -->
        <div class="interlinks-container">

			<?php

			//optimization
			if ( ! is_null( $data['op'] ) and
			     ( trim( $data['op'] ) != 'all' and ( intval( $data['op'], 10 ) == 0 or intval( $data['op'],
						     10 ) == 1 ) )
			     and ( strlen( trim( $data['op'] ) ) > 0 ) ) {
				$filter = "WHERE optimization = '" . intval( $data['op'], 10 ) . "'";
			} else {
				$filter = '';
			}

			//search
			if ( ! is_null( $data['s'] ) and strlen( trim( $data['s'] ) ) > 0 ) {
				$search_string = $data['s'];
				global $wpdb;
				if ( strlen( trim( $filter ) ) > 0 ) {
					$filter .= $wpdb->prepare( ' AND (post_title LIKE %s)', '%' . $search_string . '%' );
				} else {
					$filter .= $wpdb->prepare( 'WHERE (post_title LIKE %s)', '%' . $search_string . '%' );
				}
			} else {
				$filter .= '';
			}

			//sort -------------------------------------------------

			//sort by
			if ( ! is_null( $data['sb'] ) ) {

				/*
				 * verify if the value is valid, if the value is invalid
				 *  default to the "post_date"
				 */
				switch ( $data['sb'] ) {

					case 'pd':
						$sort_by = 'post_date';
						break;

					case 'ti':
						$sort_by = 'post_title';
						break;

					case 'mi':
						$sort_by = 'manual_interlinks';
						break;

					case 'pt':
						$sort_by = 'post_type';
						break;

					case 'cl':
						$sort_by = 'content_length';
						break;

					case 'op':
						$sort_by = 'optimization';
						break;

					default:
						$sort_by = 'post_date';
						break;
				}

			} else {
				$sort_by = 'post_date';
			}

			//order
			if ( ! is_null( $data['or'] ) and $data['or'] === 0 ) {
				$order = "ASC";
			} else {
				$order = "DESC";
			}

			//retrieve the total number of events
			global $wpdb;
			$table_name  = $wpdb->prefix . $this->shared->get( 'slug' ) . "_archive";
			$total_items = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name " . $filter );

			//Initialize the pagination class
			require_once( $this->shared->get( 'dir' ) . '/admin/inc/class-daextinma-pagination.php' );
			$pag = new daextinma_pagination();
			$pag->set_total_items( $total_items );//Set the total number of items
			$pag->set_record_per_page( 10 ); //Set records per page
			$pag->set_target_page( "admin.php?page=" . $this->shared->get( 'slug' ) . "-dashboard" );//Set target page
			$pag->set_current_page();//set the current page number from $_GET

			?>

            <!-- Query the database -->
			<?php
			$query_limit = $pag->query_limit();
			$results     = $wpdb->get_results( "SELECT * FROM $table_name " . $filter . " ORDER BY $sort_by $order $query_limit ",
				ARRAY_A ); ?>

			<?php if ( count( $results ) > 0 ) : ?>

                <div class="daext-items-container">

                    <table class="daext-items">
                        <thead>
                        <tr>
                            <th>
                                <div><?php esc_html_e( 'Post', 'daext-interlinks-manager'); ?></div>
                                <div class="help-icon"
                                     title="<?php esc_attr_e( 'The post, page or custom post type title.', 'daext-interlinks-manager'); ?>"></div>
                            </th>
                            <th>
                                <div><?php esc_html_e( 'Date', 'daext-interlinks-manager'); ?></div>
                                <div class="help-icon"
                                     title="<?php esc_attr_e( 'The post, page or custom post type publishing date.', 'daext-interlinks-manager'); ?>"></div>
                            </th>
                            <th>
                                <div><?php esc_html_e( 'PT', 'daext-interlinks-manager'); ?></div>
                                <div class="help-icon"
                                     title="<?php esc_attr_e( 'The post type.', 'daext-interlinks-manager'); ?>"></div>
                            </th>
                            <th>
                                <div><?php esc_html_e( 'CL', 'daext-interlinks-manager'); ?></div>
                                <div class="help-icon"
                                     title="<?php esc_attr_e( 'The length of the raw (with filters not applied) post content.', 'daext-interlinks-manager'); ?>"></div>
                            </th>
                            <th>
                                <div><?php esc_html_e( 'MIL', 'daext-interlinks-manager'); ?></div>
                                <div class="help-icon"
                                     title="<?php esc_attr_e( 'The manual internal links of the post.', 'daext-interlinks-manager'); ?>"></div>
                            </th>
                            <th>
                                <div><?php esc_html_e( 'RI', 'daext-interlinks-manager'); ?></div>
                                <div class="help-icon"
                                     title='<?php esc_attr_e( 'The recommended number of interlinks. This value is based on the post length and on the "Characters per Interlink" option that you defined on the plugin options.', 'daext-interlinks-manager'); ?>'></div>
                            </th>
                            <th>
                                <div><?php esc_html_e( 'OF', 'daext-interlinks-manager'); ?></div>
                                <div class="help-icon"
                                     title='<?php esc_attr_e( 'The "Optimization Flag" is based on the post length, on the "Characters per Interlink" option and on the "Optimization Delta" option that you defined on the plugin options.', 'daext-interlinks-manager'); ?>'></div>
                            </th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

						<?php foreach ( $results as $result ) : ?>

                            <tr>
                                <td>
									<?php
									if ( get_post_status( $result['post_id'] ) === false ) {
										echo esc_html( apply_filters( 'the_title', $result['post_title'] ) );
									} else {
										echo '<a href="' . esc_url( get_permalink( $result['post_id'] ) ) . '">' . esc_html( apply_filters( 'the_title',
												$result['post_title'] ) ) . '</a>';
									}
									?>
                                </td>
                                <td><?php echo esc_html( mysql2date( get_option( 'date_format' ),
										$result['post_date'] ) ); ?></td>
                                <td><?php echo esc_html( stripslashes( $result['post_type'] ) ); ?></td>
                                <td><?php echo esc_html( $result['content_length'] ); ?></td>
                                <td><?php echo esc_html( $result['manual_interlinks'] ); ?></td>
                                <td><?php echo esc_html( $result['recommended_interlinks'] ); ?></td>
                                <td><?php echo esc_html( $result['optimization'] ); ?></td>
                                <td class="icons-container">
									<?php if ( get_post_status( $result['post_id'] ) !== false ) : ?>
                                        <a class="menu-icon edit"
                                           href="post.php?post=<?php echo esc_attr( $result['post_id'] ); ?>&action=edit"></a>
									<?php endif; ?>
                                </td>
                            </tr>

						<?php endforeach; ?>

                        </tbody>
                    </table>

                </div>

			<?php else : ?>

				<?php

				if ( strlen( trim( $filter ) ) > 0 ) {
					echo '<div class="error settings-error notice is-dismissible below-h2"><p>' . esc_html__( 'There are no results that match your filter.', 'daext-interlinks-manager') . '</p></div>';
				} else {
					echo '<div class="error settings-error notice is-dismissible below-h2"><p>' . esc_html__( 'There are no data at moment, click on the "Generate Data" button to generate data and statistics about the internal links of your blog.', 'daext-interlinks-manager') . '</p></div>';
				}

				?>

			<?php endif; ?>

            <!-- Display the pagination -->
			<?php if ( $pag->total_items > 0 ) : ?>
                <div class="daext-tablenav daext-clearfix">
                    <div class="daext-tablenav-pages">
                        <span class="daext-displaying-num"><?php echo esc_html( $pag->total_items ); ?>&nbsp<?php esc_html_e( 'items', 'daext-interlinks-manager'); ?></span>
						<?php $pag->show(); ?>
                    </div>
                </div>
			<?php endif; ?>

        </div><!-- #subscribers-container -->

        <div class="sidebar-container">

            <div class="daext-widget">

                <h3 class="daext-widget-title">Interlinks Data</h3>

                <div class="daext-widget-content">

                    <p><?php esc_html_e( 'This procedure allows you to generate data and statistics about the internal links of your blog.', 'daext-interlinks-manager'); ?></p>

                </div><!-- .daext-widget-content -->

                <div class="daext-widget-submit">
                    <input id="ajax-request-status" type="hidden" value="inactive">
                    <input class="button" id="update-archive" type="button"
                           value="<?php esc_attr_e( 'Generate Data', 'daext-interlinks-manager'); ?>">
                    <img id="ajax-loader"
                         src="<?php echo esc_url( $this->shared->get( 'url' ) . 'admin/assets/img/ajax-loader.gif' ); ?>">
                </div>

            </div>

            <div class="daext-widget" id="filter-and-sort">

                <h3 class="daext-widget-title"><?php esc_html_e( 'Filter & Sort', 'daext-interlinks-manager'); ?></h3>

                <form method="GET" action="admin.php">

                    <input type="hidden" name="page"
                           value="<?php echo esc_attr( $this->shared->get( 'slug' ) ); ?>-dashboard">

                    <div class="daext-widget-content">

                        <h3><?php esc_html_e( 'Search', 'daext-interlinks-manager'); ?></h3>
                        <p>
							<?php
							if ( !is_null($data['s']) && strlen( trim( $data['s'] ) ) > 0 ) {
								$search_string = $data['s'];
							} else {
								$search_string = '';
							}
							?>
                            <input id="filter-and-sort-search" type="text" name="s"
                                   value="<?php echo esc_attr( stripslashes( $search_string ) ); ?>" autocomplete="off"
                                   maxlength="255">
                        </p>

                        <h3><?php esc_html_e( 'Optimization', 'daext-interlinks-manager'); ?></h3>
                        <p>
                            <select name="op" id="op">
                                <option value="all" <?php selected( $data['op'], 'all' ); ?>><?php esc_html_e( 'All', 'daext-interlinks-manager'); ?></option>
                                <option value="0" <?php selected( $data['op'],
									'0' ); ?>><?php esc_html_e( 'Not Optimized', 'daext-interlinks-manager'); ?></option>
                                <option value="1" <?php selected( $data['op'], '1' ); ?>><?php esc_html_e( 'Optimized', 'daext-interlinks-manager'); ?></option>
                            </select>
                        </p>


                        <h3><?php esc_html_e( 'Sort By', 'daext-interlinks-manager'); ?></h3>
                        <p>
                            <select name="sb" id="sb">
                                <option value="pd"><?php esc_html_e( 'Date', 'daext-interlinks-manager'); ?></option>
                                <option value="ti" <?php selected( $data['sb'], 'ti' ); ?>><?php esc_html_e( 'Title', 'daext-interlinks-manager'); ?></option>
                                <option value="pt" <?php selected( $data['sb'],
									'pt' ); ?>><?php esc_html_e( 'Post Type', 'daext-interlinks-manager'); ?></option>
                                <option value="cl" <?php selected( $data['sb'],
									'cl' ); ?>><?php esc_html_e( 'Content Length', 'daext-interlinks-manager'); ?></option>
                                <option value="mi" <?php selected( $data['sb'],
									'mi' ); ?>><?php esc_html_e( 'Manual Interlinks', 'daext-interlinks-manager'); ?></option>
                                <option value="op" <?php selected( $data['sb'],
									'op' ); ?>><?php esc_html_e( 'Optimization', 'daext-interlinks-manager'); ?></option>
                            </select>
                        </p>


                        <h3><?php esc_html_e( 'Order', 'daext-interlinks-manager'); ?></h3>
                        <p>
                            <select name="or" id="or">
                                <option value="1" <?php selected( $data['or'], 1 ); ?>><?php esc_html_e( 'Descending', 'daext-interlinks-manager'); ?></option>
                                <option value="0" <?php selected( $data['or'], 0 ); ?>><?php esc_html_e( 'Ascending', 'daext-interlinks-manager'); ?></option>
                            </select>
                        </p>

                    </div><!-- .daext-widget-content -->

                    <div class="daext-widget-submit">
                        <input class="button" type="submit"
                               value="<?php esc_attr_e( 'Apply Query', 'daext-interlinks-manager'); ?>">
                    </div>

                </form>

            </div>

        </div>

    </div>

</div>