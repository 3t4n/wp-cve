<?php
/**
 * Template Menu Items Archives.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.
?>
<?php if ( 'posts' === eos_dp_get_option( 'show_on_front' )  ) { ?>
<li class="hover eos-dp-setts-menu-itemzzz">
	<span class="dashicons dashicons-admin-home" style="margin-top:1px"></span>
	<a href="<?php echo esc_url( admin_url( 'admin.php?page=eos_dp_by_archive&eos_dp_home=true' ) ); ?>"><?php esc_html_e( 'Homepage', 'freesoul-deactivate-plugins' ); ?></a>
</li>	
<?php } ?> 
<li class="hover eos-dp-setts-menu-item">
	<a href="<?php echo esc_url( admin_url( 'admin.php?page=eos_dp_by_archive' ) ); ?>"><?php esc_html_e( 'Post Type Archives', 'freesoul-deactivate-plugins' ); ?></a>
</li>
<?php
$taxs = get_taxonomies( array(), 'objects' );
if ( $taxs && is_array( $taxs ) ) {
	foreach ( $taxs as $tax ) {
		if ( '1' == $tax->public && isset( $tax->object_type ) ) {
			$show         = false;
			$labels_names = array();
			foreach ( $tax->object_type as $term_post_type ) {
				if ( in_array( $term_post_type, $post_types ) ) {
					$show           = true;
					$postTypeObj    = get_post_type_object( $term_post_type );
					$labels         = $postTypeObj ? get_post_type_labels( $postTypeObj ) : false;
					$labels_names[] = $labels && isset( $labels->name ) ? $labels->name : $term_post_type;
				}
			}
			if ( $show ) {
				?>
		<li class="eos-dp-submenu-item"><a href="<?php echo esc_url( add_query_arg( array( 'eos_dp_tax' =>  $tax->name, 'tpt' => $term_post_type ), admin_url( 'admin.php?page=eos_dp_by_term_archive' ) ) ); ?>"><?php echo esc_html( sprintf( esc_html__( '%1$s (%2$s)', 'freesoul-deactivate-plugins' ), $tax->label, implode( ',', $labels_names ) ) ); ?></a></li>
		<li class="eos-dp-submenu-item"><a href="<?php echo esc_url( add_query_arg( array( 'eos_dp_tax' => $tax->name, 'tpt' => $term_post_type, 'only-all' => '1' ), admin_url( 'admin.php?page=eos_dp_by_term_archive' ) ) ); ?>"><?php echo esc_html( sprintf( esc_html__( 'Any %1$s (%2$s)', 'freesoul-deactivate-plugins' ), $tax->label, implode( ',', $labels_names ) ) ); ?></a></li>
				<?php
			}
		}
	}
}
