<?php
require_once( dirname( __FILE__ ) . '/class-ib-fragment-visitor.php' );

class IB_Fragment_DOM extends IB_Fragment_Visitor {
	private $currentNode;
	private $stack;

	public function read( $input ) {
		$this->currentNode = (object) array( 'name' => '', 'type' => 'fragment', 'children' => array() );
		$this->stack = array();
		if ( ! $this->visit( $input ) ) return null;
		if ( count( $this->stack ) > 0 ) return null;
		return $this->currentNode;
	}

	private function _write( $item ) {
		$output = '';
		if ( $item ) {
			switch ( $item->type ) {
				case 'fragment':
					foreach ( $item->children as $child ) {
						$output .= $this->_write( $child );
					}
					break;
				case 'element':
					$output .= ('<' . $item->name);
					foreach ( $item->attr as $name => $value ) {
						$output .= (' ' . $name . '="' . $value . '"');
					}
					$output .= '>';
					foreach ( $item->children as $child ) {
						$output .= $this->_write( $child );
					}
					$output .= ('</' . $item->name . '>');
					break;
				case 'text':
					$output = $item->value;
					break;
			}
		}
		return $output;
	}

	public function write( $dom ) {
		return $this->_write( $dom );
	}

	protected function on_content( $content ) {
		$this->currentNode->children[] = (object) array( 'type' => 'text', 'value' => $content );
		return TRUE;
	}

	protected function on_tag_open( $tag ) {
		array_push( $this->stack, $this->currentNode );
		$this->currentNode = (object) array( 'type' => 'element', 'name' => $tag['name'], 'attr' => $tag['attributes'], 'children' => array() );
		return TRUE;
	}

	protected function on_tag_close( $tag ) {
		if ( $tag['name'] != $this->currentNode->name ) return FALSE;
		$parent = array_pop( $this->stack );
		$parent->children[] = $this->currentNode;
		$this->currentNode  = $parent;
		return TRUE;
	}
}
