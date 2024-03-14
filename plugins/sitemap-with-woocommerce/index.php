<?php

/*

Plugin Name: Sitemap with woocommerce

Plugin URI:  http://wpproject.weblink4you.com/wordpress-sitemap-with-woocommerce-plugin/

Description: Create sitemap page by using shortcodes, separate shortcodes for posts, pages and woocommerce products woocommerce category.

Version: 1.0

Author: subash chandra pandey 

Author URI: http://wpproject.weblink4you.com/wordpress-sitemap-with-woocommerce-plugin/

Text Domain: sitemap-with-woocommerce

Domain Path: /languages

License: GPLv2 or later

*/



//Display Posts in Sitemap

if ( !function_exists('pagelist_sbwpes_add_stylesheet') ) {

	function pagelist_sbwpes_add_stylesheet() {

		wp_enqueue_style( 'page-list-style', plugins_url( '/css/page-list.css', __FILE__ ), false, '4.2', 'all' );

	}

	add_action('wp_print_styles', 'pagelist_sbwpes_add_stylesheet');

}



function sbwpes_sitemap_post() { ?>

    <h2><?php _e('Posts', 'easy-sitemap' ); ?>:</h2>

				<ul class="sitemap">

				<?php

				//http://codex.wordpress.org/Function_Reference/get_categories

				$cats = get_categories('exclude='); //***Exclude categories by ID, separated by comma if you like.

				 

				foreach ($cats as $cat) {

				  echo '<li class="category">'."\n".'<h3><span class="grey">Category: </span>'.$cat->cat_name.'</h3>'."\n";

				  echo '<ul class="cat-posts">'."\n";

				   

				  //http://codex.wordpress.org/Function_Reference/query_posts

				  query_posts('posts_per_page=-1&cat='.$cat->cat_ID); //-1 shows all posts per category. 1 to show most recent post.

					 

				  //http://us3.php.net/while ; http://codex.wordpress.org/The_Loop ; http://codex.wordpress.org/The_Loop_in_Action

				  //http://codex.wordpress.org/Function_Reference/the_time ;  http://codex.wordpress.org/Function_Reference/the_permalink 

				  //http://codex.wordpress.org/Function_Reference/the_title ; http://codex.wordpress.org/Function_Reference/comments_number

				  while(have_posts()): the_post(); 

					 //http://codex.wordpress.org/Function_Reference/get_the_category

					 $category = get_the_category();

					 //Display a post once, even if it is in multiple categories/subcategories. Lists the post in the first Category displayed.

					 if ($category[0]->cat_ID == $cat->cat_ID) {?>

						<li><?php the_time('M d, Y')?> &raquo; <a href="<?php the_permalink() ?>"  title="Permanent Link to: <?php the_title(); ?>">

						<?php the_title(); ?></a> </li>

				   <?php } //endif

					endwhile; //endwhile

				   ?>

				  </ul> 

				  </li>

				<?php } ?>

				</ul>

				<?php 

				//http://codex.wordpress.org/Function_Reference/wp_reset_query

				wp_reset_query(); 

				?>

	<?php  }   

add_shortcode( 'SitemapPost', 'sbwpes_sitemap_post' );





//Display Pages in Sitemap

function sbwpes_sitemap_page($args = '') { ?>

		<h2><?php _e('Pages', 'easy-sitemap' ); ?>:</h2>

			<ul class="sitemap">

			<?php 

			//http://codex.wordpress.org/Function_Reference/wp_list_pages

			$defaults = array(

        'depth'        => 0,

        'show_date'    => '',

        'date_format'  => get_option( 'date_format' ),

        'child_of'     => 0,

        'exclude'      => '',

        'title_li'     => __( 'Pages' ),

        'echo'         => 1,

        'authors'      => '',

        'sort_column'  => 'menu_order, post_title',

        'link_before'  => '',

        'link_after'   => '',

        'item_spacing' => 'preserve',

        'walker'       => '',

    );

 

    $r = wp_parse_args( $args, $defaults );

 

    if ( ! in_array( $r['item_spacing'], array( 'preserve', 'discard' ), true ) ) {

        // invalid value, fall back to default.

        $r['item_spacing'] = $defaults['item_spacing'];

    }

 

    $output = '';

    $current_page = 0;

 

    // sanitize, mostly to keep spaces out

    $r['exclude'] = preg_replace( '/[^0-9,]/', '', $r['exclude'] );

 

    // Allow plugins to filter an array of excluded pages (but don't put a nullstring into the array)

    $exclude_array = ( $r['exclude'] ) ? explode( ',', $r['exclude'] ) : array();

 

    /**

     * Filters the array of pages to exclude from the pages list.

     *

     * @since 2.1.0

     *

     * @param array $exclude_array An array of page IDs to exclude.

     */

    $r['exclude'] = implode( ',', apply_filters( 'wp_list_pages_excludes', $exclude_array ) );

 

    // Query pages.

    $r['hierarchical'] = 0;

    $pages = get_pages( $r );

 

    if ( ! empty( $pages ) ) {

        if ( $r['title_li'] ) {

            $output .= '<ul>';

        }

        global $wp_query;

        if ( is_page() || is_attachment() || $wp_query->is_posts_page ) {

            $current_page = get_queried_object_id();

        } elseif ( is_singular() ) {

            $queried_object = get_queried_object();

            if ( is_post_type_hierarchical( $queried_object->post_type ) ) {

                $current_page = $queried_object->ID;

            }

        }

 

        $output .= walk_page_tree( $pages, $r['depth'], $current_page, $r );

 

        if ( $r['title_li'] ) {

            $output .= '</ul>';

        }

    }

 

    /**

     * Filters the HTML output of the pages to list.

     *

     * @since 1.5.1

     * @since 4.4.0 `$pages` added as arguments.

     *

     * @see wp_list_pages()

     *

     * @param string $output HTML output of the pages list.

     * @param array  $r      An array of page-listing arguments.

     * @param array  $pages  List of WP_Post objects returned by `get_pages()`

     */

    $html = apply_filters( 'wp_list_pages', $output, $r, $pages );

 

    if ( $r['echo'] ) {

        echo $html;

    } else {

        return $html;

    }

			//wp_list_pages('exclude=&title_li='); //***Exclude page Id, separated by comma. I excluded the sitemap of this blog (page_ID=889).

			?>

			</ul>  

	<?php

}

add_shortcode( 'SitemapPage', 'sbwpes_sitemap_page' );

	

//Display Woocommerce Products in Sitemap

function sbwpes_sitemap_product() { ?>

    <h2><?php _e('Products', 'easy-sitemap' ); ?>:</h2>

		<ul class="sitemap">

		<?php $loop = new WP_Query( array( 

			'post_type' => 'product', 

			'posts_per_page' => 1000,

			'orderby' => 'title',

			'order'   => 'ASC'

		 ) ); ?>

			<?php while ( $loop->have_posts() ) : $loop->the_post(); $post = get_post( $post_id );
			$product = wc_get_product( $post_id );
			if(empty($post->post_password)){	?>		

				<li><?php the_title( '<a href="' . get_permalink() . '" title="' . the_title_attribute( 'echo=0' ) . '" rel="bookmark">', '</a>' ); ?></li>

			<?php } endwhile; ?>

		</ul>

	<?php

}

add_shortcode( 'SitemapProduct', 'sbwpes_sitemap_product' );







//Display Woocommerce Category in Sitemap

function sbwpes_sitemap_category() { 





$args = array(

    'number'     => $number,

    'orderby'    => 'title',

    'order'      => 'ASC',

    'hide_empty' => $hide_empty,

    'include'    => $ids

);

$product_categories = get_terms( 'product_cat', $args );

$count = count($product_categories);?>

<h2><?php _e('Category', 'easy-sitemap' ); ?>:</h2>

<ul class="sitemap">

<?php if ( $count > 0 ){

    foreach ( $product_categories as $product_category ) {

        echo '<li><a href="' . get_term_link( $product_category ) . '">' . $product_category->name . '</a></li>';

       /* $args = array(

            'posts_per_page' => -1,

            'tax_query' => array(

                'relation' => 'AND',

                array(

                    'taxonomy' => 'product_cat',

                    'field' => 'slug',

                    // 'terms' => 'white-wines'

                    'terms' => $product_category->slug

                )

            ),

            'post_type' => 'product',

            'orderby' => 'title,'

        );

        $products = new WP_Query( $args );

        echo "<ul class="sitemap">";

        while ( $products->have_posts() ) {


            $products->the_post();

            ?>

                <li>

                    <a href="<?php the_permalink(); ?>">

                        <?php the_title(); ?>

                    </a>

                </li>

            <?php

        }*/

       

    }

	 echo "</ul>";

}

?>















   <!-- <h2><?php _e('Products', 'easy-sitemap' ); ?>:</h2>

		<ul class="sitemap">

		<?php $loop = new WP_Query( array( 

			'post_type' => 'product', 

			'posts_per_page' => 1000,

			'orderby' => 'title',

			'order'   => 'ASC'

		 ) ); ?>

			<?php while ( $loop->have_posts() ) : $loop->the_post(); ?>

				<li><?php the_title( '<a href="' . get_permalink() . '" title="' . the_title_attribute( 'echo=0' ) . '" rel="bookmark">', '</a>' ); ?></li>

			<?php endwhile; ?>

		</ul>-->

	<?php

}

add_shortcode( 'SitemapCategory', 'sbwpes_sitemap_category' );





// Add Quicktags

function sbwpes_add_quicktags() {

	if ( wp_script_is( 'quicktags' ) ) {

	?>

	<script type="text/javascript">

	QTags.addButton( 'SitemapPost', 'SitemapPost', '[SitemapPost]', '', '', '',  );

	QTags.addButton( 'SitemapPage', 'SitemapPage', '[SitemapPage]', '', '', '',  );

	QTags.addButton( 'SitemapProduct', 'SitemapProduct', '[SitemapProduct]', '', '', '',  );

	QTags.addButton( 'SitemapCategory', 'SitemapCategory', '[SitemapCategory]', '', '', '',  );

	</script>

	<?php

	}

}

add_action( 'admin_print_footer_scripts', 'sbwpes_add_quicktags' );