<?php
/**
 * Copyright (c) 2016 Ultra Community (http://www.ultracommunity.com)
 */

namespace InvisibleReCaptcha\MchLib\Plugin;

abstract class MchBaseAdminPlugin extends MchBasePlugin
{
	protected $adminPagesList = array();

	public abstract function enqueueAdminScriptsAndStyles();
	public abstract function getMenuPosition();

	protected function __construct()
	{
		parent::__construct();

		add_action('admin_init', array($this, 'initializeAdminPlugin'));

		add_action( self::isNetworkActivated() ? 'network_admin_menu' : 'admin_menu', array( $this, 'buildPluginMenu' ), 10);

		add_action('admin_enqueue_scripts', array( $this, 'enqueueAdminScriptsAndStyles' ));

	}

	public function registerAdminPage(MchBaseAdminPage $adminPage)
	{
		$this->adminPagesList[] = $adminPage;
	}

	public function getRegisteredAdminPages()
	{
		return $this->adminPagesList;
	}

	public function renderPluginActiveAdminPage()
	{
		$activeAdminPage = $this->getActivePage();

		$arrPageHolderClasses = array('wrap', 'container-fluid', $activeAdminPage->getPageMenuSlug());

		$adminPageHtmlCode  = '<div class="' . implode(' ', $arrPageHolderClasses) . '">';

		$adminPageHtmlCode .= '<h2 class="nav-tab-wrapper">';

		foreach ($this->getRegisteredAdminPages() as $adminPage) {
			$adminPageHtmlCode .= '<a class="nav-tab' . (($adminPage->isActive()) ? ' nav-tab-active' : '') . '" href="?page=' . $adminPage->getPageMenuSlug() . '">';
			$adminPageHtmlCode .= $adminPage->getPageMenuTitle() . '</a>';
		}

		$adminPageHtmlCode .= '</h2>';

		echo $adminPageHtmlCode;


		if(null !== $activeAdminPage)
		{
			$activeAdminPage->renderPageContent();
		}

		echo '</div>';
	}


	public function buildPluginMenu()
	{
		$arrRegisteredPages = $this->getRegisteredAdminPages();
		$adminFirstPage = reset($arrRegisteredPages);
		if(false === $adminFirstPage)
			return;

		$pageAdminScreenId = add_menu_page(
			$adminFirstPage->getPageBrowserTitle(),
			self::$PLUGIN_NAME,
			'manage_options',
			$adminFirstPage->getPageMenuSlug(),
			array($this, 'renderPluginActiveAdminPage'),
			'dashicons-shield',
			$this->getMenuPosition()
		);

		$this->adminPagesList[0]->setAdminScreenId($pageAdminScreenId);

		$arrSize = count($this->adminPagesList);
		if(1 === $arrSize)
			return;

		add_submenu_page(
			$adminFirstPage->getPageMenuSlug(),
			$adminFirstPage->getPageBrowserTitle(),
			$adminFirstPage->getPageMenuTitle(),
			'manage_options',
			$adminFirstPage->getPageMenuSlug()
		);


		for($i = 1; $i < $arrSize; ++$i)
		{
			if(!$this->adminPagesList[$i]->hasRegisteredModules())
			{
				unset($this->adminPagesList[$i]);
				continue;
			}

			$pageMenuTitle = $this->adminPagesList[$i]->getPageMenuTitle();
			if(strpos($pageMenuTitle, 'Extensions') !== false) {
				$pageMenuTitle = '<span style="color:#f16600">' . $pageMenuTitle . '</span>';
			}

			$pageAdminScreenId = add_submenu_page(
				$adminFirstPage->getPageMenuSlug(),
				$this->adminPagesList[$i]->getPageBrowserTitle(),
				$pageMenuTitle,
				'manage_options',
				$this->adminPagesList[$i]->getPageMenuSlug(),
				array($this, 'renderPluginActiveAdminPage')
			);

			$this->adminPagesList[$i]->setAdminScreenId($pageAdminScreenId);
		}

	}

	/**
	 * @return MchBaseAdminPage | null
	 */
	public function getActivePage()
	{
		foreach($this->getRegisteredAdminPages() as $adminPage)
			if($adminPage->isActive())
				return $adminPage;

		return null;
	}

	public function initializeAdminPlugin()
	{
		$classInstance = $this;

		add_action('admin_enqueue_scripts', function() use ($classInstance){

			//$classInstance = $className::getInstance();
			if(!$classInstance->getActivePage())
				return;

			wp_enqueue_style('dashboard');
			wp_enqueue_script('dashboard');

			wp_add_inline_style( 'wp-admin',
				'
						.clearfix:after, div.mch-left-side-holder div.inside:after
						{
						    content: ".";
						    display: block;
						    height: 0;
						    clear: both;
						    visibility: hidden;
						    zoom: 1
						}
						div.mch-left-side-holder
						{
							width:100% !important;
						}

						div.mch-left-side-holder #normal-sortables:empty, div.mch-left-side-holder #advanced-sortables:empty, div.mch-left-side-holder #bottom-sortables:empty
						{
						    display:none;
						}
					'
			);

			if($classInstance->getActivePage()->shouldRenderModulesInSubTabs())
			{
				wp_add_inline_style( 'wp-admin',
					'
						ul.mch-module-tabs
						{
						    width: 150px;
						    float: left;
						    margin:0;
						}

						ul.mch-module-tabs a
						{
						    padding: 6px 0;
						    text-decoration: none;
						    display: block;
						    border: 1px solid #fff;
						    font-size: 14px;
						}

						ul.mch-module-tabs li.active a {
						    line-height: 25px;
						    z-index: 50 !important;
						    background-color: #F6FBFD;
						    border: 1px solid #E1E1E1;
						    border-right-color: #F6FBFD;
						    border-left: 2px solid #2EA2CC;
						    margin: 0 -1px 0 0;
						    width: 138px;
						    padding: 6px 0 6px 10px !important;
						}

						form.mch-module-settings-form.tabbed{
							margin-left:150px;
							border:1px solid #E1E1E1;
							padding:15px;
							padding-top:0;
						}

						form.mch-module-settings-form.tabbed .mch-settings-section-header{
							position:relative;
							margin:0 !important;
							padding:0 !important;
							line-height:37px;height:37px;
							border-bottom:1px solid #E1E1E1;
						}

						div.mch-settings-section-header h3{
margin: 0 !important;
padding: 0 !important;
float: left;
display: inline-block;
font-weight: 400;
						}

						form.mch-module-settings-form table.form-table{
							clear:right !important;
							margin-top:0 !important;
						}
					'
				);

			}



		}, PHP_INT_MAX);

	}




}