<?php

// Import

add_action( 'enqueue_block_editor_assets', 'spice_blocks_import_style',11 );
 
function spice_blocks_import_style(){
    $id = $GLOBALS['hook_suffix'];
     if('post-new.php'==$id || 'post.php'==$id ){
        wp_enqueue_style( 'spice-blocks-custom-editor-css', SPICE_BLOCKS_PLUGIN_URL. '/assets/css/block.css', ['wp-edit-blocks'],
               false,
               'all' );   
        wp_enqueue_script( 'spice-blocks-custom-link-in-toolbar', SPICE_BLOCKS_PLUGIN_URL. '/assets/js/block.js', array(), '1.0', true );  
        wp_localize_script('spice-blocks-custom-link-in-toolbar','plugin1',['pluginpath1' => SPICE_BLOCKS_PLUGIN_URL ]);
        wp_enqueue_script('spice-blocks-custom-link-in-toolbar'); 
        wp_localize_script( 'spice-blocks-custom-link-in-toolbar', 'pva_params', array( 'pva_ajax_url' => admin_url( 'admin-ajax.php' ) ) );
        wp_enqueue_script( 'spice-blocks-custom-link-in-toolbar' );  
   }
}

function spice_blocks_add_modal(){
    $id = $GLOBALS['hook_suffix'];
    if('post-new.php'==$id || 'post.php'==$id ){?>
        <div class="spice-block-library" id="spice-block-library">
            <div class="overlay"></div>
            <div class="library-container">
                <div class="library-header">
                    <div><img src="<?php echo esc_url(SPICE_BLOCKS_PLUGIN_URL.'admin/assets/images/site-logo.png');?>" alt="site logo" class="logo"></div>
                    <div class="switch-btn">
                        <button class="btn btn-1 active" id="starter-pack-btn"><?php echo __('Starter Packs','spice-blocks');?></button>
                        <button class="btn btn-2" id="demo-section-pack-btn" ><?php echo __('Sections','spice-blocks');?></button>
                    </div>
                    <div><span class="close">&times;</span></div>
                </div>
                <div class="library-body"><div id="spice-block-loader" ><span></span></div> 
                    <!-------------------------- Demo Sections -------------------------->
                    <div class="library-body-wrapper" id="demo-section-pack">                        
                                       
                        <div class="library-sidebar">
                            <span class="section-search">
                                <svg width="15" height="16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.5 0a5.5 5.5 0 00-4.12 9.147L0 15.253l.747.667 5.366-6.08A5.5 5.5 0 109.5.007V0zm0 10a4.5 4.5 0 110-9 4.5 4.5 0 010 9z" ></path></svg>
                                <input id="search_field" class="search" type="text" placeholder="Search...">
                            </span>
                            <div class="sidebar-items">
                                <div class="item-filter" id="filters">
                                    <!-- <div class="layout">
                                        <h3><?php //echo __('Layout','spice-blocks');?></h3>
                                        <ul class="item-list spice-filter-group">
                                        <li class="licence" data-filter-toggle="dark"><?php// echo __('Dark','spice-blocks');?></li>
                                        <li class="licence" data-filter-toggle="light"><?php// echo __('Light','spice-blocks');?></li>
                                        </ul>
                                    </div> -->
                                    <div class="Categories">
                                        <h3>Categories</h3>
                                        <ul class="item-list spice-filter-group">
                                            <!-- <li class="is-active" data-filter-toggle="all"><?php echo __('All','spice-blocks');?></li> -->
                                            <li class="licence" data-filter-toggle="banner"><?php echo __('Banner','spice-blocks');?></li>
                                            <li class="licence" data-filter-toggle="feature"><?php echo __('Feature','spice-blocks');?></li>
                                            <li class="licence" data-filter-toggle="about"><?php echo __('About','spice-blocks');?></li>
                                            <li class="licence" data-filter-toggle="service"><?php echo __('Service','spice-blocks');?></li>
                                            <li class="licence" data-filter-toggle="project"><?php echo __('Project','spice-blocks');?></li>
                                            <li class="licence" data-filter-toggle="team"><?php echo __('Team','spice-blocks');?></li>
                                            <li class="licence" data-filter-toggle="pricing"><?php echo __('Pricing','spice-blocks');?></li>
                                            <li class="licence" data-filter-toggle="testimonial"><?php echo __('Testimonial','spice-blocks');?></li>
                                            <li class="licence" data-filter-toggle="faq"><?php echo __('Faq','spice-blocks');?></li>
                                            <li class="licence" data-filter-toggle="client"><?php echo __('Client','spice-blocks');?></li>
                                            <li class="licence" data-filter-toggle="progress"><?php echo __('Progress','spice-blocks');?></li>
                                            <li class="licence" data-filter-toggle="timeline"><?php echo __('Timeline','spice-blocks');?></li>
                                            <li class="licence" data-filter-toggle="cta"><?php echo __('Call To Action','spice-blocks');?></li>
                                            <li class="licence" data-filter-toggle="img-comparison"><?php echo __('Image Comparison','spice-blocks');?></li>                                        
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="library-content">
                            <div class="library-content-wrapper">
                                <!--  Demo Section column-1 -->

                                    <div class="item card content-section" data-filter-tags="all,dark,feature">
                                        <div class="demo-section-img label ">
                                            <img src="<?php echo esc_url(SPICE_BLOCKS_PLUGIN_URL.'admin/assets/images/demo-section-image/features.png');?>" alt="feature section">
                                        </div>
                                        <div class="card-details">
                                            <div class="heading"><h4><?php echo __('Feature','spice-blocks');?></h4></div>
                                            <div class="card-btn"><button onclick="spice_block_import(this.value)" class="sbimport" value="darkfusion-feature-section"><?php echo __('Import','spice-blocks');?></button></div>
                                        </div>
                                    </div>

                                    <div class="item card content-section" data-filter-tags="all,dark,about">
                                        <div class="demo-section-img label ">
                                            <img src="<?php echo esc_url(SPICE_BLOCKS_PLUGIN_URL.'admin/assets/images/demo-section-image/about.png');?>" alt="about section">
                                        </div>
                                        <div class="card-details">
                                            <div class="heading"><h4><?php echo __('About','spice-blocks');?></h4></div>
                                            <div class="card-btn"><button onclick="spice_block_import(this.value)" class="sbimport" value="darkfusion-about-section"><?php echo __('Import','spice-blocks');?></button></div>
                                        </div>
                                    </div>

                                    <div class="item card content-section" data-filter-tags="all,dark,service">
                                        <div class="demo-section-img label ">
                                            <img src="<?php echo esc_url(SPICE_BLOCKS_PLUGIN_URL.'admin/assets/images/demo-section-image/service.png');?>" alt="service section">
                                        </div>
                                        <div class="card-details">
                                            <div class="heading"><h4><?php echo __('Service','spice-blocks');?></h4></div>
                                            <div class="card-btn"><button onclick="spice_block_import(this.value)" class="sbimport" value="darkfusion-service-section"><?php echo __('Import','spice-blocks');?></button></div>
                                        </div>
                                    </div>

                                    <div class="item card content-section" data-filter-tags="all,dark,progress">
                                        <div class="demo-section-img label ">
                                            <img src="<?php echo esc_url(SPICE_BLOCKS_PLUGIN_URL.'admin/assets/images/demo-section-image/progress.png');?>" alt="progress section">
                                        </div>
                                        <div class="card-details">
                                            <div class="heading"><h4><?php echo __('Progress','spice-blocks');?></h4></div>
                                            <div class="card-btn"><button onclick="spice_block_import(this.value)" class="sbimport" value="darkfusion-progress-section"><?php echo __('Import','spice-blocks');?></button></div>
                                        </div>
                                    </div>

                                    <div class="item card content-section" data-filter-tags="all,dark,img-comparison">
                                        <div class="demo-section-img label ">
                                            <img src="<?php echo esc_url(SPICE_BLOCKS_PLUGIN_URL.'admin/assets/images/demo-section-image/img-comparison.png');?>" alt="img comparison section">
                                        </div>
                                        <div class="card-details">
                                            <div class="heading"><h4><?php echo __('Image Comparison','spice-blocks');?></h4></div>
                                            <div class="card-btn"><button onclick="spice_block_import(this.value)" class="sbimport" value="darkfusion-img-comparison-section"><?php echo __('Import','spice-blocks');?></button></div>
                                        </div>
                                    </div>

                                    

                               

                                <!--  Demo Section column-2 -->

                                
                                    <div class="item card content-section" data-filter-tags="all,dark,project">
                                        <div class="demo-section-img label ">
                                            <img src="<?php echo esc_url(SPICE_BLOCKS_PLUGIN_URL.'admin/assets/images/demo-section-image/project.png');?>" alt="project section">
                                        </div>
                                        <div class="card-details">
                                            <div class="heading"><h4>Project</h4></div>
                                            <div class="card-btn"><button onclick="spice_block_import(this.value)" class="sbimport" value="darkfusion-project-section"><?php echo __('Import','spice-blocks');?></button></div>
                                        </div>
                                    </div>

                                    <div class="item card content-section" data-filter-tags="all,dark,team">
                                        <div class="demo-section-img label ">
                                            <img src="<?php echo esc_url(SPICE_BLOCKS_PLUGIN_URL.'admin/assets/images/demo-section-image/team.png');?>" alt="team section">
                                        </div>
                                        <div class="card-details">
                                            <div class="heading"><h4><?php echo __('Team','spice-blocks');?></h4></div>
                                            <div class="card-btn"><button onclick="spice_block_import(this.value)" class="sbimport" value="darkfusion-team-section"><?php echo __('Import','spice-blocks');?></button></div>
                                        </div>
                                    </div>

                                    <div class="item card content-section" data-filter-tags="all,dark,pricing">
                                        <div class="demo-section-img label ">
                                            <img src="<?php echo esc_url(SPICE_BLOCKS_PLUGIN_URL.'admin/assets/images/demo-section-image/pricing.png');?>" alt="pricing section">
                                        </div>
                                        <div class="card-details">
                                            <div class="heading"><h4><?php echo __('Pricing','spice-blocks');?></h4></div>
                                            <div class="card-btn"><button onclick="spice_block_import(this.value)" class="sbimport" value="darkfusion-pricing-section"><?php echo __('Import','spice-blocks');?></button></div>
                                        </div>
                                    </div>

                                    <div class="item card content-section" data-filter-tags="all,dark,timeline">
                                        <div class="demo-section-img label ">
                                            <img src="<?php echo esc_url(SPICE_BLOCKS_PLUGIN_URL.'admin/assets/images/demo-section-image/timeline.png');?>" alt="timeline section">
                                        </div>
                                        <div class="card-details">
                                            <div class="heading"><h4><?php echo __('Timeline','spice-blocks');?></h4></div>
                                            <div class="card-btn"><button onclick="spice_block_import(this.value)" class="sbimport" value="darkfusion-timeline-section"><?php echo __('Import','spice-blocks');?></button></div>
                                        </div>
                                    </div>    

                                    <div class="item card content-section" data-filter-tags="all,dark,banner">
                                        <div class="demo-section-img label ">
                                            <img src="<?php echo esc_url(SPICE_BLOCKS_PLUGIN_URL.'admin/assets/images/demo-section-image/hero.png');?>" alt="banner section">
                                        </div>
                                        <div class="card-details">
                                            <div class="heading"><h4><?php echo __('Banner','spice-blocks');?></h4></div>
                                            <div class="card-btn"><button onclick="spice_block_import(this.value)" class="sbimport" value="darkfusion-banner-section"><?php echo __('Import','spice-blocks');?></button></div>
                                        </div>
                                    </div>                                

                                

                                <!--  Demo Section column-3 -->

                                
                                    <div class="item card content-section" data-filter-tags="all,dark,testimonial">
                                        <div class="demo-section-img label ">
                                            <img src="<?php echo esc_url(SPICE_BLOCKS_PLUGIN_URL.'admin/assets/images/demo-section-image/testimonial.png');?>" alt="testimonial section">
                                        </div>
                                        <div class="card-details">
                                            <div class="heading"><h4><?php echo __('Testimonial','spice-blocks');?></h4></div>
                                            <div class="card-btn"><button onclick="spice_block_import(this.value)" class="sbimport" value="darkfusion-testimonial-section"><?php echo __('Import','spice-blocks');?></button></div>
                                        </div>
                                    </div>

                                    <div class="item card content-section" data-filter-tags="all,dark,faq">
                                        <div class="demo-section-img label ">
                                            <img src="<?php echo esc_url(SPICE_BLOCKS_PLUGIN_URL.'admin/assets/images/demo-section-image/faq.png');?>" alt="faq section">
                                        </div>
                                        <div class="card-details">
                                            <div class="heading"><h4><?php echo __('Faq','spice-blocks');?></h4></div>
                                            <div class="card-btn"><button onclick="spice_block_import(this.value)" class="sbimport" value="darkfusion-faq-section"><?php echo __('Import','spice-blocks');?></button></div>
                                        </div>
                                    </div>

                                    <div class="item card content-section" data-filter-tags="all,dark,client">
                                        <div class="demo-section-img label ">
                                            <img src="<?php echo esc_url(SPICE_BLOCKS_PLUGIN_URL.'admin/assets/images/demo-section-image/clients.png');?>" alt="client section">
                                        </div>
                                        <div class="card-details">
                                            <div class="heading"><h4><?php echo __('Client','spice-blocks');?></h4></div>
                                            <div class="card-btn"><button onclick="spice_block_import(this.value)" class="sbimport" value="darkfusion-client-section"><?php echo __('Import','spice-blocks');?></button></div>
                                        </div>
                                    </div>

                                    <div class="item card content-section" data-filter-tags="all,dark,cta">
                                        <div class="demo-section-img label ">
                                            <img src="<?php echo esc_url(SPICE_BLOCKS_PLUGIN_URL.'admin/assets/images/demo-section-image/cta.png');?>" alt="cta section">
                                        </div>
                                        <div class="card-details">
                                            <div class="heading"><h4><?php echo __('Call To Action','spice-blocks');?></h4></div>
                                            <div class="card-btn"><button onclick="spice_block_import(this.value)" class="sbimport" value="darkfusion-cta-section"><?php echo __('Import','spice-blocks');?></button></div>
                                        </div>
                                    </div>


                            </div>
                        </div>
                    </div>

                    <!-------------------------- Starter-pack -------------------------->
                    <div class="library-body-wrapper" id="starter-pack" style="display:flex;">
                        <div class="library-sidebar">
                            <span class="section-search">
                                <svg width="15" height="16" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9.5 0a5.5 5.5 0 00-4.12 9.147L0 15.253l.747.667 5.366-6.08A5.5 5.5 0 109.5.007V0zm0 10a4.5 4.5 0 110-9 4.5 4.5 0 010 9z" ></path></svg>
                                <input id="search_field2" class="search" type="text" placeholder="Search...">
                            </span>
                            <div class="sidebar-items">
                                <div class="item-filter" id="filters-2">
                                    <!-- <div class="layout">
                                        <h3><?php //echo __('Layout','spice-blocks');?></h3>
                                        <ul class="item-list filter-group-2">
                                            <li class="licence" data-filter-toggle1="drk"><?php //echo __('Dark','spice-blocks');?></li>
                                            <li class="licence" data-filter-toggle1="lht"><?php //echo __('Light','spice-blocks');?></li>
                                        </ul>
                                    </div> -->
                                    <div class="filter-box Categories">
                                        <h3><?php echo __('Categories','spice-blocks');?></h3>
                                        <ul class="item-list filter-group-2">
                                            <!-- <li class="is-active" data-filter-toggle1="alll"><?php echo __('All','spice-blocks');?></li> -->
                                            <li class="licence" data-filter-toggle1="darkfusion"><?php echo __('Dark Fusion','spice-blocks');?></li>
                                            <li class="licence" data-filter-toggle1="arctact"><?php echo __('Architect','spice-blocks');?></li>
                                            <li class="licence" data-filter-toggle1="busnss"><?php echo __('Business','spice-blocks');?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="library-content">
                            <div class="library-content-wrapper">
                                    <!-- Starter pack Architect -->
                                <div class="item card content-section" data-filter-tags="alll,drk,darkfusion">
                                    <div class="starter-pack">
                                        <div class="starter-pack-img label new" data-slug="darkfusion">
                                            <img src="<?php echo esc_url(SPICE_BLOCKS_PLUGIN_URL.'admin/assets/images/starter-pack-images/business-1.png');?>" alt="darkfusion demo image">
                                        </div>
                                        <div class="card-details">
                                            <div class="heading"><h4><?php echo __('Dark Fusion','spice-blocks');?></h4></div>
                                            <div class="card-btn"><a href="<?php echo esc_url('https://g1.spiceblocks.com/'); ?>" target="_blank" class="spice-block-preview"><?php echo __('Preview','spice-blocks');?></a></div>
                                        </div>
                                    </div>
                                </div>
                                        <!-- Starter pack Architect -->
                                <div class="item card content-section" data-filter-tags="alll,drk,arctact">
                                    <div class="starter-pack">
                                        <div class="starter-pack-img label soon" data-slug="architect">
                                            <img src="<?php echo esc_url(SPICE_BLOCKS_PLUGIN_URL.'admin/assets/images/starter-pack-images/architect.png');?>" alt="architect demo image">
                                        </div>
                                        <div class="card-details">
                                            <div class="heading"><h4><?php echo __('Architect','spice-blocks');?></h4></div>
                                        </div>
                                    </div>
                                </div>
                                        <!-- Starter pack Business -->
                                <div class="item card content-section" data-filter-tags="alll,drk,busnss">
                                    <div class="starter-pack">
                                        <div class="starter-pack-img label soon" data-slug="business">
                                            <img src="<?php echo esc_url(SPICE_BLOCKS_PLUGIN_URL.'admin/assets/images/starter-pack-images/business.png');?>" alt="business demo image">
                                        </div>
                                        <div class="card-details">
                                            <div class="heading"><h4><?php echo __('Business','spice-blocks');?></h4></div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div> 
                    </div>
                        <!-------------------------- Single Page -------------------------->
                    <div class="library-body library-single-page" style="display:none;">
                        <div class="library-body-wrapper" id="demo-section-inner"> 
                        <div class="library-content">
                            <div class="back-btn"><a><span> &#10229; <?php echo __('Back to Starter Packs','spice-blocks');?></span></a></div>
                            <div class="library-content-wrapper business-starter-demo" id='addinnerpage'></div>
                            <!-- <div  class="view-demo"><a href="#">View Demo</a></div> -->
                        </div>
                    </div>
                    </div>
                </div>
            </div>
    <?php
    }
}
add_action('admin_footer','spice_blocks_add_modal',11);


// creating Ajax call for WordPress
add_action( 'wp_ajax_nopriv_pva_create01', 'spice_blocks_pva_create01',11 );
add_action( 'wp_ajax_pva_create01', 'spice_blocks_pva_create01',11 );

/* WP Insert Post Function
----- */

function spice_blocks_pva_create01(){  
   if(isset($_POST['file_path'])){
        $url  = $_POST['file_path'];
        $file_name  = $_POST['file_name'];
        $post_id = $_POST['post_id'];
        //$type = $_POST['type'];
        $uploads_dir = trailingslashit( wp_upload_dir()['basedir'] ) . 'spice-blocks';
        wp_mkdir_p( $uploads_dir );
        $path = $uploads_dir.'/'.$file_name.'.json';
        $fp = fopen($path, 'w');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        $data = curl_exec($ch);
        curl_close($ch);
        fclose($fp);
        $str = file_get_contents($path);
        $jsondata = json_decode($str,true);
        // Create post object
        

        $str01=str_replace(array('u003cbru003e','\\u003cbr\\u003e','u0026amp;','u003c/emu003e','u0026','\\u003cem\\u003e','\\u003c/em\\u003e','u003cemu003e','u003c/emu003e'), array('<br>','<br>','&','','&','<em>','</em>','<em>','</em>'), $jsondata['content']);
        $content_post = get_post($post_id);
        $contentold = $content_post->post_content;
        $str02=str_replace(array('u003cbru003e','\\u003cbr\\u003e','u0026amp;','u003c/emu003e','u0026','\\u003cem\\u003e','\\u003c/em\\u003e','u003cemu003e','u003c/emu003e'), array('<br>','<br>','&','','&','<em>','</em>','<em>','</em>'), $contentold);         
        if($content_post->post_title=='Auto Draft'){
            $titleold = '';
        }else{
            $titleold = $content_post->post_title;
        }
        $updatecontent ='';
        $updatecontent .=$str02;
        $updatecontent .=$str01;
        $updated_post = array(
            'ID'            => $post_id,
            'post_type'     => 'page',
            'post_title'    => $titleold,
            'post_content'  => $updatecontent,
            'post_status'   => 'publish',
            'post_author'   => 1,
        );
        wp_update_post($updated_post);
    }
};