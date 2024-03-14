<?php
defined( 'ABSPATH' ) || exit; // Exit if accessed directly

if ( wp_doing_ajax() ) {
	require EOS_CARDS_DIR.'/admin/cards-ajax-admin.php';
}
if( isset( $_GET['taxonomy'] ) && $_GET['taxonomy'] === 'decks' ){
	add_action('admin_enqueue_scripts', 'eos_cards_add_cards_genres_scripts' );
}
if( ( isset( $_GET['post_type'] ) && $_GET['post_type'] === 'card' ) || ( isset( $_GET['page'] ) && $_GET['page'] === 'oracle-cards-status' ) ){
	add_action( 'admin_head','eos_cards_admin_notices',10 );
	add_action( 'in_admin_header','eos_cards_plugin_title_in_admin_header' );
}

//Update options in case of single or multisite installation.
function eos_cards_update_option( $option_key,$newvalue,$autoload = true ){
	if( !$option_key ){
		$option_key = 'eos-cards-options';
	}
	if( !is_multisite() ){
		return update_option( $option_key,$newvalue,$autoload );
	}
	else{
		return update_blog_option( get_current_blog_id(),$option_key,$newvalue );
	}
}

//Add plugin name and version in admin header
function eos_cards_plugin_title_in_admin_header(){
	?>
	<a style="text-decoration:none;color:inherit" href="<?php echo EOS_CARDS_PLUGIN_URI; ?>" rel="noopener" target="_blank">
		<div style="margin-top:16px;margin-bottom:48px;display:table">
			<img width="48px" height="48px" style="height:48px;width:48px;display:inline-block" src="<?php echo EOS_CARDS_URL.'/admin/img/oracle-cards.png'; ?>" alt="Oracle Cards logo" />
			<h3 style="display:table-cell;vertical-align:middle;padding:0 10px">Oracle Cards v<?php echo EOS_CARDS_PLUGIN_VERSION; ?></h3>
		</div>
	</a>
	<?php
}
//Manage admin notices
function eos_cards_admin_notices(){
	remove_all_actions( 'admin_notices' );
}
//It adds the script needed for the cards genres editing pages
function eos_cards_add_cards_genres_scripts(){
	wp_enqueue_media();
	wp_enqueue_script( 'admin-cards-genre',EOS_CARDS_URL.'/admin/js/cards-genres.min.js',array( 'jquery' ) );
	$params = array(
		'upload_img_header' => esc_js( __( 'Upload deck image','oracle-cards' ) ),
		'generate_from_imgs_header' => esc_js( __( 'Upload card fronts','oracle-cards' ) ),
		'button_text' => esc_js( __( 'Insert it as card front','oracle-cards' ) ),
		'button_text_back' => esc_js( __( 'Insert it as card back','oracle-cards' ) ),
		'generate_from_imgs_text' => esc_js( __( 'Generate cards','oracle-cards' ) )
	);
	wp_localize_script( 'admin-cards-genre','eos_cards_genre',$params );
}

add_action( 'admin_menu', 'eos_cards_admin_menu' );
//It loads the cards setting backend style
function eos_cards_admin_menu(){
	wp_enqueue_style( 'cards-admin-style',EOS_CARDS_URL.'/admin/css/cards-admin.min.css' );
	add_menu_page( esc_html__( 'Oracle Cards','oracle-cards' ),esc_html__( 'Oracle Cards','oracle-cards' ),'edit_others_posts','edit-tags.php?taxonomy=decks&post_type=card','','dashicons-images-alt',60 );
	add_submenu_page( 'edit-tags.php?taxonomy=decks&post_type=card',esc_html__( 'All Cards','oracle-cards' ),esc_html__( 'All Cards','oracle-cards' ),'edit_others_posts',admin_url( 'edit.php?post_type=card' ),null,10 );
	add_submenu_page( 'edit-tags.php?taxonomy=decks&post_type=card',esc_html__( 'Add Card','oracle-cards' ),esc_html__( 'Add Card','oracle-cards' ),'edit_others_posts',admin_url( 'post-new.php?post_type=card' ),null,20 );
	add_submenu_page( 'edit-tags.php?taxonomy=decks&post_type=card',esc_html__( 'Documentation','oracle-cards' ),esc_html__( 'Documentation','oracle-cards' ),'edit_posts',EOS_CARDS_PLUGIN_DOCU,null,30 );
	add_submenu_page( 'edit-tags.php?taxonomy=decks&post_type=card',esc_html__( 'Status','oracle-cards' ),esc_html__( 'Status','oracle-cards' ),'edit_posts',admin_url( '?page=oracle-cards-status' ),null,40 );
	add_submenu_page( null,esc_html__( 'Status','oracle-cards' ),esc_html__( 'Status','oracle-cards' ),'edit_others_posts','oracle-cards-status','eos_cards_installation_status',60 );
}

//Callback for installation status
function eos_cards_installation_status(){
	?>
	<h1><?php esc_html_e( 'Installation Status','oracle-cards' ); ?></h1>
	<?php
	require EOS_CARDS_DIR.'/admin/class.cards-status.php';
	$status = new Eos_Cards_System_Report();
	$status->output();
}

//Options page
function eos_cards_generate_cards(){
	wp_nonce_field( 'eos_cards_creation_nonce','eos_cards_creation_nonce' );
	$terms = get_terms( array(
		'taxonomy' => 'decks',
		'hide_empty' => false,
	) );
	$n = 0;
	$cards = false;
	$deck_id = isset( $_GET['tag_ID'] ) ? sanitize_text_field( $_GET['tag_ID'] ) : false;
	if( $deck_id ){
		$cards = get_posts(
			array(
				'posts_per_page' => -1,
				'post_type' => 'card',
				'taxonomy' => 'decks',
				'tax_query' => array(
					array(
						'taxonomy' => 'decks',
						'field' => 'id',
						'terms' => esc_attr( $deck_id ),
					)
				)
			)
		);
	}
	if( $cards ){
		$deck = get_term( $deck_id,'decks' );
		$default_cards_name = $deck->name;
	}
	?>
	<section id="eos-cards-sec">
		<div id="cards-creation" class="oracle-cards-section">
			<h1><?php _e( 'Add cards to this deck','oracle-cards' ); ?></h1>
			<div id="cards_title-wrp" class="oracle-cards-section">
				<div><label for="cards_title"><?php esc_html_e( 'Cards title (a progressive number will be added automatically for each card)','oracle-cards' ); ?></label></div>
				<div><input id="cards_title" type="text" value="<?php echo $cards ? esc_attr( $default_cards_name ) : esc_attr__( 'Card','oracle-cards' ); ?>" /></div>
			</div>
			<div id="generate_by_images" class="oracle-cards-section oracle-cards-generation">
				<div><input type="checkbox" id="by-file-title"><span class="eos-checkbox-caption"><?php _e( 'Create titles by uploaded files names','oracle-cards' ); ?></span></div>
				<div><span class="button eos-generate-from-imgs"><?php esc_html_e( 'Upload card front images','oracle-cards' ); ?></div>
			</div>
		</div>
		<?php if( $cards ):
		$deck_slug = $deck->slug;
		$s1 = '<a href="'.admin_url( 'edit.php?decks='.$deck_slug.'&post_type=card' ).'">';
		$s2 = '</a>';
		?>
		<p><?php echo wp_kses( sprintf( __( '%s%s cards%s assigned to this deck','oracle-cards' ),$s1,'<span id="eos-assigned-n">'.count( $cards ).'</span>',$s2 ),array( 'span' => array( 'id' ) ) ); ?></p>
		<?php endif; ?>
		<div>
			<p id="cards-msg-success" class="eos-cards-notice notice notice-success eos-hidden"><?php esc_html_e( 'Cards succesfully created','oracle-cards' ); ?></p>
			<p id="cards-msg-fail" class="eos-cards-notice notice notice-error eos-hidden"><?php esc_html_e( 'Something went wrong.','oracle-cards' ); ?></p>
		</div>
		<?php if( isset( $_GET['tag_ID'] ) ){ ?>
		<div id="cards-to-page" class="oracle-cards-section">
			<h1><?php _e( 'Add cards to the page','oracle-cards' ); ?></h1>
			<div class="oracle-cards-section" style="margin-bottom:0">
				<h2><?php esc_html_e( 'Shortcode','oracle-cards' ); ?></h2>
				<p><?php printf( esc_html__( 'You can add this deck using the shortcode %s','oracle-cards' ),'<p><strong id="shortcode-output">[oracle_cards deck="'.esc_attr( $_GET['tag_ID'] ).'"]</strong></p>' ); ?></p>
				<p><?php printf( esc_html__( 'To customize the deck use the shortcode parameters described in the %sdocumentation.%s','oracle-cards' ),'<a href="'.EOS_CARDS_PLUGIN_DOCU.'#1601503711629-7d3dffeb-d8a2" rel="noopener" target="_blank"">','</a>' ); ?></p>
				<p><?php esc_html_e( 'Be carefuul! Modifing the parameters through the shortcode generator will not update the decks that you have already added to the pages. You need to copy again the generated shortcode and replace the old shortcode with the new one if you want to see the changes.','oracle-cards' ); ?></p>
			</div>
			<div><span id="oc-customize-deck" class="button"><?php esc_html_e( 'Customize Shortcode','oracle-cards' ); ?></span></div>
			<script>var deck_id = <?php echo esc_js( $_GET['tag_ID'] ); ?>;</script>
			<div style="display:none;position:absolute;left:-9999px;top:-9999px">
			<?php
				$args = array(
					'tinymce'       => array(
						'toolbar1'      => 'oracle_cards',
						'toolbar2'      => '',
						'toolbar3'      => '',
					),
				);
				wp_editor( '','oracle-cards',$args );
			} ?>
			</div>
		</div>
		<div id="cards-to-page-builders" class="oracle-cards-section">
			<?php
			if( defined( 'EOSB_VERSION' ) ){
			?>
			<h2><?php esc_html_e( 'Freesoul Builder','oracle-cards' ); ?></h2>
			<p><?php esc_html_e( 'When you edit the page with Freesoul Builder, you will find the element "Oracle Cards."','oracle-cards' ); ?></p>
			<?php
			}
			?>
		</div>
	</section>
	<?php
}

//Add deck choice on Decks admin page
function eos_card_decks_callback(){
	global $oracle_cards;
	wp_nonce_field( 'eos_cards_settings_nonce','eos_cards_settings_nonce' );
	$opts = eos_cards_get_option();
	$defaults = $oracle_cards->default_deck_options();
	$cards_options = isset( $_GET['tag_ID'] ) && isset( $opts[$_GET['tag_ID']] ) ? $opts[$_GET['tag_ID']] : $defaults;
	$preUrl = EOS_CARDS_URL.'/admin/img/card-back-';
	$custom_url = isset( $cards_options['custom_back_card_id'] ) ? wp_get_attachment_url( $cards_options['custom_back_card_id'] ) : false;
	$custom_url = $custom_url ? $custom_url : EOS_CARDS_URL.'/admin/img/card-back-5.png';
	$N = defined ( 'EOS_CARDS_PRO' ) && EOS_CARDS_PRO ? 6 : 5;
	?>
	<div id="cards-options-to-save" class="form-wrap">
		<div id="cards_back" class="eos-cards-opt-radio-group">
			<label><?php esc_html_e( 'Deck Back','oracle-cards' ); ?></label>
			<?php for( $n = 1; $n < $N; ++$n ){ ?>
			<div id="back_default_<?php echo $n; ?>" class="<?php echo $n > 4 ? 'img_preview upload_cat-img_preview ' : '';echo $n > 4 && ( !defined( 'EOS_CARDS_PRO' ) || false === EOS_CARDS_PRO ) ? 'oc-pro-feature ' : '';echo $n === absint( $cards_options['def-back-card-choice']  ) ? 'active ' : ''; ?>back-default" data-n="<?php echo $n; ?>" style="display:inline-block;position:relative;">
				<?php if( $n === 5 ){ ?>
					<input type="hidden" name="custom_back_card_id" id="custom_back_card_id" class="eos-cards-opt-num" value="<?php echo absint( $cards_options['custom_back_card_id'] ); ?>" />
				<?php } ?>
				<img <?php echo $n > 4 && defined ( 'EOS_CARDS_PRO' ) && EOS_CARDS_PRO ? 'id="eos_cat-image" class="eos-upload-image"' : ''; ?> width="150" height="225" src="<?php echo $n !== 5 ? $preUrl.$n.'.png' : esc_url( $custom_url ); ?>" alt="<?php echo esc_attr( sprintf( __( 'Default back card, choice %s','oracle-cards' ),$n ) ); ?>" />
				<div class="def_back_card_choice_wrp">
					<input type="radio" class="eos-cards-opt-radio def-back-card-choice"<?php echo $n === $cards_options['def-back-card-choice'] ? ' checked' : ''; ?> name="def-back-card-choice" id="def-back-card-choice-<?php echo $n; ?>" value="<?php echo $n; ?>" />
				</div>
			</div>
			<?php } ?>
		</div>
		<div class="oracle-cards-section">
			<div>
				<span><?php esc_html_e( 'Cards Width','oracle-cards' ); ?></span>
				<input type="number" name="card_width" id="card_width" class="eos-cards-opt-num" min="150" max="900" step="1" value="<?php echo absint( $cards_options['card_width'] ); ?>" />
				<span><?php esc_html_e( 'Cards Height','oracle-cards' ); ?></span>
				<input type="number" name="card_height" id="card_height" class="eos-cards-opt-num" min="200" max="900" step="1" value="<?php echo absint( $cards_options['card_height'] ); ?>" />
			</div>
			<div>
				<input type="submit" class="button button-primary" value="<?php esc_attr_e( 'Update','oracle-cards' ); ?>">
			</div>
		</div>
	</div>
	<?php
}

add_filter( 'manage_edit-decks_columns', 'eos_decks_columns_head' );
//Add new column to decks table list
function eos_decks_columns_head( $columns ){
	$columns['posts'] = esc_html__( 'Cards','oracle-cards' );
	$columns['dimensions'] = esc_html__( 'Dimensions','oracle-cards' );
	$columns['back'] = esc_html__( 'Back','oracle-cards' );
	$columns['shortcode'] = esc_html__( 'Shortcode','oracle-cards' );
    return $columns;
}

add_filter( 'manage_decks_custom_column','eos_decks_custom_column_content',10,3 );
//Add content to custom decks columns
function eos_decks_custom_column_content( $out,$column_name,$term_id ){
	$cards_options = eos_cards_get_option();
	if( isset( $cards_options[$term_id] ) ){
		$deckOpts = $cards_options[$term_id];
		$outs = array(
			'dimensions' => $deckOpts['card_width'].'X'.$deckOpts['card_height'],
			'shortcode' => '[oracle_cards deck="'.$term_id.'"]'
		);
		if( 'back' === $column_name ){
			global $oracle_cards;
			$defaults = $oracle_cards->default_deck_options();
			$def_choice = isset( $deckOpts['def-back-card-choice'] ) ? $deckOpts['def-back-card-choice'] : $defaults['def-back-card-choice'];
			$img = 5 === $def_choice && isset( $deckOpts['custom_back_card_id'] ) ? wp_get_attachment_image_url( $deckOpts['custom_back_card_id'],'medium' ) : false;
			if( !$img ){
				$img = EOS_CARDS_URL.'/admin/img/card-back-'.$def_choice.'.png';
			}
			return '<img src="'.esc_url( $img ).'" width="60" />';
		}
		return $outs[$column_name];
	}
	return $out;
}

add_filter( 'manage_card_posts_columns', 'eos_card_columns_head' );
//Add new column to cards table list
function eos_card_columns_head( $columns ){
	unset( $columns['title'] );
	unset( $columns['date'] );
	unset( $columns['taxonomy-decks'] );
    return array_merge( $columns,array(
		'featured_img' => '<div style="width:80px;text-align:center;"><span class="dashicons dashicons-format-image"></span></div>',
		'title' => esc_html__( 'Name','oracle-cards' ),
		'content' => esc_html__( 'Content','oracle-cards' ),
		'linked_url' => esc_html__( 'Linked url','oracle-cards' ),
		'taxonomy-decks' => esc_html__( 'Deck','oracle-cards' ),
		'date' => esc_html__( 'Date','oracle-cards' ),
	) );
    return $columns;
}
add_action( 'manage_card_posts_custom_column', 'eos_card_columns_content', 10, 2 );
//Set content for custom column in card content table lists
function eos_card_columns_content($column_name, $post_ID) {
	switch ( $column_name ) {
		case 'featured_img':
			echo get_the_post_thumbnail( $post_ID,array( 80,80) );
			break;
		case 'linked_url':
			echo esc_attr( get_post_meta( $post_ID, '_eos_linked_url_key', true ) );
			break;
		case 'content':
			echo wp_trim_words( get_the_excerpt( $post_ID ),40,'...' );
			break;
	}
}

add_action( 'decks_edit_form', 'eos_cards_tax_edit_meta_field',999,2 );
// Edit term page
function eos_cards_tax_edit_meta_field( $term ) {
	if ( current_user_can('edit_posts') ) {
		eos_card_decks_callback();
		eos_cards_generate_cards();
	}
}
add_action( 'edited_decks', 'eos_cards_save_tax_meta', 10, 2 ); //Save custom taxonomy meta for card genre edit page
add_action( 'create_decks', 'eos_cards_save_tax_meta', 10, 2 ); //Save custom taxonomy meta for card genre
//Save extra taxonomy fields callback function.
function eos_cards_save_tax_meta( $t_id ) {
	if ( current_user_can('edit_posts') ) {
		if (
			isset( $_POST['def-back-card-choice'] )
			&& ( isset( $_POST['custom_back_card_id'] ) || !defined( 'EOS_CARDS_PRO' ) || false === EOS_CARDS_PRO )
			&& isset( $_POST['card_width'] )
			&& isset( $_POST['card_height'] )
			&& isset( $_POST['eos_cards_settings_nonce'] )
			&& wp_verify_nonce( $_POST['eos_cards_settings_nonce'],'eos_cards_settings_nonce' )
		) {
			$cards_options = eos_cards_get_option();
			$cards_options[$t_id] = array(
				'name' => esc_html( $_POST['name'] ),
				'description' => wp_kses_post( $_POST['description'] ),
				'def-back-card-choice' => max( 1,min( 5,absint( $_POST['def-back-card-choice'] ) ) ),
				'card_width' => max( 150,min( 900,absint( $_POST['card_width'] ) ) ),
				'card_height' => max( 200,min( 900,absint( $_POST['card_height'] ) ) )
			);
			if( isset( $_POST['custom_back_card_id'] ) ){
				$cards_options[$t_id]['custom_back_card_id'] = absint( $_POST['custom_back_card_id'] );
			}
			eos_cards_update_option( 'eos-cards-options',$cards_options );
		}
	}
}

add_filter( 'gettext','eos_card_change_admin_cpt_text_filter',20,3 );
//Change the text in the admin for the cards post type
function eos_card_change_admin_cpt_text_filter( $translated_text,$untranslated_text,$domain ) {
  global $typenow;
  if( 'card' == $typenow )  {
    switch( $untranslated_text ) {
        case 'The name is how it appears on your site.':
          $translated_text = '';
        break;
        case 'The description is not prominent by default; however, some themes may show it.':
          $translated_text = '';
        break;
        case 'Name':
          $translated_text = esc_html__( 'Card name','oracle-cards' );
        break;
        case 'A term with the name provided already exists with this parent.':
          $translated_text = esc_html__( 'A deck with this name already exists','oracle-cards' );
        break;
     }
   }
   return $translated_text;
}

add_filter( 'gettext_with_context', 'eos_card_gettext_with_context', 10, 4 );
//Change the text in the admin for the cards post type
function eos_card_gettext_with_context( $translated,$text,$context,$domain ) {
	global $typenow;
	if( 'card' == $typenow ){
        if ( 'term name' == $context ) {
            $translated = esc_html__( 'Deck name','oracle-cards' );
        }
    }

    return $translated;
}

add_action( 'delete_decks','eos_card_delete_decks',10,3 );
//Update options when a deck is deleted
function eos_card_delete_decks( $term,$tt_id,$deleted_term ){
	$cards_options = eos_cards_get_option();
	if( isset( $cards_options[$tt_id] ) ){
		unset( $cards_options[$tt_id] );
	}
	eos_cards_update_option( 'eos-cards-options',$cards_options );
};

$plugin = EOS_CARDS_PLUGIN_BASE_NAME;
//It adds a settings link to the action links in the plugins page
add_filter( "plugin_action_links_$plugin", 'eos_card_plugin_add_settings_link' );
//It adds a settings link to the action links in the plugins page
function eos_card_plugin_add_settings_link( $links ) {
    $settings_link = '| <a class="eos-dp-setts" href="'.admin_url( 'edit-tags.php?taxonomy=decks&post_type=card' ).'">'.esc_html__( 'Decks','oracle-cards' ). '</a>';
    $settings_link .= ' | <a class="eos-dp-setts" href="'.EOS_CARDS_PLUGIN_DOCU.'" rel="noopener" target="oracle_cards_documentation">'.esc_html__( 'Documentation','oracle-cards' ). '</a>';
    array_push( $links, $settings_link );
  	return $links;
}

add_filter( 'post_row_actions','eos_cards_action_links',10,2 );
//Add preview action links
function eos_cards_action_links( $actions,$post ){
	if ( 'card' === $post->post_type ){
		$decksA = wp_get_post_terms( $post->ID,'decks' );
		$deck = 0;
		if( !empty( $decksA ) ){
			$termObj = $decksA[0];
			if( isset( $termObj->term_id ) ){
				$deck = $termObj->term_id;
			}
		}
		$url = add_query_arg( array( 'card_id' => $post->ID,'deck' => $deck ),get_home_url().'/oracle-cards-preview' );
		$actions['preview_in_fan'] = '<a href="'.add_query_arg( 'deck_type','folding_fan',$url ).'" target="_blank" rel="noopener">'.esc_html__( 'Preview in Folding Fan','oracle-cards' ).'</a>';
		$actions['preview_in_deck'] = '<a href="'.add_query_arg( 'deck_type','deck',$url ).'" target="_blank" rel="noopener">'.esc_html__( 'Preview in Deck','oracle-cards' ).'</a>';
	}
	return $actions;
}

add_filter( 'pre_set_site_transient_update_plugins', 'eos_cards_update_plugins_transient_icons', 99 );
//Manage transient update
function eos_cards_update_plugins_transient_icons( $transient ) {
	if ( is_object( $transient ) && isset( $transient->response ) && is_array( $transient->response ) ) {
		$basename = plugin_basename( EOS_CARDS_PLUGIN_FILE );
		if( isset( $transient->response[$basename] ) ){
			$transient->response[$basename]->icons = array(
				'2x' => EOS_CARDS_URL.'/admin/img/icon-256x256.png',
				'1x' => EOS_CARDS_URL.'/admin/img/icon-128x128.png'
			);
		}
	}
	return $transient;
}

add_filter( 'mce_buttons', 'eos_cards_add_tiny_button' );
//Add button to tinymce
function eos_cards_add_tiny_button( $buttons ) {
   array_push( $buttons,'oracle_cards' );
   return $buttons;
}

add_filter( 'mce_external_plugins', 'eos_cards_add_tinymce_plugin' );
//Add plugin to tinymce
function eos_cards_add_tinymce_plugin( $plugins ){
	$options = isset( $_GET['taxonomy'] ) && 'decks' === $_GET['taxonomy'] && isset( $_GET['tag_ID'] ) ? '-options' : '';
    $plugins['oracle_cards'] = EOS_CARDS_URL.'/admin/js/tinymce/cards-tinymce'.$options.'.js';
    return $plugins;
}

add_action( 'admin_head','eos_cards_admin_head_script' );
//Add variables for tinymce
function eos_cards_admin_head_script(){
	?><script><?php
	$opts = eos_cards_get_option();
	$dir = is_rtl() ? array( 'left','right' ) : array( 'right','left' );
	if( false === $opts ){
		?>var decks_ids = false;<?php
	}
	else{
		$decks_titles = array();
		$decks_ids = array();
		foreach( $opts as $t_id => $term ){
			$decks_titles[] = esc_attr( $term['name'] );
			$decks_ids[] = esc_attr( $t_id );
		}
		?>
		var decks_titles = <?php echo wp_json_encode( $decks_titles ); ?>,
			decks_ids = <?php echo wp_json_encode( $decks_ids ); ?>;
		<?php
	}
	?></script>
	<style>
	#oracle-cards-window {
		padding-<?php echo $dir[0]; ?>:10px;
		max-height:400px;
		max-height:80vh;
		max-height:calc(100vh - 50px);
		overflow-y:auto;
		overflow-x:hidden;
		z-index:150000 !important
	}
	.taxonomy-decks .mce-floatpanel{
		z-index:150102 !important
	}
	#imageText{
		border:none
	}
	#imageText-body{background-size:contain;background-repeat:no-repeat;background-position:center;height:48px;max-width: 48px;margin:0 auto;border:1px dotted}
	#oracle-cards-window-title{margin-<?php echo $dir[0]; ?>:24px}
	#oracle-cards-window-title:before{position:absolute;top:2px;<?php echo $dir[0]; ?>:2px;width:32px;height:32px;display:inline-block;content:url(<?php echo EOS_CARDS_URL.'/admin/img/oracle-cards-icon.png'; ?>)}
	i.mce-ico.mce-i-oracle-cards{
		background-image:url(<?php echo EOS_CARDS_URL.'/admin/img/oracle-cards-icon.png'; ?>);
	}
	#cards_maxrand,#cards_maxmargin,#cards_distance,#deck_from,.eos-folding_fan #deck_animation_distance{opacity:0.6;pointer-events:none}.eos-deck #deck_animation_distance,.eos-folding_fan #deck_from,.eos-folding_fan #cards_distance,.eos-folding_fan #cards_maxmargin,.eos-folding_fan #cards_maxrand{opacity:1;pointer-events:initial}
	.oracle-cards-section{
		margin-bottom:32px
	}
	.oracle-cards-section div{
		margin:16px 0
	}
	.def_back_card_choice_wrp{
		position: absolute;
		top: 0;
		<?php echo $dir[0]; ?>:0
	}
	.edit-tag-actions{
    position: fixed;
    <?php echo $dir[0]; ?>: 10px;
    top: 20px
	}
	.taxonomy-decks #delete-link,
	.term-parent-wrap{
		display:none !important
	}
	#back_default_5{
		background:#fff;
		border-top-right-radius: 10px
	}
	#back_default_5:before {
    height: 224px;
    content: "";
    display: inline-block;
		position:absolute
	}
	</style>
	<?php
}
