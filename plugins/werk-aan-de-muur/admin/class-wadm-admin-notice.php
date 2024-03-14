<?php

class Wadm_Admin_Notice
{
	protected $_message;

	protected $_type;

	protected $_isDismissable = false;

	public function __construct($message, $type = 'success')
	{
		$this->_message = $message;
		$this->_type = $type;
	}

	public function notice()
	{
		echo '
			<div class="notice notice-' . $this->_type . ($this->_isDismissable ? ' is-dismissible' : '') . '">
				<p>' . $this->_message . '</p>
			</div>
		';
	}

	public function makeDismissable()
	{
		$this->_isDismissable = true;
	}

	public function add()
	{
		add_action('admin_notices', array($this, 'notice'));
	}
}