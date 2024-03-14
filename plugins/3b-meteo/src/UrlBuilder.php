<?php
declare(strict_types=1);

namespace TreBiMeteo;

final class UrlBuilder implements UrlBuilderInterface {


	/**
	 * @var string
	 */
	private $url;

	/**
	 * @var array<string, mixed>
	 */
	private $query_arguments = [];

	/**
	 * @var HttpQueryBuilder|QueryBuilderStrategy
	 */
	private $query_builder;

	/**
	 * @param string $url
	 */
	public function __construct( string $url, QueryBuilderStrategy $query_builder = null ) {
		$this->url = $url;
		$this->query_builder = $query_builder ?? new HttpQueryBuilder();
	}

	public function render(): string {
		return \sprintf(
			'%s%s',
			$this->url,
			$this->query_builder->build( $this->query_arguments )
		);
	}

	public function __toString() {
		return $this->render();
	}

	/**
	 * @param array<string, string> $params
	 * @return $this
	 */
	public function query( array $params ): self {
		$this->query_arguments = $params;
		return $this;
	}
}
