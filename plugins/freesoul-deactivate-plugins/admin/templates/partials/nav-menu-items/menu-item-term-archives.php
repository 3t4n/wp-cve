<?php
/**
 * Template Menu Items Archives.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

$taxs = get_taxonomies( array(), 'objects' );
if ( $taxs ) {
	foreach ( $taxs as $tax ) {
		if ( '1' == $tax->public && isset( $tax->object_type ) ) {
			$show         = false;
			$labels_names = array();
			foreach ( $tax->object_type as $term_post_type ) {
				if ( in_array( $term_post_type, $post_types ) ) {
					$show = true;

					$postTypeObj    = get_post_type_object( $term_post_type );
					$labels         = get_post_type_labels( $postTypeObj );
					$labels_names[] = isset( $labels->name ) ? $labels->name : $term_post_type;
				}
			}
			if ( $show ) {
				?>
		<li class="eos-dp-submenu-item"><a href="<?php echo esc_url( add_query_arg( 'eos_dp_tax', $tax->name, admin_url( 'admin.php?page=eos_dp_by_term_archive' ) ) ); ?>"><?php printf( esc_html__( '%1$s (%2$s)', 'freesoul-deactivate-plugins' ), esc_html( $tax->label ), esc_html( implode( ',', $labels_names ) ) ); ?></a></li>
				<?php
			}
		}
	}
}
