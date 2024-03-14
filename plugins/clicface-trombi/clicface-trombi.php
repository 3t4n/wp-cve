<?php
/*
Plugin Name: Clicface Trombi
Plugin URI: https://plugins.clicface.com/
Description: A great plugin for WordPress that creates a directory of all your employees.
Version: 2.08
Author: Clicface
Author URI: https://plugins.clicface.com/
License: GPL2
*/

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
require_once( plugin_dir_path(__FILE__) . 'includes/class-collaborateur.php' );

add_action( 'init', 'clicface_trombi_initialize' );
function clicface_trombi_initialize() {
	register_setting('clicface_trombi_settings_group', 'clicface_trombi_settings', 'clicface_trombi_settings_validate' );
	load_plugin_textdomain('clicface-trombi', false, dirname( plugin_basename( __FILE__ ) ) . '/i18n/');
	include_once( plugin_dir_path( __FILE__ ) . '/includes/settings-initialization.php' );
	wp_register_style( 'clicface-trombi-style', plugins_url('clicface-trombi/css/clicface-trombi.css') );
	add_post_type_support( 'collaborateur', 'author' );
}

add_action( 'admin_print_styles', 'clicface_trombi_admin_styles' );
function clicface_trombi_admin_styles() {
    global $typenow;
    if( $typenow == 'collaborateur' ) {
        wp_enqueue_style( 'clicface-trombi-admin-style', plugin_dir_url( __FILE__ ) . 'css/clicface-trombi-admin-styles.css' );
    }
}

register_activation_hook( __FILE__, 'clicface_trombi_activate' );
function clicface_trombi_activate() {
	clicface_trombi_register_cpt_collaborateur();
	flush_rewrite_rules();
}

add_action( 'init', 'clicface_trombi_register_cpt_collaborateur' );
function clicface_trombi_register_cpt_collaborateur() {
	$clicface_trombi_settings = get_option('clicface_trombi_settings');
	if ( !isset( $clicface_trombi_settings['trombi_title_name_singular'] ) ) $clicface_trombi_settings['trombi_title_name_singular'] = __('Employee', 'clicface-trombi');
	if ( !isset( $clicface_trombi_settings['trombi_title_name_plural'] ) ) $clicface_trombi_settings['trombi_title_name_plural'] = __('Employees', 'clicface-trombi');
	$label_name_plural = ($clicface_trombi_settings['trombi_title_name_plural']) ? $clicface_trombi_settings['trombi_title_name_plural'] : __('Employees', 'clicface-trombi');
	$label_name_singular = ($clicface_trombi_settings['trombi_title_name_singular']) ? $clicface_trombi_settings['trombi_title_name_singular'] : __('Employee', 'clicface-trombi');
	$labels = array(
		'name' => ucwords($label_name_plural),
		'singular_name' => ucwords($label_name_singular),
		'add_new' => __('Add New', 'clicface-trombi'),
		'add_new_item' => sprintf(__('Add New %s', 'clicface-trombi'), ucwords($label_name_singular)),
		'edit_item' => sprintf(__('Edit %s', 'clicface-trombi'), ucwords($label_name_singular)),
		'new_item' => sprintf(__('New %s', 'clicface-trombi'), ucwords($label_name_singular)),
		'view_item' => sprintf(__('View %s', 'clicface-trombi'), ucwords($label_name_singular)),
		'search_items' => sprintf(__('Search %s', 'clicface-trombi'), ucwords($label_name_plural)),
		'not_found' => sprintf(__('No %s found', 'clicface-trombi'), ucwords($label_name_singular)),
		'not_found_in_trash' => sprintf(__('No %s found in Trash', 'clicface-trombi'), ucwords($label_name_singular)),
		'parent_item_colon' => sprintf(__('Parent %s:', 'clicface-trombi'), ucwords($label_name_singular)),
		'menu_name' => ucwords($label_name_plural),
	);

	$args = array(
		'labels' => $labels,
		'hierarchical' => false,

		'supports' => array( 'title' ),

		'public' => true,
		'menu_position' => 17,
		'menu_icon' => 'dashicons-id',

		'has_archive' => false,
		'query_var' => false,
		'can_export' => true,
		'rewrite' => true,
		'capability_type' => 'post'
	);

	register_post_type( 'collaborateur', $args );
}

add_action( 'add_meta_boxes_collaborateur', 'adding_collaborateur_meta_boxes' );
function adding_collaborateur_meta_boxes( $post ) {
	add_meta_box( 'collaborateur_infos', __('Data Form', 'clicface-trombi'), 'collaborateur_infos_render', 'collaborateur', 'normal', 'default');
	add_meta_box( 'help_infos', __('Help', 'clicface-trombi'), 'help_infos_render', 'collaborateur', 'side', 'low');
}
function collaborateur_infos_render( $post ) {
	$nom				= get_post_meta($post->ID, 'nom', true);
	$prenom				= get_post_meta($post->ID, 'prenom', true);
	$mail				= get_post_meta($post->ID, 'mail', true);
	$website			= get_post_meta($post->ID, 'website', true);
	$fonction			= get_post_meta($post->ID, 'fonction', true);
	$telephone_fixe		= get_post_meta($post->ID, 'telephone_fixe', true);
	$telephone_portable	= get_post_meta($post->ID, 'telephone_portable', true);
	$commentaires		= get_post_meta($post->ID, 'commentaires', true);
	$facebook			= get_post_meta($post->ID, 'facebook', true);
	$twitter			= get_post_meta($post->ID, 'twitter', true);
	$linkedin			= get_post_meta($post->ID, 'linkedin', true);
	$youtube			= get_post_meta($post->ID, 'youtube', true);
?>
<div class="clicface-field-container">
	<div class="clicface-label-container">
		<label class="clicface-label" for="nom"><?php _e('Last Name', 'clicface-trombi'); ?></label>
	</div>
	<div class="clicface-field">
		<input id="nom" type="text" name="nom" value="<?php echo $nom; ?>" />
	</div>
</div>

<div class="clicface-field-container">
	<div class="clicface-label-container">
		<label class="clicface-label" for="prenom"><?php _e('First Name', 'clicface-trombi'); ?></label>
	</div>
	<div class="clicface-field">
		<input id="prenom" type="text" name="prenom" value="<?php echo $prenom; ?>" />
	</div>
</div>

<div class="clicface-field-container">
	<div class="clicface-label-container">
		<label class="clicface-label" for="mail"><?php _e('E-mail', 'clicface-trombi'); ?></label>
	</div>
	<div class="clicface-field">
		<input id="mail" type="text" name="mail" value="<?php echo $mail; ?>" />
	</div>
</div>

<div class="clicface-field-container">
	<div class="clicface-label-container">
		<label class="clicface-label" for="website"><?php _e('Website', 'clicface-trombi'); ?></label>
		<span class="description"><?php _e('Type the address with http://', 'clicface-trombi') ?></span>
	</div>
	<div class="clicface-field">
		<input id="website" type="text" name="website" value="<?php echo $website; ?>" />
	</div>
</div>

<div class="clicface-field-container">
	<div class="clicface-label-container">
		<label class="clicface-label" for="fonction"><?php _e('Job Title', 'clicface-trombi'); ?></label>
	</div>
	<div class="clicface-field">
		<input id="fonction" type="text" name="fonction" value="<?php echo $fonction; ?>" />
	</div>
</div>

<div class="clicface-field-container">
	<div class="clicface-label-container">
		<label class="clicface-label" for="telephone_fixe"><?php _e('Landline Number', 'clicface-trombi'); ?></label>
	</div>
	<div class="clicface-field">
		<input id="telephone_fixe" type="text" name="telephone_fixe" value="<?php echo $telephone_fixe; ?>" />
	</div>
</div>

<div class="clicface-field-container">
	<div class="clicface-label-container">
		<label class="clicface-label" for="telephone_portable"><?php _e('Mobile Number', 'clicface-trombi'); ?></label>
	</div>
	<div class="clicface-field">
		<input id="telephone_portable" type="text" name="telephone_portable" value="<?php echo $telephone_portable; ?>" />
	</div>
</div>

<div class="clicface-field-container">
	<div class="clicface-label-container">
		<label class="clicface-label" for="commentaires"><?php _e('Comments', 'clicface-trombi'); ?></label>
	</div>
	<div class="clicface-field">
		<textarea id="commentaires" name="commentaires" rows="3" cols="50"><?php echo $commentaires; ?></textarea>
	</div>
</div>

<div class="clicface-field-container">
	<div class="clicface-label-container">
		<label class="clicface-label" for="facebook"><?php _e('Facebook', 'clicface-trombi'); ?></label>
		<span class="description"><?php _e('Type the address with http://', 'clicface-trombi') ?></span>
	</div>
	<div class="clicface-field">
		<input id="facebook" type="text" name="facebook" value="<?php echo $facebook; ?>" />
	</div>
</div>

<div class="clicface-field-container">
	<div class="clicface-label-container">
		<label class="clicface-label" for="twitter"><?php _e('Twitter', 'clicface-trombi'); ?></label>
		<span class="description"><?php _e('Type the address with http://', 'clicface-trombi') ?></span>
	</div>
	<div class="clicface-field">
		<input id="twitter" type="text" name="twitter" value="<?php echo $twitter; ?>" />
	</div>
</div>

<div class="clicface-field-container">
	<div class="clicface-label-container">
		<label class="clicface-label" for="linkedin"><?php _e('LinkedIn', 'clicface-trombi'); ?></label>
		<span class="description"><?php _e('Type the address with http://', 'clicface-trombi') ?></span>
	</div>
	<div class="clicface-field">
		<input id="linkedin" type="text" name="linkedin" value="<?php echo $linkedin; ?>" />
	</div>
</div>

<div class="clicface-field-container">
	<div class="clicface-label-container">
		<label class="clicface-label" for="youtube"><?php _e('Youtube', 'clicface-trombi'); ?></label>
		<span class="description"><?php _e('Type the address with http://', 'clicface-trombi') ?></span>
	</div>
	<div class="clicface-field">
		<input id="youtube" type="text" name="youtube" value="<?php echo $youtube; ?>" />
	</div>
</div>

<div class="clicface-field-container">
	<div class="clicface-label-container"></div>
	<div class="clicface-field"></div>
</div>
<?php
}

function help_infos_render( $post ) {
	wp_enqueue_style('clicface-trombi-style');
?>
<p>
	<strong><?php _e('Clicface Trombi Support', 'clicface-trombi'); ?></strong>

	<ol>
		<li><a href="https://plugins.clicface.com/how-to-use" target="_blank">Documentation</a></li>
		<li><a href="https://plugins.clicface.com/faq" target="_blank">FAQ</a></li>
		<li><a href="https://plugins.clicface.com/support" target="_blank">Support Ticket System</a></li>
	</ol>
</p>
<br />
<p>
	<strong><?php _e( 'Help Promote Clicface Trombi' , 'clicface-trombi'); ?></strong>
	<ul id="promote-clicface-trombi">
		<li id="star"><a href="https://plugins.clicface.com/" target="_blank"><?php _e( 'Get QR Code, Search and Org Chart' , 'clicface-trombi'); ?></a></li>
		<li id="twitter"><?php _e( 'Twitter:' , 'clicface-trombi'); ?> <a href="https://twitter.com/#!/ClicfacePlugins" target="_blank">@ClicfacePlugins</a></li>
		<li id="star"><a href="https://wordpress.org/plugins/clicface-trombi/" target="_blank"><?php _e( 'Rate Clicface Trombi on WordPress.org' , 'clicface-trombi'); ?></a></li>
	</ul>
</p>
<?php
}

add_action('init', 'create_clicface_trombi_taxonomies');
function create_clicface_trombi_taxonomies() {
	// Services
	$labels = array(
		'name'                       => __('Divisions', 'clicface-trombi'),
		'singular_name'              => __('Division', 'clicface-trombi'),
		'parent_item'                => null,
		'parent_item_colon'          => null,
	);

	$args = array(
		'hierarchical'          => true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'collaborateur-service' ),
	);

	register_taxonomy( 'collaborateur_service', 'collaborateur', $args );
	
	// Worksites
	$labels = array(
		'name'                       => __('Worksites', 'clicface-trombi'),
		'singular_name'              => __('Worksite', 'clicface-trombi'),
		'parent_item'                => null,
		'parent_item_colon'          => null,
	);

	$args = array(
		'hierarchical'          => true,
		'labels'                => $labels,
		'show_ui'               => true,
		'show_admin_column'     => true,
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'collaborateur-worksite' ),
	);

	register_taxonomy( 'collaborateur_worksite', 'collaborateur', $args );
}

add_action('new_to_publish', 'trombi_check_num_collab');
add_action('auto-draft_to_publish', 'trombi_check_num_collab');
add_action('draft_to_publish', 'trombi_check_num_collab');
add_action('pending_to_publish', 'trombi_check_num_collab');
function trombi_check_num_collab( $post ) {
	if ($post->post_type == 'collaborateur') {
		if ( wp_count_posts('collaborateur')->publish > strlen('-clicface-') ) {
			wp_delete_post( $post->ID, true );
			wp_die( __('You can\'t create any more record, you have already reached the limit. You have to update your Clicface Trombi plugin version to add more records.', 'clicface-trombi') . '<br /><center><a href="https://plugins.clicface.com/" target="_blank">https://plugins.clicface.com/</a></center>' );
		}
	}
}

add_action('save_post','save_collaborateur_metaboxes');
function save_collaborateur_metaboxes( $post_ID ) {
	if( isset($_POST['nom']) )					update_post_meta( $post_ID, 'nom', sanitize_text_field($_POST['nom']) );
	if( isset($_POST['prenom']) )				update_post_meta( $post_ID, 'prenom', sanitize_text_field($_POST['prenom']) );
	if( isset($_POST['mail']) )					update_post_meta( $post_ID, 'mail', sanitize_text_field($_POST['mail']) );
	if( isset($_POST['website']) )				update_post_meta( $post_ID, 'website', sanitize_text_field($_POST['website']) );
	if( isset($_POST['fonction']) )				update_post_meta( $post_ID, 'fonction', sanitize_text_field($_POST['fonction']) );
	if( isset($_POST['telephone_fixe']) )		update_post_meta( $post_ID, 'telephone_fixe', sanitize_text_field($_POST['telephone_fixe']) );
	if( isset($_POST['telephone_portable']) )	update_post_meta( $post_ID, 'telephone_portable', sanitize_text_field($_POST['telephone_portable']) );
	if( isset($_POST['commentaires']) )			update_post_meta( $post_ID, 'commentaires', esc_html($_POST['commentaires']) );
	if( isset($_POST['facebook']) )				update_post_meta( $post_ID, 'facebook', sanitize_text_field($_POST['facebook']) );
	if( isset($_POST['twitter']) )				update_post_meta( $post_ID, 'twitter', sanitize_text_field($_POST['twitter']) );
	if( isset($_POST['linkedin']) )				update_post_meta( $post_ID, 'linkedin', sanitize_text_field($_POST['linkedin']) );
	if( isset($_POST['youtube']) )				update_post_meta( $post_ID, 'youtube', sanitize_text_field($_POST['youtube']) );
}

add_action ('save_post', 'titlize_collaborateur');
function titlize_collaborateur( $post_id ) {
	$type = get_post_type( $post_id );
	if ($type == 'collaborateur') {
		$update_post['ID'] = $post_id;
		
		if ( isset($_POST['nom']) &&  isset($_POST['prenom']) ) {
			update_post_meta($post_id, 'nom', $_POST['nom']);
			update_post_meta($post_id, 'prenom', $_POST['prenom']);
			update_post_meta($post_id, 'mail', $_POST['mail']);
			update_post_meta($post_id, 'website', $_POST['website']);
			update_post_meta($post_id, 'fonction', $_POST['fonction']);
			update_post_meta($post_id, 'telephone_fixe', $_POST['telephone_fixe']);
			update_post_meta($post_id, 'telephone_portable', $_POST['telephone_portable']);
			update_post_meta($post_id, 'commentaires', $_POST['commentaires']);
			update_post_meta($post_id, 'facebook', $_POST['facebook']);
			update_post_meta($post_id, 'twitter', $_POST['twitter']);
			update_post_meta($post_id, 'linkedin', $_POST['linkedin']);
			update_post_meta($post_id, 'youtube', $_POST['youtube']);
		}
		
		// On sauvegarde une première fois
		remove_action('save_post', 'titlize_collaborateur'); // unhook this function so it doesn't loop infinitely
		wp_update_post( $update_post );
		
		// On met à jour le titre, et on sauvegarde à nouveau
		$update_post['post_title'] = get_post_meta($post_id, 'prenom', true) . ' ' . get_post_meta($post_id, 'nom', true);
		wp_update_post( $update_post );
		
		add_action ('save_post', 'titlize_collaborateur'); // re-hook this function
	} else {
		return true;
	}
}

add_filter( 'template_include', 'wpse_57232_render_cpt', 100 );
function wpse_57232_render_cpt( $template ) {
	$post_type = 'collaborateur';
	if ( FALSE !== strpos( $template, "/single-$post_type.php" ) ) {
		return $template;
	}
	if ( is_singular() && $post_type === get_post_type( $GLOBALS['post'] ) ) {
		return plugin_dir_path( __FILE__ ) . "/templates/single-$post_type.php";
	}
	return $template;
}

add_shortcode( 'clicface-trombi', 'trombi_display_views' );
function trombi_display_views() {
	$clicface_trombi_settings = get_option('clicface_trombi_settings');
	wp_enqueue_style('clicface-trombi-style');
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-dialog');
	$output = '';
	
	include_once( plugin_dir_path( __FILE__ ) . '/includes/settings-initialization.php' );
	
	// query
	$args = array(
			'post_type' => 'collaborateur',
			'posts_per_page' => -1,
			'meta_key' => 'nom',
			'orderby' => 'meta_value',
			'order' => 'ASC',
		);
	$the_query = new WP_Query($args);
	
	switch($clicface_trombi_settings['trombi_target_window']) {
		case 'thickbox':
			$ExtraLink = '?TB_iframe=true&width=' . $clicface_trombi_settings['trombi_thickbox_width'] . '&height=' . $clicface_trombi_settings['trombi_thickbox_height'];
			$WindowTarget = '_self';
			$ExtraClassImg = 'class="thickbox"';
			$ExtraClassTxt = 'thickbox';
			add_thickbox();
		break;
		
		case '_self':
			$ExtraLink = ($clicface_trombi_settings['trombi_move_to_anchor'] == 'oui') ? '#ClicfaceTrombi' : '';
			$WindowTarget = '_self';
			$ExtraClassImg = '';
			$ExtraClassTxt = '';
		break;
		
		default: // _blank
			$ExtraLink = ($clicface_trombi_settings['trombi_move_to_anchor'] == 'oui') ? '#ClicfaceTrombi' : '';
			$WindowTarget = '_blank';
			$ExtraClassImg = '';
			$ExtraClassTxt = '';
		break;
	}
	
	switch($clicface_trombi_settings['trombi_affichage_type']) {
		case 'list':
			$output .= '<table class="clicface-trombi-table">';
			$output .= '<tr><td colspan="2" style="border: none;"><hr></td></tr>';
			while ( $the_query->have_posts() ) : $the_query->the_post();
				$collaborateur = new clicface_Collaborateur( get_the_ID() );
				$output .= '<tr><td style="border: none;">';
				$output .= '<a class="clicface-trombi-collaborateur ' . $ExtraClassTxt .'" href="'. $collaborateur->Link . $ExtraLink .'" target="'. $WindowTarget .'" ' . $ExtraClassImg . '><div>';
				$output .= '<div class="clicface-trombi-person-name">' . $collaborateur->Nom . '</div>';
				$output .= '<div class="clicface-trombi-person-function">' . $collaborateur->Fonction . '</div>';
				if ( $clicface_trombi_settings['trombi_display_service'] == 'oui' ) {
					$output .= '<br /><u>' . __('Division:', 'clicface-trombi') . '</u><br /><div class="clicface-trombi-person-service">' . $collaborateur->Service . '</div>';
				}
				if ( $clicface_trombi_settings['trombi_display_phone'] == 'oui' && $collaborateur->TelephoneFixe != NULL ) {
					$output .= '<br />' . __('Phone:', 'clicface-trombi') . ' ' . $collaborateur->TelephoneFixe;
				}
				if ( $clicface_trombi_settings['trombi_display_cellular'] == 'oui' && $collaborateur->TelephonePortable != NULL ) {
					$output .= '<br />' . __('Cell:', 'clicface-trombi') . ' ' . $collaborateur->TelephonePortable;
				}
				if ( $clicface_trombi_settings['trombi_display_email'] == 'oui' && $collaborateur->Mail != NULL ) {
					$output .= '<br />' . $collaborateur->Mailto;
				}
				$output .= '</div></a>';
				$output .= '</td><td style="border: none;">';
				$output .= '<div class="clicface-label-container"><a href="' . $collaborateur->Link . $ExtraLink .'" target="'. $WindowTarget .'" ' . $ExtraClassImg . '>' . $collaborateur->PhotoThumbnail . '</a></div>';
				$output .= '</td></tr>';
				$output .= '<tr><td colspan="2" style="border: none;"><hr></td></tr>';
			endwhile;
			$output .= '</table>';
		break;
		
		default: //grid
			$i = 1;
			$output .= '<style type="text/css">.clicface-trombi-cellule {width: ' . $clicface_trombi_settings['vignette_width'] . 'px; background-color: ' . $clicface_trombi_settings['vignette_color_background_top'] . '; background-image: linear-gradient(' . $clicface_trombi_settings['vignette_color_background_top'] . ', ' . $clicface_trombi_settings['vignette_color_background_bottom'] . '); border: ' . $clicface_trombi_settings['vignette_border_thickness'] . 'px solid ' . $clicface_trombi_settings['vignette_color_border'] . ' !important; border-radius: ' . $clicface_trombi_settings['vignette_border_radius'] . 'px;}</style>';
			if ( $clicface_trombi_settings['vignette_ext_drop_shadow'] == 'oui' ) {
				$output .= '<style type="text/css">.clicface-trombi-cellule { box-shadow: 3px 3px 3px rgba(0, 0, 0, 0.5); }</style>';
			}
			if ( $clicface_trombi_settings['vignette_int_drop_shadow'] == 'oui' ) {
				$output .= '<style type="text/css">.clicface-trombi-vignette .clicface-label-container a img { box-shadow: 2px 2px 12px #555; }</style>';
			}
			$output .= '<table class="clicface-trombi-table">';
			$output .= '<tr>';
			while ( $the_query->have_posts() ) : $the_query->the_post();
				$collaborateur = new clicface_Collaborateur( get_the_ID() );
				$output .= '<td class="clicface-trombi-cellule"><div class="clicface-trombi-vignette">';
				$output .= '<div class="clicface-label-container"><a href="' . $collaborateur->Link . $ExtraLink . '" target="'. $WindowTarget .'" ' . $ExtraClassImg . '>' . $collaborateur->PhotoThumbnail . '</a></div>';
				$output .= '<a class="clicface-trombi-collaborateur ' . $ExtraClassTxt . '" href="' . $collaborateur->Link . $ExtraLink . '" target="'. $WindowTarget .'" ' . $ExtraClassImg . '><div>';
				$output .= '<div class="clicface-trombi-person-name">' . $collaborateur->Nom . '</div>';
				$output .= '<div class="clicface-trombi-person-function">' . $collaborateur->Fonction . '</div>';
				if ( $clicface_trombi_settings['trombi_display_service'] == 'oui' ) {
					$output .= '<div class="clicface-trombi-person-service">' . $collaborateur->Service . '</div>';
				}
				if ( $clicface_trombi_settings['trombi_display_phone'] == 'oui' && $collaborateur->TelephoneFixe != NULL ) {
					$output .= '<br />' . __('Phone:', 'clicface-trombi') . ' ' . $collaborateur->TelephoneFixe;
				}
				if ( $clicface_trombi_settings['trombi_display_cellular'] == 'oui' && $collaborateur->TelephonePortable != NULL ) {
					$output .= '<br />' . __('Cell:', 'clicface-trombi') . ' ' . $collaborateur->TelephonePortable;
				}
				if ( $clicface_trombi_settings['trombi_display_email'] == 'oui' && $collaborateur->Mail != NULL ) {
					$output .= '<br />' . $collaborateur->Mailto;
				}
				$output .= '</div></a>';
				$output .= '</div></td>';
				if ( $i % $clicface_trombi_settings['trombi_collaborateurs_par_ligne'] == 0) {
					$output .= '</tr><tr>';
				}
				$i++;
			endwhile;
			$output .= '</table>';
		break;
	}
	wp_reset_postdata();
	return $output;
}

add_action( 'admin_menu' , 'remove_fonction_meta' );
function remove_fonction_meta() {
	remove_meta_box( 'tagsdiv-collaborateur_fonction', 'collaborateur', 'side' );
}

add_action( 'admin_menu' , 'remove_service_meta' );
function remove_service_meta() {
	remove_meta_box( 'tagsdiv-collaborateur_service', 'collaborateur', 'side' );
}

add_action( 'admin_menu' , 'remove_worksite_meta' );
function remove_worksite_meta() {
	remove_meta_box( 'tagsdiv-collaborateur_worksite', 'collaborateur', 'side' );
}

class ClicfaceTrombiImages {
	private $clicface_trombi_images_array = array(
		'0' => array(
			'title' => 'Picture',
			'slug' => 'collaborateur-photo'
		),
	);
	
	private $clicface_trombi_post_types = array('collaborateur');
	
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'clicface_trombi_images_add_meta_box' ) );
		add_action( 'save_post', array( $this, 'clicface_trombi_images_save' ) );
		add_action( 'admin_print_styles', array( $this, 'clicface_trombi_images_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'clicface_trombi_images_image_enqueue' ) );
	}
	
	public function clicface_trombi_images_add_meta_box( $post_type ) {
		if ( in_array( $post_type, $this->clicface_trombi_post_types ) ) {
			add_meta_box(
				'clicface_trombi_images_meta_box'
				,__( 'Picture', 'clicface-trombi' )
				,array( $this, 'clicface_trombi_images_render_meta_box_content' )
				,$post_type
				,'advanced'
				,'high'
			);
		}
	}
	
	public function clicface_trombi_images_render_meta_box_content( $post ) {
		wp_nonce_field( basename( __FILE__ ), 'clicface_trombi_images_nonce' );
		$clicface_trombi_images_stored_meta = get_post_meta( $post->ID );
		echo '<ul id="clicface-trombi-images">';
		foreach( $this->clicface_trombi_images_array as $clicface_trombi_images_image ) {
			$clicface_trombi_images_type_name = "clicface-trombi-images-type-" . $clicface_trombi_images_image['slug'];
?>
<li class="clicface-trombi-images-upload" id="<?php echo $clicface_trombi_images_type_name; ?>">
	<p class="clicface-trombi-images-upload-header"><?php _e('Picture', 'clicface-trombi'); ?></p>
	<div class="clicface-trombi-images-upload-thumbnail">
<?php
			if( $clicface_trombi_images_stored_meta[$clicface_trombi_images_type_name] ) {
				echo wp_get_attachment_image( $clicface_trombi_images_stored_meta[$clicface_trombi_images_type_name][0] );
			}
?>
	</div>
	<input type="button" class="button clicface-trombi-images-button clicface-trombi-images-upload-button" value="<?php _e( 'Choose Image ', 'clicface-trombi' )?>" />
	<input type="button" class="button clicface-trombi-images-button clicface-trombi-images-upload-clear" value="&#215;" />
	<input class="clicface-trombi-images-upload-id" type="hidden" name="<?php echo $clicface_trombi_images_type_name ?>" value="<?php if ( isset ( $clicface_trombi_images_stored_meta[$clicface_trombi_images_type_name] ) ) echo $clicface_trombi_images_stored_meta[$clicface_trombi_images_type_name][0]; ?>" />
</li>
<?php
		}
		echo '<div class="clicface-field-container"><div class="clicface-label-container"></div><div class="clicface-field"></div></div>';
	}
	
	public function clicface_trombi_images_save( $post_id ) {
		if ( ! isset( $_POST['clicface_trombi_images_nonce'] ) ) return $post_id;
		$nonce = $_POST['clicface_trombi_images_nonce'];
		if ( ! wp_verify_nonce( $nonce, basename( __FILE__ ) ) ) return $post_id;
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return $post_id;
		if ( 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) ) return $post_id;
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) ) return $post_id;
		}
		
		foreach( $this->clicface_trombi_images_array as $clicface_trombi_images_image ) {
			$clicface_trombi_images_type_name = "clicface-trombi-images-type-" . $clicface_trombi_images_image['slug'];
			$clicface_trombi_images_data = sanitize_text_field( $_POST[ $clicface_trombi_images_type_name ] );
			if( $clicface_trombi_images_data ) {
				update_post_meta( $post_id, $clicface_trombi_images_type_name, $clicface_trombi_images_data );
			} else {
				delete_post_meta( $post_id, $clicface_trombi_images_type_name );
		    }
		}
	}
	
	public function clicface_trombi_images_image_enqueue() {
		global $typenow;
		if ( in_array( $typenow, $this->clicface_trombi_post_types )) {
			wp_enqueue_media();
			wp_register_script( 'clicface-trombi-images-meta-box-image', plugin_dir_url( __FILE__ ) . 'lib/clicface-trombi-admin-images.js', array( 'jquery' ) );
			wp_localize_script( 'clicface-trombi-images-meta-box-image', 'meta_image',
				array(
					'title' => __( 'Choose or Upload an Image test', 'clicface-trombi' ),
					'button' => __( 'Use this image', 'clicface-trombi' ),
				)
			);
			wp_enqueue_script( 'clicface-trombi-images-meta-box-image' );
		}
	}
	
	public function clicface_trombi_images_admin_styles() {
		global $typenow;
		if ( in_array( $typenow, $this->clicface_trombi_post_types )) {
			wp_enqueue_style( 'clicface_trombi_images_meta_box_styles', plugin_dir_url( __FILE__ ) . 'css/clicface-trombi-admin-images.css' );
		}
	} 
}
$custom_post_images = new ClicfaceTrombiImages();

// Settings
function clicface_trombi_settings_validate($input) {
	$clicface_trombi_settings = get_option('clicface_trombi_settings');
	if( isset( $input['trombi_affichage_type'] ) )				$clicface_trombi_settings['trombi_affichage_type'] = $input['trombi_affichage_type'];
	if( isset( $input['trombi_display_service'] ) )				$clicface_trombi_settings['trombi_display_service'] = $input['trombi_display_service'];
	if( isset( $input['trombi_display_phone'] ) )				$clicface_trombi_settings['trombi_display_phone'] = $input['trombi_display_phone'];
	if( isset( $input['trombi_display_cellular'] ) )			$clicface_trombi_settings['trombi_display_cellular'] = $input['trombi_display_cellular'];
	if( isset( $input['trombi_display_email'] ) )				$clicface_trombi_settings['trombi_display_email'] = $input['trombi_display_email'];
	if( isset( $input['trombi_target_window'] ) )				$clicface_trombi_settings['trombi_target_window'] = $input['trombi_target_window'];
	if( isset( $input['trombi_profile_width_type'] ) )			$clicface_trombi_settings['trombi_profile_width_type'] = $input['trombi_profile_width_type'];
	if( isset( $input['trombi_profile_width_size'] ) )			$clicface_trombi_settings['trombi_profile_width_size'] = $input['trombi_profile_width_size'];
	if( isset( $input['trombi_profile_height_type'] ) )			$clicface_trombi_settings['trombi_profile_height_type'] = $input['trombi_profile_height_type'];
	if( isset( $input['trombi_profile_height_size'] ) )			$clicface_trombi_settings['trombi_profile_height_size'] = $input['trombi_profile_height_size'];
	if( isset( $input['trombi_display_worksite'] ) )			$clicface_trombi_settings['trombi_display_worksite'] = $input['trombi_display_worksite'];
	if( isset( $input['trombi_display_return_link'] ) )			$clicface_trombi_settings['trombi_display_return_link'] = $input['trombi_display_return_link'];
	if( isset( $input['trombi_move_to_anchor'] ) )				$clicface_trombi_settings['trombi_move_to_anchor'] = $input['trombi_move_to_anchor'];
	if( isset( $input['trombi_default_picture'] ) )				$clicface_trombi_settings['trombi_default_picture'] = $input['trombi_default_picture'];
	if( isset( $input['trombi_collaborateurs_par_ligne'] ) )	$clicface_trombi_settings['trombi_collaborateurs_par_ligne'] = $input['trombi_collaborateurs_par_ligne'];
	if( isset( $input['vignette_width'] ) )						$clicface_trombi_settings['vignette_width'] = $input['vignette_width'];
	if( isset( $input['trombi_title_name_singular'] ) )			$clicface_trombi_settings['trombi_title_name_singular'] = $input['trombi_title_name_singular'];
	if( isset( $input['trombi_title_name_plural'] ) )			$clicface_trombi_settings['trombi_title_name_plural'] = $input['trombi_title_name_plural'];
	if( isset( $input['trombi_thickbox_width'] ) )				$clicface_trombi_settings['trombi_thickbox_width'] = $input['trombi_thickbox_width'];
	if( isset( $input['trombi_thickbox_height'] ) )				$clicface_trombi_settings['trombi_thickbox_height'] = $input['trombi_thickbox_height'];
	if( isset( $input['vignette_color_border'] ) )				$clicface_trombi_settings['vignette_color_border'] = $input['vignette_color_border'];
	if( isset( $input['vignette_border_thickness'] ) )			$clicface_trombi_settings['vignette_border_thickness'] = $input['vignette_border_thickness'];
	if( isset( $input['vignette_border_radius'] ) )				$clicface_trombi_settings['vignette_border_radius'] = $input['vignette_border_radius'];
	if( isset( $input['vignette_color_background_top'] ) )		$clicface_trombi_settings['vignette_color_background_top'] = $input['vignette_color_background_top'];
	if( isset( $input['vignette_color_background_bottom'] ) )	$clicface_trombi_settings['vignette_color_background_bottom'] = $input['vignette_color_background_bottom'];
	if( isset( $input['vignette_ext_drop_shadow'] ) )			$clicface_trombi_settings['vignette_ext_drop_shadow'] = $input['vignette_ext_drop_shadow'];
	if( isset( $input['vignette_int_drop_shadow'] ) )			$clicface_trombi_settings['vignette_int_drop_shadow'] = $input['vignette_int_drop_shadow'];
	return $clicface_trombi_settings;
}

add_action('admin_menu', 'clicface_trombi_settings_menu');
function clicface_trombi_settings_menu() {
	add_submenu_page( 'edit.php?post_type=collaborateur', __( 'Settings', 'clicface-trombi' ), __( 'Settings', 'clicface-trombi' ), 'manage_options', 'clicface-trombi-settings', 'clicface_trombi_settings_page' );
}

function clicface_trombi_page_tabs($current = 'general') {
	$tabs = array(
		'general'	=> __( 'General', 'clicface-trombi' ),
		'fields'	=> __( 'Fields', 'clicface-trombi' ),
		'profile'	=> __( 'Profile', 'clicface-trombi' ),
		'grid'		=> __( 'Grid', 'clicface-trombi' ),
		'title'		=> __( 'Title', 'clicface-trombi' ),
		'thickbox'	=> __( 'ThickBox', 'clicface-trombi' ),
		'style'		=> __( 'Style', 'clicface-trombi' ),
	);
	$html =  '<h2 class="nav-tab-wrapper">';
	foreach( $tabs as $tab => $name ){
		$class = ($tab == $current) ? 'nav-tab-active' : '';
		$html .=  '<a class="nav-tab ' . $class . '" href="?post_type=collaborateur&page=clicface-trombi-settings&tab=' . $tab . '">' . $name . '</a>';
	}
	$html .= '</h2>';
	echo $html;
}

function clicface_trombi_settings_page() {
	wp_enqueue_script( 'media-upload' );
	wp_enqueue_script( 'thickbox' );
	wp_register_script( 'clicface-trombi-admin-settings', plugin_dir_url( __FILE__ ) . 'lib/clicface-trombi-admin-settings.js', array( 'jquery', 'media-upload', 'thickbox' ) );
	wp_enqueue_script( 'clicface-trombi-admin-settings' );
	wp_enqueue_style( 'thickbox' );
?>
	<div class="wrap">
		<h2><?php _e( 'Clicface Trombi Settings', 'clicface-trombi' ); ?></h2>
		<ol>
			<li>To see how to use Clicface Trombi, <a href="https://plugins.clicface.com/documentation/how-to-use-clicface-trombi/" target="_blank">a tutorial is available online</a></li>
			<li>Stay in touch with Clicface updates by <a href="http://eepurl.com/Oz7YH" target="_blank">subscribing to our newsletter</a>. New subscribers automatically receive discount vouchers.</li>
			<li>Need help? Check our <a href="https://plugins.clicface.com/documentation/faq/" target="_blank">FAQ</a> or <a href="http://support.clicface.com/" target="_blank">create a new support ticket</a></li>
			<li>Consider <a href="https://twitter.com/ClicfacePlugins" target="_blank">following us on Twitter</a></li>
		</ol>
		
		<?php $tab = (!empty($_GET['tab']))? esc_attr($_GET['tab']) : 'general'; ?>
		<?php clicface_trombi_page_tabs($tab); ?>
		
		<form method="post" action="options.php">
			
		<?php settings_fields('clicface_trombi_settings_group'); ?>
		<?php $clicface_trombi_settings = get_option('clicface_trombi_settings'); ?>
		
			<?php if( $tab == 'general' ): ?>
			<h2><?php _e( 'General Settings', 'clicface-trombi' ); ?></h2>
			<table class="form-table">
				<tr>
					<th scope="row"><?php _e('List display', 'clicface-trombi'); ?></th>
					<td>
						<ul class="clicface-field-list">
							<li>
								<label>
									<input type="radio" name="clicface_trombi_settings[trombi_affichage_type]" value="grid" <?php checked('grid', $clicface_trombi_settings['trombi_affichage_type']); ?> />
									<span><?php _e('Grid', 'clicface-trombi'); ?></span>
								</label>
							</li>
							<li>
								<label>
									<input type="radio" name="clicface_trombi_settings[trombi_affichage_type]" value="list" <?php checked('list', $clicface_trombi_settings['trombi_affichage_type']); ?> />
									<span><?php _e('List', 'clicface-trombi'); ?></span>
								</label>
							</li>
						</ul>
					</td>
				</tr>
			</table>
			<?php endif; ?>
			
			<?php if( $tab == 'fields' ): ?>
			<h2><?php _e( 'Field Settings on the main page', 'clicface-trombi' ); ?></h2>
			<table class="form-table">
				<tr>
					<th scope="row"><?php _e('Display Division', 'clicface-trombi'); ?></th>
					<td>
						<ul class="clicface-field-list">
							<li>
								<label>
									<input type="radio" name="clicface_trombi_settings[trombi_display_service]" value="oui" <?php checked('oui', $clicface_trombi_settings['trombi_display_service']); ?> />
									<span><?php _e('Yes', 'clicface-trombi'); ?></span>
								</label>
							</li>
							<li>
								<label>
									<input type="radio" name="clicface_trombi_settings[trombi_display_service]" value="non" <?php checked('non', $clicface_trombi_settings['trombi_display_service']); ?> />
									<span><?php _e('No', 'clicface-trombi'); ?></span>
								</label>
							</li>
						</ul>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Display Landline Number', 'clicface-trombi'); ?></th>
					<td>
						<ul class="clicface-field-list">
							<li>
								<label>
									<input type="radio" name="clicface_trombi_settings[trombi_display_phone]" value="oui" <?php checked('oui', $clicface_trombi_settings['trombi_display_phone']); ?> />
									<span><?php _e('Yes', 'clicface-trombi'); ?></span>
								</label>
							</li>
							<li>
								<label>
									<input type="radio" name="clicface_trombi_settings[trombi_display_phone]" value="non" <?php checked('non', $clicface_trombi_settings['trombi_display_phone']); ?> />
									<span><?php _e('No', 'clicface-trombi'); ?></span>
								</label>
							</li>
						</ul>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Display Mobile Number', 'clicface-trombi'); ?></th>
					<td>
						<ul class="clicface-field-list">
							<li>
								<label>
									<input type="radio" name="clicface_trombi_settings[trombi_display_cellular]" value="oui" <?php checked('oui', $clicface_trombi_settings['trombi_display_cellular']); ?> />
									<span><?php _e('Yes', 'clicface-trombi'); ?></span>
								</label>
							</li>
							<li>
								<label>
									<input type="radio" name="clicface_trombi_settings[trombi_display_cellular]" value="non" <?php checked('non', $clicface_trombi_settings['trombi_display_cellular']); ?> />
									<span><?php _e('No', 'clicface-trombi'); ?></span>
								</label>
							</li>
						</ul>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Display E-mail', 'clicface-trombi'); ?></th>
					<td>
						<ul class="clicface-field-list">
							<li>
								<label>
									<input type="radio" name="clicface_trombi_settings[trombi_display_email]" value="oui" <?php checked('oui', $clicface_trombi_settings['trombi_display_email']); ?> />
									<span><?php _e('Yes', 'clicface-trombi'); ?></span>
								</label>
							</li>
							<li>
								<label>
									<input type="radio" name="clicface_trombi_settings[trombi_display_email]" value="non" <?php checked('non', $clicface_trombi_settings['trombi_display_email']); ?> />
									<span><?php _e('No', 'clicface-trombi'); ?></span>
								</label>
							</li>
						</ul>
					</td>
				</tr>
			</table>
			<?php endif; ?>
			
			<?php if( $tab == 'profile' ): ?>
			<h2><?php _e( "Person's Profile", 'clicface-trombi' ); ?></h2>
			<table class="form-table">
				<tr>
					<th scope="row"><?php _e("Target to Person's Profile", 'clicface-trombi'); ?></th>
					<td>
						<ul class="clicface-field-list">
							<li>
								<label>
									<input type="radio" name="clicface_trombi_settings[trombi_target_window]" value="_blank" <?php checked('_blank', $clicface_trombi_settings['trombi_target_window']); ?> />
									<span><?php _e('New Window', 'clicface-trombi'); ?></span>
								</label>
							</li>
							<li>
								<label>
									<input type="radio" name="clicface_trombi_settings[trombi_target_window]" value="_self" <?php checked('_self', $clicface_trombi_settings['trombi_target_window']); ?> />
									<span><?php _e('Same Window', 'clicface-trombi'); ?></span>
								</label>
							</li>
							<li>
								<label>
									<input type="radio" name="clicface_trombi_settings[trombi_target_window]" value="thickbox" <?php checked('thickbox', $clicface_trombi_settings['trombi_target_window']); ?> />
									<span><?php _e('ThickBox', 'clicface-trombi'); ?></span>
								</label>
							</li>
						</ul>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Width type', 'clicface-trombi'); ?></th>
					<td>
						<ul class="clicface-field-list">
							<li>
								<label>
									<input type="radio" name="clicface_trombi_settings[trombi_profile_width_type]" value="max" <?php checked('max', $clicface_trombi_settings['trombi_profile_width_type']); ?> />
									<span><?php _e('100%', 'clicface-trombi'); ?></span>
								</label>
							</li>
							<li>
								<label>
									<input type="radio" name="clicface_trombi_settings[trombi_profile_width_type]" value="fixed" <?php checked('fixed', $clicface_trombi_settings['trombi_profile_width_type']); ?> />
									<span><?php _e('Fixed size', 'clicface-trombi'); ?></span>
								</label>
							</li>
						</ul>
					</td>
				</tr>
				<tr class="hidden" id="trombi_profile_width_size">
					<th scope="row"><?php _e('Width (in pixels)', 'clicface-trombi'); ?></th>
					<td>
						<input type="number" name="clicface_trombi_settings[trombi_profile_width_size]" value="<?php echo $clicface_trombi_settings['trombi_profile_width_size']; ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Height type', 'clicface-trombi'); ?></th>
					<td>
						<ul class="clicface-field-list">
							<li>
								<label>
									<input type="radio" name="clicface_trombi_settings[trombi_profile_height_type]" value="auto" <?php checked('auto', $clicface_trombi_settings['trombi_profile_height_type']); ?> />
									<span><?php _e('Auto : fit to content', 'clicface-trombi'); ?></span>
								</label>
							</li>
							<li>
								<label>
									<input type="radio" name="clicface_trombi_settings[trombi_profile_height_type]" value="fixed" <?php checked('fixed', $clicface_trombi_settings['trombi_profile_height_type']); ?> />
									<span><?php _e('Fixed size', 'clicface-trombi'); ?></span>
								</label>
							</li>
						</ul>
					</td>
				</tr>
				<tr class="hidden" id="trombi_profile_height_size">
					<th scope="row"><?php _e('Height (in pixels)', 'clicface-trombi'); ?></th>
					<td>
						<input type="number" name="clicface_trombi_settings[trombi_profile_height_size]" value="<?php echo $clicface_trombi_settings['trombi_profile_height_size']; ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Display worksite', 'clicface-trombi'); ?></th>
					<td>
						<ul class="clicface-field-list">
							<li>
								<label>
									<input type="radio" name="clicface_trombi_settings[trombi_display_worksite]" value="oui" <?php checked('oui', $clicface_trombi_settings['trombi_display_worksite']); ?> />
									<span><?php _e('Yes', 'clicface-trombi'); ?></span>
								</label>
							</li>
							<li>
								<label>
									<input type="radio" name="clicface_trombi_settings[trombi_display_worksite]" value="non" <?php checked('non', $clicface_trombi_settings['trombi_display_worksite']); ?> />
									<span><?php _e('No', 'clicface-trombi'); ?></span>
								</label>
							</li>
						</ul>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Display a link to the previous page or a link to close the new window (not applicable to ThickBox)', 'clicface-trombi'); ?></th>
					<td>
						<ul class="clicface-field-list">
							<li>
								<label>
									<input type="radio" name="clicface_trombi_settings[trombi_display_return_link]" value="oui" <?php checked('oui', $clicface_trombi_settings['trombi_display_return_link']); ?> />
									<span><?php _e('Yes', 'clicface-trombi'); ?></span>
								</label>
							</li>
							<li>
								<label>
									<input type="radio" name="clicface_trombi_settings[trombi_display_return_link]" value="non" <?php checked('non', $clicface_trombi_settings['trombi_display_return_link']); ?> />
									<span><?php _e('No', 'clicface-trombi'); ?></span>
								</label>
							</li>
						</ul>
						<span class="description"><?php _e('A link to the previous page can be displayed on each Person\'s Profile page when it is opened in the same window ; a link to close is displayed when the Person\'s Profile page is opened in a new window.', 'clicface-trombi'); ?></span>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Move the page to the content on each Person\'s Profile page (not applicable to ThickBox)', 'clicface-trombi'); ?></th>
					<td>
						<ul class="clicface-field-list">
							<li>
								<label>
									<input type="radio" name="clicface_trombi_settings[trombi_move_to_anchor]" value="oui" <?php checked('oui', $clicface_trombi_settings['trombi_move_to_anchor']); ?> />
									<span><?php _e('Yes', 'clicface-trombi'); ?></span>
								</label>
							</li>
							<li>
								<label>
									<input type="radio" name="clicface_trombi_settings[trombi_move_to_anchor]" value="non" <?php checked('non', $clicface_trombi_settings['trombi_move_to_anchor']); ?> />
									<span><?php _e('No', 'clicface-trombi'); ?></span>
								</label>
							</li>
						</ul>
						<span class="description"><?php _e('This option is useful when you have a big header on your website and you want to avoid visitors to scroll down to the content on each Person\'s Profile page.', 'clicface-trombi'); ?></span>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Default picture URL', 'clicface-trombi'); ?></th>
					<td>
						<input id="trombi_default_picture" type="text" name="clicface_trombi_settings[trombi_default_picture]" value="<?php echo $clicface_trombi_settings['trombi_default_picture'];?>" /><input class="upload_image_button" type="button" value="<?php _e('Upload image', 'clicface-trombi'); ?>" /><br />
						<span class="description"><?php _e('This picture will be displayed if no picture is provided.', 'clicface-trombi'); ?></span>
					</td>
				</tr>
			</table>
			<?php endif; ?>
			
			<?php if( $tab == 'grid' ): ?>
			<h2><?php _e('Grid Settings (for the Grid display only)', 'clicface-trombi'); ?></h2>
			<table class="form-table">
				<tr>
					<th scope="row"><?php _e('Number of people per line', 'clicface-trombi'); ?></th>
					<td>
						<input type="number" name="clicface_trombi_settings[trombi_collaborateurs_par_ligne]" value="<?php echo $clicface_trombi_settings['trombi_collaborateurs_par_ligne']; ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Width of boxes (in pixels)', 'clicface-trombi');; ?></th>
					<td>
						<input type="number" name="clicface_trombi_settings[vignette_width]" value="<?php echo $clicface_trombi_settings['vignette_width']; ?>" />
					</td>
				</tr>
			</table>
			<?php endif; ?>
			
			<?php if( $tab == 'title' ): ?>
			<h2><?php _e('Title', 'clicface-trombi'); ?></h2>
			<table class="form-table">
				<tr>
					<th scope="row"><?php _e('Title name (singular)', 'clicface-trombi'); ?></th>
					<td>
						<input type="text" name="clicface_trombi_settings[trombi_title_name_singular]" value="<?php echo $clicface_trombi_settings['trombi_title_name_singular']; ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Title name (plural)', 'clicface-trombi'); ?></th>
					<td>
						<input type="text" name="clicface_trombi_settings[trombi_title_name_plural]" value="<?php echo $clicface_trombi_settings['trombi_title_name_plural']; ?>" />
					</td>
				</tr>
			</table>
			<?php endif; ?>
			
			<?php if( $tab == 'thickbox' ): ?>
			<h2><?php _e('ThickBox', 'clicface-trombi'); ?></h2>
			<table class="form-table">
				<tr>
					<th scope="row"><?php _e('Width (in pixels)', 'clicface-trombi'); ?></th>
					<td>
						<input type="number" name="clicface_trombi_settings[trombi_thickbox_width]" value="<?php echo $clicface_trombi_settings['trombi_thickbox_width']; ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Height (in pixels)', 'clicface-trombi'); ?></th>
					<td>
						<input type="number" name="clicface_trombi_settings[trombi_thickbox_height]" value="<?php echo $clicface_trombi_settings['trombi_thickbox_height']; ?>" />
					</td>
				</tr>
			</table>
			<?php endif; ?>
			
			<?php if( $tab == 'style' ): ?>
			<h2><?php _e('Style', 'clicface-trombi'); ?></h2>
			<table class="form-table">
				<tr>
					<th scope="row"><?php _e('Border Color', 'clicface-trombi'); ?></th>
					<td>
						<input type="color" name="clicface_trombi_settings[vignette_color_border]" value="<?php echo $clicface_trombi_settings['vignette_color_border']; ?>" />
						<span class="description"><?php _e('Click to pick a color.', 'clicface-trombi') ?> <?php _e('Default color:', 'clicface-trombi') ?> #000000</span>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Border thickness (in pixels)', 'clicface-trombi'); ?></th>
					<td>
						<input type="number" name="clicface_trombi_settings[vignette_border_thickness]" value="<?php echo $clicface_trombi_settings['vignette_border_thickness']; ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Border radius (in pixels)', 'clicface-trombi'); ?></th>
					<td>
						<input type="number" name="clicface_trombi_settings[vignette_border_radius]" value="<?php echo $clicface_trombi_settings['vignette_border_radius']; ?>" />
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Top Background Color', 'clicface-trombi'); ?></th>
					<td>
						<input type="color" name="clicface_trombi_settings[vignette_color_background_top]" value="<?php echo $clicface_trombi_settings['vignette_color_background_top']; ?>" />
						<span class="description"><?php _e('Click to pick a color.', 'clicface-trombi') ?> <?php _e('Default color:', 'clicface-trombi') ?> #FFFFFF</span>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Bottom Background Color', 'clicface-trombi'); ?></th>
					<td>
						<input type="color" name="clicface_trombi_settings[vignette_color_background_bottom]" value="<?php echo $clicface_trombi_settings['vignette_color_background_bottom']; ?>" />
						<span class="description"><?php _e('Click to pick a color.', 'clicface-trombi') ?> <?php _e('Default color:', 'clicface-trombi') ?> #FFFFFF</span>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Display a drop shadow around the box', 'clicface-trombi'); ?></th>
					<td>
						<ul class="clicface-field-list">
							<li>
								<label>
									<input type="radio" name="clicface_trombi_settings[vignette_ext_drop_shadow]" value="oui" <?php checked('oui', $clicface_trombi_settings['vignette_ext_drop_shadow']); ?> />
									<span><?php _e('Yes', 'clicface-trombi'); ?></span>
								</label>
							</li>
							<li>
								<label>
									<input type="radio" name="clicface_trombi_settings[vignette_ext_drop_shadow]" value="non" <?php checked('non', $clicface_trombi_settings['vignette_ext_drop_shadow']); ?> />
									<span><?php _e('No', 'clicface-trombi'); ?></span>
								</label>
							</li>
						</ul>
					</td>
				</tr>
				<tr>
					<th scope="row"><?php _e('Display a drop shadow around the picture', 'clicface-trombi'); ?></th>
					<td>
						<ul class="clicface-field-list">
							<li>
								<label>
									<input type="radio" name="clicface_trombi_settings[vignette_int_drop_shadow]" value="oui" <?php checked('oui', $clicface_trombi_settings['vignette_int_drop_shadow']); ?> />
									<span><?php _e('Yes', 'clicface-trombi'); ?></span>
								</label>
							</li>
							<li>
								<label>
									<input type="radio" name="clicface_trombi_settings[vignette_int_drop_shadow]" value="non" <?php checked('non', $clicface_trombi_settings['vignette_int_drop_shadow']); ?> />
									<span><?php _e('No', 'clicface-trombi'); ?></span>
								</label>
							</li>
						</ul>
					</td>
				</tr>
			</table>
			<?php endif; ?>
			
			<?php submit_button(); ?>
		</form>
	</div>
<?php
}