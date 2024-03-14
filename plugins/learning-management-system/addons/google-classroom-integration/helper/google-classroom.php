<?php

use League\OAuth2\Client\Provider\Google;
use Masteriyo\Addons\GoogleClassroomIntegration\Models\GoogleClassroomSetting;

if ( ! function_exists( 'masteriyo_is_google_classroom_credentials_set' ) ) {
	/**
	 * Return true if the google classroom credentials are set.
	 * Doesn't validate credentials.
	 *
	 * @since 1.8.3
	 *
	 * @return boolean
	 */
	function masteriyo_is_google_classroom_credentials_set() {
		$setting = new GoogleClassroomSetting();

		$account_id    = $setting->get( 'account_id' );
		$client_id     = $setting->get( 'client_id' );
		$client_secret = $setting->get( 'client_secret' );

		return ! ( empty( $account_id ) || empty( $client_id ) || empty( $client_secret ) );
	}
}

if ( ! function_exists( 'masteriyo_get_google_classroom_credits' ) ) {
	/**
	 * Gets the data from the google classroom integration setting.
	 *
	 * @since 1.8.3
	 */
	function masteriyo_get_google_classroom_credits( $key = null ) {
		return masteriyo( 'addons.google-classroom-integration.setting' )->get( $key );
	}
}


if ( ! function_exists( 'masteriyo_get_google_classroom_course_meta' ) ) {
	/**
	 * get meta data for google classroom addon
	 *
	 * @param Masteriyo\Models\Course $course Course object.
	 * @since 1.8.3
	 */
	function masteriyo_get_google_classroom_course_meta( $course ) {

		return array(
			'google_classroom_enrollment_code' => get_post_meta( $course->get_id(), '_google_classroom_enrollment_code', true ),
			'google_classroom_course_id'       => get_post_meta( $course->get_id(), '_google_classroom_course_id', true ),
			'google_course_url'                => get_post_meta( $course->get_id(), '_google_course_url', true ),
		);

	}
}

if ( ! function_exists( 'masteriyo_is_google_classroom_course' ) ) {
	/**
	 * check google classroom course exists or not.
	 *
	 * since 1.8.3
	 */
	function masteriyo_is_google_classroom_course( $course ) {
		if ( get_post_meta( $course->get_id(), '_google_classroom_course_id', true ) ) {
			return true;
		}

		return false;
	}
}


if ( ! function_exists( 'masteriyo_google_classroom_course_data_insertion' ) ) {
	/**
	 * accessing the google classroom data based on the token and provider and put it in database.
	 *
	 * since 1.8.3
	 */
	function masteriyo_google_classroom_course_data_insertion( $access_token, $google_provider ) {

		$request = $google_provider->getAuthenticatedRequest(
			'GET',
			'https://classroom.googleapis.com/v1/courses',
			$access_token
		);

		$response      = $google_provider->getResponse( $request );
		$response_data = (string) $response->getBody();
		$object_data   = json_decode( $response_data );
		$object_data   = json_decode( wp_json_encode( $object_data ), true );

		foreach ( $object_data['courses'] as $course ) {
			$course_id         = $course['id'];
			$students_request  = $google_provider->getAuthenticatedRequest(
				'GET',
				"https://classroom.googleapis.com/v1/courses/{$course_id}/students",
				$access_token
			);
			$students_response = $google_provider->getResponse( $students_request );
			$students_data     = json_decode( (string) $students_response->getBody() ) ?? array();
			if ( isset( $students_data->students ) ) {
				$students     = json_decode( wp_json_encode( $students_data->students ), true );
				$all_students = array();
				foreach ( $students as $student ) {
					$student_data   = array(
						'id'            => $student['profile']['id'],
						'email_address' => $student['profile']['emailAddress'],
						'full_name'     => $student['profile']['name']['fullName'],
						'family_name'   => $student['profile']['name']['familyName'],
						'given_name'    => $student['profile']['name']['givenName'],
					);
					$all_students[] = $student_data;
				}
			}

			$course['students'] = $all_students ?? array();
			$data['courses'][]  = $course;

		}
		return $data;
	}
}

if ( ! function_exists( 'create_google_client' ) ) {
	/**
	 * creates the google client based on the google classroom setting data,
	 *  this is the basic for validating and accessing access token.
	 *
	 * @since 1.8.3
	 * @param $google_database_info google classroom setting data.
	 */
	function create_google_client( $google_database_info ) {
		$scopes   = array(
			'https://www.googleapis.com/auth/classroom.courses.readonly',
			'https://www.googleapis.com/auth/classroom.rosters.readonly',
			'https://www.googleapis.com/auth/classroom.profile.emails',
		);
		$provider = new Google(
			array(
				'clientId'     => $google_database_info['client_id'],
				'clientSecret' => $google_database_info['client_secret'],
				'redirectUri'  => home_url( '/wp-admin/admin.php?page=masteriyo' ),
				'scopes'       => $scopes,
				'accessType'   => 'offline',
				'prompt'       => 'consent',
			)
		);
		return $provider;
	}
}

