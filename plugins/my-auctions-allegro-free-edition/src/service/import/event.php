<?php
declare(strict_types=1);

require_once(GJMAA_PATH_CODE.'/service/abstract.php');

/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined( 'ABSPATH' ) or die;

abstract class GJMAA_Service_Import_Event extends GJMAA_Service_Abstract
{
    protected $event;

    protected $profile;

    protected $processedAuctions = [];

    public function execute(int $settingsId)
    {
        $this->setSettings($settingsId);

        /** @var GJMAA_Model_Settings $settings */
        $settings = $this->connect($settingsId);

        /** @var GJMAA_Lib_Rest_Api_Sale_Offerevents $apiLibrary */
        $apiLibrary = GJMAA::getLib('rest_api_sale_offerevents');
        $apiLibrary->setLimit(100);
        $apiLibrary->setTypes($this->getTypes());
        $apiLibrary->setFrom($this->getLastEvent());
        $apiLibrary->setSandboxMode($settings->getData('setting_is_sandbox'));
        $apiLibrary->setToken($settings->getData('setting_client_token'));

        $result = $apiLibrary->execute();

        $this->processEvents($result, $settingsId);

        return $this->getLastEvent();
    }

    abstract public function getTypes() : array;

    abstract public function processEvents($result, $settingsId);

    public function resetProcessedAuction() : self
    {
        $this->processedAuctions = [];

        return $this;
    }

    public function addToProcessedAuctions($auctionId) : self
    {
        $this->processedAuctions[] = $auctionId;

        return $this;
    }

    public function isProcessedAuction($auctionId) : bool
    {
        return in_array($auctionId, $this->processedAuctions);
    }

    public function getEventTypeSource()
    {
        return GJMAA::getSource('allegro_offer_event_type');
    }

    public function setLastEvent(?string $event = null)
    {
        $this->event = $event;
    }

    public function getLastEvent() : ?string
    {
        return $this->event;
    }

    public function getAuctionDetail($auctionId, $settingId)
    {
        $settings = $this->connect($settingId);

        /** @var GJMAA_Lib_Rest_Api_Sale_Offers $api */
        $api = GJMAA::getLib( 'rest_api_sale_offers' );
        $api->setSandboxMode($settings->getData('setting_is_sandbox'));
        $api->setToken($settings->getData('setting_client_token'));
        $api->setAuctionId($auctionId);
        return $api->execute();
    }
}