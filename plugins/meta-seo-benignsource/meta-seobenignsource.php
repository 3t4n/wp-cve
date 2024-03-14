<?php
/**
 * @package Meta SEO BenignSource
 * @version 3.0
 */
/*
Plugin Name: Meta SEO BenignSource Free Version
Plugin URI: http://www.benignsource.com
Description: Optimize your website as outputs, custom title, meta description and meta keywords in the element head.
Author: BenignSource
Version: 3.0
*/
class MetaSEOBenignSourceOne {
	var $default = array(
		'includes_taxonomies'		=> array(),
		'excerpt_as_description'	=> true,
		'include_term'				=> true,
	);
	
	var $setting;
	var $msbs_ckeywords;
	var $msbs_cdescription;

public function __construct() {
	if ( is_admin() ) {
		add_action( 'add_meta_boxes'					, array( &$this, 'add_post_meta_box' ), 10, 2 );
		add_action( 'wp_insert_post'					, array( &$this, 'update_post_meta' ) );
		add_action( 'admin_menu'						, array( &$this, 'add_setting_menu' ) );
		add_action( 'admin_print_styles-settings_page_meta-seo', array( &$this, 'print_icon_style' ) );
		add_action( 'plugins_loaded'					, array( &$this, 'update_settings' ) );
		add_filter( 'plugin_action_links'				, array( &$this, 'plugin_action_links' ), 10, 2 );
		add_action( 'admin_print_styles-post.php'		, array( &$this, 'print_metabox_styles' ) );
		add_action( 'admin_print_styles-post-new.php'	, array( &$this, 'print_metabox_styles' ) );
		register_deactivation_hook( __FILE__ , array( &$this, 'deactivation' ) );
	}

	add_action( 'wp_loaded',	array( &$this, 'taxonomy_update_hooks' ), 9999 );
	add_action( 'wp_head',		array( &$this, 'output_meta' ), 0 );

	$this->msbs_ckeywords = get_option( 'msbs_ckeywords' );
	$this->msbs_cdescription = get_option( 'msbs_cdescription' );
	if ( ! $this->setting = get_option( 'msbs_seo_settings' ) ) {
		$this->setting = $this->default;
	}
	
}

public function taxonomy_update_hooks() {
	$taxonomies = get_taxonomies( array( 'public' => true, 'show_ui' => true ) );
	if ( ! empty( $taxonomies ) ) {
		foreach ( $taxonomies as $taxonomy ) {
			add_action( $taxonomy . '_add_form_fields', array( $this, 'add_keywords_form' ) );
			add_action( $taxonomy . '_edit_form_fields', array( &$this, 'edit_keywords_form' ), 0, 2 );
			add_action( 'created_' . $taxonomy, array( &$this, 'update_term_meta' ) );
			add_action( 'edited_' . $taxonomy, array( &$this, 'update_term_meta' ) );
			add_action( 'delete_' . $taxonomy, array( &$this, 'delete_term_meta' ) );
		}
	}
}

public function plugin_action_links( $links, $file ) {
	$status = get_query_var( 'status' ) ? get_query_var( 'status' ) : 'all';
	$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;
	$s = get_query_var( 's' );
	$this_plugin = plugin_basename(__FILE__);
	if ( $file == $this_plugin ) {
		$link = trailingslashit( get_bloginfo( 'wpurl' ) ) . 'wp-admin/options-general.php?page=meta-seobenignsource.php';
		$tax_regist_link = '<a href="' . $link . '">' . __( 'Settings' ) . '</a>';
		array_unshift( $links, $tax_regist_link ); // before other links
		$link = wp_nonce_url( 'plugins.php?action=deactivate&amp;plugin=' . $this_plugin . '&amp;plugin_status=' . $status . '&amp;paged=' . $paged . '&amp;deloption=1&amp;s=' . $s, 'deactivate-plugin_' . $this_plugin );
		$del_setting_deactivation_link = '<a href="' . $link . '">Remove the setting</a>';
		array_push( $links, $del_setting_deactivation_link );
	}
	return $links;
}
public function deactivation() {
	if ( isset( $_GET['deloption'] ) && $_GET['deloption'] ) {
		delete_option( 'msbs_keywords' );
		delete_option( 'msbs_description' );
		delete_option( 'msbs_seo_settings' );
		delete_post_meta_by_key( '_msbs_postkeywords' );
		delete_post_meta_by_key( '_msbs_postdescription' );
	}
}


public function add_keywords_form() {
?>
<div class="form-field">
	<label for="msbs_description">Description</label>
	<textarea name="msbs_description" id="msbs_description" rows="5" cols="40"></textarea>
</div>
<div class="form-field">
	<label for="msbs_keywords">Keywords</label>
	<textarea name="msbs_keywords" id="msbs_keywords" rows="3" cols="40"></textarea>
</div>
<?php
}
public function edit_keywords_form( $tag ) {
?>
    	<tr class="form-field">
		<th scope="row" valign="top"><label for="msbs_description">Description</label></th>
		<td><textarea name="msbs_description" id="msbs_description" cols="40" rows="5"><?php echo isset( $this->msbs_cdescription[$tag->term_id] ) ? esc_attr( $this->msbs_cdescription[$tag->term_id] ) : ''; ?></textarea></td>
	</tr>
    <tr class="form-field">
		<th scope="row" valign="top"><label for="msbs_keywords">Keywords</label></th>
		<td><input type="text" name="msbs_keywords" id="msbs_keywords" size="40" value="<?php echo isset( $this->msbs_ckeywords[$tag->term_id] ) ? esc_attr( $this->msbs_ckeywords[$tag->term_id] ) : ''; ?>" /></td>
	</tr>
<?php
}
public function update_term_meta( $term_id ) {
	if ( isset( $_POST['msbs_keywords'] ) ) {
		$post_msbskeywords = sanitize_text_field( $_POST['msbs_keywords'] );
		$post_msbskeywords = $this->get_unique_keywords( $post_msbskeywords );
		if ( ! isset( $this->msbs_ckeywords[$term_id] ) || $this->msbs_ckeywords[$term_id] != $post_msbskeywords ) {
			$this->msbs_ckeywords[$term_id] = $post_msbskeywords;
			update_option( 'msbs_ckeywords', $this->msbs_ckeywords );
		}
	}

	if ( isset( $_POST['msbs_description'] ) ) {
		$post_msbsdescription = sanitize_text_field( $_POST['msbs_description'] );
		if ( ! isset( $this->msbs_cdescription[$term_id] ) || $this->msbs_cdescription[$term_id] != $post_msbsdescription ) {
			$this->msbs_cdescription[$term_id] = $post_msbsdescription;
			update_option( 'msbs_cdescription', $this->msbs_cdescription );
		}
	}
}

public function delete_term_meta( $term_id ) {
	if ( isset( $this->msbs_ckeywords[$term_id] ) ) {
		unset( $this->msbs_ckeywords[$term_id] );
		update_option( 'msbs_ckeywords', $this->msbs_ckeywords );
	}
	if ( isset( $this->msbs_cdescription[$term_id] ) ) {
		unset( $this->msbs_cdescription[$term_id] );
		update_option( 'msbs_cdescription', $this->msbs_cdescription );
	}
	
}

public function add_post_meta_box( $post_type, $post ) {
	$post_type_obj = get_post_type_object( $post_type );
	$output_flag = apply_filters( 'meta_seo_meta_box_display', true, $post_type, $post );
	if ( $post_type_obj && $post_type_obj->public && $output_flag ) {
		add_meta_box( 'post_meta_box', 'Meta', array( &$this, 'post_meta_box' ), $post_type, 'normal', 'high');
	}
}

public function post_meta_box() {
	global $post;
	$post_msbskeywords = get_post_meta( $post->ID, '_msbs_postkeywords', true ) ? get_post_meta( $post->ID, '_msbs_postkeywords', true ) : '';
	$post_msbsdescription = get_post_meta( $post->ID, '_msbs_postdescription', true ) ? get_post_meta( $post->ID, '_msbs_postdescription', true ) : '';
?>
<div>
<div style="padding:10px; margin-bottom:20px; padding-right:5px; width:99%;border-bottom: 4px #e96656 solid; background-color: #433a3b; color:#FFFFFF; float:left;">Meta SEO BenignSource</div>
<?php
$needle = array( 'about' => 'about', 'above' => 'above', 'after' => 'after', 'again' => 'again', 'against' => 'against', 'all' => 'all', 'and' => 'and','any' => 'any','are' => 'are','aren\'t' => 'aren\'t','because' => 'because','been' => 'been','before' => 'before','being' => 'being','below' => 'below','between' => 'between','both' => 'both','but' => 'but','can\'t' => 'can\'t','cannot' => 'cannot','could' => 'could','couldn\'t' => 'couldn\'t','did' => 'did','didn\'t' => 'didn\'t','does' => 'does','doesn\'t' => 'doesn\'t','doesn\'t' => 'doesn\'t','don\'t' => 'don\'t','down' => 'down','during' => 'during','each' => 'each','few' => 'few','for' => 'for','from' => 'from','further' => 'further','had' => 'had','hadn\'t' => 'hadn\'t','has' => 'has','hasn\'t' => 'hasn\'t','have' => 'have','haven\'t' => 'haven\'t','having' => 'having','he' => 'he','he\'d' => 'he\'d','he\'ll' => 'he\'ll','he\'s' => 'he\'s','her' => 'her','here' => 'here','here\'s' => 'here\'s','hers' => 'hers','herself' => 'herself','him' => 'him','himself' => 'himself','his' => 'his','how' => 'how','how\'s' => 'how\'s','i\'d' => 'i\'d','i\'ll' => 'i\'ll','i\'m' => 'i\'m','i\'ve' => 'i\'ve','into' => 'into','isn\'t' => 'isn\'t','it\'s' => 'it\'s','its' => 'its','itself' => 'itself','let\'s' => 'let\'s','more' => 'more','most' => 'most','mustn\'t' => 'mustn\'t','myself' => 'myself','nor' => 'nor','not' => 'not','off' => 'off','once' => 'once','only' => 'only','other' => 'other','ought' => 'ought','our' => 'our','ours' => 'ours','ourselves' => 'ourselves','out' => 'out','over' => 'over','own' => 'own','same' => 'same','shan\'t' => 'shan\'t','she' => 'she','she\'d' => 'she\'d','she\'ll' => 'she\'ll','she\'s' => 'she\'s','should' => 'should','shouldn\'t' => 'shouldn\'t','some' => 'some','such' => 'such','than' => 'than','that' => 'that','that\'s' => 'that\'s','the' => 'the','their' => 'their','theirs' => 'theirs','them' => 'them','themselves' => 'themselves','then' => 'then','there' => 'there','there\'s' => 'there\'s','these' => 'these','they' => 'they','they\'d' => 'they\'d','they\'ll' => 'they\'ll','they\'re' => 'they\'re','they\'ve' => 'they\'ve','this' => 'this','those' => 'those','through' => 'through','too' => 'too','under' => 'under','until' => 'until','very' => 'very','was' => 'was','wasn\'t' => 'wasn\'t','we' => 'we','we\'d' => 'we\'d','we\'ll' => 'we\'ll','we\'re' => 'we\'re','we\'ve' => 'we\'ve','were' => 'were','weren\'t' => 'weren\'t','what' => 'what','what\'s' => 'what\'s','when' => 'when','when\'s' => 'when\'s','where' => 'where','where\'s' => 'where\'s','which' => 'which','while' => 'while','who' => 'who','who\'s' => 'who\'s','whom' => 'whom','why' => 'why','why\'s' => 'why\'s','with' => 'with','won\'t' => 'won\'t','would' => 'would','wouldn\'t' => 'wouldn\'t','you' => 'you','you\'d' => 'you\'d','you\'ll' => 'you\'ll','you\'re' => 'you\'re','you\'ve' => 'you\'ve','your' => 'your','yours' => 'yours','yourself' => 'yourself','yourselves' => 'yourselves');
$matchFoundDesc = preg_match_all("/\b(" . implode($needle,"|") . ")\b/i", $post_msbsdescription, $matches);
$matchFoundKey = preg_match_all("/\b(" . implode($needle,"|") . ")\b/i", $post_msbskeywords, $matches);
?>
<div style="width:100%; float:left;">
<div style="width:100%; float:left;font-size:16px; margin-bottom:15px;"><h2 style="border-bottom: 4px #e96656 solid;font-size:16px; width:20%;">Custom Title</h2></div>
<div style="width:100%; float:left;"><div style="width:70%; float:left;">Only Premium Version <a href="http://www.benignsource.com/product/meta-seo-benignsource-for-woocommerce-wordpress/"  target="_blank">Upgrade</a></div>
<div style="color:#FF0000; width:100%; float:left;"></div></div></div>

<div style="width:100%; float:left; margin-top:20px;">
<?php
if ($matchFoundDesc) {
?>
<div style="width:40%; float:left; font-size:16px;margin-bottom:15px;"><h2 style="border-bottom: 4px #e96656 solid;font-size:16px; width:50%;">Description</h2></div>
<?php } else{?>
<div style="width:40%; float:left; font-size:16px;margin-bottom:15px;"><h2 style="border-bottom: 4px #00CC00 solid;font-size:16px; width:50%;">Description</h2></div>
<?php }?>
<div style="font-size:14px; width:10%;padding:10px; float:left; color:#666666;">Characters Left</div><div style="font-size:12px;padding:10px; width:20%; float:left; color:#FF0000; font-weight:bold;">Premium</div>
<div style="width:80%; float:left;"><textarea name="_msbs_postdescription" id="post_msbsdescription" cols="100" style="width:70%;"  rows="3" onkeyup="countCharD(this)"><?php esc_attr_e( $post_msbsdescription ); ?></textarea></div>
<div style="color:#FF0000; width:100%; float:left;"><i>Keeping your description between 130 and 150 characters</i></div>
<div style="width:70%; float:left;">
<?php
if ($matchFoundDesc) { ?>
    <div style="font-size:14px; padding:10px; width:50%; float:left;">
    '<div style="width:7%;font-size:16px; float:left;color:#FF0000;"><b><?php esc_html_e($matchFoundDesc);?></b></div>
    <div style="width:50%; float:left;">Stopwords has been found</div></div>
	<?php
} else { ?>
    <div  style="font-size:14px; width:50%; padding:10px; float:left;">There is no Stopwords in your Description</div>
    <?php
}
if ($matchFoundDesc) { ?>
<div style="font-size:16px; padding:10px; width:70%; float:left;">
<?php
esc_html_e('Stopwords: ');
foreach ($needle as $descword) {
    if (stristr($post_msbsdescription, $descword) !== FALSE) {
    print "<b>$descword</b>,\n";
	 }
   } ?>
</div>
<?php
  } else {
}
?></div>
</div>
<div style="width:100%; float:left; margin-top:20px; margin-bottom:30px;">
<?php
if ($matchFoundKey) {
?>
<div style="width:100%; float:left;font-size:16px;margin-bottom:15px;"><h2 style="border-bottom: 4px #e96656 solid;font-size:16px; width:20%;">Keywords</h2></div>
<?php } else{?>
<div style="width:100%; float:left;font-size:16px;margin-bottom:15px;"><h2 style="border-bottom: 4px #00CC00 solid;font-size:16px; width:20%;">Keywords</h2></div>
<?php }?>
<div style="width:70%; float:left;"><input type="text" name="_msbs_postkeywords" id="post_msbskeywords" size="100%" value="<?php esc_attr_e( $post_msbskeywords ); ?>" /></div>
<div style="color:#FF0000; width:100%; float:left;"><i>We suggest you include a minimum of 7 keywords</i></div>
<div style="width:70%; float:left;">
<?php
if ($matchFoundKey) { ?>
    <div style="font-size:14px; padding:10px; width:50%; float:left;">
    <div style="width:7%;font-size:16px; float:left;color:#FF0000;"><b><?php esc_html_e($matchFoundKey);?></b></div>
    <div style="width:50%; float:left;">Stopwords has been found</div></div>
    <?php
} else { ?>
    <div  style="font-size:14px; width:50%; padding:10px; float:left;">There is no Stopwords in your Keywords</div>
    <?php
}
if ($matchFoundKey) { ?>
<div style="font-size:16px; padding:10px; width:70%; float:left;">
<?php
esc_html_e('Stopwords: ');
foreach ($needle as $keyword) {
    if (stristr($post_msbskeywords, $keyword) !== FALSE) {
        
		print "<b>$keyword</b>,\n";
		}
    } ?>
	</div>
    <?php
  } else {
}
?></div></div>
<div style="width:98%; margin-bottom:20px;">The example listing below is what this web page may look like in search results.</div>
<div style="width:60%; margin-bottom:20px; padding:5px; border:1px #999999 solid;">
<div style="width:98%; font-size:16px; "><a href="">Only Premium Version</a></div>
<div style="width:98%; "><a href="" style="text-decoration:none;"><?php echo get_site_url(); ?></a></div>
<div style="width:90%; "><?php esc_attr_e( $post_msbsdescription ); ?></div>
</div>
<div style="width:98%;">This is Free version 2.0 more info and support visit our website <a href="http://www.benignsource.com" target="_blank" title="benignsource.com">benignsource.com</a> or <a href="http://www.wordpress.org" target="_blank" title="WordPress.org">WordPress.org</a> Upgrade to Premium Version <a href="http://www.benignsource.com/product/meta-seo-benignsource-for-woocommerce-wordpress/" target="_blank" title="BenignSource">BenignSource</a></div></div>
<?php
}
public function print_metabox_styles() {
?>
<style type="text/css" charset="utf-8">
#msbs_keywords,
#msbs_description {
	width: 98%;
}
</style>
<?php
}
public function update_post_meta( $post_ID ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	if ( isset( $_POST['_msbs_postkeywords'] ) ) {
		$post_msbskeywords = sanitize_text_field( $_POST['_msbs_postkeywords'] );
		$post_msbskeywords = $this->get_unique_keywords( $post_msbskeywords );
		update_post_meta( $post_ID, '_msbs_postkeywords', $post_msbskeywords );
	}
	if ( isset( $_POST['_msbs_postdescription'] ) ) {
		$post_msbskeywords = sanitize_text_field( $_POST['_msbs_postdescription'] );
		update_post_meta( $post_ID, '_msbs_postdescription', $post_msbskeywords );
	}
}
public function output_meta() {
$meta = $this->get_meta();

if ( $meta['msbsdescription'] ) {?>
<meta name="description" content="<?php  esc_html_e( $meta['msbsdescription'] );?>" /><?php esc_html_e("\n");
}
if ( $meta['msbskeywords'] ) {?>
<meta name="keywords" content="<?php esc_html_e( $meta['msbskeywords'] );?>" /><?php esc_html_e("\n");
}
$msbsincludeauthoron = get_option('msbs_include_author');
if ($msbsincludeauthoron < 1 ){
} else{?>
<meta name="author" content="<?php esc_html_e( $meta['msbsblogname'] );?>"/><?php esc_html_e("\n");
 }
if ( $meta['msbskeywords'] ) {?>
<!-- Powered by Meta SEO BenignSource --><?php esc_html_e("\n");
	}
	
}
private function get_meta() {
	$meta = array();
	$option = array();
	$meta['msbskeywords'] = get_option( 'msbs_keywords' ) ? get_option( 'msbs_keywords' ) : '';
	$meta['msbsdescription'] = get_option( 'msbs_description' ) ? get_option( 'msbs_description' ) : '';
	$meta['msbsblogname'] = get_bloginfo('name');
	$meta['msbsincludeauthor'] = get_option( 'msbs_include_author' ) ? get_option( 'msbs_include_author' ) : '';
	if ( is_singular() ) {
		$option = $this->get_post_meta();
	} elseif ( is_tax() || is_category() || is_tag() ) {
		$option = $this->get_term_meta();
	}

	if ( ! empty( $option ) && $option['msbskeywords'] ) {
		$meta['msbskeywords'] = $this->get_unique_keywords( $option['msbskeywords']);
	} else {
		$meta['msbskeywords'] = $this->get_unique_keywords( $meta['msbskeywords'] );
	}
	
	if ( ! empty( $option ) && $option['msbsdescription'] ) {
		$meta['msbsdescription'] = $option['msbsdescription'];
	}
	$meta['msbsdescription'] = mb_substr( $meta['msbsdescription'], 0, 1000, 'UTF-8' );
	return $meta;
}
private function get_post_meta() {
	global $post;
	$post_meta = array();
	$post_meta['msbskeywords'] = get_post_meta( $post->ID, '_msbs_postkeywords', true ) ? get_post_meta( $post->ID, '_msbs_postkeywords', true ) : '';
	if ( ! empty( $this->setting['includes_taxonomies'] ) ) {
		foreach ( $this->setting['includes_taxonomies'] as $taxonomy ) {
			$taxonomy = get_taxonomy( $taxonomy );
			if ( in_array( $post->post_type, $taxonomy->object_type ) ) {
				$terms = get_the_terms( $post->ID, $taxonomy->name );
				if ( $terms ) {
					$add_keywords = array();
					foreach ( $terms as $term ) {
						$add_keywords[] = $term->name;
					}
					$add_keywords = implode( ',', $add_keywords );
					if ( $post_meta['msbskeywords'] ) {
						$post_meta['msbskeywords'] .= ',' . $add_keywords;
					} else {
						$post_meta['msbskeywords'] = $add_keywords;
					}
				}
			}
		}
	}
	$post_meta['msbsdescription'] = get_post_meta( $post->ID, '_msbs_postdescription', true ) ? get_post_meta( $post->ID, '_msbs_postdescription', true ) : '';
	if ( $this->setting['excerpt_as_description'] && ! $post_meta['msbsdescription'] ) {
		if ( trim( $post->post_excerpt ) ) {
			$post_meta['msbsdescription'] = $post->post_excerpt;
		} else {
			$excerpt = apply_filters( 'the_content', $post->post_content );
			$excerpt = strip_shortcodes( $excerpt );
			$excerpt = str_replace( ']]>', ']]&gt;', $excerpt );
			$excerpt = strip_tags( $excerpt );
			$post_meta['msbsdescription'] = trim( preg_replace( '/[\n\r\t ]+/', ' ', $excerpt), ' ' );
		}
	}
	return $post_meta;
}
private function get_term_meta() {
	$term_meta = array();
	if ( is_tax() ) {
		$taxonomy = get_query_var( 'taxonomy' );
		$slug = get_query_var( 'term' );
		$term = get_term_by( 'slug', $slug, $taxonomy );
		$term_id = $term->term_id;
	} elseif ( is_category() ) {
		$term_id = get_query_var( 'cat' );
		$term = get_category( $term_id );
	} elseif ( is_tag() ) {
		$slug = get_query_var( 'tag' );
		$term = get_term_by( 'slug', $slug, 'post_tag' );
		$term_id = $term->term_id;
	}
	$term_meta['msbskeywords'] = isset( $this->msbs_ckeywords[$term_id] ) ? $this->msbs_ckeywords[$term_id] : '';
	if ( $this->setting['include_term'] ) {
		$term_meta['msbskeywords'] = $term->name . ',' . $term_meta['msbskeywords'];
	}
	$term_meta['msbsdescription'] = isset( $this->msbs_cdescription[$term_id] ) ? $this->msbs_cdescription[$term_id] : '';
	return $term_meta;
}
private function get_unique_keywords() {
	$args = func_get_args();
	$msbskeywords = array();
	if ( ! empty( $args ) ) {
		foreach ( $args as $arg ) {
			if ( is_string( $arg ) ) {
				$msbskeywords[] = trim( $arg, ', ' );
			}
		}
		$msbskeywords = implode( ',', $msbskeywords );
		$msbskeywords = preg_replace( '/[, ]*,[, ]*/', ',', $msbskeywords );
		$msbskeywords = explode( ',', $msbskeywords );
		foreach ( $msbskeywords as $key => $keyword ) {
			if ( ! $keyword ) {
				unset( $msbskeywords[$key] );
			}
		}
		$msbskeywords = array_map( 'trim', $msbskeywords );
		$msbskeywords = array_unique( $msbskeywords );
	}
	$msbskeywords = implode( ',', $msbskeywords );
	return $msbskeywords;
}
public function add_setting_menu() {
	add_options_page( 'Meta SEO BenignSource', 'Meta SEO BenignSource', 'manage_options', basename( __FILE__ ), array( &$this, 'setting_page' ) );
}


public function setting_page() {
	$msbs_keywords = get_option( 'msbs_keywords' ) ? get_option( 'msbs_keywords' ) : '';
	$msbs_description = get_option( 'msbs_description' ) ? get_option( 'msbs_description' ) : '';
	$msbs_include_author = get_option( 'msbs_include_author' ) ? get_option( 'msbs_include_author' ) : '';
	$taxonomies = get_taxonomies( array( 'public' => true, 'show_ui' => true ), false );
?>
<div class="wrap">
<form action="" method="post">
<?php wp_nonce_field( 'msbs_seobs' ); ?>
<div class="postbox" style="padding:30px; width:65%;">
<?php

$msbshomecheck = array( 'about', 'About', 'above', 'Above', 'after', 'After', 'again', 'Again', 'against', 'Against', 'all', 'All', 'and', 'And','any', 'Any','are', 'Are','aren\'t', 'Aren\'t', 'because', 'Because','been', 'Been','before', 'Before','being', 'Being','below', 'Below','between', 'Between','both', 'Both','but', 'But','can\'t', 'Can\'t','cannot', 'Cannot','could', 'Could','couldn\'t', 'Couldn\'t','did', 'Did','didn\'t', 'Didn\'t','does', 'Does','doesn\'t', 'Doesn\'t','doesn\'t', 'Doesn\'t','don\'t', 'Don\'t','down', 'Down','during', 'During','each', 'Each','few', 'Few','for', 'For','from', 'From','further', 'Further','had', 'Had','hadn\'t', 'Hadn\'t','has', 'Has','hasn\'t', 'Hasn\'t','have', 'Have','haven\'t', 'Haven\'t','having', 'Having','he\'d', 'He\'d','he\'ll', 'He\'ll','he\'s', 'He\'s','her', 'Her','here', 'Here','here\'s', 'Here\'s','hers', 'Hers','herself', 'Herself','him', 'Him','himself', 'Himself','his', 'His','how', 'How','how\'s', 'How\'s','i\'d', 'I\'d','i\'ll', 'I\'ll','i\'m', 'I\'m','i\'ve', 'I\'ve','into', 'Into','isn\'t', 'Isn\'t','it', 'It','it\'s', 'It\'s','its', 'Its','itself', 'Itself','let\'s', 'Let\'s','more', 'More','most', 'Most','mustn\'t', 'Mustn\'t','myself', 'Myself','nor', 'Nor','not', 'Not','off', 'Off','once', 'Once','only', 'Only','other', 'Other','ought', 'Ought','our', 'Our','ours', 'Ours','ourselves', 'Ourselves','out', 'Out','over', 'Over','own', 'Own','same', 'Same','shan\'t', 'Shan\'t','she', 'She','she\'d', 'She\'d','she\'ll', 'She\'ll','she\'s', 'She\'s','should', 'Should','shouldn\'t', 'Shouldn\'t','some', 'Some','such', 'Such','than', 'Than','that', 'That','that\'s', 'That\'s','the', 'The','their', 'Their','theirs', 'Theirs','them', 'Them','themselves', 'Themselves','then', 'Then','there', 'There','there\'s', 'There\'s','these', 'These','they', 'They','they\'d', 'They\'d','they\'ll', 'They\'ll','they\'re', 'They\'re','they\'ve', 'They\'ve','this', 'This','those', 'Those','through', 'Through','too', 'Too','under', 'Under','until', 'Until','very', 'Very','was', 'Was','wasn\'t', 'Wasn\'t','we\'d', 'We\'d','we\'ll', 'We\'ll','we\'re', 'We\'re','we\'ve', 'We\'ve','were', 'Were','weren\'t', 'Weren\'t','what', 'What','what\'s', 'What\'s','when', 'When','when\'s', 'When\'s','where', 'Where','where\'s', 'Where\'s','which', 'Which','while', 'While','who', 'Who','who\'s', 'Who\'s','whom', 'Whom','why', 'Why','why\'s', 'Why\'s','with', 'With','won\'t', 'Won\'t','would', 'Would','wouldn\'t', 'Wouldn\'t','you', 'You','you\'d', 'You\'d','you\'ll', 'You\'ll','you\'re', 'You\'re','you\'ve', 'You\'ve','your', 'Your','yours', 'Yours','yourself', 'Yourself','yourselves', 'Yourselves');

$matchFoundHomeDesc = preg_match_all("/\b(" . implode($msbshomecheck,"|") . ")\b/i", $msbs_description, $matches);
$matchFoundHomeKey = preg_match_all("/\b(" . implode($msbshomecheck,"|") . ")\b/i", $msbs_keywords, $matches);
?>
<div style="padding:10px; width:100%;background-color: #433a3b; color:#FFFFFF; float:left; margin-bottom:15px;">
<div style=" width:200px;color:#FFFFFF; padding:10px; font-size:18px; float:left;">Meta SEO Settings</div>
<div style="float:right; width:300px;"><?php echo '<img src="' . esc_attr( plugins_url( 'logo_metaseo.png', __FILE__ ) ) . '" alt="Meta SEO BenignSource" border="0px"> ';?></div></div>
<div style="width:100%; padding:10px;">This description will appear on the home page if you using only WordPress if you have Shop WooCommerce no need to insert information here go to Pages / Shop edit / and enter there information.</div></div>

<div class="postbox" style="padding:30px; padding-top:10px; width:65%; float:left; text-align:left;">
<div style="padding:10px; padding-right:5px; width:100%;border-bottom: 4px #e96656 solid; background-color: #433a3b; color:#FFFFFF; float:left;">Description Home Page</div>
<div style=" margin-top:20px; width:100%; float:left;">

<h2 style="border-bottom: 4px #00CC00 solid; width:20%;">Title</h2>

<div style="padding:10px; padding-left:0px;">
<label for="msbs_title">
Only Premium Version <a href="http://www.benignsource.com/product/meta-seo-benignsource-for-woocommerce-wordpress/"  target="_blank">Upgrade</a>
</label>
</div>
<div style="width:70%; float:left;"><i>We recommend your title be between 40 and 60 characters</i></div>
<div style="width:70%; float:left;">
</div>
</div>
<div style=" width:100%; float:left;">
<?php
if ($matchFoundHomeDesc) {
?>
<h2 style="border-bottom: 4px #e96656 solid; width:20%;">Description</h2>
<?php } else{?>
<h2 style="border-bottom: 4px #00CC00 solid; width:20%;">Description</h2>
<?php }?>
<div style="padding:10px; padding-left:0px;">
<label for="msbs_description">
<textarea name="msbs_description" id="msbs_description" cols="100" rows="3"><?php esc_attr_e( $msbs_description ); ?></textarea>
</label>
</div>
<div style="width:70%; float:left;"><i>Keeping your description between 130 and 150 characters</i></div>
<div style="width:70%; float:left;">
<?php
if ($matchFoundHomeDesc) { ?>
    <div style="font-size:14px; padding:10px; width:50%; float:left;">
    <div style="width:7%;font-size:16px; float:left;color:#FF0000;border-radius:50%;"><b><?php esc_html_e($matchFoundHomeDesc);?></b></div>
    <div style="width:50%; float:left;">Stopwords has been found</div></div>
    <?php
} else { ?>
    <div  style="font-size:14px; width:50%; padding:10px; float:left;">There is no Stopwords in your Description</div>
    <?php
}
if ($matchFoundHomeDesc) { ?>
<div style="font-size:16px; padding:10px; width:70%; float:left;">
<?php
esc_html_e('Stopwords: ');
foreach ($msbshomecheck as $deschome) {
  
    if (strpos($msbs_description, $deschome) !== FALSE) {
    print "<b>$deschome</b>,\n";
    }
   } ?>
   </div>
   <?php
  } else {
}
?></div>
</div>
<div style="width:100%; float:left;">
<?php
if ($matchFoundHomeKey) {
?>
<h2 style="border-bottom: 4px #e96656 solid; width:20%;">Keywords</h2>
<?php } else{?>
<h2 style="border-bottom: 4px #00CC00 solid; width:20%;">Keywords</h2>
<?php }?>
<div style="padding:10px; padding-left:0px;">
<label for="msbs_keywords">
<input type="text" name="msbs_keywords" id="msbs_keywords" size="100" value="<?php esc_attr_e( $msbs_keywords ); ?>" />
</label>
</div>
<div style="width:70%; float:left;"><i>We suggest you include a minimum of 7 keywords</i></div>
<div style="width:70%; float:left;">
<?php
if ($matchFoundHomeKey) { ?>
    <div style="font-size:14px; padding:10px; width:50%; float:left;">
    <div style="width:7%;font-size:16px; float:left;color:#FF0000;border-radius:50%;"><b><?php esc_html_e($matchFoundHomeKey);?></b></div>
    <div style="width:50%; float:left;">Stopwords has been found</div></div>
    <?php
} else { ?>
    <div  style="font-size:14px; width:50%; padding:10px; float:left;">There is no Stopwords in your Keywords</div>
    <?php
}
if ($matchFoundHomeKey) { ?>
<div style="font-size:16px; padding:10px; width:70%; float:left;">
<?php
esc_html_e('Stopwords: ');
foreach ($msbshomecheck as $keyhome) {
    if (strpos($msbs_keywords, $keyhome) !== FALSE) {
    print "<b>$keyhome</b>,\n";
    }
   }?>
   </div>
   <?php
  } else {
}
?></div>
</div>
<div style="padding:10px; margin-top:20px; margin-bottom:20px; padding-right:5px; width:100%;border-bottom: 4px #e96656 solid; background-color: #433a3b; color:#FFFFFF; float:left;">Setting functionality & extras</div>
<style type="text/css" >
.int{ 
background-color: #F1F2F7;
border:2px #666666 solid;
}
.check{
    content: "\2717";
    font-size: 24px;
    -webkit-font-smoothing: antialiased;
    text-align: center;
    color: #fff;
    display: inline-block;
    width: 26px;
    height: 26px;
	
   
    background: #C9D6E2;
    box-shadow: inset 0 1px 2px rgba(0,0,0,.1);
    border: 1px solid #B2BFCA;
}

[type="checkbox"]:not(:checked),
[type="checkbox"]:checked {
  position: absolute;
  left: -9999px;
}
[type="checkbox"]:not(:checked) + label,
[type="checkbox"]:checked + label {
  position: relative;
  padding-left: 75px;
  cursor: pointer;
}
[type="checkbox"]:not(:checked) + label:before,
[type="checkbox"]:checked + label:before,
[type="checkbox"]:not(:checked) + label:after,
[type="checkbox"]:checked + label:after {
  content: '';
  position: absolute;
}
[type="checkbox"]:not(:checked) + label:before,
[type="checkbox"]:checked + label:before {
  left:0; top: -3px;
  width: 65px; height: 30px;
  background: #DDDDDD;
  border-radius: 15px;
  transition: background-color .2s;
}
[type="checkbox"]:not(:checked) + label:after,
[type="checkbox"]:checked + label:after {
  width: 20px; height: 20px;
  transition: all .2s;
  border-radius: 50%;
  background: #7F8C9A;
  top: 2px; left: 5px;
}

/* on checked */
[type="checkbox"]:checked + label:before {
  background:#34495E; 
}
[type="checkbox"]:checked + label:after {
  background: #39D2B4;
  top: 2px; left: 40px;
}

[type="checkbox"]:checked + label .ui,
[type="checkbox"]:not(:checked) + label .ui:before,
[type="checkbox"]:checked + label .ui:after {
  position: absolute;
  left: 6px;
  width: 65px;
  border-radius: 15px;
  font-size: 14px;
  font-weight: bold;
  line-height: 22px;
  transition: all .2s;
}
[type="checkbox"]:not(:checked) + label .ui:before {
  content: "no";
  left: 32px
}
[type="checkbox"]:checked + label .ui:after {
  content: "yes";
  color: #39D2B4;
}
[type="checkbox"]:focus + label:before {
  border: 1px dashed #777;
  box-sizing: border-box;
  margin-top: -1px;
}

.checkdone{
    content: "\2717";
    font-size: 24px;
    -webkit-font-smoothing: antialiased;
    text-align: center;
    color: #fff;
    display: inline-block;
    width: 26px;
    height: 26px;
	
   
    background: #00CC00;
    box-shadow: inset 0 1px 2px rgba(0,0,0,.1);
    border: 1px solid #B2BFCA;
}
</style>
<div style="width:100%; float:left; margin-bottom:20px;border-bottom:1px #e96656 solid; ">
<div style="width:35%; float:left;">
<?php 
// Include Author
$msbsincludeauthoron = get_option('msbs_include_author');
if ($msbsincludeauthoron < 1 ){?>
<h2 style="border-bottom: 4px #e96656 solid; width:55%;">Author</h2>
<?php }else{?>
<h2 style="border-bottom: 4px #00CC00 solid; width:55%;">Author</h2>
<?php }?>
<div style="padding:10px; padding-left:0px;">
Author is the name of your website
</div></div>
<div style="width:40%; padding:20px; float:left;">
<label for="msbs_include_author"><strong></strong></label>
<input type="checkbox" name="msbs_include_author" id="msbs_include_author" value="1" <?php echo get_option('msbs_include_author') ? ' checked="checked"' : ''; ?>/>
<label for="msbs_include_author"><span class="ui"></span>Include Author in Meta Tag</label>
</div></div>
<div style="width:100%; float:left; margin-bottom:20px;border-bottom:1px #e96656 solid;">
<div style="width:35%; float:left;">
<h2 style="border-bottom: 4px #e96656 solid; width:55%;">Publisher</h2>
</div>
<div style="width:40%; padding:20px; float:left;">
<label for="msbs_include_publisher"><strong></strong></label>
<input type="checkbox" name="msbs_include_publisher" id="msbs_include_publisher" value="1" />
<label for="msbs_include_publisher"><span class="ui"></span>Include Publisher in Meta Tag</label>
</div>
<div style="padding:10px; padding-left:0px;width:100%; float:left;">
<label for="msbs_publisher">
Only Premium Version <a href="http://www.benignsource.com/product/meta-seo-benignsource-for-woocommerce-wordpress/"  target="_blank">Upgrade</a>
</label><p>Example: Your Google Plus Page plus.google.com</p>
</div></div>
<div style="width:100%; float:left; margin-bottom:20px;border-bottom:1px #e96656 solid;">
<div style="width:35%; float:left;">

<h2 style="border-bottom: 4px #e96656 solid; width:55%;">Google Search by Title</h2>
Only Premium Version <a href="http://www.benignsource.com/product/meta-seo-benignsource-for-woocommerce-wordpress/"  target="_blank">Upgrade</a>
</div>
<div style="width:40%; padding:20px; float:left;">
<label for="msbs_include_google"><strong></strong></label>
<input type="checkbox" name="msbs_include_google" id="msbs_include_google" value="1" />
<label for="msbs_include_google"><span class="ui"></span>Include Google in Meta Tag</label>
</div>
<div style="padding:10px; padding-left:0px; width:100%; float:left;">
<label for="msbs_google">
This setting will create a meta tag to search criteria.
</label></div>
</div>
<div style="width:100%; float:left; margin-bottom:20px;border-bottom:1px #e96656 solid;">
<div style="width:35%; float:left;">
<h2 style="border-bottom: 4px #e96656 solid; width:55%;">Alexa Script Include</h2>
Only Premium Version <a href="http://www.benignsource.com/product/meta-seo-benignsource-for-woocommerce-wordpress/"  target="_blank">Upgrade</a>
</div>
<div style="width:40%; padding:20px; float:left;">
<label for="msbs_include_alexa"><strong></strong></label>
<input type="checkbox" name="msbs_include_alexa" id="msbs_include_alexa" value="1" />
<label for="msbs_include_alexa"><span class="ui"></span>Include Alexa</label>
</div>
<div style="padding:10px; padding-left:0px; width:100%; float:left;">
<label for="msbs_alexa">
Your website will send the correct information to Alexa and this will optimize your rating.
</label></div>
</div>
</div>
<div style="padding:20px; padding-top:10px; width:65%; float:left; text-align:right;"><input type="submit" name="meta_seo_update" value="<?php _e( 'Save Changes' ); ?>" class="button-primary" /></div></form>
<div class="postbox" style="padding:30px; width:65%; float:left;">
<div style="padding:10px; padding-right:5px; width:100%;border-bottom: 4px #e96656 solid; background-color: #433a3b; color:#FFFFFF; float:left;">SEO advices</div>
        <div style="width:100%; float:left;">
        <br /><br />When you install the Plugins, open the page source and look if you see quite no need informacia remove it will not help you hurt SEO optimization.
        <br /><br /> The same goes for someone SEO plugins put prohibiting command to search engine spiders.
        <br /><br />
        Examples:
        <br /><br />
        meta name="robots" content="noindex, nofollow" "The spider will now index your whole website."<br />
        meta name="robots" content="noodp" "this Meta Tag say to the search engine not to index this page." 
        </div><div style="width:100%; float:left;"><br />
        Be careful what you are installing on your website keep code orderly and clean loading speed depends from this and Google loves stacked pages.
        </div><div style="width:100%; float:left;"><br />
        Test your speed : <a href="https://developers.google.com/speed/pagespeed/insights/" target="_blank">Analyze your site performance</a>
        </div>
<div style="padding:10px; margin-top:30px; padding-right:5px; width:100%;border-bottom: 4px #e96656 solid; background-color: #433a3b; color:#FFFFFF; float:left;">
Latest from BenignSource</div>
<div style="width:100%; float:left;"><a href="http://www.benignsource.com/product/protect-benignsource/" target="_blank" title="Protect BenignSource"><h2 style="color:#e96656; font-size:16px;">Protect BenignSource</h2></a>
<h2>Protect your WordPress from being loaded in another website or being copied by WEBSITE COPIER Tools!</h2>
</div>
<div style="width:70%; float:left;"><a href="http://www.benignsource.com/product/woo-product-design-benignsource/" target="_blank" title="Optimize your WordPress"><h2 style="color:#e96656; font-size:16px;">Woo Product Design BenignSource</h2></a>
<h2>Woo Product Design make your products with different designs and style!</h2>
</div>
<div style="width:70%; float:left;"><a href="http://www.benignsource.com/product/loyal-customer-benignsource/" target="_blank" title="osCommerce"><h2 style="color:#e96656; font-size:16px;">Loyal Customer BenignSource</h2></a>
<h2>Create a campaign for regular customers or new ones</h2>
</div>
<div style="width:70%; float:left; margin-bottom:20px;"><h2>BenignSource <a href="http://www.benignsource.com/" target="_blank" title="BenignSource">Support Page</a> | <a href="http://www.benignsource.com/products/" target="_blank" title="Products">Products</a> | <a href="http://www.benignsource.com/#contact" target="_blank" title="Send feedback">Send feedback</a></h2></div>
<div style="width:100%; float:left; text-align:center;">Copyright &copy; 2001 - <?php printf(__('%1$s | %2$s'), date("Y"), ''); ?> <a href="http://www.benignsource.com/" target="_blank" title="BenignSource">BenignSource</a> Company, All Rights Reserved.</div>
</div>
</div>
<?php
}
public function update_settings() {
	if ( isset( $_POST['meta_seo_update'] ) ) {
		check_admin_referer( 'msbs_seobs' );
		$bs_msbsdescription = sanitize_text_field($_POST['msbs_description']);
		update_option('msbs_description', $bs_msbsdescription);
		
		$bs_msbskeywords = sanitize_text_field($_POST['msbs_keywords']);
		update_option('msbs_keywords', $bs_msbskeywords);
        
		$bs_msbsincludeauthor = sanitize_text_field(isset($_POST['msbs_include_author']));
		update_option('msbs_include_author', $bs_msbsincludeauthor);
		$message = __('Settings Saved.');
	}
  }
}
?>
<?php
/**
 * Register BenignSource menu page.
 */
if (! function_exists('bs_register_benignsource_menu_page')){
function bs_register_benignsource_menu_page() {
    add_menu_page(
        __( 'BenignSource', 'BenignSource' ),
        'BenignSource',
        'manage_options',
        'meta-seo-benignsource/plugins.php',
        '',
        plugins_url( 'meta-seo-benignsource/icon.png' ),
        6
    );
}
add_action( 'admin_menu', 'bs_register_benignsource_menu_page' ); 
}else{
}
?>
<?php
// class end
$msbs_seobs = new MetaSEOBenignSourceOne;?>