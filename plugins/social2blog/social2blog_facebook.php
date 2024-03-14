<?php
if( !defined( 'ABSPATH' ) ) exit;

global $error_message;

$action = null;

if (isset($_REQUEST["action"])) {
	$action = $_REQUEST["action"];
};

/**
 * ACTION
 */

try {

	if ($action == "save-facebook-card") {

		$social2blogfacebook->saveFacebookCard($_REQUEST);
		
	} elseif ($action == "remove-facebook-card") {
		$social2blogfacebook->removeFacebookCard();
	}

} catch (Exception $e) {
	//printiamo un messaggio di errore
	echo "<div class='notice error my-acf-notice is-dismissible' ><p>".$e->getMessage()."</p></div>";
}


$tags_string = $social2blogfacebook->getTags();
$tags = explode(' ', $tags_string);

$has_event = null;

?>
<script>
var errorHashtagformat = "<?php echo __("Formato HASHTAG errato", "social2blog-text"); ?>";


jQuery( document ).ready(function($) {
	var tag_ischange = false;

	
	window.onbeforeunload = function(){
		if ( tag_ischange == true )
			return "<?php echo __( 'Modifiche non salvate. Cambiando pagina le modifiche non salvate andranno perse.', 'social2blog-text' );?>";
	};


	$("#show-event").click(function(){
		// Mostra / nasconde il form per la gestione degli eventi fb
		$("#event-setting").toggle();
	});

	$("#show-post").click(function(){
		// Mostra / nasconde il form per la gestione dei post fb
		$("#post-setting").toggle();
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

	jQuery(".tm-input").tagsManager({
        deleteTagsOnBackspace: false,
        delimiters: [9, 13, 32, 44],
        validator: checkHashtag
    	});
	
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

	$( "#cancellaFacebook" ).on( "click", function() {
		tag_ischange = false;
		$('#action').val("remove-facebook-card");
		 var url = "<?php echo SOCIAL2BLOG_LOCALURL?>-facebook"; // the script where you handle the form input.

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
	    var page = $("select[name='idPage']").val();
		var tags = $("input[name='hidden-tags']").val();
		var statusPost = $("select[name='statusPost']").val();
		var fb_post = document.getElementById('show-post');
		var fb_event = document.getElementById('show-event');

		if ( page == "" ){
			alert('Page required');
			return false;
		}else if( !fb_post.checked && !fb_event.checked ){
			alert('Post or event Facebook required');
			return false;
		}else if(fb_post.checked && (tags=="" || typeof tags === 'undefined')){
			alert('Tags required');
			return false;
		}

	});

	<?php
	if (!empty($tags)) {
		for($i=0; $i< count($tags); $i++){ 	?>
		jQuery(".tm-input").tagsManager('pushTag','<?php echo $tags[$i]; ?> ');
	<?php }
	}

	?>

	tag_ischange = false;
});
</script>

<?php
$fbConnected = $social2blogfacebook->isFBConnected();
if ($fbConnected) {

	if (isset ( $_POST ['submit'] )) {
		$idPaginaAdmin = $_POST ['idPage'];
		$social2blogfacebook->setIdPage ( $idPaginaAdmin );
	}

	$pageAdmin = $social2blogfacebook->capturePageAdmin ();
	if( empty($pageAdmin) ){
		$social2blogfacebook->removeAccessToken();

		//header("Location:".SOCIAL2BLOG_LOCALURL);
		?>
		'<script type="text/javascript">
			alert("<?php echo __( 'Errore nel caricare le pagine amministrate. Riautenticarsi.', 'social2blog-text' );?>");
        	window.location = "<?php echo SOCIAL2BLOG_LOCALURL;?>"
        </script>'
		<div class="notice error my-acf-notice is-dismissible" >
		<p><?php _e( 'Errore nel caricare le pagine amministrate. Riautenticarsi.', 'social2blog-text' ); ?></p>
		</div>
		<?php
	}

	$page_id = $social2blogfacebook->getPage_id();
	$sceltaPag = "";
	if($page_id == ""){
		$sceltaPag = "<option value=''>------------</option>";
	}

	for($i = 0; $i < count ( $pageAdmin ); $i ++) {
		if($pageAdmin [$i][1] === $page_id){
			$sceltaPag .= "<option selected value='" . $pageAdmin [$i] [1] . "'>" . $pageAdmin [$i] [0] . "</option>";
		}
		else{
			$sceltaPag .= "<option value='" . $pageAdmin [$i] [1] . "'>" . $pageAdmin [$i] [0] . "</option>";
		}
	}

	
	$authors = get_users('who=authors', 'orderby=display_name' );
	
	$user =  get_user_by( "login", $social2blogfacebook->getAuthorPost() );

	$author_post = $social2blogfacebook->getAuthorPost(); 
	$auth = empty($author_post) ? "" : "<option selected value='".$social2blogfacebook->getAuthorPost()."'>".$user->data->display_name."</option>";
	foreach ($authors as $author) {
		$auth .= $author->user_login != $social2blogfacebook->getAuthorPost() ? "<option value='".$author->user_login."'>".$author->display_name."</option>" : "";
	}

	$organizers = "";
	$organizers = $social2blogfacebook->getOrganizersEvents();
	$organizer = "<option value='------'>------</option>";

	$current_organizer = Social2blog_Facebook::retriveOrganizer();
	for ($i=0; $i < count($organizers); $i++){
		$def = $organizers[$i]->ID == $current_organizer ? "selected" : "" ;
		$organizer .= "<option $def value='".$organizers[$i]->ID."'>".$organizers[$i]->post_title."</option>";
	}
}

?>


<div class="wrap">
	<h1 class="title_xwp"><img src='<?php echo plugin_dir_url( __FILE__ )."icon.png"?>' style="margin-right: 10px" />
	<span class="logoso">Social</span><span class="logo2">2</span><span class="logowp">Blog</span></h1>
	<h2 class="nav-tab-wrapper">
		<a href="<?php echo SOCIAL2BLOG_LOCALURL?>" class="nav-tab nav-tab"><?php echo __( 'Generale', 'social2blog-text' )?></a>
		<a
			<?php echo $social2blogfacebook->isFBConnected() ? "href=".SOCIAL2BLOG_LOCALURL."-facebook" : ""?>
			class="nav-tab nav-tab-active">Facebook</a> <a
			<?php echo $social2blogtwitter->isTWConnected() ? "href=".SOCIAL2BLOG_LOCALURL."-twitter" : ""?>
			class="nav-tab nav-tab">Twitter</a>
		<a <?php echo "href=".SOCIAL2BLOG_LOCALURL."-instagram"; ?>  class="nav-tab nav-tab">Instagram</a>
		<a <?php echo "href=".SOCIAL2BLOG_LOCALURL."-gallery"; ?>  class="nav-tab nav-tab">Gallery</a>
	</h2>
	<h2><?php echo __( 'Manage Facebook', 'social2blog-text' )?></h2>
	<form id='form_pg' class='select_pg' action='<?php echo SOCIAL2BLOG_LOCALURL?>-facebook' method='POST'>

		<div id="sp_facebook" class="postbox s2b-postbox">

			<div class="inside_xwp">
				<div class="text_label">
					<b><?php echo __( 'Pagina:', 'social2blog-text' )?></b>
				</div>
				<div class="inside_xwp">
					<select name='idPage'>
					<?php echo $sceltaPag;?>
				</select>
				</div>
			</div>

			<div class="inside_xwp">
				<div class = "text_label">
					<b><?php echo __( 'Scarica post:', 'social2blog-text' )?></b>
				</div>
				<div class="costruzione_custom">
					<div class="option_inside_xwp">
						<input type="checkbox" id="show-post" name="fb-post" <?php echo $social2blogfacebook->getPost() == "on" ? "checked" : "";?>>
					</div>
				</div>
			</div>

			<div id = "post-setting" <?php echo $social2blogfacebook->getPost() == "on" ? "" : "hidden";?>>
				<h1 class="hndle">
					<span class="subtitle_xwp">Facebook Post</span>
				</h1>

				<div class="inside_xwp">
					<div class="text_label">
						<b>#Hashtag:</b>
					</div>
					<div class="input_xwp">
						<input type="text" id="tags" name="tags" placeholder="Tags"
							class=' -info tm-input tm-tag-large' />
						<div id="messageTag"></div>
					</div>
				</div>
   
				<div class="inside_xwp">
					<div class="text_label">
						<b><?php echo __( 'Stato predefinito:', 'social2blog-text' )?></b>
					</div>
					<div class="input_xwp">
						<select name='statusPost'>
							<option
								<?php echo $social2blogfacebook->retriveStatusPost()=="0" ? "selected" : "";?>
								value='0'><?php echo __( 'Bozza', 'social2blog-text' )?></option>
							<option
								<?php echo $social2blogfacebook->retriveStatusPost()=="1" ? "selected" : "";?>
								value='1'><?php echo __( 'Pubblica', 'social2blog-text' )?></option>
						</select>
					</div>
				</div>

				<div class="inside_xwp">
					<div class="text_label">
						<b><?php echo __( 'Autore:', 'social2blog-text' )?></b>
					</div>
					<div class="input_xwp">
						<select name='autor_post'>
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
								echo ($social2blogfacebook->getTitle_count () == 0) ? "checked" : "";
								?> /><?php echo __( 'Fino al primo punto (? o ; o ! o .)', 'social2blog-text' )?><br />
							<br />
						</div>
						<div class="clear"></div>
						<div class="option_inside_xwp">
							<input type="radio" name="titolo_type" value="1"
								<?php
								echo ($social2blogfacebook->getTitle_count () != 0) ? "checked" : "";
								?> /><?php echo __( 'Prime', 'social2blog-text' )?>
		  					<input class="number_post_input" name="titolo_count"
								type="number"
								value="<?php echo ($social2blogfacebook->getTitle_count() != 0) ? $social2blogfacebook->getTitle_count() : "10";?>">
		  					<?php echo __( ' parole del post', 'social2blog-text' )?>
						</div>
					</div>
				</div>

			</div>

			<div class="inside_xwp">
				<div class = "text_label">
					<b><?php echo __( 'Scarica eventi:', 'social2blog-text' )?></b>
				</div>

				<?php


				if (social2blog_mincarat () == -3){ ?>

					<div class="costruzione_custom">
					<div class="option_inside_xwp">
						<?php echo _e( 'The Events Calendar plugin is missing or outdated. The function Facebook Events is not available', 'social2blog-text' );  ?>
					</div>
				</div>


				<?php }
			else { ?>


				<div class="costruzione_custom">
					<div class="option_inside_xwp">
					<?php $has_event =  Social2blog_Facebook::retriveEvent(); ?>
						<input type="checkbox" id="show-event" name="fb-event" <?php echo $has_event == "on" ? "checked" : "";?>>
					</div>
				</div>
				<?php } ?>
			</div>

			<div id = "event-setting" <?php echo ($has_event == "on" and social2blog_mincarat () != -3) ? "" : "hidden";?>>
			<h1 class="hndle"></h1>
			<h1 class="hndle">
				<span class="subtitle_xwp"><?php echo __( 'Facebook Eventi', 'social2blog-text' )?></span>
			</h1>

			<div class="inside_xwp">
				<div class="text_label">
					<b><?php echo __( 'Stato predefinito:', 'social2blog-text' )?></b>
				</div>
				<div class="input_xwp">
					<select name='statusEvents'>
						<option
							<?php echo $social2blogfacebook->retriveStatusEvent()=="0" ? "selected" : "";?>
							value='0'><?php echo __( 'Bozza', 'social2blog-text' )?></option>
						<option
							<?php echo $social2blogfacebook->retriveStatusEvent()=="1" ? "selected" : "";?>
							value='1'><?php echo __( 'Pubblica', 'social2blog-text' )?></option>
					</select>
				</div>
			</div>

			<div class="inside_xwp">
				<div class="text_label">
					<b><?php echo __( 'Organizzatore:', 'social2blog-text' )?></b>
				</div>
				<div class="input_xwp">
					<select name='organizer_events'>
						<?php echo $organizer;?>
					</select>
				</div>
			</div>
			</div>
			<div class="inside_xwp">
				<div class="text_label"></div>
				<div class="input_xwp">
				<?php
				$social2blogfacebook->getFormButton ();
				?>
			</div>
			</div>

			<input type='hidden' id="action" name='action' value='save-facebook-card' />
		</div>
	</form>
	</div>
