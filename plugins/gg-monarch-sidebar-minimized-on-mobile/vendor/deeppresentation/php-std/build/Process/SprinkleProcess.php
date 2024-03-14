<?php namespace MSMoMDP\Std\Process;

class SprinkleProcess {

	/*public const PRIORITY_PARENT = 'parent-priority';
	public const PRIORITY_CHILD = 'child-priority';*/
	protected $processFce;
	public $args;
	public $parent;
	public $childs = array();
	protected $component;
	public function __construct( $componentInstance, ?SprinkleProcess $parent, string $processFce, array $customArgs = null ) {
		$this->component  = $componentInstance;
		$this->parent     = $parent;
		$this->processFce = $processFce;
		$this->args       = $customArgs;
	}
	public static function create_super_sprinkle( $componentInstance ) {
		return new SprinkleProcess( $componentInstance, null, '', null );
	}
	public function is_super_sprinkle() {
		return ! $this->has_parent();
	}
	public function add_child( string $processFce, array $customArgs ) : SprinkleProcess {
		$newSprinkle    = new SprinkleProcess( $this->component, $this, $processFce, $customArgs );
		$this->childs[] = $newSprinkle;
		return $newSprinkle;
	}

	public function run() : array {
		$ret = array(
			'next-sprinkle' => null,
			'result'        => null,
		);
		if ( $this->processFce && $this->component ) {
			$ret['result'] = call_user_func( array( $this->component, $this->processFce ), $this );
		}
		if ( $this->has_child() ) {
			$ret['next-sprinkle'] = $this->get_first_child();

		} else {
			$ret['next-sprinkle'] = $this->shift();
		}
		return $ret;
	}

	private function shift() : ?SprinkleProcess {
		$parent = $this->get_parrent();
		if ( $parent ) {
			array_shift( $parent->childs ); // remove self
			if ( $parent->has_child() ) { // get next sibling
				return $parent->childs[0];
			} else {
				// recursively go to top affter all lower levels are processed
				return $parent->shift();
			}
		} else {
			// all processed
			return null;
		}
	}

	public function sprinkles_to_process() {
		$res = 0;
		foreach ( $this->childs as $child ) {
			$res += $child->sprinkles_to_process();
		}
		return $res;
	}

	private function has_child() : bool {
		return count( $this->childs ) > 0;
	}

	private function has_parent() : bool {
		return isset( $this->parent );
	}
	private function has_sibling() : bool {
		return $this->has_parent() && $this->parent->has_child();
	}

	private function get_sibling() : ?SprinkleProcess {
		return $this->has_sibling() ? $this->parent->child[0] : null;
	}
	private function get_parrent() : ?SprinkleProcess {
		return $this->has_parent() ? $this->parent : null;
	}
	private function get_first_child() : ?SprinkleProcess {
		return $this->has_child() ? $this->childs[0] : null;
	}
}
