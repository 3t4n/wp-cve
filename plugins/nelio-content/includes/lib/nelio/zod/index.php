<?php

namespace Nelio_Content\Zod;

require_once dirname( __FILE__ ) . '/abstract-schema.php';
require_once dirname( __FILE__ ) . '/array-schema.php';
require_once dirname( __FILE__ ) . '/boolean-schema.php';
require_once dirname( __FILE__ ) . '/enum-schema.php';
require_once dirname( __FILE__ ) . '/literal-schema.php';
require_once dirname( __FILE__ ) . '/number-schema.php';
require_once dirname( __FILE__ ) . '/object-schema.php';
require_once dirname( __FILE__ ) . '/string-schema.php';
require_once dirname( __FILE__ ) . '/union-schema.php';

class Zod {
	public static function array( Schema $schema ): ArraySchema {
		return ArraySchema::make( $schema );
	}//end array()

	public static function boolean(): BooleanSchema {
		return BooleanSchema::make();
	}//end boolean()

	public static function enum( array $values ): EnumSchema {
		return EnumSchema::make( $values );
	}//end enum()

	public static function literal( array $values ): LiteralSchema {
		return LiteralSchema::make( $values );
	}//end literal()

	public static function number(): NumberSchema {
		return NumberSchema::make();
	}//end number()

	public static function object( array $schema ): ObjectSchema {
		return ObjectSchema::make( $schema );
	}//end object()

	public static function string(): StringSchema {
		return StringSchema::make();
	}//end string()

	public static function union( array $schemas ): UnionSchema {
		return UnionSchema::make( $schemas );
	}//end union()

}//end class
