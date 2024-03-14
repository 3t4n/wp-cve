<?php

namespace Photonic_Plugin\Platforms;

use Photonic_Plugin\Components\Album_List;
use Photonic_Plugin\Components\Error;
use Photonic_Plugin\Components\Pagination;
use Photonic_Plugin\Components\Photo_List;
use Photonic_Plugin\Core\Photonic;
use Photonic_Plugin\Components\Album;
use Photonic_Plugin\Components\Photo;

require_once 'OAuth2.php';
require_once 'Level_One_Module.php';
require_once 'Level_Two_Module.php';
require_once 'Pageable.php';

/**
 * Fetches photos from a user's Google Photos account.
 * Lacks support for dual title / description fields, doesn't provide download URLs, and video support is ambiguous.
 */
class Google_Photos extends OAuth2 implements Level_One_Module, Level_Two_Module, Pageable {
	public $error_date_format;
	public $refresh_token_valid;
	private static $instance = null;

	protected function __construct() {
		parent::__construct();
		global $photonic_google_client_id, $photonic_google_client_secret, $photonic_google_refresh_token;

		// if (!empty($photonic_google_use_own_keys) || (!empty($photonic_google_client_id) && !empty($photonic_google_client_secret))) {
		if (!empty($photonic_google_client_id) && !empty($photonic_google_client_secret)) {
			$this->client_id     = trim($photonic_google_client_id);
			$this->client_secret = trim($photonic_google_client_secret);
		}

		/*
		 *
		elseif (empty($photonic_google_use_own_keys)) {
					$this->client_id = '';
					$this->client_secret = '';
				}
		*/

		$this->provider            = 'google';
		$this->oauth_version       = '2.0';
		$this->response_type       = 'code';
		$this->scope               = 'https://www.googleapis.com/auth/photoslibrary.readonly';
		$this->link_lightbox_title = false; // empty($photonic_google_disable_title_link);

		// Documentation
		$this->doc_links = [
			'general' => 'https://aquoid.com/plugins/photonic/google-photos/',
			'photos'  => 'https://aquoid.com/plugins/photonic/google-photos/photos/',
			'albums'  => 'https://aquoid.com/plugins/photonic/google-photos/albums/',
		];

		$this->error_date_format = esc_html__('Dates must be entered in the format Y/M/D where Y is from 0 to 9999, M is from 0 to 12 and D is from 0 to 31. You entered %s.', 'photonic');
		$this->oauth_done        = false;
		$this->authenticate($photonic_google_refresh_token);
	}

	/**
	 * Main function that fetches the images associated with the shortcode.
	 *
	 * @param array $attr
	 * @return array
	 */
	public function get_gallery_images($attr = []): array {
		global $photonic_google_refresh_token, $photonic_google_media, $photonic_google_title_caption;
		$this->push_to_stack('Get Gallery Images');

		$attr = array_merge(
			$this->common_parameters,
			[
				'caption'         => $photonic_google_title_caption,
				'thumb_size'      => '150',
				'main_size'       => '1600',
				'tile_size'       => '1600',
				'crop_thumb'      => 'crop',

				// Google ...
				'count'           => 100,
				'media'           => $photonic_google_media,
				'video_size'      => 'dv',
				'date_filters'    => '',
				'content_filters' => '',
				'access'          => 'all',
			],
			$attr
		);
		$attr = array_map('trim', $attr);

		$attr['overlay_size']       = empty($attr['overlay_size']) ? $attr['thumb_size'] : $attr['overlay_size'];
		$attr['overlay_video_size'] = empty($attr['overlay_video_size']) ? $attr['video_size'] : $attr['overlay_video_size'];
		$attr['overlay_crop']       = empty($attr['overlay_crop']) ? $attr['crop_thumb'] : $attr['overlay_crop'];

		if (empty($this->client_id)) {
			$this->pop_from_stack();
			return [new Error(esc_html__('Google Photos Client ID not defined.', 'photonic') . Photonic::doc_link($this->doc_links['general']))];
		}
		if (empty($this->client_secret)) {
			$this->pop_from_stack();
			return [new Error(esc_html__('Google Photos Client Secret not defined.', 'photonic') . Photonic::doc_link($this->doc_links['general']))];
		}
		if (empty($photonic_google_refresh_token)) {
			$this->pop_from_stack();
			return [new Error(sprintf(esc_html__('Google Photos Refresh Token not defined. Please authenticate from %s.', 'photonic'), '<em>Photonic &rarr; Authentication</em>') . Photonic::doc_link($this->doc_links['general']))];
		}
		if (!$this->refresh_token_valid) {
			$this->pop_from_stack();
			$error = sprintf(esc_html__('Google Photos Refresh Token invalid. Please authenticate from %s.', 'photonic'), '<em>Photonic &rarr; Authentication</em>');
			if (!empty($this->auth_error)) {
				$error .= '<br/>' . sprintf(esc_html__('Error encountered during authentication: %s', 'photonic'), '<br/><pre>' . $this->auth_error . '</pre>');
			}
			return [new Error($error . Photonic::doc_link($this->doc_links['general']))];
		}

		if (empty($attr['view'])) {
			$this->pop_from_stack();
			return [new Error(sprintf(esc_html__('The %s parameter is mandatory for the shortcode.', 'photonic'), '<code>view</code>'))];
		}

		$query_urls = [];
		if ('albums' === $attr['view']) {
			$additional = [];
			if (!empty($attr['count'])) {
				$additional['pageSize'] = intval($attr['count']) > 50 ? 50 : intval($attr['count']);
			}

			if (!empty($attr['next_token'])) {
				$additional['pageToken'] = $attr['next_token'];
			}

			$access = $this->access_all_or_shared($attr);
			if ($access['shared']) {
				$query_urls['https://photoslibrary.googleapis.com/v1/sharedAlbums'] = ['GET' => $additional];
			}
			if ($access['self']) {
				$query_urls['https://photoslibrary.googleapis.com/v1/albums'] = ['GET' => $additional];
			}
		}
		elseif ('photos' === $attr['view'] || 'shared-photos' === $attr['view']) {
			$additional = [];
			if (!empty($attr['album_id'])) {
				$additional['albumId'] = $attr['album_id'];
			}
			else {
				$filters = [];

				$date_parameter  = [];
				$range_parameter = [];
				if (!empty($attr['date_filters'])) {
					/*
					 * Structure of $attr['date_filters']: comma-separated list of dates or date ranges.
					 * Each date is represented by Y/M/D, where 0 <= Y <= 9999, 0 <= M <= 12, 0 <= D < 31
					 * Each range is represented as Y/M/D-Y/M/D
					 */
					$date_filters = explode(',', trim($attr['date_filters']));
					foreach ($date_filters as $date_filter) {
						$dates = explode('-', trim($date_filter));
						if (count($dates) > 2) {
							$dates = array_slice($dates, 0, 2);
						}
						$range = [];
						foreach ($dates as $idx => $date) {
							$date_parts = explode('/', trim($date));
							if (count($date_parts) !== 3) {
								$this->pop_from_stack();
								return [new Error(sprintf($this->error_date_format, $date))];
							}

							if (!is_numeric($date_parts[0]) || $date_parts[0] > 9999 || $date_parts[0] < 0 ||
								!is_numeric($date_parts[1]) || $date_parts[1] > 12 || $date_parts[1] < 0 ||
								!is_numeric($date_parts[2]) || $date_parts[2] > 31 || $date_parts[2] < 0) {
								$this->pop_from_stack();
								return [new Error(sprintf($this->error_date_format, $date))];
							}

							$date_object = [
								'year'  => intval($date_parts[0]),
								'month' => intval($date_parts[1]),
								'day'   => intval($date_parts[2]),
							];

							if (count($dates) === 1) {
								$date_parameter[] = $date_object;
							}
							elseif (0 === $idx) {
								$range['startDate'] = $date_object;
							}
							else {
								$range['endDate'] = $date_object;
							}
						}
						if (!empty($range)) {
							$range_parameter[] = $range;
						}
					}

					$date_filter_parameter = [];
					if (!empty($date_parameter)) {
						$date_filter_parameter['dates'] = $date_parameter;
					}
					if (!empty($range_parameter)) {
						$date_filter_parameter['ranges'] = $range_parameter;
					}
					if (!empty($date_filter_parameter)) {
						$filters['dateFilter'] = $date_filter_parameter;
					}
				}

				if (!empty($attr['content_filters'])) {
					$valid_filters = [
						'NONE'         => 'Default content category. This category is ignored if any other category is also listed.',
						'LANDSCAPES'   => 'Media items containing landscapes.',
						'RECEIPTS'     => 'Media items containing receipts.',
						'CITYSCAPES'   => 'Media items containing cityscapes.',
						'LANDMARKS'    => 'Media items containing landmarks.',
						'SELFIES'      => 'Media items that are selfies.',
						'PEOPLE'       => 'Media items containing people.',
						'PETS'         => 'Media items containing pets.',
						'WEDDINGS'     => 'Media items from weddings.',
						'BIRTHDAYS'    => 'Media items from birthdays.',
						'DOCUMENTS'    => 'Media items containing documents.',
						'TRAVEL'       => 'Media items taken during travel.',
						'ANIMALS'      => 'Media items containing animals.',
						'FOOD'         => 'Media items containing food.',
						'SPORT'        => 'Media items from sporting events.',
						'NIGHT'        => 'Media items taken at night.',
						'PERFORMANCES' => 'Media items from performances.',
						'WHITEBOARDS'  => 'Media items containing whiteboards.',
						'SCREENSHOTS'  => 'Media items that are screenshots.',
						'UTILITY'      => 'Media items that are considered to be utility. These include, but are not limited to documents, screenshots, whiteboards etc.',
					];

					/*
					 * Structure of content_filters: C1,C2,-C3,C4,-C5.
					 * The filters are specified as a comma-separated list.
					 * A "-" before the filter's name indicates that the filter should be excluded rather than included.
					 */
					$content_filters = explode(',', $attr['content_filters']);
					$include         = $exclude = [];
					foreach ($content_filters as $content_filter) {
						$content_filter = strtoupper($content_filter);
						if (stripos($content_filter, '-') === 0 && array_key_exists(substr($content_filter, 1), $valid_filters)) {
							$exclude[] = substr($content_filter, 1);
						}
						elseif (array_key_exists($content_filter, $valid_filters)) {
							$include[] = $content_filter;
						}
					}

					$content_filter_parameter = [];
					if (!empty($include)) {
						$content_filter_parameter['includedContentCategories'] = $include;
					}
					if (!empty($exclude)) {
						$content_filter_parameter['excludedContentCategories'] = $exclude;
					}
					if (!empty($content_filter_parameter)) {
						$filters['contentFilter'] = $content_filter_parameter;
					}
				}

				$media_filters          = explode(',', $attr['media']);
				$media_filter_parameter = [];
				if (in_array('all', $media_filters, true)) {
					$media_filter_parameter[] = 'ALL_MEDIA';
				}
				elseif (in_array('photos', $media_filters, true)) {
					$media_filter_parameter[] = 'PHOTO';
				}
				elseif (in_array('videos', $media_filters, true)) {
					$media_filter_parameter[] = 'VIDEO';
				}

				if (!empty($media_filter_parameter)) {
					$filters['mediaTypeFilter'] = ['mediaTypes' => $media_filter_parameter];
				}

				if (!empty($filters)) {
					$additional['filters'] = $filters;
				}
			}

			if (!empty($attr['count']) || !empty($attr['photo_count'])) {
				$additional['pageSize'] = !empty($attr['photo_count']) ? $attr['photo_count'] : $attr['count'];
				$additional['pageSize'] = intval($additional['pageSize']) > 100 ? 100 : intval($additional['pageSize']);
			}
			if (!empty($attr['next_token'])) {
				$additional['pageToken'] = $attr['next_token'];
			}

			$query_urls['https://photoslibrary.googleapis.com/v1/mediaItems:search'] = ['POST' => $additional];
		}

		$out = $this->make_call($query_urls, $attr);
		$this->pop_from_stack();

		if (!empty($this->stack_trace[$this->gallery_index])) {
			$out[] = $this->stack_trace[$this->gallery_index];
		}

		return $out;
	}

	/**
	 * Makes calls to Google with the shortcode parameters.
	 *
	 * @param $query_urls - The URLs to be queried
	 * @param $attr - The shortcode attributes
	 * @return array
	 */
	private function make_call($query_urls, $attr): array {
		global $photonic_google_refresh_token;
		$this->push_to_stack('Making calls');

		$incremented = false;
		$components  = [];
		$access      = $this->access_all_or_shared($attr);

		$all_ids = [];
		foreach ($query_urls as $query_url => $method_and_args) {
			$this->push_to_stack("Query $query_url");
			if ($access['self'] && 'https://photoslibrary.googleapis.com/v1/sharedAlbums' === $query_url) {
				$defer = true;
			}
			else {
				$defer = false;
			}

			if (!empty($photonic_google_refresh_token) && !empty($this->access_token)) {
				$query_url = add_query_arg('access_token', $this->access_token, $query_url);
			}

			foreach ($method_and_args as $method => $args) {
				$this->push_to_stack('Sending request');
				if (empty($args['filters'])) {
					$call_args              = [];
					$call_args['method']    = $method;
					$call_args['body']      = $args;
					$call_args['sslverify'] = PHOTONIC_SSL_VERIFY;
					$response               = wp_remote_request($query_url, $call_args);
				}
				else {
					$headers   = [];
					$headers['Accept'] = 'application/json';
					$headers['Content-Type'] = 'application/json';

					$response = wp_remote_request(
						$query_url,
						[
							'method'      => 'POST',
							'headers'     => $headers,
							'httpversion' => '1.0',
							'body'        => wp_json_encode($args),
						]
					);
				}
				$this->pop_from_stack();

				if (!is_wp_error($response)) {
					$this->push_to_stack('Processing Response');
					$body = wp_remote_retrieve_body($response);

					if (!$incremented) {
						$incremented = true;
					}

					$output = $this->process_response($body, $attr, $defer, $all_ids);

					if (!is_null($output)) {
						if ($defer && is_array($output)) {
							foreach ($output as $object) {
								$all_ids[] = $object->id;
							}
						}
						else {
							$components[] = $output;
						}
					}

					$this->pop_from_stack();
				}
				else {
					$this->pop_from_stack(); // "Query $query_url"
					$this->pop_from_stack(); // 'Making calls'
					return [new Error($response->get_error_message())];
				}
			}
			$this->pop_from_stack();
		}

		$this->pop_from_stack();
		return $components;
	}

	/**
	 * @param $body
	 * @param $short_code
	 * @param bool|false $deferred
	 * @param array $remove
	 * @return mixed
	 */
	private function process_response($body, $short_code, $deferred = false, $remove = []) {
		global $photonic_google_photo_title_display, $photonic_google_photos_per_row_constraint, $photonic_gallery_template_page,
			$photonic_google_photos_constrain_by_count, $photonic_google_photo_pop_title_display, $photonic_google_hide_album_photo_count_display;

		if (!empty($body)) {
			$body            = json_decode($body);
			$row_constraints = ['constraint-type' => $photonic_google_photos_per_row_constraint, 'count' => $photonic_google_photos_constrain_by_count];
			$display         = $short_code['display'];
			if (isset($body->albums) || isset($body->sharedAlbums)) {
				$albums = $body->albums ?? $body->sharedAlbums;

				$pagination = $this->get_pagination($body, $short_code);
				$dummy_options = [];
				$albums     = $this->build_level_2_objects($albums, $short_code, $remove, $dummy_options, $pagination);

				global $photonic_google_photos_layout_engine;
				$layout_engine = $short_code['layout_engine'] ?? $photonic_google_photos_layout_engine;
				if ('css' === $layout_engine) {
					$this->update_thumbnail_information($albums, $short_code);
				}

				if ($deferred) {
					return $albums;
				}

				$album_list = new Album_List($short_code);

				$album_list->albums                = $albums;
				$album_list->row_constraints       = $row_constraints;
				$album_list->type                  = 'albums';
				$album_list->singular_type         = 'album';
				$album_list->title_position        = $photonic_google_photo_title_display;
				$album_list->level_1_count_display = !empty($photonic_google_hide_album_photo_count_display);
				$album_list->pagination            = $pagination;
				$album_list->album_opens_gallery   = ('page' === $short_code['popup'] && !empty($photonic_gallery_template_page) && is_string(get_post_status($photonic_gallery_template_page)));

				return $album_list;
			}
			elseif (isset($body->mediaItems)) {
				if ('local' === $display) {
					$title_position = $photonic_google_photo_title_display;
				}
				else {
					$row_constraints = ['constraint-type' => 'padding'];
					$title_position  = $photonic_google_photo_pop_title_display;
				}

				$pagination = $this->get_pagination($body, $short_code);

				$photos = $body->mediaItems;
				$photos = $this->build_level_1_objects($photos, $short_code);

				$photo_list                  = new Photo_List($short_code);
				$photo_list->photos          = $photos;
				$photo_list->title_position  = $title_position;
				$photo_list->row_constraints = $row_constraints;
				$photo_list->parent          = 'album';
				$photo_list->pagination      = $pagination;

				return $photo_list;
			}
			elseif (isset($body->error)) {
				$err = esc_html__('Failed to get data. Error:', 'photonic') . "<br/><code>\n";
				$err .= $body->error->message;
				$err .= "</code><br/>\n";

				return new Error($err);
			}
		}
		else {
			$err = esc_html__('Failed to get data. Error:', 'photonic') . "<br/><code>\n";
			$err .= $body;
			$err .= "</code><br/>\n";

			return new Error($err);
		}

		return null;
	}

	public function build_level_1_objects($response, array $short_code, $module_parameters = [], $options = []): array {
		$objects = [];
		$sizes   = [
			'thumb_size' => [
				'size' => $short_code['thumb_size'],
				'crop' => $short_code['crop_thumb'],
			],
			'tile_size'  => [
				'size' => $short_code['tile_size'],
			],
			'main_size'  => [
				'size' => $short_code['main_size'],
			]
		];

		foreach ($response as $photo) {
			$photonic_photo = new Photo();

			$is_video = false;
			if (!empty($photo->mediaMetadata)) {
				if (!empty($photo->mediaMetadata->video)) {
					$is_video = true;
				}

				if (!empty($photo->mediaMetadata->creationTime)) {
					$photonic_photo->taken_on = sanitize_text_field($photo->mediaMetadata->creationTime);
				}
			}

			$photonic_photo->thumb_size = $photonic_photo->tile_size = $photonic_photo->main_size = [];

			$media     = explode(',', $short_code['media']);
			$videos_ok = in_array('videos', $media, true) || in_array('all', $media, true);
			$photos_ok = in_array('photos', $media, true) || in_array('all', $media, true);
			if (($is_video && !$videos_ok) || (!$is_video && !$photos_ok)) {
				continue;
			}

			$photonic_photo->id         = $photo->id;
			$photonic_photo->thumbnail  = esc_url($photo->baseUrl . "=w{$short_code['thumb_size']}-h{$short_code['thumb_size']}" . ('crop' === $short_code['crop_thumb'] ? '-c' : ''));
			$photonic_photo->tile_image = esc_url($photo->baseUrl . "=w{$short_code['tile_size']}-h{$short_code['tile_size']}");
			$photonic_photo->main_image = esc_url($photo->baseUrl . "=w{$short_code['main_size']}-h{$short_code['main_size']}");

			$this->calculate_sizes($photo, $photonic_photo, $sizes);

			if ($is_video) {
				$photonic_photo->video = esc_url($photo->baseUrl . "={$short_code['video_size']}");
				$photonic_photo->mime  = $photo->mimeType ?: 'video/mp4';
			}
			else {
				$photonic_photo->download = esc_url($photonic_photo->main_image . '-d');
			}

			if (!isset($photo->productUrl)) {
				$photonic_photo->main_page = esc_url($photonic_photo->main_image);
			}
			else {
				$photonic_photo->main_page = esc_url($photo->productUrl);
			}

			if (!empty($photo->description)) {
				$photonic_photo->title = wp_kses_post($photo->description);
			}
			else {
				$photonic_photo->title = '';
			}

			$photonic_photo->alt_title   = $photonic_photo->title;
			$photonic_photo->description = $photonic_photo->title;

			$objects[] = $photonic_photo;
		}

		return $objects;
	}

	/**
	 * @param $objects_or_response
	 * @param array $short_code
	 * @param array $remove
	 * @param array $options
	 * @param Pagination|null $pagination
	 * @return array
	 */
	public function build_level_2_objects($objects_or_response, array $short_code, array $remove = [], array &$options = [], Pagination &$pagination = null): array {
		$filter    = $short_code['filter'];
		$filters   = empty($filter) ? [] : explode(',', $filter);
		$processed = [];

		$objects = [];
		foreach ($objects_or_response as $album) {
			if (!empty($filters) && ((!in_array($album->id, $filters, true) && strtolower($short_code['filter_type']) !== 'exclude') ||
					(in_array($album->id, $filters, true) && strtolower($short_code['filter_type']) === 'exclude'))) {
				continue;
			}

			if (in_array($album->id, $remove, true)) {
				continue;
			}

			$object = $this->process_album($album, $short_code);
			if (!empty($object)) {
				$objects[]   = $object;
				$processed[] = $album->id;
			}
		}

		global $photonic_google_chain_queries;
		if (!empty($pagination->next_token) && strtolower($short_code['filter_type']) !== 'exclude' && !empty($filters) && count($processed) < count($filters) && !empty($photonic_google_chain_queries)) {
			$additional = [];
			if (!empty($short_code['count'])) {
				$additional['pageSize'] = intval($short_code['count']) > 50 ? 50 : intval($short_code['count']);
			}

			$additional['pageToken'] = $pagination->next_token;

			$access = $this->access_all_or_shared($short_code);
			if ($access['shared']) {
				$query_url = 'https://photoslibrary.googleapis.com/v1/sharedAlbums';
			}
			if ($access['self']) {
				$query_url = 'https://photoslibrary.googleapis.com/v1/albums';
			}

			if (!empty($query_url)) {
				global $photonic_google_refresh_token;
				if (!empty($photonic_google_refresh_token) && !empty($this->access_token)) {
					$query_url = add_query_arg('access_token', $this->access_token, $query_url);
				}

				$call_args              = [];
				$call_args['method']    = 'GET';
				$call_args['body']      = $additional;
				$call_args['sslverify'] = PHOTONIC_SSL_VERIFY;
				$response               = wp_remote_request($query_url, $call_args);
				if (!is_wp_error($response)) {
					$body         = wp_remote_retrieve_body($response);
					$body         = json_decode($body);
					$inner_albums = $body->albums ?? $body->sharedAlbums;
					if (!empty($body->nextPageToken)) {
						$pagination->next_token = $body->nextPageToken;
					}
					else {
						$pagination = new Pagination();
					}

					$remaining            = array_diff($filters, $processed);
					$remaining            = implode(',', $remaining);
					$inner_code           = $short_code;
					$inner_code['filter'] = $remaining;
					$inner                = $this->build_level_2_objects($inner_albums, $inner_code, $remove, $options, $pagination);
					$objects              = array_merge($objects, $inner);
				}
			}
		}

		return $objects;
	}

	private function calculate_sizes($photo, &$object, $sizes) {
		if (!empty($photo->mediaMetadata->width) && !empty($photo->mediaMetadata->height)) {
			$original_width  = $photo->mediaMetadata->width;
			$original_height = $photo->mediaMetadata->height;
			$aspect_ratio    = $original_width / $original_height;

			foreach ($sizes as $size => $size_value) {
				if (is_numeric($size_value['size']) && max($original_width, $original_height) >= $size_value['size']) {
					if (!empty($size_value['crop'])) {
						$object->{$size} = [
							'w' => $size_value['size'],
							'h' => $size_value['size'],
						];
					}
					else {
						$object->{$size} = [
							'w' => ($aspect_ratio > 1) ? $size_value['size'] : ($size_value['size'] * $aspect_ratio),
							'h' => ($aspect_ratio < 1) ? $size_value['size'] : ($size_value['size'] / $aspect_ratio),
						];
					}
				}
				elseif (is_numeric($size_value['size'])) {
					$object->main_size = [
						'w' => $original_width,
						'h' => $original_height,
					];
				}
			}
		}
	}

	private function update_thumbnail_information(&$albums, array $short_code) {
		$thumbnail_albums = [];
		$thumbnail_ids = [];
		$additional = [];
		foreach ($albums as $album) {
			$thumbnail_albums[$album->thumbnail_id] = $album;
			$thumbnail_ids[] = $album->thumbnail_id;
		}

		$query_url = 'https://photoslibrary.googleapis.com/v1/mediaItems:batchGet';
		global $photonic_google_refresh_token;
		if (!empty($photonic_google_refresh_token) && !empty($this->access_token)) {
			$query_url = add_query_arg('access_token', $this->access_token, $query_url);
		}

		$additional['mediaItemIds'] = $thumbnail_ids;

		$headers   = [];
		$headers['Accept'] = 'application/json';
		$headers['Content-Type'] = 'application/json';

		$args = [
			'method'      => 'GET',
			'headers'     => $headers,
			'sslverify'   => PHOTONIC_SSL_VERIFY,
			'httpversion' => '1.0',
		];

		$media_item_query = '';
		foreach ($thumbnail_ids as $thumbnail_id) {
			$media_item_query .= '&mediaItemIds=' . $thumbnail_id;
		}

		$query_url .= $media_item_query;

		$response = wp_remote_request($query_url, $args);

		if (!is_wp_error($response)) {
			$body = wp_remote_retrieve_body($response);
			$body = json_decode($body);
			if (!empty($body->mediaItemResults) && is_array($body->mediaItemResults)) {
				$media_items = $body->mediaItemResults;

				$sizes = [
					'thumb_size' => [
						'size' => $short_code['thumb_size'],
						'crop' => $short_code['crop_thumb'],
					],
					'tile_size'  => [
						'size' => $short_code['tile_size'],
					],
				];

				foreach ($media_items as $media_item) {
					if (!empty($media_item->mediaItem) && !empty($thumbnail_albums[$media_item->mediaItem->id])) {
						$album = $thumbnail_albums[$media_item->mediaItem->id];
						$this->calculate_sizes($media_item->mediaItem, $album, $sizes);
					}
				}
			}
		}
	}

	/**
	 * @param $album
	 * @param $short_code
	 * @return Album
	 */
	private function process_album($album, $short_code) {
		if (empty($album->coverPhotoBaseUrl)) {
			return null;
		}

/*		$sizes = [
			'thumb_size' => [
				'size' => $short_code['thumb_size'],
				'crop' => $short_code['crop_thumb'],
			],
			'tile_size'  => [
				'size' => $short_code['tile_size'],
			],
		];*/

		$photonic_album = new Album();

		$internal_short_code           = $short_code;
		$internal_short_code['layout'] = empty($short_code['photo_layout']) ? $short_code['layout'] : $short_code['photo_layout'];
		unset($internal_short_code['filter']);
		unset($internal_short_code['filter_type']);
		unset($internal_short_code['next_token']);
		$internal_short_code['view']     = 'photos';
		$internal_short_code['album_id'] = $album->id;

		$photonic_album->id = "{$album->id}";

		$photonic_album->thumbnail  = esc_url($album->coverPhotoBaseUrl . "=w{$short_code['thumb_size']}-h{$short_code['thumb_size']}" . ('crop' === $short_code['crop_thumb'] ? '-c' : ''));
		$photonic_album->tile_image = esc_url($album->coverPhotoBaseUrl . "=w{$short_code['tile_size']}-h{$short_code['tile_size']}");
		$photonic_album->thumbnail_id = $album->coverPhotoMediaItemId;

//		$this->calculate_sizes($album, $photonic_album, $sizes);

		$photonic_album->main_page = '';

		$photonic_album->title   = wp_kses_post($album->title);
		$photonic_album->counter = empty($album->totalMediaItems) ? $album->mediaItemsCount : $album->totalMediaItems;

		global $photonic_gallery_template_page;
		if ('page' === $short_code['popup'] && !empty($photonic_gallery_template_page) && is_string(get_post_status($photonic_gallery_template_page))) {
			$photonic_album->gallery_url = $this->get_gallery_url(
				$internal_short_code,
				[
					'title' => $photonic_album->title,
				]
			);
		}

		return $photonic_album;
	}

	private function access_all_or_shared($short_code): array {
		$all_or_shared = [];
		$access        = explode(',', $short_code['access']);
		if (empty($access) || (in_array('shared', $access, true) && in_array('not-shared', $access, true)) || in_array('all', $access, true)) {
			$all_or_shared['self']   = true;
			$all_or_shared['shared'] = false;
		}
		elseif (count($access) === 1 && in_array('not-shared', $access, true)) {
			$all_or_shared['self']   = true;
			$all_or_shared['shared'] = true;
		}
		elseif (count($access) === 1 && in_array('shared', $access, true)) {
			$all_or_shared['self']   = false;
			$all_or_shared['shared'] = true;
		}
		return $all_or_shared;
	}

	public function authentication_URL() {
		return 'https://accounts.google.com/o/oauth2/auth';
	}

	public function access_token_URL() {
		return 'https://accounts.google.com/o/oauth2/token';
	}

	protected function set_token_validity($validity) {
		$this->refresh_token_valid = $validity;
	}

	public function execute_helper($args = []): string {
		if (empty($args['album_type']) || !in_array($args['album_type'], ['self', 'shared'], true)) {
			$album_type = 'self';
		}
		else {
			$album_type = $args['album_type'];
		}
		$query_url  = ('self' === $album_type)
			? 'https://photoslibrary.googleapis.com/v1/albums'
			: 'https://photoslibrary.googleapis.com/v1/sharedAlbums';
		$parameters = [
			'access_token' => $this->access_token,
			'pageSize'     => 50,
		];
		if (!empty($args['nextPageToken'])) {
			$parameters['pageToken'] = sanitize_text_field($args['nextPageToken']);
		}

		$call_args              = [];
		$call_args['method']    = 'GET';
		$call_args['body']      = $parameters;
		$call_args['sslverify'] = PHOTONIC_SSL_VERIFY;
		$response               = wp_remote_request($query_url, $call_args);

		if (!is_wp_error($response)) {
			if (isset($response['response']) && isset($response['response']['code'])) {
				if (200 === $response['response']['code']) {
					$body = json_decode(wp_remote_retrieve_body($response));
					if ((isset($body->albums) && !empty($body->albums) && is_array($body->albums)) ||
						(isset($body->sharedAlbums) && !empty($body->sharedAlbums) && is_array($body->sharedAlbums))) {
						$albums = !empty($body->albums) ? $body->albums : $body->sharedAlbums;

						$ret = "<table>\n";
						$ret .= "\t<tr>\n";
						$ret .= "\t\t<th>Album Title</th>\n";
						$ret .= "\t\t<th>Thumbnail</th>\n";
						$ret .= "\t\t<th>Album ID</th>\n";
						$ret .= "\t\t<th>Media Count</th>\n";
						$ret .= "\t</tr>\n";

						foreach ($albums as $album) {
							$ret .= "\t<tr>\n";
							$ret .= "\t\t<td>" . wp_specialchars_decode(stripslashes(wp_filter_nohtml_kses($album->title)), ENT_QUOTES) . "</td>\n";
							$ret .= "\t\t<td><img alt='thumbnail' src='" . esc_url($album->coverPhotoBaseUrl) . "=w75-h75-c' /></td>\n";
							$ret .= "\t\t<td>" . wp_filter_nohtml_kses($album->id) . "</td>\n";
							$ret .= "\t\t<td>" . wp_filter_nohtml_kses($album->mediaItemsCount) . "</td>\n";
							$ret .= "\t</tr>\n";
						}

						if (!empty($body->nextPageToken)) {
							$ret .= "\t<tr>\n";
							$ret .= "\t\t<td colspan='4'>\n";
							$ret .= '<input type="button" value="' . esc_attr__('Load More', 'photonic') . '" name="photonic-google-album-more" class="photonic-helper-more" data-photonic-token="' . esc_attr($body->nextPageToken) . '" data-photonic-platform="google" data-photonic-access="' . esc_attr($album_type) . '"/>';
							$ret .= "\t\t</td>\n";
							$ret .= "\t</tr>\n";
						}

						$ret .= "</table>\n";

						return '<div class="photonic-helper">' . $ret . '</div>';
					}
					else {
						return '<div class="photonic-helper">' . esc_html__('No albums found', 'photonic') . '</div>';
					}
				}
				else {
					Photonic::log($response['response']);
					return '<div class="photonic-helper">' . sprintf(esc_html__('No data returned. Error code %s', 'photonic'), $response['response']['code']) . '</div>';
				}
			}
			else {
				Photonic::log($response);
				return '<div class="photonic-helper">' . esc_html__('No data returned. Empty response, or empty error code.', 'photonic') . '</div>';
			}
		}
		else {
			return '<div class="photonic-helper">' . $response->get_error_message() . '</div>';
		}
	}

	public function renew_token($refresh_token) {
		$token    = [];
		$error    = '';
		$response = Photonic::http(
			$this->access_token_URL(),
			'POST',
			[
				'client_id'     => $this->client_id,
				'client_secret' => $this->client_secret,
				'refresh_token' => $refresh_token,
				'grant_type'    => 'refresh_token'
			]
		);

		if (!is_wp_error($response)) {
			$token = $this->parse_token($response);
			if (!empty($token)) {
				$token['client_id'] = $this->client_id;
			}
			set_transient('photonic_' . $this->provider . '_token', $token, $token['oauth_token_expires']);
			if (empty($token)) {
				$error = print_r(wp_remote_retrieve_body($response), true);
			}
		}
		else {
			$error = $response->get_error_message();
		}

		return [$token, $error];
	}

	/**
	 * Not applicable for Google. We make this always return 0.
	 *
	 * @param int $soon_limit
	 * @return int|null
	 */
	public function is_token_expiring_soon($soon_limit) {
		return 0;
	}

	/**
	 * @param $body
	 * @param array $short_code
	 * @return Pagination
	 */
	public function get_pagination($body, array $short_code = []): Pagination {
		$pagination = new Pagination();
		if (!empty($body->nextPageToken)) {
			$pagination->total      = 10;
			$pagination->start      = 0;
			$pagination->end        = 1;
			$pagination->per_page   = $short_code;
			$pagination->next_token = $body->nextPageToken;
		}
		return $pagination;
	}
}
