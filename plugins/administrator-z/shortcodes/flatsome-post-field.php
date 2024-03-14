<?php 
	use Adminz\Helper\ADMINZ_Helper_Flatsome_Shortcodes;

	$_________ = new \Adminz\Helper\ADMINZ_Helper_Flatsome_Shortcodes;
	$_________->shortcode_name = 'adminz_post_field';
	$_________->shortcode_type = 'container';
	$_________->shortcode_title = 'Post Field';
	$_________->shortcode_icon = 'text';	
	// $_________->shortcode_template = '<div class="{{ shortcode.options.visibility }} {{ shortcode.options.class }}" style="min-height: 16px;" ng-bind-html="shortcode.content | html"></div>';


	$options = [
		'post_field'=> [
			'type' =>'select',
			'heading' => 'Select Field',
			'default' => 'post_title',
			'options' => [
				"ID" => "ID",
				"post_author" => "post_author",
				"post_date" => "post_date",
				"post_date_gmt" => "post_date_gmt",
				"post_content" => "post_content",
				"post_title" => "post_title",
				"post_excerpt" => "post_excerpt",
				"post_status" => "post_status",
				"comment_status" => "comment_status",
				"ping_status" => "ping_status",
				"post_password" => "post_password",
				"post_name" => "post_name",
				"to_ping" => "to_ping",
				"pinged" => "pinged",
				"post_modified" => "post_modified",
				"post_modified_gmt" => "post_modified_gmt",
				"post_content_filtered" => "post_content_filtered",
				"post_parent" => "post_parent",
				"guid" => "guid",
				"menu_order" => "menu_order",
				"post_type" => "post_type",
				"post_mime_type" => "post_mime_type",
				"comment_count" => "comment_count",
				"filter" => "filter",
			]
		]
	];

	$options = array_merge(
		$options,
		require ADMINZ_DIR."/shortcodes/inc/flatsome-element-advanced.php",
	);
	$_________->options = $options;
	
	$_________->shortcode_callback = function($atts, $content = null){
		
		$atts = shortcode_atts(
			array(
				"post_field" => "post_title",
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
					$field = get_post_field($atts['post_field']);
					// Nếu là admin thì call luôn post field 
					if(isset($_POST['ux_builder_action'])){
						?>
						<span style="padding: 15px; background: #71cedf; border: 2px dashed #000; display: flex; flex-direction: column; color: white; justify-content: center; align-items: center;">
							<?php echo do_shortcode($field) ?>
						</span>
						<?php
					}else{
						// Nếu front-end thì kiểm tra có Template không
						$content = trim($content);
						if($content){
							echo do_shortcode(str_replace("XXX", $field, $content));
						}else{
							echo do_shortcode($field);
						}
					}
					
				?>
			</div>
		<?php

		return ob_get_clean();
	};

	
	
	$_________->general_element();

