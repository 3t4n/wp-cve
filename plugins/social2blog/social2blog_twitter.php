<?php

if( !defined( 'ABSPATH' ) ) exit;

$action = $_REQUEST["action"];

/**
 * ACTION
 */
try {
	if ($action == "save-twitter-card") {
		$social2blogtwitter->saveTwitterCard($_REQUEST);
	} elseif ($action == "remove-twitter-card") {
		$social2blogtwitter->removeTwitterCard();
	}
} catch (Exception $e) {
	echo "<div class='notice error my-acf-notice is-dismissible' ><p>".$e->getMessage()."</p></div>";
}



$tags_string = $social2blogtwitter->getTags();
$social2blogtwitter->retrivePostFoto();
$tags = explode(' ', $tags_string);


	$authors = get_users('who=authors', 'orderby=display_name' );
	
	$user =  get_user_by( "login", $social2blogtwitter->getAuthorPost() );

	$author_post = $social2blogtwitter->getAuthorPost(); 
	$auth = empty($author_post) ? "" : "<option selected value='".$social2blogtwitter->getAuthorPost()."'>".$user->data->display_name."</option>";
	foreach ($authors as $author) {
		$auth .= $author->user_login != $social2blogtwitter->getAuthorPost() ? "<option value='".$author->user_login."'>".$author->display_name."</option>" : "";
	}

?>
<script>
var errorHashtagformat = "<?php echo __("Formato HASHTAG errato", "social2blog-text"); ?>";
jQuery( document ).ready(function($) {
	var tag_ischange = false;
	jQuery(".tm-input").tagsManager({
        deleteTagsOnBackspace: false,
        delimiters: [9, 13, 32, 44],
        validator: checkHashtag
    	});
	
	jQuery(".tm-input").bind("paste", function(e){
	    // access the clipboard using the api
	    e.preventDefault();
	    var pastedData = e.originalEvent.clipboardData.getData('text');
	    var regex =/(^|\s)([#][a-z-A-Z\d_]{2,140})$/;

		var tag = pastedData.match(/(\w){1,}/);
		checkHash = tag[0].search("#");

		if (checkHash == "-1"){
			tag = "#"+tag[0];
		}

	    jQuery("#tags").val(tag);

	} );
	
	window.onbeforeunload = function(){
		if ( tag_ischange == true )
			return "<?php echo __( 'Modifiche non salvate. Cambiando pagina le modifiche non salvate andranno perse.', 'social2blog-text' );?>";
	};
	

	function checkHashtag(tag){

		var regex =/(^|\s)([#][a-z-A-Z\d_]{2,140})$/;
		checkHash = tag.search("#");

		if (checkHash == "-1"){
			$("#messageTag").html(errorHashtagformat);
			return false;
		}
		var result = tag.match( regex );
		if (result != null){
			$("#messageTag").html("");
			return true;
		}
		$("#messageTag").html(errorHashtagformat);
			return false;
	}

	jQuery(".tm-input").on('tm:pushed', function(e, tag) {
		tag_ischange = true;
	});

	jQuery(".tm-input").on('tm:spliced', function(e, tag) {
		tag_ischange = true;
	});

	$( "#cancellaTwitter" ).on( "click", function() {
		tag_ischange = false;
		$('#action').val("remove-twitter-card");
		 var url = "<?php echo SOCIAL2BLOG_LOCALURL?>-twitter"; // the script where you handle the form input.

		    $.ajax({
		           type: "POST",
		           url: url,
		           data: $("#form_pg").serialize(), // serializes the form's elements.
		           success: function(data)
		           {
		        	   location.reload();
		           }
			});
	});
	$("#form_pg").submit(function(e) {
		tag_ischange = false;
		var tags = $("input[name='hidden-tags']").val();
		var statusPost = $("select[name='statusPost']").val();

		if (tags == "" || typeof tags === 'undefined') {
	    	alert('Tags required');
	    	return false;
		}

	});


	<?php
	if (!empty($tags)) {
		for($i=0; $i< count($tags); $i++){ 	?>
		jQuery(".tm-input").tagsManager('pushTag','<?php echo $tags[$i]; ?> ');
	<?php }
	} ?>

	tag_ischange = false;
});
</script>



<div class="wrap">
<h1 class="title_xwp"><img src='<?php echo plugin_dir_url( __FILE__ )."icon.png"?>' style="margin-right: 10px" />
<span class="logoso">Social</span><span class="logo2">2</span><span class="logowp">Blog</span></h1>
<h2 class="nav-tab-wrapper">
	<a href="<?php echo SOCIAL2BLOG_LOCALURL?>" class="nav-tab nav-tab"><?php echo __( 'Generale', 'social2blog-text' )?></a>
		<a <?php echo $social2blogfacebook->isFBConnected() ? "href=".SOCIAL2BLOG_LOCALURL."-facebook" : ""?> class="nav-tab nav-tab">Facebook</a>
		<a <?php echo $social2blogtwitter->isTWConnected() ? "href=".SOCIAL2BLOG_LOCALURL."-twitter" : ""?> class="nav-tab nav-tab-active">Twitter</a>
		<a <?php echo "href=".SOCIAL2BLOG_LOCALURL."-instagram"; ?>  class="nav-tab nav-tab">Instagram</a>
		<a <?php echo "href=".SOCIAL2BLOG_LOCALURL."-gallery"; ?>  class="nav-tab nav-tab">Gallery</a>
</h2>
<h2><?php echo __( 'Manage Twitter', 'social2blog-text' )?></h2>
<form id='form_pg' class='select_pg' action='<?php echo SOCIAL2BLOG_LOCALURL?>-twitter' method='POST'>
	<div id="sp_twitter" class="postbox s2b-postbox">
		<h1 class="hndle">
			<span class="subtitle_xwp">Twitter</span>
		</h1>
			<div class="inside_xwp">
				<div class="text_label">
					<b>#Hashtag:</b>
				</div>
				<div class="input_xwp">
					<input type="text" name="tags" id="tags" placeholder="Tags" class='tm-input tm-tag-large' />
					<div id="messageTag"></div>
				</div>
			</div>

			<div class="inside_xwp">
				<div class="text_label">
					<b><?php echo __( 'Stato predefinito:', 'social2blog-text' )?></b>
				</div>
				<div class="input_xwp">
				<select name='statusPost' >
					<option <?php echo $social2blogtwitter->retriveStatusPost()=="0" ? "selected" : "";?> value='0'><?php echo __( 'Bozza', 'social2blog-text' )?></option>
					<option <?php echo $social2blogtwitter->retriveStatusPost()=="1" ? "selected" : "";?> value='1'><?php echo __( 'Pubblica', 'social2blog-text' )?></option>
				</select>
				</div>
			</div>

			<div class="inside_xwp">
				<div class="text_label">
				</div>
				<div class="input_xwp">
					<input type="checkbox" name="post_foto" <?php echo ($social2blogtwitter->retrivePostFoto() != "off") ? "checked value='on'" : "" ?>/><b><?php echo __( 'Solo post con foto', 'social2blog-text' )?></b>
				</div>
			</div>

			<div class="inside_xwp">
				<div class="text_label">
					<b><?php echo __( 'Autore:', 'social2blog-text' )?></b>
				</div>
				<div class="input_xwp">
				<select name='autor_post' >
					<?php echo $auth;?>
				</select>
				</div>
			</div>
<!--
		<h1 class="hndle"></h1>
		<h1 class="hndle">
			<span class="subtitle_xwp"><?php //echo __( 'Gestione titolo', 'social2blog-text' )?></span>
		</h1>
-->
			<div class="inside_xwp">
				<div class="text_label">
					<b><?php echo __( 'Costruzione titolo dei post:', 'social2blog-text' )?></b>
				</div>
				<div class="costruzione_custom">
					<div class="option_inside_xwp">
						<input type="radio" name="titolo_type" value="0"
							<?php
							echo ($social2blogtwitter->getTitle_count () == 0) ? "checked" : "";
							?> /><?php echo __( 'Fino al primo punto (? o ; o ! o .)', 'social2blog-text' )?><br />
						<br />
					</div>
					<div class="clear"></div>
					<div class="option_inside_xwp">
						<input type="radio" name="titolo_type" value="1"
							<?php
							echo ($social2blogtwitter->getTitle_count () != 0) ? "checked" : "";
							?> /><?php echo __( 'Prime', 'social2blog-text' )?>
	  					<input class="number_post_input" name="titolo_count"
							type="number"
							value="<?php echo ($social2blogtwitter->getTitle_count() != 0) ? $social2blogtwitter->getTitle_count() : "10";?>">
	  					<?php echo __( ' parole del post', 'social2blog-text' )?>
					</div>
				</div>
			</div>

			<div class="inside_xwp">
				<div class="text_label">
				</div>
				<div class="input_xwp">
				<?php
					$social2blogtwitter->getFormButton();
				?>
				</div>
			</div>
	</div>

<input type='hidden' id="action" name='action' value='save-twitter-card'/>
</form>

</div>
