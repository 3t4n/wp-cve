<?php

namespace ZPOS\Admin\Setting;

use ZPOS\Structure\ArrayObject;

abstract class Page
{
	public $capability;
	public $title;
	public $slug;
	public $parent;
	private $tabs;

	public function __construct($slug, $parent)
	{
		$this->slug = $slug;
		$this->parent = $parent;
		$this->tabs = $this->getTabs();
		add_action('admin_init', [$this, 'initTabs']);
	}

	public function getJson()
	{
		return json_encode(
			(new ArrayObject($this->tabs))
				->filter(function (Tab $tab) {
					return $tab->isVisible();
				})
				->map(function (Tab $tab) {
					return $tab->toJSON();
				})
				->get()
		);
	}

	public function initTabs()
	{
		$this->tabs = apply_filters(static::class . '::getTabs', $this->tabs, $this);
	}

	abstract function getTabs();

	public function submit_button()
	{
		ob_start();
		submit_button();
		$data = ob_get_contents();
		ob_end_clean();

		return json_encode($data);
	}

	public function render()
	{
		settings_errors($this->slug); ?>
		<form action="<?= admin_url('options.php') ?>" method="post">
			<div id="root"></div>
			<script>
				window.SETTING = <?= $this->getJson() ?>;
				window.SUBMIT_BUTTON = <?= $this->submit_button() ?>;
				window.renderPOS();
			</script>
		</form>
		<?php
	}

	protected function getUrl()
	{
		return add_query_arg('page', $this->slug, admin_url($this->parent));
	}

	public function isRequested()
	{
		$current_url =
			(is_ssl() ? 'https://' : 'http://') . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

		$admin_page = parse_url($this->getUrl());
		$current = parse_url($current_url);

		$diff = array_diff($admin_page, $current);

		if (isset($diff['path'])) {
			return false;
		}

		$admin_page_query = parse_url($this->getUrl(), PHP_URL_QUERY);
		$current_query = parse_url($current_url, PHP_URL_QUERY);

		parse_str($admin_page_query, $admin_page_query);
		parse_str($current_query, $current_query);

		$diff = array_diff($admin_page_query, $current_query);

		return count($diff) === 0;
	}
}
