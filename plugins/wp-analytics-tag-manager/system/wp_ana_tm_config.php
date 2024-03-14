<?php
/**
 * Plugin Name: WP Analytics Tag Manager 
 * Plugin URI: http://niiyz.com/wordpress/plugin/wp_analytics_tag_manager.html
 * Description: 設定
 * Version: 0.7.0
 * Author: Tetsuya Yoshida 
 * Author URI: http://niiyz.com/
 * Created : Feb 22, 2014
 * Modified: -
 * Text Domain: wp-analiytics-tag-manager
 * Domain Path: /languages/
 * License: GPL2
 *
 * Copyright 2014 Tetsuya Yoshida (email : hello@niiyz.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
class WP_ANA_TAG_Config {

	/**
	 * NAME
	 * プラグイン識別子
	 */
	const NAME = 'wp-ana-tag-manager';

	/**
	 * DOMAIN
	 * ネームスペース
	 */
	const DOMAIN = 'wp-ana-tag-manager';

	/**
	 * DBDATA
	 * DBに保存する問い合わせデータの post_type名 の接頭辞
	 */
	const DBDATA = 'wp_ana_tm';
}
