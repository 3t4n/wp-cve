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
	.page-container {
		display: none;
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
	$book = $plugin->get_book_new(get_the_ID());

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
						$html .= '<div class="pages">';
						$html .= '</div><!-- /pages -->';

						$html .= '<div class="page-container">';
						$html .=  preg_replace('/\s+/', ' ', trim($value));
						$html .= '</div><!-- /page-container -->';
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

	<script type="text/javascript">
		




	var page = document.getElementsByClassName("page-container");

	for (var i = 0; i < page.length; i++) {

	  var text = page[i].innerHTML; // gets the text, which should be displayed later on

	  var textArray = text.match(/(<table[^>]*>(?:.|\n)*?<\/table>)|<img\s+[^>]*>|<p.*?>[^<>]*<\/p>|<h.*?>[^<>]*<\/h.*?>|[^\s]+/g);

	  console.log(textArray);
	  createPage(i); // creates the first page
	  for (var j = 0; j < textArray.length; j++) { // loops through all the words


	  	
	      var success = appendToLastPage(textArray[j]); // tries to fill the word in the last page
	      if (!success) { // checks if word could not be filled in last page
	          createPage(i); // create new empty page
	          appendToLastPage(textArray[j]); // fill the word in the new last element
	      }
	  }
  }


function createPage(i) {
    var page = document.createElement("div"); // creates new html element
    page.setAttribute("class", "page"); // appends the class "page" to the element
    document.getElementsByClassName("pages")[i].appendChild(page); // appends the element to the container for all the pages

}

function appendToLastPage(word) {
    var page = document.getElementsByClassName("page")[document.getElementsByClassName("page").length - 1]; // gets the last page
    var pageText = page.innerHTML; // gets the text from the last page
    page.innerHTML += word + " "; // saves the text of the last page
    if (page.offsetHeight < page.scrollHeight) { // checks if the page overflows (more words than space)
        page.innerHTML = pageText; //resets the page-text
        return false; // returns false because page is full
    } else {
        return true; // returns true because word was successfully filled in the page
    }
}




	</script>
</body>
</html>