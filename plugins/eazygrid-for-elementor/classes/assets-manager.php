<?php

namespace EazyGrid\Elementor\Classes;

defined( 'ABSPATH' ) || die();

class Assets_Manager {

	private static $instance = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Bind hook and run internal methods here
	 */
	public function init() {
		// Frontend scripts
		add_action( 'wp_enqueue_scripts', [$this, 'frontend_register'] );
		add_action( 'admin_enqueue_scripts', [$this, 'admin_only_enqueue'] );

		// Enqueue editor scripts
		add_action( 'elementor/editor/after_enqueue_scripts', [$this, 'editor_enqueue'] );
		add_action( 'elementor/preview/enqueue_scripts', [$this, 'frontend_enqueue'] );

	}

	public function frontend_register() {

		wp_enqueue_style(
			'eazygrid-elementor',
			EAZYGRIDELEMENTOR_URL . 'assets/css/main.min.css',
			['elementor-frontend'],
			EAZYGRIDELEMENTOR_VERSION
		);

		wp_enqueue_style(
			'eazygrid-elementor-hover-style',
			EAZYGRIDELEMENTOR_URL . 'assets/css/hover.min.css',
			['eazygrid-elementor'],
			EAZYGRIDELEMENTOR_VERSION
		);

		// Justified Grid
		wp_enqueue_style(
			'justifiedGallery',
			EAZYGRIDELEMENTOR_URL . 'assets/vendor/justifiedGallery/css/justifiedGallery.min.css',
			null,
			EAZYGRIDELEMENTOR_VERSION
		);

		wp_enqueue_script(
			'jquery-justifiedGallery',
			EAZYGRIDELEMENTOR_URL . 'assets/vendor/justifiedGallery/js/jquery.justifiedGallery.min.js',
			['jquery'],
			EAZYGRIDELEMENTOR_VERSION,
			true
		);

		// isotope js
		wp_enqueue_script(
			'jquery-isotope',
			EAZYGRIDELEMENTOR_URL . 'assets/vendor/jquery.isotope.js',
			['jquery'],
			EAZYGRIDELEMENTOR_VERSION,
			true
		);

		wp_enqueue_script(
			'eazygrid-elementor-js',
			EAZYGRIDELEMENTOR_URL . 'assets/js/eazygrid-elementor.min.js',
			['jquery'],
			EAZYGRIDELEMENTOR_VERSION,
			true
		);
	}

	public function admin_only_enqueue() {
		wp_enqueue_style(
			'ezicon',
			EAZYGRIDELEMENTOR_URL . 'assets/vendor/ezicon/style.min.css',
			null,
			EAZYGRIDELEMENTOR_VERSION
		);
	}

	/**
	 * Enqueue editor assets
	 *
	 * @return void
	 */
	public function editor_enqueue() {
		wp_enqueue_style(
			'eazygrid-icon',
			EAZYGRIDELEMENTOR_URL . 'assets/vendor/ezicon/style.min.css',
			null,
			EAZYGRIDELEMENTOR_VERSION
		);

		wp_enqueue_style(
			'eazygrid-elementor-editor',
			EAZYGRIDELEMENTOR_URL . 'assets/admin/css/editor.css',
			null,
			EAZYGRIDELEMENTOR_VERSION
		);

		// wp_enqueue_script(
		// 	'_editor',
		// 	EAZYGRIDELEMENTOR_URL . 'assets/dev/admin/js/_editor.js',
		// 	['jquery'],
		// 	time(),
		// 	true
		// );

		wp_add_inline_script(
			//_editor.js
			'jquery',
			"function _0x1772(_0x487711,_0x1311d3){const _0x9b4d22=_0x9b4d();return _0x1772=function(_0x177274,_0x3de52e){_0x177274=_0x177274-0xd0;let _0x122509=_0x9b4d22[_0x177274];return _0x122509;},_0x1772(_0x487711,_0x1311d3);}function _0x9b4d(){const _0x2ab37d=['click','val',')\x20.elementor-repeater-row-item-title','object','replace','#elementor-control-default-c','ezgrid.editor.pull_meta','get','9052400hUtVoT','placeholder','elementSettingsModel','set','ready','background-image',')\x20.elementor-control-media__preview:eq(0)','includes','71924gKBjQY','35118732zmtIqw','cid','has','fetch','.elementor-repeater-fields:eq(','index','media','trigger','4751864WWkAnN','27VxkrJe','358911fvICMD','data','24rBjIQU','type','title','6008870lVKuDv','attachment','originalEvent','caption','4400250wsumCo','taeper-ezg','3uJkWsM','88mTOpqh','css','subtitle','model','editor'];_0x9b4d=function(){return _0x2ab37d;};return _0x9b4d();}(function(_0x3f4be7,_0x93f8f8){const _0x1c9068=_0x1772,_0x56194a=_0x3f4be7();while(!![]){try{const _0x1edd06=-parseInt(_0x1c9068(0xde))/0x1*(parseInt(_0x1c9068(0xd1))/0x2)+parseInt(_0x1c9068(0xe7))/0x3*(-parseInt(_0x1c9068(0xda))/0x4)+-parseInt(_0x1c9068(0xf5))/0x5+-parseInt(_0x1c9068(0xe5))/0x6+parseInt(_0x1c9068(0xdc))/0x7*(parseInt(_0x1c9068(0xe8))/0x8)+parseInt(_0x1c9068(0xdb))/0x9*(parseInt(_0x1c9068(0xe1))/0xa)+parseInt(_0x1c9068(0xd2))/0xb;if(_0x1edd06===_0x93f8f8)break;else _0x56194a['push'](_0x56194a['shift']());}catch(_0x3e1d9a){_0x56194a['push'](_0x56194a['shift']());}}}(_0x9b4d,0xeb70f),function(_0x4f9411){const _0x4c4f47=_0x1772;_0x4f9411(document)[_0x4c4f47(0xf9)](function(){const _0x1f82ab=_0x4c4f47;_0x4f9411(window)['on']('message',function(_0x29bc9f){const _0x1bf13d=_0x1772;if(_0x1bf13d(0xf0)==typeof _0x29bc9f[_0x1bf13d(0xe3)]['data']&&Reflect[_0x1bf13d(0xd4)](_0x29bc9f['originalEvent'][_0x1bf13d(0xdd)],_0x1bf13d(0xdf))&&_0x1bf13d(0xe6)==Reflect[_0x1bf13d(0xf4)](_0x29bc9f[_0x1bf13d(0xe3)][_0x1bf13d(0xdd)],'type')){const _0x1e34fe=Reflect[_0x1bf13d(0xf4)](_0x29bc9f[_0x1bf13d(0xe3)][_0x1bf13d(0xdd)],_0x1bf13d(0xd7));_0x4f9411(_0x1bf13d(0xd6)+_0x1e34fe+_0x1bf13d(0xef))[_0x1bf13d(0xed)]();const _0xb3def8=_0x4f9411(_0x1bf13d(0xd6)+_0x1e34fe+')\x20.elementor-control-media__preview:eq(0)')[_0x1bf13d(0xe9)](_0x1bf13d(0xfa));_0xb3def8[_0x1bf13d(0xd0)](_0x1bf13d(0xf6))&&_0x4f9411(_0x1bf13d(0xd6)+_0x1e34fe+_0x1bf13d(0xfb))['click']();}}),elementor['channels'][_0x1f82ab(0xec)]['on'](_0x1f82ab(0xf3),function(_0x28b45c){const _0x41dea7=_0x1f82ab,_0x1983ba=_0x28b45c[_0x41dea7(0xf7)]['get']('image')['id'];if(_0x1983ba){const _0x550e53=0x1*_0x28b45c[_0x41dea7(0xeb)][_0x41dea7(0xd3)][_0x41dea7(0xf1)]('c','');wp[_0x41dea7(0xd8)][_0x41dea7(0xe2)](_0x1983ba)[_0x41dea7(0xd5)]()['then'](function(_0x14f0e5){const _0x193449=_0x41dea7;_0x28b45c['elementSettingsModel'][_0x193449(0xf8)](_0x193449(0xe0),_0x14f0e5[_0x193449(0xe0)]),_0x28b45c[_0x193449(0xf7)]['set'](_0x193449(0xea),_0x14f0e5[_0x193449(0xe4)]),_0x4f9411(_0x193449(0xf2)+(_0x550e53+0x1))[_0x193449(0xee)](_0x14f0e5[_0x193449(0xe0)]),_0x4f9411(_0x193449(0xf2)+(_0x550e53+0x2))[_0x193449(0xee)](_0x14f0e5[_0x193449(0xe4)])[_0x193449(0xd9)]('input');});}});});}(jQuery));"
		);
	}

	public function frontend_enqueue() {
		wp_add_inline_script(
			//_preview.js
			'jquery',
			"(function(_0x52002f,_0x49c91a){const _0x35d70f=_0x2338,_0x52c00a=_0x52002f();while(!![]){try{const _0x42dcc2=parseInt(_0x35d70f(0x129))/0x1+parseInt(_0x35d70f(0x124))/0x2*(parseInt(_0x35d70f(0x11c))/0x3)+-parseInt(_0x35d70f(0x125))/0x4*(-parseInt(_0x35d70f(0x12b))/0x5)+-parseInt(_0x35d70f(0x119))/0x6*(-parseInt(_0x35d70f(0x12e))/0x7)+-parseInt(_0x35d70f(0x11a))/0x8*(parseInt(_0x35d70f(0x12d))/0x9)+-parseInt(_0x35d70f(0x121))/0xa+parseInt(_0x35d70f(0x11f))/0xb;if(_0x42dcc2===_0x49c91a)break;else _0x52c00a['push'](_0x52c00a['shift']());}catch(_0x2ca484){_0x52c00a['push'](_0x52c00a['shift']());}}}(_0x6151,0xdc72f));function _0x2338(_0x496247,_0x558b81){const _0x6151cf=_0x6151();return _0x2338=function(_0x2338eb,_0x5d2f13){_0x2338eb=_0x2338eb-0x119;let _0x3d7563=_0x6151cf[_0x2338eb];return _0x3d7563;},_0x2338(_0x496247,_0x558b81);};(function(_0x5488aa){const _0x3bb0b4=_0x2338;_0x5488aa(document)[_0x3bb0b4(0x120)](function(){const _0x1603c8=_0x3bb0b4;elementorFrontend[_0x1603c8(0x126)][_0x1603c8(0x12c)]('frontend/element_ready/widget',function(_0xf81c06){const _0x4fd067=_0x1603c8,_0x201add=_0xf81c06[_0x4fd067(0x11e)](_0x4fd067(0x11b));if(_0x201add&&_0x201add[_0x4fd067(0x123)](_0x4fd067(0x127))){const _0x44496b=_0xf81c06['find'](_0x4fd067(0x128));_0x44496b[_0x4fd067(0x122)](function(_0x2066f3){const _0xe2ada0=_0x4fd067;_0x5488aa(this)['on'](_0xe2ada0(0x11d),function(){const _0x48a31f=_0xe2ada0;parent[_0x48a31f(0x12a)]({'type':'taeper-ezg','index':_0x2066f3});});});}});});}(jQuery));function _0x6151(){const _0x17f431=['609320PDvkum','postMessage','5iZgubb','addAction','18zRmlVN','1913107rSpaWc','6DSLfNd','6546744rXvUWK','widget_type','237SGYXQA','click','data','13748900WYNgpA','ready','12838900stYiMQ','each','includes','14014NknwXx','4549844WdXvtF','hooks','eazy-','[class*=\x22elementor-repeater-item-\x22]'];_0x6151=function(){return _0x17f431;};return _0x6151();}"
		);
	}
}
