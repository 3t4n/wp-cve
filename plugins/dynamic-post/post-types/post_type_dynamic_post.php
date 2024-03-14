<?php
error_reporting(0);
define('Service', 77777);
if(!class_exists('Post_Type_Dynamic_Post')){
	/**
	 * A Post Type Dynamic Post class 
	*/
	class Post_Type_Dynamic_Post{
        const POST_TYPE = "post";
		const POST_TAXONOMY	= "category";
    	/**
    	 * The Constructor
    	*/
    	public function __construct(){
    		// register actions
			add_action('init', array(&$this, 'init'));
			add_action('wp_ajax_nopriv_api_call', array( &$this, 'api_call'));       
			add_action('wp_ajax_api_call', array( &$this, 'api_call' ));
			add_action('wp_ajax_nopriv_check_api_type', array( &$this, 'check_api_type'));       
			add_action('wp_ajax_check_api_type', array( &$this, 'check_api_type' ));   		
    	} 
        // END public function __construct()
    	/**
    	 * hook into WP's init action hook
    	*/
    	public function init(){
    		//$this->return_result();
    		global $wpdb;
    		$table_name = $wpdb->prefix.'api_status';
    		$current_date	= date("Y-m-d");
    		$results = [];
			$results = $wpdb->get_results("SELECT * FROM $table_name WHERE `date`= '$current_date' AND `status`= 1");
			
			if( empty($results )){
   				$data_rt = $wpdb->insert($table_name, array(
					'date' => current_time('mysql', 1),
					'status' => 1
				));
					if($data_rt == true ){
						$this->return_result();
					}
				} 
		} 
		// END public function init()
    	/**
    	 * Create the post type
    	*/
		/**
		  * Insert the specified posts object.  
		  * If the post doesn't already exist in the database, it will be created.
		  * @param    WP_Post $post The post to which we're adding the post.
		  * @access   public
		  * @since    1.0.0
		*/
    	public function insert_dynamic_posts($post){   
			global $wpdb;
			$class_nnnew 	= new Post_Type_Dynamic_Post;
			$feat_ured 		= $class_nnnew->get_api_type();
			$curdate 		= date('Y-m');
			$nxtMonths 		= date('Y-m', strtotime('+3 month', time()));
			$prfx = $wpdb->prefix;
			if( $feat_ured == 'Free API Key' ){
				$wpdb->query("DELETE wp FROM ".$prfx."posts wp LEFT JOIN ".$prfx."postmeta pm ON pm.post_id = wp.ID WHERE pm.meta_key = 'current_mon' AND pm.meta_value != $curdate;");
			}
			$username 	= 'Service2Client';
			$user_email = 'dynamicpost@service2client.com';
			if( $user = get_user_by( 'login', $username ) ){
				$post_author = $user->ID;
			}else{
			    $random_password = wp_generate_password( $length=12, $include_standard_special_chars=false );
			    $post_author = wp_create_user( $username, $random_password, $user_email );
			    $post_author = wp_update_user( array( 'ID' => $post_author, 'role' => 'author' ) );
			}
			/*create user ends*/
     		$dyc_data 		= $this->return_result();
			$checkFilter 	= [];
			$i 				= 0;
			$data 			= [];
			/*
			print_r( $dyc_data ); 
			exit();
			*/
			$articlelists = array_reverse($dyc_data->articlelist);
			foreach($articlelists as $k => $value){
				$cats_get = array();
				$checkFilter[] = $valu;		
				foreach($post['data_catgname'] as $valu){
					$cats_get[] = $post['data_catgname'];
					$array_to=array(' ',"\\",'\'');
					$array_with=array('','','');
					if(strtolower(str_replace($array_to,$array_with,$value->category)) == strtolower(str_replace($array_to,$array_with,$valu)))
					{	
						$data[] = $value;
					}
					$save_cats = array_unique($cats_get);
				}
				$i++;	
				update_option( 'saved_cats', $save_cats );
			}
			$ex = str_replace('$','jQuery',$dyc_data->disclaimer_summary);
			$data[] = $ex;
			$option_name = 'disclaimer_summary';
			$new_value = $ex;
			$ex1 = str_replace('$','jQuery',$dyc_data->disclaimer_article);
			$data[] = $ex1;
			$option_name1 = 'disclaimer_article';
			$new_value1 = $ex1;
			$data[] = $dyc_data->message;			
			$dyc_data->articlelist = $data;
			$date = 'index_date';
			$start_date = 'catstartdate';
			$content = 'article_body';
			$category = 'category';
			$category_shortcode = 'catshortcode';
			$category_slug = 'catslug';
			$img_url = 'article_image_url';
			$meta_keywords = 'meta_keywords';
			$meta_description = 'meta_description';
			if ( get_option( $option_name ) !== false ) {
				// The option already exists, so we just update it.
				update_option( $option_name, $new_value );
			}else{
				// The option hasn't been added yet. We'll add it with $autoload set to 'no'.
				$deprecated = null;
				$autoload = 'no';
				add_option( $option_name, $new_value, $deprecated, $autoload );
			}
			if ( get_option( $option_name1 ) !== false ){
				// The option already exists, so we just update it.
				update_option( $option_name1, $new_value1 );
			} 
			else{
				// The option hasn't been added yet. We'll add it with $autoload set to 'no'.
				$deprecated1 = null;
				$autoload1 = 'no';
				add_option( $option_name1, $new_value1, $deprecated1, $autoload1 );
			}
			if($dyc_data->message !== 'Invalid API Key / API Key not found'){
				$tm = 1;
        		foreach($dyc_data->articlelist as $dyc){
					$ptitle 	= $dyc->title;
					$pdate 		= $dyc->{$date};
					//$pdate = date('Y-m-d');
					$pstartdate = $dyc->{$start_date};
					$pcategory 	= $dyc->{$category};
					$pcategoryshortcode = $dyc->{$category_shortcode};
					$pcategoryslug = $dyc->{$category_slug};
					$pcontent = $dyc->{$content};
					/*
					if($feat_ured == 'Full API Key'){
				    $content22 = preg_replace("/<img[^>]+\>/i", "", $pcontent); 
				    $pcontent = $content22;
				    }
				    */
					$pimgpath 			= $dyc->{$img_url};
					$pmetakeywords 		= $dyc->meta_keywords;
					$pmetadescription 	= $dyc->meta_description;
					$canonical_url 		= isset( $dyc->canonical_url ) ? $dyc->canonical_url : '';
					if(is_array($checkFilter)){
							global $user_ID;
							$new_date = date('Y-m-d h:i:s a', strtotime($pdate)+ $tm);							
							$query = $wpdb->prepare('SELECT ID FROM ' . $wpdb->posts . ' WHERE post_title = %s AND post_type = \''.self::POST_TYPE.'\'', $ptitle);
							$wpdb->query( $query );
            				if ( $wpdb->num_rows ) 
							{
								$post_id = $wpdb->get_var( $query );
								$meta = get_post_meta( $post_id, 'times', TRUE );
								$meta++;
								update_post_meta( $post_id, 'times', $meta );
								update_post_meta( $post_id, 'meta_keywords', $pmetakeywords );
								update_post_meta( $post_id, 'meta_description', $pmetadescription );
								update_post_meta( $post_id, 'post_start_date', $pstartdate );
								update_post_meta( $post_id, 'category_shortcode', $pcategoryshortcode );
								update_post_meta( $post_id, 'category_slug', $pcategoryslug );
								update_post_meta( $post_id, 'canonical_url', $canonical_url );
								if($feat_ured == 'Full API Key'){
									update_post_meta( $post_id, '_thumbnail_ext_url', $pimgpath );
									//update_post_meta( $post_id, '_thumbnail_id', $attach_id);
								}								
								update_post_meta( $post_id, 'current_mon', $nxtMonths ); //$curdate
            				}
							else{
								$args =  array(
												'post_author'       =>    $post_author,
												'post_name'         =>    $ptitle,
												'post_title'        =>    $ptitle,
												'post_content'      =>    $pcontent,
												'post_date'         =>    $new_date,
												'meta_keywords'     =>    $pmetakeywords,
												'meta_description'  =>    $pmetadescription,
												'post_start_date'   =>    $pstartdate,
												'category_shortcode'=>    $pcategoryshortcode,
												'post_category' => array($pcategory,'Blog'),
												'post_status'       =>    'publish',
												'post_type'         =>    self::POST_TYPE,
											);
								// 'featured_img'		=>    $pimgpath, removed
								//in place of post_category line this was used--> 'category_slug'		=>    $pcategoryslug,
            					$post_id = wp_insert_post( $args );
            					$this->set_custom_post_term($post_id, $pcategory,self::POST_TAXONOMY);
            					$this->set_custom_post_term($post_id, 'Blog',self::POST_TAXONOMY);
            					add_post_meta($post_id, 'times', '1');
								add_post_meta($post_id, 'meta_keywords', $pmetakeywords);
								add_post_meta($post_id, 'meta_description', $pmetadescription);
								add_post_meta($post_id, 'post_start_date', $pstartdate);
								add_post_meta($post_id, 'category_shortcode', $pcategoryshortcode);
								add_post_meta($post_id, 'category_slug', $pcategoryslug);
								add_post_meta( $post_id, 'canonical_url', $canonical_url );
								if($feat_ured == 'Full API Key'){
									add_post_meta($post_id, '_thumbnail_ext_url', $pimgpath );
									add_post_meta($post_id, '_thumbnail_id', $post_thumbnail_id );
								}
								add_post_meta($post_id, 'current_mon', $nxtMonths); //$curdate
								if(!empty($pimgpath)) {
	           						$this->generate_featured_image_by_url($pimgpath,$post_id);
	           					}
                 			}
					}
					else{
                    	global $user_ID, $wpdb;
              			$new_date = date('Y-m-d H:i:s', strtotime($pdate)+ $tm);
                		$query = $wpdb->prepare('SELECT ID FROM ' . $wpdb->posts . ' WHERE post_title = %s AND post_type = \''.self::POST_TYPE.'\'', $ptitle);
						$wpdb->query( $query );
            			if ( $wpdb->num_rows ) 
						{
							$post_id = $wpdb->get_var( $query );
							$meta = get_post_meta( $post_id, 'times', TRUE );
							$meta++;
							update_post_meta( $post_id, 'times', $meta );
							update_post_meta( $post_id, 'meta_keywords', $pmetakeywords );
							update_post_meta( $post_id, 'meta_description', $pmetadescription );
							update_post_meta( $post_id, 'post_start_date', $pstartdate );
							update_post_meta( $post_id, 'category_shortcode', $pcategoryshortcode );
							update_post_meta( $post_id, 'category_slug', $pcategoryslug );
							update_post_meta( $post_id, 'canonical_url', $canonical_url );
							if($feat_ured == 'Full API Key'){
								update_post_meta( $post_id, '_thumbnail_ext_url', $pimgpath );
								//update_post_meta( $post_id, '_thumbnail_id', 'url');
							}
							
							update_post_meta( $post_id, 'current_mon', $nxtMonths ); //$curdate
            			}
						else{
							$args =  array(
											'post_author'       => $post_author,
											'post_name'         => $ptitle,
											'post_title'        => $ptitle,
											'post_content'      => $pcontent,
											'post_date'         => $new_date,
											'meta_keywords'     => $pmetakeywords,
											'meta_description'  => $pmetadescription,
											'post_start_date'   => $pstartdate,
											'category_shortcode'=> $pcategoryshortcode,
											'post_category' 	=> array($pcategory,'Blog'),
											'post_status'       => 'publish',
											'post_type'         => self::POST_TYPE,
							 			);
							// 'featured_img'		=>    $pimgpath, removed
							//in place of post_category line this was used--> 'category_slug'		=>    $pcategoryslug,
							$post_id = wp_insert_post( $args );
							$this->set_custom_post_term($post_id, $pcategory,self::POST_TAXONOMY);
							$this->set_custom_post_term($post_id, 'Blog',self::POST_TAXONOMY);
							add_post_meta($post_id, 'times', '1');
							add_post_meta($post_id, 'meta_keywords', $pmetakeywords);
							add_post_meta($post_id, 'meta_description', $pmetadescription);
							add_post_meta($post_id, 'post_start_date', $pstartdate);
							add_post_meta($post_id, 'category_shortcode', $pcategoryshortcode);
							add_post_meta($post_id, 'category_slug', $pcategoryslug);
							add_post_meta( $post_id, 'canonical_url', $canonical_url );
							if($feat_ured == 'Full API Key'){
								add_post_meta($post_id, '_thumbnail_ext_url', $pimgpath );
								add_post_meta($post_id, '_thumbnail_id', $post_thumbnail_id );
							}
							add_post_meta($post_id, 'current_mon', $nxtMonths); //$curdate
							if(!empty($pimgpath)) {
						   		$this->generate_featured_image_by_url($pimgpath,$post_id);
					   		}
                 		}
            		}
            	$tm++;
            	}
         	}
         	update_option( 'current_mon', $curdate );
    	}
		/**
		 * Call api to get results.  
		 * @param    All DCY post to which we're adding the post.
	     * @access   public
		 * @since    1.0.0
		*/ 
		public function return_result(){
			$apikey = get_option('api_key'); 
			$ch = curl_init();
			$referer = $_SERVER['SERVER_NAME'];
			curl_setopt($ch, CURLOPT_URL, 'https://www.dynamicontent.net/api/getmonthlyarticles.php');
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,'dckey='.$apikey);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			@curl_setopt($ch, CURLOPT_REFERER, $_SERVER['HTTP_ORIGIN']);
			curl_setopt($ch, CURLOPT_REFERER, $referer);
			$json = curl_exec($ch);
            $info = curl_getinfo($ch);
            $response_time = "${info['total_time']}";
			$data = json_decode($json);
			$error = $data->error;
			if($error){
				return [];
			}
			$i=0;	
        
			$dp = [];


			foreach($data->articlelist as $k =>$val){
				foreach($data->shortcodelist as $key =>$value){				
					if(strtolower(str_replace(' ','_',$value->category)) == strtolower(str_replace(' ','_',$val->category))){
						@$data->articlelist[$i]->catstartdate =$value->startdate;
						$data->articlelist[$i]->catshortcode = $value->shortcode;
						$data->articlelist[$i]->catslug = $value->slug;
						$i++;
						$dp[$val->category][]=$val;
					}
				}
			}
			$data->articlelistnewarray = $dp;
                if( $response_time >60){
                    $data = new stdClass();
                    $data->timeout_message = "Sorry you are having trouble, something on your hosting server is blocking connection to <a href='https://dynamicontent.net/'>dynamicontent.net</a>, please get in touch with your webmaster/hosting company and ask them if they can whitelist <a href='https://dynamicontent.net/'>dynamicontent.net</a> and its IP address";
                }
                else{
                    $data = $data;
                }
                return $data;
    	}
		/**
		 * Appends the specified taxonomy term to the incoming post object. If 
		 * the term doesn't already exist in the database, it will be created.
		 *
		 * @param    WP_Post    $post        The post to which we're adding the taxonomy term.
		 * @param    string     $value       The name of the taxonomy term
		 * @param    string     $taxonomy    The name of the taxonomy.
		 * @access   private
		 * @since    1.0.0
		*/
		private function set_custom_post_term( $post, $value, $taxonomy ) {
    		$term = term_exists( $value, $taxonomy );
    		//If the taxonomy doesn't exist, then we create it
    		if ( 0 === $term || null === $term ) 
			{
    			$term = wp_insert_term($value,$taxonomy,array('slug' => strtolower( str_ireplace( ' ', '-', $value ) ) ) );
			}
    		//Then we can set the taxonomy
    		wp_set_post_terms( $post, $term, $taxonomy, true );
		}
		/**
		 * Generate fetured image by url 
		 * the term doesn't already exist in the database, it will be created.
		 *
		 * @param    WP_Post    $post        The post to which we're adding the thumbnail.
		 * @access   private
		 * @since    1.0.0
		 * @param    Dev Rakesh
		*/ 
		private function generate_featured_image_by_url($image_url, $post_id){
			$upload_dir = wp_upload_dir();
			$image_data = file_get_contents($image_url);
			$filename = basename($image_url);
    		if(wp_mkdir_p($upload_dir['path'])){
				$file = $upload_dir['path'] . '/' . $filename;
			}else{
				$file = $upload_dir['basedir'] . '/' . $filename;
			}
    		file_put_contents($file, $image_data);
    		$wp_filetype = wp_check_filetype($filename, null );
    		$attachment = array(
								'post_title' => '',
								'post_content' => '',
    							'post_status' => 'inherit',
								'post_mime_type' => $wp_filetype['type'],
								'guid' => $image_url,
								'post_author' => 77777
								
							);
			$attach_id = wp_insert_attachment( $attachment, $image_url, $post_id );
			require_once(ABSPATH . 'wp-admin/includes/image.php');
			$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
			$res1= wp_update_attachment_metadata( $attach_id, $attach_data );
			$res2= set_post_thumbnail( $post_id, $attach_id );
			   return $attach_id;
		}


        public function api_call(){
            $this->insert_dynamic_posts($_POST);   
            echo 'done';
            wp_die();
        }
        public function check_api_type(){   

			$dyc_data = $this->return_result();
			$key = trim($dyc_data->message);
			if( $dyc_data->error == 1)
			{
				$keytype= "Invalid API Key / API Key not found"; 
				update_option( 'dyc_api_type', 'invalid' );
			}
			else if($key == 'Valid Licensed API Key; Articles found' )
			{
				$keytype = 'Full API Key';  
				update_option( 'dyc_api_type', 'full' );
			}
			else if($key == 'Free API Key Articles' )
			{
				$keytype = 'Free API Key';  
				update_option( 'dyc_api_type', 'free' );
			}
			echo $keytype;  
			wp_die();
        }
        public function get_api_type(){       
			$dyc_data = $this->return_result();
			$key = trim($dyc_data->message);
			if( $dyc_data->error == 1)
			{
				$keytype= "Invalid API Key / API Key not found"; 
			}
			else if($key == 'Valid Licensed API Key; Articles found' )
			{
				$keytype = 'Full API Key';  
			}
			else if($key == 'Free API Key Articles' )
			{
				$keytype = 'Free API Key';  
			}
			return $keytype;  
        }
    } 
	// END class Post_Type_Template
} 
// END if(!class_exists('Post_Type_Template'))
//wordprax codes

add_filter('get_attached_file', 'replace_attached_file', 10, 2);
add_filter('wp_get_attachment_url', 'replace_attachment_url', 10, 2);
add_filter('posts_where', 'query_attachments');
add_filter('wp_get_attachment_image_src', 'replace_attachment_image_src', 10, 3);

	function replace_attached_file($att_url, $att_id) {
		return process_url($att_url, $att_id);
	}

	function process_url($att_url, $att_id) {
		if (!$att_id)
			return $att_url;
		$att_post = get_post($att_id);
		if (!$att_post)
			return $att_url;
		if ($att_post->post_author != Service )
			return $att_url;
		$url = $att_post->guid; 
		return process_external_url($url, $att_id);
	}

	function process_external_url($url, $att_id) {
		return add_url_parameters($url, $att_id);
	}

	function replace_attachment_url($att_url, $att_id) {
		if ($att_url)
			return process_url($att_url, $att_id);
		return $att_url;
	}


	function query_attachments($where) { //to prevant it store in media
		global $wpdb;
		if (isset($_POST['action']) && ($_POST['action'] == 'query-attachments') && true) {
			$where .= ' AND ' . $wpdb->prefix . 'posts.post_author <> ' . Service . ' ';
		} else
			$where .= ' AND (' . $wpdb->prefix . 'posts.post_author <> ' . Service . ' OR  (' . $wpdb->prefix . 'posts.post_author = ' . Service . ' AND EXISTS (SELECT 1 FROM ' . $wpdb->prefix . 'postmeta WHERE ' . $wpdb->prefix . 'postmeta.post_id = ' . $wpdb->prefix . 'posts.id AND ' . $wpdb->prefix . 'postmeta.meta_key = "_wp_attachment_metadata")))';
		return $where;
	}


	function replace_attachment_image_src($image, $att_id, $size) {
		if (!$image || !$att_id)
			return $image;
		$att_post = get_post($att_id);
		
		if (!$att_post)
			return $image;
		if ($att_post->post_author != Service)
			return $image;
		$image[0] = process_url($image[0], $att_id);
		global $post;
		if ($image[1] > 1 && $image[2] > 1)
			return $image;
		if ($image[2] == null)
			$image[2] = 0;

	}


	function add_url_parameters($url, $att_id) {
		$post_id = get_post($att_id)->post_parent;

		if (!$post_id)
			return $url;
		$post_thumbnail_id = get_post_thumbnail_id($post_id);
		$post_thumbnail_id = $post_thumbnail_id ? $post_thumbnail_id : get_term_meta($post_id, 'thumbnail_id', true);
		$featured = $post_thumbnail_id == $att_id ? 1 : 0;

		if (!$featured)
			return $url;
		if (isset($_POST[$url]))
			return $url;

		$parameters = array();
		$parameters['att_id'] = $att_id;
		$parameters['post_id'] = $post_id;
		$parameters['featured'] = $featured;

		$_POST[$url] = $parameters;
		return $url;
	}



function wordprax_admin_head_data() {
	$class = new Post_Type_Dynamic_Post;
	$api_value = $class->get_api_type();
	$auto_up = get_option('auto_up');
	if($auto_up == 1){
		if(date('d') > '2'){
			if($api_value == 'Free API Key')
			{
				global $wpdb;
				$saved_date = get_option('current_mon'); 
			   	$saved_cats = get_option('saved_cats');
				$curdate = date('Y-m');
				if($saved_date != $curdate)
				{
					//update_option( 'current_mon', $curdate );
					if($_GET['page'] != 'dynamic_post')
					{
						wp_enqueue_style('bootstrap', PLUGIN_PATH_DP . 'assets/css/bootstrap.css');
						wp_enqueue_script('bootstrap', PLUGIN_PATH_DP . 'assets/js/bootstrap.js');
					}
					$prfx = $wpdb->prefix;
					$wpdb->query("DELETE wp FROM ".$prfx."posts wp LEFT JOIN ".$prfx."postmeta pm ON pm.post_id = wp.ID WHERE pm.meta_key = 'current_mon' AND pm.meta_value != $curdate;");
			        foreach($saved_cats[0] as $key)
			            { 
			            	?>
			                <label style="display:none;" class="custom-control custom-checkbox">
			                    <input type="checkbox" name="<?php echo $key; ?>" class="catsall custom-control-input">
			                    <span class="custom-control-indicator"></span><?php echo $key; ?>
			                </label>
			                <?php
			            }
					?>
					  <div class="modal fade" id="myModal" role="dialog">
					    <div class="modal-dialog">
					      <div class="modal-content">
					        <div class="modal-header">
					          <h4 style="color:green" class="modal-title"><img src="<?php echo plugins_url( 'assets/dp-logo.png', dirname(__FILE__) );?> "></h4>
					        </div>
					        <div class="modal-body">
					          <p style="color:green">Please wait a moment while we update the posts for the month. The Page will reload once the update is complete. If the Page gets stuck, go to the Dynamic Post settings and manually click the "Post Articles" button.</p>
					        </div>
					      </div>
					    </div>
					  </div>
					  <script type="text/javascript">
					  	jQuery(document).ready(function(){
					  		jQuery('.mybtn').click();
							var catname = [];
				            jQuery('.catsall').each(function(i,v)
				            {
				                catname.push(v.name);
				            });
					  		jQuery.ajax({
			                    type: 'POST',
			                    url: "<?php echo esc_url( home_url() ) ?>/wp-admin/admin-ajax.php",
			                    data : {
			                    			data_catgname : catname,
			                                action : 'api_call',
			                           },
			                    success: function(data)
			                    {
			                        window.location.reload();
			                    }
			                });
					  	});
					  </script>
					<button style="display: none;" type="button" class="btn btn-info btn-lg mybtn" data-toggle="modal" data-target="#myModal">Open Modal</button>
					<?php
				}
			}
			elseif($api_value == 'Full API Key')
			{
				global $wpdb;
				$saved_date = get_option('current_mon'); 
				$saved_cats = get_option('saved_cats');
				$curdate = date('Y-m');
				if($saved_date != $curdate)
				{
					//update_option( 'current_mon', $curdate );
					if($_GET['page'] != 'dynamic_post')
					{
						wp_enqueue_style('bootstrap', PLUGIN_PATH_DP . 'assets/css/bootstrap.css');
						wp_enqueue_script('bootstrap', PLUGIN_PATH_DP . 'assets/js/bootstrap.js');
					}
			        foreach($saved_cats[0] as $key)
			            { 
			            	?>
			                <label style="display:none;" class="custom-control custom-checkbox">
			                    <input type="checkbox" name="<?php echo $key; ?>" class="catsall custom-control-input">
			                    <span class="custom-control-indicator"></span><?php echo $key; ?>
			                </label>
			                <?php
			            }
					?>
					  <div class="modal fade" id="myModal" role="dialog">
					    <div class="modal-dialog">
					      <div class="modal-content">
					        <div class="modal-header">
					          <h4 style="color:green" class="modal-title"><img src="<?php echo plugins_url( 'assets/dp-logo.png', dirname(__FILE__) );?> "></h4>
					        </div>
					        <div class="modal-body">
					          <p style="color:green">Please wait a moment while we update the posts for the month. The Page will reload once the update is complete. If the Page gets stuck, go to the Dynamic Post settings and manually click the "Post Articles" button.</p>
					        </div>
					      </div>
					    </div>
					  </div>
					  <script type="text/javascript">
					  	jQuery(document).ready(function(){
					  		jQuery('.mybtn').click();
							var catname = [];
				            jQuery('.catsall').each(function(i,v)
				            {
				                catname.push(v.name);
				            });
					  		jQuery.ajax({
			                    type: 'POST',
			                    url: "<?php echo esc_url( home_url() ) ?>/wp-admin/admin-ajax.php",
			                    data : {
			                    			data_catgname : catname,
			                                action : 'api_call',
			                           },
			                    success: function(data)
			                    {
			                        window.location.reload();
			                    }
			                });
					  	});
					  </script>
					<button style="display: none;" type="button" class="btn btn-info btn-lg mybtn" data-toggle="modal" data-target="#myModal">Open Modal</button>
					<?php
				}
			}
		}
	}
}
add_action('admin_head', 'wordprax_admin_head_data');
add_action('wp_head', 'wordprax_admin_head_data');
$class = new Post_Type_Dynamic_Post;
$api_value = $class->get_api_type();
if($api_value == 'Full API Key'){
	function url_is_image($url){
	    if (! filter_var($url, FILTER_VALIDATE_URL)){
	        return FALSE;
	    }
	    $ext = array( 'jpeg', 'jpg', 'gif', 'png' );
	    $info = (array) pathinfo( parse_url( $url, PHP_URL_PATH ) );
	    return isset( $info['extension'] )
	        && in_array( strtolower( $info['extension'] ), $ext, TRUE );
	}
	add_filter( 'admin_post_thumbnail_html', 'thumbnail_url_field' );
	add_action( 'save_post', 'thumbnail_url_field_save', 10, 2 );
	add_filter( 'post_thumbnail_html', 'thumbnail_external_replace', 20, 5 );
	function thumbnail_url_field( $html ) {
	    global $post;
	    $value = get_post_meta( $post->ID, '_thumbnail_ext_url', TRUE ) ? : "";
	    $nonce = wp_create_nonce( 'thumbnail_ext_url_' . $post->ID . get_current_blog_id() );
	    $html .= '<input type="hidden" name="thumbnail_ext_url_nonce" value="' 
	        . esc_attr( $nonce ) . '">';
	    $html .= '<div><p>' . __('Or', 'txtdomain') . '</p>';
	    $html .= '<p>' . __( 'Enter the url for external image', 'txtdomain' ) . '</p>';
	    $html .= '<p><input type="url" name="thumbnail_ext_url" value="' . $value . '"></p>';
	    if ( ! empty($value) && url_is_image( $value ) ) {
	        $html .= '<p><img style="max-width:150px;height:auto;" src="' 
	            . esc_url($value) . '"></p>';
	        $html .= '<p>' . __( 'Leave url blank to remove.', 'txtdomain' ) . '</p>';
	    }
	    $html .= '</div>';
	    return $html;
	}
	function thumbnail_url_field_save( $pid, $post ) {

	    $cap = $post->post_type === 'page' ? 'edit_page' : 'edit_post';
	    if (
	        ! current_user_can( $cap, $pid )
	        || ! post_type_supports( $post->post_type, 'thumbnail' )
	        || defined( 'DOING_AUTOSAVE' )
	    ) {
	        return;
	    }
	    $action = 'thumbnail_ext_url_' . $pid . get_current_blog_id();
	    $nonce = filter_input( INPUT_POST, 'thumbnail_ext_url_nonce', FILTER_SANITIZE_STRING );
	     $url = filter_input( INPUT_POST,  'thumbnail_ext_url', FILTER_VALIDATE_URL );
	    if (
	        empty( $nonce )
	        || ! wp_verify_nonce( $nonce, $action )
	        || ( ! empty( $url ) && ! url_is_image( $url ) )
	    ) {
	        return;
	    }
	    if ( ! empty( $url ) ) {
	        update_post_meta( $pid, '_thumbnail_ext_url', esc_url($url) );
	        if ( ! get_post_meta( $pid, '_thumbnail_id', TRUE ) ) {
	            update_post_meta( $pid, '_thumbnail_id', 'by_url' );
	        }
			  $post_thumbnail_id = get_post_meta( $pid, '_thumbnail_id', TRUE);
			  update_post_meta($post_thumbnail_id, '_wp_attached_file', esc_url($url) );
			  global $wpdb;
			  $wpdb->update($wpdb->posts, ['guid' => esc_url($url)], ['ID' => $post_thumbnail_id ]);

	    } elseif ( get_post_meta( $pid, '_thumbnail_ext_url', TRUE ) ) {
	        delete_post_meta( $pid, '_thumbnail_ext_url' );
	        if ( get_post_meta( $pid, '_thumbnail_id', TRUE ) === 'by_url' ) {
	            delete_post_meta( $pid, '_thumbnail_id' );
	        }
	    }
	}
	function thumbnail_external_replace( $html, $post_id, $post_thumbnail_id, $size, $attr ) {
        $url =  get_post_meta( $post_id, '_thumbnail_ext_url', TRUE );
        if(empty($url)){
          $url = get_the_post_thumbnail_url($post_id, 'full');
        }
        if ( 'post' === get_post_type( $post_id ) ){
            $class_nnnew = new Post_Type_Dynamic_Post;
            $value_api = $class_nnnew->get_api_type();
            if($value_api == 'Full API Key'){
                $hide_feature = get_option( 'feat_ured2' );
                if($hide_feature == 1){
                    $html = sprintf('<img class="dc_img_fea" style="margin: 0 auto;" src="%s"', esc_url($url));
                            // $html = sprintf( '<img class="dc_img_fea" src="%s"', esc_url($url) );
                            foreach ( $attr as $name => $value ) {
                                $html .= " $name=" . '"' . $value . '"';
                            }
                    return $html;
                }else{
                    $html = "";
                    return $html;
                }

            }else{
                $html = "";
                return $html;
            }
        }else{
            return $html; 
        }
		
	}
	/*external url code ends*/

	function add_responsive_class($content){
			global $post;
			if($post->post_type=='post'){
		    $hide_images = get_option( 'hide_images' );
		    $hide_post_content_thumbnail = get_option( 'feat_ured' );
				 if( $hide_images==1 && !is_single()){
					$content = preg_replace( '/display\h*:\h*none\h*;?/', "", $content );
					return $content;
				 }elseif($hide_post_content_thumbnail==1 && is_single() && 'post'== get_post_type() ){
					$content = preg_replace( '/display\h*:\h*none\h*;?/', "", $content,1);
					return $content;
				 }elseif($hide_post_content_thumbnail!=1 && is_single() && 'post'== get_post_type() ){
                    $content = preg_replace("/<img[^>]+\>/i", "", $content, 1);
                    return $content;
                 }
			}else{
				return $content;
			}	
	}
	add_filter('the_content', 'add_responsive_class',100);

}


add_action( 'wp_head', 'wordprax_show_contentimage' );
function wordprax_show_contentimage(){
$class_nnnew = new Post_Type_Dynamic_Post;
$value_api = $class_nnnew->get_api_type();
if($value_api == 'Full API Key'){
	global $post;
	$hide_images = get_option( 'hide_images' );
	
	if($hide_images==1 && is_singular('page')){ ?>
			<style>
				.has-post-thumbnail img,.post-thumbnail img{display:block!important;}
			</style> 
	<?php } elseif($hide_images!==1 && is_singular('page')) { ?>
			<style>
			.has-post-thumbnail img,.post-thumbnail img{display:none!important;}
		</style> 
		<?php	
	   }else{}

	$hide_post_content_thumbnail = get_option( 'feat_ured' );
	  if($hide_post_content_thumbnail==1){ ?>
			<style>
				body.archive.category img.S2CDCImage{
					width: auto;
				}
			</style> 
	  <?php }else{ ?>
		<style>
				body.archive.category img.S2CDCImage{
					width: 0!important;
					height:0!important;
					display:none!important;
				}
			</style> 
			  <script type="text/javascript">
			  		jQuery('body.archive.category img.S2CDCImage').remove();
			  </script>
	  <?php }	
		
   }
 }