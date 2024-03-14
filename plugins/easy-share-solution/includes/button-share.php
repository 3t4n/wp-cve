<?php

/**
 * Easy-share-solution-widget
 *
 * @link              http://codecanyon.net/user/expert-wp
 * @since             1.0.0
 * @package           Easy share solution
 *
 * All share button and all position display set here.
 *
 */
// Get all option from the setting page
if ($options = get_option('easy_share_solution_settings')) {
	$options = get_option('easy_share_solution_settings');
}
if (isset($options['btn_position']['four'])) {
	$position_four = $options['btn_position']['four'];
} else {
	$position_four = 'one';
}
if ($position_four == 'four') {
	if (!function_exists('born_share_content_buttons')) {
		function born_share_content_buttons($content)
		{
			if ($options = get_option('easy_share_solution_settings')) {
				$options = get_option('easy_share_solution_settings');
			}
			if ($other = get_option('easy_share_solution_other_option')) {
				$other = get_option('easy_share_solution_other_option');
			}
			$name_one = (isset($options['social_one'])) ? $options['social_one'] : 'Facebook';
			$name_two = (isset($options['social_two'])) ? $options['social_two'] : 'Twitter';
			$name_three = (isset($options['social_three'])) ? $options['social_three'] : 'Tumblr';
			$name_four = (isset($options['social_four'])) ? $options['social_four'] : 'Linkedin';
			$name_five = (isset($options['social_five'])) ? $options['social_five'] : 'Pinterest';
			$name_six = (isset($options['social_six'])) ? $options['social_six'] : 'noselected';
			$name_seven = (isset($options['social_seven'])) ? $options['social_seven'] : 'noselect';
			$show_hide = (isset($options['show_hide'])) ? $options['show_hide'] : 'yes';
			$btn_type = (isset($options['btn_type'])) ? $options['btn_type'] : 'textandicon';
			$select_count = (isset($other['select_count'])) ? $other['select_count'] : 'hover';
			$all_sharebtn = (isset($other['all_sharebtn'])) ? $other['all_sharebtn'] : 'yes';

			if ($btn_type == 'icon' || $btn_type == 'round_icon') {
				$icononly = 'text-left';
			} else {
				$icononly = 'text';
			}
			if ($btn_type == 'round_icon') {
				$iconround = 'round-icons';
			} else {
				$iconround = 'icon';
			}
			if ($select_count == 'yes') {
				$countshow = 'count_show';
			} else {
				$countshow = 'show';
			}
			// Add sharing button at the end of post content

			$content .= ' <div id="bshare-social" class="baby-sideshare share-content after-content ' . $iconround . ' ' . $countshow . '">';
			if ($show_hide == 'yes') {
				$content .= '<div class="share_hide_show content_hide_show"> 
			<i class="icon-share"></i>
		</div>';
			}
			$content .= '<div class="count-set-bottom ' . $icononly . '">
<button class="tottal-count bshare-' . $name_one . ' share s_' . $name_one . '" type="button">';
			if ($select_count !== 'no') :
				if ($name_one == 'Facebook' || $name_one == 'Linkedin' || $name_one == 'Pinterest' || $name_one == 'Tumblr'  || $name_one == 'Myworld' || $name_one == 'Vk' || $name_one == 'Ok') :
				// $content .= '<span class="baby-count-bottom counter c_'.$name_one.'"></span>';
				endif;
			endif;
			$content .= '<i class="icon-' . $name_one . '"></i>&nbsp;' . $name_one . '
			</button>
		</div>
		<div class="count-set-bottom ' . $icononly . '">
			<button class="tottal-count bshare-' . $name_two . ' share s_' . $name_two . '" type="button">';
			if ($select_count !== 'no') :
				if ($name_two == 'Facebook' || $name_two == 'Linkedin' || $name_two == 'Pinterest' || $name_two == 'Tumblr'  || $name_two == 'Myworld' || $name_two == 'Vk' || $name_two == 'Ok') :
				//	$content .= '<span class="baby-count-bottom counter c_'.$name_two.'"></span>';
				endif;
			endif;
			$content .= '<i class="icon-' . $name_two . '"></i>&nbsp;' . $name_two . '
			</button>
		</div>
		<div class="count-set-bottom ' . $icononly . '">
		<button class="tottal-count bshare-' . $name_three . ' share s_' . $name_three . '" type="button">';
			if ($select_count !== 'no') :
				if ($name_three == 'Facebook' || $name_three == 'Linkedin' || $name_three == 'Pinterest' || $name_three == 'Tumblr'  || $name_three == 'Myworld' || $name_three == 'Vk' || $name_three == 'Ok') :
				//	$content .= '<span class="baby-count-bottom counter c_'.$name_three.'"></span>';
				endif;
			endif;
			$content .= '<i class="icon-' . $name_three . '"></i>&nbsp;' . $name_three . '
			</button>
		</div>
		<div class="count-set-bottom ' . $icononly . '">
		<button class="tottal-count bshare-' . $name_four . ' share s_' . $name_four . '" type="button">';
			if ($select_count !== 'no') :
				if ($name_four == 'Facebook' || $name_four == 'Linkedin' || $name_four == 'Pinterest' || $name_four == 'Tumblr'  || $name_four == 'Myworld' || $name_four == 'Vk' || $name_four == 'Ok') :
				//	$content .= '<span class="baby-count-bottom counter c_'.$name_four.'"></span>';
				endif;
			endif;
			$content .= '<i class="icon-' . $name_four . '"></i>&nbsp;' . $name_four . '
			</button>
		</div>
		<div class="count-set-bottom ' . $icononly . '">
		
			<button class="tottal-count bshare-' . $name_five . ' share s_' . $name_five . '" type="button">';
			if ($select_count !== 'no') :
				if ($name_five == 'Facebook' || $name_five == 'Linkedin' || $name_five == 'Pinterest' || $name_five == 'Tumblr'  || $name_five == 'Myworld' || $name_five == 'Vk' || $name_five == 'Ok') :
				// $content .= '<span class="baby-count-bottom counter c_'.$name_five.'"></span>';
				endif;
			endif;
			$content .= '<i class="icon-' . $name_five . '"></i>&nbsp;' . $name_five . '
			</button>
		</div>';
			if ($name_six !== 'noselected') :
				$content .= '<div class="count-set-bottom ' . $icononly . '">';
				if ($select_count !== 'no') :
					if ($name_six == 'Facebook' || $name_six == 'Linkedin' || $name_six == 'Pinterest' || $name_six == 'Tumblr'  || $name_six == 'Myworld' || $name_six == 'Vk' || $name_six == 'Ok') :
					//	$content .= '<span class="baby-count-bottom counter c_'.$name_six.'"></span>';
					endif;
				endif;
				$content .= '<button class="tottal-count bshare-' . $name_six . ' share s_' . $name_six . '" type="button">
				<i class="icon-' . $name_six . '"></i>&nbsp;' . $name_six . '
			</button>
		</div>';
			endif;
			if ($name_seven !== 'noselect') :
				$content .= '<div class="count-set-bottom ' . $icononly . '">
	
			<button class="tottal-count bshare-' . $name_seven . ' share s_' . $name_seven . '" type="button">';
				if ($select_count !== 'no') :
					if ($name_seven == 'Facebook' || $name_seven == 'Linkedin' || $name_seven == 'Pinterest' || $name_seven == 'Tumblr'  || $name_seven == 'Myworld' || $name_seven == 'Vk' || $name_seven == 'Ok') :
					//	$content .= '<span class="baby-count-bottom counter c_'.$name_seven.'"></span>';
					endif;
				endif;
				$content .= '<i class="icon-' . $name_seven . '"></i>&nbsp;' . $name_seven . '
			</button>
		</div>';
			endif;
			if ($all_sharebtn == 'yes') :
				$content .= '<div class="count-set-bottom ' . $icononly . '">
			<button class="my_popup_open">' . __('All share', 'easy-share-solution') . '&nbsp;<i class="icon-more"></i></button>
		</div>';
			endif;
			$content .= '</div>';

			return $content;
		};
		add_filter('the_content', 'born_share_content_buttons');
	}
}


if (!function_exists('easy_share_solution_buton_set')) :
	function easy_share_solution_buton_set()
	{
		if ($other = get_option('easy_share_solution_other_option')) {
			$other = get_option('easy_share_solution_other_option');
		}
		$pop_btn = (isset($other['btn_pup_style'])) ? $other['btn_pup_style'] : 'squire';

?>

		<div id="my_popup">
			<div class="shar-button-set <?php if ($pop_btn == 'round') : ?>round-icons<?php endif; ?>">
				<div class="share-btnall">
					<button class="button-popup share bshare-Facebook s_Facebook"><i class="icon-Facebook"></i></button>
					<button class="button-popup share bshare-Twitter s_Twitter"><i class="icon-Twitter"></i></button>
					<button class="button-popup share bshare-Tumblr s_Tumblr"><i class="icon-Tumblr"></i></button>
					<button class="button-popup share bshare-Linkedin s_Linkedin"><i class="icon-Linkedin"></i></button>
					<button class="button-popup share bshare-Pinterest s_Pinterest"><i class="icon-Pinterest"></i></button>
					<button class="button-popup share bshare-Buffer s_Buffer"><i class="icon-Buffer"></i></button>
					<button class="button-popup share bshare-Digg s_Digg"><i class="icon-Digg"></i></button>
					<button class="button-popup share bshare-Pocket s_Pocket"><i class="icon-Pocket"></i></button>
					<button class="button-popup share bshare-Tumblr s_Tumblr"><i class="icon-Tumblr"></i></button>
					<button class="button-popup share bshare-Blogger s_Blogger"><i class="icon-Blogger"></i></button>
					<button class="button-popup share bshare-Myspace s_Myspace"><i class="icon-Myspace"></i></button>
					<button class="button-popup share bshare-Delicious s_Delicious"><i class="icon-Delicious"></i></button>
					<button class="button-popup share bshare-Ok s_Ok"><i class="icon-Ok"></i></button>
					<button class="button-popup share bshare-Reddit s_Reddit"><i class="icon-Reddit"></i></button>
					<button class="button-popup share bshare-Aim s_Aim"><i class="icon-Aim"></i></button>
					<button class="button-popup share bshare-Wordpress s_Wordpress"><i class="icon-Wordpress"></i></button>
					<button class="button-popup share bshare-Friendfeed s_Friendfeed"><i class="icon-Friendfeed"></i></button>
					<button class="button-popup share bshare-Hackernews s_Hackernews"><i class="icon-Hackernews"></i></button>
					<button class="button-popup share bshare-Plurk s_Plurk"><i class="icon-Plurk"></i></button>
					<button class="button-popup share bshare-Stumbleupon s_Stumbleupon"><i class="icon-Stumbleupon"></i></button>
					<button class="button-popup share bshare-Box s_Box"><i class="icon-Box"></i></button>
					<button class="button-popup share bshare-Gmail s_Gmail"><i class="icon-Gmail"></i></button>
					<button class="button-popup share bshare-Instapaper s_Instapaper"><i class="icon-Instapaper"></i></button>
					<button class="button-popup share bshare-Yahoo s_Yahoo"><i class="icon-Yahoo"></i></button>
					<button class="button-popup share bshare-Vk s_Vk"><i class="icon-Vk"></i></button>
					<button class="button-popup share bshare-Diigo s_Diigo"><i class="icon-Diigo"></i></button>
					<button class="button-popup share bshare-Tumblr s_Tumblr"><i class="icon-Tumblr"></i></button>
					<button class="button-popup share bshare-Amazon s_Amazon"><i class="icon-Amazon"></i></button>
					<button class="button-popup share bshare-Evernote s_Evernote"><i class="icon-Evernote"></i></button>
					<button class="button-popup share bshare-Viadeo s_Viadeo"><i class="icon-Viadeo"></i></button>
					<button class="button-popup share bshare-Mixi s_Mixi"><i class="icon-Mixi"></i></button>
					<button class="button-popup share bshare-Myworld s_Myworld"><i class="icon-Myworld"></i></button>
				</div>
				<!-- Add an optional button to close the popup -->
				<button class="my_popup_close"><?php esc_html_e('Close', 'easy-share-solution'); ?></button>

			</div>
		</div>

		<?php
	}
endif;

if (!function_exists('born_share_side')) :
	function born_share_side()
	{
		// Get all option from the setting page
		if ($options = get_option('easy_share_solution_settings')) {
			$options = get_option('easy_share_solution_settings');
		}
		if ($other = get_option('easy_share_solution_other_option')) {
			$other = get_option('easy_share_solution_other_option');
		}

		$name_one = (isset($options['social_one'])) ? $options['social_one'] : 'Facebook';
		$name_two = (isset($options['social_two'])) ? $options['social_two'] : 'Twitter';
		$name_three = (isset($options['social_three'])) ? $options['social_three'] : 'Tumblr';
		$name_four = (isset($options['social_four'])) ? $options['social_four'] : 'Linkedin';
		$name_five = (isset($options['social_five'])) ? $options['social_five'] : 'Pinterest';
		$name_six = (isset($options['social_six'])) ? $options['social_six'] : 'noselected';
		$name_seven = (isset($options['social_seven'])) ? $options['social_seven'] : 'noselect';
		$show_hide = (isset($options['show_hide'])) ? $options['show_hide'] : 'yes';
		$btn_type = (isset($options['btn_type'])) ? $options['btn_type'] : 'textandicon';
		$btn_top_set = (isset($other['btn_top_set'])) ? $other['btn_top_set'] : 20;
		$select_count = (isset($other['select_count'])) ? $other['select_count'] : 'hover';
		$all_sharebtn = (isset($other['all_sharebtn'])) ? $other['all_sharebtn'] : 'yes';

		if ($position_one = !empty($options['btn_position'])) {
			if (!empty($position_one)) {
				$position_one = !empty($options['btn_position']['one']);
			}
		} else {
			$position_one = 'one';
		}
		if ($position_one == 'one') {

		?>
			<div id="bshare-social" class="baby-sideshare share-left <?php if ($select_count == 'yes') : ?>count_show<?php endif; ?> <?php if ($btn_type == 'round_icon') : ?>round-icons<?php endif; ?>" style="top:<?php echo $btn_top_set; ?>%">
				<div class="count-set-left <?php if ($btn_type == 'icon' || $btn_type == 'round_icon') : ?>text-left<?php endif; ?>">
					<button class="tottal-count bshare-<?php echo $name_one; ?> share s_<?php echo $name_one; ?>" type="button">
						<?php echo $name_one; ?>&nbsp; <i class="icon-<?php echo $name_one; ?>"></i>
						<?php if ($select_count !== 'no') : ?>
							<?php if ($name_one == 'Facebook' || $name_one == 'Linkedin' || $name_one == 'Pinterest' || $name_one == 'Tumblr'  || $name_one == 'Myworld' || $name_one == 'Vk' || $name_one == 'Ok') : ?>
								<!-- <span class="baby-count-left counter c_<?php echo $name_one; ?>"></span> -->
							<?php endif; ?>
						<?php endif; ?>
					</button><!-- button one-->

				</div>
				<div class="count-set-left t-left <?php if ($btn_type == 'icon' || $btn_type == 'round_icon') : ?>text-left<?php endif; ?>">
					<button class="bshare-<?php echo $name_two; ?> share s_<?php echo $name_two; ?>" type="button">
						<?php echo $name_two; ?>&nbsp; <i class="icon-<?php echo $name_two; ?>"></i>
						<?php if ($select_count !== 'no') : ?>
							<?php if ($name_two == 'Facebook' || $name_two == 'Linkedin' || $name_two == 'Pinterest' || $name_two == 'Tumblr'  || $name_two == 'Myworld' || $name_two == 'Vk' || $name_two == 'Ok') : ?>
								<!-- <span class="baby-count-left counter c_<?php echo $name_two; ?>"></span> -->
							<?php endif; ?>
						<?php endif; ?>
					</button><!-- button two-->
				</div>
				<div class="count-set-left t-left <?php if ($btn_type == 'icon' || $btn_type == 'round_icon') : ?>text-left<?php endif; ?>">
					<button class="bshare-<?php echo $name_three; ?> share s_<?php echo $name_three; ?>" type="button">
						<?php echo $name_three; ?>&nbsp; <i class="icon-<?php echo $name_three; ?>"></i>
						<?php if ($select_count !== 'no') : ?>
							<?php if ($name_three == 'Facebook' || $name_three == 'Linkedin' || $name_three == 'Pinterest' || $name_three == 'Tumblr'  || $name_three == 'Myworld' || $name_three == 'Vk' || $name_three == 'Ok') : ?>
								<!-- <span class="baby-count-left counter c_<?php echo $name_three; ?>"></span> -->
							<?php endif; ?>
						<?php endif; ?>
					</button><!-- button three-->
				</div>
				<div class="count-set-left t-left <?php if ($btn_type == 'icon' || $btn_type == 'round_icon') : ?>text-left<?php endif; ?>">
					<button class="bshare-<?php echo $name_four; ?> share s_<?php echo $name_four; ?>" type="button">
						<?php echo $name_four; ?>&nbsp; <i class="icon-<?php echo $name_four; ?>"></i>
						<?php if ($select_count !== 'no') : ?>
							<?php if ($name_four == 'Facebook' || $name_four == 'Linkedin' || $name_four == 'Pinterest' || $name_four == 'Tumblr'  || $name_four == 'Myworld' || $name_four == 'Vk' || $name_four == 'Ok') : ?>
								<!-- <span class="baby-count-left counter c_<?php echo $name_four; ?>"></span> -->
							<?php endif; ?>
						<?php endif; ?>
					</button><!-- button four-->
				</div>
				<div class="count-set-left t-left <?php if ($btn_type == 'icon' || $btn_type == 'round_icon') : ?>text-left<?php endif; ?>">
					<button class="bshare-<?php echo $name_five; ?> share s_<?php echo $name_five; ?>" type="button">
						<?php echo $name_five; ?>&nbsp; <i class="icon-<?php echo $name_five; ?>"></i>
						<?php if ($select_count !== 'no') : ?>
							<?php if ($name_five == 'Facebook' || $name_five == 'Linkedin' || $name_five == 'Pinterest' || $name_five == 'Tumblr'  || $name_five == 'Myworld' || $name_five == 'Vk' || $name_five == 'Ok') : ?>
								<!-- <span class="baby-count-left counter c_<?php echo $name_five; ?>"></span> -->
							<?php endif; ?>
						<?php endif; ?>
					</button><!-- button five-->

				</div>
				<?php if ($name_six !== 'noselected') : ?>
					<div class="count-set-left t-left <?php if ($btn_type == 'icon' || $btn_type == 'round_icon') : ?>text-left<?php endif; ?>">
						<button class="bshare-<?php echo $name_six; ?> share s_<?php echo $name_six; ?>" type="button">
							<?php echo $name_six; ?>&nbsp; <i class="icon-<?php echo $name_six; ?>"></i>
							<?php if ($select_count !== 'no') : ?>
								<?php if ($name_six == 'Facebook' || $name_six == 'Linkedin' || $name_six == 'Pinterest' || $name_six == 'Tumblr'  || $name_six == 'Myworld' || $name_six == 'Vk' || $name_six == 'Ok') : ?>
									<!-- <span class="baby-count-left counter c_<?php echo $name_six; ?>"></span> -->
								<?php endif; ?>
							<?php endif; ?>
						</button><!-- button six-->
					</div>
				<?php endif; ?>
				<?php if ($name_seven !== 'noselect') : ?>
					<div class="count-set-left t-left <?php if ($btn_type == 'icon' || $btn_type == 'round_icon') : ?>text-left<?php endif; ?>">
						<button class="bshare-<?php echo $name_seven; ?> share s_<?php echo $name_seven; ?>" type="button">
							<?php echo $name_seven; ?>&nbsp; <i class="icon-<?php echo $name_seven; ?>"></i>
							<?php if ($select_count !== 'no') : ?>
								<?php if ($name_seven == 'Facebook' || $name_seven == 'Linkedin' || $name_seven == 'Pinterest' || $name_seven == 'Tumblr'  || $name_seven == 'Myworld' || $name_seven == 'Vk' || $name_seven == 'Ok') : ?>
									<!-- <span class="baby-count-left counter c_<?php echo $name_seven; ?>"></span> -->
								<?php endif; ?>
							<?php endif; ?>
						</button><!-- button seven-->
					</div>
				<?php endif; ?>
				<?php if ($all_sharebtn == 'yes') : ?>
					<div class="count-set-left t-left <?php if ($btn_type == 'icon' || $btn_type == 'round_icon') : ?>text-left<?php endif; ?>">
						<button class="my_popup_open"><?php esc_html_e('All share', 'easy-share-solution'); ?>&nbsp;<i class="icon-more"></i></button>
					</div><!-- popup button -->
				<?php endif; ?>
				<?php if ($show_hide == 'yes') : ?>
					<div class="share_hide_show left_hide_show">
						<i class="icon-share"></i>
					</div><!-- share icon -->
				<?php endif; ?>
			</div> <!-- left side share button -->
		<?php }
		if ($position_two = !empty($options['btn_position'])) {
			$position_two = $options['btn_position'];
		}
		if (!empty($position_two['two'])) {
		?>
			<div id="bshare-social" class="baby-sideshare share-right <?php if ($select_count == 'yes') : ?>count_show<?php endif; ?> <?php if ($btn_type == 'round_icon') : ?>round-icons<?php endif; ?>" style="top:<?php echo $btn_top_set; ?>%">
				<div class="count-set-right <?php if ($btn_type !== 'textandicon') : ?>text-left<?php endif; ?>">
					<div class="right-hover">
						<button class="tottal-count bshare-<?php echo $name_one; ?> share s_<?php echo $name_one; ?>" type="button">
							<i class="icon-<?php echo $name_one; ?>"></i>&nbsp;<?php echo $name_one; ?>
						</button><!-- button one-->
						<?php if ($select_count !== 'no') : ?>
							<?php if ($name_one == 'Facebook' || $name_one == 'Linkedin' || $name_one == 'Pinterest' || $name_one == 'Tumblr'  || $name_one == 'Myworld' || $name_one == 'Vk' || $name_one == 'Ok') : ?>
								<!-- <span class="baby-count-right counter c_<?php echo $name_one; ?>"></span> -->
							<?php endif; ?>
						<?php endif; ?>

					</div>
				</div>
				<div class="count-set-right <?php if ($btn_type !== 'textandicon') : ?>text-left<?php endif; ?>">
					<div class="right-hover">
						<button class="tottal-count bshare-<?php echo $name_two; ?> share s_<?php echo $name_two; ?>" type="button">
							<i class="icon-<?php echo $name_two; ?>"></i>&nbsp;<?php echo $name_two; ?>
						</button><!-- button two-->
						<?php if ($select_count !== 'no') : ?>
							<?php if ($name_two == 'Facebook' || $name_two == 'Linkedin' || $name_two == 'Pinterest' || $name_two == 'Tumblr'  || $name_two == 'Myworld' || $name_two == 'Vk' || $name_two == 'Ok') : ?>
								<!-- <span class="baby-count-right counter c_<?php echo $name_two; ?>"></span> -->
							<?php endif; ?>
						<?php endif; ?>

					</div>
				</div>
				<div class="count-set-right <?php if ($btn_type !== 'textandicon') : ?>text-left<?php endif; ?>">
					<div class="right-hover">
						<button class="tottal-count bshare-<?php echo $name_three; ?> share s_<?php echo $name_three; ?>" type="button">
							<i class="icon-<?php echo $name_three; ?>"></i>&nbsp;<?php echo $name_three; ?>
						</button><!-- button three-->
						<?php if ($select_count !== 'no') : ?>
							<?php if ($name_three == 'Facebook' || $name_three == 'Linkedin' || $name_three == 'Pinterest' || $name_three == 'Tumblr'  || $name_three == 'Myworld' || $name_three == 'Vk') : ?>
								<!-- <span class="baby-count-right counter c_<?php echo $name_three; ?>"></span> -->
							<?php endif; ?>
						<?php endif; ?>
					</div>
				</div>
				<div class="count-set-right <?php if ($btn_type !== 'textandicon') : ?>text-left<?php endif; ?>">
					<div class="right-hover">
						<button class="tottal-count bshare-<?php echo $name_four; ?> share s_<?php echo $name_four; ?>" type="button">
							<i class="icon-<?php echo $name_four; ?>"></i>&nbsp;<?php echo $name_four; ?>
						</button><!-- button four-->
						<?php if ($select_count !== 'no') : ?>
							<?php if ($name_four == 'Facebook' || $name_four == 'Linkedin' || $name_four == 'Pinterest' || $name_four == 'Tumblr'  || $name_four == 'Myworld' || $name_four == 'Vk' || $name_four == 'Ok') : ?>
								<!-- <span class="baby-count-right counter c_<?php echo $name_four; ?>"></span> -->
							<?php endif; ?>
						<?php endif; ?>
					</div>
				</div>
				<div class="count-set-right <?php if ($btn_type !== 'textandicon') : ?>text-left<?php endif; ?>">
					<div class="right-hover">
						<button class="tottal-count bshare-<?php echo $name_five; ?> share s_<?php echo $name_five; ?>" type="button">
							<i class="icon-<?php echo $name_five; ?>"></i>&nbsp;<?php echo $name_five; ?>
						</button><!-- button five-->
						<?php if ($select_count !== 'no') : ?>
							<?php if ($name_five == 'Facebook' || $name_five == 'Linkedin' || $name_five == 'Pinterest' || $name_five == 'Tumblr'  || $name_five == 'Myworld' || $name_five == 'Vk' || $name_five == 'Ok') : ?>
								<!-- <span class="baby-count-right counter c_<?php echo $name_five; ?>">0</span> -->
							<?php endif; ?>
						<?php endif; ?>
					</div>
				</div>
				<?php if ($name_six !== 'noselected') : ?>
					<div class="count-set-right <?php if ($btn_type !== 'textandicon') : ?>text-left<?php endif; ?>">
						<div class="right-hover">
							<button class="tottal-count bshare-<?php echo $name_six; ?> share s_<?php echo $name_six; ?>" type="button">
								<i class="icon-<?php echo $name_six; ?>"></i>&nbsp;<?php echo $name_six; ?>
							</button><!-- button six-->
							<?php if ($select_count !== 'no') : ?>
								<?php if ($name_six == 'Facebook' || $name_six == 'Linkedin' || $name_six == 'Pinterest' || $name_six == 'Tumblr'  || $name_six == 'Myworld' || $name_six == 'Vk' || $name_six == 'Ok') : ?>
									<!-- <span class="baby-count-right counter c_<?php echo $name_six; ?>"></span> -->
								<?php endif; ?>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>
				<?php if ($name_seven !== 'noselect') : ?>
					<div class="count-set-right <?php if ($btn_type !== 'textandicon') : ?>text-left<?php endif; ?>">
						<div class="right-hover">
							<button class="tottal-count bshare-<?php echo $name_seven; ?> share s_<?php echo $name_seven; ?>" type="button">
								<i class="icon-<?php echo $name_seven; ?>"></i>&nbsp;<?php echo $name_seven; ?>
							</button><!-- button seven-->
							<?php if ($select_count !== 'no') : ?>
								<?php if ($name_seven == 'Facebook' || $name_seven == 'Linkedin' || $name_seven == 'Pinterest' || $name_seven == 'Tumblr'  || $name_seven == 'Myworld' || $name_seven == 'Vk' || $name_seven == 'Ok') : ?>
									<!-- <span class="baby-count-right counter c_<?php echo $name_seven; ?>"></span> -->
								<?php endif; ?>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>
				<?php if ($all_sharebtn == 'yes') : ?>
					<div class="count-set-right <?php if ($btn_type !== 'textandicon') : ?>text-left<?php endif; ?>">
						<button class="my_popup_open"><?php esc_html_e('All share', 'easy-share-solution'); ?>&nbsp;<i class="icon-more"></i></button>
					</div><!-- popup button -->
				<?php endif; ?>
				<?php if ($show_hide == 'yes') : ?>
					<div class="share_hide_show right_hide_show">
						<i class="icon-share"></i>
					</div>
				<?php endif; ?>
			</div><!-- right side share button -->
		<?php }
		if ($position_three = !empty($options['btn_position'])) {
			$position_three = $options['btn_position'];
		}
		if (!empty($position_three['three'])) {
		?>
			<div id="bshare-social" class="baby-sideshare share-bottom <?php if ($select_count == 'yes') : ?>count_show<?php endif; ?> <?php if ($btn_type == 'round_icon') : ?>round-icons<?php endif; ?>" style="left:<?php echo $btn_top_set; ?>%">
				<?php if ($show_hide == 'yes') : ?>
					<div class="share_hide_show bottom_hide_show">
						<i class="icon-share"></i>
					</div><!-- share icon -->
				<?php endif; ?>
				<div class="count-set-bottom <?php if ($btn_type !== 'textandicon') : ?>text-left<?php endif; ?>">
					<button class="tottal-count bshare-<?php echo $name_one; ?> share s_<?php echo $name_one; ?>" type="button">
						<?php if ($select_count !== 'no') :  ?>
							<?php if ($name_one == 'Facebook' || $name_one == 'Linkedin' || $name_one == 'Pinterest' || $name_one == 'Tumblr'  || $name_one == 'Myworld' || $name_one == 'Vk' || $name_one == 'Ok') : ?>
								<!-- <span class="baby-count-bottom counter c_<?php echo $name_one; ?>"></span> -->
							<?php endif; ?>
						<?php endif; ?>
						<i class="icon-<?php echo $name_one; ?>"></i>&nbsp;<?php echo $name_one; ?>
					</button><!-- button one-->
				</div>
				<div class="count-set-bottom <?php if ($btn_type !== 'textandicon') : ?>text-left<?php endif; ?>">
					<button class="tottal-count bshare-<?php echo $name_two; ?> share s_<?php echo $name_two; ?>" type="button">
						<?php if ($select_count !== 'no') : ?>
							<?php if ($name_two == 'Facebook' || $name_two == 'Linkedin' || $name_two == 'Pinterest' || $name_two == 'Tumblr'  || $name_two == 'Myworld' || $name_two == 'Vk' || $name_two == 'Ok') : ?>
								<!-- <span class="baby-count-bottom counter c_<?php echo $name_two; ?>"></span> -->
							<?php endif; ?>
						<?php endif; ?>
						<i class="icon-<?php echo $name_two; ?>"></i>&nbsp;<?php echo $name_two; ?>
					</button><!-- button two-->
				</div>
				<div class="count-set-bottom <?php if ($btn_type !== 'textandicon') : ?>text-left<?php endif; ?>">
					<button class="tottal-count bshare-<?php echo $name_three; ?> share s_<?php echo $name_three; ?>" type="button">
						<?php if ($select_count !== 'no') : ?>
							<?php if ($name_three == 'Facebook' || $name_three == 'Linkedin' || $name_three == 'Pinterest' || $name_three == 'Tumblr'  || $name_three == 'Myworld' || $name_three == 'Vk' || $name_three == 'Ok') : ?>
								<!-- <span class="baby-count-bottom counter c_<?php echo $name_three; ?>"></span> -->
							<?php endif; ?>
						<?php endif; ?>
						<i class="icon-<?php echo $name_three; ?>"></i>&nbsp;<?php echo $name_three; ?>
					</button><!-- button three-->
				</div>
				<div class="count-set-bottom <?php if ($btn_type !== 'textandicon') : ?>text-left<?php endif; ?>">
					<button class="tottal-count bshare-<?php echo $name_four; ?> share s_<?php echo $name_four; ?>" type="button">
						<?php if ($select_count !== 'no') :  ?>
							<?php if ($name_four == 'Facebook' || $name_four == 'Linkedin' || $name_four == 'Pinterest' || $name_four == 'Tumblr'  || $name_four == 'Myworld' || $name_four == 'Vk' || $name_four == 'Ok') : ?>
								<!-- <span class="baby-count-bottom counter c_<?php echo $name_four; ?>"></span> -->
							<?php endif; ?>
						<?php endif; ?>
						<i class="icon-<?php echo $name_four; ?>"></i>&nbsp;<?php echo $name_four; ?>
					</button><!-- button four-->
				</div>
				<div class="count-set-bottom <?php if ($btn_type !== 'textandicon') : ?>text-left<?php endif; ?>">

					<button class="tottal-count bshare-<?php echo $name_five; ?> share s_<?php echo $name_five; ?>" type="button">
						<?php if ($select_count !== 'no') :  ?>
							<?php if ($name_five == 'Facebook' || $name_five == 'Linkedin' || $name_five == 'Pinterest' || $name_five == 'Tumblr'  || $name_five == 'Myworld' || $name_five == 'Vk' || $name_five == 'Ok') : ?>
								<!-- <span class="baby-count-bottom counter c_<?php echo $name_five; ?>"></span> -->
							<?php endif; ?>
						<?php endif; ?>
						<i class="icon-<?php echo $name_five; ?>"></i>&nbsp;<?php echo $name_five; ?>
					</button><!-- button five-->
				</div>
				<?php if ($name_six !== 'noselected') : ?>
					<div class="count-set-bottom <?php if ($btn_type !== 'textandicon') : ?>text-left<?php endif; ?>">

						<button class="tottal-count bshare-<?php echo $name_six; ?> share s_<?php echo $name_six; ?>" type="button">
							<?php if ($select_count !== 'no') :  ?>
								<?php if ($name_six == 'Facebook' || $name_six == 'Linkedin' || $name_six == 'Pinterest' || $name_six == 'Tumblr'  || $name_six == 'Myworld' || $name_six == 'Vk' || $name_six == 'Ok') : ?>
									<!-- <span class="baby-count-bottom counter c_<?php echo $name_six; ?>"></span> -->
								<?php endif; ?>
							<?php endif; ?>
							<i class="icon-<?php echo $name_six; ?>"></i>&nbsp;<?php echo $name_six; ?>
						</button><!-- button six-->
					</div>
				<?php endif; ?>
				<?php if ($name_seven !== 'noselect') : ?>
					<div class="count-set-bottom <?php if ($btn_type !== 'textandicon') : ?>text-left<?php endif; ?>">
						<button class="tottal-count bshare-<?php echo $name_seven; ?> share s_<?php echo $name_seven; ?>" type="button">
							<?php if ($select_count !== 'no') :  ?>
								<?php if ($name_seven == 'Facebook' || $name_seven == 'Linkedin' || $name_seven == 'Pinterest' || $name_seven == 'Tumblr'  || $name_seven == 'Myworld' || $name_seven == 'Vk' || $name_seven == 'Ok') : ?>
									<!-- <span class="baby-count-bottom counter c_<?php echo $name_seven; ?>"></span> -->
								<?php endif; ?>
							<?php endif; ?>
							<i class="icon-<?php echo $name_seven; ?>"></i>&nbsp;<?php echo $name_seven; ?>
						</button><!-- button seven-->
					</div>
				<?php endif; ?>
				<?php if ($all_sharebtn == 'yes') : ?>
					<div class="count-set-bottom all-share <?php if ($btn_type !== 'textandicon') : ?>text-left<?php endif; ?>">
						<button class="my_popup_open"><?php esc_html_e('All share', 'easy-share-solution'); ?>&nbsp;<i class="icon-more"></i></button><!-- popup button-->
					</div>
				<?php endif; ?>

			</div><!-- bottom side share button -->
		<?php }

		?>
		<!-- Add content to the popup -->
		<div class="all-share-button">
			<?php easy_share_solution_buton_set(); ?>
		</div>

	<?php
	}
	add_action('wp_footer', 'born_share_side');
endif;
if (!function_exists('born_share_scripts')) :
	function born_share_scripts()
	{
		if ($other = get_option('easy_share_solution_other_option')) {
			$other = get_option('easy_share_solution_other_option');
		}
		$all_sharebtn = (isset($other['all_sharebtn'])) ? $other['all_sharebtn'] : 'yes';
		$tweet_active = (isset($other['tee_active'])) ? $other['tee_active'] : 'yes';
		$min_text = (isset($other['min_text'])) ? $other['min_text'] : 5;
		$max_text = (isset($other['max_text'])) ? $other['max_text'] : 60;

		global $post;
		// Get current page URL 
		$essURL = get_permalink($post->ID);
		// Get title
		$essTitle = get_the_title($post->ID);
		// Get current content
		$essexcerpt =  get_the_excerpt();
		// Get Post Thumbnail for pinterest
		$essThumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'full');
	?>
		<script type="text/javascript">
			(function($) {
				"use strict";

				$(document).ready(function() {
					$('.share').ShareLink({
						title: '<?php echo $essTitle; ?>',
						text: '<?php echo $essexcerpt; ?>',
						image: '<?php if (!empty($essThumbnail[0])) : echo $essThumbnail[0];
								endif; ?>',
						url: '<?php echo esc_url($essURL); ?>'
					});
					$('.counter').ShareCounter({
						<?php if (is_single()) : ?>
							url: '<?php echo esc_url($essURL); ?>'
						<?php else : ?>
							url: '<?php echo esc_url(home_url()); ?>'
						<?php endif; ?>

					});
					<?php if ($all_sharebtn == 'yes') : ?>

						$('#my_popup').popup({
							outline: true, // optional
							focusdelay: 400, // optional
							vertical: 'top' //optional
						});
						$('.my_popup_open').click(function() {
							$('.all-share-button').toggleClass('esblock');
						});
					<?php endif; ?>
					<?php if ($tweet_active == 'yes') : ?>
						$('body').tweetHighlighted({
							// html node to use as the 'Tweet' button
							node: '<a href="#"><i class="icon-Twitter"></i></a>',
							// class attribute to attach to the node
							cssClass: 'text-tweet',
							// minimum length of selected text needed to show the 'Tweet' button
							minLength: <?php echo $min_text; ?>,
							// maximum length of selected text after which the 'Tweet' button is not shown
							maxLength: <?php echo $max_text; ?>,

						});
					<?php endif; ?>
					// left side click icon
					$(".left_hide_show i").on('click', function() {
						$(".share-left").toggleClass("hide_show_left");
					});
					// right side click icon
					$(".right_hide_show i").on('click', function() {
						$(".share-right").toggleClass("hide_show_right");
					});
					// bottom side click icon
					$(".bottom_hide_show i").on('click', function() {
						$(".share-bottom").toggleClass("hide_show_bottom");
					});
					// content click icon
					$(".content_hide_show i").on('click', function() {
						$(".share-content ").toggleClass("hide_show_content");
					});
				});
			}(jQuery));
		</script>
<?php
	}

	add_action('wp_footer', 'born_share_scripts', 99);
endif;
