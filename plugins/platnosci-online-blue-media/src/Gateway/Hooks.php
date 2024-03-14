<?php

namespace Ilabs\BM_Woocommerce\Gateway;

use Ilabs\BM_Woocommerce\Gateway\Hooks\Payment_On_Account_Page;

class Hooks {

	public function init() {
		( new Payment_On_Account_Page() )->init();
	}
}

