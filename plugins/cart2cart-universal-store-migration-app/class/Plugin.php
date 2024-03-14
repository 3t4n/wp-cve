<?php

namespace Cart2cart;

class Plugin
{
  const PLUGINS_TITLE = 'Cart2Cart Universal Migration App';

  const URL_LIMIT = 'to-woocommerce';
  const SOURCE_CART_ID = '';

  const PLUGIN_PAGE = 'cart2cart-migration';
  const HOW_IT_WORKS_PAGE = 'cart2cart-how-it-works';
  const SERVICES_PAGE = 'cart2cart-additional-services';
  const HELP_PAGE = 'cart2cart-migration-help';

  /** @var \Cart2cart\View  */
  public $view;

  public function __construct()
  {
    $this->view = new View();
    $this->_addActions();
  }

  public static function init()
  {
    new self;
  }

  private function _addActions()
  {
    add_action('admin_menu', array($this, 'registerMenuItems'));
  }

  public function registerMenuItems()
  {
    add_menu_page(
      'Cart2Cart Migration',
      'Cart2Cart Migration',
      'manage_options',
      self::PLUGIN_PAGE,
      array($this, 'getPluginsPageContent'),
      plugins_url('/img/icon.png', CART2CART_PLUGIN_ROOT_DIR . 'dir'),
      60
    );

    add_submenu_page(self::PLUGIN_PAGE, 'How it works', 'How it works', 'manage_options', self::HOW_IT_WORKS_PAGE, array($this, 'howItWorksPageContent'));
    add_submenu_page(self::PLUGIN_PAGE, 'Additional Services', 'Additional Services', 'manage_options', self::SERVICES_PAGE, array($this, 'servicesPageContent'));
    add_submenu_page(self::PLUGIN_PAGE, 'Live Chat & Help', 'Live Chat & Help', 'manage_options', self::HELP_PAGE, array($this, 'faqHelpPageContent'));
  }

  public function getPluginsPageContent()
  {
    echo $this->view->render('main/index.phtml');
  }

  public function supportChatPageContent()
  {
    echo $this->view->render('support/chat.phtml');
  }

  public function howItWorksPageContent()
  {
    echo $this->view->render('support/how-it-works.phtml');
  }

  public function servicesPageContent()
  {
    echo $this->view->render('support/additional-services.phtml');
  }

  public function faqHelpPageContent()
  {
    echo $this->view->render('support/faqs-and-help.phtml');
  }
}