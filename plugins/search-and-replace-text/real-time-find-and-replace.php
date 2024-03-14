<?php
/*
Plugin Name: Search and Replace Text
Version: 1.0
Author: Nabeel Tahir
Description: Search and Replace Text is used for change text in wordpress after page load,Like if you wana change some text in wordpress website that is very hard for you to change just install the Search and Replace Text plugin search what text you wana find after replace with you can change or remove word or paragraph from whole website.
License: GPLv2 or later
Text Domain: fnrt-wp


*/

//Exit if accessed directly
if (!defined('ABSPATH'))
{
    exit;
}
/*
 * Add a submenu under Tools
*/
function fnrt_pages()
{
    add_submenu_page('tools.php', 'Search and Replace Text', 'Search and Replace Text', 'activate_plugins', 'fnrt-wp', 'rtsearchreplace');
}

function rtsearchreplace()
{
$fnrt = get_option( 'fnrt_data' );
    include ('js/dynmicjs.php');
	if (isset($_POST['row']))
    {
		$post_data = stripslashes_deep( $_POST );
		$search = $post_data['search'];
		$replace = $post_data['replace'];
		$page= $post_data['page'];
		$fnrt_array = compact("search","replace","page");		
		if( empty( $post_data['search'])) {
			delete_option( 'fnrt_data' );
		} 
		else {
			update_option( 'fnrt_data', $fnrt_array );
		}
    }
?>
	<div class="">
		<h2>Search and Replace Text</h2>
		<p>Search and Replace is used for change text in wordpress after page load,Like if you wana change some text in wordpress website that is very hard for you to change just install the Search and Replace Text plugin search what text you wana find after replace with you can change or remove word or paragraph from whole website or spesfic page.</p>
	</div>
<form method="post" action="<?php echo esc_url($_SERVER["REQUEST_URI"]); ?>">
<div class="container fart">
	<?php
    $i = 0;
    $fnrt_data = get_option('fnrt_data');
    if (isset($fnrt_data["search"]))
    {
        foreach ($fnrt_data["search"] as $key => $find)
        {		
            $args = array(
                'sort_column' => 'post_title',
                'post_type' => 'page',
                'post_status' => 'publish'
            );
            $pages = get_pages($args);
			if(!empty($fnrt_data["search"])){
            $searchfield = $fnrt_data["search"][$key];
            $replacefield = $fnrt_data["replace"][$key];
            $selectpage = $fnrt_data["page"][$key];
			
			
            $cols = '<div class="row"><div class="col-lg-6"><textarea class="form-control" name="search[' . $i . ']" />' . esc_textarea($searchfield) . '</textarea></div>';
            $cols .= '<div class="col-lg-6"><textarea  class="form-control" name="replace[' . $i . ']"/>' . esc_textarea( $replacefield) . '</textarea></div>';
            $cols .= '<div class="col-lg-8">';
            $cols .= '<select name="page[' . $i . ']">	';
			$cols .= "<option value='allpage'>All pages</option>";
            foreach ($pages as $page)
            {
                $title = $page->post_title;
                $id = $page->ID;
				
				if($selectpage == $id){$checked = "selected";}else{$checked = "";}
                $cols .= "<option $checked value='$id'>$title</option>";
            }
            $cols .= '</select></div>';
            $cols .= '<div class="col-lg-4"><input type="button" class="ibtnDel btn btn-md btn-danger "  value="Delete"></div>';
            echo $cols;
		    $i = $i + 1;
            echo "</div>";
			}
        }
    }
    echo "<div class='findif' id='id' style='display:none'>" . $i . "</div>";
?>     
</div>
        <div class="main-btn">
			<input type="hidden" name="row">
            <input type="button" class="btn btn-lg btn-block" id="addrow" value="Add Row" />
			<input type="submit"/>
        </div>
</form>
<?php
} 

/*
 * Add a Stylesheet
*/
function fnrt_style($hook) {	
	wp_enqueue_style( 'my-style', plugins_url('css/style.css', __FILE__) );
}
add_action( 'admin_enqueue_scripts', 'fnrt_style' );

/*
 * Include funtions
*/
include_once('public/public-functions.php');

//Add menu item
add_action('admin_menu', 'fnrt_pages');

//public pages
add_action( 'template_redirect', 'fnrt_redirect' );
