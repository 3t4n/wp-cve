<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Exception;

/**
 * Thrown by any customer provider when customer cannot be provided.
 */
final class CannotProvideCustomerException extends \RuntimeException implements ShopMagicException {

}
