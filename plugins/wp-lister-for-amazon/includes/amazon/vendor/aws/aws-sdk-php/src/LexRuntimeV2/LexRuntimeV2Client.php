<?php
namespace Aws\LexRuntimeV2;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Lex Runtime V2** service.
 * @method \Aws\Result deleteSession(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteSessionAsync(array $args = [])
 * @method \Aws\Result getSession(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getSessionAsync(array $args = [])
 * @method \Aws\Result putSession(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise putSessionAsync(array $args = [])
 * @method \Aws\Result recognizeText(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise recognizeTextAsync(array $args = [])
 * @method \Aws\Result recognizeUtterance(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise recognizeUtteranceAsync(array $args = [])
 */
class LexRuntimeV2Client extends AwsClient {}
