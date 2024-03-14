<?php

// класс выдран, переделан и кастрирован из плагина Table of Contents Plus.
// замена класса от камы, так как он не поддерживает вложенность без css.

if ( ! class_exists( 'YTurbo_Contents' ) ) :
	class YTurbo_Contents {

		private $options;
		private $collision_collector;  // keeps a track of used anchors for collision detecting

		function __construct() {

			$this->collision_collector = [];
			$yturbo_options = get_option('yturbo_options');

			$selectors = array();
			if ($yturbo_options['yttoch1']=='enabled'){array_push($selectors, '1');}
			if ($yturbo_options['yttoch2']=='enabled'){array_push($selectors, '2');}
			if ($yturbo_options['yttoch3']=='enabled'){array_push($selectors, '3');}
			if ($yturbo_options['yttoch4']=='enabled'){array_push($selectors, '4');}
			if ($yturbo_options['yttoch5']=='enabled'){array_push($selectors, '5');}
			if ($yturbo_options['yttoch6']=='enabled'){array_push($selectors, '6');}
			if ( ! $selectors ) array_push($selectors, '1');

			// get options
			$defaults = [  // default options
				'fragment_prefix'                    => 'i',
				'position'                           => $yturbo_options['yttocmesto'],
				'start'                              => $yturbo_options['yttocnumber'],
				'show_heading_text'                  => true,
				'heading_text'                       => $yturbo_options['yttoczag'],
				'show_heirarchy'                     => true,
				'ordered_list'                       => true,
				'lowercase'                          => true,
				'hyphenate'                          => false,
				'bullet_spacing'                     => false,
				'exclude'                            => esc_attr(stripslashes($yturbo_options['yttocexclude'])),
				'heading_levels'                     => $selectors,
			];

			$options = '';
			$this->options = wp_parse_args( $options, $defaults );

			//add_filter( 'yturbo_the_content', [ $this, 'the_content' ], 100 );  // run after shortcodes are interpretted (level 10)

		}

		function __destruct() {}

		/**
		 * Returns a clean url to be used as the destination anchor target
		 */
		private function url_anchor_target( $title ) {
			$return = false;

			if ( $title ) {
				$return = trim( strip_tags( $title ) );

				// convert accented characters to ASCII
				$return = remove_accents( $return );

				// replace newlines with spaces (eg when headings are split over multiple lines)
				$return = str_replace( [ "\r", "\n", "\n\r", "\r\n" ], ' ', $return );

				// remove &amp;
				$return = str_replace( '&amp;', '', $return );

				// remove non alphanumeric chars
				$return = preg_replace( '/[^a-zA-Z0-9 \-_]*/', '', $return );

				// convert spaces to _
				$return = str_replace(
					[ '  ', ' ' ],
					'_',
					$return
				);

				// remove trailing - and _
				$return = rtrim( $return, '-_' );

				// lowercase everything?
				if ( $this->options['lowercase'] ) {
					$return = strtolower( $return );
				}

				// if blank, then prepend with the fragment prefix
				// blank anchors normally appear on sites that don't use the latin charset
				if ( ! $return ) {
					$return = ( $this->options['fragment_prefix'] ) ? $this->options['fragment_prefix'] : '_';
				}

				// hyphenate?
				if ( $this->options['hyphenate'] ) {
					$return = str_replace( '_', '-', $return );
					$return = str_replace( '--', '-', $return );
				}
			}

			if ( array_key_exists( $return, $this->collision_collector ) ) {
				$this->collision_collector[ $return ]++;
				$return .= '-' . $this->collision_collector[ $return ];
			} else {
				$this->collision_collector[ $return ] = 1;
			}

			return apply_filters( 'toc_url_anchor_target', $return );
		}


		private function build_hierarchy( &$matches ) {
			$current_depth      = 100;  // headings can't be larger than h6 but 100 as a default to be sure
			$html               = '';
			$numbered_items     = [];
			$numbered_items_min = null;

			// reset the internal collision collection
			$this->collision_collector = [];

			// find the minimum heading to establish our baseline
			for ( $i = 0; $i < count( $matches ); $i++ ) {
				if ( $current_depth > $matches[ $i ][2] ) {
					$current_depth = (int) $matches[ $i ][2];
				}
			}

			$numbered_items[ $current_depth ] = 0;
			$numbered_items_min               = $current_depth;

			for ( $i = 0; $i < count( $matches ); $i++ ) {

				if ( $current_depth === (int) $matches[ $i ][2] ) {
					$html .= '<li>';
				}

				// start lists
				if ( $current_depth !== (int) $matches[ $i ][2] ) {
					for ( $current_depth; $current_depth < (int) $matches[ $i ][2]; $current_depth++ ) {
						$numbered_items[ $current_depth + 1 ] = 0;
						$html                                .= '<ul><li>';
					}
				}

				// list item
				if ( in_array( $matches[ $i ][2], $this->options['heading_levels'] ) ) {
					$html .= '<a href="'.get_the_permalink().'#' . $this->url_anchor_target( $matches[ $i ][0] ) . '">';
					if ( $this->options['ordered_list'] ) {
						// attach leading numbers when lower in hierarchy
						$html .= '<span class="toc_number toc_depth_' . ( $current_depth - $numbered_items_min + 1 ) . '">';
						for ( $j = $numbered_items_min; $j < $current_depth; $j++ ) {
							$number = ( $numbered_items[ $j ] ) ? $numbered_items[ $j ] : 0;
							$html  .= $number . '.';
						}

						$html .= ( $numbered_items[ $current_depth ] + 1 ) . '</span> ';
						$numbered_items[ $current_depth ]++;
					}
					$html .= strip_tags( $matches[ $i ][0] ) . '</a>';
				}

				// end lists
				if ( count( $matches ) - 1 !== $i ) {
					if ( $current_depth > (int) $matches[ $i + 1 ][2] ) {
						for ( $current_depth; $current_depth > (int) $matches[ $i + 1 ][2]; $current_depth-- ) {
							$html                            .= '</li></ul>';
							$numbered_items[ $current_depth ] = 0;
						}
					}

					if ( (int) @$matches[ $i + 1 ][2] === $current_depth ) {
						$html .= '</li>';
					}
				} else {
					// this is the last item, make sure we close off all tags
					for ( $current_depth; $current_depth >= $numbered_items_min; $current_depth-- ) {
						$html .= '</li>';
						if ( $current_depth !== $numbered_items_min ) {
							$html .= '</ul>';
						}
					}
				}
			}

			return $html;
		}


		/**
		 * Returns a string with all items from the $find array replaced with their matching
		 * items in the $replace array.  This does a one to one replacement (rather than
		 * globally).
		 *
		 * This function is multibyte safe.
		 *
		 * $find and $replace are arrays, $string is the haystack.  All variables are
		 * passed by reference.
		 */
		private function mb_find_replace( &$find = false, &$replace = false, &$string = '' ) {
			if ( is_array( $find ) && is_array( $replace ) && $string ) {
				// check if multibyte strings are supported
				if ( function_exists( 'mb_strpos' ) ) {
					for ( $i = 0; $i < count( $find ); $i++ ) {
						$string =
							mb_substr( $string, 0, mb_strpos( $string, $find[ $i ] ) ) . // everything before $find
							$replace[ $i ] . // its replacement
							mb_substr( $string, mb_strpos( $string, $find[ $i ] ) + mb_strlen( $find[ $i ] ) ); // everything after $find
					}
				} else {
					for ( $i = 0; $i < count( $find ); $i++ ) {
						$string = substr_replace(
							$string,
							$replace[ $i ],
							strpos( $string, $find[ $i ] ),
							strlen( $find[ $i ] )
						);
					}
				}
			}

			return $string;
		}


		/**
		 * This function extracts headings from the html formatted $content.  It will pull out
		 * only the required headings as specified in the options.  For all qualifying headings,
		 * this function populates the $find and $replace arrays (both passed by reference)
		 * with what to search and replace with.
		 *
		 * Returns a html formatted string of list items for each qualifying heading.  This
		 * is everything between and NOT including <ul> and </ul>
		 */
		public function extract_headings( &$find, &$replace, $content = '' ) {
			$matches = [];
			$anchor  = '';
			$items   = false;

			// reset the internal collision collection as the_content may have been triggered elsewhere
			// eg by themes or other plugins that need to read in content such as metadata fields in
			// the head html tag, or to provide descriptions to twitter/facebook
			$this->collision_collector = [];

			if ( is_array( $find ) && is_array( $replace ) && $content ) {
				// get all headings
				// the html spec allows for a maximum of 6 heading depths
				if ( preg_match_all( '/(<h([1-6]{1})[^>]*>).*<\/h\2>/msuU', $content, $matches, PREG_SET_ORDER ) ) {

					// remove undesired headings (if any) as defined by heading_levels
					if ( count( $this->options['heading_levels'] ) != 6 ) {
						$new_matches = [];
						for ( $i = 0; $i < count( $matches ); $i++ ) {
							if ( in_array( $matches[ $i ][2], $this->options['heading_levels'] ) ) {
								$new_matches[] = $matches[ $i ];
							}
						}
						$matches = $new_matches;
					}

					// remove specific headings if provided via the 'exclude' property
					if ( $this->options['exclude'] ) {
						$excluded_headings = explode( '|', $this->options['exclude'] );
						if ( count( $excluded_headings ) > 0 ) {
							for ( $j = 0; $j < count( $excluded_headings ); $j++ ) {
								// escape some regular expression characters
								// others: http://www.php.net/manual/en/regexp.reference.meta.php
								$excluded_headings[ $j ] = str_replace(
									[ '*' ],
									[ '.*' ],
									trim( $excluded_headings[ $j ] )
								);
							}

							$new_matches = [];
							for ( $i = 0; $i < count( $matches ); $i++ ) {
								$found = false;
								for ( $j = 0; $j < count( $excluded_headings ); $j++ ) {
									if ( @preg_match( '/^' . $excluded_headings[ $j ] . '$/imU', strip_tags( $matches[ $i ][0] ) ) ) {
										$found = true;
										break;
									}
								}
								if ( ! $found ) {
									$new_matches[] = $matches[ $i ];
								}
							}
							if ( count( $matches ) !== count( $new_matches ) ) {
								$matches = $new_matches;
							}
						}
					}

					// remove empty headings
					$new_matches = [];
					for ( $i = 0; $i < count( $matches ); $i++ ) {
						if ( trim( strip_tags( $matches[ $i ][0] ) ) != false ) {
							$new_matches[] = $matches[ $i ];
						}
					}
					if ( count( $matches ) !== count( $new_matches ) ) {
						$matches = $new_matches;
					}

					// check minimum number of headings
					if ( count( $matches ) >= $this->options['start'] ) {

						for ( $i = 0; $i < count( $matches ); $i++ ) {
							// get anchor and add to find and replace arrays
							$anchor    = $this->url_anchor_target( $matches[ $i ][0] );
							$find[]    = $matches[ $i ][0];
							$replace[] = str_replace(
								[
									$matches[ $i ][1], // start of heading
									'</h' . $matches[ $i ][2] . '>', // end of heading
								],
								[
									$matches[ $i ][1] . '<test id="' . $anchor . '">',
									'</test></h' . $matches[ $i ][2] . '>',
								],
								$matches[ $i ][0]
							);
							//по умолчанию toc вставляет span внутрь заголовков, а яндекс этого не понимает
							$pattern = "/<h(.*?)><test id=\"(.*?)\">(.*?)<\/test>/i";
							$replacement = '<h$1 id="$2">$3';
							$replace[$i] = preg_replace($pattern, $replacement, $replace[$i]);

							// assemble flat list
							if ( ! $this->options['show_heirarchy'] ) {
								$items .= '<li><a href="'.get_the_permalink().'#' . $anchor . '">';
								if ( $this->options['ordered_list'] ) {
									$items .= count( $replace ) . ' ';
								}
								$items .= strip_tags( $matches[ $i ][0] ) . '</a></li>';
							}
						}

						// build a hierarchical toc?
						// we could have tested for $items but that var can be quite large in some cases
						if ( $this->options['show_heirarchy'] ) {
							$items = $this->build_hierarchy( $matches );
						}
					}
				}
			}

			return $items;
		}


		function the_content( $content ) {
			global $post;
			$yturbo_options = get_option('yturbo_options');
			$items               = '';
			$find                = [];
			$replace             = [];

				$items = $this->extract_headings( $find, $replace, $content );

				if ( $items ) {

						// add container, toc title and list items
						$html = '<div id="toc_container">';
						if ( $this->options['show_heading_text'] && $this->options['heading_text'] ) {
							$toc_title = htmlentities( stripslashes($this->options['heading_text']), ENT_COMPAT, 'UTF-8' );
							$html .= '<h3 class="toc_title">' . $toc_title . '</h3>';
						}
						$html .= '<ul class="toc_list">' . $items . '</ul></div>';
						$html = PHP_EOL . wpautop( $html ) . PHP_EOL;

							if ( count( $find ) > 0 ) {
								switch ( $yturbo_options['yttocmesto'] ) {
									case 'В начале записи':
										$content = $html . $this->mb_find_replace( $find, $replace, $content );
										break;

									case 'В конце записи':
										$content = $this->mb_find_replace( $find, $replace, $content ) . $html;
										break;

									case 'После первого заголовка':
										$replace[0] = $replace[0] . $html;
										$content    = $this->mb_find_replace( $find, $replace, $content );
										break;

									case 'Перед первым заголовком':
									default:
										$replace[0] = $html . $replace[0];
										$content    = $this->mb_find_replace( $find, $replace, $content );
								}
							}

				}

			return $content;
		}

	}
endif;
