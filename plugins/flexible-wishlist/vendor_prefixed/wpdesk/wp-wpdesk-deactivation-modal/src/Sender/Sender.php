<?php

namespace FlexibleWishlistVendor\WPDesk\DeactivationModal\Sender;

use FlexibleWishlistVendor\WPDesk\DeactivationModal\Exception\SenderRequestFailedException;
use FlexibleWishlistVendor\WPDesk\DeactivationModal\Model\RequestData;
interface Sender
{
    /**
     * @param RequestData $request_data .
     *
     * @return array
     */
    public function generate_request_data(\FlexibleWishlistVendor\WPDesk\DeactivationModal\Model\RequestData $request_data) : array;
    /**
     * @param RequestData $request_data .
     *
     * @return bool Request success status.
     *
     * @throws SenderRequestFailedException
     */
    public function send_request(\FlexibleWishlistVendor\WPDesk\DeactivationModal\Model\RequestData $request_data) : bool;
}
