<?php

function mantis_recommend_html()
{
	ob_start();

	$site = get_option('mantis_site_id');

	require(MANTIS_ROOT . '/html/publisher/recommend.php');

	$html = ob_get_contents();

	ob_end_clean();

	return $html;
}

function mantis_recommend_comments($file)
{
	global $mantis_comments;

	if ($mantis_comments) {
		return $mantis_comments;
	}

	$mantis_comments = $file;

	return MANTIS_ROOT . '/comments.php';
}

function mantis_recommend_after($content)
{
	if (is_home()) {
		return $content;
	}

	return $content . mantis_recommend_html();
}

function mantis_recommend_render()
{
	echo mantis_recommend_html();
}

function mantis_recommend()
{
	$location = get_option('mantis_recommend');

	switch ($location) {
		case 'after_content':
			add_filter('the_content', 'mantis_recommend_after', 2);
			break;
		case 'before_comments':
		case 'after_comments':
			add_filter('comments_template', 'mantis_recommend_comments');
			break;
	}
}

add_action('init', 'mantis_recommend');