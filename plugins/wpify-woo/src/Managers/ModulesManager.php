<?php

namespace WpifyWoo\Managers;

use WpifyWoo\Modules\AsyncEmails\AsyncEmailsModule;
use WpifyWoo\Modules\Comments\CommentsModule;
use WpifyWoo\Modules\DeliveryDates\DeliveryDatesModule;
use WpifyWoo\Modules\EmailAttachments\EmailAttachmentsModule;
use WpifyWoo\Modules\FreeShippingNotice\FreeShippingNoticeModule;
use WpifyWoo\Modules\HeurekaMereniKonverzi\HeurekaMereniKonverziModule;
use WpifyWoo\Modules\HeurekaOverenoZakazniky\HeurekaOverenoZakaznikyModule;
use WpifyWoo\Modules\IcDic\IcDicModule;
use WpifyWoo\Modules\PacketaShipping\PacketaShippingModule;
use WpifyWoo\Modules\Prices\PricesModule;
use WpifyWoo\Modules\PricesLog\PricesLogModule;
use WpifyWoo\Modules\QRPayment\QRPaymentModule;
use WpifyWoo\Modules\SklikRetargeting\SklikRetargetingModule;
use WpifyWoo\Modules\Template\TemplateModule;
use WpifyWoo\Modules\ZboziConversions\ZboziConversionsModule;
use WpifyWoo\Modules\Vocative\VocativeModule;
use WpifyWoo\Modules\XmlFeedHeureka\XmlFeedHeurekaModule;
use WpifyWoo\Plugin;
use WpifyWooDeps\Wpify\Core\Abstracts\AbstractManager;

/**
 * Class ApiManager
 *
 * @package WpifyWoo\Managers
 * @property Plugin $plugin
 */
class ModulesManager extends AbstractManager {
	protected $modules = array();
	private $async_emails = AsyncEmailsModule::class;
	private $packeta_shipping = PacketaShippingModule::class;
	private $ic_dic = IcDicModule::class;
	private $heureka_overeno_zakazniky = HeurekaOverenoZakaznikyModule::class;
	private $heureka_mereni_konverzi = HeurekaMereniKonverziModule::class;
	private $free_shipping_notice = FreeShippingNoticeModule::class;
	private $vocative = VocativeModule::class;
	private $qr_payment = QRPaymentModule::class;
	private $xml_feed_heureka = XmlFeedHeurekaModule::class;
	private $sklik_retargeting = SklikRetargetingModule::class;
	private $zbozi_conversions_lite = ZboziConversionsModule::class;
	private $template = TemplateModule::class;
	private $email_attachments = EmailAttachmentsModule::class;
	private $prices = PricesModule::class;
	private $prices_log = PricesLogModule::class;
	private $comments = CommentsModule::class;
	private $delivery_dates = DeliveryDatesModule::class;

	public function load_components() {
		$woo_integration = $this->plugin->get_woocommerce_integration();

		foreach ( $woo_integration->get_modules() as $module ) {
			if ( $woo_integration->is_module_enabled( $module['value'] ) && property_exists( $this, $module['value'] ) ) {
				$this->load( $module['value'] );
				$this->{$module['value']}->init();
				$this->add_module( $this->{$module['value']} );
			}
		}
	}

	/**
	 * @return string
	 */
	public function get_checkout() {
		return $this->checkout;
	}

	/**
	 * @param string $checkout
	 */
	public function set_checkout( $checkout ) {
		$this->checkout = $checkout;
	}

	/**
	 * @return string
	 */
	public function get_async_emails() {
		return $this->async_emails;
	}

	/**
	 * @param string $async_emails
	 */
	public function set_async_emails( $async_emails ) {
		$this->async_emails = $async_emails;
	}

	/**
	 * @return string
	 */
	public function get_packeta_shipping() {
		return $this->packeta_shipping;
	}

	/**
	 * @param string $packeta_shipping
	 */
	public function set_packeta_shipping( $packeta_shipping ) {
		$this->packeta_shipping = $packeta_shipping;
	}

	/**
	 * @return string
	 */
	public function get_ic_dic() {
		return $this->ic_dic;
	}

	/**
	 * @param string $ic_dic
	 */
	public function set_ic_dic( $ic_dic ) {
		$this->ic_dic = $ic_dic;
	}

	/**
	 * @return string
	 */
	public function get_heureka_overeno_zakazniky() {
		return $this->heureka_overeno_zakazniky;
	}

	/**
	 * @param string $heureka_overeno_zakazniky
	 */
	public function set_heureka_overeno_zakazniky( $heureka_overeno_zakazniky ) {
		$this->heureka_overeno_zakazniky = $heureka_overeno_zakazniky;
	}

	/**
	 * @return string
	 */
	public function get_free_shipping_notice() {
		return $this->free_shipping_notice;
	}

	/**
	 * @param string $free_shipping_notice
	 */
	public function set_free_shipping_notice( $free_shipping_notice ) {
		$this->free_shipping_notice = $free_shipping_notice;
	}

	public function get_module_by_id( $id ) {
		foreach ( $this->get_modules() as $module ) {
			if ( $module->id() === $id ) {
				return $module;
			}
		}

		return null;
	}

	/**
	 * @return string
	 */
	public function get_heureka_mereni_konverzi() {
		return $this->heureka_mereni_konverzi;
	}

	/**
	 * @param string $heureka_mereni_konverzi
	 */
	public function set_heureka_mereni_konverzi( $heureka_mereni_konverzi ) {
		$this->heureka_mereni_konverzi = $heureka_mereni_konverzi;
	}

	/**
	 * @return string
	 */
	public function get_vocative() {
		return $this->vocative;
	}

	/**
	 * @param string $vocative
	 */
	public function set_vocative( $vocative ): void {
		$this->vocative = $vocative;
	}

	/**
	 * @return string
	 */
	public function get_xml_feed_heureka() {
		return $this->xml_feed_heureka;
	}

	/**
	 * @param string $xml_feed_heureka
	 */
	public function set_xml_feed_heureka( $xml_feed_heureka ): void {
		$this->xml_feed_heureka = $xml_feed_heureka;
	}

	/**
	 * @return string
	 */
	public function get_phone_validation() {
		return $this->phone_validation;
	}

	/**
	 * @return string
	 */
	public function get_qr_payment() {
		return $this->qr_payment;
	}

	/**
	 * @param string $qr_payment
	 */
	public function set_qr_payment( $qr_payment ): void {
		$this->qr_payment = $qr_payment;
	}

	/**
	 * @return string
	 */
	public function get_sklik_retargeting() {
		return $this->sklik_retargeting;
	}

	/**
	 * @param string $sklik_retargeting
	 */
	public function set_sklik_retargeting( $sklik_retargeting ) {
		$this->sklik_retargeting = $sklik_retargeting;
	}

	/**
	 * @return string
	 */
	public function get_zbozi_conversions_lite() {
		return $this->zbozi_conversions_lite;
	}

	/**
	 * @param string $zbozi_conversions_lite
	 */
	public function set_zbozi_conversions_lite( $zbozi_conversions_lite ) {
		$this->zbozi_conversions_lite = $zbozi_conversions_lite;
	}

	/**
	 * @return string
	 */
	public function get_template() {
		return $this->template;
	}

	/**
	 * @param string $template
	 */
	public function set_template( $template ): void {
		$this->template = $template;
	}

	/**
	 * @return string
	 */
	public function get_email_attachments() {
		return $this->email_attachments;
	}

	/**
	 * @param string $email_attachments
	 */
	public function set_email_attachments( $email_attachments ): void {
		$this->email_attachments = $email_attachments;
	}

	/**
	 * @return string
	 */
	public function get_prices() {
		return $this->prices;
	}

	/**
	 * @param string $prices
	 */
	public function set_prices( $prices ): void {
		$this->prices = $prices;
	}

	/**
	 * @return string
	 */
	public function get_prices_log() {
		return $this->prices_log;
	}

	/**
	 * @param string $prices_log
	 */
	public function set_prices_log( $prices_log ): void {
		$this->prices_log = $prices_log;
	}

	/**
	 * @return string
	 */
	public function get_comments() {
		return $this->comments;
	}

	/**
	 * @param string $comments
	 */
	public function set_comments( $comments ): void {
		$this->comments = $comments;
	}

	/**
	 * @return string
	 */
	public function get_delivery_dates() {
		return $this->delivery_dates;
	}

	/**
	 * @param string $delivery_dates
	 */
	public function set_delivery_dates( $delivery_dates ): void {
		$this->delivery_dates = $delivery_dates;
	}
}
