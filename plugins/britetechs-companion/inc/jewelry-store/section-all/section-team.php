<?php
if ( !function_exists( 'bc_js_team' ) ) :
	function bc_js_team(){
		$option = wp_parse_args(  get_option( 'jewelrystore_option', array() ), jewelry_store_reset_data() );

		$items = $option['team_contents'];

		if(is_string($items)){
		    $items = json_decode($items);
		}

		if ( empty( $items ) || !is_array( $items ) ) {
		    $items = array();
		}

		$teams = array();
		if (!empty($items) && is_array($items)) {
			foreach ($items as $k => $v) {
				$teams[] = wp_parse_args($v, array(
		                    'image'=> array(
									'url' => plugin_dir_url( __FILE__ ) . 'images/team'.$k++.'.jpg',
									'id' => '51'
								),
							'title'=> 'Your Team Name',
							'position'=> 'Designation',
							'facebook_url'=> '#',
							'twitter_url'=> '#',
							'linkedin_url'=> '#',
							'googleplus_url'=> '#',
							'link' => '#',
		                ));
			}
		}else{
			$teams = bc_team_default_contents();
		}

		$containerClass = '';
		if($option['team_container_width']!=''){
		    $containerClass = $option['team_container_width'];
		}

		if($option['team_enable']==true){
		?>
		<div id="team" class="section team_section">
		        <div class="<?php echo esc_attr( $containerClass ); ?>">
		        	<?php if( $option['team_subtitle'] != '' || $option['team_title'] != '' || $option['team_desc'] != '' ){ ?>
		            <div class="row">
		                <div class="col-12">
		                	<div class="header_section wow animated fadeInUp">
			            		<div class="header_section_container">
				            		<div class="header_section_details">
				            			<?php if( $option['team_subtitle'] != '' || $option['team_title'] != '' ){ ?>
		                                    <h2 class="section_title_wrap">
		                                        <?php if( $option['team_subtitle'] != '' ){ ?>
		                                        <span class="section_subtitle"><?php echo wp_kses_post($option['team_subtitle']); ?></span>
		                                        <?php } ?>
		                                        <?php if( $option['team_title'] != '' ){ ?>
		                                        <span class="section_title"><?php echo wp_kses_post($option['team_title']); ?></span>
		                                        <?php } ?>
		                                    </h2>
		                                <?php } ?>
		                                <?php if($option['team_desc']!=''){ ?>
		                                    <p class="section_desc"><?php echo wp_kses_post($option['team_desc']); ?></p>
		                                <?php } ?>
				            		</div>		            		
				            	</div>
			            	</div>
		                </div>                    
		            </div>
		            <?php } ?>
		            <div class="row">
		                <div class="col-12">
		                    <div id="team_slider" class="owl-carousel owl-theme" data-collg="<?php echo esc_attr( $option['team_column'] ); ?>" data-colmd="3" data-colsm="2" data-colxs="1" data-itemspace="30" data-loop="true" data-autoplay="true" data-smartspeed="800" data-nav="true" data-dots="true">
		                        <?php 
		                        foreach ($teams as $team) { 
		                        	$team_m = wp_parse_args($team,array('image'=>''));
		                        	$imgurl = jewelry_store_get_media_url( $team_m['image'] , 'medium'); 
		                        ?>
		                        <div class="item wow animated fadeInUp">
		                            <div class="team">
		                                <div class="team_container">
		                                    <div class="team_image">
		                                        <a href="<?php echo esc_url($team['link']); ?>">
		                                            <img src="<?php echo esc_url($imgurl); ?>" alt="<?php echo esc_attr($team['title']); ?>">
		                                        </a>
		                                        <?php if( function_exists('is_pro') ){ ?>
		                                        <div class="team_social_icons">
		                                        	<ul>
								                        <?php if( $option['facebook_link'] != '' ){ ?>
								                        <li><a href="<?php echo esc_url( $option['facebook_link'] ); ?>"><i class="fa fa-facebook"></i></a></li>
								                        <?php } ?>

								                        <?php if( $option['twitter_link'] != '' ){ ?>
								                        <li><a href="<?php echo esc_url( $option['twitter_link'] ); ?>"><i class="fa fa-twitter"></i></a></li>
								                        <?php } ?>

								                        <?php if( $option['linkedin_link'] != '' ){ ?>
								                        <li><a href="<?php echo esc_url( $option['linkedin_link'] ); ?>"><i class="fa fa-linkedin"></i></a></li>
								                        <?php } ?>
								                    </ul>
								                    <span class="share"><i class="fa fa-share-alt" aria-hidden="true"></i></span>
								                </div>
								                <?php } ?>                                
		                                    </div>
		                                    <div class="team_content">              
	                                            <h4 class="team_title">
	                                            	<a href="<?php echo esc_url($team['link']); ?>">
	                                            		<?php echo esc_html($team['title']); ?>
	                                            	</a>
	                                            </h4>
		                                        <span class="team_designation"><?php echo esc_html($team['position']); ?></span>
		                                    </div>                     
		                                </div>
		                            </div>
		                        </div>
		                        <?php } ?>
		                    </div>                            
		                </div>
		            </div>
		        </div>
		</div>
		<?php }
	}
endif;
if ( function_exists( 'bc_js_team' ) ) {
	$section_priority = apply_filters( 'jewelry_store_section_priority', 5, 'bc_js_team' );
	add_action( 'jewelry_store_sections', 'bc_js_team', absint( $section_priority ) );
}