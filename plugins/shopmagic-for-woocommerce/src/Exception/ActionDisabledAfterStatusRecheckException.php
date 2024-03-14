<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Exception;

/**
 * Thrown by SupportsDeferredCheck event when event status is no loger valid.
 */
final class ActionDisabledAfterStatusRecheckException extends \RuntimeException implements ShopMagicException {

}
