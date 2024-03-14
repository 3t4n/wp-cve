<?php
if ( !function_exists( 'bc_js_service' ) ) :
	function bc_js_service(){
		$option = wp_parse_args(  get_option( 'jewelrystore_option', array() ), jewelry_store_reset_data() );

		$items = $option['service_contents'];

		if(is_string($items)){
		    $items = json_decode($items);
		}

		if ( empty( $items ) || !is_array( $items ) ) {
		    $items = array();
		}

		$services = array();
		if (!empty($items) && is_array($items)) {
			foreach ($items as $k => $v) {
				$services[] = wp_parse_args($v, array(
		                    'icon'=> 'fa fa-mobile',
							'title'=> esc_html__('Your service title', 'britetechs-companion'),
							'desc'=> esc_html__('Your service description', 'britetechs-companion'),
							'link'=> '#',
		                ));
			}
		}else{
			$services = bc_service_default_contents();
		}

		$containerClass = '';
		if($option['service_container_width']!=''){
		    $containerClass = $option['service_container_width'];
		}

		$columnLayoutClass = '';
		if($option['service_column']==4){
		    $columnLayoutClass = 'col-lg-3 col-md-6 col-sm-6';
		}else if($option['service_column']==3){
		    $columnLayoutClass = 'col-lg-4 col-md-6 col-sm-6';
		}else{
		    $columnLayoutClass = 'col-lg-6 col-md-6 col-sm-6';
		}

		if($option['service_enable']==true){
		?>
		<div id="service" class="section service_section">
		    <div class="<?php echo esc_attr( $containerClass ); ?>">

		        <?php if( $option['service_title'] != '' || $option['service_subtitle'] != '' || $option['service_desc'] != '' ){ ?>
		        <div class="row">
		            <div class="col-12">
		            	<div class="header_section wow animated fadeInUp">
		            		<div class="header_section_container">
			            		<div class="header_section_details">
			            			<?php if( $option['service_subtitle'] != '' || $option['service_title'] != '' ){ ?>
	                                    <h2 class="section_title_wrap">
	                                        <?php if( $option['service_subtitle'] != '' ){ ?>
	                                        <span class="section_subtitle"><?php echo wp_kses_post($option['service_subtitle']); ?></span>
	                                        <?php } ?>
	                                        <?php if( $option['service_title'] != '' ){ ?>
	                                        <span class="section_title"><?php echo wp_kses_post($option['service_title']); ?></span>
	                                        <?php } ?>
	                                    </h2>
	                                <?php } ?>
	                                <?php if($option['service_desc']!=''){ ?>
	                                    <p class="section_desc"><?php echo wp_kses_post($option['service_desc']); ?></p>
	                                <?php } ?>
			            		</div>		            		
			            	</div>
		            	</div>
		            </div>                    
		        </div>
		        <?php } ?>
		        
		        <div class="row">
		        	<?php if(!empty($services)) { ?>
			            <?php foreach ($services as $service) { ?>
			            <div class="<?php echo esc_attr( $columnLayoutClass ); ?> wow animated fadeInUp">
			                <div class="service">
			                    <div class="service_container">
			                        <div class="service_icon">
			                            <a <?php if(isset($service['link']))echo 'href="'.esc_url($service['link']).'"'; ?>>
			                            	<i class="<?php echo esc_attr($service['icon']); ?>"></i>
			                            </a>
			                        </div>
			                        <div class="service_content">
			                        	<h3 class="service_title">
			                        		<?php if( function_exists('is_pro') ) { ?>
				                        	<a <?php if(isset($service['link']))echo 'href="'.esc_url($service['link']).'"'; ?>>
				                        	<?php } ?>
				                        		<?php echo wp_kses_post($service['title']); ?>
				                        	<?php if( function_exists('is_pro') ) { ?>
				                        	</a>
				                        	<?php } ?>
				                        </h3>
				                        <div class="service_desc">
				                            <?php echo wp_kses_post($service['desc']); ?>
				                        </div>
			                        </div>
			                    </div>
			                </div>                        
			            </div>
			            <?php } ?>           
		            <?php } ?>           
		        </div>
		    </div>
		</div>
		<?php }
	}
endif;
if ( function_exists( 'bc_js_service' ) ) {
	$section_priority = apply_filters( 'jewelry_store_section_priority', 2, 'bc_js_service' );
	add_action( 'jewelry_store_sections', 'bc_js_service', absint( $section_priority ) );
}