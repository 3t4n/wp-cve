<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Wpdev
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
  <?php wp_head(); ?>
<style type="text/css">
.no-scroll {
	position: relative;
}
	.no-scroll .page {
		position: absolute;
		top: 0;
		left: 0;
		right: 0;


	}
</style>
</head>
<body <?php body_class(); ?>>
	<center>
		<a href="<?php echo site_url(); ?>">Back To site</a>
	</center>
	<br>
	<?php
	$plugin = new Book_Press();
	$book = $plugin->get_book(get_the_ID());
	$html = '';
	$html .= "\n";
	$html .= '<div class="sections">';
	$html .= "\n";
	foreach ($book['Sections'] as $key_section => $section) {
		$html .= '	<div class="section" id="'.str_replace(' ', '-', strtolower($key_section)).'">';
		$html .= "\n";
		$html .= '		<div class="elements">';
		$html .= "\n";
		if(isset($section['Elements'])){
			foreach ($section['Elements'] as $key_element => $element) {
				if( !empty($element['Content']) ) {
					$html .= '			<div class="element" id="'.str_replace(' ', '-', strtolower($key_element)).'">';
					$html .= "\n";
					foreach ($element['Content'] as $key => $value) {
						if( !empty($value['Page Content']) ){
							$footnote_html = '';
							if(!empty($value['Footnote'])){
								$footnote_html .= '<p class="book-footnote">';
								foreach ($value['Footnote'] as $key_fnote => $footnote) {
									$coma = '';
									if(($key_fnote+1) < count($value['Footnote'])) {
										$coma = ', ';
									}
									$footnote_html .= '<span>'. ($key_fnote+1) .'-'. $footnote.''.$coma.'</span>';
								}
								$footnote_html .= '</p>';
							}
		
							$html .= '				<div class="page" data-word-count="'.$value['Word Count'].'">';
							$html .= "\n";
							$html .= '					<div class="page-number">'.$value['Page Number'].'</div>';
							$html .= "\n";
							$html .= '					<div class="page-content">';
							$html .= "\n";
							$html .= '						'.$value['Page Content'].''.$footnote_html;
							$html .= "\n";
							$html .='					</div>';
							$html .= "\n";
							$html .= '				</div><!-- /page -->';
							$html .= "\n";
							$html .= "\n";
						}
					}
					$html .= '			</div><!-- /element -->';
					$html .= "\n";

				}
			}
		}
		$html .= '	</div><!-- /elements -->';
		$html .= "\n";

		$html .= '</div><!-- /section -->';
		$html .= "\n";

	}
	$html .= '</div><!-- /sections -->';
	$html .= "\n";

	echo $html;
	?>
	<?php wp_footer(); ?>
</body>
</html>