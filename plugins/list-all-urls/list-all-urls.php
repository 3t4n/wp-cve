<?php
/**
 * Plugin Name: List all URLs
 * Plugin URI: http://www.evanwebdesign.com/
 * Description: Creates a page in the admin panel under Settings > List All URLs that outputs an ordered list of all of the website's published URLs.
 * Version: 0.2.1
 * Author: Evan Scheingross
 * Author URI: http://www.evanwebdesign.com/
 * License: GPL v2 or higher
 * License URI: License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Tested up to: 6.4.1
 */

// See http://codex.wordpress.org/Administration_Menus for more info on the process of creating an admin page
add_action( 'admin_menu', 'my_plugin_menu' );


function my_plugin_menu() {
	add_options_page( 'List All URLs', 'List All URLs', 'manage_options', 'list-all-urls', 'generate_url_list' );
}


function generate_url_list() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}

$i = 0;
$myposttpe = array(); // Creating array variable to house all custom post types
$mypostname = array(); // Creating array variable to house all pretty names custom post types


// Get all Custom Post Types, and ONLY Custom Post Types. See http://codex.wordpress.org/Function_Reference/get_post_types
$args=array(
  'public'   => true,
  '_builtin' => false
);
$output = 'objects'; // names or objects, note names is the default.
$operator = 'and'; // 'and' or 'or'
$post_types = get_post_types($args,$output,$operator);


// Loop through get_post_types and create arrays for custom post type names and labels
foreach ($post_types  as $post_type ) {
$myposttpe[$i] = $post_type->name; // Getting the code name for the post type. See http://codex.wordpress.org/Function_Reference/get_post_type_object
$mypostname[$i] = $post_type->labels->singular_name; // Getting the pretty name for the post type.
$i++;
}

$arrlength = count($myposttpe); // Setting variable arrlength equal to the number of array units in $myposttpye



// Form and function called on submit sourced in part from http://stackoverflow.com/questions/6060028/call-form-submit-action-from-php-function-on-same-page
?>

<p><strong>Select the URLs you would like to list from the following options:</strong></p>
<form id = "myform" action = "" method = "post">
    <input type="radio" name="getpost-radio" value="all"/> All URLs (pages, posts, and custom post types)<br>
    <input type="radio" name="getpost-radio" value="pages"/> Pages Only<br>
    <input type="radio" name="getpost-radio" value="posts"/> Posts Only<br>
    <?php
		for($x=0;$x<$arrlength;$x++) {
		  echo '<input type="radio" name="getpost-radio" value="'. $myposttpe[$x] . '"/> ' . $mypostname[$x] . ' Posts Only<br>';
		  }
     ?>
    <br>
    <input type="checkbox" name="makelinks" value="makelinks"  /> Make the generated list of URLs clickable hyperlinks <br>
    <br>

    <input type="submit" class="button-primary" value="Submit"/>
</form>

<?php
	if (isset($_POST['getpost-radio'])) {

	    if ($_POST['getpost-radio']=="all") {
			$the_query = new WP_Query( array('post_type' => 'any', 'posts_per_page' => '-1', 'post_status' => 'publish' ) );
		} else if ($_POST['getpost-radio']=="pages") {
			$the_query = new WP_Query( array('post_type' => 'page', 'posts_per_page' => '-1', 'post_status' => 'publish' ) );
		} else if ($_POST['getpost-radio']=="posts") {
			$the_query = new WP_Query( array('post_type' => 'post', 'posts_per_page' => '-1', 'post_status' => 'publish' ) );
		} else {
			for($y=0;$y<$arrlength;$y++) {
			  if ($_POST['getpost-radio'] == $myposttpe[$y]) {
				echo '<p>test</p>';
				$the_query = new WP_Query( 'post_type='.$myposttpe[$y].'&posts_per_page=-1&post_status=publish');
				}
			}
		}

		echo '<p><strong>Below is a list of your requested URLs:</strong></p>';
?>

<ol>
    <?php // The Loop
		while ( $the_query->have_posts() ) :
			$the_query->the_post();
			?>
    <li>
		<?php if (isset($_POST['makelinks'])) { ?>
				<a href="<?php the_permalink();?>"><?php the_permalink(); ?></a>
                <?php } else {
					the_permalink();
					} ?>
    </li>
    <?php endwhile; ?>
</ol>
<?php } // end if

} // end generate_url_list()







