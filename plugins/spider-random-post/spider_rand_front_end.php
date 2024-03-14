<?php

$path  = '';  
if ( !defined('WP_LOAD_PATH') ) {

	/** classic root path if wp-content and plugins is below wp-config.php */
	$classic_root = dirname(dirname(dirname(dirname(__FILE__)))) . '/' ;
	
	if (file_exists( $classic_root . 'wp-load.php') )
		define( 'WP_LOAD_PATH', $classic_root);
	else
		if (file_exists( $path . 'wp-load.php') )
			define( 'WP_LOAD_PATH', $path);
		else
			exit("Could not find wp-load.php");
}
// let's load WordPress
require_once( WP_LOAD_PATH . 'wp-load.php');
if(isset($_GET['count_pages'])){
if($_GET['count_pages']>0){
$numberposts=$_GET['count_pages'];
}
else{$numberposts=1;}
}
else{$numberposts=1;}

if(isset($_GET['categori_id'])){
if($_GET['categori_id']>0){
$cat_id=$_GET['categori_id'];
}
else{$cat_id="";}
}
else{$cat_id="";}




$args = array(
    'numberposts'     => $numberposts,
    'offset'          => 0,
    'category'        => $cat_id,
    'orderby'         => 'rand',
    'order'           => 'ASC',  
    'post_type'       => 'post',
     'post_status'     => 'publish' );
$lastposts = get_posts($args);

foreach($lastposts as $post) : setup_postdata($post); ?>
	<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	<?php the_content(); ?>
<?php endforeach; ?>









