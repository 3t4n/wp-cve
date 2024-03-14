<?php
class IB_DOM_Walker {
	private $stack;

	private function _walk( $item, $parent, $index ) {
		$running = TRUE;
		if ( $this->on_open( $item, $parent, $index ) ) $running = FALSE;
		if ( isset( $item->children ) ) {
			array_push( $this->stack, $item );
			$subindex = 0;
			while ( $running && ($subindex < count( $item->children ) ) ) {
				$child = $item->children[ $subindex ];
				if ( $child ) {
					$running = $this->_walk( $item->children[ $subindex ], $item, $subindex );
				}
				$subindex++;
			}
			array_pop( $this->stack );
		}
		if ( $running && $this->on_close( $item, $parent, $index ) ) $running = FALSE;
		return $running;
	}

	protected function walk( $dom ) {
		$this->stack = array();
		$this->_walk( $dom, null, 0 );
	}

	protected function ancestor_node( $name ) {
		for ( $i = count( $this->stack ) - 1; $i >= 0; $i-- ) {
			$item = $this->stack[ $i ];
			if ( $item->type == 'element' && $item->name == $name ) return $item;
		}
		return null;
	}

	protected function add_class( $node, $clazz ) {
		$now = isset( $node->attr['class'] ) ? explode( ' ', $node->attr['class'] ) : array();
		if ( ! in_array( $clazz, $now ) ) $now[] = $clazz;
		$node->attr['class'] = implode( ' ', $now );
	}

	protected function add_attribute( $node, $key, $value ) {
		$node->attr[ $key ] = esc_attr( $value );
	}
}
