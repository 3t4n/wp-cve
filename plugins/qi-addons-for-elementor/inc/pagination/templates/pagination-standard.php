<?php if ( 'yes' === $enable_pagination && ! is_singular( 'post' ) && isset( $query_result ) && intval( $query_result->max_num_pages ) > 1 ) { ?>
	<div class="qodef-addons-m-pagination qodef--standard">
		<nav class="navigation pagination" role="navigation" aria-label="<?php esc_attr_e( 'Posts', 'qi-addons-for-elementor' ); ?>">
			<h2 class="screen-reader-text"><?php esc_html_e( 'Posts navigation', 'qi-addons-for-elementor' ); ?></h2>
			<div class="nav-links">
				<?php
				$prev_text = qi_addons_for_elementor_get_template_part( 'pagination', 'templates/parts/arrow-left', '', $params );
				$next_text = qi_addons_for_elementor_get_template_part( 'pagination', 'templates/parts/arrow-right', '', $params );
				$current   = is_front_page() ? max( 1, get_query_var( 'page' ) ) : max( 1, get_query_var( 'paged' ) );

				echo paginate_links(
					array(
						'prev_text' => $prev_text,
						'next_text' => $next_text,
						'current'   => $current,
						'total'     => $query_result->max_num_pages,
					)
				);
				?>
			</div>
		</nav>
	</div>
<?php } ?>
