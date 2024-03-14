<?php
defined('ABSPATH') or die();
?>
<div class="wrap">
	<h2 class="nav-tab-wrapper">
		<a href="?page=legal-page-generator" class="nav-tab"><?php _e( 'Main', 'legal-page-generator' ); ?></a>
		<a href="?page=legal-page-generator-pages" class="nav-tab nav-tab-active"><?php _e( 'Manage Pages', 'legal-page-generator' ); ?></a>
		<a href="?page=legal-page-generator-cr" class="nav-tab"><?php _e( 'Custom Request', 'legal-page-generator' ); ?></a>
	</h2>
	<h1><?php _e( 'Legal Pages Generator - Manage Generated Pages', 'legal-page-generator' ); ?></h1>
	<?php
		$output = array();
		// Check if supported pages already exist
	?>
	<div class="page">
		<table>
			<tbody>
				<?php foreach ( $this->supported_pages as $page_name => $page_title ) { ?>
					<?php
						$page_id = (int) get_option( 'lpg_' . $page_name . '_page_id', 0 );
						if ( $page_id < 1 ) {
							continue;
						}
					?>
					<tr>
						<td>
							<h3><?php _e( $page_title );?></h3>
						</td>
						<td>
							<?php
								$output = '<a style="margin-top: -5px;" class="button button-secondary" href="' . get_the_permalink( $page_id ) . '" target="_blank">' . __( 'View Page', 'legal-page-generator' ) . '</a>&nbsp;&nbsp;';
								$output .= '<a style="margin-top: -5px;" class="button button-secondary" href="' . get_edit_post_link( $page_id ) . '" target="_blank">' . __( 'Edit Page', 'legal-page-generator' ) . '</a>&nbsp;&nbsp;';
								$output .= '<a style="margin-top: -5px;" class="button button-secondary" href="?page=legal-page-generator-pages&remove='.$page_name.'">' . __( 'Remove Page', 'legal-page-generator' ) . '</a>';
								echo $output;
							?>
						</td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
	<p><?php
			echo __( 'Need more Legal Documents? Feel free to use our ', 'legal-page-generator' ) . '<a href="' . admin_url( 'admin.php?page=legal-page-generator-cr' ) . '">' . __( 'Custom Request Form', 'legal-page-generator' ) . '</a>';
		?>
	</p>
</div>