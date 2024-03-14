<?php
use Adminz\Helper\ADMINZ_Helper_Flatsome_Shortcodes;

$_________                  = new \Adminz\Helper\ADMINZ_Helper_Flatsome_Shortcodes;
$_________->shortcode_name  = 'adminz_taxonomy_posts';
$_________->shortcode_title = 'Taxonomy Posts';
$_________->shortcode_icon  = 'text';


$options = [ 
	'layout' => [ 
		'type'    => 'select',
		'heading' => 'Layout',
		'default' => '',
		'options' => [ 
			""       => "Default",
			"list"   => "List",
			"inline" => "Inline",
			"2-col"  => "2 Cols",
			"3-col"  => "3 Cols",
		],
	],
];

$options            = array_merge(
	$options,
	require ADMINZ_DIR . "/shortcodes/inc/flatsome-element-advanced.php",
);
$_________->options = $options;

$_________->shortcode_callback = function ($atts, $content = null) use($_________) {

	$atts = shortcode_atts(
		array(
			"layout" 	=> "",
			'css'        => '',
			'class'      => '',
			'visibility' => '',
		),
		$atts,
	);

	$term = get_queried_object();
	if ( 'WP_Term' !== get_class( $term ) ) {
		echo $_________->preview_text();
		return ob_get_clean();
	}


	$classes = array();
	if ( !empty( $atts['class'] ) )
		$classes[] = $atts['class'];
	if ( !empty( $atts['visibility'] ) )
		$classes[] = $atts['visibility'];

	ob_start(); ?>
	<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
		<?php
			get_template_part( 'template-parts/posts/archive', $atts['layout'] );
		?>
	</div>
	<?php

	return ob_get_clean();
};



$_________->general_element();

