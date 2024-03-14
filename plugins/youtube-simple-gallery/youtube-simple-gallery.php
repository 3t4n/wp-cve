<?php
/*
Plugin Name: YouTube Simple Gallery
Version: 2.2.0
Description: YouTube Simple Gallery is a plugin you can create an area for YouTube videos quickly with management by shortcode.
Author: CHR Designer
Author URI: http://www.chrdesigner.com
Plugin URI: http://wordpress.org/plugins/youtube-simple-gallery/
License: A slug describing license associated with the plugin (usually GPL2)
Text Domain: youtube-simple-gallery
Domain Path: /languages/
*/

load_plugin_textdomain( 'youtube-simple-gallery', false, plugin_basename( dirname( __FILE__ ) ) . '/languages/' );

require_once('custom-post-youtube-gallery.php');

require_once('custom-taxonomy-youtube-gallery.php');

add_image_size( 'chr-thumb-youtube', 320, 180, true  );

function ysg_plugin_action_links( $links, $file ) {
	$settings_link = '<a href="' . admin_url( 'themes.php?page=ysg_settings' ) . '">' . __( 'Configura&ccedil;&otilde;es Gerais', 'youtube-simple-gallery' ) . '</a>';
	if ( $file == 'youtube-simple-gallery/youtube-simple-gallery.php' )
		array_unshift( $links, $settings_link );

	return $links;
}
add_filter( 'plugin_action_links', 'ysg_plugin_action_links', 10, 2 );

function ysg_create_page_personality_gallery() {
	$title_galeria = __('Galeria de V&#237;deo', 'youtube-simple-gallery');
	$check_title=get_page_by_title($title_galeria, 'OBJECT', 'page');
	if (empty($check_title) ){
		$chr_page_gallery = array(
			'post_title' 	 => $title_galeria,
			'post_type' 	 => 'page',
			'post_name'	 	 => __('galeria-de-video', 'youtube-simple-gallery'),
			'comment_status' => 'closed',
			'ping_status'    => 'closed',
			'post_content'   => '[chr-youtube-gallery order="DESC" orderby="date" posts="6"]',
			'post_status'    => 'publish',
			'post_author'    => 1,
			'menu_order'     => 0
		);
		wp_insert_post( $chr_page_gallery );
	}
}
register_activation_hook( __FILE__, 'ysg_create_page_personality_gallery' );

require_once('generation-codes.php');

function ysg_script_UtubeGallery() {
	wp_enqueue_script(array('jquery', 'thickbox'));
	wp_register_style( 'style-UtubeGallery', plugins_url('/assets/css/style-UtubeGallery-min.css' , __FILE__ ) );
	wp_enqueue_style( 'style-UtubeGallery' );
}  
add_action('wp_print_scripts', 'ysg_script_UtubeGallery');

function ysg_admin_style() {
    wp_register_style( 'style.ysg.admin', plugins_url('/admin/css/style.ysg.admin.min.css' , __FILE__ ), false, '2.1.2', false );
    wp_enqueue_style( 'style.ysg.admin' );
    wp_enqueue_style( 'font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css' );
    wp_enqueue_style( 'font-awesome' );
}
add_action( 'admin_enqueue_scripts', 'ysg_admin_style' );

require_once('content-list-gallery.php');

add_action( 'init', 'ysg_chrUtube_buttons' );

function ysg_chrUtube_buttons() {
	add_filter("mce_external_plugins", "ysg_chrUtube_add_buttons");
    add_filter('mce_buttons', 'ysg_chrUtube_register_buttons');
}	
function ysg_chrUtube_add_buttons($plugin_array) {
	$plugin_array['chrUtube'] = plugins_url( '/admin/tinymce/chrUtube-tinymce.js' , __FILE__ );
	return $plugin_array;
}
function ysg_chrUtube_register_buttons($buttons) {
	array_push( $buttons, 'showUtube' );
	return $buttons;
}

require_once('widget.php');

/*
 * Create and Include custom single page - single-youtube-gallery.php
 */

add_filter( 'template_include', 'include_template_ysg', 1 );
function include_template_ysg( $template_path ) {
    if ( get_post_type() == 'youtube-gallery' ) {
        if ( is_single() ) {
            if ( $theme_file = locate_template( array ( 'single-youtube-gallery.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/single-youtube-gallery.php';
            }
        }
    }
    return $template_path;
}

/*
 * Start - Settings Page - YouTube Simple Gallery
 */

$ysg_options = array(
	'ysg_size_wight' => '640',
	'ysg_size_height' => '390',
	'ysg_thumb_wight' => '320',
	'ysg_thumb_height' => '180',
	'ysg_thumb_s_wight' => '160',
	'ysg_thumb_s_height' => '90',
	'ysg_autoplay' => '0'
);

if ( is_admin() ) :
/**
 * YouTube Simple Gallery columns.
 */
function ysg_edit_columns( $columns ) {
    $columns = array(
        'cb' => '<input type="checkbox" />',
        'ysg_image' => __( 'Imagem do V&iacute;deo', 'youtube-simple-gallery' ),
        'ysg_title' => __( 'T&iacute;tulo', 'youtube-simple-gallery' ),
        'ysg_categories' => __("Categoria", 'youtube-simple-gallery' ),
        'ysg_url' => __( 'Link do V&iacute;deo', 'youtube-simple-gallery' )
    );

    return $columns;
}
add_filter( 'manage_edit-youtube-gallery_columns', 'ysg_edit_columns' );

/**
 * YouTube Simple Gallery custom columns content.
 */
function ysg_posts_columns( $column, $post_id ) {
    switch ( $column ) {
    	case 'ysg_image':
			$ysgGetId = get_post_meta($post_id, 'valor_url', true );
			$ysgPrintId = ysg_youtubeEmbedFromUrl($ysgGetId);
			echo sprintf( '<a href="%1$s" title="%2$s">', admin_url( 'post.php?post=' . $post_id . '&action=edit' ), get_the_title() );
			if ( has_post_thumbnail()) { 
				the_post_thumbnail(array(150,90)); 
			}else{
				echo '<img title="'. get_the_title().'" alt="'. get_the_title().'" src="http://img.youtube.com/vi/' . $ysgPrintId .'/mqdefault.jpg" width="150" height="90" />';	
			}
			echo '</a>';
            break;

        case 'ysg_title':
            echo sprintf( '<a href="%1$s" title="%2$s">%2$s</a>', admin_url( 'post.php?post=' . $post_id . '&action=edit' ), get_the_title() );
            break;
        
        case "ysg_categories":
		$ysgTerms = get_the_terms($post_id, 'youtube-videos');
		if ( !empty( $ysgTerms ) )
		{
			$ysgOut = array();
			foreach ( $ysgTerms as $ysgTerm )
				$ysgOut[] = '<a href="edit.php?post_type=youtube-gallery&youtube-videos=' . $ysgTerm->slug . '">' . esc_html(sanitize_term_field('name', $ysgTerm->name, $ysgTerm->term_id, 'youtube-videos', 'display')) . '</a>';
			echo join( ', ', $ysgOut );
		}
		else
		{
			echo __( 'Sem Categoria', 'youtube-simple-gallery' );
		}
		break;
			
        case 'ysg_url':
            $idvideo = get_post_meta($post_id, 'valor_url', true );            
            echo ! empty( $idvideo ) ? sprintf( '<a href="%1$s" target="_blank">%1$s</a>', esc_url( $idvideo ) ) : '';
            break;
    }
}

add_action( 'manage_posts_custom_column', 'ysg_posts_columns', 1, 2 );

function ysg_register_settings() {
	register_setting( 'ysg_plugin_options', 'ysg_options', 'ysg_validate_options' );
}
add_action( 'admin_init', 'ysg_register_settings' );

$ysg_btn_autoplay = array(
	'1' => array(
		'value' => '1',
		'label' => 'True'
	),
	'0' => array(
		'value' => '0',
		'label' => 'False'
	),
);

function ysg_plugin_options() {
	add_theme_page( 'YouTube Simple Gallery', __('YSG Configurações', 'youtube-simple-gallery'), 'manage_options', 'ysg_settings', 'ysg_plugin_options_page' );
}
add_action( 'admin_menu', 'ysg_plugin_options' );

function ysg_plugin_options_page() {
	global $ysg_options, $ysg_btn_autoplay;

	if ( ! isset( $_REQUEST['settings-updated'] ) ) $_REQUEST['settings-updated'] = false; ?>

	<div class="wrap">

		<?php screen_icon(); echo "<h2 class='title-ysg'>" . __( ' YouTube Simple Gallery - Configura&ccedil;&otilde;es','youtube-simple-gallery' ) . "</h2>"; ?>
	
		<?php if ( false !== $_REQUEST['settings-updated'] ) : ?>
		<div class="updated fade"><p><strong><?php echo __('Op&ccedil;&otilde;es Salvas','youtube-simple-gallery' );?></strong></p></div>
		<?php endif; // If the form has just been submitted, this shows the notification ?>
	
		<form method="post" action="options.php">
			<?php $settings = get_option( 'ysg_options', $ysg_options ); settings_fields( 'ysg_plugin_options' ); ?>
			<h3 class="title-ysg-red"><?php echo __('Configura&ccedil;&otilde;es padr&otilde;es do YouTube Simple Gallery','youtube-simple-gallery' );?></h3>
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="ysg_size_wight"><?php echo __('Tamanho do V&iacute;deo','youtube-simple-gallery' );?></label></th>
					<td>
						<input id="ysg_size_wight" name="ysg_options[ysg_size_wight]" type="text" value="<?php esc_attr_e($settings['ysg_size_wight']); ?>" style="width: 40px;" />x<input id="ysg_size_height" name="ysg_options[ysg_size_height]" type="text" value="<?php esc_attr_e($settings['ysg_size_height']); ?>" style="width: 40px;" />
					</td>
				</tr>
				<tr valign="top">
					<td colspan="2" class="figure-ysg">
						<figure>
							<img src="http://dummyimage.com/<?php esc_attr_e($settings['ysg_size_wight']); ?>x<?php esc_attr_e($settings['ysg_size_height']); ?>/b0b0b0/fff.png" alt="" title="" >
							<figcaption><?php _e('Tamanho Real do Embed','youtube-simple-gallery');?></figcaption>
						</figure>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="ysg_thumb_wight"><?php echo __('Tamanho do Thumbnail Maior','youtube-simple-gallery' );?></label></th>
					<td>
						<input id="ysg_thumb_wight" name="ysg_options[ysg_thumb_wight]" type="text" value="<?php esc_attr_e($settings['ysg_thumb_wight']); ?>" style="width: 40px;" />x<input id="ysg_thumb_height" name="ysg_options[ysg_thumb_height]" type="text" value="<?php esc_attr_e($settings['ysg_thumb_height']); ?>" style="width: 40px;" />
					</td>
				</tr>
				<tr valign="top">
					<td colspan="2" class="figure-ysg">
						<figure>
							<img src="http://dummyimage.com/<?php esc_attr_e($settings['ysg_thumb_wight']); ?>x<?php esc_attr_e($settings['ysg_thumb_height']); ?>/b0b0b0/fff.png" alt="" title="" >
							<figcaption><?php _e('Tamanho Real do Thumbnail Maior','youtube-simple-gallery');?></figcaption>
						</figure>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><label for="ysg_thumb_s_wight"><?php echo __('Tamanho do Thumbnail Menor','youtube-simple-gallery' );?></label></th>
					<td>
						<input id="ysg_thumb_s_wight" name="ysg_options[ysg_thumb_s_wight]" type="text" value="<?php esc_attr_e($settings['ysg_thumb_s_wight']); ?>" style="width: 40px;" />x<input id="ysg_thumb_s_height" name="ysg_options[ysg_thumb_s_height]" type="text" value="<?php esc_attr_e($settings['ysg_thumb_s_height']); ?>" style="width: 40px;" />
					</td>
				</tr>
				<tr valign="top">
					<td colspan="2" class="figure-ysg">
						<figure>
							<img src="http://dummyimage.com/<?php esc_attr_e($settings['ysg_thumb_s_wight']); ?>x<?php esc_attr_e($settings['ysg_thumb_s_height']); ?>/b0b0b0/fff.png" alt="" title="" >
							<figcaption><?php _e('Tamanho Real do Thumbnail Menor','youtube-simple-gallery');?></figcaption>
						</figure>
					</td>
				</tr>
				<tr valign="top"><th scope="row"><?php echo __('AutoPlay','youtube-simple-gallery' );?></th>
					<td>
					<?php foreach( $ysg_btn_autoplay as $autoplay ) : ?>
						<input type="radio" id="<?php echo 'autoplay-' . $autoplay['value']; ?>" name="ysg_options[ysg_autoplay]" value="<?php esc_attr_e( $autoplay['value'] ); ?>" <?php checked( $settings['ysg_autoplay'], $autoplay['value'] ); ?> />
						<label for="<?php echo 'autoplay-' . $autoplay['value']; ?>"><?php echo $autoplay['label']; ?></label><br />
					<?php endforeach; ?>
					</td>
				</tr>
			</table>
			<p class="submit"><input type="submit" class="button-primary" value="<?php echo __('Salvar Op&ccedil;&otilde;es','youtube-simple-gallery' );?>" /></p>
			<p class="alignright"><?php _e('Desenvolvido por','youtube-simple-gallery' );?> <a href="http://www.chrdesigner.com" style="text-decoration: none;" target="_blank">CHR Designer</a></p>
		</form>
	</div>
	<?php
}
function ysg_validate_options( $input ) {
	global $ysg_options, $ysg_btn_autoplay;
	$settings = get_option( 'ysg_options', $ysg_options );
	$input['ysg_size_wight'] = wp_filter_nohtml_kses( $input['ysg_size_wight'] );
	$input['ysg_size_height'] = wp_filter_nohtml_kses( $input['ysg_size_height'] );
	$input['ysg_thumb_wight'] = wp_filter_nohtml_kses( $input['ysg_thumb_wight'] );
	$input['ysg_thumb_height'] = wp_filter_nohtml_kses( $input['ysg_thumb_height'] );
	$input['ysg_thumb_s_wight'] = wp_filter_nohtml_kses( $input['ysg_thumb_s_wight'] );
	$input['ysg_thumb_s_height'] = wp_filter_nohtml_kses( $input['ysg_thumb_s_height'] );
	$prev = $settings['ysg_autoplay'];
	if ( !array_key_exists( $input['ysg_autoplay'], $ysg_btn_autoplay ) )
		$input['ysg_autoplay'] = $prev;
	return $input;
}
endif;
/*/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/// End | Settings Page - YouTube Simple Gallery//////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////*/