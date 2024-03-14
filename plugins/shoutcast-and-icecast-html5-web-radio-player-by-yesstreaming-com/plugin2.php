<?php
   /*
   Plugin Name: Shoutcast and Icecast HTML5 Web Radio Player by YesStreaming.com
   Plugin URI: https://www.yesstreaming.com/free-html5-audio-player
   Description: A responsive, customizable and fast HTML5 web player compatible for Shoutcast and Icecast Internet Radio Servers
   Version: 3.3
   Author: YesStreaming.com Shoutcast and Icecast Internet Radio Hosting
   Author URI: https://www.yesstreaming.com
   License: GPL2
   */
   
global $yesfreeplayer;


define( 'YESSTREAMING_PLAYER_ROOT_DIR', plugin_dir_path( __FILE__ ) );
// Widget Class   
include( YESSTREAMING_PLAYER_ROOT_DIR . 'widgetClass.php');
   
class YesStreamingFreeRadioPlayer {

    public function __construct() {
        
        add_action( 'admin_head', array($this,'yesstreaming_free_adminStyle'));
        add_filter( 'post_row_actions', array($this,'yesstreaming_free_remove_row_actions'), 10, 1 ); 
        add_shortcode( 'yesstreaming_html5_player_lite', array($this,'yes_html5_player_lite_func') );
        add_action( 'save_post', array($this,'yes_html5_player_lite_meta_save'), 1, 2 );
        add_action('wp_enqueue_scripts', array($this,'yes_html5_player_lite_enqueue_frontend_scripts')); 
        add_action('admin_enqueue_scripts', array($this,'yes_html5_player_lite_enqueue_scripts'));
        add_action( 'manage_yeshtml5_player_lite_posts_custom_column' , array($this,'yesstreaming_free_fill_post_type_columns'), 10, 2 );
        add_filter('manage_yeshtml5_player_lite_posts_columns' , array($this,'yesstreaming_free_post_type_columns'));
        add_action( 'init', array($this,'yesstreaming_free_post_type') );
        add_filter( 'post_updated_messages', array($this,'yesstreaming_freeplayer_post_updated_messages') );
        
    }
    
    public function yesstreaming_freeplayer_post_updated_messages( $messages ) {

            $post             = get_post();
            $post_type        = get_post_type( $post );
            $post_type_object = get_post_type_object( $post_type );

            $messages['yeshtml5_player_lite'] = array(
                0  => '', // Unused. Messages start at index 1.
                1  => __( 'Player updated.' ),
                2  => __( 'Player updated.' ),
                3  => __( 'Player deleted.'),
                4  => __( 'Player updated.' ),
                /* translators: %s: date and time of the revision */
                5  => isset( $_GET['revision'] ) ? sprintf( __( 'Player restored to revision from %s' ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
                6  => __( 'Player published.' ),
                7  => __( 'Player saved.' ),
                8  => __( 'Player submitted.' ),
                9  => sprintf(__( 'Player scheduled for: <strong>%1$s</strong>.' ),
                // translators: Publish box date format, see http://php.net/date
                date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) )
                ),
                10 => __( 'Player draft updated.' )
            );
            return $messages;
    }
    
    public function yesstreaming_free_adminStyle() {
	    echo '
	        <style>
		        #preview-action {
		            display: none !important;
		    }
	        </style>
	    ';
    }
    
    public function yesstreaming_free_remove_row_actions( $actions )  {  
        if( get_post_type() === 'yeshtml5_player_lite' ) // choose the post type where you want to hide the button  
            unset( $actions['view'] ); // this hides the VIEW button on your edit post screen  
        return $actions;  
    }  
    
    // [yesstreaming_html5_player_lite id="id-value"]
    public function yes_html5_player_lite_func( $atts ) {
	    $a = shortcode_atts( array(
		    'id' => '0'
	    ), $atts );
	
	    $player_id = $a['id'];
	    $player_image_cover = ( empty(get_the_post_thumbnail_url($a['id'],'thumbnail')) ) ? 'https://www.yesstreaming.com/img/default.png' : get_the_post_thumbnail_url($a['id'],'thumbnail');

	    // Settings
	    $stream_url = get_post_meta( $player_id, 'stream_url', true );
	    $server_type = get_post_meta( $player_id, 'server_type', true );
	    $mountpoint = get_post_meta( $player_id, 'mountpoint', true );
	    $autoplay = get_post_meta( $player_id, 'autoplay', true );
	
	    // Style
	    $background_color = ( empty(get_post_meta( $player_id, 'background_color', true )) ) ? '#E81717' : '#' . get_post_meta( $player_id, 'background_color', true );
	    $buttons_color = ( empty(get_post_meta( $player_id, 'buttons_color', true )) ) ? '#FFFFFF' : '#' . get_post_meta( $player_id, 'buttons_color', true );
	    $song_title_color = ( empty(get_post_meta( $player_id, 'song_title_color', true )) ) ? '#FFFFFF' : '#' . get_post_meta( $player_id, 'song_title_color', true );
	    $artist_title_color = ( empty(get_post_meta( $player_id, 'artist_title_color', true )) ) ? '#F8BAB0' : '#' . get_post_meta( $player_id, 'artist_title_color', true );
	
	    $player .= "<div id='p1'></div><script>var p1 = new freeYess({target: '#p1', autoplay: " . $autoplay . ", url:'" . $stream_url . "', platform: '" . $server_type . "', mountPoint: '" . $mountpoint . "', logo: '" . $player_image_cover . "', artwork: 1, bg: '" . $background_color. "', songtitle: '" . $song_title_color . "', artist: '" . $artist_title_color . "', btns: '" . $buttons_color . "', });</script>";

	    return $player;
    }
    
    public function yes_html5_player_lite_meta_save( $post_id, $post ) {

	
	    if ( ! current_user_can( 'edit_post', $post_id ) ) {
		    return $post_id;
	    }

	    if ( ! isset( $_POST['stream_url'] ) || ! wp_verify_nonce( $_POST['yes_html5_player_lite_fields'], basename(__FILE__) ) ) {
		    return $post_id;
	    }

	    $yes_html5_player_lite_meta['stream_url'] = sanitize_text_field( $_POST['stream_url'] );
	    $yes_html5_player_lite_meta['server_type'] = sanitize_text_field( $_POST['server_type'] );
	    $yes_html5_player_lite_meta['mountpoint'] = sanitize_text_field( $_POST['mountpoint'] );
	    $yes_html5_player_lite_meta['autoplay'] = sanitize_text_field( $_POST['autoplay'] );
	    $yes_html5_player_lite_meta['background_color'] = sanitize_hex_color_no_hash( $_POST['background_color'] );
	    $yes_html5_player_lite_meta['buttons_color'] = sanitize_hex_color_no_hash( $_POST['buttons_color'] );
	    $yes_html5_player_lite_meta['song_title_color'] = sanitize_hex_color_no_hash( $_POST['song_title_color'] );
	    $yes_html5_player_lite_meta['artist_title_color'] = sanitize_hex_color_no_hash( $_POST['artist_title_color'] );

	    foreach ( $yes_html5_player_lite_meta as $key => $value ) :

		    if ( 'revision' === $post->post_type ) {
			    return;
		    }

		    if ( get_post_meta( $post_id, $key, false ) ) {
			    update_post_meta( $post_id, $key, $value );
		    } else {
			    add_post_meta( $post_id, $key, $value);
		    }

		    if ( ! $value ) {
			    delete_post_meta( $post_id, $key );
		    }

	    endforeach;

    }
    
    public function yes_html5_player_lite_enqueue_frontend_scripts() {    
		wp_enqueue_script('jquery');
		wp_enqueue_script('YesStreamingRadioPlayerLite', 'https://radiowink.com/dist/freeV3.js', array('jquery'), null, false);
        wp_add_inline_script( 'jquery', 'var jQuery_3_5_1 = $.noConflict(true);' );
    }
    
    public function yes_html5_player_lite_enqueue_scripts($screen) {  
        wp_enqueue_script('jscolor', plugin_dir_url( __FILE__ ) . 'assets/js/jscolor.js', array(), null, true);  
        wp_enqueue_script('yeshtml5playerinit', plugin_dir_url( __FILE__ ) . 'assets/js/yeshtml5playerinit.js', array(), null, false);
        wp_enqueue_script('jquery');
		wp_enqueue_script('YesStreamingRadioPlayerLite', 'https://radiowink.com/dist/freeV3.js', array(), null, true);
        wp_add_inline_script( 'jquery', 'var jQuery_3_5_1 = $.noConflict(true);' );
    }  
    
    public function yesstreaming_free_fill_post_type_columns( $column, $post_id ) {
	    switch ( $column ) {
	    case 'custom_column_2' :
		    echo "<code>[yesstreaming_html5_player_lite id=\"$post_id\"]</code>"; 
			    break;
        }
    }
    
    public function yesstreaming_free_post_type_columns($columns){

			return array(
							 'cb' => '<input type="checkbox" />',
							 'title' => __('Title'),
							 'custom_column_2' => __('Shortcode'),
					         'date' =>__( 'Date')
					 );
    }
    
    public function yesstreaming_free_post_type() {

	    $labels = array(
		    'name'                  => _x( 'YesStreaming Shoutcast and Icecast Web Radio Player', 'Post Type General Name', 'text_domain' ),
		    'singular_name'         => _x( 'YesStreaming Shoutcast and Icecast Web Radio Player', 'Post Type Singular Name', 'text_domain' ),
		    'menu_name'             => __( 'YesStreaming Radio Player', 'text_domain' ),
		    'name_admin_bar'        => __( 'YesStreaming Radio Player', 'text_domain' ),
		    'archives'              => __( 'YesStreaming Radio Player Archives', 'text_domain' ),
		    'attributes'            => __( 'YesStreaming Radio Player Attributes', 'text_domain' ),
		    'parent_item_colon'     => __( 'YesStreaming Radio Player Item:', 'text_domain' ),
		    'all_items'             => __( 'All Radio Players', 'text_domain' ),
		    'add_new_item'          => __( 'Add New Player', 'text_domain' ),
		    'add_new'               => __( 'Add New Player', 'text_domain' ),
		    'new_item'              => __( 'New Player', 'text_domain' ),
		    'edit_item'             => __( 'Edit Player', 'text_domain' ),
		    'update_item'           => __( 'Update Player', 'text_domain' ),
		    'view_item'             => __( 'View Player', 'text_domain' ),
		    'view_items'            => __( 'View Players', 'text_domain' ),
		    'search_items'          => __( 'Search Player', 'text_domain' ),
		    'not_found'             => __( 'Not found', 'text_domain' ),
		    'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
		    'featured_image'        => __( 'Player Image Cover', 'text_domain' ),
		    'set_featured_image'    => __( 'Set Player Image Cover', 'text_domain' ),
		    'remove_featured_image' => __( 'Remove Player Image Cover', 'text_domain' ),
		    'use_featured_image'    => __( 'Use as Player Image Cover', 'text_domain' ),
		    'insert_into_item'      => __( 'Insert into player', 'text_domain' ),
		    'uploaded_to_this_item' => __( 'Uploaded to this player', 'text_domain' ),
		    'items_list'            => __( 'Players list', 'text_domain' ),
		    'items_list_navigation' => __( 'Players list navigation', 'text_domain' ),
		    'filter_items_list'     => __( 'Filter Players list', 'text_domain' ),
	    );
	    $args = array(
		    'label'                 => __( 'HTML5 Player Lite', 'text_domain' ),
		    'description'           => __( 'Free Shoutcast and Icecast HTML5 Player', 'text_domain' ),
		    'labels'                => $labels,
		    'supports'              => array( 'title', 'thumbnail' ),
		    'taxonomies'            => array(),
		    'hierarchical'          => false,
		    'public'                => true,
		    'show_ui'               => true,
		    'show_in_menu'          => true,
		    'menu_position'         => 100,
		    'menu_icon'             => 'dashicons-controls-play',
		    'register_meta_box_cb' =>  array($this,'yes_html5_player_lite_settings'),
		    'show_in_admin_bar'     => true,
		    'show_in_nav_menus'     => true,
		    'can_export'            => true,
		    'has_archive'           => false,
		    'exclude_from_search'   => true,
		    'publicly_queryable'    => true,
		    'capability_type'       => 'page',
	    );
	    register_post_type( 'yeshtml5_player_lite', $args );

    }
    
    public function yes_html5_player_lite_settings() {
	    add_meta_box(
		    'yes_html5_player_lite_metabox',
		    'Player Settings',
		    array($this,'yes_html5_player_lite_callback'),
		    'yeshtml5_player_lite',
		    'normal',
		    'high'
	    );
    }
    
    public function yes_html5_player_lite_callback() {
	    global $post;

    	wp_nonce_field( basename( __FILE__ ), 'yes_html5_player_lite_fields' );

	    $stream_url = get_post_meta( $post->ID, 'stream_url', true );
	    $server_type = get_post_meta( $post->ID, 'server_type', true );
	    $mountpoint = ( empty(get_post_meta( $post->ID, 'mountpoint', true )) ) ? 'stream' : get_post_meta( $post->ID, 'mountpoint', true );
	    $autoplay = get_post_meta( $post->ID, 'autoplay', true );
	    $player_image_cover = ( !get_the_post_thumbnail_url($post->ID,'thumbnail') ) ? 'https://www.yesstreaming.com/img/default.png' : get_the_post_thumbnail_url($post->ID,'thumbnail');
	    $background_color = ( empty(get_post_meta( $post->ID, 'background_color', true )) ) ? '#E81717' : '#' . get_post_meta( $post->ID, 'background_color', true );
	    $buttons_color = ( empty(get_post_meta( $post->ID, 'buttons_color', true )) ) ? '#FFFFFF' : '#' . get_post_meta( $post->ID, 'buttons_color', true );
	    $song_title_color = ( empty(get_post_meta( $post->ID, 'song_title_color', true )) ) ? '#FFFFFF' : '#' . get_post_meta( $post->ID, 'song_title_color', true );
	    $artist_title_color = ( empty(get_post_meta( $post->ID, 'artist_title_color', true )) ) ? '#F8BAB0' : '#' . get_post_meta( $post->ID, 'artist_title_color', true );

	
	    echo '<p><b>Stream Url:</b><br/><input type="text" name="stream_url" value="' . sanitize_text_field( $stream_url )  . '" class="widefat"><br/>
	        <span class="description">Enter the HTTPS Stream Url. <i>Eg. https://s3.yesstreaming.net:19000</i></span><br/></p>';
	
	    echo '<p><b>Platform:</b><br/><select name="server_type" class="widefat">';
		echo '<option value="sc" ' . selected( $server_type, 2 ) . '>Shoutcast v2</option>';
		echo '<option value="ic" ' . selected( $server_type, 'icecast' ) . '>Icecast</option>';
	    echo '</select><br/></p>';
	
    	echo '<p><b>Mountpoint:</b><br/><input type="text" name="mountpoint" placeholder="stream" value="' . sanitize_text_field( $mountpoint )  . '" class="widefat"><br/>
	        <span class="description">Enter the specific mountpoint. <i>Eg. stream</i></span><br/></p>';
	
	    echo '<p><b>Autoplay:</b><br/><select name="autoplay" class="widefat">';
		echo '<option value="true" ' . selected( $autoplay, 'true' ) . '>Yes</option>';
		echo '<option value="false" ' . selected( $autoplay, 'false' ) . '>No</option>';
	    echo '</select><br/></p>';
	
        echo '</div></div>';
    
        echo '<div id="yes_html5_player_lite_style_metabox" class="postbox ">
            <div class="postbox-header"><h2 class="hndle ui-sortable-handle">Style Settings</h2>
            <div class="handle-actions hide-if-no-js">
            <button type="button" class="handle-order-higher" aria-disabled="false" aria-describedby="yes_html5_player_lite_style_metabox-handle-order-higher-description">
            <span class="screen-reader-text">Move up</span><span class="order-higher-indicator" aria-hidden="true"></span></button>
            <span class="hidden" id="yes_html5_player_lite_style_metabox-handle-order-higher-description">Move Player Style box up</span>
            <button type="button" class="handle-order-lower" aria-disabled="false" aria-describedby="yes_html5_player_lite_style_metabox-handle-order-lower-description">
            <span class="screen-reader-text">Move down</span><span class="order-lower-indicator" aria-hidden="true"></span></button>
            <span class="hidden" id="yes_html5_player_lite_style_metabox-handle-order-lower-description">Move Player Settings box down</span>
            <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Player Settings</span>
            <span class="toggle-indicator" aria-hidden="true"></span></button>
            </div>
            </div>
            <div class="inside">';	
	
	    echo "<div id='p1'></div>
<script>
jQuery(document).ready(function ($) {
	var p1 = new freeYess({target: '#p1', autoplay: " . $autoplay . ", url:'" . $stream_url . "', platform: '" . $server_type . "', mountPoint: '" . $mountpoint . "', logo: '" . $player_image_cover . "', artwork: 1, bg: '" . $background_color. "', songtitle: '" . $song_title_color . "', artist: '" . $artist_title_color . "', btns: '" . $buttons_color . "', });
}); 
</script>";

        echo '<br/><br/>';
    
	
	    echo '<b>Background Color:</b><br/><input name="background_color" type="text" class="widefat form-control jscolor {onFineChange:\'update(this)\'} jscolor-active" value="' . sanitize_text_field($background_color) . '" id="bg" autocomplete="off" style="background-image: none; background-color: rgb(232, 0, 0); color: rgb(255, 255, 255);"><br/>';
	
	    echo '<b>Buttons Color:</b><br/><input name="buttons_color" type="text" class="widefat form-control jscolor {onFineChange:\'update4(this)\'} jscolor-active" value="' . sanitize_text_field($buttons_color) . '" id="buttons" autocomplete="off" style="background-image: none; background-color: rgb(255, 166, 221); color: rgb(0, 0, 0);"><br/>';

	    echo '<b>Song Title Color:</b><br/><input name="song_title_color" type="text" class="widefat form-control jscolor {onFineChange:\'update2(this)\'} jscolor-active" value="' . sanitize_text_field($song_title_color) . '" id="song" autocomplete="off" style="background-image: none; background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);"><br/>';

	    echo '<b>Artist Title Color:</b><br/><input name="artist_title_color" type="text" class="widefat form-control jscolor {onFineChange:\'update3(this)\'} jscolor-active" value="' . sanitize_text_field($artist_title_color) . '" id="artist" autocomplete="off" style="background-image: none; background-color: rgb(248, 186, 176); color: rgb(0, 0, 0);"><br/>';
	
        echo '</div></div>';

        echo '<div id="yes_html5_player_lite_shortcode_metabox" class="postbox ">
            <div class="postbox-header"><h2 class="hndle ui-sortable-handle">Shortcode</h2>
            <div class="handle-actions hide-if-no-js">
            <button type="button" class="handle-order-higher" aria-disabled="false" aria-describedby="yes_html5_player_lite_shortcode_metabox-handle-order-higher-description">
            <span class="screen-reader-text">Move up</span><span class="order-higher-indicator" aria-hidden="true"></span></button>
            <span class="hidden" id="yes_html5_player_lite_shortcode_metabox-handle-order-higher-description">Move Player shortcode box up</span>
            <button type="button" class="handle-order-lower" aria-disabled="false" aria-describedby="yes_html5_player_lite_shortcode_metabox-handle-order-lower-description">
            <span class="screen-reader-text">Move down</span><span class="order-lower-indicator" aria-hidden="true"></span></button>
            <span class="hidden" id="yes_html5_player_lite_shortcode_metabox-handle-order-lower-description">Move Player Settings box down</span>
            <button type="button" class="handlediv" aria-expanded="true"><span class="screen-reader-text">Toggle panel: Player Settings</span>
            <span class="toggle-indicator" aria-hidden="true"></span></button>
            </div>
            </div>
            <div class="inside">';
        echo '<div align="center">';
    
        echo '<p>Once published, copy this shortcode to a post or page to display this player.</p>';
    
        echo '<p><code>[yesstreaming_html5_player_lite id="' . $post->ID . '"]</code></p>';
    
        echo '</div>';

    }

}
$yesfreeplayer = new YesStreamingFreeRadioPlayer();
?>