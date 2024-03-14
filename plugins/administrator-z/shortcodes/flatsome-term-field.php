<?php 
	use Adminz\Helper\ADMINZ_Helper_Flatsome_Shortcodes;

	$_________ = new \Adminz\Helper\ADMINZ_Helper_Flatsome_Shortcodes;
	$_________->shortcode_name = 'adminz_term_field';
	$_________->shortcode_type = 'container';
	$_________->shortcode_title = 'Term Field/ Meta';
	$_________->shortcode_icon = 'text';	
	// $_________->shortcode_template = '<div class="{{ shortcode.options.visibility }} {{ shortcode.options.class }}" style="min-height: 16px;" ng-bind-html="shortcode.content | html"></div>';


	$options = [
		'term_field'=> [
			'type' =>'textfield',
			'heading' => 'Select Field/meta',
			'default' => 'name',
			'description' => 'name, slug, term_id, description, or your custom_meta_key'
		]
	];

	$options = array_merge(
		$options,
		require ADMINZ_DIR."/shortcodes/inc/flatsome-element-advanced.php",
	);
	$_________->options = $options;
	
	$_________->shortcode_callback = function($atts, $content = null) use ($_________) {
		
		$atts = shortcode_atts(
			array(
				"term_field" => "name",
				'css' => '',			
				'class' => '',
				'visibility' => '',
			),
			$atts,
		);
        
        $term = get_queried_object();
        if('WP_Term' !==get_class($term)){
			echo $_________->preview_text();
            return ob_get_clean();
        }

		$classes = array();
		if ( ! empty( $atts['class'] ) )      $classes[] = $atts['class'];
		if ( ! empty( $atts['visibility'] ) ) $classes[] = $atts['visibility'];

        ob_start(); ?>
			<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
				<?php		
					$field = ( get_term_field( $atts['term_field'], $term->term_id, $term->taxonomy ) );
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

		ob_start();
		return ob_get_clean();
	};

	
	
	$_________->general_element();

