<?php
namespace Aws\ChimeSDKMeetings;

use Aws\AwsClient;

/**
 * This client is used to interact with the **Amazon Chime SDK Meetings** service.
 * @method \Aws\Result batchCreateAttendee(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise batchCreateAttendeeAsync(array $args = [])
 * @method \Aws\Result batchUpdateAttendeeCapabilitiesExcept(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise batchUpdateAttendeeCapabilitiesExceptAsync(array $args = [])
 * @method \Aws\Result createAttendee(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createAttendeeAsync(array $args = [])
 * @method \Aws\Result createMeeting(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createMeetingAsync(array $args = [])
 * @method \Aws\Result createMeetingWithAttendees(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise createMeetingWithAttendeesAsync(array $args = [])
 * @method \Aws\Result deleteAttendee(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteAttendeeAsync(array $args = [])
 * @method \Aws\Result deleteMeeting(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise deleteMeetingAsync(array $args = [])
 * @method \Aws\Result getAttendee(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getAttendeeAsync(array $args = [])
 * @method \Aws\Result getMeeting(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise getMeetingAsync(array $args = [])
 * @method \Aws\Result listAttendees(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise listAttendeesAsync(array $args = [])
 * @method \Aws\Result startMeetingTranscription(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise startMeetingTranscriptionAsync(array $args = [])
 * @method \Aws\Result stopMeetingTranscription(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise stopMeetingTranscriptionAsync(array $args = [])
 * @method \Aws\Result updateAttendeeCapabilities(array $args = [])
 * @method \WPLab\GuzzeHttp\Promise\Promise updateAttendeeCapabilitiesAsync(array $args = [])
 */
class ChimeSDKMeetingsClient extends AwsClient {}
