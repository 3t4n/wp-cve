<?php
/*
 * Manage the worpdress attachments, apply alt TAG to make accessibility valid.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // disable direct access
}
$oct_filter     = isset( $_GET['oc-filter'] ) ? $_GET['oc-filter'] : "to-fix";
$items_per_page = 15;
?>
<div class="wrap accessibilty-wrapper">
    <h1><?php esc_html_e( 'Accessibility - Manage Images (Add alt attribute)', 'enable-accessibility' ) ?></h1>
    <p><?php esc_html_e( 'It is very important for accessibility measures and great for your SEO as well.', 'enable-accessibility' ) ?></p>

    <div class="accessibility-inner">
		<?php
		$paged          = ( get_query_var( 'paged' ) ) ? intval( get_query_var( 'paged' ) ) :
			( ( ! empty( $_GET['paged'] ) && is_numeric( $_GET['paged'] ) ) ? intval( $_GET['paged'] ) : 1 );
		$getAttachments = $this->get_attachments();

		$p_data      = array();
		$with_alt    = 0;
		$without_alt = 0;
		foreach ( $getAttachments as $a ) {
			setup_postdata( $a );
			$alt_meta = get_post_meta( $a->ID, '_wp_attachment_image_alt', true );
			$has_alt  = ! ( $alt_meta == "" || empty( $alt_meta ) );

			$skip_post = false;
			if ( $oct_filter == 'to-fix' && $has_alt ) {
				$skip_post = true;
			}
			if ( $has_alt ) {
				$with_alt ++;
			} else {
				$without_alt ++;
			}
			if ( false === wp_get_attachment_image_src( $a->ID ) ) {
				$skip_post = true;
			}

			if ( ! $skip_post ) {
				$p_data[] = [
					'post_id'    => (int)$a->ID,
					'meta_alt'   => sanitize_text_field( $alt_meta ),
					'src'        => wp_get_attachment_image_src( $a->ID ) ? wp_get_attachment_image_src( $a->ID )[0] : '',
					'post_title' => sanitize_text_field( $a->post_title ),
					'view_url'   => get_permalink( $a->ID ),
					'edit_url'   => get_edit_post_link( $a->ID ) ? get_edit_post_link( $a->ID ) : '',
					'suggestion' => sanitize_text_field( $a->parent_title ),
				];
			}
		}


		$item_firstkey = 0 + ( $paged - 1 ) * $items_per_page;
		$pageData      = array_slice( $p_data, $item_firstkey, $items_per_page );
		?>

        <div class="acc-atttachments-form-wrapper">
            <ul class="subsubsub">
				<?php $query_string = remove_query_arg( array( 'oc-filter', 'paged' ) ); ?>
                <li class="all">
                    <a href="<?php echo esc_attr( add_query_arg( 'oc-filter', "all", $query_string ) ); ?>"
                       class="<?php if ( $oct_filter == 'all' ): ?>current<?php endif; ?>">
						<?php esc_html_e( 'All' ); ?> <span class="count">(<?php echo count( $getAttachments ); ?>)</span>
                    </a> |
                </li>
                <li class="missingAlt">
                    <a href="<?php echo add_query_arg( 'oc-filter', "to-fix", $query_string ); ?>"
                       class="<?php if ( $oct_filter == 'to-fix' ): ?>current<?php endif; ?>">
						<?php esc_html_e( 'To Fix', 'enable-accessibility' ); ?> <span
                                class="count">(<?php echo esc_html( $without_alt ); ?>)</span>
                    </a></li>
            </ul>
            <form class="oc-accessiblity-attachments-form" method="post"
                  action="<?php echo esc_attr( $_SERVER['REQUEST_URI'] ); ?>">
                <div class="tablenav top">
                    <div class="alignleft actions">
                        <input type="button" class="button action oc-accept-all-suggestion"
                               value="<?php esc_html_e( 'Apply All Suggestions', 'enable-accessibility' ); ?>">
                        <input type="submit" class="button-primary"
                               value="<?php esc_html_e( 'Save Changes', 'enable-accessibility' ) ?>"/>
                    </div>
                </div>
                <input type="hidden" name="action" value="save_accessibility_attachments_settings">
				<?php wp_nonce_field( 'save_accessibility_attachments_settings' ); ?>
                <table class="wp-list-table widefat fixed striped oct-admin-table oct-filter-<?php echo esc_attr( $oct_filter ); ?>">
                    <thead>
                    <tr>
                        <th class="dashicons-admin-media dashicons-before"></th>
                        <th><?php esc_html_e( "Title" ); ?></th>
                        <th><?php esc_html_e( "alt Attribute" ); ?></th>
                        <th><?php esc_html_e( "Actions" ); ?></th>
                    </tr>
                    </thead>
                    <tbody>
					<?php if ( $pageData ) : ?>
						<?php foreach ( $pageData as $data ): ?>
                            <tr class="<?php if ( empty( $data['meta_alt'] ) || $data['meta_alt'] == '' ): ?>oc-markRow<?php endif; ?>">
                                <td>
                                    <img src="<?php echo esc_attr( $data['src'] ); ?>" title="<?php echo esc_attr( $data['post_title'] ); ?>"/>
                                </td>
                                <td class="title_box">
                                    <input type="text" name="attachments_title[<?php echo esc_attr( $data['post_id'] ); ?>]"
                                           placeholder="- title -"
                                           value="<?php echo esc_attr( $data['post_title'] ); ?>"/>
                                    <p><span class="oc-accept-suggestion"
                                             title="<?php esc_attr_e( 'Apply' ); ?>">&#x25B2;</span> <span
                                                class="oc-suggestion"><?php echo esc_html( $data['suggestion'] ); ?></span></p>
                                </td>
                                <td class="alt_box">
                                    <input type="text" name="attachments_alt[<?php echo esc_attr( $data['post_id'] ); ?>]"
                                           value="<?php echo esc_attr( $data['meta_alt'] ); ?>"
                                           placeholder="- alt -"/>
                                    <p><span class="oc-accept-suggestion"
                                             title="<?php esc_attr_e( 'Apply' ); ?>">&#x25B2;</span> <span
                                                class="oc-suggestion"><?php echo esc_html( $data['suggestion'] ); ?></span></p>
                                </td>
                                <td>
                                    <a href="<?php echo $data['edit_url']; ?>">
										<?php esc_html_e( "Edit" ) ?>
                                    </a>
                                    &nbsp;|&nbsp;
                                    <a href="<?php echo $data['view_url']; ?>">
										<?php esc_html_e( "View", $this->plugin_slug ) ?>
                                    </a>
                                </td>
                            </tr>
							<?php
						endforeach;
					endif;
					?>
                    </tbody>
                </table>
                <p class="submit">
                    <input type="submit" class="button-primary"
                           value="<?php esc_attr_e( 'Save Changes', 'enable-accessibility' ) ?>"/>
                </p>
            </form>
            <ul class="oc-pager">
				<?php
				$p_query_string = remove_query_arg( array( 'paged' ) );
				$page_count     = ceil( count( $p_data ) / $items_per_page );
				if ( $paged != 1 ) {
					echo '<li><a href="' . add_query_arg( 'paged', $paged - 1, $p_query_string ) . '">'
					     . '<span class="screen-reader-text">' . esc_html__( 'Previous Page' ) . '</span><span aria-hidden="true">‹</span></a></li>';
				}

				for ( $i = 1; $i <= $page_count; $i ++ ) {
					$class = ( $i == $paged ) ? 'oc-pager-active' : '';
					echo '<li class="' . $class . '"><a href="' . add_query_arg( 'paged', $i, $p_query_string ) . '">' . $i . '</a></li>';
				}
				if ( $paged != $page_count ) {
					echo '<li><a href="' . add_query_arg( 'paged', $paged + 1, $p_query_string ) . '">'
					     . '<span class="screen-reader-text">' . esc_html__( 'Next Page' ) . '</span><span aria-hidden="true">›</span></a></li>';
				}
				?>
            </ul>
        </div>
    </div>
</div>

<script>
    jQuery('body').on('click', '.oc-accept-suggestion', function () {
        var containingField = jQuery(this).closest('td');
        containingField.find('input').val(containingField.find('.oc-suggestion').text().trim());
    });
    jQuery('body').on('click', '.oc-accept-all-suggestion', function () {
        jQuery('.oc-accept-suggestion').each(function () {
            var containingField = jQuery(this).closest('td');
            containingField.find('input').val(containingField.find('.oc-suggestion').text().trim());
        });
    });
</script>
