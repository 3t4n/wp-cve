<?php


$embed_link_button = 1;

/*Load Embed Scripts*/
add_action('wp_enqueue_scripts', 'qcopd_load_embed_scripts');

function qcopd_load_embed_scripts()
{
	
	wp_register_style('qcopd-embed-form-css', QCOPD_URL . 'embed/css/embed-form.css');

    wp_register_script('qcopd-embed-form-script', QCOPD_URL . 'embed/js/embed-form.js', array('jquery'));

}


// Load template for embed link page url
function qcopd_load_embed_link_template($template)
{
    if (is_page('embed-link')) {
        return dirname(__FILE__) . '/qcopd-embed-link.php';
    }
    return $template;
}

add_filter('template_include', 'qcopd_load_embed_link_template', 99);


// Create embed page when plugin install or activate

//register_activation_hook(__FILE__, 'qcopd_create_embed_page');
add_action('init', 'qcopd_create_embed_page');

function qcopd_create_embed_page()
{

	//print_r(get_option('hclpage'));exit;


    $query = new WP_Query(
        array(
            'post_type'              => 'page',
            'title'                  => 'Embed Link',
            'post_status'            => 'all',
            'posts_per_page'         => 1,
            'no_found_rows'          => true,
            'ignore_sticky_posts'    => true,
            'update_post_term_cache' => false,
            'update_post_meta_cache' => false,
            'orderby'                => 'post_date ID',
            'order'                  => 'ASC',
        )
    );
     
    $page_got_by_title = ! empty( $query->post ) ? $query->post : null;

    if ( $page_got_by_title == NULL && FALSE === get_post_status( get_option('hclpage') )) {
        //post status and options
        $post = array(
            'comment_status' => 'closed',
            'ping_status' => 'closed',
            'post_author' => get_current_user_id(),
            'post_date' => date('Y-m-d H:i:s'),
            'post_status' => 'publish',
            'post_title' => 'Embed Link',
            'post_type' => 'page',
        );
        //insert page and save the id
        $embedPost = wp_insert_post($post, false);
        //save the id in the database
        update_option('hclpage', $embedPost);
    }
}

if ($embed_link_button == 1) {
    add_action('qcsld_attach_embed_btn', 'qcld_custom_embedder');
}

function qcld_custom_embedder($shortcodeAtts)
{
    global $post;
	
	$site_title = get_bloginfo('title');
	$site_link = get_bloginfo('url');

	if( get_option( 'sld_embed_credit_title' ) != "" ){
		$site_title = get_option( 'sld_embed_credit_title' );
	}

	if( get_option( 'sld_embed_credit_link' ) != "" ){
		$site_link = get_option( 'sld_embed_credit_link' );
	}
	
    $pagename = $post->post_name;

    if ($pagename != 'embed-link') {
	
        ?>
<div class="qcopd_embed_container">




<?php if(get_option( 'sld_add_new_button' )=='on' && get_option( 'sld_add_item_link' )!=''): ?>
<a style="" href="<?php echo esc_url(get_option( 'sld_add_item_link' )); ?>" class="button-link cls-embed-btn">
<?php 
	if(get_option('sld_lan_add_link')!=''){
		echo esc_html(get_option('sld_lan_add_link'));
	}else{
		esc_html_e( 'Add New', 'qc-pd' ); 
	}
?>
</a>
<?php endif; ?>

<?php if($shortcodeAtts['enable_embedding'] == 'true'): ?>
<a class="button-link js-open-modal cls-embed-btn" href="#" data-modal-id="popup"
           data-url="<?php bloginfo('url'); ?>/embed-link"
           data-order="<?php echo $shortcodeAtts['order']; ?>"
           data-mode="<?php echo $shortcodeAtts['mode']; ?>"
           data-list-id="<?php echo $shortcodeAtts['list_id']; ?>"
           data-column="<?php echo $shortcodeAtts['column']; ?>"
           data-style="<?php echo $shortcodeAtts['style']; ?>"
           data-category="<?php echo $shortcodeAtts['category']; ?>" 
		   data-credittitle="<?php echo $site_title; ?>"
           data-creditlink="<?php echo $site_link; ?>"> 
			<?php 
				if(get_option('sld_lan_share_list')!=''){
					echo get_option('sld_lan_share_list');
				}else{
					echo esc_html__('Share List', 'qc-opd') ;
				}
			 ?>
		   <i class="fa fa-share-alt"></i> 
		   <?php
                add_action( 'wp_footer', 'sld_share_modal' );
            ?>
		   
		   </a>
<?php endif; ?>


</div>
<?php }
}

function sld_share_modal() {
	?>
	<div id="popup" class="modal-box">
	  <header> <a href="#" class="js-modal-close close">×</a>
		<h3><?php echo esc_html('Generate Embed Code For This List'); ?></h3>
	  </header>
	  <div class="modal-body">
		<div class="iframe-css">
		  <div class="iframe-main">
			<div class="ifram-row">
			  <div class="ifram-sm">
				<span><?php echo esc_html("Width: (in '%' or 'px')"); ?></span>
				<input id="igwidth" name="igwidth" type="text" value="100">
			</div>
			<div class="ifram-sm qcopd_iframe_sm" >
				<span>&nbsp;</span>
				<select name="igsizetype" class="iframe-main-select">
					<option value="%"><?php echo esc_html('%'); ?></option>
					<option value="px"><?php echo esc_html('px'); ?></option>
				</select>
			</div>
			<div class="ifram-sm">
				<span><?php echo esc_html("Height: (in 'px')"); ?></span>
				<input id="igheight" name="igheight" type="text" value="400">
			</div>
			  <div class="ifram-sm"> <span>&nbsp;</span> <a class="btn icon icon-code" id="generate-igcode" onclick=""><?php echo esc_html('Generate & Copy'); ?></a>
				</select>
			  </div>
			</div>
			<div class="ifram-row">
			  <div class="ifram-lg"> <span class="qcld-span-label"><?php echo esc_html('Generated Code'); ?></span> <br>
				<textarea id="igcode_textarea" class="igcode_textarea" name="igcode" readonly="readonly"></textarea>
				<p class="guideline"><?php echo esc_html('Hit "Generate & Copy" button to generate embed code. It will be copied to your Clipboard. You can now paste this embed code inside your website\'s HTML where you want to show the List.'); ?></p>
			  </div>
			</div>
		  </div>
		</div>
	  </div>
	</div>
	<?php
}
