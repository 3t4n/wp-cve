<?php

if ( ! current_user_can( 'edit_posts' ) ) {
	wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'daext-interlinks-manager') );
}

//Sanitization -------------------------------------------------------------------------------------------------

//Filter and search data
$data['s'] = isset( $_GET['s'] ) ? sanitize_text_field( $_GET['s'] ) : null;

?>

<!-- process data -->

<!-- output -->

<div class="wrap">

    <div id="daext-header-wrapper" class="daext-clearfix">

        <h2><?php esc_html_e( 'Interlinks Manager - Juice', 'daext-interlinks-manager'); ?></h2>

        <form action="admin.php" method="get" id="daext-search-form">

            <input type="hidden" name="page" value="daextinma-juice">

            <p><?php esc_html_e( 'Perform your Search', 'daext-interlinks-manager'); ?></p>

			<?php
			if ( ! is_null( $data['s'] ) ) {
				if ( mb_strlen( trim( $data['s'] ) ) > 0 ) {
					$search_string = $data['s'];
				} else {
					$search_string = '';
				}
			} else {
				$search_string = '';
			}
			?>

            <input type="text" name="s"
                   value="<?php echo esc_attr( stripslashes( $search_string ) ); ?>" autocomplete="off" maxlength="255">
            <input type="submit" value="">

        </form>

    </div>

    <div id="daext-menu-wrapper" class="daext-clearfix">

        <!-- list of subscribers -->
        <div class="juice-container">

			<?php

			//create the query part used to filter the results when a search is performed
			if ( ! is_null( $data['s'] ) ) {
				if ( strlen( trim( $data['s'] ) ) > 0 ) {
					$search_string = $data['s'];
					global $wpdb;
					$filter = $wpdb->prepare( 'WHERE (url LIKE %s)', '%' . $search_string . '%' );
				} else {
					$filter = '';
				}
			} else {
				$filter = '';
			}

			//default pagination -----------------------------------

			//retrieve the total number of events
			global $wpdb;
			$table_name  = $wpdb->prefix . $this->shared->get( 'slug' ) . "_juice";
			$total_items = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name $filter" );

			//Initialize the pagination class
			require_once( $this->shared->get( 'dir' ) . '/admin/inc/class-daextinma-pagination.php' );
			$pag = new daextinma_pagination();
			$pag->set_total_items( $total_items );//Set the total number of items
			$pag->set_record_per_page( 10 ); //Set records per page
			$pag->set_target_page( "admin.php?page=" . $this->shared->get( 'slug' ) . "-juice" );//Set target page
			$pag->set_current_page();//set the current page number from $_GET

			?>

            <!-- Query the database -->
			<?php
			$query_limit = $pag->query_limit();//die("SELECT * FROM $table_name " . $filter . " ORDER BY $sort_by $order $query_limit ");
			$results     = $wpdb->get_results( "SELECT * FROM $table_name $filter ORDER BY juice DESC $query_limit ",
				ARRAY_A ); ?>

			<?php if ( count( $results ) > 0 ) : ?>

                <div class="daext-items-container">

                    <table class="daext-items">
                        <thead>
                        <tr>
                            <th>
                                <div><?php esc_html_e( 'URL', 'daext-interlinks-manager'); ?></div>
                                <div class="help-icon" title="<?php esc_attr_e( 'The URL that receives the link juice.', 'daext-interlinks-manager'); ?>"></div>
                            </th>
                            <th>
                                <div><?php esc_html_e( 'IIL', 'daext-interlinks-manager'); ?></div>
                                <div class="help-icon"
                                     title="<?php esc_attr_e( 'The number of internal links received by the URL.', 'daext-interlinks-manager'); ?>"></div>
                            </th>
                            <th>
                                <div><?php esc_html_e( 'Juice (Value)', 'daext-interlinks-manager'); ?></div>
                                <div class="help-icon" title="<?php esc_attr_e( 'The link juice received by the URL.', 'daext-interlinks-manager'); ?>"></div>
                            </th>
                            <th>
                                <div><?php esc_html_e( 'Juice (Visual)', 'daext-interlinks-manager'); ?></div>
                                <div class="help-icon"
                                     title="<?php esc_attr_e( 'The visual representation of the link juice received by the URL.', 'daext-interlinks-manager'); ?>"></div>
                            </th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>

						<?php foreach ( $results as $result ) : ?>

                            <tr>
                                <td>
                                    <a href="<?php echo esc_url( $result['url'] ); ?>"><?php echo esc_html( $result['url'] ); ?></a>
                                </td>
                                <td><?php echo esc_html( $result['iil'] ); ?></td>
                                <td><?php echo esc_html( $result['juice'] ); ?></td>
                                <td>
                                    <div id="juice-relative-container">
                                        <div id="juice-relative"
                                             style="width: <?php echo esc_attr( $result['juice_relative'] ); ?>px"></div>
                                    </div>
                                </td>
                                <td class="icons-container">
                                    <form method="POST">
                                        <input class="menu-icon external help-icon open-anchors-modal-window"
                                               data-juice-id="<?php echo intval( $result['id'], 10 ); ?>" class="button"
                                               type="submit" value=""
                                               title="<?php esc_attr_e( 'View a table that includes a list of the internal links that contribute to generate the overall link juice of this URL.', 'daext-interlinks-manager'); ?>">
                                    </form>
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
					echo '<div class="error settings-error notice is-dismissible below-h2"><p>' . esc_html__( 'There are no data at moment, click on the "Generate Data" button to generate data about the flow of "Link Juice" on the URLs of your website.', 'daext-interlinks-manager') . '</p></div>';
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

                <h3 class="daext-widget-title"><?php esc_html_e( 'Juice Data', 'daext-interlinks-manager'); ?></h3>

                <div class="daext-widget-content">

                    <p><?php esc_html_e( 'This procedure allows you to generate data about the flow of "Link Juice" on the URLs of your website.', 'daext-interlinks-manager'); ?></p>

                </div><!-- .daext-widget-content -->

                <div class="daext-widget-submit">
                    <input id="ajax-request-status" type="hidden" value="inactive">
                    <input class="button" id="update-archive" type="button"
                           value="<?php esc_attr_e( 'Generate Data', 'daext-interlinks-manager'); ?>">
                    <img id="ajax-loader"
                         src="<?php echo esc_url( $this->shared->get( 'url' ) . 'admin/assets/img/ajax-loader.gif' ); ?>">
                </div>

            </div>

        </div>

    </div>

</div>

<!-- Dialog Confirm -->
<div id="dialog-url-juice" title="<?php esc_attr_e( 'URL Juice', 'daext-interlinks-manager'); ?>"
     class="daext-display-none"></div>