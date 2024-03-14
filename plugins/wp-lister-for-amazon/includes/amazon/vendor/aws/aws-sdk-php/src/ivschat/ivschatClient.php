<?php
namespace Aws\ivschat;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Interactive Video Service Chat** service.
 * @method \Aws\Result createChatToken(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createChatTokenAsync(array $args = [])
 * @method \Aws\Result createRoom(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createRoomAsync(array $args = [])
 * @method \Aws\Result deleteMessage(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteMessageAsync(array $args = [])
 * @method \Aws\Result deleteRoom(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteRoomAsync(array $args = [])
 * @method \Aws\Result disconnectUser(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise disconnectUserAsync(array $args = [])
 * @method \Aws\Result getRoom(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getRoomAsync(array $args = [])
 * @method \Aws\Result listRooms(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listRoomsAsync(array $args = [])
 * @method \Aws\Result listTagsForResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listTagsForResourceAsync(array $args = [])
 * @method \Aws\Result sendEvent(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise sendEventAsync(array $args = [])
 * @method \Aws\Result tagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise tagResourceAsync(array $args = [])
 * @method \Aws\Result untagResource(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise untagResourceAsync(array $args = [])
 * @method \Aws\Result updateRoom(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateRoomAsync(array $args = [])
 */
class ivschatClient extends AwsClient {}
