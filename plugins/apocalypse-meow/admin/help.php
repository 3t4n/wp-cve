<?php
/**
 * Admin: Help
 *
 * @package Apocalypse Meow
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

/**
 * Do not execute this file directly.
 */
if (! \defined('ABSPATH')) {
	exit;
}

use blobfolio\wp\meow\admin;
use blobfolio\wp\meow\vendor\common;
use blobfolio\wp\meow\vendor\md;



// Our help files.
$Parsedown = new md\Parsedown();
$help = array(
	'Constants'=>\MEOW_PLUGIN_DIR . 'skel/help/constant.md',
	'Hooks'=>\MEOW_PLUGIN_DIR . 'skel/help/hook.md',
	'WP-CLI'=>\MEOW_PLUGIN_DIR . 'skel/help/cli.md',
);
$links = array();
$locale = \get_locale();
foreach ($help as $k=>$v) {
	$translated = common\mb::substr($v, 0, -3) . "-$locale.md";

	// There might be a translation.
	if ($locale && @\file_exists($translated)) {
		$v = $translated;
		$help[$k] = $help[$k];
	}
	// Otherwise English.
	elseif (! \file_exists($v)) {
		unset($help[$k]);
		continue;
	}

	try {
		$help[$k] = $Parsedown->text(\file_get_contents($v));

		// Ready for PrismJS.
		$help[$k] = \preg_replace_callback(
			'/<pre>(.*)<\/pre>/sU',
			function($match) {
				$classes = array();

				// Is this PHP?
				if (
					(false !== \strpos($match[1], '<?php')) ||
					(false !== \strpos($match[1], '&lt;?php'))
				) {
					$classes[] = 'language-php';
				}
				else {
					$classes[] = 'language-handlebars';
				}
				$classes[] = 'line-numbers';

				return '<pre class="' . \implode(' ', $classes) . "\">{$match[1]}</pre>";
			},
			$help[$k]
		);

		// Now generate the corresponding links.
		$help[$k] = $help[$k] = \preg_replace_callback(
			'/<(h2|h3)>(.*)<\/\\1>/sU',
			function($match) use(&$links, $k) {

				if (! isset($links[$k])) {
					$links[$k] = array();
				}

				$id = \sanitize_title($k) . '--' . \sanitize_title(\str_replace('/', '_', $match[2]));
				$links[$k][] = array(
					'name'=>$match[2],
					'id'=>"#$id",
					'child'=>('h3' === $match[1] && \count($links)),
				);

				return "<{$match[1]} id=\"$id\">{$match[2]}</{$match[1]}>";
			},
			$help[$k]
		);

		// Also, build up links.
	} catch (Throwable $e) {
		unset($help[$k]);
	}
}
if (! \count($help)) {
	\wp_die(\__('The reference files are missing.', 'apocalypse-meow'), 'Error');
}
$first = \array_keys($help);
$first = $first[0];



// JSON doesn't appreciate broken UTF.
admin::json_meowdata(array(
	'help'=>common\format::array_to_indexed($help),
	'links'=>$links,
	'showingHelp'=>$first,
));
?>
<div class="wrap" id="vue-help" v-cloak>
	<h1>Apocalypse Meow: <?php echo \__('Help', 'apocalypse-meow'); ?></h1>

	<div id="poststuff">
		<div id="post-body" class="metabox-holder meow-columns one-two fixed">

			<!-- Reference -->
			<div class="postbox-container two">

				<!-- navigation for relative stats -->
				<h3 class="nav-tab-wrapper">
					<a v-for="item in help" v-on:click.prevent="showingHelp = item.key" style="cursor:pointer" class="nav-tab" v-bind:class="{'nav-tab-active' : showingHelp === item.key}">{{item.key}}</a>
				</h3>

				<!-- ==============================================
				Reference
				=============================================== -->
				<div v-for="item in help" class="postbox" v-show="showingHelp === item.key">
					<h3 class="hndle">{{item.key}}</h3>
					<div class="inside meow-reference" v-html="item.value"></div>
				</div>

			</div><!--.postbox-container-->

			<!-- Links -->
			<div class="postbox-container one">

				<!-- ==============================================
				LINKS
				=============================================== -->
				<div class="postbox">
					<h3 class="hndle"><?php echo \__('Quick Links', 'apocalypse-meow'); ?></h3>
					<div class="inside">
						<ul class="meow-reference--links">
							<li class="meow-reference--link" v-for="item in links[showingHelp]" v-bind:class="{'child' : item.child}"><a v-bind:href="item.id" style="cursor:pointer">{{item.name}}</a></li>
						</ul>
					</div>
				</div>
			</div><!--.postbox-container-->

		</div><!--#post-body-->
	</div><!--#poststuff-->

</div><!--.wrap-->
