<?php namespace MSMoMDP\Std\DataFlow;

use MSMoMDP\Std\Core\Arr;
use MSMoMDP\Std\Core\Special;
use MSMoMDP\Std\Html\Html;
use MSMoMDP\Std\Core\Color;
use MSMoMDP\Std\Core\Path;
use MSMoMDP\Std\Components\Tooltip;

class Renderer {

	//[0] action:  hover content convert__style_prop:[style-property]
	//[1] content: img h2 p
	//[2] link: file-name url
	public static function get_html_country_table_cell_container( $rowName, $cellData, $convertArgs, $pseudoCodeDelimiter = '_', $pseudoCodeArgsDelimiter = '|', $pseudoCodeArgsKeyValSeparator = ':' ) {
		if ( is_array( $cellData ) ) {
			if ( ! isset( $seasonColors ) ) {
				$seasonColors = self::default_color_options();
			}
			$htmlContent = '';
			foreach ( $cellData as $cellItem ) {
				$itemPseudoCommanders = array();
				foreach ( $cellItem as $pseudoCodeKey => $value ) {
					$action = PseudoCommand::get_pseudo_action( $pseudoCodeKey );
					if ( isset( $action ) ) {
						$itemPseudoCommanders[ $action ][] = new PseudoCommand(
							$pseudoCodeKey,
							$value,
							$pseudoCodeDelimiter,
							$pseudoCodeArgsDelimiter,
							$pseudoCodeArgsKeyValSeparator
						);
					}
				}
				$htmlContent .= self::get_html_country_table_cell_container_sub( $rowName, $itemPseudoCommanders, $convertArgs );
			}
		}
		if ( ! empty( $htmlContent ) ) {
			return $htmlContent;
		} else {
			return '';
		}
	}


	private static function default_color_options() {
		$defaults = array(
			'High'     => '#26c485',
			'Shoulder' => '#e9b76a',
			'Low'      => '#c7efbf',
			'Unlisted' => '#cccccc',
			'Selected' => '#965495',
		);
		return $defaults;
	}

	private static function get_html_country_table_cell_container_sub_tooltip( $hoverCommanders, &$addAttrsAsoc ) {
		$imgComanders = array_filter(
			$hoverCommanders,
			function( $commander ) {
				return $commander->get_content() == 'img';
			}
		);
		$hComanders   = array_filter(
			$hoverCommanders,
			function( $commander ) {
				return substr( $commander->get_content(), 0, 1 ) == 'h';
			}
		);
		$pComanders   = array_filter(
			$hoverCommanders,
			function( $commander ) {
				return $commander->get_content() == 'p';
			}
		);

		$imgComander = reset( $imgComanders ); //TODO multi content
		$hComander   = reset( $hComanders );
		$pComander   = reset( $pComanders );

		$title = $hComander ? $hComander->get_data() : null;
		Tooltip::insert_data_attr_to_ref(
			$addAttrsAsoc,
			$title ?? '',
			$pComander ? $pComander->get_data() : '',
			$imgComander ? $imgComander->get_data() : ''
		);
		return $title;
	}


	private static function get_html_country_table_cell_container_sub( $rowName, $itemPseudoCommanders, $convert ) : ?string {
		$renderEn = false;
		// TODO multi content
		$contentCommander = Arr::get( $itemPseudoCommanders, 'content.0' );
		$convertCommander = Arr::get( $itemPseudoCommanders, 'convert.0' );
		$styleCommander   = Arr::get( $itemPseudoCommanders, 'style.0' );
		$hoverCommanders  = Arr::as_array( Arr::get( $itemPseudoCommanders, 'hover' ) );

		$content      = array();
		$htmlClasses  = array( 'div-table-cell-sub', 'js--tooltip-ref' );
		$htmlStyle    = array();
		$addAttrsAsoc = array();

		$imgAlt = null;
		// hover
		if ( count( $hoverCommanders ) > 0 ) {
			$imgAlt = self::get_html_country_table_cell_container_sub_tooltip( $hoverCommanders, $addAttrsAsoc );
		}
		// convertors
		if ( isset( $convertCommander ) ) { // join color strip to each cell top
			$data = $convertCommander->get_data();
			if ( is_string( $convert['fce'] ) || is_array( $convert['fce'] ) ) {
				$cvtRes = call_user_func( $convert['fce'], $data, $convert['args'] );
				switch ( $convertCommander->get_link() ) {
					case 'style':
						$styleProp = $convertCommander->get_argVal( 'prop' );
						if ( $styleProp ) {
							$htmlStyle[ $styleProp ] = $cvtRes;
							$renderEn                = true;
						}
						break;
				}
			}
		}
		// convertors
		if ( isset( $styleCommander ) ) { // join color strip to each cell top
			$data          = $styleCommander->get_data();
			$stylePropName = $styleCommander->get_content();
			if ( $stylePropName && $data ) {
				$htmlStyle[ $stylePropName ] = $data;
			}
		}
		// content
		if ( isset( $contentCommander ) ) {
			$data              = $contentCommander->get_data();
			$subContentType    = $contentCommander->get_content();
			$subContentClasses = array( 'div-table-cell-sub-content' );
			$elementName       = '';
			$attributes        = array();
			$subContent        = null;
			$closingEl         = true;
			if ( substr( $subContentType, 0, 1 ) == 'h' ) {
				$elementName = $subContentType;
				$subContent  = $data;
			} else {
				switch ( $subContentType ) {
					case 'img':
						$closingEl       = false;
						$elementName     = 'img';
						$contentLinkType = $contentCommander->get_link();
						$imgSrc          = null;
						switch ( $contentLinkType ) {
							case 'file-name':
								if ( ! empty( $data ) ) {
									$attributes['src'] = get_site_url( null, 'wp-content/uploads/' . $data );
									$attributes['alt'] = $imgAlt ?? pathinfo( $data, PATHINFO_FILENAME );
									if ( ! file_exists( $attributes['src'] ) ) {
										// $imgSrc = get_site_url(null, 'wp-content/uploads/' . $rowName. '_Fallback.png');
									}
								} else {
									$attributes['src'] = get_site_url( null, 'wp-content/uploads/' . $rowName . '_Fallback.png' );
									$attributes['alt'] = $imgAlt ?? $rowName;
								}
								break; // TODO address by param - no wp dependency
							case 'url':
								$attributes['src'] = $data;
								break;
							default:
								$elementName = '';
								break;
						}
						if ( empty( $attributes['src'] ) ) {
							$elementName = '';
						}
						break;
					case 'p':
					case 'text':
						$elementName = 'p';
						$subContent  = $data;
						break;
					case 'a':
						$elementName = 'a';
						$link        = '#';
						$base        = $contentCommander->get_argVal( 'base' );
						$linkRel     = $contentCommander->get_link();
						if ( $linkRel ) {
							$link = get_site_url( null, Path::combine_unix( $base, $linkRel ) );
						}
						$attributes['href'] = $link;
						$subContent         = $data;
						break;
				}
			}
			if ( ! empty( $elementName ) ) {
				$content['content'] = Html::get_str( $elementName, $subContentClasses, null, $subContent, $attributes, $closingEl );
				$renderEn           = true;
			}
		}

		if ( $renderEn ) {
			return  Html::get_str( 'div', $htmlClasses, $htmlStyle, $content, $addAttrsAsoc );
		} else {
			return '';
		}
	}

	private static function echo_input( $key, $value ) {
		echo $key . ':' . json_encode( $value );
	}

}
