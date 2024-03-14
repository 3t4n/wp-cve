<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Routing\Controller;

class ArgumentResolver {

	/** @var ArgumentValueResolver[] */
	private $value_resolvers;

	public function __construct( array $value_resolvers ) {
		$this->value_resolvers = $value_resolvers;
	}

	public function get_arguments( \WP_REST_Request $request, callable $controller ): array {
		$parameters = ( new \ReflectionMethod( $controller[0], $controller[1] ) )->getParameters();
		$arguments  = [];

		foreach ( $parameters as $parameter ) {
			foreach ( $this->value_resolvers as $resolver ) {
				if ( $resolver->supports( $request, $parameter ) ) {
					$arguments[] = $resolver->resolve( $request, $parameter );
					// Continue to the next parameter
					continue 2;
				}
			}

			throw new \RuntimeException(
				sprintf(
					'Controller "%s:%s()" requires a value for argument "$%s"',
					get_class( $controller[0] ),
					$controller[1],
					$parameter->getName()
				)
			);
		}

		return $arguments;
	}

}
