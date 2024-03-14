<?php

/*
 * 酱茄小程序开源版
 * Author: 追格
 * Help document: https://www.zhuige.com/docs/zxfree.html
 * github: https://github.com/zhuige-com/jiangqie_kafei
 * gitee: https://gitee.com/zhuige_com/jiangqie_kafei
 * Copyright ️© 2020-2024 www.zhuige.com All rights reserved.
 */

class JiangQie_API_Setting_Controller extends JiangQie_API_Base_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->module = 'setting';
	}

	public function register_routes()
	{
		//首页
		register_rest_route($this->namespace, '/' . $this->module . '/home', [
			[
				'callback' => [$this, 'get_home'],
				'permission_callback' => '__return_true',
			]
		]);

		//热门
		register_rest_route($this->namespace, '/' . $this->module . '/hot', [
			[
				'callback' => [$this, 'get_hot'],
				'permission_callback' => '__return_true',
			]
		]);

		//分类
		register_rest_route($this->namespace, '/' . $this->module . '/category', [
			[
				'callback' => [$this, 'get_category'],
				'permission_callback' => '__return_true',
			]
		]);

		//用户中心
		register_rest_route($this->namespace, '/' . $this->module . '/ucenter', [
			[
				'callback' => [$this, 'get_ucenter'],
				'permission_callback' => '__return_true',
			]
		]);

		//登录
		register_rest_route($this->namespace, '/' . $this->module . '/login', [
			[
				'callback' => [$this, 'get_login'],
				'permission_callback' => '__return_true',
			]
		]);

		//注销
		register_rest_route($this->namespace, '/' . $this->module . '/logout', [
			[
				'callback' => [$this, 'get_logout'],
				'permission_callback' => '__return_true',
			]
		]);
	}

	/**
	 * 获取配置 首页
	 */
	public function get_home($request)
	{
		//LOGO
		$data['logo'] = JiangQie_API::option_image_url(JiangQie_API::option_value('logo'));

		//小程序名称-仅百度小程序使用
		$data['title'] = JiangQie_API::option_value('title');

		//描述-仅百度小程序使用
		$data['description'] = JiangQie_API::option_value('description');

		//关键字-仅百度小程序使用
		$data['keywords'] = JiangQie_API::option_value('keywords');

		//顶部分类
		$cat_ids = JiangQie_API::option_value('home_top_nav');
		$args = ['hide_empty' => 0];
		if (!empty($cat_ids)) {
			$args['include'] = implode(',', $cat_ids);
		}

		$result = get_categories($args);

		$categories = [];
		foreach ($cat_ids as $cat_id) {
			foreach ($result as $item) {
				if ($cat_id == $item->term_id) {
					$categories[] = [
						'id' => $item->term_id,
						'name' => $item->name,
					];
					break;
				}
			}
		}

		$data['top_nav'] = $categories;

		// //幻灯片
		// $slide_ids = JiangQie_API::option_value('top_slide');
		// $slides = [];
		// if (!empty($slide_ids)) {
		// 	$args = [
		// 		'post__in' => $slide_ids,
		// 		'orderby' => 'post__in',
		// 		'posts_per_page' => -1,
		// 		'ignore_sticky_posts' => 1
		// 	];

		// 	$query = new WP_Query();
		// 	$result = $query->query($args);
		// 	foreach ($result as $item) {
		// 		$slides[] = [
		// 			'id' => $item->ID,
		// 			'thumbnail' => apply_filters('jiangqie_post_thumbnail', $item->ID)
		// 		];
		// 	}
		// }
		// $data['slide'] = $slides;

		//幻灯片
		$home_slide_org = JiangQie_API::option_value('home_slide');
		$home_slide = [];
		if (is_array($home_slide_org)) {
			foreach ($home_slide_org as &$item) {
				if ($item && $item['switch']) {
					$item['image'] = JiangQie_API::option_image_url($item['image']);
					$home_slide[] = $item;
				}
			}
		}
		$data['home_slide'] = $home_slide;

		//图标导航
		$icon_nav_org = JiangQie_API::option_value('home_icon_nav');
		$icon_nav = [];
		if (is_array($icon_nav_org)) {
			foreach ($icon_nav_org as &$item) {
				if ($item && $item['switch']) {
					$item['icon'] = JiangQie_API::option_image_url($item['icon']);
					$icon_nav[] = $item;
				}
			}
		}
		$data['icon_nav'] = $icon_nav;

		//活动区域
		$home_active = JiangQie_API::option_value('home_active');
		if (
			JiangQie_API::option_value('home_active_switch')
			&& !empty($home_active)
			&& !empty($home_active['left_image'])
			&& !empty($home_active['right_top_image'])
			&& !empty($home_active['right_down_image'])
		) {
			$data['actives'] = [
				'left' => [
					'image' => JiangQie_API::option_image_url($home_active['left_image']),
					'title' => $home_active['left_title'],
					'link' => $home_active['left_link']
				],
				'right_top' => [
					'image' => JiangQie_API::option_image_url($home_active['right_top_image']),
					'title' => $home_active['right_top_title'],
					'link' => $home_active['right_top_link']
				],
				'right_down' => [
					'image' => JiangQie_API::option_image_url($home_active['right_down_image']),
					'title' => $home_active['right_down_title'],
					'link' => $home_active['right_down_link']
				],
			];
		} else {
			$data['actives'] = false;
		}

		//热门文章
		$hot_ids = JiangQie_API::option_value('home_hot');
		$hots = [];
		if (!empty($hot_ids)) {
			$args = [
				'post__in' => $hot_ids,
				'orderby' => 'post__in',
				'posts_per_page' => -1,
				'ignore_sticky_posts' => 1
			];

			$query = new WP_Query();
			$result = $query->query($args);
			foreach ($result as $item) {
				$hots[] = [
					'id' => $item->ID,
					'title' => $item->post_title,
					'thumbnail' => apply_filters('jiangqie_post_thumbnail', $item->ID)
				];
			}
		}
		$data['hot'] = $hots;

		//列表模式
		$data['list_mode'] = JiangQie_API::option_value('home_list_mode');
		if (!$data['list_mode']) {
			$data['list_mode'] = 3;
		}

		$data['background'] = plugins_url("images/id_bg.png", dirname(__FILE__));

		// 广告设置
		$wx_ad = JiangQie_API::option_value('wx_ad_home');
		if ($wx_ad && $wx_ad['switch'] && $wx_ad['adid']) {
			$data['wx_ad'] = $wx_ad['adid'];
			$data['wx_ad_delay'] = $wx_ad['delay'];
		} else {
			$data['wx_ad'] = false;
		}

		//弹框广告
		$home_ad_pop = JiangQie_API::option_value('home_ad_pop');
		if ($home_ad_pop && $home_ad_pop['switch'] && $home_ad_pop['image'] && $home_ad_pop['image']['url']) {
			$data['pop_ad'] = [
				'image' => $home_ad_pop['image']['url'],
				'link' => $home_ad_pop['link'],
				'interval' => $home_ad_pop['interval'],
			];
		}

		// 分享标题
		$data['share_title'] = JiangQie_API::option_value('home_title');

		//分享缩略图
		$home_thumb = JiangQie_API::option_value('home_thumb');
		if ($home_thumb && $home_thumb['url']) {
			$data['share_thumb'] = $home_thumb['url'];
		}

		return $this->make_success($data);
	}

	/**
	 * 获取配置 热门
	 */
	public function get_hot()
	{
		$data = [
			'background' => JiangQie_API::option_image_url(JiangQie_API::option_value('hot_background'), 'hot_bg.png'),
			'title' => JiangQie_API::option_value('hot_title'),
			'description' => JiangQie_API::option_value('hot_description'),
			'tl_background' => plugins_url("images/timeline_bg.png", dirname(__FILE__))
		];

		return $this->make_success($data);
	}

	/**
	 * 获取配置 分类
	 */
	public function get_category()
	{
		$data = [
			'background' => JiangQie_API::option_image_url(JiangQie_API::option_value('category_background'), 'cat_bg.png'),
			'title' => JiangQie_API::option_value('category_title'),
			'description' => JiangQie_API::option_value('category_description'),
		];

		// 广告设置
		$wx_ad = JiangQie_API::option_value('wx_ad_category');
		if ($wx_ad && $wx_ad['switch'] && $wx_ad['adid']) {
			$data['wx_ad'] = $wx_ad['adid'];
		} else {
			$data['wx_ad'] = false;
		}

		return $this->make_success($data);
	}

	/**
	 * 获取配置 用户中心
	 */
	public function get_ucenter()
	{
		$result = JiangQie_API::option_value('profile_menu');
		$menu = [];
		if (is_array($result)) {
			foreach ($result as &$item) {
				if ($item && $item['switch']) {
					if ($item['icon'] && $item['icon']['url']) {
						$item['icon'] = $item['icon']['url'];
					} else {
						$item['icon'] = '';
					}
					if ($item['line'] !== '0') {
						$item['line'] = 'yes';
					}
					$menu[] = $item;
				}
			}
		}

		$data = [
			'background' => JiangQie_API::option_image_url(JiangQie_API::option_value('profile_background'), 'my_bg.png'),
			'menu' => $menu,
		];

		// 备案信息
		$beian_icp = JiangQie_API::option_value('beian_icp');
		if ($beian_icp && $beian_icp['switch']) {
			$data['beian_icp'] = [
				'sn' => $beian_icp['sn'],
				'link' => $beian_icp['link'],
			];
		}

		return $this->make_success($data);
	}

	/**
	 * 获取配置 登录
	 */
	public function get_login()
	{
		$data = [
			'background' => plugins_url("images/id_bg.png", dirname(__FILE__)),
			'ystk' => JiangQie_API::option_value('user_login_ystk'),
			'yhxy' => JiangQie_API::option_value('user_login_yhxy')
		];

		return $this->make_success($data);
	}

	/**
	 * 获取配置 注销
	 */
	public function get_logout()
	{
		$data = [];

		// 奖项说明
		$data['explain'] = apply_filters('the_content', JiangQie_API::option_value('user_logout_explain'));

		return $this->make_success($data);
	}

}
