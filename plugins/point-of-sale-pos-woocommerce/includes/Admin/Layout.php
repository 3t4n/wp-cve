<?php

namespace ZPOS\Admin;

use ZPOS\Admin;
use ZPOS\Admin\Stations\Post;
use ZPOS\Plugin;

class Layout
{
	public function __construct()
	{
		add_action('in_admin_header', [$this, 'pageHeader']);
	}

	public function pageHeader()
	{
		if (!self::isActive()) {
			return;
		} ?>
				<div class="zpos-layout-wrapper">
						<div class="zpos-layout">
								<div class="zpos-base">
										<a href="http://bizswoop.com/pos">
												<img
													class="zpos-logo"
													src="<?= Plugin::getUrl('assets/admin/logo.png') ?>"
													alt="<?= esc_attr__('Jovvie POS', 'zpos-wp-api') ?>"
												>
										</a>
										<div class="zpos-title">
												<a href="http://jovvie.com/">
														<?= __('Jovvie POS', 'zpos-wp-api') ?>
												</a>
										</div>
										<div class="zpos-slogan">
												<span><?= __('Sell Anywhere', 'zpos-wp-api') ?></span>
										</div>
								</div>
								<div class="zpos-navigation">
										<ul>
												<li>
														<a
															href="<?= Admin::getPageURL('stations') ?>"
															class="<?= self::isActiveClass('stations') ?>"
														>
																<div class="zpos-icon">
																		<i class="fal fa-cash-register"></i>
																</div>
																<?= __('Stations', 'zpos-wp-api') ?>
														</a>
												</li>
												<?php // todo: ADDONS, hidden for the v1 launch
		?>
<!--												<li>-->
<!--														<a-->
<!--															href="--><?//= Admin::getPageURL('addons'); ?><!--"-->
<!--															class="--><?//= self::isActiveClass('addons'); ?><!--"-->
<!--														>-->
<!--																<div class="zpos-icon">-->
<!--																		<i class="far fa-cubes"></i>-->
<!--																</div>-->
<!--																--><?//= __('Add-ons', 'zpos-wp-api'); ?>
<!--														</a>-->
<!--												</li>-->
												<li>
														<a
															href="<?= Admin::getPageURL('settings') ?>"
															class="<?= self::isActiveClass('settings') ?>"
														>
																<div class="zpos-icon">
																		<i class="fal fa-cog"></i>
																</div>
																<?= __('Settings', 'zpos-wp-api') ?>
														</a>
												</li>
												<li>
														<a href="http://bizswoop.com/">
																<div class="zpos-icon">
																		<img
																			src="<?= Plugin::getUrl('assets/admin/bizswoop.png') ?>"
																			alt="BizSwoop">
																</div>
																BizSwoop
														</a>
												</li>
										</ul>
								</div>
						</div>
				</div>
				<?php
	}

	public static function isActive($tag = null)
	{
		switch ($tag) {
			case 'stations':
				return in_array(\get_current_screen()->id, ['edit-' . Post::TYPE, Post::TYPE]);
			case 'addons':
				return \get_current_screen()->id === 'pos-station_page_pos_addons';
			case 'settings':
				return \get_current_screen()->id === 'pos-station_page_pos';
			default:
				return self::isActive('stations') || self::isActive('addons') || self::isActive('settings');
		}
	}

	public static function isActiveClass($tag)
	{
		return self::isActive($tag) ? 'zpos-active-link' : '';
	}
}
