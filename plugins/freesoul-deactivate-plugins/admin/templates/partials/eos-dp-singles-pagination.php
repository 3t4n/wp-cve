<?php
/**
 * Template Singles Pagination.

 * @package Freesoul Deactivate Plugins
 */

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

?>
<div style="display:inline-block">
  <div class="tablenav-pages"><span class="displaying-num"><?php printf( esc_html__( '%s items', 'freesoul-deactivate-plugins' ), esc_html( $published_posts ) ); ?></span>
	<span class="pagination-links">
	<a class="button next-page<?php echo $current_page - 1 === 0 ? ' eos-no-events' : ''; ?>" href="
										 <?php
											echo esc_url(
												add_query_arg(
													array(
														'posts_per_page' => $this->posts_per_page,
														'eos_dp_post_type' => $this->post_type,
														'eos_page' => 1,
													),
													admin_url( 'admin.php?page=eos_dp_menu' )
												)
											);
											?>
		"><span class="screen-reader-text">First page</span><span aria-hidden="true">&#xab;</span></a>
	<a class="button next-page<?php echo $current_page < 2 ? ' eos-no-events' : ''; ?>" href="
										 <?php
											echo esc_url(
												add_query_arg(
													array(
														'posts_per_page' => $this->posts_per_page,
														'eos_dp_post_type' => $this->post_type,
														'eos_page' => $current_page - 1,
													),
													admin_url( 'admin.php?page=eos_dp_menu' )
												)
											);
											?>
		"><span class="screen-reader-text">Previous page</span><span aria-hidden="true">&#x2039;</span></a>
	  <span class="paging-input">
		<label class="screen-reader-text"><?php esc_html_e( 'Current Page', 'freesoul-deactivate-plugins' ); ?></label>
		<input data-url="<?php echo esc_url( add_query_arg( 'eos_dp_post_type', $this->post_type, admin_url( 'admin.php?page=eos_dp_menu' ) ) ); ?>" class="current-page current-page-selector" type="number" min="1" max="<?php echo esc_attr( $pagesN ); ?>" step="1" name="paged" value="<?php echo esc_attr( $current_page ); ?>" size="1" aria-describedby="table-paging">
		<span class="tablenav-paging-text"> of <span class="total-pages"><?php echo esc_html( $pagesN ); ?></span>
	  </span>
	</span>
	<a class="button next-page<?php echo $current_page - $pagesN == 0 ? ' eos-no-events' : ''; ?>" href="
										 <?php
											echo esc_url(
												add_query_arg(
													array(
														'posts_per_page' => $this->posts_per_page,
														'eos_dp_post_type' => $this->post_type,
														'eos_page' => $current_page + 1,
													),
													admin_url( 'admin.php?page=eos_dp_menu' )
												)
											);
											?>
	"><span class="screen-reader-text">Next page</span><span aria-hidden="true">&#8250;</span></a>
	<a class="button last-page<?php echo $current_page - $pagesN == 0 ? ' eos-no-events' : ''; ?>" href="
										 <?php
											echo esc_url(
												add_query_arg(
													array(
														'posts_per_page' => $this->posts_per_page,
														'eos_dp_post_type' => $this->post_type,
														'eos_page' => $pagesN,
													),
													admin_url( 'admin.php?page=eos_dp_menu' )
												)
											);
											?>
	"><span class="screen-reader-text">Last page</span><span aria-hidden="true">&#187;</span></a></span>
  </div>
</div>
