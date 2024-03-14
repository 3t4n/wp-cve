<?php

namespace ZPOS\Admin\Setting;

abstract class Post
{
	private $tabs;
	protected $type;

	public function __construct($type)
	{
		$this->type = $type;

		add_action('edit_form_after_title', [$this, 'render']);
		add_action('save_post_' . $this->type, [$this, 'savePost']);
		add_action('admin_enqueue_scripts', [$this, 'enqueueScripts'], 0);
		add_action('admin_init', [$this, 'initTabs']);

		$this->tabs = $this->getTabs();
	}

	public function getJson(...$args)
	{
		return json_encode(
			array_map(function ($tab) use ($args) {
				/* @var PostTab $tab */
				return $tab->toJSON(...$args);
			}, $this->tabs)
		);
	}

	public function initTabs()
	{
		$this->tabs = apply_filters(static::class . '::getTabs', $this->tabs, $this);
	}

	abstract function getTabs();

	public function savePost()
	{
		global $post;
		array_map(function ($tab) use ($post) {
			/* @var PostTab $tab */
			return $tab->savePost($post);
		}, $this->tabs);
	}

	public function enqueueScripts()
	{
		if (get_current_screen()->id !== $this->type) {
			return;
		}
	}

	public function render()
	{
		if (get_current_screen()->id !== $this->type) {
			return;
		}
		global $post;
		$data = $this->getJson($post);
		?>
		<div id="root">
			<div class="loader"></div>
		</div>
		<script>
			window.SETTING = <?= $data; ?>;
			window.renderPOS();
		</script>
		<style>
			#root .loader {
				width: 100%;
				height: 100px;
				margin: 1rem 0;
				position: relative;
			}

			#root .loader:before, #root .loader:after {
				content: '';
				display: block;
				position: absolute;

				border-radius: 50%;
				border-width: 2px;
				border-style: solid;
				border-color: #666;
				border-left-color: transparent;
				animation: pos-setting-loader-spin 1s linear infinite;
			}

			#root .loader:before {
				width: 64px;
				height: 64px;
				top: calc(50% - 32px);
				left: calc(50% - 32px);
			}

			#root .loader:after {
				width: 40px;
				height: 40px;
				top: calc(50% - 20px);
				left: calc(50% - 20px);
				animation-direction: reverse;
			}

			@keyframes pos-setting-loader-spin {
				0% {
					transform: rotate(0deg);
				}

				100% {
					transform: rotate(360deg);
				}
			}

		</style>
		<?php
	}
}
