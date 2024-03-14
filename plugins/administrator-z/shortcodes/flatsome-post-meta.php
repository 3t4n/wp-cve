<?php 
	use Adminz\Helper\ADMINZ_Helper_Flatsome_Shortcodes;
	$_________ = new \Adminz\Helper\ADMINZ_Helper_Flatsome_Shortcodes;
	$_________->shortcode_name = 'adminz_post_meta';
	$_________->shortcode_type = 'container';
	$_________->shortcode_title = 'Post Meta';
	$_________->shortcode_icon = 'text';
	// $_________->shortcode_template = '<div class="{{ shortcode.options.visibility }} {{ shortcode.options.class }}" style="min-height: 16px;" ng-bind-html="shortcode.content | html"></div>';

	
	$options = [
		'meta_key'=> [
			'type' =>'textfield',
			'heading' => 'Meta key',
			'default' => '_thumbnail_id',
			'description' => 'Test Meta Url: '.get_site_url()."?testmeta=post_id",
		]
	];
	$options = array_merge(
		$options,
		require ADMINZ_DIR."/shortcodes/inc/flatsome-element-advanced.php",
	);

	$_________->options = $options;
	$_________->shortcode_callback = function($atts, $content){

		$atts = shortcode_atts(
			array(
				"meta_key" => "_thumbnail_id",
				'css' => '',			
				'class' => '',
				'visibility' => '',
			),
			$atts,
		);


		$classes = array();
		if ( ! empty( $atts['class'] ) )      $classes[] = $atts['class'];
		if ( ! empty( $atts['visibility'] ) ) $classes[] = $atts['visibility'];

		ob_start(); ?>
			<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
				<?php		
					$meta_value = get_post_meta(get_the_ID(),  $atts['meta_key'], true);
					// Nếu là admin thì call luôn post field 
					if(isset($_POST['ux_builder_action'])){
						?>
						<span style="padding: 15px; background: #71cedf; border: 2px dashed #000; display: flex; color: white; justify-content: center; align-items: center;">
							<?php echo do_shortcode($meta_value) ; ?>
						</span>
						<?php
					}else{
						// Nếu front-end thì kiểm tra có Template không
						$content = trim($content);
						if($content){
							echo do_shortcode(str_replace("XXX", $meta_value, $content));
						}else{
							echo do_shortcode($meta_value);
						}
					}
					
				?>
			</div>
		<?php

		return ob_get_clean();
	};

	$_________->general_element();