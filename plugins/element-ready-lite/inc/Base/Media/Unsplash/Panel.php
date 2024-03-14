<?php

namespace Element_Ready\Base\Media\Unsplash;
use Element_Ready\Base\Media\Unsplash\Er_Unsplash;

class Panel { 
    
    /**
     * Bootstrap code from here
     * @version 1.1
     */
    public function register(){
        
        if( ! $this->element_ready_get_modules_option( 'media_unsplash' ) ){
          return; 
        }

        add_filter( 'element_ready/dashboard/api-data', [ $this,'add_option' ] );
        add_action( 'admin_enqueue_scripts', [ $this,'add_script' ] );
        add_action( 'wp_enqueue_scripts', [ $this,'add_script' ] );
        add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] );
        add_action( 'elementor/editor/footer', [ $this , 'render_template' ] );
        add_action( 'admin_footer', [ $this , 'render_template' ] );
        add_action( 'wp_ajax_element_ready_get_unsplash', [$this,'unsplash_handler'] );
        add_action( 'wp_ajax_save_er_unsplash_media', [$this,'unsplash_save_handler'] );
    }
    
    public function add_option($option){

        $option['media_unsplash_api_key'] = [
            'demo_link' => 'https://unsplash.com/developers',
            'lavel'     => esc_html__('Unsplash','element-ready-lite'),
            'default'   => '',
            'type'      => 'text',
            'is_pro'    => 0,
        ];

        return $option;
    }
    public function unsplash_save_handler()
	{
	
        $post_id = sanitize_text_field( isset($_POST['post_id']) ?$_POST['post_id']:0 );
        $image   = sanitize_text_field($_POST['image']);
        $src     = sanitize_url($_POST['src']);
        if ( $image ) {

    		try {

                if ( $src == 'unsplash' ) {
                    $response = wp_remote_get($image, array( 'timeout' => 180 ) );
                    if( !is_wp_error( $response ) ){

                           $bits             = wp_remote_retrieve_body( $response );
                           $filename         = strtotime('now').'_'.uniqid().'.jpg';
                           $upload           = wp_upload_bits( $filename, null, $bits );
                           $file_type        = $upload['type'];
                         
                           $attachment = array(
                                'post_mime_type' => $file_type,
                                'post_title'     => preg_replace( '/\.[^.]+$/' , '' , $filename ),
                                'post_content'   => '',
                                'post_status'    => 'inherit',
                                'guid'           => esc_url_raw( $upload[ 'url' ] . "/" . $filename ),
                           );
                    }
                }

                $uploads          = wp_upload_dir();
                $filename         = wp_unique_filename( $uploads['path'], $filename, null );
                $fullpathfilename = $uploads['path'] . "/" . $filename;
                $wp_filetype      = wp_check_filetype($filename, null);
               
                $attach_id = wp_insert_attachment( $attachment, $upload['file'], $post_id );
				
				if (!$attach_id) {
					throw new \Exception("Failed to save image into db.");
				}
				
                $attach_data = wp_generate_attachment_metadata($attach_id, $upload['file']);
              	wp_update_attachment_metadata($attach_id, $attach_data);
				// Local URL
				$localUrl = $uploads['baseurl'] . '/' . $attach_data['file'];
                
				$data = array(
					"status" => true,
					"category" => "photos",
					"photoId" => "7QSfdghPL8V",
					"attachmentData" => [
					  "id"            => esc_attr($attach_id),
					  "title"         => "Element Ready sit amet",
					  "filename"      => "element-ready-ipsum-dolor-sit-amet.jpg",
					  "url"           => esc_url_raw($localUrl),
					  "link"          => "#",
					  "alt"           => "Element ready amet",
					  "author"        => "1",
					  "description"   => "Lorem ipsum dolor sit amet, ipsum dolor",
					  "caption"       => "Lorem ipsum dolor sit amet, ipsum dolor",
					  "name"          => "lorem-ipsum-dolor-sit-amet",
					  "mime"          => "image/jpeg",
					  "type"          => "image",
					  "subtype"       => "jpeg",
					  "dateFormatted" => "June 24, 2021",
					]
                );
                
                wp_send_json_success($data);

				wp_die();

			} catch (\Exception $e) {

                wp_send_json_error($e->getMessage());
			
			}
		}
	}
    function unsplash_handler(){

        $q = sanitize_text_field( $_POST[ 'q' ] );
        
        $image = new Er_Unsplash();
        $page_r = (int) sanitize_text_field( isset($_POST['page']) ?$_POST['page']:1 );  
        if ( isset($_POST['type']) && $_POST['type'] == 'list' ) {
            wp_send_json_success( $image->list_photos($page_r,30) );
        }elseif( isset( $_POST[ 'q' ] ) && isset( $_POST[ 'type' ] ) && $_POST[ 'type' ] == 'search' ){
            wp_send_json_success( $image->search($q,$page_r) );     
        }elseif( isset($_POST[ 'id' ] ) && isset( $_POST[ 'type' ] ) && $_POST[ 'type' ] == 'single' ){
            wp_send_json_success( $image->get_photo($q) );     
        }else{
            wp_send_json_success( $image->list_photos() );  
        }

    }
    function add_script($handle) {
        
        if( is_admin() &&'nav-menus.php' == $handle ){
            return;
        }

        global $post;
      
        wp_enqueue_script( 'element-ready-admin', ELEMENT_READY_ROOT_JS . 'admin'.ELEMENT_READY_SCRIPT_VAR.'js', [ 'jquery','wp-util' ], '', true );
        wp_localize_script( 'element-ready-admin', 'ermedia', array(
            'ajaxurl' => esc_url_raw( admin_url( 'admin-ajax.php' ) ),
            'post_id' => $post
        ) );  

    }
    function element_ready_get_modules_option($key = false){
            
        $option = get_option('element_ready_modules');
        if($option == false){
            return false;
        }
        return isset($option[$key]) && $option[$key] == 'on'? true: false;
    } 
    function enqueue_editor_scripts(){
      
        wp_enqueue_script( 'element-ready-admin', ELEMENT_READY_ROOT_JS . 'admin'.ELEMENT_READY_SCRIPT_VAR.'js', [ 'jquery','wp-util' ], '', true );    
        wp_localize_script( 'element-ready-admin', 'ermedia', array(
           'ajaxurl' => esc_url( admin_url( 'admin-ajax.php' ) )
        ) );  

    }

    public function get_image_sizes(){

        $imageSizes = [
            'cdn' => esc_html__('Direct Link', 'element-ready-lite' ),
            'download' => esc_html__( 'Download' , 'element-ready-lite') 
        ];

	    return $imageSizes;
    }

    public function render_template() {
 		?>
  	    <script type="text/template" id="tmpl-element-ready-pro-gallary-unsplash-header">
			<div id="element-ready-pro-unsplash-gl" class="element-ready-pro-unsplash-gl-header">
			   <h5><?php echo esc_html__('Unsplash Images','element-ready-lite'); ?> </h5>  
			</div>
        </script>
        <script type="text/template" id="tmpl-element-ready-pro-gallary-unsplash-header-message">
			<div id="element-ready-pro-unsplash-gl-message" class="element-ready-pro-unsplash-gl-header-message">
			   <h3 class="mnessage"></h3>  
			</div>
        </script>
        <script type="text/template" id="tmpl-element-ready-pro-gallary-unsplash-single-image">
            <div class="element-ready-unsplash-back-container">
                <button class="element-ready-unsplash-back-btn"> <span class="dashicons dashicons-controls-back"></span> <?php echo esc_html__('Back','element-ready-lite'); ?> </button>
            </div> 
			<div id="element-ready-pro-unsplash-gl-single" class="element-ready-pro-unsplash-gl-container">
      		     <div class="element-ready-unsplash-single-image"><img src="{{ data.image.urls.full }}" /> </div>
			     <div class="element-ready-unsplash-single-image-tools">
                     <h2 class="element-ready-unsplash-single-image-header"><?php echo esc_html__('Image Details','element-ready-lite'); ?> </h2>
                     <div class="element-ready-unsplash-image-tools">
                         <h4 class="element-ready-unsplash-image-desc"><?php echo esc_html__('Title:','element-ready-lite'); ?> {{ data.image.alt_description }} </h4>
                         <# if(data.image.categories){ #>
                            <# if(data.image.categories.length){ #>
                                <p class="unsplash-image"> {{ data.image.categories.join(",") }} </p>
                            <# } #>
                         <# } #>
                        <h5 class="element-ready-unsplash-image-choose"><?php echo esc_html__('Choose Type','element-ready-lite'); ?></h5>
                        <select class="er-unsplash-image-size">
                            <?php foreach($this->get_image_sizes() as $k=> $title){ ?>
                                <option value="<?php echo esc_attr($k); ?>"><?php echo esc_html($title); ?></option>
                            <?php } ?>
                        </select>
                        <button data-src="{{ data.image.urls.full }}" class="er-unsplash-insert-button button button-primary button-large"><span class="dashicons dashicons-download"></span> <?php echo esc_html__('Insert Image','element-ready-lite'); ?> </button>
                        <div style="display:none" class="er-loader-img">
                            <span class="er-loader-status"> </span>  
                            <img src="<?php echo esc_url(ELEMENT_READY_ROOT_IMG).'loading.gif'; ?>" /> 
                        </div>
                      </div>
                  </div>
			</div>
        </script>
        <script type="text/template" id="tmpl-element-ready-pro-gallary-home">
            <div class="element-ready-gallery">
                <div class="element-ready-templates-modal-body-inner element0ready-templates-modal-body-header">
                    <div class="element-ready-body-header-search">
                        <div class="er-unsplash-search-column">
                            <input class="er-unsplash-search" type="text" value="" />
                            <button id="er-unsplash-search-ubtn-" class="er-unsplash-search-btn"> 
                            <span class="dashicons dashicons-search"></span>
                            </button> 
                        </div>
                        <div class="er-unsplash-page-col">
                            <a data-page="1" id="er-pro-unsplash-next-ubtn-" class="er-pro-unsplash-next-btn button"> 
                                <?php echo esc_html__('Next','element-ready-lite'); ?>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="element-ready-image-list">
                    <# if(data.image != false) { #>
                        <# _.each( data.images, function( image ){ #>
                            <div class="element-ready-unsplash-remote-image">
                                <img data-id="{{ image.id }}" src="{{ image.urls.thumb }}" /> 
                            </div>
                        <# }); #> 
                    <# }else { #>
                            <div class="element-ready-unsplash-remote-image">
                                 <div class="unplash-image-not-found"> <?php echo esc_html__('Image not found . May be you did not configure unsplash Api from dashboard','element-ready-lite'); ?> </div> 
                            </div>
                    <# }; #> 
                </div>
            </div>

        </script>
        <style>

            .element-ready-gallery .element-ready-image-list {
                display: flex;
                flex-wrap: wrap;
            }

            .element-ready-gallery .element-ready-image-list .element-ready-unsplash-remote-image{
                position: relative;
                height: 200px;
                width: 9.4%;
                z-index: 10;
                overflow: hidden;
                margin: 5px;
            }
            @media only screen and (min-width: 992px) and (max-width: 1600px) {
                .element-ready-gallery .element-ready-image-list .element-ready-unsplash-remote-image{
                    width: 18.4%;
                }
            }
            .element-ready-gallery .element-ready-image-list .element-ready-unsplash-remote-image::before{
                position: absolute;
                content: '';
                left: 0;
                top: 0;
                height: 100%;
                width: 100%;
                background-color: rgba(0, 0, 0, 0.7);
                opacity: 0;
                cursor: pointer;
                transition: all linear 0.4s;
                z-index: 9;
            }
            .er-unsplash-image-size{
                margin-top : 20px;
                display: none;
            }
            .element-ready-unsplash-image-choose{
                display: none; 
            }
            .element-ready-gallery .element-ready-image-list .element-ready-unsplash-remote-image::after{
                position: absolute;
                content: "\f543";
                left: 50%;
                top: 50%;
                transform: translate(-50%, -50%);
                color: #fff;
                font-size: 30px;
                font-family: dashicons;
                opacity: 0;
                transition: all linear 0.4s;
                cursor: pointer;
                z-index: 10;
            }

            .element-ready-gallery .element-ready-image-list .element-ready-unsplash-remote-image:hover::before,
            .element-ready-gallery .element-ready-image-list .element-ready-unsplash-remote-image:hover::after{
                opacity: 1;
            }

            .element-ready-gallery .element-ready-image-list .element-ready-unsplash-remote-image img{
                position: absolute;
                left: 0%;
                top: 0%;
                width: 100%;
                height: 100%;
            }
           .unplash-image-not-found{
                margin: 20px 20px;
                font-size:26px;
                text-align:center;

             }
             .er-unsplash-search-column{
                 width : 100%
             }
            .er-loader-img{
                margin-top:20px;
            }
            .er-loader-img span{
                font-size:16px;
                display:block;
                padding:10px 0;
            }
            .element-ready-unsplash-image-desc{
                font-size:18px;
                padding: 15px 0;
            }
            .element-ready-image-list{
                position:relative;
            }

            .element-ready-unsplash-remote-image{
                position: relative;
                display: inline-block;
            }

           .element-ready-image-list img{
               display:inline-block;
              
           }
           .element-ready-unsplash-single-image img{
               width:96%;
               border:5px solid #03a9f4;
               border-radius: 10px;
           }
           .element-ready-pro-unsplash-gl-container{
               display: flex;
               padding: 15px;
           }
           .element-ready-unsplash-single-image-header{
               padding: 10px 0;
               border-bottom:1px solid #e1e1e1;
           }
           .element-ready-pro-unsplash-gl-container .element-ready-unsplash-single-image{
               width: 75%;
            }

            .element-ready-pro-unsplash-gl-container .element-ready-unsplash-single-image-tools{
                width :25%
            }
     
           .element-ready-unsplash-image-choose{
               font-size:18px;
           }
           .er-unsplash-insert-button{
            display:block !important;
            margin-top:20px !important;
            line-height: 19px !important;
           }
           .er-unsplash-page-col .er-pro-unsplash-next-btn {
            background: #fff;
            padding: 0px 45px;
            line-height: 50px;
            color: #000000;
            font-size: 16px;
            font-weight: 500;
           }
           .element-ready-body-header-search{
               padding:20px;
               display:flex;
               justify-content: space-between;
               align-items: center;
               background:#3467ff
           }

           .element-ready-body-header-search .er-unsplash-search{
                width: 30%;
                height: 50px;
                border-radius: 5px;
                border: 1px solid rgba(0, 0, 0, 0.2);
                font-size:15px;
                padding: 0 20px !important;
                height: 50px;
           }

           .element-ready-body-header-search .er-unsplash-search-btn{
                background: #ffffff;
                line-height: 50px;
                padding: 0px 30px;
                border-radius: 5px;
                color: #3467ff;
                display:inline-block;
                font-weight: 500;
                align-items: center;
                border: 0;
                height: auto;
                margin-left: 10px;
                transition: 1s;
           }
           .element-ready-unsplash-back-container{
               margin-top:10px;
           }
           .element-ready-unsplash-back-container .element-ready-unsplash-back-btn {
                background: #2196f3;
                line-height: 34px;
                padding: 0 15px;
                border-radius: 5px;
                color: #fff;
                font-weight: 500;
                display: flex;
                align-items: center;
                border: 0;
                height: auto;
                margin-left: 10px;
                transition: 1s;
                font-size: 13px;
                cursor: pointer;
            }
           .element-ready-unsplash-back-container .element-ready-unsplash-back-btn span{
               font-size:23px;
               margin-right: 10px;
           }
           .element-ready-body-header-search .er-unsplash-search-btn span{
                font-size: 28px;
                line-height: 2;
                margin-left: -11px;
                margin-top: -4px;
                color: #000;
           }
           .element-ready-body-header-search .er-unsplash-search-btn:hover,.element-ready-body-header-search .er-unsplash-search-btn:focus{
               border: 5px;
               background: #f7f7f7;
               color: #000;
           }
          
        </style>    
     <?php   
    }    

 }