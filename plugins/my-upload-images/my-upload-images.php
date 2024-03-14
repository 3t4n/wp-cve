<?php

/*
plugin name: My Upload Images
Plugin URI: http://web.contempo.jp/weblog/tips/p617
Description: Create metabox with media uploader. It allows user to upload and sort images in any post_type you want.
Author: Mizuho Ogino
Author URI: http://web.contempo.jp/
Version: 1.4.1
Text Domain: my-upload-images
Domain Path: /languages
License: http://www.gnu.org/licenses/gpl.html GPL v2 or later
*/

if ( !class_exists( 'MyUPIMG' ) ) {
class MyUPIMG {


	public function __construct() {
		load_plugin_textdomain( 'my-upload-images', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		register_activation_hook( __FILE__, array( $this,'mui_activate' ) );
		add_action( 'upgrader_process_complete', array( $this,'mui_upgrader_process_complete' ), 10, 2 );
		add_action( 'admin_menu', array( $this,'mui_admin_menu' ), 1, 1 );
		add_action( 'save_post', array( $this,'mui_save_images'), 100, 1 );
		add_action( 'new_to_publish', array( $this,'mui_save_images'), 100, 1 );
		add_action( 'wp_insert_post', array( $this,'mui_save_preview_postmeta'), 11, 1 );
	}


	public function mui_activate() { // set default and update settings
		$this->mui_update_options(); // overwrite settings
		return;
	}


	public function mui_upgrader_process_complete( $upgrader_object, $options ) {
		$current_plugin_path_name = plugin_basename( __FILE__ );
		if ($options['action'] == 'update' && $options['type'] == 'plugin' && $options['plugins'] ){
			foreach($options['plugins'] as $each_plugin){
				if ($each_plugin == $current_plugin_path_name ){
					delete_option( 'mui_posttype' ); delete_option( 'mui_pages' ); delete_option( 'mui_keepvalues' ); delete_option( 'mui_postthumb' ); delete_option( 'mui_title' ); delete_option( 'mui_position' ); // delete old version settings
					$this->mui_update_options();
					return;
				}
			}
		}
	}


	private function mui_update_options() {
		$opt = get_option( 'mui_options' );
		$update_options = array(
			'posttype' => isset( $opt[ 'posttype' ] ) ? $opt[ 'posttype' ] : '',
			'pages' => isset( $opt[ 'pages' ] ) ? $opt[ 'pages' ] : '',
			'keepvalues' => isset( $opt[ 'keepvalues' ] ) && $opt[ 'keepvalues' ] ? $opt[ 'keepvalues' ] : 'keep',
			'postthumb' => isset( $opt[ 'postthumb' ] ) && $opt[ 'postthumb' ] ? $opt[ 'postthumb' ] : 'none',
			// 'editbutton' => isset( $opt[ 'editbutton' ] ) && $opt[ 'editbutton' ] ? $opt[ 'editbutton' ] : 'none',
			'maxnum' => isset( $opt[ 'maxnum' ] ) && $opt[ 'maxnum' ] ? $opt[ 'maxnum' ] : '',
			'imgheight' => isset( $opt[ 'imgheight' ] ) && $opt[ 'imgheight' ] ? $opt[ 'imgheight' ] : 120,
			'title' => isset( $opt[ 'title' ] ) && $opt[ 'title' ] ? $opt[ 'title' ] : __( 'My Upload Images', 'my-upload-images' ),
			'position' => isset( $opt[ 'position' ] ) && $opt[ 'position' ] ? $opt[ 'position' ] : 'side'
		);
		update_option( 'mui_options', $update_options ); // overwrite settings
		return;
	}


	public function mui_admin_menu() {
		add_options_page( __( 'My Upload Images', 'my-upload-images' ), __( 'My Upload Images', 'my-upload-images' ), 'manage_options', __FILE__, array( $this,'mui_options_page') );

		$opt = get_option( 'mui_options' );
		if ( empty($opt['posttype']) ) return;
		$opt_title = ( isset($opt['title']) && $opt['title'] ? $opt['title'] : __( 'My Upload Images', 'my-upload-images' ) );
		$opt_position = $opt['position'];
		$posttype = $opt['posttype'];
		foreach( $posttype as $key => $val ):
			$get_captype = get_post_type_object( $val );
			if ( $get_captype && $get_captype->capability_type == 'page' ){
				if ( isset($_GET['post']) && $_GET['post'] ) $post_id = $_GET['post'];
				elseif ( isset($_POST['post_ID']) && $_POST['post_ID'] ) $post_id = $_POST['post_ID'];
				$opt_p = $opt['pages'];
				if ($opt_p): foreach( $opt_p as $key_p => $val_p ):
					if ( isset( $post_id ) && $post_id == $val_p) {
						add_meta_box( 'mui_images', $opt_title, array( $this, 'mui_uploader'), $val, ( $opt_position === 'mui_after_title' ? 'mui_after_title' : $opt_position ), 'high' );
						if( $opt_position === 'mui_after_title' ) add_action( 'edit_form_after_title', array( $this,'mui_edit_form_after_title') );
					}
				endforeach; endif;
				require_once(ABSPATH . 'wp-admin/includes/template.php');
			} else {
				add_meta_box( 'mui_images', $opt_title, array( $this, 'mui_uploader'), $val, $opt_position, 'high' );
				if( $opt_position === 'mui_after_title' ) add_action( 'edit_form_after_title', array( $this,'mui_edit_form_after_title') );
			}
		endforeach;
	}


	public function mui_edit_form_after_title() {
		global $post, $wp_meta_boxes;
		do_meta_boxes( get_current_screen(), 'mui_after_title', $post );
		unset( $wp_meta_boxes[get_post_type( $post )][ 'mui_after_title'] );
	}


	public function mui_options_page() {
		if ( isset($_POST["mui_options_nonce"]) && wp_verify_nonce($_POST['mui_options_nonce'], basename(__FILE__)) ) { // save options
			$update_options = array(
				'posttype' => ( isset( $_POST[ 'mui_posttype' ] ) ? $_POST[ 'mui_posttype' ] : '' ),
				'pages' => ( isset( $_POST[ 'mui_pages' ] ) ? $_POST[ 'mui_pages' ] : '' ),
				'keepvalues' => ( isset( $_POST[ 'mui_keepvalues' ] ) ? $_POST[ 'mui_keepvalues' ] : 'keep' ),
				'postthumb' => ( isset( $_POST[ 'mui_postthumb' ] ) ? $_POST[ 'mui_postthumb' ] : 'none' ),
				'maxnum' => ( isset( $_POST[ 'mui_maxnum' ] ) ? $_POST[ 'mui_maxnum' ] : '' ),
				// 'editbutton' => ( isset( $_POST[ 'mui_editbutton' ] ) ? $_POST[ 'mui_editbutton' ] : 'none' ),
				'imgheight' => ( isset( $_POST[ 'mui_imgheight' ] ) ? $_POST[ 'mui_imgheight' ] : 120 ),
				'title' => wp_strip_all_tags( ( isset( $_POST[ 'mui_title' ] ) ? $_POST[ 'mui_title' ] : __( 'My Upload Images', 'my-upload-images' ) ) ),
				'position' => ( isset( $_POST[ 'mui_position' ] ) ? $_POST[ 'mui_position' ] : 'side' )
			);
			update_option( 'mui_options', $update_options );
			echo '<div class="updated fade"><p><strong>'. __('Options saved.', 'my-upload-images'). '</strong></p></div>';
		}
		$opt = get_option( 'mui_options' );
		$posttype = isset( $opt['posttype'] ) ? $opt['posttype'] : '';
		$default = array();
		if ($posttype): foreach( $posttype as $key => $val ):
			$default[$val] = true;
		endforeach; endif;
		$pages = isset( $opt['pages'] ) ? $opt['pages'] : '';
		if ($pages): foreach( $pages as $key => $val ):
			$default[$val] = true;
		endforeach; endif;

		$post_types = get_post_types( array( 'public' => true ), 'objects' );
		unset($post_types['attachment']);
		$inputs = $individuals = '';
		if ($post_types) : foreach($post_types as $post_type) :
			if ( isset($default[ $post_type->name ]) ) $checked = ' checked="checked"'; else $checked = '';
			$inputs .= "\t\t\t".'<p><label for="field-mui_posttype-'.$post_type->name.'"><input id="field-mui_posttype-'.$post_type->name.'" class="cb-posttype" type="checkbox" name="mui_posttype[]" value="'.$post_type->name.'"'.$checked.'/>'.$post_type->label.'</label></p>'."\n";
			if ( $post_type->capability_type == 'page' ) {
				$pages = get_posts( array('post_type' => $post_type->name, 'orderby' => 'menu_order', 'post_status' => array('publish', 'pending', 'draft', 'future', 'private' ), 'numberposts' => -1 ) );
				if ($pages) :
					$individuals .=
						"\t".'<tr id="individuals-'.$post_type->name.'">'."\n".
						"\t\t".'<th scope="row">'.sprintf(__('Select %s', 'my-upload-images'), $post_type->label).'</th>'."\n".
						"\t\t".'<td>'."\n";
					foreach($pages as $page) :
						if ( isset($default[ $page->ID ]) ) $checked = ' checked="checked"'; else $checked = '';
						$individuals .= "\t\t\t".'<p><label for="field-mui_pages-'.$page->ID.'"><input id="field-mui_pages-'.$page->ID.'" type="checkbox" name="mui_pages[]" value="'.$page->ID.'"'.$checked.'/>'.esc_html( $page->post_title ).'</label></p>'."\n";
					endforeach;
					$individuals .=
						"\t\t".'</td>'."\n".
						"\t".'</tr>'."\n";
				endif;
			}
		endforeach; endif;
?>
<div class="wrap">
<h2><?php _e( 'My Upload Images Settings', 'my-upload-images' ); ?></h2>
<h3><?php _e( 'Select post_types to display the metabox.', 'my-upload-images' ); ?></h3>
<form action="" method="post">
<table class="form-table">
	<tr>
		<th scope="row">
			<?php _e( 'Metabox title', 'my-upload-images' ); ?>
		</th>
		<td>
			<input type="text" name="mui_title" class="text" size="40" value="<?php echo ( isset( $opt[ 'title' ] ) ? esc_attr( $opt[ 'title' ] ) : '' ); ?>" />
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php _e( 'Select post types', 'my-upload-images' ); ?>
			<p><?php _e( 'If the post_type has "capability_type" parameter as "page", pages will be individually selectable.', 'my-upload-images' ); ?></p>
		</th>
		<td>
<?php
	echo $inputs;
?>
		</td>
	</tr>
<?php
	echo $individuals;
?>
<!-- 	<tr>
		<th scope="row">
			<?php _e( 'Edit field of image', 'my-upload-images' ); ?>
		</th>
		<td>
			<select name="mui_editbutton"><option value="display"<?php echo ( isset( $opt['editbutton'] ) && $opt['editbutton'] === 'display' ? ' selected' : '' ); ?>><?php _e( 'Show the editor on default', 'my-upload-images' ); ?></option><option value="none"<?php echo ( isset( $opt['editbutton'] ) && $opt['editbutton'] === 'none' ? ' selected' : '' ); ?>><?php _e( 'Hide the editor on default', 'my-upload-images' ); ?></option></select>
		</td>
	</tr> -->
	<tr>
		<th scope="row">
			<?php _e( 'Image max height', 'my-upload-images' ); ?>
			<p><?php _e( 'From 60 to 600 px', 'my-upload-images' ); ?></p>
		</th>
		<td>
			<input type="number" name="mui_imgheight" class="number" min="60" max="600" size="20" value="<?php echo ( isset( $opt[ 'imgheight' ] ) && $opt[ 'imgheight' ] ? esc_attr( $opt[ 'imgheight' ] ) : 140 ); ?>" /> px
		</td>
	</tr>
	<tr>
		<th scope="row"><?php _e( 'Featured images', 'my-upload-images' ); ?></th>
		<td>
			<select name="mui_postthumb"><option value="generate"<?php echo ( isset( $opt['postthumb'] ) && $opt['postthumb'] === 'generate' ? ' selected' : '' ); ?>><?php _e( 'Generate a featured image from the first of my upload images', 'my-upload-images' ); ?></option><option value="none"<?php echo ( isset( $opt['postthumb'] ) && $opt['postthumb'] === 'none' ? ' selected' : '' ); ?>><?php _e( 'No automatically generating', 'my-upload-images' ); ?></option></select>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php _e( 'Limit max number of registerable images', 'my-upload-images' ); ?>
			<p><?php _e( 'Set 0 (empty) for no limitation', 'my-upload-images' ); ?></p>
		</th>
		<td>
			<input type="number" name="mui_maxnum" class="number" size="20" min="0" value="<?php echo ( isset( $opt[ 'maxnum' ] ) && $opt[ 'maxnum' ] ? esc_attr( $opt[ 'maxnum' ] ) : '' ); ?>" />
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php _e( 'Put the metabox', 'my-upload-images' ); ?>
			<p><?php _e( 'When the set value is "after the title", it may cause conflict with other plugins. If the metabox doesn\'t appear, set value to another.', 'my-upload-images' ); ?></p>
		</th>
		<td>
			<select name="mui_position">
				<option value="side"<?php echo ( isset( $opt['position'] ) && $opt['position'] === 'side' ? ' selected' : '' ); ?>><?php _e( 'on the side', 'my-upload-images' ); ?></option>
				<option value="advanced"<?php echo ( isset( $opt['position'] ) && $opt['position'] === 'advanced' ? ' selected' : '' ); ?>><?php _e( 'after the editor', 'my-upload-images' ); ?></option>
				<option value="mui_after_title"<?php echo ( isset( $opt['position'] ) && $opt['position'] === 'mui_after_title' ? ' selected' : '' ); ?>><?php _e( 'after the title', 'my-upload-images' ); ?></option>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row">
			<?php _e( 'When the plugin is uninstalled', 'my-upload-images' ); ?>
		</th>
		<td>
			<select name="mui_keepvalues">
				<option value="keep"<?php echo ( isset( $opt['keepvalues'] ) && $opt['keepvalues'] === 'keep' ? ' selected' : '' ); ?>><?php _e( 'Keep the options and customfields', 'my-upload-images' ); ?></option>
				<option value="delete_options"<?php echo ( isset( $opt['keepvalues'] ) && $opt['keepvalues'] === 'delete_options' ? ' selected' : '' ); ?>><?php _e( 'Delete the options', 'my-upload-images' ); ?></option>
				<option value="delete"<?php echo ( isset( $opt['keepvalues'] ) && $opt['keepvalues'] === 'delete' ? ' selected' : '' ); ?>><?php _e( 'Delete the options and customfields', 'my-upload-images' ); ?></option>
			</select>
		</td>
	</tr>
</table>
<p class="submit"><input type="submit" name="Submit" class="button-primary" value="<?php _e( 'Save changes', 'my-upload-images' ); ?>" /></p>
<input type="hidden" name="mui_options_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)); ?>" />
</form>
<style type="text/css">.form-table th p { font-size:.88em; color:gray; margin-top:.4em; font-weight:normal; }</style>
<script type="text/javascript">
jQuery( function($){
	$("input.cb-posttype").each(function(){
		var ckbtn = $( this ),
			cktaget = $( "#individuals-" + $( this ).val() ).hide();
		if ( ckbtn.is(":checked") ) cktaget.show();
		ckbtn.click( function () {
			if ( ckbtn.is(":checked") ) cktaget.show(); else cktaget.hide();
		});
	});
});
</script>
<h3 style="margin-top:50px;"><?php _e( 'Donate', 'my-upload-images' ); ?></h3>
<p><?php _e( 'If you find this plugin useful and you want to support its future development, please consider making a donation.', 'my-upload-images' ); ?></p>
<p><a href="http://web.contempo.jp/donate?mui" class="button button-primary" target="_blank"><?php _e( 'Donate via PayPal', 'my-upload-images' ); ?></a></p>
<p style="padding-top:20px; font-size:.9em;"><?php _e( 'If you are having problems with the plugin, see the plugin page on <a href="https://wordpress.org/plugins/my-upload-images/" target="_blank">the WordPress.org plugin directory</a>.', 'my-upload-images' ); ?></p>
</div>
<?php
	}


	public function mui_uploader(){
		$post = get_post();
		$post_id = $post->ID;
		$opt = get_option( 'mui_options' );
		$mui_li = array();
		if ( $post_id ):
			$mui_images = get_post_meta( $post_id, 'my_upload_images', true );
			if ( $mui_images): foreach( $mui_images as $key => $img_id ):
				$thumb_src = wp_get_attachment_image_src ( $img_id, 'medium' );
				$src = wp_get_attachment_image_src ( $img_id, 'fullsize' );
				if ( empty ($thumb_src[0]) ){ // If the file is not exist, delete the ID.
					delete_post_meta( $post_id, 'my_upload_images', $img_id );
				} else {
					$img_id = (int)$img_id;
					$att = get_post( $img_id );
					$nonce = wp_create_nonce( "image_editor-$img_id" );
					$mui_li[] =
						"\t".'<li class="mui-li" id="mui-att-'.$img_id.'" title="'.__('Sort it in any order', 'my-upload-images' ).'">'."\n".
						"\t\t".'<div class="mui-wrap">'."\n".
						"\t\t\t".'<a href="#" class="mui-remove button" title="'.__( 'Remove this image from the list', 'my-upload-images' ).'"></a>'."\n".
						"\t\t\t".'<div class="mui-img" style="background-image:url(\''.$thumb_src[0].'\')"><img src="'.$thumb_src[0].'" /></div>' ."\n".
						"\t\t\t".'<div class="mui-imgname"><span>'.wp_strip_all_tags( $att->post_title ).'</span></div>'."\n".
						"\t\t\t".'<div class="mui-editor"><label for="at' .$img_id. '_ttl">'.__('Title').'</label><input id="at' .$img_id. '_ttl" type="text" placeholder="'.__('Title').'" name="my_upload_images_attr['.$img_id. '][title]" value="'.$att->post_title.'" /><label for="at' .$img_id. '_alt">'.__('Alt Text').'</label><input id="at' .$img_id. '_alt" type="text" placeholder="'.__('Alt Text').'" name="my_upload_images_attr['.$img_id. '][alt]" value="'.get_post_meta( $img_id, '_wp_attachment_image_alt', true).'" /><label for="at' .$img_id. '_cap">'.__('Caption').'</label><textarea id="at' .$img_id. '_cap" placeholder="'.__('Caption').'" name="my_upload_images_attr[' .$img_id. '][caption]">'.$att->post_excerpt.'</textarea></div>'."\n".
						"\t\t\t".'<input type="hidden" name="my_upload_images[]" value="'.$img_id.'" />'."\n".
						"\t\t".'</div>'."\n".
						"\t".'</li>'."\n";
				}
			endforeach; endif;
		endif;
		$setting = get_post_meta( $post_id, '_my_upload_images_meta', true );

		echo '<ul id="mui-ul"'.( isset($setting['view']) && $setting['view'] == 'true' ? ' class="editor"' : '' ).'>'.join( $mui_li ).'</ul>'."\n";

		if ( isset( $opt[ 'maxnum' ] ) && $opt[ 'maxnum' ] )
			echo '<div id="mui-maxnum-note" class="mui-note"'.( count( $mui_li ) > $opt[ 'maxnum' ] ? ' style="display:block;"' : '' ).'>'.sprintf(__( 'A maximum of <b>%d</b> images can be set', 'my-upload-images' ), $opt[ 'maxnum' ] ).'</div>'."\n";
?>
	<div id="mui-media">
		<a class="button mui-open" title="<?php echo __( 'Add Images', 'my-upload-images' ); ?>"><?php echo __( 'Add Images', 'my-upload-images' ); ?></a>
		<a class="button mui-editor-open" title="<?php echo __( 'Editor View', 'my-upload-images' ); ?>"></a>
		<a class="button mui-editor-close" title="<?php echo __( 'Block View', 'my-upload-images' ); ?>"></a>
	</div>
	<input type="hidden" id="mui_view" name="my_upload_images_view" value="<?php echo ( isset($setting['view']) ? $setting['view'] : 'false' ); ?>" />
	<input type="hidden" id="mui_nonce" name="mui_nonce" value="<?php echo wp_create_nonce(basename(__FILE__)); ?>" />
	<style type="text/css">
		#mui_images *, #mui_images *:before, #mui_images *:after { -webkit-box-sizing:border-box; box-sizing:border-box; }
		#mui_after_title-sortables { margin-top:20px; }
		#mui_images .inside { padding-top:8px; padding-bottom:13px; }
		#mui-ul { display:block; list-style:none; margin:0 -7px; padding:0; display:-webkit-box; display:-ms-flexbox; display:flex; -webkit-flex-wrap:wrap; -ms-flex-wrap:wrap; flex-wrap:wrap;}
		#mui-ul:after { content:' '; display:block; height:0; clear:both; visibility:hidden; }
		#mui-ul li { display:block; margin:0; padding:4px 6px; position:relative; }
		.mui-wrap { display:block; margin:0; padding:5px; position:relative; overflow:visible; background:#f8f8f8; border:1px solid #e7e7e7; -webkit-border-radius:2px; border-radius:2px; }
		.mui-editor { display:none; padding:2px 0 0 }
		.mui-editor label { display:none; }
		.mui-editor input, .mui-editor textarea { display:block; width:100%; margin:3px 0 0!important;}
		#mui_images input::-webkit-input-placeholder, #mui_images textarea::-webkit-input-placeholder { color:#b3c6d1;; }
		#mui_images input::-moz-placeholder, #mui_images textarea::-moz-placeholder { color:#b3c6d1; }
		#mui_images input::-ms-input-placeholder, #mui_images textarea::-ms-input-placeholder { color:#b3c6d1; }
		#mui_images input::placeholder-shown, #mui_images textarea::placeholder-shown { color:#b3c6d1; }
		.mui-img { padding:0; margin:0; display:block; position:relative; vertical-align:middle; overflow:hidden; height:<?php echo ( $opt['imgheight'] ? $opt['imgheight'] : 140 ).'px'; ?>; background-position:center center; background-repeat:no-repeat; background-size:contain; }
		.mui-wrap:hover { background:#eff7fa; border-color:#dae4ea; cursor:move; }
		.mui-img > img { margin:0; padding:0; max-height:100%; max-width:100%; height:auto; width:auto; opacity:0; }
		.mui-imgname { position:absolute; right:5px; bottom:5px; left:5px; text-align:right; overflow:hidden; }
		.mui-imgname span { display:inline-block; background:rgba(0,0,0,.5); color:#fff; font-size:10px; margin:0; vertical-align:bottom; line-height:12px; padding:1px 4px; }
		#mui-ul.editor li { width:100%; }
		#mui-ul.editor .mui-imgname { display:none; }
		#mui-ul.editor .mui-img, #mui-ul.editor .mui-editor { display:block; text-align:center; }
		#mui-ul.editor .mui-img { height:120px; }
		#mui-ul:not(.editor) + #mui-media .mui-editor-close, #mui-ul.editor + #mui-media .mui-editor-open { background-color:#eff7fa; }
		.mui-wrap input[type="hidden"] { display:none; }
		#mui-ul a.mui-remove { height:28px; width:28px; text-align:center; position:absolute; right:-5px; text-decoration:none; padding:0; -webkit-border-radius:2px; border-radius:2px; z-index:20; }
		#mui-ul a.mui-remove { top:-4px; }
		#mui-ul a.mui-remove:before { font-family:"dashicons"; display:block; text-align:center; vertical-align:middle; font-size:20px; line-height:20px; height:28px; padding:4px 0; }
		#mui-ul a.mui-remove:before { content:"\f158"; }
		#mui-media { padding:5px 108px 0 0; position:relative; }
		#mui-media a.button { height:30px; padding:8px; font-size:13px; line-height:20px; width:100%; height:auto; font-weight:normal; text-align:center; display:block; vertical-align:baseline;}
		#mui-media a.button:before { font-family:"dashicons"; font-size:20px; line-height:20px; display:inline; vertical-align:middle; }
		#mui-media a.mui-open:before { content:"\f128"; margin-right:.3em; }
		#mui-media a.mui-open { margin-right: 70px; }
		#mui-media a.mui-editor-open { position:absolute; top:5px; right:54px; width:50px; }
		#mui-media a.mui-editor-close { position:absolute; top:5px; right:0; width:50px; }
		#mui-media a.mui-editor-open:before { content:"\f163"; }
		#mui-media a.mui-editor-close:before { content:"\f509"; }
		<?php if ( $opt[ 'maxnum' ] ) { ?>#mui-ul > li:nth-child(n+ <?php echo $opt[ 'maxnum' ]+1; ?>) .mui-wrap::before { display:block; content:' '; position:absolute; top:0; left:0; bottom:0; right:0; z-index:18; opacity:.7; background:#eee; }
		#mui-ul > li:nth-child(n+ <?php echo $opt[ 'maxnum' ]+1; ?>) .mui-img::after { display:block; content:' '; border-top:1px dashed red; width:144%; position:absolute; top:50%; left:-22%; z-index:19; -webkit-transform:rotate(45deg); -moz-transform:rotate(45deg); -ms-transform:rotate(45deg); transform:rotate(45deg); }
		#mui_images .mui-note { color:red; font-size:.88em; line-height:1.4em; text-align:center; margin:0; display:none; /*display controlled by js*/}
		#mui_images .mui-note b { font-size:1.2em; }<?php echo "\n"; } ?>
		@media screen and (min-width : 1540px){
			#normal-sortables #mui-ul.editor li { width:50%; }
		}
		@media screen and (min-width : 851px){
			#side-sortables #mui_images .inside { padding-top:4px; }
			#side-sortables #mui-ul { text-align:center; }
			#side-sortables #mui-ul li { float:none; display:block; height:auto; }
			#side-sortables #mui-media { padding:5px 72px 0 0; }
			#side-sortables #mui-media a.button:before { font-size:18px; }
			#side-sortables #mui-media a.mui-editor-open { right:36px; width:36px; }
			#side-sortables #mui-media a.mui-editor-close { right:0; width:36px; }
			#side-sortables #mui-ul.editor .mui-img { display:block; text-align:center; width:auto; margin:0 auto; }
			#side-sortables #mui-ul.editor .mui-editor { padding:4px 0 0; display:block; width:auto; }
			#side-sortables .mui-editor label { display:none; }
			#side-sortables #mui_images input::-webkit-input-placeholder, #side-sortables #mui_images textarea::-webkit-input-placeholder { color:#b3c6d1; }
			#side-sortables #mui_images input::-moz-placeholder, #side-sortables #mui_images textarea::-moz-placeholder { color:#b3c6d1; }
			#side-sortables #mui_images input::-ms-input-placeholder, #side-sortables #mui_images textarea::-ms-input-placeholder { color:#b3c6d1; }
			#side-sortables #mui_images input::placeholder-shown, #side-sortables #mui_images textarea::placeholder-shown { color:#b3c6d1; }
			#side-sortables #mui-ul:not(.editor) { margin:0 -6px; }
			#side-sortables #mui-ul:not(.editor) li { width:50%; padding:5px; }
			#side-sortables #mui-ul:not(.editor) li .mui-img { height:auto; width:100%; display:block; background-size:cover; }
			#side-sortables #mui-ul:not(.editor) li .mui-img:before { content:' '; display:block; padding:100% 0 0 0; }
			#side-sortables #mui-ul:not(.editor) li .mui-img img { display:none; }
		}
		@media screen and (max-width : 850px){
			.mui-img { height:<?php echo ( $opt['imgheight'] < 120 ? $opt['imgheight'] : 120 ).'px'; ?>; }
		}
		@media screen and (min-width : 479px){
			.mui-editor label { width:80px; margin-left:-80px; display:inline-block; text-align:right; float:left; font-size:10px; padding:3px 4px 0 0; }
			.mui-editor label:after { display:inline-block; content:':'; }
			.mui-editor { padding:0 5px 0 84px; }
			#mui-ul.editor .mui-wrap { display:table; width:100%; }
			#mui-ul.editor .mui-img { width:24%; min-width:140px; max-width:240px; }
			#mui-ul.editor .mui-img, #mui-ul.editor .mui-editor { display:table-cell; vertical-align:middle; }
			#mui_images input::-webkit-input-placeholder, #mui_images textarea::-webkit-input-placeholder { color:transparent; }
			#mui_images input::-moz-placeholder, #mui_images textarea::-moz-placeholder { color:transparent; }
			#mui_images input::-ms-input-placeholder, #mui_images textarea::-ms-input-placeholder { color:transparent; }
			#mui_images input::placeholder-shown, #mui_images textarea::placeholder-shown { color:transparent; }
		}
		@media screen and (max-width : 478px){
			.mui-img { height:<?php echo ( $opt['imgheight'] < 100 ? $opt['imgheight'] : 100 ).'px'; ?>; }
			#mui-ul:not(.editor) li { width:25%; padding:3px; }
			#mui-ul:not(.editor) li .mui-img { height:auto; width:100%; display:block; background-size:cover; }
			#mui-ul:not(.editor) li .mui-img:before { content:' '; display:block; padding:100% 0 0 0; }
			#mui-ul:not(.editor) li .mui-img img { display:none; }
			.mui-wrap { padding:3px; display:block; }
			#mui-ul .mui-imgname { display:none; }
		}
		@media screen and (max-width : 380px){
			#mui-ul:not(.editor) li { width:33.3333%; }
		}
	</style>
	<script type="text/javascript">
	jQuery( function( $ ){

		var mui_uploader = wp.media({
			state : 'mui_state',
			multiple: true
		});
		mui_uploader.states.add([
			new wp.media.controller.Library({
				id:	'mui_state',
				library: wp.media.query( { type: 'image' } ),
				title: <?php echo '\''.$opt["title"].'\''; ?>,
				priority: 70,
				toolbar: 'select',
				menu: false,
				filterable: 'uploaded',
				multiple: 'add'
			})
		]);

		var ex_ul = $( '#mui-ul' ), ex_ids = [];

		$( '#mui_images' ).on( 'click', 'a.mui-open', function( e ) {
			e.preventDefault();
			var clickagain = function() { if ( !$( '.media-frame' ).length ) mui_uploader.open(); }
			setTimeout( clickagain, 100); // The parameter "menu:false" makes a delay of open() event.
			mui_uploader.open();
		});

		mui_uploader.on( 'select', function( ){
			var this_id = 0, this_url = '',
				selection = mui_uploader.state().get( 'selection' );

			ex_ul.children( 'li' ).each( function( ){
				this_id = Number( $(this).attr( 'id' ).slice(8) ); //#mui-att-(N)
				if ( this_id ){
					ex_ids.push( this_id );
				} 
			});
			selection.each( function( file ){
				this_id = file.toJSON().id;
				if ( file.attributes.sizes.medium ) {
					this_url = file.attributes.sizes.medium.url;
				} else if ( file.attributes.sizes.large ) {
					this_url = file.attributes.sizes.large.url;
				} else {
					this_url = file.attributes.url;
				}
				if ( $.inArray( this_id, ex_ids ) === -1 ){ // The ID is NOT existing in the list.
					ex_ul.append( '<li class="mui-li" id="mui-att-' + this_id + '"></li>' ).find( 'li:last' ).append(
						'<div class="mui-wrap">' +
						'<a href="#" class="mui-remove button" title="' + <?php echo '\''.__( 'Remove this image from the list', 'my-upload-images' ).'\''; ?> + '"></a>' +
						'<div class="mui-img" style="background-image:url(\'' + this_url + '\')"><img src="' + this_url + '" /></div>' +
						'<div class="mui-imgname"><span>' + file.toJSON().title + '</span></div>' +

						'<div class="mui-editor"><label for="at' + this_id + '_ttl">' + <?php echo '\''.__( 'Title' ).'\''; ?> + '</label><input id="at' + this_id + '_ttl" type="text" name="my_upload_images_attr[' + this_id + '][title]" placeholder="' + <?php echo '\''.__('Title').'\''; ?> + '" value="' + file.toJSON().title + '" /><label for="at' + this_id + '_alt">' + <?php echo '\''.__('Alt Text').'\''; ?> + '</label><input id="at' + this_id + '_alt" type="text" name="my_upload_images_attr[' + this_id + '][alt]" placeholder="' + <?php echo '\''.__('Alt Text').'\''; ?> + '" value="' + file.toJSON().alt + '" /><label for="at' + this_id + '_cap">' + <?php echo '\''.__('Caption').'\''; ?> + '</label><textarea id="at' + this_id + '_cap" placeholder="' + <?php echo '\''.__('Caption').'\''; ?> + '" name="my_upload_images_attr[' + this_id + '][caption]">' + file.toJSON().caption + '</textarea></div>' +

						'<input type="hidden" name="my_upload_images[]" value="'+ this_id +'" />' +
						'</div>'
					);
				}
			});
			<?php if ( isset( $opt[ 'maxnum' ] ) && $opt[ 'maxnum' ] ) { ?>if ( ex_ul.children( 'li' ).length > <?php echo $opt[ 'maxnum' ]; ?> ) $( '#mui-maxnum-note' ).show(); else $( '#mui-maxnum-note' ).hide();<?php } // if maxnum is set ?>
		});

		$( '#mui_images' ).on( 'click', '.mui-remove', function( e ) {
			e.preventDefault();
			img_obj = $(this).parents( 'li.mui-li' ).remove();
			<?php if ( isset( $opt[ 'maxnum' ] ) && $opt[ 'maxnum' ] ) { ?>if ( $( '#mui-ul' ).children( 'li' ).length > <?php echo $opt[ 'maxnum' ]; ?> ) $( '#mui-maxnum-note' ).show(); else $( '#mui-maxnum-note' ).hide();<?php } // if maxnum is set ?>
		});

		$( '#mui-ul' ).sortable({
			cursor : 'move',
			tolerance : 'pointer',
			opacity: 0.6
		});

		$( '#mui_images' ).on( 'click', 'a.mui-editor-open', function( e ) {
			$( '#mui-ul' ).addClass('editor');	
			$( '#mui_view').val( 'true' );
		});
		$( '#mui_images' ).on( 'click', 'a.mui-editor-close', function( e ) {
			$( '#mui-ul' ).removeClass('editor');	
			$( '#mui_view').val( 'false' );		
		});
	});
	</script>
	<?php }

	public function mui_save_images( $post_id ){
		if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return $post_id;
		if ( !isset($_POST['mui_nonce']) || isset($_POST['mui_nonce']) && !wp_verify_nonce($_POST['mui_nonce'], basename(__FILE__))) return $post_id;
		if ( 'page' == $_POST['post_type'] && !current_user_can( 'edit_page', $post_id ) ) return $post_id;
		elseif ( !current_user_can( 'edit_post', $post_id ) ) return $post_id;

		$attr = isset($_POST['my_upload_images_attr']) ? $_POST['my_upload_images_attr']: null;
		$setting = get_post_meta( $post_id, '_my_upload_images_meta', true );
		$ex_attr = isset($setting['attr']) ? $setting['attr'] : null;
		if ( $attr !== $ex_attr ){
			foreach( $attr as $key => $val ):
				$title = $caption = $alt = '';
				foreach ( $val as $key2 => $val2 ){
					if ( trim($key2) == 'title' ) $title = $val2;
					elseif ( trim($key2) == 'caption' ) $caption = $val2;
					elseif ( trim($key2) == 'alt' ) $alt = $val2;
				}
				wp_update_post( array( 'ID' => $key, 'post_title' => $title, 'post_excerpt' => $caption ) );
				update_post_meta( $key, '_wp_attachment_image_alt', $alt );
			endforeach; 
		}
		$view = isset($_POST['my_upload_images_view']) ? $_POST['my_upload_images_view']: null;
		$newvalue = array( 'view' => $view, 'attr' => $attr );
		update_post_meta( $post_id, '_my_upload_images_meta', $newvalue );

		$opt = get_option('mui_options');
		$new_images = isset($_POST['my_upload_images']) ? $_POST['my_upload_images']: null;
		$ex_images = get_post_meta( $post_id, 'my_upload_images', true );
		$num = isset($opt[ 'maxnum' ]) ? (int)$opt[ 'maxnum' ] : 0;
		if ( $num > 0 && count( $new_images ) > $num )
			$new_images = array_slice( $new_images, 0, $num );

		if ( $ex_images !== $new_images ){
			if ( $new_images ){
				update_post_meta( $post_id, 'my_upload_images', $new_images );
			} else {
				delete_post_meta( $post_id, 'my_upload_images', $ex_images );
			}
		}

		if ( isset($opt['postthumb']) && $opt['postthumb'] == 'generate' ) { // USING MY UPLOAD IMAGES AS POST THUMBNAIL
			$image = get_post_meta( $post_id, 'my_upload_images', true );
			if ( isset( $image[0] ) && $image[0] ){
				update_post_meta( $post_id, '_thumbnail_id', $image[0] );
			}
		}
	}


	public function mui_save_preview_postmeta( $post_id ) {

		if( isset( $_POST[ 'wp-preview' ] ) && $_POST[ 'wp-preview' ] === 'dopreview' ) $post_id = wp_get_post_parent_id( $post_id ); //if a preview hit
		if ( wp_is_post_revision( $post_id ) ) {
			if ( isset( $_POST['my_upload_images'] ) ):
				add_metadata( 'post', $post_id, 'my_upload_images', serialize( $_POST['my_upload_images'] ), true );
			endif;
		}
		return $post_id;
	}


	// function mui_preview_postmeta( $return, $post_id, $meta_key, $single ) {
	// 	global $post;
	// 	if ( $post->ID == $post_id && is_preview() && $preview = wp_get_post_autosave( $post->ID ) ) {
	// 		if ( $post_id !== $preview->ID ) {
	// 			$return = get_post_meta( $preview->ID, $meta_key, $single );
	// 		}
	// 	}
	// 	return $return;
	// }
	// add_filter( 'get_post_metadata', 'mui_preview_postmeta', 10, 4 );
}
new MyUPIMG();
}
