<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/13/18
 * Time: 4:26 PM
 */

namespace BeeketingConnect_beeketing_woocommerce\Common\Data\Model;

// @codingStandardsIgnoreStart
class Count
{
    /**
     * @var int
     */
    public $count;

    /**
     * Count constructor.
     * @param int $count
     */
    public function __construct($count = null)
    {
        $this->count = (int)$count;
    }
}
// @codingStandardsIgnoreEnd
