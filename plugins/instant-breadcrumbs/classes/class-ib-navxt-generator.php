<?php
require_once( dirname( __FILE__ ) . '/class-ib-fragment-dom.php' );
require_once( dirname( __FILE__ ) . '/class-ib-dom-walker.php' );

class IB_Navxt_Generator extends IB_DOM_Walker {
	private $currentUrl;
	private $crumbs;
	private $lastLink;
	private $freeText;
	private $unlinked;

	public function is_supported() {
		return function_exists( 'bcn_display' );
	}
	
	public function init() {
	}

	private function coalesce( $parser, $dom ) {
		$out = (object) array( 'name' => '', 'type' => 'fragment', 'children' => array() );
		$textbuffer = (object) array( 'name' => '', 'type' => 'fragment', 'children' => array() );

		foreach ( $dom->children as $child ) {
			if ( $child->type == 'element' && $child->name == 'a' ) {
				// output any pending textbuffer as inline HTML
				if ( count( $textbuffer->children ) > 0 ) {
					$out->children[] = (object) array( 'type' => 'text', 'value' => $parser->write( $textbuffer ) );
				}
				// reformat subnodes as inline HTML
				$textbuffer->children = $child->children;
				$newchild = (object) array( 'type' => 'text', 'value' => $parser->write( $textbuffer ) );
				$child->children = array( $newchild );
				$textbuffer->children = array();
				$out->children[] = $child;
			} else if ( $child->type == 'element' && $child->name == 'span' ) {
				// In equestrian, the output contains RDF data (So a's may be wrapped in spans). Do the same as above, but skip the span.
				if ( count( $textbuffer->children ) > 0 ) {
					$out->children[] = (object) array( 'type' => 'text', 'value' => $parser->write( $textbuffer ) );
				}
				// reformat subnodes as inline HTML
				$anode = $child->children[0];
				$textbuffer->children = $anode->children;
				$newchild = (object) array( 'type' => 'text', 'value' => $parser->write( $textbuffer ) );
				$anode->children = array( $newchild );
				$textbuffer->children = array();
				$out->children[] = $anode;
			} else {
				$textbuffer->children[] = $child;
			}
		}
		if ( count( $textbuffer->children ) > 0 ) {
			$out->children[] = (object) array( 'type' => 'text', 'value' => $parser->write( $textbuffer ) );
		}
		return $out;
	}

	public function generate_crumbs() {
		global $wp;
		$this->currentUrl = home_url( add_query_arg( array(), $wp->request ) );
		$navxt            = bcn_display( TRUE );
		$parser           = new IB_Fragment_DOM;
		$dom              = $parser->read( $navxt );
		// new for 1.2. Coalesce the contents of every top-level a, and every top-level node, as inline HTML text
		$dom            = $this->coalesce( $parser, $dom );
		$this->crumbs   = array();
		$this->lastLink = null;
		$this->freeText = array();
		$this->unlinked = false;
		$this->walk( $dom );
		// if there's some unlinked text, the navxt setting didn't create the final link, so let's see if we can
		if ( $this->unlinked ) {
			$count = count( $this->freeText );
			$title = $this->freeText[ $count - 1 ];
			if ( $count > 1 && 0 === strpos( $title, $this->freeText[ $count - 2 ] ) ) {
				$title = substr( $title, strlen( $this->freeText[ $count - 2 ] ) );
			}
			$this->crumbs[] = array( 'text' => $title, 'url' => $this->currentUrl );
		}
		return $this->crumbs;
	}

	protected function on_open( $item, $parent, $index ) {
		// keep track if we're in an a
		// if so the text node inside is the link text
		// otherwise if the last node is text, then it's the current page, unlinked, but may have the separator
		if ( isset ( $item->attr ) && isset ( $item->attr['href'] ) ) {
			$this->lastLink = $item->attr['href'];
		} elseif ( $item->type == 'text' ) {
			if ( $this->lastLink ) {
				$this->crumbs[] = array( 'text' => $item->value, 'url' => $this->lastLink );
			} else {
				$this->freeText[] = $item->value;
				$this->unlinked   = true;
			}
		}
	}

	protected function on_close( $item, $parent, $index ) {
		if ( isset ( $item->attr ) && isset ( $item->attr['href'] ) ) {
			$this->lastLink = null;
			$this->unlinked = false;
		}
	}
}
