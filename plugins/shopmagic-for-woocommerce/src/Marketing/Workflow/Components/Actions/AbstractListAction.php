<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Marketing\Workflow\Components\Actions;

use ShopMagicVendor\WPDesk\Forms\Field\InputTextField;
use ShopMagicVendor\WPDesk\Forms\Field\SelectField;
use WPDesk\ShopMagic\Marketing\Subscribers\AudienceList\AudienceListRepository;
use WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber\SubscriberObjectRepository;
use WPDesk\ShopMagic\Marketing\Subscribers\ListSubscriber\SubscriptionManager;
use WPDesk\ShopMagic\Workflow\Action\Action;
use WPDesk\ShopMagic\Workflow\Event\DataLayer;

/**
 * Abstract template for Lists related actions.
 */
abstract class AbstractListAction extends Action {
	public const PARAM_LIST  = 'list';
	public const PARAM_EMAIL = 'email';

	/** @var SubscriptionManager */
	protected $manager;

	/** @var SubscriberObjectRepository */
	protected $repository;

	/** @var AudienceListRepository */
	private $list_repository;

	public function __construct( SubscriptionManager $manager, AudienceListRepository $list_repository ) {
		$this->manager         = $manager;
		$this->repository      = $manager->get_repository();
		$this->list_repository = $list_repository;
	}

	final public function execute( DataLayer $resources ): bool {
		$this->resources = $resources;
		$email           = $this->get_email();
		if ( ! empty( $email ) ) {
			$list_id   = absint( $this->fields_data->get( self::PARAM_LIST ) );
			$list_name = get_the_title( $list_id );

			return $this->do_list_action( $email, $list_id, $list_name );
		}

		return false;
	}

	private function get_email(): string {
		return $this->placeholder_processor->process( $this->fields_data->get( self::PARAM_EMAIL ) );
	}

	abstract protected function do_list_action( string $email, int $list_id, string $list_name ): bool;

	/**
	 * @return mixed[]
	 */
	final public function get_required_data_domains(): array {
		return [];
	}

	/**
	 * @return mixed[]
	 */
	public function get_fields(): array {
		return array_merge( parent::get_fields(), [
			( new SelectField() )
				->set_label( esc_html__( 'List', 'shopmagic-for-woocommerce' ) )
				->set_options( $this->list_repository->get_as_select_options() )
				->set_name( self::PARAM_LIST ),
			( new InputTextField() )
				->set_label( esc_html__( 'Email', 'shopmagic-for-woocommerce' ) )
				->set_placeholder( esc_html__( 'E-mail or a placeholder with an e-mail', 'shopmagic-for-woocommerce' ) )
				->set_name( self::PARAM_EMAIL ),
		] );
	}
}
