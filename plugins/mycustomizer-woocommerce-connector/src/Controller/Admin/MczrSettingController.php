<?php

namespace MyCustomizer\WooCommerce\Connector\Controller\Admin;

use Exception;
use Symfony\Component\HttpFoundation\Request;
use MyCustomizer\WooCommerce\Connector\Auth\MczrAccess;
use MyCustomizer\WooCommerce\Connector\Config\MczrConfig;
use MyCustomizer\WooCommerce\Connector\Controller\Admin\MczrProductTypeController;
use MyCustomizer\WooCommerce\Connector\Factory\MczrFactory;
use MyCustomizer\WooCommerce\Connector\Libs\MczrConnect;
use MyCustomizer\WooCommerce\Connector\Libs\MczrFlashMessage;
use MyCustomizer\WooCommerce\Connector\Libs\MczrSettings;

MczrAccess::isAuthorized();

class MczrSettingController {

	public function __construct() {
		$this->request                   = Request::createFromGlobals();
		$this->factory                   = new MczrFactory();
		$this->twig                      = $this->factory->getTwig();
		$this->flash                     = new MczrFlashMessage();
		$this->mczr                      = new MczrConnect();
		$this->settings                  = new MczrSettings();
		$this->mczrProductTypeController = new MczrProductTypeController();
	}

	public function init() {
		add_action( 'admin_menu', array( $this, 'addSubmenu' ) );
	}

	public function addSubmenu() {
		add_submenu_page( 'woocommerce', 'MyCustomizer Settings', 'MyCustomizer', 'manage_options', 'mczrSettings', array( $this, 'settingPageAction' ) );
	}

	public function settingPageAction() {
		MczrAccess::can( 'manage_options' );
		$vars            = array();
		$vars['updated'] = false;
		$vars['title']   = get_admin_page_title();

		if ( isset( $_POST ) && ! empty( $_POST ) ) {
			check_admin_referer( 'mczr-settings-save' );
			if ( !count( $_POST ) ) {
				$this->flash->display();
				$this->renderForm();
				return;
			}

			$unlinkBrand = null !== $this->request->get( 'unlinkBrand' ) && $this->request->get( 'unlinkBrand' ) == 'true';
			$syncCode = $this->request->get( 'syncCode' );
			if ( isset( $syncCode ) && '' != $syncCode ) {
				try {
					$syncCodeDecoded = json_decode( base64_decode( $syncCode ) );

					if ( isset( $syncCodeDecoded->brand ) && isset( $syncCodeDecoded->token ) && isset( $syncCodeDecoded->onlineStoreId ) ) {
						$this->settings->update(
							array(
								'brand'    => $syncCodeDecoded->brand,
								'apiToken' => $syncCodeDecoded->token,
							)
						);

						$this->mczr->connect( $syncCodeDecoded->brand, $syncCodeDecoded->onlineStoreId );
						$this->flash->add( MczrFlashMessage::TYPE_SUCCESS, sprintf( 'Your store is now synced with your MyCustomizer account.' ) );
					} elseif ( isset( $syncCodeDecoded->startingPointId )
						&& isset( $syncCodeDecoded->startingPointName )
						&& isset( $syncCodeDecoded->startingPointImage )
						&& isset( $syncCodeDecoded->price )
					) {
						$productId = $this->mczrProductTypeController->create( $syncCodeDecoded->startingPointId, $syncCodeDecoded->startingPointName, $syncCodeDecoded->price );

						$this->mczrProductTypeController->attachProductThumbnail( $productId, $syncCodeDecoded->startingPointImage );

						$this->flash->add( MczrFlashMessage::TYPE_SUCCESS, sprintf( 'Your product was created.' ) );
					} else {
						throw new Exception( 'syncCode is not valid.' );
					}
				} catch ( Exception $err ) {
					$this->flash->add( MczrFlashMessage::TYPE_ERROR, 'Sync code: the sync code you entered is not valid' );
					$this->flash->display();
					$this->renderForm();
					return;
				}
			} else {
				$this->settings->update(
					array(
						'shopId'               => $unlinkBrand ? '' : $this->request->get( 'shopId' ),
						'brand'                => $unlinkBrand ? '' : $this->request->get( 'brand' ),
						'iframeWidth'          => $this->request->get( 'iframeWidth' ),
						'iframeHeight'         => $this->request->get( 'iframeHeight' ),
						'iframeHook'   	       => $this->request->get( 'iframeHook' ),
						'iframeHookPriority'   => $this->request->get( 'iframeHookPriority' ),
						'productCss'           => $this->request->get( 'productCss' ),
						'apiToken'             => $unlinkBrand ? '' : $this->request->get( MczrConnect::TOKEN_FIELD_NAME ),
					)
				);
				// Success
				$flashMessage = $unlinkBrand ? 'Brand Unlinked. ' : 'Settings are saved. ';
				$this->flash->add( MczrFlashMessage::TYPE_SUCCESS, sprintf( $flashMessage ) );
			}
		}
		$this->flash->display();
		$this->renderForm();
		return;
	}

	public function renderForm() {
		MczrAccess::can( 'manage_options' );

		$brand = $this->settings->get( 'brand' );

		if ( empty( $brand ) ) {
			$mczrDashboardLinkHref  = MczrConfig::getInstance()['registerUrl'] . '?eCommerce=woocommerce&shop=' . get_site_url() . '&woocommerceToken=' . $this->settings->get( 'authorizationKey' );
			$mczrDashboardLinkTitle = '(!) Please register on MyCustomizer Dashboard';
		} else {
			$mczrDashboardLinkHref  = str_replace( '{{brand}}', $brand, MczrConfig::getInstance()['dashboardUrlPattern'] );
			$mczrDashboardLinkTitle = 'Open MyCustomizer Dashboard';
		}

		$vars['form']                           = array();
		$vars['form']['mczrDashboardLinkHref']  = $mczrDashboardLinkHref;
		$vars['form']['mczrDashboardLinkTitle'] = $mczrDashboardLinkTitle;
		$vars['form']['title']                  = esc_html( get_admin_page_title() );
		$vars['form']['iframeWidth']            = $this->settings->get( 'iframeWidth' );
		$vars['form']['iframeHeight']           = $this->settings->get( 'iframeHeight' );
		$vars['form']['iframeHook']             = $this->settings->get( 'iframeHook' );
		$vars['form']['iframeHookPriority']     = $this->settings->get( 'iframeHookPriority' );
		$vars['form']['productCss']             = $this->settings->get( 'productCss' );
		$vars['form']['brand']                  = $brand;
		$vars['form']['shopId']                 = $this->settings->get( 'shopId' );
		$vars['form']['apiToken']               = $this->settings->get( MczrConnect::TOKEN_FIELD_NAME );
		$vars['form']['token']                  = wp_nonce_field( 'mczr-settings-save' );
		$allowed_html = array(
			'a' => array(
				'href' => array(),
				'target' => array(),
			),
			'br' => array(),
			'button' => array(
				'class' => array(),
				'id' => array(),
				'name' => array(),
				'type' => array(),
			),
			'div' => array(
				'class' => array(),
			),
			'fieldset' => array(
			),
			'form' => array(
				'name' => array(),
				'method' => array(),
			),
			'h1' => array(
			),
			'h2' => array(
			),
			'h3' => array(
			),
			'hr' => array(
			),
			'img' => array(
				'src' => array(),
			),
			'input' => array(
				'id' => array(),
				'class' => array(),
				'name' => array(),
				'placeholder' => array(),
				'readonly' => array(),
				'type' => array(),
				'value' => array(),
			),
			'label' => array(
				'class' => array(),
				'for' => array(),
			),
			'textarea' => array(
				'class' => array(),
				'id' => array(),
				'help' => array(),
				'name' => array(),
				'rows' => array(),
				'cols' => array(),
			),
			'small' => array(
				'class' => array(),
			),
		);
		echo wp_kses( $this->twig->render( 'Settings/index.html.twig', $vars ), $allowed_html );
		return;
	}
}
