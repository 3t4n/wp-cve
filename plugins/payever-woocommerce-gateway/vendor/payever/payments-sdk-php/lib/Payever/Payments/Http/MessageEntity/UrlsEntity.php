<?php

/**
 * PHP version 5.4 and 8
 *
 * @category  MessageEntity
 * @package   Payever\Payments
 * @author    payever GmbH <service@payever.de>
 * @copyright 2017-2023 payever GmbH
 * @license   MIT <https://opensource.org/licenses/MIT>
 * @link      https://docs.payever.org/shopsystems/api/getting-started
 */

namespace Payever\Sdk\Payments\Http\MessageEntity;

use Payever\Sdk\Core\Base\MessageEntity;

/**
 * This class represents Urls entity
 *
 * @method string getRedirect()
 * @method string getSuccess()
 * @method string getPending()
 * @method string getFailure()
 * @method string getCancel()
 * @method string getNotification()
 * @method self setRedirect(string $value)
 * @method self setSuccess(string $value)
 * @method self setPending(string $value)
 * @method self setFailure(string $value)
 * @method self setCancel(string $value)
 * @method self setNotification(string $value)
 */
class UrlsEntity extends MessageEntity
{
    /**
     * @var string
     */
    protected $redirect;

    /**
     * @var string
     */
    protected $success;

    /**
     * @var string
     */
    protected $pending;

    /**
     * @var string
     */
    protected $failure;

    /**
     * @var string
     */
    protected $cancel;

    /**
     * @var string
     */
    protected $notification;
}
