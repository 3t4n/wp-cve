<?php

namespace GSBEH;
?>

<div class="gs-containeer">
	<div class="gs-roow"><?php

$gsbeh_user_url  = "https://www.behance.net/" . $shortcode_settings['userid'] . "/projects" ;
$gs_behance_user = plugin()->scrapper->scrape( $gsbeh_user_url );

if( isset( $gs_behance_user['http_code'] ) && $gs_behance_user['http_code'] == '404' ) {
	echo __( "Data Not Found", 'gs-behance' );
	return;
}

if (is_array( $gs_behance_user )) {

	$gs_behance_user = $gs_behance_user['profile']['owner'];
	$beh_user        = $gs_behance_user['first_name'];
	$beh_stats       = $gs_behance_user['stats'];
	$beh_fields      = $gs_behance_user['fields'];
	$beh_s_links     = $gs_behance_user['social_links'];
	$beh_secs        = $gs_behance_user['sections'];
	$beh_links       = $gs_behance_user['links'];
	$beh_user_image  = $gs_behance_user['images'][138];
?>
	<div class="gs-beh-profile">
		<div class="gs-gs-col-md-3">
			<div class= "gs-beh-profile-top">

				<img class="profile-pic" src="<?php echo esc_url($beh_user_image); ?>"/>
				<h1><?php echo esc_html($beh_user); ?></h1>
				<div class="profile-details">
					<div><i class="fa fa-user"></i><?php echo esc_html($gs_behance_user['username']); ?></div><?php
					$beh_city = !empty($gs_behance_user['city']) ? $gs_behance_user['city'] : '';
					if ( ! empty( $beh_city ) ) : ?>
					<div><i class="fa fa-map-marker"></i><?php echo esc_html($beh_city); ?></div><?php
					endif;

					$beh_country = !empty($gs_behance_user['country']) ? $gs_behance_user['country'] : '';
					if ( ! empty( $beh_country ) ) : ?>
					<div><i class="fa fa-flag"></i><?php echo esc_html($beh_country); ?></div><?php
					endif;

					$beh_occup = !empty($gs_behance_user['occupation']) ? $gs_behance_user['occupation'] : '';
					if ( ! empty( $beh_occup ) ) : ?>
					<div><i class="fa fa-folder-open"></i><?php echo esc_html($beh_occup); ?></div><?php
					endif;

					$beh_web = !empty($gs_behance_user['website']) ? $gs_behance_user['website'] : '';
					if ( ! empty( $beh_web ) ) : ?>
					<div><i class="fa fa-link"></i><?php echo esc_html($beh_web); ?></div><?php
					endif;
?>
				</div>

			</div>

				<ul class="profile-stats">
					<li><span> Project Views: </span> <span><?php echo esc_html($beh_stats['views']); ?> </span></li>
					<li><span> Project Appreciations: </span> <span><?php echo esc_html($beh_stats['appreciations']); ?></span></li>
					<li><span> Project Followers: </span> <span><?php echo esc_html($beh_stats['followers']); ?></span></li>
					<li><span> Project Following: </span> <span><?php echo esc_html($beh_stats['following']); ?></span></li>
				</ul>
		</div>

	<div class="gs-gs-col-md-9">
<?php
	if ( $beh_fields ) { ?>

		<ul class="beh-focus"><div><?php echo __( 'Focus', 'gs-behance' ); ?></div>
<?php
			foreach( $beh_fields as $field ) { ?>
				<li><?php echo esc_html($field['name']); ?></li><?php
			}
?>
		</ul>
	<?php
	}

	$beh_ref = !empty($beh_links[0]['url']) ? $beh_links[0]['url'] : '';

	if ( ! empty( $beh_ref ) ) : ?>

		<ul class="beh-ref"><div>Web References :</div>

			<li><a href="<?php echo esc_url($beh_links[0]['url']); ?>" target="_blank"><?php echo esc_html($beh_links[0]['title']); ?></a></li>
<?php
			if ( array_key_exists( 1, $beh_links ) ) { ?>
				<li><a href="<?php echo esc_url($beh_links[1]['url']); ?>" target="_blank"><?php echo esc_html($beh_links[1]['title']); ?></a></li><?php
			} ?>

		</ul>
		<?php
	endif;

	if ( ! empty( $web_url_0 ) ) : ?>
		<ul class="beh-url"><div>On the Web : </div><?php
		$web_url_0    = !empty($beh_s_links[0]['url']) ? $beh_s_links[0]['url'] : '';
		$web_url_0ser = !empty($beh_s_links[0]['service_name']) ? $beh_s_links[0]['service_name'] : '';
		$web_url_1    = !empty($beh_s_links[1]['url']) ? $beh_s_links[1]['url'] : '';
		$web_url_1ser = !empty($beh_s_links[1]['service_name']) ? $beh_s_links[1]['service_name'] : '';
		$web_url_2    = !empty($beh_s_links[2]['url']) ? $beh_s_links[2]['url'] : '';
		$web_url_2ser = !empty($beh_s_links[2]['service_name']) ? $beh_s_links[2]['service_name'] : '';
		$web_url_3    = !empty($beh_s_links[3]['url']) ? $beh_s_links[3]['url'] : '';
		$web_url_3ser = !empty($beh_s_links[3]['service_name']) ? $beh_s_links[3]['service_name'] : '';
		$web_url_4    = !empty($beh_s_links[4]['url']) ? $beh_s_links[4]['url'] : '';
		$web_url_4ser = !empty($beh_s_links[4]['service_name']) ? $beh_s_links[4]['service_name'] : '';

		if ( ! empty( $web_url_0 ) ) : ?>
			<li><a href="<?php echo esc_url($web_url_0); ?>" target="_blank"><?php echo esc_html($web_url_0ser); ?></a></li><?php
		endif;
		if ( ! empty( $web_url_1 ) ) : ?>
			<li><a href="'. $web_url_1 .'" target="_blank"><?php echo esc_html($web_url_1ser); ?></a></li><?php
		endif;
		if ( ! empty( $web_url_2 ) ) : ?>
			<li><a href="<?php echo esc_attr($web_url_2); ?>" target="_blank"><?php echo esc_html($web_url_2ser); ?></a></li><?php
		endif;
		if ( ! empty( $web_url_3 ) ) : ?>
			<li><a href="<?php echo esc_url($web_url_3); ?>" target="_blank"><?php echo esc_html($web_url_3ser); ?></a></li><?php
		endif;
		if ( ! empty( $web_url_4 ) ) : ?>
			<li><a href="<?php echo esc_url($web_url_4); ?>" target="_blank"><?php echo esc_html($web_url_4ser); ?></a></li><?php
		endif; ?>
		</ul><?php
	endif;


	$beh_abt = !empty($beh_secs['About']) ? $beh_secs['About'] : '';
	if ( ! empty( $beh_abt ) ) : ?>
		<div class="pro-info">About:</div>
		<p><?php echo esc_html($beh_abt); ?></p><?php
	endif;

	$beh_abt_me = !empty($beh_secs['About Me']) ? $beh_secs['About Me'] : '';
	if ( ! empty( $beh_abt_me ) ) : ?>
		<div class="pro-info">About Me:</div>
		<p><?php echo esc_html($beh_abt_me); ?></p><?php
	endif;

	$beh_get_con = !empty($beh_secs['Get Contact ']) ? $beh_secs['Get Contact '] : '';
	if ( ! empty( $beh_get_con ) ) : ?>
		<div class="pro-info">Get Contact:</div>
		<p><?php echo esc_html($beh_get_con); ?></p><?php
	endif;

	$beh_work = !empty($beh_secs['My work']) ? $beh_secs['My work'] : '';
	if ( ! empty( $beh_work ) ) : ?>
		<div class="pro-info">My work:</div>
		<p><?php echo esc_html($beh_work); ?></p><?php
	endif; ?>
	</div>
	</div>
<?php
} // end array
?>

<div class="gs-containeer">
<div class="gs-roow"><?php
foreach ( $gs_behance_shots as $gs_beh_single_shot ) {
	$bfields = unserialize( $gs_beh_single_shot['bfields'] );
	if ( ! empty( $atts['field'] ) ) {
		if ( in_array( $atts['field'],  array_column($bfields,'name') ) ) { ?>
			<div class="<?php echo esc_attr($columnClasses); ?> beh-projects">

			<div class="beh-img-tit-cat"><?php
			echo plugin()->helpers->get_shot_thumbnail( $gs_beh_single_shot['thum_image'], '' ); ?>

			<div class="beh-tit-cat">
			<span class="beh-proj-tit"><?php echo esc_html($gs_beh_single_shot['name'] ); ?></span>
			<ul class="beh-cat"><i class="fa fa-tags"></i><?php
			foreach ( $bfields as $bcats ) { ?>
					<li><?php echo esc_attr($bcats['name']); ?></li>
<?php
			} ?>
			</ul>

				<a class="beh_hover" href="<?php echo esc_url($gs_beh_single_shot['url']); ?>" target="<?php echo esc_attr($shortcode_settings['link_target']); ?> ">
				<i class="fa fa-paper-plane-o"></i>
				</a>
				</div>

				</div>

				<ul class="beh-stat">
				<li class="beh-app"><i class="fa fa-thumbs-o-up"></i><span class="number"><?php echo number_format_i18n( $gs_beh_single_shot['blike'] ); ?></span></li>
				<li class="beh-views"><i class="fa fa-eye"></i><span class="number "><?php echo number_format_i18n( $gs_beh_single_shot['bview'] ); ?></span></li>
				<li class="beh-comments"><i class="fa fa-comment-o"></i><span class="number"><?php echo number_format_i18n( $gs_beh_single_shot['bcomment'] ); ?></span></li>
				</ul>

			</div>
<?php

		} // array
	} else { ?>
		<div class="<?php echo esc_attr($columnClasses); ?> beh-projects">

			<div class="gs_beh-content-wrap">
				
				<a href="<?php echo esc_url($gs_beh_single_shot[ 'url' ]); ?>" target="<?php echo esc_attr($shortcode_settings['link_target']); ?>"><?php
				echo plugin()->helpers->get_shot_thumbnail( $gs_beh_single_shot['thum_image'], '' ); ?>
				</a>


				<div class="gs_beh-content">

				<div class="gs_beh-content-left">
					
					<h4 class="gs_beh-user-name"><?php echo esc_html( $gs_beh_single_shot['beusername'] ); ?></h4>

				</div>

				<ul class="gs_beh-credentials">
					<li class="beh-app"><i class="fa fa-thumbs-o-up"></i><span class="number"><?php echo number_format_i18n( $gs_beh_single_shot['blike'] ); ?></span></li>

					<li class="beh-views"><i class="fa fa-eye"></i><span class="number "><?php echo number_format_i18n( $gs_beh_single_shot['bview'] ); ?></span></li>

					<li class="beh-comments"><i class="fa fa-comment-o"></i><span class="number"><?php echo number_format_i18n( $gs_beh_single_shot['bcomment'] ); ?></span></li>

				</ul>


				</div>

			</div>

		</div><?php
	}
} // foreach
?>
</div><?php
do_action( 'gs_behance_custom_css' ); ?>
</div>
