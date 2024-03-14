<?php

namespace FloatingButton\Dashboard;

defined( 'ABSPATH' ) || exit;

// Exit if accessed directly.
use FloatingButton\Optimization\Obfuscator;
use FloatingButton\Optimization\CSSMinifier;
use FloatingButton\WOW_Plugin;

class FolderManager {

	public static function create(): void {
		$basedir = self::path_upload_dir();

		if ( ! file_exists( $basedir ) ) {
			wp_mkdir_p( $basedir );
		}
	}

	public static function path_upload_dir(): string {
		$upload = wp_upload_dir();

		return $upload['basedir'] . '/' . WOW_Plugin::FOLDER . '/';
	}

	public static function path_upload_url(): string {
		$upload = wp_upload_dir();

		return $upload['baseurl'] . '/' . WOW_Plugin::FOLDER . '/';
	}

	public static function get_style_url( $result ): string {
		$path_style = self::path_upload_dir() . 'style-' . $result->id . '.css';
		$param      = ! empty( $data['param'] ) ? maybe_unserialize( $data['param'] ) : [];
		$css        = $result->css_code;


		if ( ! empty( $param['minified_css'] ) ) {
			$css = CSSMinifier::minify( $css );
		}

		if ( ! file_exists( $path_style ) ) {
			file_put_contents( $path_style, $css );
		}

		return self::path_upload_url() . 'style-' . $result->id . '.css';
	}

	public static function get_js_url( $result ): string {

		$path_js = self::path_upload_dir() . 'script-' . $result->id . '.js';

		if ( ! file_exists( $path_js ) ) {
			file_put_contents( $path_js, $result->js_code );
		}

		return self::path_upload_url() . 'script-' . $result->id . '.js';
	}

	public static function update_style( $data, $id ) {
		$path_style = self::path_upload_dir() . 'style-' . $id . '.css';
		$css        = $data['css_code'];
		$param      = ! empty( $data['param'] ) ? maybe_unserialize( $data['param'] ) : [];

		if ( ! empty( $param['minified_css'] ) ) {
			$css = CSSMinifier::minify( $css );
		}

		return file_put_contents( $path_style, $css );
	}

	public static function update_script( $data, $id ) {
		if ( ! is_numeric( $id ) ) {
			return false;
		}

		$path_js = self::path_upload_dir() . 'script-' . $id . '.js';

		$param = ! empty( $data['param'] ) ? maybe_unserialize( $data['param'] ) : [];

		$js_code = wp_specialchars_decode( $data['js_code'], ENT_QUOTES );

		$minified = ! empty( $param['minified_js'] ) ? $param['minified_js'] : 'obfuscate';

		if ( $minified === 'obfuscate' ) {
			$packer  = new Obfuscator( $js_code, 'Normal', true, false );
			$js_code = $packer->pack();
		}
		if ( $minified === 'minify' ) {
			$packer  = new Obfuscator( $js_code, 'None', true, false );
			$js_code = $packer->pack();
		}


		return file_put_contents( $path_js, $js_code );
	}

	public static function update_files( $data, $id ): void {
		if ( ! empty( $data['css_code'] ) ) {
			self::update_style( $data, $id );
		}
		if ( ! empty( $data['js_code'] ) ) {
			self::update_script( $data, $id );
		}
	}
}