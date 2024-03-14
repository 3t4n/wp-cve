<?php
defined( 'ABSPATH' ) || exit;

function yahman_addons_site_map(){

	?>
	<div class="sitemap">
		<ul class="sitemap_list m0" style="list-style:none;">
			<li class="sitemap_home">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
			</li>
			<?php
			yahman_addons_make_sitemap_tree(0,1);

			$option =  get_option('yahman_addons') ;
			$exclude_id = isset($option['sitemap']['exclude']) ? $option['sitemap']['exclude']: '';
			$exclude_tree_id = isset($option['sitemap']['exclude_tree']) ? $option['sitemap']['exclude_tree']: '';


			$args = array(
				'authors'      => '',
				'child_of'     => 0,
				'date_format'  => get_option( 'date_format' ),
				'depth'        => 0,
				'echo'         => 1,
				'exclude'      => $exclude_id,
				'exclude_tree' => $exclude_tree_id,
	//'include'      => '',
				'link_after'   => '',
				'link_before'  => '',
				'post_type'    => 'page',
				'post_status'  => 'publish',
				'show_date'    => '',
				'sort_column'  => 'menu_order, post_title',
				'sort_order'   => '',
	//'title_li'     => __('Pages'),
				'walker'       => new Walker_Page
			);
			wp_list_pages($args);
			?>
		</ul>
	</div>
	<?php


}

function yahman_addons_make_sitemap_tree($parent_id, $cat_ul_use_flag){

	$categories = get_terms('category', array(
		'parent'    => $parent_id,
	));

	if($categories){
		foreach($categories as $category_values){
			echo '<li class="cat-item cat-item-' . esc_attr( $category_values->term_id ) . '"><a href="'.esc_url( get_category_link($category_values->term_id)).'" >'.esc_html( $category_values->name ).'</a>'."\n";
			$posts = get_posts(array('numberposts'=>100, 'category'=>$category_values->term_id));


			$arg_ul_use_flag = 1;
			if($posts){
				echo '<ul style="list-style:none;">'."\n";
				foreach($posts as $post_values){
					echo '<li class="post-item post-item-' . esc_attr( $post_values->ID ) . '"><a href="'.esc_url( get_permalink($post_values->ID) ).'">'.esc_html( $post_values->post_title ).'</a></li>'."\n";
				}
				$arg_ul_use_flag = 0;
			}
			yahman_addons_make_sitemap_tree($category_values->term_id, $arg_ul_use_flag);
			if($posts) echo '</ul>'."\n";
			echo '</li>'."\n";
		}
	}



}

