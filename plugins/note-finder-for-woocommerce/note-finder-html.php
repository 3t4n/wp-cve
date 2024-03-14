<div class="wrap">
	<?php
	if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ):
		echo '<h1 class="wp-heading-inline">';
		esc_html_e( 'WooCommerce Note Finder', 'note-finder-for-woocommerce' );
	else:
		echo '<h1 style="color:red;" class="wp-heading-inline">';
		esc_html_e( 'Please activate WooCommerce to run the plugin', 'note-finder-for-woocommerce' );
	endif; ?></h1>
    <hr class="wp-header-end">
    <div class="tablenav top">
        <div class="alignleft actions">
            <p style="display: inline;" class="search-box">
            <form id="searchkeyword2" method="GET" action="<?php echo admin_url(); echo 'admin.php' ?>">
                <input type="hidden" name="page" value="wc-note-finder"/>
                <input type="search" id="post-search-input" name="searchkeyword" action="<?php echo admin_url(); echo 'admin.php' ?>" value="<?php echo esc_attr( $searchkeyword ); ?>">
                <input type="submit" id="search-submit" class="button" value="<?php esc_html_e( 'Search notes', 'note-finder-for-woocommerce' ); ?>">
            </form>
            </p>
        </div>
        <div class="alignright actions">
            <p style="display: inline;"><?php esc_html_e( 'Number of items per page:', 'note-finder-for-woocommerce' ); ?></p>
            <p class="search-box">

            <form id="number2" style="display: inline;" method="GET" action="<?php echo admin_url();
			echo 'admin.php' ?>">
                <input type="hidden" name="page" value="wc-note-finder"/>
                <input type="number" step="1" min="1" max="999" maxlength="3" name="number" value="<?php echo esc_attr( $number ); ?>">
                <input type="submit" class="button" value="<?php esc_html_e( 'Apply', 'note-finder-for-woocommerce' ); ?>">
            </form>
            </p>
        </div>
        <br class="clear">
    </div>
    <table class="wp-list-table widefat fixed striped posts">
        <thead>
        <tr>
            <th scope="col" class="manage-column" style="width: 80px"> <?php esc_html_e( 'Order', 'note-finder-for-woocommerce' ); ?></th>
            <th scope="col" class="manage-column"><?php esc_html_e( 'Note', 'note-finder-for-woocommerce' ); ?></th>
            <th scope="col" class="manage-column"><?php esc_html_e( 'Date & Time', 'note-finder-for-woocommerce' ); ?></th>
            <th scope="col" class="manage-column"><?php esc_html_e( 'Note to customer?', 'note-finder-for-woocommerce' ); ?></th>
        </tr>
        </thead>
        <tbody id="the-list">
		<?php
		$args = array(
			'post_id' => 0,
			'orderby' => 'comment_ID',
			'order'   => 'DESC',
			'approve' => 'approve',
			'type'    => 'order_note',
			'number'  => (int) $number,
			'offset'  => (int) $offset,
			'search'  => sanitize_text_field( $searchkeyword ),

		);
if ( function_exists('icl_object_id') ) {
    global $sitepress;
    remove_filter( 'comments_clauses', array( $sitepress, 'comments_clauses' ), 10, 2 );
}
remove_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ), 10, 1 );
$notes = get_comments( $args );
add_filter( 'comments_clauses', array( 'WC_Comments', 'exclude_order_comments' ), 10, 1 );
if ( function_exists('icl_object_id') ) {
    add_filter( 'comments_clauses', array( $sitepress, 'comments_clauses' ), 10, 2 );
}
		if ( $notes ) {
			foreach ( $notes as $note ) {
							$post_id = $note->comment_post_ID;
							$order   = wc_get_order( $post_id );

							if ( ! $order ) {
								continue;
							}

							$order_id = $order->get_order_number();
				?>
                <tr>
                    <td style="width: 60px">
                        <a href="<?php echo esc_html( get_edit_post_link( $post_id ) ); ?>"><strong>#<?php echo $order_id; ?></strong></a>
                    </td>
                    <td>
						<?php echo wp_strip_all_tags( wpautop( wptexturize( wp_kses_post( $note->comment_content ) ) ) ); ?>
                    </td>
                    <td>
						<?php printf( esc_html__( '%1$s %2$s', 'note-finder-for-woocommerce' ), date_i18n( wc_date_format(), strtotime( $note->comment_date ) ), date_i18n( wc_time_format(), strtotime( $note->comment_date ) ) ); ?><?php if ( $note->comment_author != 'WooCommerce' ) {
							printf( ' ' . esc_html__( 'by %s', 'note-finder-for-woocommerce' ), $note->comment_author );
						} ?>
                    </td>
                    <td>
						<?php
						if ( get_comment_meta( $note->comment_ID, 'is_customer_note', true ) === '1' ) {
							echo '<i class="dashicons dashicons-yes"></i>';
						}
						?>
                    </td>
                </tr>
				<?php
			}
		}
		?>
        </tbody>
        <tfoot>
        <tr>
            <th scope="col" class="manage-column" style="width: 60px">
				<?php esc_html_e( 'Order', 'note-finder-for-woocommerce' ); ?>
            </th>
            <th scope="col" class="manage-column">
				<?php esc_html_e( 'Note', 'note-finder-for-woocommerce' ); ?>
            </th>
            <th scope="col" class="manage-column">
				<?php esc_html_e( 'Date & Time', 'note-finder-for-woocommerce' ); ?>
            </th>
            <th scope="col" class="manage-column">
				<?php esc_html_e( 'Note to customer', 'note-finder-for-woocommerce' ); ?>
            </th>
        </tr>
        </tfoot>
    </table>
    <div class="tablenav bottom">
        <div class="alignleft actions">
            <p class="search-box">
            <form id="searchkeyword2" method="GET" action="<?php echo admin_url();
			echo 'admin.php' ?>">
                <input type="hidden" name="page" value="wc-note-finder"/>
                <input type="search" id="post-search-input" name="searchkeyword" action="<?php echo admin_url();
				echo 'admin.php' ?>" value="<?php echo esc_attr( $searchkeyword ); ?>">
                <input type="submit" id="search-submit" class="button"
                       value="<?php esc_html_e( 'Search notes', 'note-finder-for-woocommerce' ); ?>">
            </form>
            </p>
        </div>
    </div>