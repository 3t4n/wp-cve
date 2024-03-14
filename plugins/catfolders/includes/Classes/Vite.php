<?php
// Adapted from https://github.com/andrefelipe/vite-php-setup/blob/master/public/helpers.php
namespace CatFolders\Classes;

class Vite {
	public static function base_path() {
		return CATF_PLUGIN_URL . 'assets/dist/';
	}

	public static function enqueueVite( string $script = 'main.tsx' ) {
		self::enqueuePreload( $script );
		self::cssTag( $script );
		self::register( $script );
		add_filter(
			'script_loader_tag',
			function ( $tag, $handle, $src ) {
				if ( str_contains( $handle, 'module/catfolders/' ) ) {
					$str  = "type='module'";
					$str .= true ? ' crossorigin' : '';
					// $tag  = str_replace( "type='text/javascript'", $str, $tag );
					$tag = '<script ' . $str . ' src="' . esc_url( $src ) . '" id="' . esc_attr( $handle ) . '-js"></script>';
				}
				return $tag;
			},
			10,
			3
		);

		add_filter(
			'script_loader_src',
			function( $src, $handle ) {
				if ( str_contains( $handle, 'module/catfolders/vite' ) && strpos( $src, '?ver=' ) ) {
					return remove_query_arg( 'ver', $src );
				}

				return $src;
			},
			10,
			2
		);

	}

	public static function enqueuePreload( $script ) {
		add_action(
			'admin_head',
			function() use ( $script ) {
				self::jsPreloadImports( $script );
			}
		);
	}

	public static function register( $entry ) {
		$url = CATF_IS_DEVELOPMENT
		? 'https://localhost:3000/' . $entry
		: self::assetUrl( $entry );

		if ( ! $url ) {
			return '';
		}

		if ( CATF_IS_DEVELOPMENT ) {
			wp_enqueue_script( 'module/catfolders/vite', 'https://localhost:3000/@vite/client', array(), CATF_VERSION, false );
		}

		wp_register_script( "module/catfolders/$entry", $url, false, true, true );
		wp_enqueue_script( "module/catfolders/$entry" );
	}

	private static function jsPreloadImports( $entry ) {
		if ( CATF_IS_DEVELOPMENT ) {
			echo '<script type="module">
			import RefreshRuntime from "https://localhost:3000/@react-refresh"
			RefreshRuntime.injectIntoGlobalHook(window)
			window.$RefreshReg$ = () => {}
			window.$RefreshSig$ = () => (type) => type
			window.__vite_plugin_react_preamble_installed__ = true
			</script>';
		} else {
			foreach ( self::importsUrls( $entry ) as $url ) {
				echo ( '<link rel="modulepreload" href="' . esc_url( $url ) . '">' );
			}
		}

	}

	private static function cssTag( string $entry ): string {
		// not needed on dev, it's inject by Vite
		if ( CATF_IS_DEVELOPMENT ) {
			return '';
		}

		$tags = '';
		foreach ( self::cssUrls( $entry ) as $key => $url ) {
			wp_register_style( "catfolders/$key", $url, array(), CATF_VERSION );
			wp_enqueue_style( "catfolders/$key", $url, array(), CATF_VERSION );
		}
		return $tags;
	}


	// Helpers to locate files

	private static function getManifest(): array {
		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$content = file_get_contents( CATF_PLUGIN_PATH . 'assets/dist/manifest.json' );

		return json_decode( $content, true );
	}

	private static function assetUrl( string $entry ): string {
		$manifest = self::getManifest();

		return isset( $manifest[ $entry ] )
		? self::base_path() . $manifest[ $entry ]['file']
		: self::base_path() . $entry;
	}

	private static function getPublicURLBase() {
		return CATF_IS_DEVELOPMENT ? '/dist/' : self::base_path();
	}

	private static function importsUrls( string $entry ): array {
		$urls     = array();
		$manifest = self::getManifest();

		if ( ! empty( $manifest[ $entry ]['imports'] ) ) {
			foreach ( $manifest[ $entry ]['imports'] as $imports ) {
				$urls[] = self::getPublicURLBase() . $manifest[ $imports ]['file'];
			}
		}
		return $urls;
	}

	private static function cssUrls( string $entry ): array {
		$urls     = array();
		$manifest = self::getManifest();

		if ( ! empty( $manifest[ $entry ]['css'] ) ) {
			foreach ( $manifest[ $entry ]['css'] as $file ) {
				$urls[ "cat_entry_$file" ] = self::getPublicURLBase() . $file;
			}
		}

		if ( ! empty( $manifest[ $entry ]['imports'] ) ) {
			foreach ( $manifest[ $entry ]['imports'] as $imports ) {
				if ( ! empty( $manifest[ $imports ]['css'] ) ) {
					foreach ( $manifest[ $imports ]['css'] as $css ) {
						$urls[ "cat_imports_$css" ] = self::getPublicURLBase() . $css;
					}
				}
			}
		}
		return $urls;
	}
}
