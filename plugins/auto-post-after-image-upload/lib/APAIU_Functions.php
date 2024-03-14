<?php


class APAIU_Functions {
	public static function registerWhitelistedOptions() {
		register_setting( "apaiu_prefs", 'apaiu_prefs' );
	}

	public static function custom_plugin_row_meta( $links, $file ) {
		if ( strpos( $file, basename( APAIU_FULL_FILE_PATH ) ) !== false ) {
			$new_links = array(
				'configuration' => '<a href="' . admin_url( "admin.php?page=auto-post-after-image-upload" ) . '">Settings</a>'
			);

			$links = array_merge( $links, $new_links );
		}

		return $links;
	}

	/**
	 * @param $attachmentId
	 *
	 * @return array
	 */
	public static function getAttachmentDetails($attachmentId){
		$attachment = get_post( $attachmentId );

		//if mime type is empty that means this is not a regular media attachment
        if(empty($attachment->post_mime_type) || mb_strlen($attachment->post_mime_type) < 1){
            return null;
        }

		/**
		 * We will ignore all other types of attachment if it isn't image.
         *
         * @todo in future we can give options to user to select what types of attachment they really need to care about
		 */
        if(strpos($attachment->post_mime_type, "image") != 0){
            return null;
        }

		$finalData = [];
		$finalData['attachment_id'] = intval($attachment->ID);
		$finalData['attachment_title'] = $attachment->post_title;
		$finalData['attachment_name'] = $attachment->post_name;
		$finalData['attachment_date'] = $attachment->post_date;

		if(!function_exists("wp_get_userdata")){
			require ABSPATH . "wp-includes/pluggable.php";
			$currentUser = wp_get_current_user();
			$finalData['user_name'] = $currentUser->first_name . " " . $currentUser->last_name;
			$finalData['user_display_name'] = $currentUser->display_name;
			$finalData['user_nickname'] = $currentUser->nickname;
			$finalData['user_id'] = intval($currentUser->ID);
		}

		$largeImage      = wp_get_attachment_image_src( $attachmentId, 'large' );
		$finalData['media_src_large'] = $largeImage[0];

        $imagePath = get_attached_file($attachmentId);
        $imageDimension = wp_get_image_editor($imagePath)->get_size();
		$finalData['media_large_width'] = isset($imageDimension['width']) ? $imageDimension['width'] : null;
		$finalData['media_large_height'] = isset($imageDimension['height']) ? $imageDimension['height'] : null;

		return array_filter($finalData);
	}

	public static function auto_post_after_image_upload( $attachId ) {
	    $preferences = unserialize(get_option("apaiu_prefs", ""));
		$attachment = self::getAttachmentDetails($attachId);
		if(empty($attachment)){
		    return null;
        }

		$image_tag  = '<p><img src="' . $attachment['media_src_large'] . '" /></p>';

		$postData = [
		        'post_type' => 'post'
        ];

		if(isset($preferences['custom_post_title']) && !empty($preferences['custom_post_title']) && mb_strlen($preferences['custom_post_title']) > 0){
		    $postData['post_title'] = (new APAIU_HTMLParser($preferences['custom_post_title'], $attachment))->output();
        }else{
			$postData['post_title'] = $attachment['attachment_title'];
        }

		if(isset($preferences['custom_post_categories']) && !empty($preferences['custom_post_categories']) && sizeof($preferences['custom_post_categories']) > 0){
		    $postData['post_category'] = $preferences['custom_post_categories'];
        }else{
			$postData['post_category'] = array( '0' );
        }

		if(isset($preferences['custom_post_tags']) && !empty($preferences['custom_post_tags']) && sizeof($preferences['custom_post_tags']) > 0){
		    $postData['tags_input'] = $preferences['custom_post_tags'];
        }

		if(isset($preferences['custom_post_content']) && !empty($preferences['custom_post_content']) && mb_strlen($preferences['custom_post_content']) > 0){
		    $postData['post_content'] = (new APAIU_HTMLParser($preferences['custom_post_content'], $attachment))->output();
        }else{
			$postData['post_content'] = $image_tag . "<br>" . $attachment['attachment_title'];
        }

		if(isset($preferences['custom_post_status']) && !empty($preferences['custom_post_status']) && in_array($preferences['custom_post_status'], ['publish', 'draft'])){
		    $postData['post_status'] = $preferences['custom_post_status'];
        }else{
			$postData['post_status'] = 'publish';
        }

		if(isset($preferences['custom_post_format']) && !empty($preferences['custom_post_format']) && in_array($preferences['custom_post_format'], ['aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat'])){
		    $postData['tax_input'] = array('post_format' => 'post-format-' . $preferences['custom_post_format']);
        }

		$post_id = wp_insert_post( $postData );

		// attach media to post
		wp_update_post( array(
			'ID'          => $attachId,
			'post_parent' => $post_id,
		) );

		if(isset($preferences['apaiu_set_featured']) && $preferences['apaiu_set_featured'] === "yes"){
			set_post_thumbnail( $post_id, $attachId );
        }

		return $attachId;
	}

	public static function on_activate() {
	}

	public static function on_deactivate() {

	}

	public static function on_uninstall() {
	    delete_option("apaiu_prefs");
	}

	public static function loadAdminHeadStyles() {
		wp_register_style( 'bootstrap.apaiu', plugins_url( 'assets/css/bootstrap-apaiu.css', APAIU_FULL_FILE_PATH ), false, APAIP_VERSION );
		wp_enqueue_style( 'bootstrap.apaiu' );

		wp_register_style( 'sweetalert2.min.apaiu', plugins_url( 'assets/css/sweetalert2.min.css', APAIU_FULL_FILE_PATH ), array( "bootstrap.apaiu" ), APAIP_VERSION );
		wp_enqueue_style( 'sweetalert2.min.apaiu' );

		wp_register_style( 'apaip.main', plugins_url( 'assets/css/main.css', APAIU_FULL_FILE_PATH ), array( "bootstrap.apaiu" ), APAIP_VERSION );
		wp_enqueue_style( 'apaip.main' );

		wp_register_script( 'popperjs.apaiu', plugins_url( 'assets/js/popper.min.js', APAIU_FULL_FILE_PATH ), array( 'jquery' ), APAIP_VERSION, true );
		wp_enqueue_script( "popperjs.apaiu" );

		wp_register_script( 'bootstrap.min.apaiu', plugins_url( 'assets/js/bootstrap.min.js', APAIU_FULL_FILE_PATH ), array( 'jquery' ), APAIP_VERSION, true );
		wp_enqueue_script( 'bootstrap.min.apaiu' );

		wp_register_script( 'sweetalert2.min.apaiu', plugins_url( 'assets/js/sweetalert2.min.js', APAIU_FULL_FILE_PATH ), array( 'bootstrap.min.apaiu' ), APAIP_VERSION, true );
		wp_enqueue_script( 'sweetalert2.min.apaiu' );

		wp_register_script( 'apaip.main', plugins_url( 'assets/js/main.js', APAIU_FULL_FILE_PATH ), array(
			'bootstrap.min.apaiu'
		), APAIP_VERSION, true );
		wp_enqueue_script( 'apaip.main' );
	}

	public static function adminMenu() {
		$menuItems = [
			[
				'page_title'        => "Auto Post After Image Upload",
				'menu_title'        => "Auto Post After Image Upload",
				'capabilities'      => 'manage_options',
				'menu_slug'         => "auto-post-after-image-upload",
				'callback_function' => array( APAIU_Functions::class, 'adminPageDisplay' ),
				'menu_icon'         => "dashicons-email",
			]
		];
		foreach ( $menuItems as $item ) {
			add_options_page( $item['page_title'], $item['menu_title'], $item['capabilities'], $item['menu_slug'], $item['callback_function']);
		}
	}

	public static function adminPageDisplay() {
		$categories = array_map(function($c){
			return ['name' => $c->name, 'id' => $c->cat_ID];
		}, get_categories());

		$tags = array_map(function($c){
			return ['name' => $c->name, 'id' => $c->term_taxonomy_id];
		}, get_tags(array('get'=>'all')));

		echo "<div class='wrap apaiu'><div class='container-fluid'><div class='apaiu-plugin-adminpage' id='apaiu-plugin-adminpage'>";
		echo '<div class="row">
    <div class="col-9">
        <section class="jumbotron text-center">
            <div class="container-full">
                <h1 class="jumbotron-heading">Auto Post After Image Upload</h1>
                <p class="lead text-muted">
                    Create post automatically after you upload any image in your media. It will definitely save lots of
                    your time and effort to upload image and create post for each image. This plugin
                    will make it easy for you. You just upload all the images and as per your settings a post will be
                    automatically created for you after successful image upload.
                </p>
                <p>
                    <a href="https://wordpress.org/support/plugin/auto-post-after-image-upload/"
                       class="btn btn-primary my-2" target="_blank">Contact Support</a>
                    <a href="https://wordpress.org/support/plugin/auto-post-after-image-upload/reviews/#new-post"
                       class="btn btn-secondary my-2" target="_blank">Rate this plugin!</a>
                </p>
                <p>
                    Or directly write me at <a href="mailto:mail@shaharia.com">mail@shaharia.com</a>
                </p>
            </div>
        </section>
    </div>
    <div class="col-3">
        <section>
            <div class="container">
                <p class="lead text-muted">
                    <span style="text-decoration: underline; font-weight: bold">Premium Wordpress Service</span><br>
                    Looking for any Wordpress development service? <a href="https://www.shaharia.com/contact?utm_source=auto-post-after-image-upload">Get in touch</a>.
                </p>
                <p class="lead text-muted">
                    <span style="text-decoration: underline; font-weight: bold">Need Support</span><br>
                    Your support will help me to continue addming more premium grade features to this tools that will be completely free of cost. <br>
                    <script type="text/javascript" src="https://sellcodes.com/quick_purchase/sFC2M6FD/embed.js"></script>
                <div class="sellcodes-quick-purchase"><a class="sc-button-first" data-product-id="sFC2M6FD" data-option-id="vTvB26Y4" data-redirect="1"><span class="custom-buy-now-text">Buy Me a Coffee</span></a></div>
                </p>
            </div>
        </section>
    </div>
</div>

<!-- Save email gateway provider configuration -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Settings / Customization</h5>
                <p class="card-text">
                    Set your preference for creating posts for every image upload. You can set your pre-defined title,
                    contents and other information that you want. <br>
                </p>
                <div class="row">
                    <div class="col-7">
                        <div class="form-group">
                            <label for="customTitle">Custom Title</label>
                            <input type="email" class="form-control" id="customTitle"
                                   aria-describedby="customTitleArea" placeholder="Customize your title" name="apaiu_custom_title">
                            <small id="customTitleArea" class="form-text text-muted">We\'ll never share your
                                email with anyone else.</small>
                        </div>
                        <div class="form-group">
                            <label for="customPostContent">Custom Post Content</label>
                            <textarea class="form-control" id="customPostContent" rows="4"
                                      placeholder="Preset your contents" aria-describedby="customTextArea" name="apaiu_custom_textarea"></textarea>
                            <small id="customTextArea" class="form-text text-muted">HTML tags are supported</small>
                        </div>
                        <div class="form-group">
                            <label for="customPreselectCategory">Preselct Category</label>
                            <select multiple class="form-control" id="customPreselectCategory" name="apaiu_categories">';
                            foreach($categories as $cat){ ?>
                                <option value="<?php echo $cat['id']?>"><?php echo $cat['name']?></option>
                            <?php } echo '</select>
                        </div>
                        <div class="form-group">
                            <label for="customPreselectTag">Select Tag</label>
                            <select multiple="multiple" class="form-control" id="customPreselectTag" name="apaiu_tags">';?>
							<?php foreach ($tags as $tag){
								echo '<option value="' . $tag['name'] .'">' . $tag['name'] .'</option>';
		}?>
		<?php echo '</select>
                        </div>
                        <div class="form-group">
                            <label for="customPreselectPostFormat">Post Format</label>
                            <select class="form-control" id="customPreselectPostFormat" name="custom_post_format">
                                <option value=""></option>
                                <option value="aside">Aside</option>
                                <option value="gallery">Gallery</option>
                                <option value="link">Link</option>
                                <option value="image">Image</option>
                                <option value="quote">Quote</option>
                                <option value="status">Status</option>
                                <option value="audio">Audio</option>
                                <option value="video">Video</option>
                                <option value="chat">Chat</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="customPreselectStatus">Default Status</label>
                            <select class="form-control" id="customPreselectStatus" name="apaiu_status">
                                <option value="publish">Publish</option>
                                <option value="draft">Draft</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="customPreselectStatus">Set as Featured Image?</label>
                            <select class="form-control" id="customPreselectStatus" name="apaiu_set_featured">
                                <option value="yes" selected="selected">Yes</option>
                                <option value="no">No</option>
                            </select>
                        </div>
                        <button class="btn btn-primary" id="savePrefsButton">Save Preference</button>
                    </div>
                    <div class="col-5">
                        <h6>Available Placeholder</h6>
                        <pre><code style="font-size: 14px; line-height: 2;">
{{user_name}}
{user_display_name}}
{{user_nickname}}
{{user_id}}

{{attachment_id}}
{{attachment_title}}
{{attachment_name}}
{{attachment_date}}
{{attachment_src}}

{{media_src_large}}
{{media_large_width}}
{{media_large_height}}

{{media_src_thumb}}
</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>';
		echo "</div></div></div>";
	}

	public static function adminFooterScripts(){

	}

	public static function apaiu_save_preferences(){
	    $prfs = unserialize(get_option("apaiu_prefs", ""));

	    $postData = $_POST;
	    $pConfigs = json_decode(stripslashes($postData['configs']), true);

	    $preferences = [];

	    if(!empty($pConfigs['custom_post_title']) && filter_var($pConfigs['custom_post_title'], FILTER_SANITIZE_STRING)){
	        $preferences['custom_post_title'] = filter_var($pConfigs['custom_post_title'], FILTER_SANITIZE_STRING);
        }else{
		    $preferences['custom_post_title'] = $prfs['custom_post_title'];
	    }

	    if(!empty($pConfigs['custom_post_content'])){
	        $preferences['custom_post_content'] = $pConfigs['custom_post_content'];
        }else{
		    $preferences['custom_post_content'] = $prfs['custom_post_content'];
        }

	    if(!empty($pConfigs['custom_post_categories'])){
	        $preferences['custom_post_categories'] = array_values($pConfigs['custom_post_categories']);
        }else{
		    $preferences['custom_post_categories'] = $prfs['custom_post_categories'];
	    }

	    if(!empty($pConfigs['custom_post_tags'])){
	        $preferences['custom_post_tags'] = array_values($pConfigs['custom_post_tags']);
        }else{
		    $preferences['custom_post_tags'] = $prfs['custom_post_tags'];
	    }

	    if(!empty($pConfigs['custom_post_status'])){
	        $preferences['custom_post_status'] = $pConfigs['custom_post_status'];
        }else{
		    $preferences['custom_post_status'] = $prfs['custom_post_status'];
	    }

	    if(!empty($pConfigs['custom_post_format'])){
	        $preferences['custom_post_format'] = $pConfigs['custom_post_format'];
        }else{
		    $preferences['custom_post_format'] = $prfs['custom_post_format'];
	    }

	    if(!empty($pConfigs['apaiu_set_featured'])){
	        $preferences['apaiu_set_featured'] = $pConfigs['apaiu_set_featured'];
        }else{
		    $preferences['apaiu_set_featured'] = 'yes';
	    }

	    update_option('apaiu_prefs', serialize($preferences));

	    wp_send_json_success(['success' => true, 'prefs' => unserialize(get_option("apaiu_prefs", []))]);
	    exit();
	}

	public static function apaiu_get_preferences(){
	    wp_send_json_success(['success' => true, 'prefs' => unserialize(get_option("apaiu_prefs", []))]);
	    exit();
	}
}