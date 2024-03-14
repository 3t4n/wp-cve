<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Are we being accessed directly ?
if(!defined('SITESEO_VERSION')) {
	exit('Hacking Attempt !');
}

global $siteseo, $pagenow;
		
	$post_ID = (int) siteseo_opt_get('post');

	if(empty($post_ID)){
		return;
	}

	$post = get_post($post_ID);

	if(empty($post)){
		return;
	}

	if ( ! current_user_can( 'edit_post', $post_ID ) ) {
		wp_die( esc_html__( 'Sorry, you are not allowed to edit this item.' ) );
	}
	
	$post_type = $post->post_type;
	$post_type_object = get_post_type_object( $post_type );
	$user_ID = get_current_user_id();
	
	// Flag that we're not loading the block editor.
	$current_screen = get_current_screen();
	$current_screen->is_block_editor = 0;
	
	$form_extra = '';
	$form_action  = 'editpost';
	$nonce_action = 'update-post_' . $post_ID;
	$form_extra  .= "<input type='hidden' id='post_ID' name='post_ID' value='" . esc_attr( $post_ID ) . "' />";
	
	// Set current screen
	set_current_screen($post_type);
	
	$GLOBALS['post'] = $post;
	
	// Set temporary pagenow
	$_pagenow = $pagenow;
	$pagenow = 'post.php';
	
	$meta_box_url = admin_url( 'post.php' );		
	$meta_box_url = add_query_arg(
		array(
			'post'	=> $post->ID,
			'action'	=> 'editpost',
		),
		$meta_box_url
	);
	
	if ( ! wp_check_post_lock( $post->ID ) ) {
		$active_post_lock = wp_set_post_lock( $post->ID );
	}
	
include_once(SITESEO_MAIN.'/admin/metaboxes/admin-metaboxes.php');
?>

<style type="text/css">
body{
height: 100vh;
}

#wpcontent,
#wpbody-content,
html.wp-toolbar{
padding:0;
}

.postbox .handle-order-higher, .postbox .handle-order-lower,
#minor-publishing-actions,
.site-menu-header{
display:none !important;	
}

#adminmenumain, #wpfooter, #wpadminbar{
display:none;
}

#wpcontent{
margin:auto;
}

#siteseo_cpt{
position:fixed;
top:0;
left:0;
right:0;
bottom:0;
z-index: 999	;
overflow: auto;
height: calc(100% - 30px);
padding-bottom: 30px;
background: #fff;
}

.btnSecondary,
.is-secondary,
.btnSecondary:hover {
color: var(--white);
background: #00ac43;
border: 1px solid #067531;
padding: 10px 25px;
line-height: 1.2;
border-radius: 4px;
}
</style>
<div id="siteseo_cpt">
	<form name="post" action="post.php" method="post" onsubmit="return siteseo_post_edit(this, event)" id="post" class="siteseo-form" <?php $referer = wp_get_referer(); ?>>
	
		<?php wp_nonce_field( $nonce_action ); ?>
		
		<input type="hidden" id="user-id" name="user_ID" value="<?php echo (int) $user_ID; ?>" />
		<input type="hidden" id="hiddenaction" name="action" value="<?php echo esc_attr( $form_action ); ?>" />
		<input type="hidden" id="originalaction" name="originalaction" value="<?php echo esc_attr( $form_action ); ?>" />
		<input type="hidden" id="post_author" name="post_author" value="<?php echo esc_attr( $post->post_author ); ?>" />
		<input type="hidden" id="post_type" name="post_type" value="<?php echo esc_attr( $post_type ); ?>" />
		<input type="hidden" id="original_post_status" name="original_post_status" value="<?php echo esc_attr( $post->post_status ); ?>" />
		<input type="hidden" id="referredby" name="referredby" value="<?php echo $referer ? esc_url( $referer ) : ''; ?>" />
		<?php if ( ! empty( $active_post_lock ) ) { ?>
			<input type="hidden" id="active_post_lock" value="<?php echo esc_attr( implode( ':', $active_post_lock ) ); ?>" />
	<?php
		}
		if ( 'draft' !== get_post_status( $post ) ) {
			wp_original_referer_field( true, 'previous' );
		}

		echo wp_kses($form_extra, ['input' => ['type' => true, 'id' => true, 'value' => true, 'name' => true]]);

		wp_nonce_field( 'meta-box-order', 'meta-box-order-nonce', false );
		wp_nonce_field( 'closedpostboxes', 'closedpostboxesnonce', false );
		
		// Render meta HTML
		siteseo_cpt($post);
	?>
		
		<div class="siteseo-meta-submit-container">
			<input type="submit" class="siteseo-save-btn is-secondary" name="siteseo-submit" value="<?php esc_html_e('Save Changes'); ?>">
		</div>
	</form>
</div>
	
<script type="text/javascript">
jQuery(document).ready(function(){
	
	// Prevent the click Inside the meta pages
	siteseo_prevent_click_metas();	
});

function siteseo_post_edit(jEle, e){
	
	e.preventDefault();
	var formData = new FormData( jQuery(jEle)[0] );

	jQuery.ajax({
		url: "<?php echo esc_url($meta_box_url); ?>",
		type: "POST",
		data: formData,
		processData: false,
		contentType: false,
		cache:false,
		success:function(result){
			//window.location.reload();						
			alert("SiteSEO meta has been updated successfully !");
		},
		error:function(result){				
			alert("There is an error while updating SiteSEO meta !");
		}
	});
}

// Prevent the click Inside the meta pages
function siteseo_prevent_click_metas(){
	jQuery(document).on("submit", function(event){
		event.preventDefault();
	});
	
	jQuery(document).on("click", function(event){
		var target = jQuery(event.target);
		if (target.closest("a").length > 0) {
			event.preventDefault();
			var href = target.closest("a").attr("href");
			
			if(!href.match(/(http|https):\/\//g)){
				return;
			}
			
			var exp = new RegExp("(http|https):\/\/"+window.location.hostname, "g");
			
			// Open new window
			if(href.match(exp)){
				
				// Reload same window
				window.parent.location.assign(href);
			}else{
				window.open(href, "_blank");
			}
			
		}
	});
}
</script>

<?php
// Reset the pagenow
$pagenow = $_pagenow;