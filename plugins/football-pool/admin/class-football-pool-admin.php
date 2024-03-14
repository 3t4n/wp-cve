<?php
/*
 * Football Pool WordPress plugin
 *
 * @copyright Copyright (c) 2023 Antoine Hurkmans
 * @link https://wordpress.org/plugins/football-pool/
 * @license https://plugins.svn.wordpress.org/football-pool/trunk/COPYING
 *
 * This file is part of Football pool.
 *
 * Football pool is free software: you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software Foundation,
 * either version 3 of the License, or (at your option) any later version.
 *
 * Football pool is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
 * PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with Football pool.
 * If not, see <https://www.gnu.org/licenses/>.
 */
/** @noinspection SqlResolve */
class Football_Pool_Admin {
	public static function add_body_class( $classes ) {
		global $hook_suffix;
		if ( strpos( $hook_suffix, 'footballpool' ) !== false ) $classes .= 'football-pool';
		return $classes;
	}
	
	public static function adminhook_suffix() {
		// for debugging
		global $hook_suffix;
		echo "<!-- admin hook for current page is: {$hook_suffix} -->";
	}
	
	public static function set_screen_options( $status, $option, $value ) {
		return ( stripos( $option, 'footballpool_' ) !== false ) ? $value : $status;
	}
	
	public static function get_screen_option( $option, $type = 'int' ) {
		$screen = get_current_screen();
		
		$screen_option = $screen->get_option( $option, 'option' );
		$option_value = get_user_meta( get_current_user_id(), $screen_option, true );
		
		$default_value = empty ( $option_value ) || $option_value < 1 ;
		if ( ! $default_value && $type == 'int' ) $option_value = (int) $option_value;
		
		if ( $default_value ) $option_value = $screen->get_option( $option, 'default' );
		
		return $option_value;
	}

	/** @noinspection HtmlUnknownTarget */
	public static function init_admin() {
		// todo: add doing_cron check?
		if ( ! wp_doing_ajax() ) {
			// Checks if Highcharts settings are correct
			$chart = new Football_Pool_Chart();
			if ( $chart->stats_enabled && ! $chart->API_loaded ) {
				$notice = sprintf( '<strong>%s: </strong>', __( 'Football Pool', 'football-pool' ) )
				          . __( 'Charts are enabled but Highcharts API was not found!', 'football-pool' ) . ' '
				          . sprintf( __( 'See <a href="%s">Help page</a> for details.', 'football-pool' )
						, 'admin.php?page=footballpool-help#charts' );
				self::notice( $notice , 'error', false, true );
			}
		}
	}
	
	private static function add_submenu_page( $parent_slug, $page_title, $menu_title, $capability,
	                                          $menu_slug, $class, $toplevel = false ) {
		if ( is_array( $class ) ) {
			$function = [$class['admin'], 'admin'];
			$help_class = $class['help'];
			$screen_options_class = $class['screen_options'];
		} else {
			$function = [$class, 'admin'];
			$help_class = $screen_options_class = $class;
		}
		
		$hook = add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function );
		
		// help tab
		if ( method_exists( $help_class, 'help' ) ) {
			$menu_level = $toplevel ? 'toplevel' : 'football-pool';
			add_action( "admin_head-{$menu_level}_page_{$menu_slug}", [$help_class, 'help'] );
		}
		
		// screen options
		if ( $hook && method_exists( $screen_options_class, 'screen_options' ) ) {
			add_action( "load-{$hook}", [$screen_options_class, 'screen_options'] );
		}
		
		do_action( "footballpool_admin_post_menu_{$menu_slug}", $parent_slug, $capability );
	}
	
	public static function admin_menu_init() {
		$menu_slug = 'footballpool-options';

		// main menu item
		add_menu_page(
			__( 'Football Pool', 'football-pool' ),
			__( 'Football Pool', 'football-pool' ),
			FOOTBALLPOOL_ADMIN_BASE_CAPABILITY,
			$menu_slug,
			array( 'Football_Pool_Admin_Options', 'admin' ),
//			'none'
//			'dashicons-games'
			'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjxzdmcKICAgdmVyc2lvbj0iMS4wIgogICB3aWR0aD0iNDgiCiAgIGhlaWdodD0iNDgiCiAgIHZpZXdCb3g9IjAgMCAzNiAzNS45OTk5OTgiCiAgIHByZXNlcnZlQXNwZWN0UmF0aW89InhNaWRZTWlkIgogICBpZD0ic3ZnMSIKICAgc29kaXBvZGk6ZG9jbmFtZT0iZm9vdGJhbGwtcG9vbC1hZG1pbi1pY29uLnN2ZyIKICAgaW5rc2NhcGU6dmVyc2lvbj0iMS4zICgwZTE1MGVkNmM0LCAyMDIzLTA3LTIxKSIKICAgeG1sbnM6aW5rc2NhcGU9Imh0dHA6Ly93d3cuaW5rc2NhcGUub3JnL25hbWVzcGFjZXMvaW5rc2NhcGUiCiAgIHhtbG5zOnNvZGlwb2RpPSJodHRwOi8vc29kaXBvZGkuc291cmNlZm9yZ2UubmV0L0RURC9zb2RpcG9kaS0wLmR0ZCIKICAgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIgogICB4bWxuczpzdmc9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KICA8c29kaXBvZGk6bmFtZWR2aWV3CiAgICAgaWQ9Im5hbWVkdmlldzEiCiAgICAgcGFnZWNvbG9yPSIjZmZmZmZmIgogICAgIGJvcmRlcmNvbG9yPSIjMDAwMDAwIgogICAgIGJvcmRlcm9wYWNpdHk9IjAuMjUiCiAgICAgaW5rc2NhcGU6c2hvd3BhZ2VzaGFkb3c9IjIiCiAgICAgaW5rc2NhcGU6cGFnZW9wYWNpdHk9IjAuMCIKICAgICBpbmtzY2FwZTpwYWdlY2hlY2tlcmJvYXJkPSIwIgogICAgIGlua3NjYXBlOmRlc2tjb2xvcj0iI2QxZDFkMSIKICAgICBpbmtzY2FwZTpkb2N1bWVudC11bml0cz0icHQiCiAgICAgaW5rc2NhcGU6em9vbT0iMTMuOTY4MTcyIgogICAgIGlua3NjYXBlOmN4PSIzNi40MDQxOTEiCiAgICAgaW5rc2NhcGU6Y3k9IjI2Ljk1NDEzNiIKICAgICBpbmtzY2FwZTp3aW5kb3ctd2lkdGg9IjE5MjAiCiAgICAgaW5rc2NhcGU6d2luZG93LWhlaWdodD0iMTAwOSIKICAgICBpbmtzY2FwZTp3aW5kb3cteD0iLTgiCiAgICAgaW5rc2NhcGU6d2luZG93LXk9Ii04IgogICAgIGlua3NjYXBlOndpbmRvdy1tYXhpbWl6ZWQ9IjEiCiAgICAgaW5rc2NhcGU6Y3VycmVudC1sYXllcj0ic3ZnMSIgLz4KICA8ZGVmcwogICAgIGlkPSJkZWZzMSIgLz4KICA8ZwogICAgIHRyYW5zZm9ybT0ibWF0cml4KDAuMDAxMTA5MjUsMCwwLC0wLjAwMTEwOTI1LDAuMzM2NTMzNiwzNS43NzIxMzgpIgogICAgIGZpbGw9IiMwMDAwMDAiCiAgICAgc3Ryb2tlPSJub25lIgogICAgIGlkPSJnMSIKICAgICBzdHlsZT0iZmlsbDojYTdhYWFkO2ZpbGwtb3BhY2l0eToxIj4KICAgIDxwYXRoCiAgICAgICBkPSJtIDE1NjA1LDMxMTI5IGMgLTE0OTcsLTI5IC0zMDE4LC0yOTMgLTQ0NjAsLTc3NSBDIDk4NjUsMjk5MjcgODU5NCwyOTI5OCA3NDgwLDI4NTQyIDY5ODQsMjgyMDUgNjQ2OSwyNzgwOSA2MDQwLDI3NDM2IDU2NDcsMjcwOTMgNTE0MSwyNjYwMiA0ODA0LDI2MjM1IDI0NzQsMjM2OTcgMTA4MiwyMDQ2MSA4NTAsMTcwNDUgODAwLDE2MzA5IDgwOCwxNTQ2MCA4NzEsMTQ3MTAgMTExOSwxMTczNiAyMjYwLDg4ODUgNDEzOCw2NTQyIDQ2NjMsNTg4NyA1MzI0LDUxOTQgNTk1NSw0NjM5IDc5OTgsMjg0MSAxMDUwMywxNjE4IDEzMTUwLDExMjYgYyAxMjcxLC0yMzcgMjU3MiwtMzExIDM4NzAsLTIyMSAyNTY4LDE3OSA1MDQ3LDEwMTQgNzIwNSwyNDI5IDM4OTYsMjU1MyA2NDA5LDY3NTAgNjc5OSwxMTM1NiA0Myw1MTIgNTEsNzEyIDUxLDEzNDAgLTEsNTgwIC01LDcxNSAtNDEsMTE3NSAtMTE5LDE1NTQgLTQ5NiwzMTI2IC0xMDk5LDQ1NzkgLTE4OSw0NTcgLTQ2NSwxMDMzIC02OTAsMTQ0NiAtNzIzLDEzMjAgLTE2MTEsMjQ5NiAtMjY3NiwzNTQ1IC0yNjk1LDI2NTMgLTYyMTYsNDE4NiAtOTk4NCw0MzQ1IC0zNDQsMTQgLTU3MCwxNiAtOTgwLDkgeiBtIC04MzIsLTUwMSBjIDU1LC02OSA5MywtMTI5IDEzNSwtMjE0IDY0LC0xMjcgNzUsLTEwOSAtMTE2LC0xOTMgLTMxOCwtMTQwIC04MjIsLTM5OSAtMTE0MywtNTg2IC04NjEsLTUwNCAtMTY5MywtMTEyOCAtMjQ3OCwtMTg1OSAtMTAxLC05NCAtMjE4LC0yMDMgLTI2MSwtMjQzIGwgLTc3LC03MiAtMTk5LC00MSBDIDg5ODYsMjcwNzkgNzQ1OSwyNjUwMCA1OTk3LDI1NjYzIGwgLTIxOCwtMTI1IC0xMjcsMTI0IGMgLTEzOCwxMzMgLTI1NiwyMjIgLTM5NCwyOTYgLTQ4LDI2IC04OCw1MCAtODgsNTQgMCwyMyA0ODMsNTA3IDc5MCw3OTEgMTc2NSwxNjMyIDM4ODMsMjgwMiA2MTgwLDM0MTUgODEwLDIxNyAxNTcwLDM1MiAyNDgwLDQ0MSA0Nyw1IDkzLDkgMTAyLDEwIDksMCAzMiwtMTggNTEsLTQxIHogbSA4NTMsLTcyOCBjIDE3MzksLTI2OSAzMDkwLC02MTQgNDM4NCwtMTEyMCAzMjAsLTEyNSA4ODcsLTM3MiA5MTAsLTM5NyAxOCwtMTggMjYwLC00MjIgMzkwLC02NDggNTkxLC0xMDMyIDk3MSwtMTkzNiAxMjIwLC0yODk3IGwgNDgsLTE4OCAtNTEsLTI4IGMgLTI5LC0xNiAtMTQ5LC04MCAtMjY3LC0xNDMgLTEyODYsLTY3NiAtMjU3MCwtMTUyOSAtMzU4NSwtMjM3OSAtODIsLTY5IC0xNTUsLTEyOSAtMTYyLC0xMzMgLTcsLTUgLTgzLDIxIC0xNzUsNTkgLTE2NTcsNjc1IC0zNDY1LDExMjcgLTUzMzMsMTMzMyAtMTQ4LDE3IC0zMDgsMzMgLTM1NSwzNyAtNDcsNCAtOTQsMTAgLTEwNiwxNCAtMTUsNiAtNTEsNzYgLTEzMiwyNTIgLTQ0Niw5NzcgLTgxMSwyMDAxIC0xMTU3LDMyNDMgbCAtODQsMzAwIDEyMiwxMTUgYyA2NzAsNjM2IDEyODAsMTEyNSAxOTc3LDE1ODYgNTg5LDM5MCAxMTgxLDcxMiAxODIwLDk5MCA4MCwzNSAxNDUsNjQgMTQ2LDY0IDAsMCAxNzYsLTI3IDM5MCwtNjAgeiBtIDc2OTMsLTExNjEgYyA3NDgsLTQzMSAxNTM2LC05OTEgMjIxNywtMTU3NiA1MjcsLTQ1MyAxMTMxLC0xMDU5IDE1OTksLTE2MDMgODE4LC05NTIgMTU3NiwtMjExOCAyMTEyLC0zMjUyIDEwMywtMjE3IDEwNiwtMjI2IDEyMCwtMzMzIDgsLTYwIDE3LC0yMzAgMjAsLTM3NiA3LC0zMzcgLTcsLTQ5MSAtNzAsLTc1OCBsIC0yMiwtOTQgLTg1LDYgYyAtNDcsNCAtMTg0LDE0IC0zMDUsMjMgLTI0OSwyMCAtMTI0NiwyNiAtMTY2MSwxMSBsIC0yMzAsLTkgLTEwOSwxMzkgYyAtNDI4LDU0MiAtODQxLDEwMDggLTEzNzksMTU1MSAtNjkyLDY5OSAtMTM4NCwxMzA2IC0yMjU3LDE5ODEgbCAtMjY0LDIwNSAtNTksMjM0IGMgLTI2NiwxMDc0IC03MjEsMjEyOSAtMTQzNywzMzMzIC03MCwxMTggLTEzMSwyMjIgLTEzNCwyMzEgLTQsMTEgMTUsMjQgODIsNTEgMzU5LDE0OSA4NzAsMjY1IDEzOTgsMzE2IDYxLDYgMTI0LDEzIDE0MCwxNSAxNywxIDU3LDQgODksNSA1OCwxIDY0LC0xIDIzNSwtMTAwIHogTSA0OTY4LDI1NjQwIGMgMTM0LC02MyAyNzQsLTE2MyAzOTksLTI4MyA2MiwtNjAgMTEzLC0xMTcgMTEzLC0xMjUgMCwtOSAtNTYsLTE0OSAtMTI0LC0zMTEgLTU1MSwtMTMxNCAtMTAwNSwtMjc2MSAtMTM3NywtNDM5MCAtNjgsLTI5OCAtNzAsLTMwNiAtMTA5LC0zNTUgLTc5MCwtOTk4IC0xNDMwLC0yMTI1IC0xOTA1LC0zMzU2IC02OCwtMTc4IC0yMTQsLTU4NSAtMjU2LC03MTggbCAtMjMsLTcyIC01MCw2IGMgLTEyNywxNCAtMzcyLDc4IC0zOTksMTAzIC0xOCwxNyAyMSw4NDAgNTksMTIzNiAyNzEsMjg4MCAxMzYwLDU1NjcgMzE2OSw3ODI1IDE4MywyMjggMzk3LDQ4MCA0MDksNDgwIDQsMCA0NywtMTggOTQsLTQwIHogbSA3NzUyLC0yNjY5IGMgMTgzMywtMTgzIDM1NzEsLTU5NCA1MjE2LC0xMjM1IDEyMiwtNDcgMjcyLC0xMDcgMzM0LC0xMzQgbCAxMTMsLTQ3IDEyMywtMzI0IGMgNTgwLC0xNTE4IDEwNDcsLTMxNDAgMTM2OSwtNDc0OSBsIDY2LC0zMzMgLTM4MywtMzg5IGMgLTIxMSwtMjE0IC02ODAsLTY5MCAtMTA0MywtMTA1NyAtNjk0LC03MDIgLTE1ODMsLTE2MTAgLTIzOTQsLTI0NDYgbCAtNDg0LC00OTkgLTU2LDYgYyAtMzEsMyAtOTksOCAtMTUxLDExIC0xMDcxLDY1IC0yMzczLDI3OCAtMzcyMCw2MDkgLTU1OSwxMzcgLTE3NDcsNDY4IC0xNzcxLDQ5MyAtOSwxMCAtMTQ1LDMzMyAtMjM4LDU2NCAtMTk0LDQ4NyAtNDQwLDExOTUgLTU5MCwxNjk5IC0yNzQsOTIxIC00OTIsMTg4MSAtNjcwLDI5NTUgbCAtNjAsMzYwIDI0MSwzMjAgYyAxMDgyLDE0MzMgMjI4OCwyNzc1IDM1NTIsMzk1MiAyNTQsMjM3IDI4NCwyNjIgMzE2LDI2MiAxOSwxIDEyMywtOCAyMzAsLTE4IHogbSAxNTk5NSwtMjYwMSBjIDE3MSwtOSA0ODIsLTM0IDU5MCwtNDYgNDksLTYgNTMsLTkgMjA1LC0xNjIgMzUzLC0zNTcgNjEzLC03NzQgODY1LC0xMzg4IDU5LC0xNDIgNzgsLTIzNSAxNDQsLTY5OSAxNzQsLTEyMDkgMTkxLC0yNTE3IDUwLC0zNzU1IC0xNDAsLTEyMzUgLTQ0MiwtMjQ2NSAtODg5LC0zNjE5IC04NiwtMjIyIC0xOTgsLTQ5MSAtMjEwLC01MDcgLTYsLTYgLTI3LC0xNCAtNDgsLTE3IC0yMCwtMyAtOTEsLTE1IC0xNTcsLTI2IC0xNjgsLTI5IC01MjMsLTY3IC03NjMsLTgyIC0yNjgsLTE2IC05NjAsLTYgLTE0MDcsMjAgLTE4NywxMSAtMzU2LDIxIC0zNzYsMjEgaCAtMzYgbCAtNywzMjMgYyAtMjAsODk5IC0xMTEsMTY1NiAtMzAxLDI0OTIgLTE1Miw2NjkgLTQwMSwxNDM4IC02NzksMjA5OCBsIC00NSwxMDkgNzEsMTU2IGMgNTc5LDEyODAgOTg3LDI2MzUgMTI0Myw0MTMzIDQzLDI1NSAxMDEsNjQxIDEyNSw4NDEgbCAxMiw5NyA1Miw1IGMgMjE3LDI0IDExNjksMjcgMTU2MSw2IHogTSA0NDQ2LDE5Nzk2IGMgOTk4LC0zNDkgMTkwNCwtNzA3IDI5MjksLTExNTkgMzMwLC0xNDUgNTgwLC0yNjAgNTkxLC0yNzEgNiwtNiAzNSwtMTU3IDYzLC0zMzYgMzAwLC0xODU3IDc3OSwtMzU1OCAxNDUyLC01MTQ5IGwgNzAsLTE2MyAtMzIwLC01MjIgYyAtNjA4LC05OTQgLTExOTUsLTE5ODkgLTE3NjYsLTI5OTIgLTE2OCwtMjk2IC0zMTAsLTU0MyAtMzE1LC01NDggLTEyLC0xMiAtMjE0LDYgLTQ1MCw0MCAtODg1LDEyNyAtMTc2Myw0MzcgLTI2MTUsOTIyIC0xODIsMTA0IC00NDksMjY4IC00NjMsMjg1IC0zMyw0MCAtMzMxLDYyNiAtNDU5LDkwMiAtNTM0LDExNTIgLTg4OSwyMzk1IC0xMDMzLDM2MTAgLTQ2LDM4NSAtNzksODk1IC04MCwxMjA0IHYgMTczIGwgNDYsMTQ3IGMgMzU4LDExMzMgOTE0LDIyODYgMTU3MSwzMjU1IDE0NSwyMTUgNDk3LDY5NSA1MDksNjk2IDEsMCAxMjIsLTQyIDI3MCwtOTQgeiBtIDE2Nzc5LC00MDgwIGMgMTE0NiwtMTc0IDI0NTQsLTQxNiAzNzE1LC02ODkgbCAzMzUsLTcyIDEwNiwtMjY1IGMgNTkxLC0xNDczIDg3OSwtMjkyNyA4NzksLTQ0MzkgdiAtMjU3IGwgLTI2MywtMzI1IEMgMjQ5MDQsODMxOSAyMzY5NCw3MDYwIDIyNDIzLDU5NDkgYyAtMjk1LC0yNTcgLTI5NywtMjU5IC0zNDMsLTI1OSAtNzcsMCAtNjQzLDM5IC04ODAsNjAgLTE0MzMsMTMwIC0yNzY5LDM5MSAtNDE2NCw4MTMgbCAtNzksMjQgLTc5LDI1NCBjIC00MTgsMTM0MiAtNzIwLDI3OTAgLTg5Nyw0Mjk4IGwgLTQwLDMzNCA0NjIsNDc2IGMgMjU0LDI2MiA1NTQsNTcxIDY2Nyw2ODYgMTEzLDExNiA0MDgsNDE3IDY1NSw2NzAgMjQ3LDI1MyA5MTQsOTMxIDE0ODIsMTUwNiBsIDEwMzMsMTA0NSAzNDMsLTQ3IGMgMTg5LC0yNiA0NzgsLTY4IDY0MiwtOTMgeiBNIDM3MjMsOTM0NiBjIDYyMSwtMzY4IDEyNTUsLTY0OSAxODgyLC04MzYgNDc2LC0xNDEgMTAxOSwtMjQzIDE0NjMsLTI3NiBsIDk0LC03IDEwNiwtMTIxIGMgODAyLC05MDcgMTU5NCwtMTY4NyAyMzk3LC0yMzU3IDU2OSwtNDc1IDEyODMsLTk5NiAxODI2LC0xMzM0IGwgNDYsLTI4IDcsLTI2NiBjIDE2LC02NDUgOTMsLTExOTAgMjQ1LC0xNzM2IDE3LC02MCAyNywtMTEyIDIzLC0xMTYgLTE3LC0xNSAtNTQ4LC03OCAtNzk3LC05NCBsIC0xMDAsLTcgLTEyNSw0NyBDIDkxNDUsMjgyOSA3NTgwLDM3NjcgNjI0NSw0OTQwIDU4NzUsNTI2NCA1MzMyLDU4MDAgNTAxOCw2MTUwIDQ0MzAsNjgwNiAzOTAyLDc1MDUgMzQ1Nyw4MjIxIGwgLTk3LDE1NiAxNSwxNDQgYyAyMCwxODggNTcsMzk1IDEwNCw1ODMgNDMsMTY5IDkwLDMyNiA5NywzMjYgMywwIDY5LC0zOCAxNDcsLTg0IHogTSAxNjk5NCw2MTQxIGMgMTUwNCwtNDYxIDMxMzUsLTc1MSA0ODE1LC04NTUgMTIzLC04IDIyNywtMTggMjMxLC0yMyAxMSwtMTMgNzksLTI1MiAxMTcsLTQxMyAxMTksLTUxMCAxOTIsLTExOTAgMTk2LC0xODUzIGwgMiwtMjQ3IC0xNjAsLTc2IGMgLTE0NjQsLTY5MyAtMzA4NSwtMTE0MiAtNDc0MCwtMTMxMyAtNzUyLC03OCAtMTY2OCwtOTcgLTI0MDAsLTUxIC02MDAsMzggLTEzMzcsMTI2IC0xNDIwLDE2OCAtMjIsMTIgLTg2LDM5IC0xNDIsNjEgLTQ0NywxNzcgLTg1Nyw0MDEgLTExNDUsNjI2IGwgLTc2LDYwIC01MSwxNzAgYyAtMTYzLDU0OCAtMjQyLDEwNjUgLTI1OCwxNjgwIGwgLTYsMjYxIDI4OSw3OCBjIDEwNzMsMjkyIDIxNDYsNjY3IDMxMzksMTA5OSA0MjgsMTg2IDYzMywyODIgMTA1MCw0OTMgMTk4LDEwMCAzNjgsMTgyIDM3OSwxODMgMTAsMCA5MSwtMjEgMTgwLC00OCB6IgogICAgICAgaWQ9InBhdGgxIgogICAgICAgc3R5bGU9ImRpc3BsYXk6aW5saW5lO2ZpbGw6IzAwMDAwMDtmaWxsLW9wYWNpdHk6MTtzdHJva2U6IzAwMDAwMDtzdHJva2Utd2lkdGg6OTIyO3N0cm9rZS1kYXNoYXJyYXk6bm9uZTtzdHJva2Utb3BhY2l0eToxIiAvPgogIDwvZz4KPC9zdmc+Cg=='
		);

		do_action( 'footballpool_admin_pre_menu_init', $menu_slug, FOOTBALLPOOL_ADMIN_BASE_CAPABILITY );
		
		// submenu pages
		self::add_submenu_page(
			$menu_slug,
			__( 'Football Pool Options', 'football-pool' ),
			__( 'Plugin Options', 'football-pool' ),
			FOOTBALLPOOL_ADMIN_BASE_CAPABILITY,
			'footballpool-options',
			'Football_Pool_Admin_Options',
			true
		);

		if ( FOOTBALLPOOL_RANKING_CALCULATION_AJAX === false ) {
			self::add_submenu_page(
				$menu_slug,
				__( 'Score Calculation', 'football-pool' ),
				__( 'Score Calculation', 'football-pool' ),
				FOOTBALLPOOL_ADMIN_BASE_CAPABILITY,
				'footballpool-score-calculation',
				'Football_Pool_Admin_Score_Calculation'
			);
		}

		self::add_submenu_page(
			$menu_slug,
			__( 'Edit users', 'football-pool' ), 
			__( 'Users', 'football-pool' ),
			FOOTBALLPOOL_ADMIN_BASE_CAPABILITY,
			'footballpool-users',
			'Football_Pool_Admin_Users'
		);

		self::add_submenu_page(
			$menu_slug,
			__( 'Edit matches', 'football-pool' ), 
			__( 'Matches', 'football-pool' ),
			FOOTBALLPOOL_ADMIN_MATCHES_CAPABILITY,
			'footballpool-games',
			'Football_Pool_Admin_Games'
		);
		
		self::add_submenu_page(
			$menu_slug,
			__( 'Edit bonus questions', 'football-pool' ), 
			__( 'Questions', 'football-pool' ),
			FOOTBALLPOOL_ADMIN_QUESTIONS_CAPABILITY,
			'footballpool-bonus',
			'Football_Pool_Admin_Bonus_Questions'
		);
		
		self::add_submenu_page(
			$menu_slug,
			__( 'Edit shoutbox', 'football-pool' ), 
			__( 'Shoutbox', 'football-pool' ),
			FOOTBALLPOOL_ADMIN_BASE_CAPABILITY,
			'footballpool-shoutbox',
			'Football_Pool_Admin_Shoutbox'
		);

		self::add_submenu_page(
			$menu_slug,
			__( 'Edit teams', 'football-pool' ),
			__( 'Teams', 'football-pool' ),
			FOOTBALLPOOL_ADMIN_BASE_CAPABILITY,
			'footballpool-teams',
			'Football_Pool_Admin_Teams'
		);
/*
		self::add_submenu_page(
			$menu_slug,
			__( 'Edit team ranking', 'football-pool' ),
			__( 'Team ranking', 'football-pool' ),
			FOOTBALLPOOL_ADMIN_BASE_CAPABILITY,
			'footballpool-team-ranking',
			'Football_Pool_Admin_Teams_Position'
		);
*/
		self::add_submenu_page(
			$menu_slug,
			__( 'Edit venues', 'football-pool' ), 
			__( 'Venues', 'football-pool' ),
			FOOTBALLPOOL_ADMIN_BASE_CAPABILITY,
			'footballpool-venues',
			'Football_Pool_Admin_Stadiums'
		);
		
		self::add_submenu_page(
			$menu_slug,
			__( 'Edit leagues', 'football-pool' ), 
			__( 'Leagues', 'football-pool' ),
			FOOTBALLPOOL_ADMIN_BASE_CAPABILITY,
			'footballpool-leagues',
			'Football_Pool_Admin_Leagues'
		);
		
		self::add_submenu_page(
			$menu_slug,
			__( 'Edit rankings', 'football-pool' ), 
			__( 'Rankings', 'football-pool' ),
			FOOTBALLPOOL_ADMIN_BASE_CAPABILITY,
			'footballpool-rankings',
			'Football_Pool_Admin_Rankings'
		);
		
		self::add_submenu_page(
			$menu_slug,
			__( 'Edit match types', 'football-pool' ), 
			__( 'Match Types', 'football-pool' ),
			FOOTBALLPOOL_ADMIN_BASE_CAPABILITY,
			'footballpool-matchtypes',
			'Football_Pool_Admin_Match_Types'
		);
		
		self::add_submenu_page(
			$menu_slug,
			__( 'Edit groups', 'football-pool' ), 
			__( 'Groups', 'football-pool' ),
			FOOTBALLPOOL_ADMIN_BASE_CAPABILITY,
			'footballpool-groups',
			'Football_Pool_Admin_Groups'
		);

		self::add_submenu_page(
			$menu_slug,
			__( 'Predictions Audit Log', 'football-pool' ),
			__( 'Audit Log', 'football-pool' ),
			FOOTBALLPOOL_ADMIN_BASE_CAPABILITY,
			'footballpool-predictions-audit-log',
			'Football_Pool_Admin_Predictions_Audit_Log'
		);

		self::add_submenu_page(
			$menu_slug,
			__( 'Help', 'football-pool' ),
			__( 'Help', 'football-pool' ),
			FOOTBALLPOOL_ADMIN_BASE_CAPABILITY,
			'footballpool-help',
			'Football_Pool_Admin_Help'
		);

		do_action( 'footballpool_admin_post_menu_init', $menu_slug, FOOTBALLPOOL_ADMIN_BASE_CAPABILITY );
	}
	
	public static function initialize_wp_media() {
		wp_enqueue_media();
	}
	
	// TinyMCE extension
	public static function tinymce_add_plugin() {
		// Don't bother doing this stuff if the current user lacks permissions
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) )
			return;
	 
		// Add only in Rich Editor mode
		if ( get_user_option( 'rich_editing' ) == 'true' ) {
			add_filter( 'mce_external_plugins', array( 'Football_Pool_Admin', 'add_tinymce_footballpool_plugin' ) );
			add_filter( 'mce_buttons', array( 'Football_Pool_Admin', 'register_tinymce_footballpool_button' ) );
			
			/* Only needed for simple tinymce plugin
			$shortcodes = array(
				array( __( 'Ranking', 'football-pool' ), '[fp-ranking ranking=""]' ),
				array( __( 'Predictions', 'football-pool' ), '[fp-predictions match="" question="" text=""]' ),
				array( __( 'Prediction Form', 'football-pool' ), '[fp-predictionform match="" question="" matchtype="" text=""]' ),
				array( __( 'Matches', 'football-pool' ), '[fp-matches match="" matchtype="" group=""]' ),
				array( __( 'Next Matches', 'football-pool' ), '[fp-next-matches date="" matchtype="" group="" num=""]' ),
				array( __( 'Group', 'football-pool' ), '[fp-group id=""]'),
				array( __( 'League Info', 'football-pool' ), '[fp-league-info league="" info="" ranking="" format=""]' ),
				array( __( 'User Score', 'football-pool' ), '[fp-user-score user="" ranking="" date="" text=""]' ),
				array( __( 'User Ranking', 'football-pool' ), '[fp-user-ranking user="" ranking="" date="" text=""]' ),
				array( __( 'Scores', 'football-pool' ), '[fp-scores league="" users="" match="" matchtype=""]' ),
				array( __( 'Countdown', 'football-pool' ), '[fp-countdown date="" match="" texts="" display="" format=""]' ),
				array( __( 'Link to Page', 'football-pool' ), '[fp-link slug=""]' ),
				array( __( 'Link to Registration', 'football-pool' ), '[fp-register title="" new=""]' ),
				array( __( 'Value for ', 'football-pool' ) . __( 'Joker multiplier', 'football-pool' ), '[fp-joker-multiplier]' ),
				array( __( 'Value for ', 'football-pool' ) . __( 'Full points', 'football-pool' ), '[fp-fullpoints]' ),
				array( __( 'Value for ', 'football-pool' ) . __( 'Toto points', 'football-pool' ), '[fp-totopoints]' ),
				array( __( 'Value for ', 'football-pool' ) . __( 'Goal bonus', 'football-pool' ), '[fp-goalpoints]' ),
				array( __( 'Value for ', 'football-pool' ) . __( 'Goal difference bonus', 'football-pool' ), '[fp-diffpoints]' ),
				array( __( 'Show Plugin Option', 'football-pool' ), '[fp-plugin-option option="" default="" type=""]' ),
			);
			*/
			wp_localize_script( 'pool-admin-js'
								, 'FootballPoolTinyMCE'
								, array( 
									'tooltip' => __( 'Add Football Pool Shortcodes. See help page for more info on the parameters.', 'football-pool' ),
									'text' => __( 'Add Shortcode', 'football-pool' ),
//									 'names' => implode( '|', array_map( array( 'self', 'get_shortcode_names' ), $shortcodes ) ),
//									 'shortcodes' => implode( '|', array_map( array( 'self', 'get_shortcode_codes' ), $shortcodes ) ),
									'button_text' => __( 'Add Shortcode', 'football-pool' ),
								)
			);
		}
	}
	/*
	private static function get_shortcode_names( $a ) {
		return $a[0];
	}
	
	private static function get_shortcode_codes( $a ) {
		return $a[1];
	}
	*/
	public static function register_tinymce_footballpool_button( $buttons ) {
		array_push( $buttons, 'footballpool' );
		return $buttons;
	}
	
	public static function add_tinymce_footballpool_plugin( $plugin_array ) {
		$suffix = FOOTBALLPOOL_LOCAL_MODE ? '' : '.min';
		$plugin_array['footballpool'] = FOOTBALLPOOL_PLUGIN_URL . "assets/admin/tinymce/plugin-advanced{$suffix}.js";
		// $plugin_array['footballpool'] = FOOTBALLPOOL_PLUGIN_URL . "assets/admin/tinymce/plugin-simple{$suffix}.js";
		return $plugin_array;
	}
	// end TinyMCE

	/** @noinspection HtmlUnknownTarget */
	public static function add_plugin_settings_link( $links, $file ) {
		if ( $file == plugin_basename( FOOTBALLPOOL_PLUGIN_DIR . 'football-pool.php' ) ) {
			$links[] = sprintf( "<a href=\"admin.php?page=footballpool-options\">%s</a>", __( 'Settings', 'football-pool' ) );
			$links[] = sprintf( "<a href=\"admin.php?page=footballpool-help\">%s</a>", __( 'Help', 'football-pool' ) );
			$links[] = sprintf( "<a href=\"%s\">%s</a>", FOOTBALLPOOL_DONATE_LINK, __( 'Donate', 'football-pool' ) );
		}

		return $links;
	}
	
	public static function get_value( $key, $default = '' ) {
		return Football_Pool_Utils::get_fp_option( $key, $default );
	}
	
	public static function set_value( $key, $value ) {
		Football_Pool_Utils::update_fp_option( $key, $value );
	}
	
	public static function image_input( $label, $key, $value, $description = '', $type = 'regular-text' ) {
		$key = esc_attr( $key );
		$title = __( 'Choose Image', 'football-pool' );
		// based on http://mikejolley.com/2012/12/using-the-new-wordpress-3-5-media-uploader-in-plugins/
		echo "<script type='text/javascript'>
			jQuery( document ).ready( function() {
				var file_frame;
				jQuery( '#r-{$key}' ).on( 'click', '#{$key}_button', function( event ) {
					event.preventDefault();
					
					if ( file_frame ) {
						file_frame.open();
						return;
					}
				 
					file_frame = wp.media.frames.file_frame = wp.media( {
						title: '{$title}',//jQuery( this ).data( 'uploader_title' ),
						button: {
							text: jQuery( this ).data( 'uploader_button_text' ),
						},
						multiple: false  
					} );
				 
					file_frame.on( 'select', function() {
						attachment = file_frame.state().get( 'selection' ).first().toJSON();
						// jQuery( '#{$key}' ).val( attachment.sizes.thumbnail.url );
						jQuery( '#{$key}' ).val( attachment.url );
					} );
					
					file_frame.open();
				} );
			} );
			</script>
		";

		$input = sprintf( '<input name="%s" type="text" id="%s" value="%s" title="%s" class="fp-image-upload-value %s">
							<input id="%s_button" type="button" value="%s" class="fp-image-upload-button">'
			, $key
			, $key
			, esc_attr( $value )
			, esc_attr( $value )
			, esc_attr( $type )
			, $key
			, $title
		);
		echo self::option_row( $key, $label, $input, $description );
	}
	
	public static function checkbox_input( $label, $key, $checked, $description = ''
									, $extra_attr = '', $depends_on = '' ) {
		$input = sprintf( '<input name="%s" type="checkbox" id="%s" value="1" %s %s>'
							, esc_attr( $key )
							, esc_attr( $key )
							, ( $checked ? 'checked="checked" ' : '' )
							, $extra_attr
						);
		echo self::option_row( $key, $label, $input, $description, $depends_on );
	}
	
	public static function dropdown( $key, $value, $options, $extra_attr = '', $multi = 'single', $class = '' ) {
		$i = 0;
		$class = 'fp-select ' . $class;
		$multiple = '';
		$name = esc_attr( $key );
		if ( $multi === 'multi' ) {
			$multiple = 'multiple="multiple" size="6"';
			$name .= '[]';
			$class .= ' fp-multi-select';
		}
		$output = sprintf( '<select id="%1$s" name="%2$s" class="%4$s" %3$s>', esc_attr( $key ), $name, $multiple, $class );
		
		foreach ( $options as $option ) {
			if ( is_array( $extra_attr ) ) {
				$extra = isset( $extra_attr[$i] ) ? $extra_attr[$i] : '';
			} else {
				$extra = $extra_attr;
			}
			
			$selected = ( self::check_selected_value( $value, $option['value'] ) ? 'selected="selected" ' : '' );
			$output .= sprintf( '<option id="%s_answer_%d" value="%s" %s %s>%s</option>'
								, esc_attr( $key )
								, $i
								, esc_attr( $option['value'] )
								, $selected
								, $extra
								, $option['text']
						);
			$i++;
		}
		$output .= '</select>';
		
		return $output;
	}
	
	private static function check_selected_value( $check_value, $option_value ) {
		if ( is_array( $check_value ) ) {
			return in_array( $option_value, $check_value );
		} else {
			return ( $option_value == $check_value );
		}
	}
	
	public static function multiselect_input( $label, $key, $value, $options, $description = '', 
									$extra_attr = '', $depends_on = '', $class = '' ) {
		echo self::option_row( $key, $label, self::dropdown( $key, $value, $options, $extra_attr, 'multi', $class )
								, $description, $depends_on );
	}
	
	public static function dropdown_input( $label, $key, $value, $options, $description = '', 
									$extra_attr = '', $depends_on = '', $class = '' ) {
		echo self::option_row( $key, $label, self::dropdown( $key, $value, $options, $extra_attr, 'single', $class )
								, $description, $depends_on );
	}
	
	public static function radiolist_input( $label, $key, $value, $options, $description = '', 
									$extra_attr = '', $depends_on = '' ) {
		$hide = self::hide_input( $depends_on ) ? ' style="display:none;"' : '';
		
		$i = 0;
		$label_extra = sprintf( '_answer_%d', $i );
		$input = '';
		foreach ( $options as $option ) {
			if ( is_array( $extra_attr ) ) {
				$extra = isset( $extra_attr[$i] ) ? $extra_attr[$i] : '';
			} else {
				$extra = $extra_attr;
			}
			$selected = ( self::check_selected_value( $value, $option['value'] ) ? 'checked="checked" ' : '' );
			$input .= sprintf( '<label class="radio"><input name="%s" type="radio" id="%s_answer_%d" 
								value="%s" %s %s> %s</label>'
								, esc_attr( $key )
								, esc_attr( $key )
								, $i++
								, esc_attr( $option['value'] )
								, $selected
								, $extra
								, $option['text']
						);
		}
		
		echo self::option_row( $key, $label, $input, $description, $depends_on, $label_extra );
	}

	/** @noinspection PhpInconsistentReturnPointsInspection */
	public static function hidden_input( $key, $value, $return = 'echo' ) {
		$output = '';

		if ( is_array( $value) ) {
			$i = 1;
			foreach ( $value as $val ) {
				$output .= sprintf( '<input type="hidden" name="%s[]" id="%s" value="%s">'
					, esc_attr( $key )
					, esc_attr( $key ) . '_' . $i++
					, esc_attr( $val )
				);
			}
		} else {
			$output = sprintf( '<input type="hidden" name="%s" id="%s" value="%s">'
				, esc_attr( $key )
				, esc_attr( $key )
				, esc_attr( $value )
			);
		}

		if ( $return === 'echo' ) {
			echo $output;
		} else {
			return $output;
		}
	}
	
	public static function no_input( $label, $value, $description ) {
		echo '<tr>
			<th scope="row"><label>', $label, '</label></th>
			<td>', Football_Pool_Utils::xssafe( $value ), '</td>
			<td><span class="description">', $description, '</span></td>
			</tr>';
	}
	
	// helper function for the date_time input. 
	// returns the combined date(time) string from the individual inputs
	public static function make_date_from_input( $input_name, $type = 'datetime' ) {
		$y = Football_Pool_Utils::post_integer( $input_name . '_y' );
		$m = Football_Pool_Utils::post_integer( $input_name . '_m' );
		$d = Football_Pool_Utils::post_integer( $input_name . '_d' );
		$value = ( $y !== 0 && $m !== 0 && $d !== 0 ) ? sprintf( '%04d-%02d-%02d', $y, $m, $d ) : '';
		
		if ( $value !== '' && $type === 'datetime' ) {
			$h = Football_Pool_Utils::post_integer( $input_name . '_h', -1 );
			$i = Football_Pool_Utils::post_integer( $input_name . '_i', -1 );
			$value = ( $h != -1 && $i != -1 ) ? sprintf( '%s %02d:%02d', $value, $h, $i ) : '';
		}
		
		return $value;
	}
	
	public static function the_datetime_input( $key, $value ) {
		if ( $value !== '' && ! is_null( $value ) ) {
			if ( is_object( $value ) ) {
				$date = $value;
			} else {
				//$date = DateTime::createFromFormat( 'Y-m-d H:i', $value );
				/** @noinspection PhpUnhandledExceptionInspection */
				$date = new DateTime( Football_Pool_Utils::date_from_gmt ( $value ) );
			}
			$year = $date->format( 'Y' );
			$month = $date->format( 'm' );
			$day = $date->format( 'd');
			$hour = $date->format( 'H' );
			$minute = $date->format( 'i' );
		} else {
			$year = $month = $day = $hour = $minute = '';
		}
		
		$input = sprintf( '<input name="%1$s_y" type="text" id="%1$s_y" value="%2$s" class="with-hint date-y"
							title="yyyy" maxlength="4">'
							, esc_attr( $key )
							, esc_attr( $year )
				);
		$input .= '-';
		$input .= sprintf( '<input name="%1$s_m" type="text" id="%1$s_m" value="%2$s" class="with-hint date-m"
							title="mm" maxlength="2">'
							, esc_attr( $key )
							, esc_attr( $month )
				);
		$input .= '-';
		$input .= sprintf( '<input name="%1$s_d" type="text" id="%1$s_d" value="%2$s" class="with-hint date-d"
							title="dd" maxlength="2">'
							, esc_attr( $key )
							, esc_attr( $day )
				);
		$input .= '&nbsp;';
		$input .= sprintf( '<input name="%1$s_h" type="text" id="%1$s_h" value="%2$s" class="with-hint date-h"
							title="hr" maxlength="2">'
							, esc_attr( $key )
							, esc_attr( $hour )
				);
		$input .= ':';
		$input .= sprintf( '<input name="%1$s_i" type="text" id="%1$s_i" value="%2$s" class="with-hint date-i"
							title="mn" maxlength="2">'
							, esc_attr( $key )
							, esc_attr( $minute )
				);
		return $input;
	}
	
	public static function datetime_input( $label, $key, $value, $description = '', $extra_attr = ''
									, $depends_on = '' ) {
		$input = self::the_datetime_input( $key, $value );
		echo self::option_row( $key, $label, $input, $description, $depends_on );
	}
	
	public static function datetimepicker_input( $label, $key, $value, $description = '', $extra_attr = ''
									, $depends_on = '' ) {
		$input = self::datetimepicker( $key, $value, null, 'return' );
		echo self::option_row( $key, $label, $input, $description, $depends_on );
	}

	/** @noinspection PhpInconsistentReturnPointsInspection */
	public static function datepicker( $key, $value, $return = 'echo' ) {
		$input = sprintf( '<input type="text" id="%s" name="%s" size="10" maxlength="10" value="%s">'
							, esc_attr( $key ), esc_attr( $key ), esc_attr( $value ) );
		$input .= sprintf( '<script>jQuery( function() { jQuery( "#%s" ).datetimepicker( { format: "Y-m-d", timepicker: false, closeOnDateSelect: true, lazyInit: false } ); } );</script>'
							, esc_attr( $key ) );
		
		if ( $return === 'echo' ) {
			echo $input;
		} else {
			return $input;
		}
	}

	/** @noinspection PhpInconsistentReturnPointsInspection */
	public static function datetimepicker( $key, $value, $step = 60, $return = 'echo' ) {
		$input = sprintf( '<input type="text" id="%s" name="%s" size="16" maxlength="16" value="%s">'
							, esc_attr( $key ), esc_attr( $key ), esc_attr( $value ) );
		$input .= sprintf( '<script>jQuery( function() { jQuery( "#%s" ).datetimepicker( { format: "Y-m-d H:i", step: %d, lazyInit: false } ) } );</script>'
							, esc_attr( $key ), $step );
		
		if ( $return === 'echo' ) {
			echo $input;
		} else {
			return $input;
		}
	}
	
	public static function textarea_field( $key, $value, $type = '' ) {
		return sprintf( '<textarea name="%s" class="%s" cols="50" rows="5">%s</textarea>'
			, esc_attr( $key ), $type, $value
		);
	}
	
	public static function textarea_input( $label, $key, $value, $description = '', $type = '', $depends_on = '' ) {
		echo self::option_row( $key, $label, self::textarea_field( $key, $value, $type )
								, $description, $depends_on );
	}
	
	public static function text_input_field( $key, $value, $type = 'regular-text', $capability = '' ) {
		if ( $capability == '' || ( $capability != '' && current_user_can( $capability ) ) ) {
			$output = '<input name="' . esc_attr( $key ) . '" type="text" id="' . esc_attr( $key ) 
					. '" value="' . esc_attr( $value ) . '" class="' . esc_attr( $type ) . '">';
		} else {
			$output = $value;
		}
		return $output;
	}

	/**
	 * @param $label
	 * @param $key
	 * @param $value
	 * @param string|null $description
	 * @param string|null $type
	 * @param mixed|null $depends_on
	 * @return void
	 */
	public static function text_input( $label, $key, $value, ?string $description = '',
	                                   ?string $type = 'regular-text', $depends_on = '' ) {
		echo self::option_row( $key, $label, self::text_input_field( $key, $value, $type )
								, $description, $depends_on );
	}
	
	private static function hide_input( $depends_on ) {
		if ( is_bool( $depends_on ) ) {
			$hide = $depends_on;
		} elseif ( is_array( $depends_on ) ) {
			$hide = true;
			foreach ( $depends_on as $key => $val ) {
				$hide = $hide && ( (string)self::get_value( $key ) === (string)$val );
			}
		} else {
			$hide = ( $depends_on !== '' && (string)self::get_value( $depends_on ) === '0' );
		}
		
		return $hide;
	}
	
	private static function option_row( $id, $label, $input, $description, $depends_on = '', $label_extra = '' ) {
		$hide = self::hide_input( $depends_on ) ? ' style="display: none"' : '';
		$class = ( $depends_on == '' ) ? '' : ' class="no-border"';
		
		$option = sprintf( '<th scope="row"><label for="%s%s">%s</label></th>'
							, esc_attr( $id ), $label_extra, $label );
		$input = sprintf( '<td>%s</td>', $input );
		$description = sprintf( '<td><span class="description">%s</span></td>', $description );
		
		return sprintf( '<tr%s%s id="r-%s" valign="top">%s%s%s</tr>'
						, $hide, $class, esc_attr( $id ), $option, $input, $description
				);
	}
	
	public static function show_option( $option ) {
		switch ( $option['type'] ) {
			case 'multi-list':
			case 'multi-select':
			case 'multi-selectbox':
				self::multiselect_input( $option['label'], $option['id'], self::get_value( $option['id'] ), $option['options'], $option['desc'], $option['extra_attr'], $option['depends_on'], $option['class'] );
				break;
			case 'dropdownlist':
			case 'dropdown':
			case 'select':
			case 'selectbox':
				self::dropdown_input( $option['label'], $option['id'], self::get_value( $option['id'] ), $option['options'], $option['desc'], $option['extra_attr'], $option['depends_on'], $option['class'] );
				break;
			case 'radiolist':
				self::radiolist_input( $option['label'], $option['id'], self::get_value( $option['id'] ), $option['options'], $option['desc'], $option['extra_attr'], $option['depends_on'] );
				break;
			case 'checkbox':
				self::checkbox_input( $option['label'], $option['id'], (bool) self::get_value( $option['id'] ), $option['desc'], $option['extra_attr'], $option['depends_on'] );
				break;
			case 'datetimepicker':
				self::datetimepicker_input( $option['label'], $option['id'], self::get_value( $option['id'] ), $option['desc'], $option['extra_attr'], $option['depends_on'] );
				break;
			case 'datetime':
				self::datetime_input( $option['label'], $option['id'], self::get_value( $option['id'] ), $option['desc'], $option['extra_attr'], $option['depends_on'] );
				break;
			case 'textarea':
			case 'multiline':
				self::textarea_input( $option['label'], $option['id'], self::get_value( $option['id'] ), $option['desc'], '', $option['depends_on'] );
				break;
			case 'integer':
			case 'string':
			case 'text':
			default:
				self::text_input( $option['label'], $option['id'], self::get_value( $option['id'] ), $option['desc'], 'regular-text', $option['depends_on'] );
				break;
		}
	}
	
	public static function show_value( $option ) {
		if ( is_array( $option[0] ) ) {
			$type = $option[0][0];
		} else {
			$type = $option[0];
		}
		
		switch ( $type ) {
			case 'no_input':
				self::no_input( $option[1], $option[3], $option[4] );
				break;
			case 'dropdownlist':
			case 'dropdown':
			case 'select':
			case 'selectbox':
				self::dropdown_input( $option[1], $option[2], $option[3], $option[4], $option[5], isset( $option[6] ) ? $option[6] : '', '', isset( $option[7] ) ? $option[7] : '' );
				break;
			case 'radiolist':
				self::radiolist_input( $option[1], $option[2], $option[3], $option[4], isset( $option[5] ) ? $option[5] : '', isset( $option[6] ) ? $option[6] : '' );
				break;
			case 'checkbox':
				self::checkbox_input( $option[1], $option[2], $option[3], $option[4], isset( $option[5] ) ? $option[5] : '' );
				break;
			case 'hidden':
				self::hidden_input( $option[2], $option[3] );
				break;
			case 'image':
				self::image_input( $option[1], $option[2], $option[3], $option[4] );
				break;
			case 'date':
			case 'datetime':
				self::datetime_input( $option[1], $option[2], $option[3], ( isset( $option[4] ) ? $option[4] : '' ) );
				break;
			case 'datetimepicker':
				self::datetimepicker_input( $option[1], $option[2], $option[3], ( isset( $option[4] ) ? $option[4] : '' ) );
				break;
			case 'multiline':
			case 'textarea':
				self::textarea_input( $option[1], $option[2], $option[3], $option[4], ( isset( $option[5] ) ? $option[5] : '' ), ( isset( $option[6] ) ? $option[6] : '' ) );
				break;
			case 'integer':
			case 'string':
			case 'text':
			default:
				self::text_input( $option[1], $option[2], $option[3], $option[4], ( isset( $option[5] ) ? $option[5] : 'regular-text' ), ( isset( $option[6] ) ? $option[6] : '' ) );
				break;
		}
	}
	
	public static function intro( $txt ) {
		echo sprintf( '<p>%s</p>', $txt );
	}
	
	// overwrite in the individual help pages
	public static function help() {
		self::add_help_tabs();
	}
	
	// Define a method named 'help' on each admin page that calls this method with 
	// the tab definition (array of tabs) and an optional sidebar.
	// Don't forget to add the admin_head-hook!
	public static function add_help_tabs( $help_tabs = '', $help_sidebar = '' ) {
		if ( ! is_array( $help_tabs ) ) return;
		
		$screen = get_current_screen();
		foreach ( $help_tabs as $help_tab ) {
			$screen->add_help_tab(
						array(
							'id' => $help_tab['id'],
							'title' => $help_tab['title'],
							'content' => $help_tab['content']
						)
					);
		}
		
		if ( $help_sidebar !== '' ) {
			$screen->set_help_sidebar(
							sprintf( 
									'<p><strong>%s</strong></p><p>%s</p>' 
									, __( 'For more information:', 'football-pool' )
									, $help_sidebar
							)
						);
		}
	}
	
	public static function admin_sectiontitle( $title ) {
		echo '<h3>', $title, '</h3>';
	}

	protected static function get_search_subtitle( $search ) {
		if ( $search !== '' ) {
			$subtitle = sprintf( __( 'Search results for &#8220;%s&#8221;' ), esc_html( $search ) );
		} else {
			$subtitle = '';
		}
		return $subtitle;
	}

	// use type 'updated' for yellow message and type 'error' or 'important' for the red one
	public static function notice( $msg, $type = 'updated', $fade = true, $is_dismissible = false ) {
		$class = 'notice ';
		switch ( $type ) {
			case 'important':
			case 'error':
				$class .= 'notice-error';
				break;
			case 'info':
				$class .= 'notice-info';
				break;
			case 'warning':
				$class .= 'notice-warning';
				break;
			case 'updated':
			default:
				$class .= 'notice-success';
		}

		if ( $fade === true ) $class .= ' fade';
		if ( $is_dismissible === true ) {
			$class .= ' is-dismissible';
			// $msg .= '<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>';
		}

		echo '<div class="', esc_attr( $class ), '"><p>', $msg, '</p></div>';
	}

	public static function admin_header( $title, $subtitle = '', $add_new = '', $extra = '' ) {
		echo '<div class="wrap fp-admin">';
		
		$page = Football_Pool_Utils::get_string( 'page' );
		if ( $add_new === 'add new' ) {
			$add_new = "<a class='page-title-action' href='?page={$page}&amp;action=edit'>"
					. __( 'Add New' ) . "</a>";
		}
		
		if ( $subtitle !== '' ) {
			$subtitle = sprintf( '<span class="subtitle">%s</span>', $subtitle );
		}
		
		printf( '<h1 class="wp-heading-inline">%s</h1>%s%s', $title, $subtitle, $add_new );

		echo $extra;
		echo '<hr class="wp-header-end">';
		
		$current_url = set_url_scheme( 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] );
		$current_url = esc_url( remove_query_arg( array( 'action', 'item_id' ), $current_url ) );
		// $current_url = esc_url( remove_query_arg( array( 'item_id' ), $current_url ) );
		/** @noinspection HtmlUnknownTarget */
		printf( '<form action="%s" method="post">', $current_url );
		echo '<input type="hidden" name="action" id="action" value="update">';
		wp_nonce_field( FOOTBALLPOOL_NONCE_ADMIN );
	}
	
	public static function admin_footer() {
		echo '<a href="#" class="back-to-top hidden fp-icon-arrow-up"></a>
			<div class="progress-bar"></div>
			</form></div>';
	}

	/**
	 * @param array $search Array containing text and value for the box
	 */
	protected static function search_box( $search, $prev_form_action = '' ) {
		echo '<p class="search-box">';
		printf( '<label class="screen-reader-text" for="search-input">%s:</label>', $search['text'] );
		printf( '<input type="search" id="search-input" name="s" value="%s">', esc_attr( $search['value'] ) );
		self::hidden_input( 'prev_action', $prev_form_action );
		if ( isset( $search['search_by'] ) ) {
			printf( '<span class="search-by">%s</span>', $search['search_by'] );
		}
		printf( '<input type="submit" name="search_submit" id="search-submit" class="button" value="%s"></p>'
			, esc_attr( $search['text'] ) );
		if ( isset( $search['extra_search'] ) ) {
			echo '<p class="search-box extra-search"><label>';
			if ( isset( $search['extra_search_text'] ) ) printf( '%s: ', $search['extra_search_text'] );
			printf( '%s</label></p>', $search['extra_search'] );
		}
	}

	protected static function bulk_actions( $actions, $name = 'action', $pagination = false, $search = false, $extra = false ) {
		if ( $search !== false ) {
			self::search_box( $search );
		}
		
		echo '<div class="tablenav top">';
		if ( count( $actions ) > 0 ) {
			echo '<div class="alignleft actions"><select id="', $name, '" name="', $name, '">';
			echo '<option selected="selected" value="-1">Bulk Actions</option>';
			foreach ( $actions as $action ) {
				printf( '<option value="%s" bulk-msg="%s">%s</option>'
						, $action[0]
						, $action[2] ?? ''
						, $action[1]
				);
			}
			printf( "</select><input onclick=\"return FootballPoolAdmin.bulk_action_warning( '%s' )\" type='submit' value='%s' class='button-secondary action' id='do%s' name=''>"
					, $name, __( 'Apply' ), $name );
			echo '</div>';
		}
		if ( $extra !== false ) {
			if ( ! isset( $extra['name'] ) ) $extra['name'] = $extra['id'];
			echo '<div class="alignleft actions">';
			printf( '<label class="screen-reader-text" for="%s">%s</label>'
					, esc_attr( $extra['id'] )
					, $extra['text']
			);
			$options = array( '' => $extra['text'] );
			foreach( $extra['options'] as $val => $option ) $options[$val] = $option;
			echo Football_Pool_Utils::select( $extra['id'], $options, '', $extra['name'] );
			printf( '<input type="submit" name="changeit" id="changeit" class="button" value="%s">'
					, __( 'Change' ) );
			echo '</div>';
		}
		
		if ( is_object( $pagination ) ) {
			$pagination->show();
		}
		echo '<br class="clear"></div>';
	}
	
	protected static function list_table( $cols, $rows, $bulkactions = null, $rowactions = null
										, $pagination = false, $search = false ) {
		if ( $bulkactions === null ) $bulkactions = [];
		if ( $rowactions === null ) $rowactions = [];

		self::bulk_actions( $bulkactions, 'action', $pagination, $search );
		echo "<table class='wp-list-table widefat fixed'>";
		self::list_table_def( $cols, 'head' );
		self::list_table_def( $cols, 'foot' );
		self::list_table_body( $cols, $rows, $rowactions );
		echo '</table>';
		self::bulk_actions( $bulkactions, 'action2' );
	}
	
	protected static function list_table_def( $cols, $tag ) {
		echo "<t{$tag}><tr>";
		echo '<th class="manage-column column-cb check-column" id="cb" scope="col"><input type="checkbox"></th>';
		foreach ( $cols as $col ) {
			echo '<th id="', esc_attr( $col[2] ), '-', $tag, '" class="manage-column column-', esc_attr( $col[2] ), '" scope="col">'
				, $col[1], '</th>';
		}
		echo "</tr></t{$tag}>";
	}

	protected static function list_table_body( $cols, $rows, $rowactions ) {
		echo "<tbody id='the-list'>";
		
		$r = count( $rows );
		$c = count( $cols );
		$page = Football_Pool_Utils::get_string( 'page' );
		
		if ( $r === 0 ) {
			echo "<tr><td colspan='", $c+1, "'>", __( 'no data', 'football-pool' ), "</td></tr>";
		} else {
			for ( $i = 0; $i < $r; $i++ ) {
				$row_class = ( $i % 2 === 0 ) ? 'alternate' : '';
				echo "
					<tr class='{$row_class}' id='row-{$i}'>
					<th class='check-column' scope='row'>
						<input type='checkbox' value='{$rows[$i][$c]}' name='itemcheck[]'>
					</th>";
				for ( $j = 0; $j < $c; $j++ ) {
					echo "<td class='column-{$cols[$j][2]}'>";
					if ( $j === 0 ) {
						echo '<strong><a title="Edit “', esc_attr( $rows[$i][$j] ), '”" href="?page=', esc_attr( $page ), '&amp;action=edit&amp;item_id=', esc_attr( $rows[$i][$c] ), '" class="row-title">';
					}
					
					switch ( $cols[$j][0] ) {
						case 'boolean':
							$value = $rows[$i][$j] == 1 ? 
											__( 'yes', 'football-pool' ) : 
											__( 'no', 'football-pool' );
							break;
						case 'image':
						case 'link':
							$value = $rows[$i][$j];
							break;
						case 'text':
						default:
							$value = Football_Pool_Utils::xssafe( $rows[$i][$j] );
					}
					echo $value;
					
					if ( $j === 0 ) {
						$row_action_url = sprintf( '?page=%s&amp;action=edit&amp;item_id=%s'
													, esc_attr( $page )
													, esc_attr( $rows[$i][$c] )
											);
						$row_action_url = wp_nonce_url( $row_action_url, FOOTBALLPOOL_NONCE_ADMIN );
						echo '</a></strong><br>
								<div class="row-actions">
									<span class="item-id">', __( 'id', 'football-pool' ), ': '
										, $rows[$i][$c], '</span>&nbsp;|&nbsp;
									<span class="edit">
										<a href="', $row_action_url, '">', __( 'Edit' ), '</a> | 
									</span>';
						foreach ( $rowactions as $action ) {
							$row_action_url = sprintf( '?page=%s&amp;action=%s&amp;item_id=%s'
														, esc_attr( $page )
														, esc_attr( $action[0] )
														, esc_attr( $rows[$i][$c] )
												);
							$row_action_url = wp_nonce_url( $row_action_url, FOOTBALLPOOL_NONCE_ADMIN );
							echo '<span class="edit">
									<a href="', $row_action_url, '">', $action[1], '</a> | 
								</span>';
						}
						$row_action_url = sprintf( '?page=%s&amp;action=delete&amp;item_id=%s'
													, esc_attr( $page )
													, esc_attr( $rows[$i][$c] )
											);
						$row_action_url = wp_nonce_url( $row_action_url, FOOTBALLPOOL_NONCE_ADMIN );
						echo "<span class='delete'>
									<a onclick=\"return confirm( '", __( 'You are about to delete this item. \'Cancel\' to stop, \'OK\' to delete.', 'football-pool' ), "' )\" href='{$row_action_url}' class='submitdelete'>", __( 'Delete' ), "</a>
								</span>
							</div>";
					}
					
					echo "</td>";
				}
				echo "</tr>";
			}
		}
		echo '</tbody>';
	}
	
	public static function value_form( $values ) {
		echo '<table class="form-table">';
		foreach ( $values as $value ) {
			self::show_value( $value );
		}
		echo '</table>';
	}

	public static function options_form( $values ) {
		echo '<table class="form-table fp-options">';
		foreach ( $values as $value ) {
			self::show_option( $value );
		}
		echo '</table>';
	}

	public static function empty_scorehistory( $ranking_id = 'all', $scorehistory = null ) {
		global $wpdb, $pool;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		if ( $scorehistory === null ) {
			$scorehistory = $pool->get_score_table();
		}
		
		if ( $ranking_id === 'all' ) {
			$check = self::empty_table( $scorehistory );
		} elseif ( is_int( $ranking_id ) && $ranking_id > 0 ) {
			$sql = $wpdb->prepare( "DELETE FROM {$prefix}{$scorehistory} WHERE ranking_id = %d", $ranking_id );
			$check = ( $wpdb->query( $sql ) !== false );
		} else {
			$check = false;
		}
		
		return $check;
	}

	/**
	 * @param string|null $table_name
	 * @return bool
	 */
	public static function empty_table( ?string $table_name = '' ): bool
	{
		global $wpdb;
		$prefix = FOOTBALLPOOL_DB_PREFIX;
		
		if ( $table_name === '' ) return false;
		
		$cache_key = 'fp_delete_method';
		$delete_method = wp_cache_get( $cache_key, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );
		
		if ( $delete_method === false ) {
			$delete_method = 'TRUNCATE TABLE';
			wp_cache_set( $cache_key, $delete_method, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );
		}
		
		$sql  = "{$delete_method} {$prefix}{$table_name}";
		$check = ( $wpdb->query( $sql ) !== false );
		// fix if user has no TRUNCATE rights
		if ( $check === false ) {
			$delete_method = 'DELETE FROM';
			wp_cache_set( $cache_key, $delete_method, FOOTBALLPOOL_WPCACHE_NON_PERSISTENT );
			
			$sql  = "{$delete_method} {$prefix}{$table_name}";
			$check = ( $wpdb->query( $sql ) !== false );
		}
		
		return $check;
	}
	
	private static function recalculate_scorehistory_lightbox() {
		echo "<script> FootballPoolAdmin.calculate() </script>";
	}
	
	public static function recalculate_button() {
		if ( FOOTBALLPOOL_RANKING_CALCULATION_AJAX === false ) {
			$nonce = wp_create_nonce( FOOTBALLPOOL_NONCE_SCORE_CALC );
			self::secondary_button(
						__( 'Recalculate Scores', 'football-pool' )
						, "admin.php?page=footballpool-score-calculation&fp_recalc_nonce={$nonce}"
						, false
						, 'link'
					);
		} else {
			self::secondary_button( __( 'Recalculate Scores', 'football-pool' )
									, array( '', "FootballPoolAdmin.calculate()" )
									, false
									, 'js-button'
			);
		}
	}

	/**
	 * @param string $force
	 * @return bool
	 */
	public static function update_score_history( string $force = 'no' ): bool
	{
		$auto_calc = Football_Pool_Utils::get_fp_option( 'auto_calculation'
														, FOOTBALLPOOL_RANKING_AUTOCALCULATION
														, 'int' );
		
		if ( FOOTBALLPOOL_RANKING_CALCULATION_AJAX && ( $auto_calc === 1 || $force === 'force' ) ) {
			self::recalculate_scorehistory_lightbox();
		} else {
			self::recalculate_button();
		}
		
		return true;
	}
	
	private static function get_button_action_val( $action ) {
		$onclick_val = '';
		
		if ( is_array( $action ) ) {
			$action_val = array_shift( $action );
			if ( count( $action ) > 0 ) {
				foreach ( $action as $val ) {
					$onclick_val .= "{$val};";
				}
			}
		} else {
			$action_val = $action;
		}
		return [$action_val, $onclick_val];
	}
	
	// this function returns HTML for a secondary button, rather than echoing it
	public static function link_button( $text, $action, $wrap = false, $other_attributes = null, $type = 'secondary' ) {
		$actions = self::get_button_action_val( $action );
		$action_val  = $actions[0];
		$onclick_val = $actions[1];
		
		$attributes = '';
		if ( is_array( $other_attributes ) ) {
			foreach( $other_attributes as $key => $value ) {
				$attributes .= $key . '="' . esc_attr( $value ) . '" ';
			}
		} elseif ( ! empty( $other_attributes ) ) {
			$attributes = $other_attributes;
		}
		
		if ( $action_val !== '' ) $action_val = "location.href = '{$action_val}';";
		$button = sprintf( '<input type="button" onclick="%s%s" 
									class="button button-%s" value="%s" %s/>'
							, $action_val
							, $onclick_val
							, $type
							, esc_attr( $text )
							, $attributes
					);
		if ( $wrap ) {
			$button = '<p class="submit">' . $button . '</p>';
		}
		
		return $button;
	}

	public static function get_secondary_button( $text, $action, $wrap = false, $type = 'button', $other_attributes = null ) {
		$actions = self::get_button_action_val( $action );
		$action_val  = $actions[0];
		$onclick_val = $actions[1];

		$onclick_val = "jQuery( '#action, #form_action' ).val( '{$action_val}' );" . $onclick_val;
		$atts = array( "onclick" => $onclick_val );

		if ( is_array( $other_attributes ) ) {
			foreach( $other_attributes as $key => $value ) {
				$atts[$key] = $value;
			}
		}

		return get_submit_button( $text, 'secondary', $action_val, $wrap, $atts );
	}

	public static function secondary_button( $text, $action, $wrap = false, $type = 'button', $other_attributes = null ) {
		if ( $type === 'button' ) {
			echo self::get_secondary_button( $text, $action, $wrap, $type, $other_attributes );
		} elseif ( $type === 'link' || $type === 'js-button' ) {
			echo self::link_button( $text, $action, $wrap, $other_attributes );
		}
	}

	/**
	 * @param string $text
	 * @param string|array $action
	 * @param bool|null $wrap
	 * @return void
	 */
	public static function primary_button( string $text, $action, ?bool $wrap = false ) {
		$onclick_val = '';
		
		if ( is_array( $action ) ) {
			$action_val = array_shift( $action );
			if ( count( $action ) > 0 ) {
				foreach ( $action as $val ) {
					$onclick_val .= "{$val};";
				}
			}
		} else {
			$action_val = $action;
		}
		
		$onclick_val = "jQuery('#action, #form_action').val('{$action_val}');" . $onclick_val;
		
		submit_button( 
				$text, 
				'primary', 
				$action_val, 
				$wrap, 
				array( "onclick" => $onclick_val ) 
		);
	}

	/**
	 * @param string $text
	 * @param bool $wrap
	 * @return void
	 */
	public static function cancel_button( string $text = '', bool $wrap = false ) {
		if ( $text === '' ) $text = __( 'Cancel', 'football-pool' );
		self::secondary_button( $text, 'cancel', $wrap );
	}

	/** @noinspection PhpInconsistentReturnPointsInspection */
	public static function donate_button( $return_type = 'echo' ) {
		$str = "<div id='donate-button-container'>
				<div id='donate-button'></div>
				<script src='https://www.paypalobjects.com/donate/sdk/donate-sdk.js' charset='UTF-8'></script>
				<script>
						PayPal.Donation.Button({
							env:'production',
							hosted_button_id:'83WKPJ6CRMUAA',
							image: {
								src:'https://www.paypalobjects.com/en_US/NL/i/btn/btn_donateCC_LG.gif',
								alt:'Donate with PayPal button',
								title:'PayPal - The safer, easier way to pay online!',
							}
						}).render('#donate-button');
				</script>
				</div>";

		if ( $return_type === 'echo' ) {
			echo $str;
		} else {
			return $str;
		}
	}
	
	public static function example_date( $gmt = 'false', $offset = -1 ) {
		if ( $offset === -1 ) $offset = 2 * WEEK_IN_SECONDS;
		$date = date( 'Y-m-d 18:00', time() + $offset );
		if ( $gmt === 'gmt' ) $date = Football_Pool_Utils::gmt_from_date( $date );
		return $date;
	}
}
