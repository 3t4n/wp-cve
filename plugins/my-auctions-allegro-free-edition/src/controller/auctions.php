<?php
/**
 * My Auctions Allegro
 * @Author Luke Grochal (Grojan Team)
 * @Author URI https://grojanteam.pl
 */

defined('ABSPATH') or die;

/**
 * class control all things related to plugin auctions
 * @author grojanteam
 *
 */
class GJMAA_Controller_Auctions extends GJMAA_Controller
{

    protected $content;

    protected $parent = 'gjmaa_dashboard';

    public function getName()
    {
        $page = $this->getParam('page');
        $action = $this->getParam('action');

        if ($page == $this->getSlug()) {
            switch ($action) {
                default:
                    return 'Auctions';
            }
        }

        return 'Auctions';
    }

    public function getMenuName()
    {
        return __('Auctions', GJMAA_TEXT_DOMAIN);
    }

    /**
     * display table for settings
     */
    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function renderView()
    {
        $html = '<div class="wrap">' . $this->getTitle() . $this->getContent() . '</div>';

        echo $html;
    }

    public function index()
    {
        $table = GJMAA::getTable('auctions');
        $this->setContent($table->show());
    }

    public function getSlug()
    {
        return 'gjmaa_auctions';
    }

    public function collectClicks()
    {}

    public function collectVisits()
    {}

    public function showOnAllegro()
    {
        $auctionId = $this->getParam('id');
        if (! $auctionId) {
            $this->addSessionError(__('Wrong Auction ID or auction does not exist.', GJMAA_TEXT_DOMAIN));
            $this->redirect($this->getIndexUrl());
            return;
        }

        try {
            $auctionHelper = GJMAA::getHelper('auctions');
            $this->redirect($auctionHelper->getAuctionUrl($auctionId));
        } catch (Exception $e) {
            $this->addSessionError(__(sprintf('Something went wrong: %s', $e->getMessage()), GJMAA_TEXT_DOMAIN));
            $this->redirect($this->getIndexUrl());
        }
    }

    public function collect_click()
    {
        $auctionId = $this->getParam('auction_id');
        $profileId = $this->getParam('profile_id');

        /** @var GJMAA_Model_Auctions $auctionModel */
        $auctionModel = GJMAA::getModel('auctions');
        $auctionModel->collect($auctionId, $profileId);

        $this->sendSuccessJsonResponse([]);
    }

    public function removeFromList()
    {
        $auctionId = $this->getParam('id');
        if (! $auctionId) {
            $this->addSessionError(__('Wrong Auction ID or auction does not exist.', GJMAA_TEXT_DOMAIN));
            $this->redirect($this->getIndexUrl());
            return;
        }

        try {
            /** @var GJMAA_Model_Auctions $auctionModel */
            $auctionModel = GJMAA::getModel('auctions');
            $auctionModel->load($auctionId);
            $auctionModel->delete();
            $this->redirect($this->getIndexUrl());
        } catch (Exception $e) {
            $this->addSessionError(__(sprintf('Something went wrong: %s', $e->getMessage()), GJMAA_TEXT_DOMAIN));
            $this->redirect($this->getIndexUrl());
        }
    }

    public function initAjaxHooks()
    {
        if (is_admin()) {
            add_action('wp_ajax_gjmaa_collect_click', [
                $this,
                'collect_click'
            ]);
        }
        add_action('wp_ajax_nopriv_gjmaa_collect_click', [
            $this,
            'collect_click'
        ]);
    }

    public function addScreenOptions() : bool
    {
        return true;
    }

    public function addOptions()
    {
        global $myListTable;

        $option = 'per_page';
        $args = [
            'label' => __('Auctions', GJMAA_TEXT_DOMAIN),
            'default' => 20,
            'option' => 'auctions_per_page'
        ];

        add_screen_option($option, $args);

        $myListTable = GJMAA::getTable('auctions');
    }
}