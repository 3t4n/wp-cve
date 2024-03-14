<?php
/**
 * Layout section in team page.
 *
 * @since      2.0.0
 * @version    2.0.0
 *
 * @package    WP_Team
 * @subpackage WP_Team/admin
 * @author     ShapedPlugin<support@shapedplugin.com>
 */

namespace ShapedPlugin\WPTeam\Admin\Configs\Generator;

use ShapedPlugin\WPTeam\Admin\Framework\Classes\SPF_TEAM;
// Cannot access directly.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * This class is responsible for layout section in Team page.
 *
 * @since      2.0.0
 */
class SPTP_Layout {

	/**
	 * Team layout settings.
	 *
	 * @since 2.0.0
	 * @param string $prefix _sptp_generator_layout.
	 */
	public static function section( $prefix ) {
		SPF_TEAM::createSection(
			$prefix,
			array(
				'fields' => array(
					array(
						'type'  => 'subheading',
						'image' => 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDI0LjMuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IgoJIHZpZXdCb3g9IjAgMCAxNDAuMTggMzUuNzciIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDE0MC4xOCAzNS43NzsiIHhtbDpzcGFjZT0icHJlc2VydmUiPgo8c3R5bGUgdHlwZT0idGV4dC9jc3MiPgoJLnN0MHtmaWxsOiNGRkZGRkY7fQo8L3N0eWxlPgo8Zz4KCTxnPgoJCTxnPgoJCQk8cGF0aCBjbGFzcz0ic3QwIiBkPSJNMTUuMTUsMjMuODZsLTAuMDQsMC4xNmwtMi42NCwwLjc5Yy0wLjc1LDAuMjQtMS42MiwyLjQ1LTIuMjksNS45M2MyLjI1LDEuMzUsNC44NSwyLjA2LDcuNDksMi4wNgoJCQkJYzAuMzIsMCwwLjY3LDAsMC45OS0wLjA0YzIuMjktMC4xNiw0LjU4LTAuODMsNi41NS0yLjAyYy0wLjY3LTMuNDQtMS41NC01Ljc0LTIuMjktNS45M2wtMi42NC0wLjc5bC0wLjA1LTAuMTYKCQkJCWMtMC4wNC0wLjEyLTAuMTItMC4yNC0wLjI4LTAuMzJsLTAuMzItMC4ybDAuMi0wLjI0YzAuMjQtMC4yNCwwLjM5LTAuNDcsMC40Ny0wLjYzYzAuMzItMC40NCwwLjU1LTAuOTEsMC43MS0xLjM4CgkJCQljMC4wOC0wLjIsMC4xNi0wLjQsMC4yNC0wLjU5bDAuMDQtMC4wOGwwLjA4LTAuMDRjMC4yLTAuMTYsMC4yOC0wLjM2LDAuMjgtMC41OVYxOWMwLTAuMTYtMC4wNC0wLjMyLTAuMTYtMC40N2wtMC4wNC0wLjA4di0xLjE5CgkJCQljMC0xLjc4LTEuNDYtMy4yNC0zLjIzLTMuMjRoLTEuMTRjLTEuNzgsMC0zLjIzLDEuNDYtMy4yMywzLjI0djEuMTlsLTAuMDQsMC4wOGMtMC4wOCwwLjEyLTAuMTYsMC4zMi0wLjE2LDAuNDd2MC43OQoJCQkJYzAsMC4yNCwwLjEyLDAuNDcsMC4yOCwwLjU5bDAuMDgsMC4wNGwwLjA0LDAuMDhjMC4wNCwwLjIsMC4xMiwwLjQsMC4yLDAuNTVjMC4yLDAuNDcsMC40MywwLjk1LDAuNzUsMS4zOAoJCQkJYzAuMTYsMC4yNCwwLjMyLDAuNDQsMC40NywwLjYzbDAuMiwwLjI0bC0wLjI4LDAuMkMxNS4zLDIzLjY3LDE1LjE4LDIzLjc0LDE1LjE1LDIzLjg2eiIvPgoJCQk8cGF0aCBjbGFzcz0ic3QwIiBkPSJNOC44MywyOS44OGMwLjA4LTAuNDQsMC4yLTAuODcsMC4zMi0xLjM1YzAuOTktNC4wNywyLjAxLTQuODcsMi44OC01LjE0bDEuNS0wLjQ0CgkJCQljLTAuMjQtMC40LTAuNDctMC44My0wLjYzLTEuMjdjLTAuMDQtMC4xMi0wLjEyLTAuMjgtMC4xNi0wLjRsMCwwbC0xLjYyLTAuNDdsLTAuMDQtMC4xNmMtMC4wNC0wLjEyLTAuMTItMC4yNC0wLjI4LTAuMzIKCQkJCWwtMC4yOC0wLjI0bDAuMi0wLjI0YzAuMTYtMC4yLDAuMzItMC40LDAuNDctMC42M2MwLjMyLTAuNDQsMC41NS0wLjkxLDAuNzEtMS4zNWMwLjA4LTAuMiwwLjE2LTAuNCwwLjI0LTAuNTlsMC4wNC0wLjA4CgkJCQlsMC4wOC0wLjA0YzAuMi0wLjE2LDAuMjgtMC4zNiwwLjI4LTAuNTl2LTAuNzljMC0wLjE2LTAuMDQtMC4zMi0wLjE2LTAuNDdsLTAuMDQtMC4wOHYtMS4xOGMwLTEuNzgtMS40Ni0zLjI0LTMuMjMtMy4yNEg3Ljk3CgkJCQljLTEuNzgsMC0zLjIzLDEuNDYtMy4yMywzLjI0djEuMTlMNC43LDE1LjMyYy0wLjA4LDAuMTItMC4xNiwwLjMyLTAuMTYsMC40N3YwLjc5YzAsMC4yNCwwLjEyLDAuNDcsMC4yOCwwLjU5bDAuMDgsMC4wNAoJCQkJbDAuMDQsMC4wOGMwLjA0LDAuMiwwLjEyLDAuNCwwLjI0LDAuNTVjMC4yLDAuNDcsMC40MywwLjk1LDAuNzUsMS4zOGMwLjE2LDAuMjQsMC4zMiwwLjQ0LDAuNDcsMC42M2wwLjIsMC4yNGwtMC4yOCwwLjIKCQkJCWMtMC4xNiwwLjEyLTAuMjQsMC4yLTAuMjgsMC4zMkw2LDIwLjc3bC0yLjY0LDAuNzlIMy4zMUM0LjE0LDI0Ljg5LDYuMTEsMjcuODIsOC44MywyOS44OHoiLz4KCQkJPHBhdGggY2xhc3M9InN0MCIgZD0iTTI3LjMzLDEwLjc3aC0xLjE0Yy0xLjc4LDAtMy4yMywxLjQ2LTMuMjMsMy4yNHYxLjE5bC0wLjA0LDAuMDhjLTAuMDgsMC4xNi0wLjEyLDAuMzItMC4xMiwwLjQ3djAuNzkKCQkJCWMwLDAuMjQsMC4xMiwwLjQ3LDAuMjgsMC41OWwwLjA4LDAuMDRsMC4wNCwwLjA4YzAuMDgsMC4yLDAuMTIsMC40LDAuMiwwLjU1YzAuMiwwLjQ3LDAuNDMsMC45NSwwLjc1LDEuMzgKCQkJCWMwLjEyLDAuMTYsMC4yOCwwLjQsMC40NywwLjYzbDAuMiwwLjI0bC0wLjI4LDAuMmMtMC4xNiwwLjEyLTAuMjQsMC4yLTAuMjgsMC4zMmwtMC4wNCwwLjE2TDIyLjYsMjEuMmwwLDAKCQkJCWMtMC4wNCwwLjE2LTAuMTIsMC4yOC0wLjE2LDAuNDRjLTAuMTYsMC40LTAuMzksMC44My0wLjYzLDEuMjdsMS41LDAuNDRjMC44NywwLjI0LDEuODksMS4wNywyLjg4LDUuMDYKCQkJCWMwLjEyLDAuNDcsMC4yNCwwLjk1LDAuMzIsMS40MmMwLjgzLTAuNjMsMS42Mi0xLjM1LDIuMjktMi4xNGMxLjU0LTEuNzgsMi42OC0zLjkyLDMuMjMtNi4xN2gtMC4wNGwtMi42NC0wLjc5bC0wLjA0LTAuMTYKCQkJCWMtMC4wNC0wLjEyLTAuMTItMC4yNC0wLjI4LTAuMzJsLTAuMjgtMC4xNmwwLjI0LTAuMjRjMC4xNi0wLjIsMC4zMi0wLjQsMC40Ny0wLjYzYzAuMzItMC40NCwwLjU1LTAuOTEsMC43MS0xLjM1CgkJCQljMC4wOC0wLjIsMC4xNi0wLjQsMC4yNC0wLjU5bDAuMDQtMC4wOGwwLjA4LTAuMDRjMC4yLTAuMTYsMC4yOC0wLjM2LDAuMjgtMC41OXYtMC43OWMwLTAuMTYtMC4wNC0wLjMyLTAuMTYtMC40N2wtMC4wNC0wLjA4CgkJCQl2LTEuMThDMzAuNTcsMTIuMjMsMjkuMTUsMTAuNzcsMjcuMzMsMTAuNzd6Ii8+CgkJPC9nPgoJCTxnPgoJCQk8cGF0aCBjbGFzcz0ic3QwIiBkPSJNMTcuNjksMzUuNzdDNy45NCwzNS43NywwLDI3LjgxLDAsMTguMDNTNy45MywwLjI5LDE3LjY5LDAuMjljOS43NSwwLDE3LjY5LDcuOTYsMTcuNjksMTcuNzQKCQkJCVMyNy40NCwzNS43NywxNy42OSwzNS43N3ogTTE3LjY5LDIuMkM4Ljk4LDIuMiwxLjksOS4zLDEuOSwxOC4wNGMwLDguNzMsNy4wOCwxNS44NCwxNS43OSwxNS44NHMxNS43OS03LjEsMTUuNzktMTUuODQKCQkJCVMyNi40LDIuMiwxNy42OSwyLjJ6Ii8+CgkJPC9nPgoJPC9nPgoJPGc+CgkJPHBhdGggY2xhc3M9InN0MCIgZD0iTTUyLjcxLDIyLjMybDIuMzUtMTQuNTdoMy4wMWwyLjg1LDE0LjU3bDIuMzUtMTQuNTdoMS44M2wtMy4zLDE3LjczaC0zLjU4bC0yLjM3LTEyLjEybC0yLjQyLDEyLjEyaC0zLjQyCgkJCUw0Ni42Niw5LjIzYy0wLjg1LDAuMzUtMS41LDAuODgtMS45MywxLjU4Yy0wLjQ0LDAuNzEtMC42NSwxLjU4LTAuNjUsMi42M2MwLDAuNDgsMC4wNCwwLjg3LDAuMTIsMS4xOAoJCQljMC4wOCwwLjMxLDAuMTcsMC41NywwLjI2LDAuNzdjMC4xLDAuMjEsMC4xOSwwLjM3LDAuMjcsMC40OGMwLjA5LDAuMTEsMC4xNSwwLjIsMC4xOCwwLjI2Yy0wLjk1LDAtMS43LTAuMjItMi4yNS0wLjY3CgkJCWMtMC41NS0wLjQ0LTAuODMtMS4xNC0wLjgzLTIuMDljMC0wLjgxLDAuMi0xLjU2LDAuNTktMi4yNmMwLjQtMC43LDAuOTItMS4zMSwxLjU4LTEuODJjMC42Ni0wLjUyLDEuNDEtMC45MiwyLjI1LTEuMjEKCQkJYzAuODUtMC4yOSwxLjcyLTAuNDQsMi42Mi0wLjQ0YzAuMTcsMCwwLjM0LDAsMC41MSwwLjAxczAuMzMsMC4wMiwwLjQ5LDAuMDRMNTIuNzEsMjIuMzJ6Ii8+CgkJPHBhdGggY2xhc3M9InN0MCIgZD0iTTczLjgzLDkuODd2Ny45NWMwLjQ5LTAuMDUsMC45Mi0wLjIzLDEuMjktMC41NmMwLjM3LTAuMzIsMC42OS0wLjczLDAuOTUtMS4yMwoJCQljMC4yNi0wLjQ5LDAuNDYtMS4wNCwwLjU5LTEuNjNjMC4xMy0wLjYsMC4yLTEuMTksMC4yLTEuOGMwLTAuMzgtMC4wNC0wLjgtMC4xMy0xLjI1cy0wLjI3LTAuODctMC41NS0xLjI2CgkJCUM3NS45LDkuNyw3NS41LDkuMzgsNzQuOTcsOS4xMWMtMC41My0wLjI2LTEuMjQtMC4zOS0yLjEyLTAuMzljLTAuNzEsMC0xLjM2LDAuMS0xLjk1LDAuMjlzLTEuMDksMC40OC0xLjUyLDAuODcKCQkJcy0wLjc2LDAuODktMSwxLjVzLTAuMzYsMS4zMy0wLjM2LDIuMTVjMCwwLjQzLDAuMDMsMC43OCwwLjA4LDEuMDZjMC4wNiwwLjI4LDAuMTMsMC41MSwwLjIxLDAuN2MwLjA5LDAuMTksMC4xOCwwLjM1LDAuMjcsMC40OAoJCQljMC4wOSwwLjEzLDAuMTgsMC4yNSwwLjI2LDAuMzZjLTAuOTUsMC0xLjctMC4yMi0yLjI1LTAuNjdjLTAuNTUtMC40NC0wLjgzLTEuMTQtMC44My0yLjA5YzAtMC44MSwwLjE5LTEuNTYsMC41OC0yLjI2CgkJCWMwLjM5LTAuNywwLjkyLTEuMzEsMS41OS0xLjgyYzAuNjctMC41MiwxLjQ2LTAuOTIsMi4zNS0xLjIxczEuODQtMC40NCwyLjg0LTAuNDRjMC45NiwwLDEuODQsMC4xMywyLjYzLDAuMzgKCQkJYzAuNzksMC4yNSwxLjQ3LDAuNjIsMi4wNCwxLjA4YzAuNTcsMC40NywxLjAxLDEuMDMsMS4zMiwxLjY5czAuNDYsMS4zOSwwLjQ2LDIuMmMwLDAuNjUtMC4xMiwxLjMyLTAuMzYsMnMtMC41OSwxLjMtMS4wNywxLjg0CgkJCWMtMC40NywwLjU1LTEuMDYsMS0xLjc3LDEuMzRzLTEuNTIsMC41Mi0yLjQ2LDAuNTJoLTAuMDh2Ni43OGgtMy40MlYxMC4zNUw3My44Myw5Ljg3eiIvPgoJCTxwYXRoIGNsYXNzPSJzdDAiIGQ9Ik05OC41NCwxMC43M2MtMC41MSwwLTEuMDgtMC4wNS0xLjcxLTAuMTVjLTAuNjMtMC4xLTEuMjktMC4yMy0xLjk3LTAuMzd2MTUuMjhoLTMuNDJWOS43M0g5MS4yCgkJCWMtMC43MSwwLTEuMzEsMC4wOC0xLjc4LDAuMjVzLTAuODUsMC40LTEuMTQsMC43MXMtMC40OCwwLjY5LTAuNTksMS4xNWMtMC4xMSwwLjQ2LTAuMTcsMC45OC0wLjE3LDEuNTUKCQkJYzAsMC41MiwwLjA0LDAuOTQsMC4xMSwxLjI2YzAuMDcsMC4zMiwwLjE1LDAuNTgsMC4yNSwwLjc3YzAuMDksMC4yLDAuMTksMC4zNSwwLjI4LDAuNDVjMC4wOSwwLjEsMC4xNywwLjE5LDAuMjQsMC4yNwoJCQljLTAuOTksMC0xLjc3LTAuMjMtMi4zMy0wLjdjLTAuNTYtMC40Ny0wLjg0LTEuMy0wLjg0LTIuNDljMC0wLjc1LDAuMTMtMS40NCwwLjM4LTIuMDlzMC42Ni0xLjIxLDEuMjItMS42OQoJCQljMC41Ni0wLjQ4LDEuMjktMC44NSwyLjE4LTEuMTJjMC44OS0wLjI3LDEuOTgtMC40LDMuMjgtMC40YzAuNjMsMCwxLjIsMC4wMywxLjcyLDAuMDhjMC41MSwwLjA2LDAuOTksMC4xMiwxLjQ0LDAuMTkKCQkJYzAuNDUsMC4wNywwLjksMC4xNCwxLjM0LDAuMTljMC40NCwwLjA2LDAuOTEsMC4wOCwxLjQsMC4wOGMwLjY2LDAsMS4yMi0wLjA1LDEuNjctMC4xNWMwLjQ1LTAuMSwwLjgzLTAuMjIsMS4xMy0wLjM1CgkJCWMwLjA1LDAuMzIsMC4wNywwLjYsMC4wNywwLjgzYzAsMC43MS0wLjIsMS4yNi0wLjU4LDEuNjNDMTAwLjA5LDEwLjU0LDk5LjQ0LDEwLjczLDk4LjU0LDEwLjczeiIvPgoJCTxwYXRoIGNsYXNzPSJzdDAiIGQ9Ik0xMDMuMzgsMjMuNjNjMC40OCwwLDAuODktMC4wNiwxLjI2LTAuMTljMC4zNi0wLjEzLDAuNjktMC4zLDAuOTctMC41MWMwLjI4LTAuMjEsMC41NS0wLjQ3LDAuNzgtMC43NgoJCQljMC4yNC0wLjI5LDAuNDctMC42MSwwLjY5LTAuOTRoMC44MWMtMC4yMiwwLjU1LTAuNDksMS4wOS0wLjc5LDEuNjJjLTAuMzEsMC41Mi0wLjY5LDAuOTktMS4xNCwxLjRzLTAuOTgsMC43NS0xLjU4LDEKCQkJcy0xLjMxLDAuMzgtMi4xMSwwLjM4Yy0wLjY4LDAtMS4zLTAuMS0xLjg3LTAuMzFzLTEuMDYtMC41My0xLjQ4LTAuOTZjLTAuNDItMC40NC0wLjc0LTEtMC45Ny0xLjY5cy0wLjM0LTEuNTItMC4zNC0yLjQ5CgkJCWMwLTAuNjgsMC4wNy0xLjQyLDAuMjMtMi4yYzAuMTUtMC43OSwwLjQyLTEuNTEsMC44LTIuMThjMC4zOC0wLjY3LDAuODktMS4yMiwxLjU0LTEuNjdjMC42NS0wLjQ0LDEuNDctMC42NywyLjQ3LTAuNjcKCQkJYzAuNzYsMCwxLjM0LDAuMTIsMS43NiwwLjM2YzAuNDEsMC4yNCwwLjcyLDAuNTIsMC45MSwwLjgzYzAuMiwwLjMyLDAuMzIsMC42MywwLjM2LDAuOTNzMC4wNiwwLjUyLDAuMDYsMC42NAoJCQljMCwwLjcxLTAuMTMsMS4zNC0wLjM5LDEuODhjLTAuMjYsMC41NC0wLjYxLDEtMS4wNCwxLjM3Yy0wLjQ0LDAuMzctMC45MywwLjY2LTEuNSwwLjg2Yy0wLjU2LDAuMi0xLjE0LDAuMzEtMS43MiwwLjM0CgkJCWMwLjAyLDEuMDYsMC4xOSwxLjgyLDAuNTMsMi4yN0MxMDEuOTMsMjMuNCwxMDIuNTMsMjMuNjMsMTAzLjM4LDIzLjYzeiBNMTAzLjg4LDE2LjI3YzAtMC41NS0wLjA5LTAuOTQtMC4yOC0xLjE3CgkJCWMtMC4xOS0wLjIzLTAuNC0wLjM0LTAuNjItMC4zNGMtMC4yNywwLTAuNTEsMC4xMy0wLjc0LDAuMzljLTAuMjIsMC4yNi0wLjQyLDAuNjEtMC41OCwxLjA0Yy0wLjE3LDAuNDMtMC4zLDAuOTQtMC40LDEuNTMKCQkJYy0wLjEsMC41OC0wLjE3LDEuMTktMC4yLDEuODJjMC40LTAuMDMsMC43Ny0wLjE0LDEuMTItMC4zMmMwLjM1LTAuMTgsMC42NS0wLjQyLDAuOS0wLjcxYzAuMjUtMC4yOSwwLjQ1LTAuNjMsMC41OS0xLjAxCgkJCUMxMDMuODEsMTcuMTIsMTAzLjg4LDE2LjcxLDEwMy44OCwxNi4yN3oiLz4KCQk8cGF0aCBjbGFzcz0ic3QwIiBkPSJNMTE2Ljg2LDEzLjU4djguMDljMCwwLjY3LDAuMDksMS4xMSwwLjI3LDEuMzJzMC41LDAuMzIsMC45NiwwLjMyczAuODQtMC4yLDEuMTUtMC41OQoJCQljMC4zMS0wLjQsMC40OS0wLjksMC41My0xLjVoMWMtMC4xMywwLjk3LTAuMzUsMS43NC0wLjY2LDIuMzJzLTAuNjcsMS4wMi0xLjA3LDEuMzNjLTAuMzksMC4zMS0wLjgsMC41MS0xLjIxLDAuNjEKCQkJYy0wLjQxLDAuMDktMC43OCwwLjE0LTEuMDksMC4xNGMtMC44NCwwLTEuNDgtMC4xOS0xLjkxLTAuNThjLTAuNDQtMC4zOS0wLjc0LTAuOTItMC45MS0xLjYxYy0wLjEzLDAuMjctMC4yOCwwLjU0LTAuNDUsMC44CgkJCWMtMC4xNywwLjI2LTAuMzgsMC41LTAuNjMsMC43cy0wLjUzLDAuMzctMC44NSwwLjVzLTAuNywwLjE5LTEuMTMsMC4xOWMtMC41MSwwLTEuMDEtMC4wOS0xLjUtMC4yOHMtMC45NC0wLjUtMS4zNC0wLjkzCgkJCWMtMC40LTAuNDMtMC43My0xLTAuOTctMS43Yy0wLjI1LTAuNzEtMC4zNy0xLjU3LTAuMzctMi42MWMwLTAuNzMsMC4wOC0xLjQ4LDAuMjUtMi4yNnMwLjQzLTEuNDksMC43OC0yLjE0CgkJCWMwLjM2LTAuNjUsMC44Mi0xLjE5LDEuNC0xLjYxczEuMjgtMC42MywyLjEtMC42M2MwLjE5LDAsMC40LDAuMDEsMC42MiwwLjA0YzAuMjIsMC4wMiwwLjQ0LDAuMDgsMC42NCwwLjE3CgkJCWMwLjIxLDAuMDksMC4zOSwwLjIxLDAuNTcsMC4zOGMwLjE3LDAuMTcsMC4zMSwwLjM5LDAuNCwwLjY4di0xLjE0TDExNi44NiwxMy41OEwxMTYuODYsMTMuNTh6IE0xMTMuNDQsMTUuODcKCQkJYy0wLjAyLTAuMDYtMC4wNS0wLjE1LTAuMDktMC4yNWMtMC4wNS0wLjEtMC4xMi0wLjIxLTAuMjEtMC4zMXMtMC4yMS0wLjE5LTAuMzYtMC4yNmMtMC4xNC0wLjA3LTAuMzEtMC4xMS0wLjUtMC4xMQoJCQljLTAuMzgsMC0wLjcxLDAuMTYtMC45OSwwLjQ5cy0wLjUsMC43My0wLjY2LDEuMjFjLTAuMTcsMC40OC0wLjI5LDEuMDItMC4zNywxLjU5Yy0wLjA4LDAuNTgtMC4xMiwxLjEzLTAuMTIsMS42NQoJCQljMCwwLjg0LDAuMDYsMS41LDAuMTksMS45NmMwLjEzLDAuNDcsMC4yOCwwLjgxLDAuNDYsMS4wMmMwLjE4LDAuMjEsMC4zOCwwLjM0LDAuNTgsMC4zOGMwLjIxLDAuMDQsMC4zOCwwLjA2LDAuNTIsMC4wNgoJCQljMC4xNywwLDAuMzUtMC4wNCwwLjUzLTAuMTNjMC4xOC0wLjA5LDAuMzUtMC4yMiwwLjUtMC4zOXMwLjI3LTAuMzksMC4zNy0wLjY1YzAuMDktMC4yNiwwLjE0LTAuNTcsMC4xNC0wLjkydi01LjM0SDExMy40NHoiLz4KCQk8cGF0aCBjbGFzcz0ic3QwIiBkPSJNMTMxLjQ4LDE4LjNjMC0wLjk1LTAuMTEtMS42My0wLjMzLTIuMDNjLTAuMjItMC40LTAuNTItMC42MS0wLjktMC42MWMtMC40NiwwLTAuNzksMC4yMi0xLjAxLDAuNjUKCQkJYy0wLjIxLDAuNDQtMC4zNCwwLjk1LTAuMzksMS41NHY3LjY0aC0zLjQydi04LjIxYzAtMC4xNy0wLjAyLTAuMzYtMC4wNS0wLjU1cy0wLjA4LTAuMzctMC4xNS0wLjU0Yy0wLjA3LTAuMTctMC4xNy0wLjMtMC4zMS0wLjQKCQkJYy0wLjEzLTAuMS0wLjMxLTAuMTUtMC41MS0wLjE1Yy0wLjQzLDAtMC43NiwwLjIxLTEsMC42NGMtMC4yNCwwLjQzLTAuMzgsMC45NS0wLjQzLDEuNTd2Ny42NGgtMy40MnYtMTEuOWgzLjQydjEuMjQKCQkJYzAuMzktMC41OSwwLjgzLTAuOTYsMS4yOS0xLjEyYzAuNDctMC4xNiwwLjg3LTAuMjQsMS4yMi0wLjI0YzAuNjIsMCwxLjE5LDAuMTYsMS43MywwLjQ4czAuOTUsMC44MywxLjIzLDEuNTUKCQkJYzAuMTktMC40MywwLjQtMC43NywwLjY0LTEuMDRjMC4yNC0wLjI2LDAuNDktMC40NiwwLjc1LTAuNjFjMC4yNi0wLjE0LDAuNTItMC4yNCwwLjc5LTAuMjljMC4yNi0wLjA1LDAuNTEtMC4wNywwLjc1LTAuMDcKCQkJYzAuNDUsMCwwLjg3LDAuMDcsMS4yNywwLjIxYzAuNDEsMC4xNCwwLjc2LDAuMzksMS4wNywwLjczYzAuMzEsMC4zNCwwLjU1LDAuNzksMC43MywxLjM2YzAuMTgsMC41NywwLjI2LDEuMjYsMC4yNiwyLjA4CgkJCWMwLDAuMjEsMCwwLjQ2LTAuMDEsMC43NmMtMC4wMSwwLjMtMC4wMiwwLjYyLTAuMDQsMC45NGMtMC4wMiwwLjMzLTAuMDMsMC42NC0wLjA0LDAuOTVjLTAuMDEsMC4zMS0wLjAxLDAuNTctMC4wMSwwLjc3CgkJCWMwLDAuNywwLjA5LDEuMjEsMC4yNiwxLjU0YzAuMTcsMC4zMywwLjUzLDAuNDksMS4wOCwwLjQ5YzAuMzEsMCwwLjUzLTAuMDUsMC42Ni0wLjE0YzAsMC44Ny0wLjE4LDEuNS0wLjUzLDEuODgKCQkJYy0wLjM2LDAuMzgtMC44OSwwLjU3LTEuNiwwLjU3Yy0wLjU3LDAtMS4wNS0wLjEyLTEuNDUtMC4zNGMtMC40LTAuMjMtMC43MS0wLjU1LTAuOTUtMC45NXMtMC40MS0wLjg3LTAuNTEtMS40CgkJCWMtMC4xLTAuNTMtMC4xNS0xLjExLTAuMTUtMS43M2MwLTAuMTYsMC0wLjM3LDAuMDEtMC42M2MwLjAxLTAuMjYsMC4wMS0wLjU0LDAuMDEtMC44MnMwLTAuNTYsMC4wMS0wLjgzCgkJCUMxMzEuNDgsMTguNjUsMTMxLjQ4LDE4LjQ0LDEzMS40OCwxOC4zeiIvPgoJPC9nPgo8L2c+Cjwvc3ZnPgo=',
						'after' => '<i class="fa fa-life-ring"></i> Support',
						'link'  => 'https://shapedplugin.com/support/?user=lite',
						'class' => 'sptp-admin-bg',
					),
					array(
						'id'      => 'layout_preset',
						'type'    => 'image_select',
						'class'   => 'sptp-layout-preset layout_preset',
						'title'   => __( 'Layout Preset', 'team-free' ),
						'options' => array(
							'carousel'        => array(
								'image'           => SPT_PLUGIN_ROOT . 'src/Admin/img/layout-preset/carousel.svg',
								'option_name'     => __( 'Carousel', 'team-free' ),
								'option_demo_url' => 'https://getwpteam.com/carousel/',
							),
							'grid'            => array(
								'image'           => SPT_PLUGIN_ROOT . 'src/Admin/img/layout-preset/grid.svg',
								'option_name'     => __( 'Grid', 'team-free' ),
								'option_demo_url' => 'https://getwpteam.com/grid/',
							),
							'list'            => array(
								'image'           => SPT_PLUGIN_ROOT . 'src/Admin/img/layout-preset/list.svg',
								'option_name'     => __( 'List', 'team-free' ),
								'option_demo_url' => 'https://getwpteam.com/list/',
							),
							'filter'          => array(
								'image'           => SPT_PLUGIN_ROOT . 'src/Admin/img/layout-preset/isotope.svg',
								'option_name'     => __( 'Isotope', 'team-free' ),
								'pro_only'        => true,
								'option_demo_url' => 'https://getwpteam.com/isotope/',
							),
							'mosaic'          => array(
								'image'           => SPT_PLUGIN_ROOT . 'src/Admin/img/layout-preset/mosaic.svg',
								'option_name'     => __( 'Mosaic', 'team-free' ),
								'pro_only'        => true,
								'option_demo_url' => 'https://getwpteam.com/mosaic/',
							),
							'inline'          => array(
								'image'           => SPT_PLUGIN_ROOT . 'src/Admin/img/layout-preset/inline.svg',
								'option_name'     => __( 'Inline', 'team-free' ),
								'pro_only'        => true,
								'option_demo_url' => 'https://getwpteam.com/inline/',
							),
							'table'           => array(
								'image'           => SPT_PLUGIN_ROOT . 'src/Admin/img/layout-preset/table.svg',
								'option_name'     => __( 'Table', 'team-free' ),
								'pro_only'        => true,
								'option_demo_url' => 'https://getwpteam.com/table/',
							),
							'accordion'       => array(
								'image'           => SPT_PLUGIN_ROOT . 'src/Admin/img/layout-preset/accordion.svg',
								'option_name'     => __( 'Accordion', 'team-free' ),
								'pro_only'        => true,
								'option_demo_url' => 'https://getwpteam.com/accordion/',
							),
							'thumbnail-pager' => array(
								'image'           => SPT_PLUGIN_ROOT . 'src/Admin/img/layout-preset/thumbnail_pager.svg',
								'option_name'     => __( 'Thumbs Pager', 'team-free' ),
								'pro_only'        => true,
								'option_demo_url' => 'https://getwpteam.com/thumbnails-pager/',
							),
						),
						'default' => 'carousel',
					),
					array(
						'id'         => 'carousel_mode',
						'type'       => 'image_select',
						'class'      => 'sptp-layout-preset image hide-active-sign carousel_style',
						'title'      => __( 'Carousel Style', 'team-free' ),
						'options'    => array(
							'standard'          => array(
								'image'       => SPT_PLUGIN_ROOT . 'src/Admin/img/layout-style/standard.svg',
								'option_name' => __( 'Standard', 'team-free' ),
							),
							'center'            => array(
								'image'       => SPT_PLUGIN_ROOT . 'src/Admin/img/layout-style/center.svg',
								'option_name' => __( 'Center', 'team-free' ),
								'pro_only'    => true,
							),
							'ticker'            => array(
								'image'       => SPT_PLUGIN_ROOT . 'src/Admin/img/layout-style/ticker.svg',
								'option_name' => __( 'Ticker', 'team-free' ),
								'pro_only'    => true,
							),
							'thumbnails-slider' => array(
								'image'       => SPT_PLUGIN_ROOT . 'src/Admin/img/layout-style/thumbnails.svg',
								'option_name' => __( 'Thumbnails', 'team-free' ),
								'pro_only'    => true,
							),
							'multi-rows'        => array(
								'image'       => SPT_PLUGIN_ROOT . 'src/Admin/img/layout-style/multi_rows.svg',
								'option_name' => __( 'Multi Rows', 'team-free' ),
								'pro_only'    => true,
							),
						),
						'default'    => 'standard',
						'dependency' => array( 'layout_preset', '==', 'carousel', true ),
					),
					array(
						'id'         => 'layout_mode',
						'type'       => 'image_select',
						'class'      => 'sptp-layout-preset image hide-active-sign carousel_style',
						'title'      => __( 'Grid Style', 'team-free' ),
						'inline'     => true,
						'only_pro'   => true,
						'options'    => array(
							'even'    => array(
								'image'       => SPT_PLUGIN_ROOT . 'src/Admin/img/layout-style/even.svg',
								'option_name' => __( 'Even', 'team-free' ),
							),
							'masonry' => array(
								'image'       => SPT_PLUGIN_ROOT . 'src/Admin/img/layout-style/masonry.svg',
								'option_name' => __( 'Masonry', 'team-free' ),
								'pro_only'    => true,
							),
						),
						'default'    => 'even',
						'dependency' => array( 'layout_preset', '==', 'grid', true ),
					),
					array(
						'id'         => 'style_member_content_position_list',
						'class'      => 'member_content_position_list sptp-layout-preset hide-active-sign image',
						'type'       => 'image_select',
						'title'      => __( 'List Style', 'team-free' ),
						'options'    => array(
							'left_img_right_content' => array(
								'image'       => SPT_PLUGIN_ROOT . 'src/Admin/img/layout-style/right.svg',
								'option_name' => __( 'Right Content', 'team-free' ),
							),
							'left_content_right_img' => array(
								'image'       => SPT_PLUGIN_ROOT . 'src/Admin/img/layout-style/left.svg',
								'option_name' => __( 'Left Content', 'team-free' ),
								'pro_only'    => true,
							),
						),
						'default'    => 'left_img_right_content',
						'dependency' => array( 'layout_preset', '==', 'list', true ),
					),
					array(
						'id'          => 'filter_members',
						'class'       => 'sptp_filter_members',
						'type'        => 'select',
						'title'       => __( 'Filter Members', 'team-free' ),
						'placeholder' => '',
						'options'     => array(
							'newest'   => __( 'Newest', 'team-free' ),
							'group'    => __( 'Groups (Pro)', 'team-free' ),
							'specific' => __( 'Specific (Pro)', 'team-free' ),
							'exclude'  => __( 'Exclude (Pro)', 'team-free' ),
						),
						'default'     => array( 'newest' ),
					),
					array(
						'type'    => 'notice',
						'content' => __( 'To create eye-catching team layout designs and access to advanced customizations</b>, <a href="https://getwpteam.com/pricing/?ref=1" target="_blank"><b>Upgrade to Pro!</b></a>', 'team-free' ),
					),
				),
			)
		);

	}
}
