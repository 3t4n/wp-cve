<?php

if ( ! current_user_can( get_option( $this->shared->get( 'slug' ) . '_statistics_menu_capability' ) ) ) {
	wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'daext-helpful' ) );
}

?>

<!-- process data -->
<?php

//Sanitization -------------------------------------------------------------------------------------------------

//Filter and sort data
$data        = [];
$data['s']   = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : null;
$data['pfr'] = isset( $_GET['pfr'] ) ? sanitize_text_field( $_GET['pfr'] ) : null;
$data['sb']  = isset( $_GET['sb'] ) ? sanitize_text_field( $_GET['sb'] ) : null;
$data['or']  = isset( $_GET['or'] ) ? intval( $_GET['or'], 10 ) : null;

?>

<!-- output -->

<div class="wrap">

    <h2><?php esc_html_e( 'Helpful - Statistics', 'daext-helpful' ); ?></h2>

    <div id="daext-menu-wrapper" class="daext-clearfix">

        <!-- list of posts -->
        <div class="statistics-container">

			<?php

			//Create a query part for the PFR
			if ( ! is_null( $data['pfr'] ) and trim( $data['pfr'] ) !== "0" ) {

				switch ( $data['pfr'] ) {

					case 1:
						$higher_limit = 100;
						$lower_limit  = 81;
						break;

					case 2:
						$higher_limit = 80;
						$lower_limit  = 61;
						break;

					case 3:
						$higher_limit = 60;
						$lower_limit  = 41;
						break;

					case 4:
						$higher_limit = 40;
						$lower_limit  = 21;
						break;

					case 5:
						$higher_limit = 20;
						$lower_limit  = 0;
						break;

					case 6:
						$higher_limit = - 1;
						$lower_limit  = - 1;
						break;

				}

				$filter = "WHERE pfr >= " . intval( $lower_limit, 10 );
				$filter .= " AND pfr <= " . intval( $higher_limit, 10 );

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

					case 'ti':
						$sort_by = 'post_title';
						break;

					case 'pt':
						$sort_by = 'post_type';
						break;

					case 'da':
						$sort_by = 'post_date';
						break;

					case 'pfr':
						$sort_by = 'pfr';
						break;

					default:
						$sort_by = 'pfr';
						break;
				}

			} else {
				$sort_by = 'pfr';
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
			require_once( $this->shared->get( 'dir' ) . '/admin/inc/class-daexthefu-pagination.php' );
			$pag = new daexthefu_pagination();
			$pag->set_total_items( $total_items );//Set the total number of items
			$pag->set_record_per_page( get_option( $this->shared->get( 'slug' ) . '_pagination_items' ) ); //Set records per page
			$pag->set_target_page( "admin.php?page=" . $this->shared->get( 'slug' ) . "-statistics" );//Set target page
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
                                <div><?php esc_html_e( 'Post Title', 'daext-helpful' ); ?></div>
                                <div class="help-icon"
                                     title="<?php esc_attr_e( 'The post, page or custom post type title.',
									     'daext-helpful' ); ?>"></div>
                            </th>
                            <th>
                                <div><?php esc_html_e( 'Post Type', 'daext-helpful' ); ?></div>
                                <div class="help-icon"
                                     title="<?php esc_attr_e( 'The post type.', 'daext-helpful' ); ?>"></div>
                            </th>
                            <th>
                                <div><?php esc_html_e( 'Publishing Date', 'daext-helpful' ); ?></div>
                                <div class="help-icon"
                                     title="<?php esc_attr_e( 'The post, page or custom post type publishing date.',
									     'daext-helpful' ); ?>"></div>
                            </th>
                            <th>
                                <div><?php esc_html_e( 'PFR', 'daext-helpful' ); ?></div>
                                <div class="help-icon"
                                     title="<?php esc_attr_e( 'The calculated positive feedback ratio of the post.',
									     'daext-helpful' ); ?>"></div>
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
										echo esc_html( $result['post_title'] );
									} else {
										echo '<a href="' . esc_url( get_permalink( $result['post_id'] ) ) . '">' . esc_html( apply_filters( 'the_title',
												$result['post_title'], $result['post_id'] ) ) . '</a>';
									}
									?>
                                </td>
								<?php
								$post_type_obj = get_post_type_object( get_post_type( $result['post_id'] ) );
								?>
                                <td><?php echo esc_html( $post_type_obj->labels->singular_name ); ?></td>
                                <td><?php echo esc_html( mysql2date( get_option( 'date_format' ),
										$result['post_date'] ) ); ?></td>
								<?php

								$positive_feedback_ratio    = $result['pfr'] !== '-1' ? $result['pfr'] . '%' : 'N/A';
								$feedback_values            = '(' . $result['positive_feedback'] . '/' . $result['negative_feedback'] . ')';
								$cumulative_feedback        = $positive_feedback_ratio . ' ' . $feedback_values;
								$post_has_no_feedback_class = ( intval( $result['positive_feedback'],
										10 ) === 0 and intval( $result['negative_feedback'],
										10 ) === 0 ) ? 'menu-icon-disabled' : '';

								?>
                                <td>
                                    <div class="daexthefu-pfr-cell-wrapper">
                                        <div class="daexthefu-pfr-cell-icon"><?php $this->shared->generate_pfr_icon( $result['pfr'] ); ?></div>
                                        <div class="daexthefu-pfr-cell-value"><?php echo esc_html( $cumulative_feedback ); ?></div>
                                    </div>
                                </td>
                                <td class="icons-container">
                                    <form method="POST">
                                        <input class="menu-icon external help-icon open-post-data-modal-window <?php echo esc_attr( $post_has_no_feedback_class ); ?>"
                                               data-post-id="<?php echo intval( $result['post_id'], 10 ); ?>"
                                               class="button" type="submit" value=""
                                               title="<?php esc_attr_e( 'View a table that lists feedback associated with this post.',
											       'daext-helpful' ); ?>">
                                    </form>
									<?php if ( get_post_status( $result['post_id'] ) !== false ) : ?>
                                        <a class="menu-icon edit"
                                           href="post.php?post=<?php echo intval( $result['post_id'],
											   10 ); ?>&action=edit"></a>
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
					echo '<div class="error settings-error notice is-dismissible below-h2"><p>' . esc_html__( 'There are no results that match your filter.',
							'daext-helpful' ) . '</p></div>';
				} else {
					echo '<div class="error settings-error notice is-dismissible below-h2"><p>' . esc_html__( 'There are no data at the moment. Click on the "Generate" button to generate statistics about the feedback on your content.',
							'daext-helpful' ) . '</p></div>';
				}

				?>

			<?php endif; ?>

            <!-- Display the pagination -->
			<?php if ( $pag->total_items > 0 ) : ?>
                <div class="daext-tablenav daext-clearfix">
                    <div class="daext-tablenav-pages">
                        <span class="daext-displaying-num"><?php echo esc_html( $pag->total_items ); ?>&nbsp<?php esc_html_e( 'items',
								'daext-helpful' ); ?></span>
						<?php $pag->show(); ?>
                    </div>
                </div>
			<?php endif; ?>

        </div><!-- #subscribers-container -->

        <div class="sidebar-container">

            <div class="daext-widget">

                <h3 class="daext-widget-title">Feedback Data</h3>

                <div class="daext-widget-content">

                    <p><?php esc_html_e( 'This procedure allows you to generate statistics about the feedback on your content.',
							'daext-helpful' ); ?></p>

                </div><!-- .daext-widget-content -->

                <div class="daext-widget-submit">
                    <input id="ajax-request-status" type="hidden" value="inactive">
                    <input class="button" id="update-archive" type="button"
                           value="<?php esc_attr_e( 'Generate', 'daext-helpful' ); ?>">
                    <img id="ajax-loader"
                         src="<?php echo esc_attr( $this->shared->get( 'url' ) . 'admin/assets/img/ajax-loader.gif' ); ?>">
                </div>

            </div>

            <div class="daext-widget" id="filter-and-sort">

                <h3 class="daext-widget-title"><?php esc_html_e( 'Filter & Sort', 'daext-helpful' ); ?></h3>

                <form method="GET" action="admin.php">

                    <input type="hidden" name="page"
                           value="<?php echo esc_attr( $this->shared->get( 'slug' ) ); ?>-statistics">

                    <div class="daext-widget-content">

                        <h3><?php esc_html_e( 'Search', 'daext-helpful' ); ?></h3>
                        <p>
							<?php
                            $data['s'] = $data['s'] ?? '';
							if ( strlen( trim( $data['s'] ) ) > 0 ) {
								$search_string = $data['s'];
							} else {
								$search_string = '';
							}
							?>
                            <input id="filter-and-sort-search" type="text" name="s"
                                   value="<?php echo esc_attr( stripslashes( $search_string ) ); ?>" autocomplete="off"
                                   maxlength="255">
                        </p>

                        <h3><?php esc_html_e( 'PFR', 'daext-helpful' ); ?></h3>
                        <p>
                            <select name="pfr" id="pfr">
                                <option value="0" <?php selected( $data['pfr'], '0' ); ?>><?php esc_html_e( 'All',
										'daext-helpful' ); ?></option>
                                <option value="1" <?php selected( $data['pfr'],
									'1' ); ?>><?php esc_html_e( 'Very Positive', 'daext-helpful' ); ?></option>
                                <option value="2" <?php selected( $data['pfr'], '2' ); ?>><?php esc_html_e( 'Positive',
										'daext-helpful' ); ?></option>
                                <option value="3" <?php selected( $data['pfr'], '3' ); ?>><?php esc_html_e( 'Average',
										'daext-helpful' ); ?></option>
                                <option value="4" <?php selected( $data['pfr'], '4' ); ?>><?php esc_html_e( 'Negative',
										'daext-helpful' ); ?></option>
                                <option value="5" <?php selected( $data['pfr'],
									'5' ); ?>><?php esc_html_e( 'Very Negative', 'daext-helpful' ); ?></option>
                                <option value="6" <?php selected( $data['pfr'],
									'6' ); ?>><?php esc_html_e( 'No Feedback', 'daext-helpful' ); ?></option>
                            </select>
                        </p>

                        <h3><?php esc_html_e( 'Sort By', 'daext-helpful' ); ?></h3>
                        <p>
                            <select name="sb" id="sb">
                                <option value="ti" <?php selected( $data['sb'],
									'ti' ); ?>><?php esc_html_e( 'Post Title', 'daext-helpful' ); ?></option>
                                <option value="pt" <?php selected( $data['sb'],
									'pt' ); ?>><?php esc_html_e( 'Post Type', 'daext-helpful' ); ?></option>
                                <option value="da" <?php selected( $data['sb'],
									'da' ); ?>><?php esc_html_e( 'Publishing Date', 'daext-helpful' ); ?></option>
                                <option value="pfr" <?php if ( $data['sb'] === 'pfr' or $data['sb'] === null ) {
									echo 'selected="selected"';
								} ?>><?php esc_html_e( 'PFR', 'daext-helpful' ); ?></option>
                            </select>
                        </p>


                        <h3><?php esc_html_e( 'Order', 'daext-helpful' ); ?></h3>
                        <p>
                            <select name="or" id="or">
                                <option value="1" <?php selected( $data['or'], 1 ); ?>><?php esc_html_e( 'Descending',
										'daext-helpful' ); ?></option>
                                <option value="0" <?php selected( $data['or'], 0 ); ?>><?php esc_html_e( 'Ascending',
										'daext-helpful' ); ?></option>
                            </select>
                        </p>

                    </div><!-- .daext-widget-content -->

                    <div class="daext-widget-submit">
                        <input class="button" type="submit"
                               value="<?php esc_attr_e( 'Apply Query', 'daext-helpful' ); ?>">
                    </div>

                </form>

            </div>

        </div>

    </div>

</div>

<div id="dialog-post-feedback" title="<?php esc_attr_e( 'Post Feedback', 'daext-helpful' ); ?>"
     class="daext-display-none"></div>