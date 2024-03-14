<?php 

use Adminz\Admin\ADMINZ_Flatsome;
if(!isset(ADMINZ_Flatsome::$options['adminz_enable_zalo_support']) or ADMINZ_Flatsome::$options['adminz_enable_zalo_support'] !=="on" ) {
	return ;
}
use Adminz\Admin\Adminz as Adminz;
add_action('ux_builder_setup', function(){
	$default_text_align = "left";
    add_ux_builder_shortcode('adminz_team_member', array(
        'name'      => __('Team Member (Zalo)'),
        'type' => 'container',
        'category'  => Adminz::get_adminz_menu_title(),
        'thumbnail' =>  get_template_directory_uri() . '/inc/builder/shortcodes/thumbnails/' . 'team_member' . '.svg',
        'presets' => array(
	        array(
	            'name' => __( 'Default' ),
	            'content' => '[adminz_team_member name="Ola Nordmann" title="Customer Support" image_height="100%" image_width="80" image_radius="100"] Lorem ipsum.. [/adminz_team_member]'
	        ),
	    ),
	    'options' => array_merge_recursive( array(
	        'layout_options' => array(
	            'type' => 'group',
	            'heading' => __( 'Layout' ),
	            'options' => array(
	                'img' => array(
	                    'type' => 'image',
	                    'heading' => 'Image',
	                    'group' => 'background',
	                    'param_name' => 'img',
	                ),
	                'style' => array(
	                    'type' => 'select',
	                    'heading' => __( 'Style' ),
	                    'default' => 'normal',
	                    'options' => require( get_template_directory().'/inc/builder/shortcodes/values/box-layouts.php' )
	                ),

	                'name' => array( 'type' => 'textfield','heading' => 'Name', 'default' => '', 'on_change' => array( 'selector' => '.person-name', 'content' => '{{ value }}')),
	                'title' => array( 'type' => 'textfield','heading' => 'Title', 'default' => '',  'on_change' => array( 'selector' => '.person-title', 'content' => '{{ value }}')),
	                'depth' => array(
	                    'type' => 'slider',
	                    'heading' => __( 'Depth' ),
	                    'default' => '0',
	                    'max' => '5',
	                    'min' => '0',
	                ),
	                'depth_hover' => array(
	                    'type' => 'slider',
	                    'heading' => __( 'Depth :hover' ),
	                    'default' => '0',
	                    'max' => '5',
	                    'min' => '0',
	                ),
	            ),
	        ),
	        'social_icons' => array(
	            'type' => 'group',
	            'heading' => __( 'Social Icons' ),
	            'options' => array(
	               'icon_style' => array(
	                    'type' => 'radio-buttons',
	                    'heading' => __( 'Style' ),
	                    'default' => 'outline',
	                    'options' => array(
	                        'outline' => array( 'title' => 'Outline' ),
	                        'fill' => array( 'title' => 'Fill' ),
	                        'small' => array( 'title' => 'Small' ),
	                    ),
	                ),
	               'zalo'=> array( 'type' => 'textfield','heading' => 'Zalo', 'default' => '', 'placeholder'=>'https://zalo.me/0123456789'),
	                'facebook' => array( 'type' => 'textfield','heading' => 'Facebook', 'default' => ''),
	                'skype' => array( 'type' => 'textfield','heading' => 'Skype', 'default' => ''),
	                'instagram' => array( 'type' => 'textfield','heading' => 'Instagram', 'default' => ''),
	                'tiktok' => array( 'type' => 'textfield','heading' => 'TikTok', 'default' => ''),
	                'twitter' => array( 'type' => 'textfield','heading' => 'Twitter', 'default' => ''),
	                'youtube' => array( 'type' => 'textfield','heading' => 'Youtube', 'default' => ''),
	                'email' => array( 'type' => 'textfield','heading' => 'Email', 'default' => ''),
	                'phone' => array( 'type' => 'textfield','heading' => 'Phone', 'default' => ''),
	                'pinterest' => array( 'type' => 'textfield','heading' => 'Pinterest', 'default' => ''),
	                'linkedin' => array( 'type' => 'textfield','heading' => 'Linkedin', 'default' => ''),
	                'telegram' => array( 'type' => 'textfield','heading' => 'Telegram', 'default' => ''),
					'twitch' => array( 'type' => 'textfield','heading' => 'Twitch', 'default' => ''),
					'discord' => array( 'type' => 'textfield','heading' => 'Discord', 'default' => ''),
	                'snapchat' => array( 'type' => 'image', 'heading' => __( 'SnapChat' )),
	            ),
	        ),
	        'link_group' => require( get_template_directory().'/inc/builder/shortcodes/commons/links.php' ),
	    ),
	    require( get_template_directory()."/inc/builder/shortcodes/commons/box-styles.php")),
    ));
});
add_shortcode('adminz_team_member', 'adminz_team_member_function');
add_action( 'init', function() {remove_shortcode( 'team_member' ); },20 );
add_action( 'init', function(){
	add_shortcode('team_member','adminz_team_member_function');
	function adminz_team_member_function($atts, $content = null){		
	  extract( shortcode_atts( array(
	      '_id' => null,
	      'class' => '',
	      'visibility' => '',
	      'img' => '',
	      'name' => '',
	      'title' => '',
	      'icon_style' => 'outline',
	      'zalo' => '',
	      'skype'=>'',
	      'twitter' => '',
	      'facebook' => '',
	      'pinterest' => '',
	      'instagram' => '',
	      'tiktok' => '',
	      'snapchat' => '',
	      'youtube' => '',
	      'email' => '',
	      'phone' => '',
	      'linkedin' => '',
		  'telegram' => '',
	      'twitch' => '',
	      'discord' => '',
	      'style' => '',
	      'depth' => '',
	      'depth_hover' => '',
	      'link' => '',
	      'target' => '',
	      'rel' => '',
	      // Box styles
	      'animate' => '',
	      'text_pos' => 'bottom',
	      'text_padding' => '',
	      'text_bg' => '',
	      'text_color' => '',
	      'text_hover' => '',
	      'text_align' => 'center',
	      'text_size' => '',
	      'image_size' => '',
	      'image_width' => '',
	      'image_radius' => '',
	      'image_height' => '100%',
	      'image_hover' => '',
	      'image_hover_alt' => '',
	      'image_overlay' => '',
	  ), $atts ) );


	    ob_start();

	     // Set Classes
	    $classes_box = array();
	    $classes_text = array();
	    $classes_image = array();
	    $classes_image_inner = array();

	    if ( $class ) $classes_box[] = $class;
	    if ( $visibility ) $classes_box[] = $visibility;

	  	$link_atts = array(
	  		'target' => $target,
	  		'rel'    => array( $rel ),
	  	);

	    // Fix old
	    if($style == 'text-overlay'){
	      $image_hover = 'zoom';
	    }

	    $style = str_replace('text-', '', $style);

	    // Set box style
	    $classes_box[] = 'has-hover';
	    if($depth) $classes_box[] = 'box-shadow-'.$depth;
	    if($depth_hover) $classes_box[] = 'box-shadow-'.$depth_hover.'-hover';

		$link_start = '<a href="' . $link . '"' . flatsome_parse_target_rel( $link_atts ) . '>';
		$link_end   = '</a>';

	    if($style) $classes_box[] = 'box-'.$style;
	    if($style == 'overlay') $classes_box[] = 'dark';
	    if($style == 'shade') $classes_box[] = 'dark';
	    if($style == 'badge') $classes_box[] = 'hover-dark';
	    if($text_pos) $classes_box[] = 'box-text-'.$text_pos;
	    if($style == 'overlay' && !$image_overlay) $image_overlay = 'rgba(0,0,0,.2)';

	    if($image_hover)  $classes_image[] = 'image-'.$image_hover;
	    if($image_hover_alt)  $classes_image[] = 'image-'.$image_hover_alt;

	    if($image_height)  $classes_image_inner[] = 'image-cover';

	    // Text classes
	    if($text_hover) $classes_text[] = 'show-on-hover hover-'.$text_hover;
	    if($text_align) $classes_text[] = 'text-'.$text_align;
	    if($text_size) $classes_text[] = 'is-'.$text_size;
	    if($text_color == 'dark') $classes_text[] = 'dark';

	    if($animate) {$animate = 'data-animate="'.$animate.'"';}

	     $css_args = array(
	        array( 'attribute' => 'background-color', 'value' => $text_bg ),
	        array( 'attribute' => 'padding', 'value' => $text_padding ),
	     );

	    $css_image = array(
	        array( 'attribute' => 'width', 'value' => $image_width,'unit' => '%' ),
	    );

	    $css_image_inner = array(
	        array( 'attribute' => 'border-radius', 'value' => $image_radius,'unit' => '%' ),
	        array( 'attribute' => 'padding-top', 'value' => $image_height),
	    );

		$has_custom_social_link = $facebook || $instagram || $tiktok || $twitter || $youtube || $email || $phone || $pinterest || $linkedin || $snapchat || $telegram || $twitch || $discord || $zalo;
	    ?>
	    <div class="box has-hover <?php echo esc_attr(implode(' ', $classes_box)); ?>" <?php echo esc_attr($animate); ?>>

	         <?php if($link) echo esc_attr($link_start); ?>
	         <div class="box-image <?php echo esc_attr(implode(' ', $classes_image)); ?>" <?php echo get_shortcode_inline_css($css_image); ?>>
	           <div class="box-image-inner <?php echo esc_attr(implode(' ', $classes_image_inner)); ?>" <?php echo get_shortcode_inline_css($css_image_inner); ?>>
	              <?php echo flatsome_get_image($img, $image_size); ?>
	              <?php if($image_overlay) { ?><div class="overlay" style="background-color:<?php echo esc_attr($image_overlay); ?>"></div><?php } ?>
	           </div>
	          </div>
	         <?php if($link) echo esc_attr($link_end); ?>

	          <div class="box-text <?php echo esc_attr(implode(' ', $classes_text)); ?>" <?php echo get_shortcode_inline_css($css_args); ?>>
	                <div class="box-text-inner">
	                  <h4 class="uppercase">
	                    <span class="person-name"><?php echo esc_attr($name); ?></span><br/>
	                    <span class="person-title is-small thin-font op-7">
	                      <?php echo esc_attr($title); ?>
	                    </span>
	                  </h4>
	                 <?php if( $has_custom_social_link ) echo do_shortcode('[follow style="'.$icon_style.'" zalo="'.$zalo.'" facebook="'.$facebook.'" skype="'.$skype.'" twitter="'.$twitter.'" snapchat="'.$snapchat.'" email="'.$email.'" phone="'.$phone.'" pinterest="'.$pinterest.'" youtube="'.$youtube.'" instagram="'.$instagram.'" tiktok="'.$tiktok.'" linkedin="'.$linkedin.'" telegram="'.$telegram.'" twitch="'.$twitch.'" discord="'.$discord.'"]'); ?>
	                 <?php if($style  !== 'overlay' && $style  !== 'shade') echo do_shortcode($content); ?>
	                </div>
	          </div>
	    </div>

	    <?php if($style  == 'overlay' || $style  == 'shade') echo '<div class="team-member-content pt-half text-'.esc_attr($text_align).'">'.esc_attr($content).'</div>'; ?>

	    <?php
	    $content = ob_get_contents();
	    ob_end_clean();
	    return $content;
	};
},21);