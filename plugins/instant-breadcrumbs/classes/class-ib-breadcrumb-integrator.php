<?php
require_once( dirname( __FILE__ ) . '/class-ib-dom-walker.php' );

class IB_Breadcrumb_Integrator extends IB_DOM_Walker {
	private $pass;
	private $dom;
	private $crumbs;
	private $level;
	private $matches;
	private $matchcount;
	private $limit;
	private $ulseen;

	private function pass( $pass ) {
		$this->pass       = $pass;
		$this->level      = 0;
		$this->matchcount = 0;
		$this->walk( $this->dom );
	}

	public function integrate( $dom, $crumbs, $padding ) {
		$this->dom    = $dom;
		$this->crumbs = $crumbs;
		$this->ulseen = null;

		// pass 1: identify existing a's that match crumbs
		$this->matches = array();
		$this->pass( 1 );

		// prepass: determine which existing a's can be modified
		$this->limit = count( $this->crumbs ) - 1;
		for ( $i = $this->matchcount - 1; $i >= 0; $i-- ) {
			if ( $this->matches[ $i ]['match'] == $this->limit ) {
				$this->matches[ $i ] = array( 'match' => $this->limit--, 'update' => TRUE );
			}
		}

		// if we didn't see a ul on the first pass, then there must have been an empty page list
		// and we need a ul on the containing div
		if ( $this->ulseen === null ) {
			$div = $dom->children[0];
			$this->ulseen = (object) array( 'type' => 'element', 'name' => 'ul', 'attr' => array(), 'children' => array() );
			$div->children = array( $this->ulseen );
		}

		// pass 2: modify existing a's, add new items
		$this->pass( 2 );
		
		// if padding is not empty, add it between all the li's
		if ( ! empty( $padding ) ) {
			$newkids = array();
			$first   = true;
			foreach ( $this->ulseen->children as $child ) {
				if ( ! $first ) {
					$newkids[] = (object) array( 'type' => 'text', 'value' => $padding );
				}
				$newkids[] = $child;
				$first     = false;
			}
			$this->ulseen->children = $newkids;
		}
		return $dom;
	}

	protected function on_open( $item, $parent, &$index ) {
		if ( $item->type == 'element' ) {
			switch ( $item->name ) {
				case 'ul':
					$this->level++;
					if ( $this->level == 1 ) {
						$this->ulseen = $item;
					}
					break;
				case 'a':
					break;
				default:
					break;
			}
		}
	}

	private function get_match_index( $url ) {
		for ( $i = 0; $i < count( $this->crumbs ); $i++ ) {
			if ( trailingslashit( esc_url( $this->crumbs[ $i ]['url'] ) ) == trailingslashit( $url ) ) return $i;
		}
		return -1;
	}

	private function update_menu( $a, $parent, $index, $which ) {
		// unless it's the last link, create a span
		// add rel and property to the a
		// add ib-crumb to the li
		// add ib-lastcrumb to the li for the last link
		
		$last = ( $which == count( $this->crumbs ) - 1 );
		$li   = $this->ancestor_node( 'li' );

		$this->add_class( $li, 'ib-crumb' );
		$this->add_class( $li, 'ib-edited' );
		if ( $last ) {
			$this->add_class( $li, 'ib-lastcrumb' );
		} else {
			$this->add_attribute( $a, 'rel', 'ib:url' );
			$this->add_attribute( $a, 'property', 'ib:title' );
			$span = (object) array( 'type' => 'element', 'name' => 'span', 'attr' => array( 'typeof' => 'ib:Breadcrumb' ), 'children' => array( $a ) );
			$parent->children[ $index ] = $span;
		}
	}

	private function insert_crumbs( $ul ) {
		// unless it's the last link, create a span
		// add rel and property to the a
		// add ib-crumb to the li
		// add ib-lastcrumb to the li for the last link
		// possibly a lot of other classes too. li (span) a - at least menu-item and current-menu-item

		for ( $which = $this->limit; $which >= 0; $which-- ) {
			$crumb = $this->crumbs[ $which ];
			$last  = ($which == count( $this->crumbs ) - 1 );
			// for 1.2. Do not escape the text a second time. It may contain HTML tags.
			$link  = (object) array( 'type' => 'text', 'value' => $crumb['text'] );
			$a     = (object) array( 'type' => 'element', 'name' => 'a', 'attr' => array( 'href' => esc_url( $crumb['url'] ) ), 'children' => array( $link ) );
			$span  = $last ? null : (object) array( 'type' => 'element', 'name' => 'span', 'attr' => array( 'typeof' => 'ib:Breadcrumb' ), 'children' => array( $a ) );
			$li = (object) array( 'type' => 'element', 'name' => 'li', 'attr' => array( 'class' => 'ib-crumb ib-added menu-item' ), 'children' => array( $last ? $a : $span ) );

			if ( $last ) {
				$this->add_class( $li, 'ib-lastcrumb' );
				$this->add_class( $li, 'current-menu-item' );
			} else {
				$this->add_attribute( $a, 'rel', 'ib:url' );
				$this->add_attribute( $a, 'property', 'ib:title' );
			}
			// add extra classes if they exist
			if ( isset( $crumb['xclass'] ) && is_array( $crumb['xclass'] ) ) {
				foreach ( $crumb['xclass'] as $xclass ) {
					$this->add_class( $li, $xclass );
				}
			}
			array_unshift( $ul->children, $li );
		}
	}
	
	protected function on_close( $item, $parent, &$index ) {
		if ( $item->type == 'element' ) {
			switch ( $item->name ) {
				case 'ul':
					if ( $this->level == 1 && $this->pass == 2 ) {
						$this->insert_crumbs( $item );
						return TRUE;	// finished
					}
					$this->level--;
					break;
				case 'a':
					if ( $this->level == 1 ) {
						$matchindex = $this->get_match_index( isset($item->attr['href']) ? $item->attr['href'] : '' );
						if ( $matchindex >= 0 ) {
							switch ( $this->pass ) {
								case 1:
									$this->matches[] = array( 'match' => $matchindex, 'update' => FALSE );
									break;
								case 2:
									$matchdata = $this->matches[ $this->matchcount ];
									if ( $matchdata['update'] ) {
										$this->update_menu( $item, $parent, $index, $matchdata['match'] );
									}
									break;
							}
							$this->matchcount++;
						}
					}
					break;
				case 'span':
					if ( empty( $item->attr ) && empty( $item->children ) ) {
						$parent->children[ $index ] = null;
					}
				default:
					break;
			}
		}
	}
}
