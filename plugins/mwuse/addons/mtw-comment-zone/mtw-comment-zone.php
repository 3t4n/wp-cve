<?php

function mtw_comment_form( $atts ) 
{

	global $post;
	global $postid;
	$postid = get_the_ID();

	$atts = shortcode_atts( array(
		'avatar_size' => 32
	), $atts );

	$args = array(
		'title_reply' =>  '',
		'title_reply_before' => false,
		);

	ob_start();

	?>
	<div class="mtw-comment-zone">
		<?php comment_form( $args ); ?>
		<div class="mtw-comment-list">
		
			<?php 
			$comments = get_comments(array(
	            'post_id' => $postid,
	            'status' => 'approve', //Change this to the type of comments to be displayed      
	        ));
	    	?>

	    	<?php 
	    	wp_list_comments( array( 
	    	'style' => 'div', 
	    	'echo' => true,
	    	'type' => 'all',
	    	'avatar_size' => $atts['avatar_size']
	    	), $comments); 
	    	?>
		</div>
		<div style="clear: both;"></div> 
	</div>
	<?php

	return ob_get_clean();
	
}
add_shortcode( 'mtw_comment_form','mtw_comment_form' );


function mtw_comment_form_default_fields( $fields )
{
	unset( $fields['url'] );
	return $fields;
}
add_filter( 'comment_form_default_fields' , 'mtw_comment_form_default_fields' );
?>