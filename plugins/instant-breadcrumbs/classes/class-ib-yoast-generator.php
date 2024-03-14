<?php
require_once( dirname( __FILE__ ) . '/class-ib-fragment-dom.php' );
require_once( dirname( __FILE__ ) . '/class-ib-dom-walker.php' );

class IB_Yoast_Generator extends IB_DOM_Walker {
	private $currentUrl;
	private $crumbs;

	public function is_supported() {
		return function_exists( 'yoast_breadcrumb' );
	}
	
	public function init() {
	}

	public function generate_crumbs() {
		global $wp;
		$this->currentUrl = home_url( add_query_arg( array(), $wp->request ) );
		$yoast            = yoast_breadcrumb( '', '', FALSE );
		$parser           = new IB_Fragment_DOM;
		$dom              = $parser->read( $yoast );
		$this->crumbs     = array();
		$this->walk( $dom );
		return $this->crumbs;
	}

	protected function on_open( $item, $parent, $index ) {
		// if it has a property='v:title' attribute, the child text is the name
		// if it has a rel='v:url' attribute, the href attribute is the link
		// otherwise assume the current URL
		
		if ( isset( $item->attr ) && isset( $item->attr['property'] ) && ( $item->attr['property'] == 'v:title' ) ) {
			$title = $item->children[0]->value;
			if ( isset( $item->attr['rel'] ) && ( $item->attr['rel'] == 'v:url' ) && isset( $item->attr['href'] ) ) {
				$url = $item->attr['href'];
			} else {
				$url = $this->currentUrl;
			}
			$this->crumbs[] = array( 'url' => $url, 'text' => $title ); 
		}
	}

	protected function on_close( $item, $parent, $index ) {
	}
}
