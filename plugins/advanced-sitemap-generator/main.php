<?php
/*
 Plugin Name: Advanced Sitemap Generator
 Plugin URI: http://www.csschopper.com/
 Description: powerfull plugin to show all your pages and post on front end in a sitemap.
 Version: 1.1.1
 Author: Deepak Tripathi
 Author URI: http://www.csschopper.com/
 Author Email: deepak@sparxtechnologies.com
 License: GPL
 */


// Hook for adding admin menus
add_action('admin_menu', 'custom_function');

function custom_function()
{
	add_options_page('sitemap', 'sitemap', 'manage_options', 'sitemapgenerator', 'sitemapfunction');
}

function sitemapfunction()
{
	?>
<div>
	<h2>Sitemap Setting</h2>
</div>
<p> This plugin is the most powerfull plugin which easily display your
	post and page through shortcode on front end.You just need to put
	shortcode([sitemap]) on your page/post. </p>
<p> If you want to exclude pages then put ([sitemap excludepage="1,4"])
	where 1,4 are the page id seperated by the comma's. </p>
<p> If you want to exclude post from a specific categories then put ([sitemap
	excludepage="1,4" excludecat="6,3"]) where 6,3 are the category id
	seperated by the comma's. </p>
<p> If you want to exclude specific posts then put ([sitemap excludepage="1,4"
	excludecat="6,3" excludepost="1,183"]) where 1,183 are the post id
	seperated by the comma's. </p>
<p> If you want to show custom link "home" then put ([sitemap excludepage="1,4"
	excludecat="6,3" excludepost="1,183" home="yes"]) </p>
<p> If you want to show specific number of post then put ([sitemap excludepage="1,4"
	excludecat="6,3" excludepost="1,183" home="yes" postcount="4"]) where 4 is
        the number of post to show </p>
<p> If you don't want to show any of the post put ([sitemap
	showpost="no"]) </p>
</br>
</br>
<div>
	<h2>Screenshot1</h2>
	<div>
		<img
			src="<?php echo plugins_url() ?>/advanced-sitemap-generator/images/screenshot-1.jpg"
			width="1100" />
	</div>
	</br>
	<hr>
	</br>
	<h2>Screenshot2</h2>
	<div>
		<img
			src="<?php echo plugins_url() ?>/advanced-sitemap-generator/images/screenshot-2.jpg"
			width="1100" />
	</div>
	</br>
	<hr>
	</br> </br>
</div>

	<?php

}
add_shortcode( 'sitemap', 'sitemap_function' );

function sitemap_function($atts)
{
	$arr=array();
	$arr_new=array();
	$str=array();
	$excludepages='';
	$excludecat='';
	$show='';
	extract(shortcode_atts(array(
			"excludepage" => 0,
                        "excludecat" => 0,
                        "excludepost" => 0,
                        "showpost"    =>'' ,
                        "home"       => '',
                        'postcount'  => 0,
	), $atts));
	if(isset($atts['excludepage']))
	$excludepages=$atts['excludepage'];
        if(isset($atts['home']))
        $homelink= $atts['home'];  
	$args=array(
        'exclude'  => $excludepages,
        'title_li' => '',
        'echo'          => false
	);
	$display =  '<div class="manage-pagepost"><ul class="manage_page"><h3>Pages</h3>';
	if($homelink=='yes')
        {
            $display.='<li><a href="'.get_bloginfo("url").'">Home</li>';
        }
        $display .= wp_list_pages($args);
	$display .= '</ul>';
	if(isset($atts['showpost']))
	$show=$atts['showpost'];
	if($show != 'no')
	{
            $display.='<ul class="manage_post">';
		if(isset($atts['excludecat']))
		$arr=explode(',',$atts['excludecat']);
		for($i=0;$i<count($arr);$i++)
		{
			$arr_new[]='-'.$arr[$i];
		}
		 
		$excludecat=implode(',',$arr_new);

		if(isset($atts['excludepost']))
		$str=explode(',',$atts['excludepost']);
		if(isset($atts['postcount']))
                $postcount= $atts['postcount'];
                $args = array(
	'post_type' => 'post',
	'cat' => $excludecat,
        'posts_per_page' => $postcount,
	'post__not_in' => $str

		);
		query_posts($args);
		$flag=1;
		if (have_posts()) : while (have_posts()) : the_post();
		if($flag==1)
		{
			$display .='<h3>posts</h3>';
		}
		$display .= '<li><a href="'.get_permalink().'">'.get_the_title().'</a></li>';
		$flag++;
		endwhile;
		endif;
		wp_reset_query();
		$display .= '</ul>';
	}
        $display.='</div>';
	return $display;

}
?>
