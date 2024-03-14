<?php 

if (!defined('ABSPATH')) { exit; } 

// Variables 
$active = 'categorify';
$max	= 6;
$URL	= CATEGORIFY_PLUGIN_URL;
$ver	= CATEGORIFY_PLUGIN_VERSION;
$site	= 'https://frenify.com/';
$ttext	= CATEGORIFY_TEXT_DOMAIN;
$previewText = esc_html__('Preview', $ttext);
$smile	= $URL.'inc/settings/assets/img/smile.svg';
$logo	= $URL.'inc/settings/assets/img/frenify-logo.svg';
$about	= '<p><b>Frenify</b> was founded in 2017. The company began working with the first customers, giving them the opportunity to purchase high-quality HTML templates.</p><p>The company’s products began to grow in terms of complexity and aesthetics. Frenify currently has a wide range of HTML templates, WordPress themes, WordPress plugins, Photoshop projects; paid and absolutely free products.</p><p>Design projects are unique and aesthetically pleasing based on customer requirements. Visit our website to get acquainted with our products. Thank you so much for being with us.</p>';

// Functions

if( !function_exists('frenify__shuffle_assoc') ){
	function frenify__shuffle_assoc($list) { 
		if (!is_array($list)){ return $list; }
		$keys = array_keys($list); 
		shuffle($keys); 
		$random = array(); 
		foreach ($keys as $key) { 
			$random[$key] = $list[$key]; 
		}
		return $random; 
	}
}

// List
$list 	= [
	'vivaco' => [
		'title'	=> 'Vivaco',
		'name'	=> 'Multipurpose Creative WordPress Theme',
		'url'	=> 'https://themeforest.net/item/vivaco-multipurpose-creative-wordpress-theme/31688792',
		'image'	=> $URL.'inc/settings/assets/img//item-vivaco.jpg'
	],
	'becipe' => [
		'title'	=> 'Becipe',
		'name'	=> 'Recipe Blogging WordPress Theme',
		'url'	=> 'https://themeforest.net/item/becipe-recipe-blogging-wordpress-theme/29029917',
		'image'	=> $URL.'inc/settings/assets/img/item-becipe.jpg'
	],
	'artemiz' => [
		'title'	=> 'Artemiz',
		'name'	=> 'Blog & Podcast WordPress Theme',
		'url'	=> 'https://themeforest.net/item/artemiz-blog-podcast-wordpress-theme/28455063',
		'image'	=> $URL.'inc/settings/assets/img/item-artemiz.jpg'
	],
	'arlo' => [
		'title'	=> 'Arlo',
		'name'	=> 'Portfolio WordPress Theme',
		'url'	=> 'https://themeforest.net/item/arlo-portfolio-wordpress-theme/25172061',
		'image'	=> $URL.'inc/settings/assets/img/item-arlo.jpg'
	],
	'mobixo' => [
		'title'	=> 'Mobixo',
		'name'	=> 'Industry WordPress Theme',
		'url'	=> 'https://themeforest.net/item/mobixo-industry-wordpress-theme/24942315',
		'image'	=> $URL.'inc/settings/assets/img/item-mobixo.jpg'
	],
	'cron' => [
		'title'	=> 'Cron',
		'name'	=> 'Industry WordPress Theme',
		'url'	=> 'https://themeforest.net/item/cron-industry-wordpress-theme/24533803',
		'image'	=> $URL.'inc/settings/assets/img/item-cron.jpg'
	],
	'industify' => [
		'title'	=> 'Industify',
		'name'	=> 'Industry WordPress Theme',
		'url'	=> 'https://themeforest.net/item/industify-industry-wordpress-theme/22729865',
		'image'	=> $URL.'inc/settings/assets/img/item-industify.jpg'
	],
	'glax' => [
		'title'	=> 'Glax',
		'name'	=> 'Industry WordPress Theme',
		'url'	=> 'https://themeforest.net/item/glax-industry-wordpress-theme/22459403',
		'image'	=> $URL.'inc/settings/assets/img/item-glax.jpg'
	],
	'constructify' => [
		'title'	=> 'Constructify',
		'name'	=> 'Construction WordPress Theme',
		'url'	=> 'https://themeforest.net/item/constructify-construction-building-wordpress-theme/22328771',
		'image'	=> $URL.'inc/settings/assets/img/item-constructify.jpg'
	],
	'buildify' => [
		'title'	=> 'Buildify',
		'name'	=> 'Construction WordPress Theme',
		'url'	=> 'https://themeforest.net/item/buildify-construction-building-wordpress-theme/21742481',
		'image'	=> $URL.'inc/settings/assets/img/item-buildify.jpg'
	],
	'fotofly' => [
		'title'	=> 'Fotofly',
		'name'	=> 'Photography WordPress Theme',
		'url'	=> 'https://themeforest.net/item/fotofly-photography-wordpress-theme/21190239',
		'image'	=> $URL.'inc/settings/assets/img/item-fotofly.jpg'
	],
	'photobuddy' => [
		'title'	=> 'Photobuddy',
		'name'	=> 'Photography WordPress Theme',
		'url'	=> 'https://themeforest.net/item/photobuddy-photography-wordpress-theme/20432690',
		'image'	=> $URL.'inc/settings/assets/img/item-photobuddy.jpg'
	],
	'bookmify' => [
		'title'	=> 'Bookmify',
		'name'	=> 'Appointment Booking WordPress Plugin',
		'url'	=> 'https://codecanyon.net/item/bookmify-appointment-booking-wordpress-plugin/23837899',
		'image'	=> $URL.'inc/settings/assets/img/item-bookmify.jpg'
	],
	'modulify' => [
		'title'	=> 'Modulify',
		'name'	=> 'Modules Addon for Elementor Page Builder',
		'url'	=> 'https://codecanyon.net/item/modulify-modules-addon-for-elementor-page-builder/22595533',
		'image'	=> $URL.'inc/settings/assets/img/item-modulify.jpg'
	],
	'magazinify' => [
		'title'	=> 'Magazinify',
		'name'	=> 'News Addon for Elementor Page Builder',
		'url'	=> 'https://codecanyon.net/item/magazinify-news-addon-for-elementor-page-builder/22194326',
		'image'	=> $URL.'inc/settings/assets/img/item-magazinify.jpg'
	],
	'projectify' => [
		'title'	=> 'Projectify',
		'name'	=> 'Project Addon for Elementor Page Builder',
		'url'	=> 'https://codecanyon.net/item/projectify-project-addon-for-elementor-page-builder/21537292',
		'image'	=> $URL.'inc/settings/assets/img/item-projectify.jpg'
	],
	'categorify' => [
		'title'	=> 'Categorify',
		'name'	=> 'WordPress Media Library Category & File Manager',
		'url'	=> 'https://frenify.com/project/categorify/',
		'image'	=> $URL.'inc/settings/assets/img/item-categorify.jpg'
	],
];
?>

<div class="frenify__welcome">

	<div class="frenify__welcome_header">
		<div class="fn__container">
			<div class="header_v"><span><?php echo esc_html($ver);?></span></div>
			<div class="info_box">
				<div class="info_left">
					<h5 class="fn__subtitle">Thank you for choosing</h5>
					<h3 class="fn__title"><?php echo esc_html($active);?></h3>
					<a href="<?php echo esc_url($list[$active]['url']); ?>" class="fn__more" target="_blank">More Info</a>
				</div>
				<div class="info_right">
					<div class="smile_icon">
						<img class="fn__svg" src="<?php echo esc_url($smile);?>" alt="">
					</div>
					<div class="img_box">
						<a href="<?php echo esc_url($site);?>" target="_blank"></a>
						<img class="fn__svg" src="<?php echo esc_url($logo);?>" alt="">
						<div class="fn__tooltip">
							<?php echo wp_kses($about,'post');?>
						</div>
					</div>
					<div class="fn__desc">
						<p>This item has been developed by <a href="<?php echo esc_url($site);?>" target="_blank">Frenify</a></p>
					</div>
				</div>
			</div>
			<h5 class="bottom_title">Our Other Works</h5>
		</div>
	</div>
	
	<?php if($active != 'categorify'){ ?>
	<div class="frenify__welcome__categorify">
		<div class="fn__container">
			<div class="title_holder">
				<h3 class="fn__title">Categorify</h3>
				<p class="fn__desc">Categorify is a WordPress plugin that enables users to quickly organize all of their Media files into categories.</p>
				<a href="<?php echo esc_attr($list['categorify']['url']);?>" class="fn__more" target="_blank">More Info</a>
			</div>
			<div class="video_holder">
				<img src="<?php echo esc_attr($list['categorify']['image']);?>" alt="">
				<a href="https://www.youtube.com/watch?v=pmNkbjC3xkU" class="popup-youtube"></a>
			</div>
		</div>
	</div>
	<?php } ?>
	
	<div class="frenify__welcome_list">
		<ul>
			<?php 
				// remove active element
				unset($list[$active]);
				// remove categorify too
				unset($list['categorify']);
				// shuffle array
				$list 		= frenify__shuffle_assoc($list);
				// leave only $max elements of the array
				$list 		= array_slice($list, 0, $max);
				
				$html		= '';
				foreach($list as $key => $item){
					$image 	= $item['image'];
					$url 	= $item['url'];
					$title	= $item['title'];
					$name	= $item['name'];
					
					$html  .= '<li>';
						$html  .= '<div class="item">';
					
							$html  .= '<div class="img_holder">';
								$html  .= '<a href="'.$url.'" target="_blank">';
									$html  .= '<img class="fn__img" src="'.$image.'" alt="" />';
									$html  .= '<div class="overlay">';
										$html  .= '<img class="fn__svg" src="'.$URL.'inc/settings/assets/img/arrow.svg" alt="">';
										$html  .= '<span>'.$previewText.'</span>';
									$html  .= '</div>';
								$html  .= '</a>';
							$html  .= '</div>';
					
							$html  .= '<div class="title_holder">';
								$html  .= '<h3 class="fn__title"><a href="'.$url.'" target="_blank">'.$title.'</a></h3>';
								$html  .= '<p class="fn__desc">'.$name.'</p>';
							$html  .= '</div>';
					
						$html  .= '</div>';
					$html  .= '</li>';
				}
				echo wp_kses($html,'post');
			?>
		</ul>
		<a href="<?php echo esc_url($site); ?>" class="fn__more" target="_blank">See All Items</a>
	</div>
	
	
</div>