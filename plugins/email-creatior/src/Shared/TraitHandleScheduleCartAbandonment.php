<?php

namespace WilokeEmailCreator\Shared;

use WilokeEmailCreator\Illuminate\Prefix\AutoPrefix;

trait TraitHandleScheduleCartAbandonment
{
	public function getScheduleKeyCartAbandonment()
	{
		return AutoPrefix::namePrefix('cart-abandonment');
	}

	public function getScheduleKeyCartAbandonmentWithUserGuest()
	{
		return AutoPrefix::namePrefix('cart-abandonment-user-guest');
	}

	public function setScheduleCartAbandonment($timestamp, $postID, $orderID): bool
	{
		settype($postID, 'int');
		settype($orderID, 'int');
		wp_schedule_single_event($timestamp, $this->getScheduleKeyCartAbandonment(), [$postID, $orderID]);
		return true;
	}

	public function setScheduleCartAbandonmentWithUserGuest($timestamp, $postID, $aProducts,$email): bool
	{
		settype($postID, 'int');
		settype($email, 'string');
		settype($aProducts, 'array');
		wp_schedule_single_event($timestamp, $this->getScheduleKeyCartAbandonmentWithUserGuest(),
			[$postID, $aProducts,$email]);
		return true;
	}
}
