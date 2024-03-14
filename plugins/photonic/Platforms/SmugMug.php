<?php

namespace Photonic_Plugin\Platforms;

use Photonic_Plugin\Components\Album_List;
use Photonic_Plugin\Components\Collection;
use Photonic_Plugin\Components\Error;
use Photonic_Plugin\Components\Header;
use Photonic_Plugin\Components\Pagination;
use Photonic_Plugin\Components\Photo_List;
use Photonic_Plugin\Core\Photonic;
use Photonic_Plugin\Components\Album;
use Photonic_Plugin\Components\Photo;
use WP_Error;

require_once 'OAuth1.php';
require_once 'Level_One_Module.php';
require_once 'Level_Two_Module.php';
require_once 'Pageable.php';
require_once PHOTONIC_PATH . '/Components/Collection.php';

class SmugMug extends OAuth1 implements Level_One_Module, Level_Two_Module, Pageable {
	private $highlights_processed;
	private static $instance = null;

	protected function __construct() {
		parent::__construct();
		global $photonic_smug_api_key, $photonic_smug_api_secret, $photonic_smug_access_token, $photonic_smug_disable_title_link, $photonic_smug_token_secret, $photonic_smug_show_buy_link;

		$this->api_key             = !empty($photonic_smug_api_key) ? trim($photonic_smug_api_key) : '86MZ8N8TqJf5x2fQ4FRWXRtJ3C6Jm7XV';
		$this->api_secret          = trim($photonic_smug_api_secret);
		$this->token               = trim($photonic_smug_access_token);
		$this->token_secret        = trim($photonic_smug_token_secret);
		$this->provider            = 'smug';
		$this->link_lightbox_title = empty($photonic_smug_disable_title_link);
		$this->oauth_version       = '1.0a';
		$this->base_url            = 'https://api.smugmug.com/api/v2/';
		$this->show_buy_link       = !empty($photonic_smug_show_buy_link);

		$this->doc_links = [
			'general' => 'https://aquoid.com/plugins/photonic/smugmug/',
			'photos'  => 'https://aquoid.com/plugins/photonic/smugmug/smugmug-photos/',
			'albums'  => 'https://aquoid.com/plugins/photonic/smugmug/smugmug-albums/',
			'tree'    => 'https://aquoid.com/plugins/photonic/smugmug/smugmug-tree/',
			'folders' => 'https://aquoid.com/plugins/photonic/smugmug/folders/',
		];

		$this->set_oauth_done();
		$this->highlights_processed = [];
	}

	/**
	 * The main gallery builder for SmugMug. SmugMug takes the following parameters:
	 *    - nick_name = The nickname of the user. This is mandatory for SmugMug.
	 *    - view = tree | albums | album | images. If left blank, a value of 'tree' is assumed.
	 *    - columns = The number of columns to show the output in
	 *    - album or album_key = The album slug, which is the AlbumKey. This is needed if view='album' or 'images'. Prior to version 1.57 album_id was required
	 *    - empty = true | false. If true, empty albums and categories are returned in the response, otherwise they are ignored.
	 *    - columns = The number of columns to return the output in. Optional.
	 *
	 * @param array $attr
	 * @return array
	 */
	public function get_gallery_images($attr = []): array {
		global $photonic_smug_title_caption, $photonic_smug_album_sort_order;
		global $photonic_smug_thumb_size, $photonic_smug_main_size, $photonic_smug_tile_size, $photonic_smug_video_size, $photonic_smug_media, $photonic_smug_default_user;
		$this->push_to_stack('Get Gallery Images');

		if (empty($this->api_key)) {
			$this->pop_from_stack();
			return [new Error(esc_html__('SmugMug API Key not defined.', 'photonic') . Photonic::doc_link($this->doc_links['general']))];
		}

		$attr = array_merge(
			$this->common_parameters,
			[
				'caption'    => $photonic_smug_title_caption,
				'thumb_size' => $photonic_smug_thumb_size,
				'main_size'  => $photonic_smug_main_size,
				'tile_size'  => $photonic_smug_tile_size,
				'video_size' => $photonic_smug_video_size,

				'empty'            => 'false',
				'view'             => 'tree',
				'nick_name'        => $photonic_smug_default_user,
				'start'            => 1,
				'count'            => 500,
				'photo_count'      => 500,
				'media'            => $photonic_smug_media,
				'album_sort_order' => $photonic_smug_album_sort_order,
			],
			$attr
		);

		/*
		 * if ($attr['main_size'] == 'custom') {
					if (!empty($attr['custom_main_size'])) {
						$attr['main_size'] = $attr['custom_main_size'];
					}
					else {
						$attr['main_size'] = 'Largest';
					}
				}
				if ($attr['thumb_size'] == 'custom') {
					if (!empty($attr['custom_thumb_size'])) {
						$attr['thumb_size'] = $attr['custom_thumb_size'];
					}
					else {
						$attr['thumb_size'] = 'Tiny';
					}
				}
				if ($attr['tile_size'] == 'custom' && !empty($attr['custom_tile_size'])) {
					if (!empty($attr['custom_tile_size'])) {
						$attr['tile_size'] = $attr['custom_tile_size'];
					}
					else {
						$attr['tile_size'] = 'same';
					}
				}
		*/

		$attr = array_map('trim', $attr);

		$args = [
			'APIKey'  => $this->api_key,
			'_accept' => 'application/json'
		];

		$chained_calls = [];

		$args['_expandmethod'] = 'inline';
		$args['_verbosity']    = '1';

		if ('tree' === $attr['view'] || 'albums' === $attr['view']) {
			if (empty($attr['nick_name'])) {
				$this->pop_from_stack();
				return [new Error(sprintf(esc_html__('The %1$s attribute is required for the %2$s and %3$s views.', 'photonic'), '<code>nick_name</code>', '<code>tree</code>', '<code>albums</code>'))];
			}
		}

		switch ($attr['view']) {
			case 'albums':
				if (!empty($attr['site_password'])) {
					$chained_calls[]  = $this->base_url . 'user/' . $attr['nick_name'] . '!unlock';
					$args['Password'] = $attr['site_password'];
				}

				$chained_calls[] = $this->base_url . 'user/' . $attr['nick_name'] . '!albums';
				$args['_expand'] = 'HighlightImage.ImageSizes,HighlightImage.ImageSizeDetails,HighlightImage.LargestImage';
				$args['Order']   = $attr['album_sort_order'];

				if (500 === absint($attr['count'])) {
					$attr['count'] = 100;
					$attr['photo_count'] = 500;
				}

				break;

			case 'album':
			case 'images':
				$album_field = '';
				$is_album    = true;
				if (!empty($attr['album_key'])) {
					$album_field = $attr['album_key'];
				}
				elseif (!empty($attr['album'])) {
					if (stripos($attr['album'], '_') === false) {
						$album_field = $attr['album'];
					}
					else {
						$album_field = substr($attr['album'], stripos($attr['album'], '_') + 1);
					}
				}
				else {
					$is_album = false;
				}

				if (!empty($attr['password']) || !empty($attr['site_password'])) {
					$args['Password'] = !empty($attr['password']) ? $attr['password'] : $attr['site_password'];
					$chained_calls[]  = $this->base_url . 'album/' . $album_field . '!unlock';
				}

				if (!empty($attr['text'])) {
					$args['Text'] = $attr['text'];
				}
				if (!empty($attr['keywords'])) {
					$keywords         = explode(',', $attr['keywords']);
					$keywords         = wp_json_encode($keywords);
					$args['Keywords'] = $keywords;
				}
				if (!empty($attr['sort_method'])) {
					$args['SortMethod'] = $attr['sort_method'];
				}
				if (!empty($attr['sort_order'])) {
					$args['SortDirection'] = $attr['sort_order'];
				}

				/*
					Weird issue: If _expand is called with a comma-separated list, and is passed in $args[], it doesn't expand everything.
					So, for comma-separated values, _expand is appended to the URL.
					However, if we do this, and OAuth is being used, the signature generation gets messed up, as the signature is generated with the "?_expand=...",
					which doesn't match the signature for when we make a call with $signed_args, because SmugMug internally parses out the URL and puts _expand=...
					somewhere in the middle. So, to fix, for authenticated calls we are separating out the _expand parameter and making two calls. Additionally we are
					moving the parameter from the URL to the array in the generate_signature method.

					Update, v1.59 - Using _expand and combining both the expansions prevents the usage of the <code>start</code> and <code>count</code>
					attributes from the album node. So, splitting the call anyway, to introduce the album!images node...
				*/
				if ($is_album) {
					$chained_calls[] = $this->base_url . 'album/' . $album_field . '?_expand=HighlightImage.ImageSizes,HighlightImage.ImageSizeDetails,HighlightImage.LargestImage';
					// if ('images' === $attr['view']) { // Fix for https://wordpress.org/support/topic/smugmug-filter-on-keywords-not-working-for-me/
					if (!empty($args['Keywords']) || !empty($args['Text'])) { // Fix for https://wordpress.org/support/topic/smugmug-filter-on-keywords-not-working-for-me/
						$args['Scope'] = 'https://api.smugmug.com/api/v2/album/' . $album_field;
					}
					else {
						$chained_calls[] = $this->base_url . 'album/' . $album_field . '!images?_expand=ImageSizes,ImageSizeDetails,LargestImage';
					}
				}
				elseif (!empty($attr['nick_name']) || !empty($attr['folder'])) {
					if (!empty($attr['folder'])) {
						$args['Scope'] = 'https://api.smugmug.com/api/v2/node/' . $attr['folder'];
					}
					else {
						$args['Scope'] = 'https://api.smugmug.com/api/v2/user/' . $attr['nick_name'];
					}
				}

				if (!empty($args['Scope'])) {
					// Only search if you have view='images'
					$chained_calls[] = 'https://api.smugmug.com/api/v2/image!search?_expand=ImageSizes,ImageSizeDetails,LargestImage';
				}

				break;

			case 'folder':
				if (!empty($attr['folder'])) {
					$this->prepare_chained_calls_for_folder($chained_calls, $attr['folder'], $attr['count']);
				}
				break;

			case 'tree':
			default:
				$this->push_to_stack('Initial user tree');
				$initial_call     = $this->base_url . 'user/' . $attr['nick_name'];
				$initial_response = Photonic::http($initial_call, 'GET', $args);

				if (!empty($attr['site_password'])) {
					$chained_calls[]  = $this->base_url . 'user/' . $attr['nick_name'] . '!unlock';
					$args['Password'] = $attr['site_password'];
				}

				if (is_array($initial_response['response']) && isset($initial_response['response']['code']) && 200 === $initial_response['response']['code']) {
					$body   = $initial_response['body'];
					$body   = json_decode($body);
					$body   = $body->Response;
					$node   = $body->User->Uris->Node->Uri;
					$pieces = explode('/', $node);
					$node   = array_pop($pieces);
					$this->prepare_chained_calls_for_folder($chained_calls, $node, $attr['count']);
				}
				$this->pop_from_stack();
				break;
		}

		if (!empty($attr['nick_name'])) {
			$args['NickName'] = $attr['nick_name'];
		}

		if (!empty($attr['start'])) {
			$args['start'] = $attr['start'];
		}

		if (!empty($attr['count'])) {
			$args['count'] = $attr['count'];
		}
		$attr['photo_count'] = empty($attr['photo_count']) ? $attr['count'] : $attr['photo_count'];

		$attr['overlay_size']       = empty($attr['overlay_size']) ? $attr['thumb_size'] : $attr['overlay_size'];
		$attr['overlay_video_size'] = empty($attr['overlay_video_size']) ? $attr['video_size'] : $attr['overlay_video_size'];

		$header_display         = $this->get_header_display($attr);
		$attr['header_display'] = $header_display;

		$out = $this->make_chained_calls($chained_calls, $args, $attr);
		$this->pop_from_stack();

		if (!empty($this->stack_trace[$this->gallery_index])) {
			$out[] = $this->stack_trace[$this->gallery_index];
		}
		return $out;
	}

	/**
	 * Runs a sequence of web-service calls to get information. Most often a single web-service call with the "Extras" parameter suffices for SmugMug.
	 * But there are some scenarios, e.g. clicking on an album to get a popup of all images in that album, where you need to chain the calls for the header.
	 *
	 * @param $chained_calls
	 * @param $smug_args
	 * @param $short_code
	 * @return array
	 */
	private function make_chained_calls($chained_calls, $smug_args, $short_code): array {
		$this->push_to_stack('Make chained calls');
		$header_done = false;

		if (is_array($chained_calls) && count($chained_calls) > 0) {
			$components = [];

			$cookies = [];
			$header  = null;

			foreach ($chained_calls as $chained_call) {
				$this->push_to_stack("Chained call: $chained_call");
				if (false !== stripos($chained_call, '!unlock')) {
					$response = Photonic::http($chained_call, 'POST', $smug_args);
					if (!is_wp_error($response)) {
						if (is_array($response['response']) && isset($response['response']['code']) && 200 === $response['response']['code']) {
							$cookies = $response['cookies'];
							foreach ($response['cookies'] as $cookie) {
								$cookies[$cookie->name] = $cookie->value;
							}
						}
					}
					else {
						$this->pop_from_stack(); // 'Chained call'
						$this->pop_from_stack(); // 'Make chained calls'
						return [$this->wp_error_message($response)];
					}
				}

				// The following is NOT an "else" because we are modifying the headers with the cookies, if required
				if (false === stripos($chained_call, '!unlock')) {
					$this->push_to_stack('Making call');
					if ($this->oauth_done && empty($cookies)) {
						$signed_args = $this->sign_call($chained_call, 'GET', $smug_args);
						$response    = Photonic::http($chained_call, 'GET', $signed_args, null, 90, false, [], $cookies);
					}
					else {
						$response = Photonic::http($chained_call, 'GET', $smug_args, null, 90, false, [], $cookies);
					}

					$this->pop_from_stack();

					if (!is_wp_error($response)) {
						$body = $response['body'];
						$body = json_decode($body);

						if (isset($body->Code) && 200 === $body->Code) {
							$body = $body->Response;
							if (false !== stripos($chained_call, '!albums')) {
								$albums = $body->Album;
								if (is_array($albums) && count($albums) > 0) {
									$filter = [];
									if (!empty($short_code['filter'])) {
										$filter = explode(',', $short_code['filter']);
									}
									$components[] = $this->process_albums($albums, '', $filter, $short_code, $body->Pages);
								}
							}
							elseif ((false !== stripos($chained_call, '/album/') || (false !== stripos($chained_call, '/image!search') && false !== stripos($chained_call, rawurlencode('/api/v2/album/')))) && false === stripos($chained_call, '!unlock')) {
								$password_check_passed = true;
								if (isset($body->Album)) {
									$album                 = $body->Album;
									$password_check_passed = ('Password' === $album->SecurityType && isset($album->Uris->AlbumImages)) || 'Password' !== $album->SecurityType;

									global $photonic_smug_disable_title_link, $photonic_smug_hide_album_thumbnail, $photonic_smug_hide_album_title, $photonic_smug_hide_album_photo_count;
									$hidden   = [
										'thumbnail' => !empty($photonic_smug_hide_album_thumbnail),
										'title'     => !empty($photonic_smug_hide_album_title),
										'counter'   => !empty($photonic_smug_hide_album_photo_count),
									];
									$counters = ['photos' => $album->ImageCount ?? 0];

									$display = $short_code['display'] ?? 'local';

									if (!$header_done) {
										$header              = new Header();
										$header->title       = wp_kses_post($album->Name);
										$header->page_url    = $album->WebUri;
										$header->description = wp_kses_post($album->Description ?? '');

										if (isset($album->Uris->HighlightImage->Image)) {
											$header->thumb_url = $album->Uris->HighlightImage->Image->ThumbnailUrl;
										}
										elseif (isset($album->Uris->AlbumImages->AlbumImage) && is_array($album->Uris->AlbumImages->AlbumImage)) {
											$rand              = wp_rand(0, $album->ImageCount - 1);
											$header->thumb_url = $album->Uris->AlbumImages->AlbumImage[$rand]->ThumbnailUrl;
										}

										$header->header_for       = 'album';
										$header->hidden_elements  = $this->get_hidden_headers($short_code['header_display'], $hidden);
										$header->counters         = $counters;
										$header->enable_link      = empty($photonic_smug_disable_title_link);
										$header->display_location = $display;

										$header_done = true;
									}
								}
								if ($password_check_passed) {
									$comp       = $this->process_images($response, $short_code, $header);
									$components = array_merge($components, $comp);
								}
								else {
									$components[] = $this->password_protected;
								}
							}
							elseif (false !== stripos($chained_call, '/user/')) {
								$root_node    = $body->User->Uris->Node->Node;
								$components[] = $this->get_collection($root_node, $short_code);
							}
							elseif (false !== stripos($chained_call, '/node/')) {
								$components[] = $this->get_collection($body->Node, $short_code);
							}
							elseif (false !== stripos($chained_call, '/image!search')) {
								$comp       = $this->process_images($response, $short_code, $header);
								$components = array_merge($components, $comp);
							}
						}
					}
					else {
						$this->pop_from_stack(); // 'Chained call'
						$this->pop_from_stack(); // 'Make chained calls'
						return [$this->wp_error_message($response)];
					}
				}
				$this->pop_from_stack();
			}
			$this->pop_from_stack();
			return $components;
		}
		$this->pop_from_stack();
		return [];
	}

	public function get_config_object($count = 500, $levels = 5): array {
		$base = [
			'expand' => [
				'HighlightImage' => [
					'expand' => [
						'ImageSizes'       => [],
						'ImageSizeDetails' => [],
						'LargestImage'     => [],
					],
				],
				'NodeCoverImage' => [
					'expand' => [
						'ImageSizes'       => [],
						'ImageSizeDetails' => [],
						'LargestImage'     => [],
					],
				],
				'ChildNodes'     => [
					'args' => [
						'count' => $count
					],
				],
			],
		];

		$level = 1;
		$combined = $base;
		while ($level < absint($levels)) {
			$combined['expand']['ChildNodes']['expand'] = $combined['expand'];
			$level++;
		}
		return $combined;
	}

	private function prepare_chained_calls_for_folder(&$chained_calls, $folder_node, $count) {
		$config     = $this->get_config_object($count);
		$config_str = wp_json_encode($config);

		if (!empty($folder_node)) {
			$chained_calls[] = $this->base_url . 'node/' . $folder_node . '?_config=' . $config_str;
		}
	}

	/**
	 * @param $node
	 * @param $short_code
	 * @param string $indent
	 * @param int $level
	 * @return Collection
	 */
	private function get_collection($node, $short_code, $indent = '', $level = 0): Collection {
		$collection         = new Collection();
		$collection->indent = $indent;

		$albums  = [];
		$folders = [];

		if (is_array($node)) {
			foreach ($node as $child) {
				if ('Album' === $child->Type) {
					if (!in_array($child->NodeID . '-' . $this->gallery_index, $this->highlights_processed, true)) {
						$this->highlights_processed[] = $child->NodeID . '-' . $this->gallery_index;
						$albums[]                     = $child;
					}
				}
				elseif ('Folder' === $child->Type) {
					$folders[] = $child;
				}
			}
		}
		elseif ('Folder' === $node->Type) {
			if (isset($node->Uris->ChildNodes->Node)) {
				$child_nodes = $node->Uris->ChildNodes->Node;
				foreach ($child_nodes as $child) {
					if ('Album' === $child->Type) {
						if (!in_array($child->NodeID . '-' . $this->gallery_index, $this->highlights_processed, true)) {
							$this->highlights_processed[] = $child->NodeID . '-' . $this->gallery_index;
						}
						else {
							continue;
						}

						if (isset($child->Uris->Album->Album)) {
							$albums[] = $child->Uris->Album->Album; // Tree?
						}
						else {
							$albums[] = $child; // Node?
						}
					}
					elseif ('Folder' === $child->Type) {
						$folders[] = $child;
					}
				}
			}
		}

		if (count($albums) > 0 || count($folders) > 0) {
			if (!is_array($node)) {
				$hidden = ['thumbnail' => true, 'title' => false, 'counter' => false];

				$header                  = new Header();
				$header->title           = wp_kses_post($node->Name);
				$header->header_for      = 'folder';
				$header->hidden_elements = $this->get_hidden_headers($short_code['header_display'], $hidden);
				$header->enable_link     = false;

				$collection->header = $header;
			}
		}

		if (count($albums) > 0) {
			$collection->album_list = $this->process_albums($albums, $indent . "\t\t", [], $short_code, null);
		}

		if (count($folders) > 0) {
			foreach ($folders as $folder) {
				$collection->collections[] = $this->get_collection($folder, $short_code, $indent . "\t\t", $level + 1);
			}
		}

		return $collection;
	}

	/**
	 * Parse an array of album objects returned by the SmugMug API, then return an appropriate response.
	 *
	 * @param $albums
	 * @param string $indent
	 * @param array $album_filter
	 * @param $short_code
	 * @param $pages
	 * @return Album_List
	 */
	private function process_albums($albums, $indent, $album_filter, $short_code, $pages): Album_List {
		global $photonic_smug_albums_album_per_row_constraint, $photonic_smug_albums_album_constrain_by_count, $photonic_smug_albums_album_title_display, $photonic_smug_hide_albums_album_photos_count_display, $photonic_gallery_template_page;
		$objects = $this->build_level_2_objects($albums, $short_code, $album_filter);

		$pagination      = $this->get_pagination($pages);
		$row_constraints = ['constraint-type' => $photonic_smug_albums_album_per_row_constraint, 'count' => $photonic_smug_albums_album_constrain_by_count];

		$album_list                        = new Album_List($short_code);
		$album_list->albums                = $objects;
		$album_list->title_position        = $photonic_smug_albums_album_title_display;
		$album_list->row_constraints       = $row_constraints;
		$album_list->type                  = 'albums';
		$album_list->singular_type         = 'album';
		$album_list->level_1_count_display = $photonic_smug_hide_albums_album_photos_count_display;
		$album_list->pagination            = $pagination;
		$album_list->indent                = $indent;
		$album_list->album_opens_gallery   = ('page' === $short_code['popup'] && !empty($photonic_gallery_template_page) && is_string(get_post_status($photonic_gallery_template_page)));

		return $album_list;
	}

	/**
	 * Takes a response, then parses out the images from that response and returns a set of thumbnails for it. This method handles
	 * local / template / lightbox images as well as images in a modal panel.
	 *
	 * @param $response
	 * @param array $short_code
	 * @param $header
	 * @return array
	 */
	private function process_images($response, $short_code, $header): array {
		global $photonic_smug_photos_per_row_constraint, $photonic_smug_photos_constrain_by_count,
			   $photonic_smug_photo_title_display, $photonic_smug_photo_pop_title_display;
		$body = $response['body'];
		$body = json_decode($body);

		$components = [];
		if ('Ok' === $body->Message) {
			$body = $body->Response;
			if (isset($body->Album)) {
				$album  = $body->Album;
				$images = $album->Uris->AlbumImages;
			}
			elseif (isset($body->Image)) {
				$images = $body->Image;
			}
			else {
				$images = $body;
			}

			$photo_objects = [];
			if (isset($images->AlbumImage)) {
				$photo_objects = $this->build_level_1_objects($images->AlbumImage, $short_code);
			}
			if (is_array($images)) {
				$photo_objects = $this->build_level_1_objects($images, $short_code);
			}

			$pages      = isset($images->Pages) ? $images->Pages : (is_array($images) && isset($body->Pages) ? $body->Pages : null);
			$pagination = $this->get_pagination($pages);

			if (!empty($photo_objects)) {
				if (!empty($header)) {
					$components[] = $header;
				}

				if (isset($short_code['display']) && 'modal' === $short_code['display']) {
					$row_constraints = ['constraint-type' => 'padding'];
					$title_position  = $photonic_smug_photo_pop_title_display;
				}
				else {
					$row_constraints = ['constraint-type' => $photonic_smug_photos_per_row_constraint, 'count' => $photonic_smug_photos_constrain_by_count];
					$title_position  = $photonic_smug_photo_title_display;
				}

				$photo_list         = new Photo_List($short_code);
				$photo_list->photos = $photo_objects;

				$photo_list->title_position  = $title_position;
				$photo_list->row_constraints = $row_constraints;
				$photo_list->parent          = 'album';
				$photo_list->pagination      = $pagination;

				$components[] = $photo_list;
			}
		}
		return $components;
	}

	private function construct_custom_size($custom, $sizes): string {
		if (isset($sizes->LargeImageUrl)) {
			$custom                                         = str_replace('ImageUrl', '', $custom);
			$large                                          = $sizes->LargeImageUrl;
			$split                                          = explode('/', $large);
			$split[count($split) - 2]                       = $custom;
			$image_parts                                    = explode('.', $split[count($split) - 1]);
			$image_name_parts                               = explode('-', $image_parts[count($image_parts) - 2]);
			$image_name_parts[count($image_name_parts) - 1] = $custom;
			$image_parts[count($image_parts) - 2]           = implode('-', $image_name_parts);
			$split[count($split) - 1]                       = implode('.', $image_parts);
			return implode('/', $split);
		}
		return '';
	}

	public function build_level_1_objects($response, array $short_code, $module_parameters = [], $options = []): array {
		$thumb = "{$short_code['thumb_size']}ImageUrl";
		$main  = "{$short_code['main_size']}ImageUrl";
		$tile  = (empty($short_code['tile_size']) || 'same' === $short_code['tile_size']) ? $main : "{$short_code['tile_size']}ImageUrl";

		$media     = explode(',', $short_code['media']);
		$videos_ok = in_array('videos', $media, true) || in_array('all', $media, true);
		$photos_ok = in_array('photos', $media, true) || in_array('all', $media, true);

		$photo_objects = [];
		$sizes         = [
			'thumb_size' => sanitize_text_field($short_code['thumb_size']),
			'main_size'  => sanitize_text_field($short_code['main_size']),
			'tile_size'  => sanitize_text_field((empty($short_code['tile_size']) || 'same' === $short_code['tile_size']) ? $short_code['main_size'] : $short_code['tile_size']),
		];

		if (is_array($response) && count($response) > 0) {
			foreach ($response as $image) {
				if ((empty($image->IsVideo) && !$photos_ok) || (!empty($image->IsVideo) && !$videos_ok)) {
					continue;
				}
				$image_sizes = $image->Uris->ImageSizes->ImageSizes;

				$photonic_photo             = new Photo();
				$photonic_photo->thumbnail  = esc_url($image_sizes->{$thumb} ?? $this->construct_custom_size($thumb, $image_sizes));
				$photonic_photo->main_image = esc_url($image_sizes->{$main} ?? $this->construct_custom_size($main, $image_sizes)); // $image_sizes->{$main};
				$photonic_photo->tile_image = esc_url($image_sizes->{$tile} ?? $this->construct_custom_size($tile, $image_sizes)); // $image_sizes->{$tile};

				$this->calculate_sizes($image, $photonic_photo, $sizes);

				$photonic_photo->title       = wp_kses_post($image->Title);
				$photonic_photo->alt_title   = $photonic_photo->title;
				$photonic_photo->description = wp_kses_post($image->Caption);
				if (isset($image->WebUri)) {
					$photonic_photo->main_page = esc_url($image->WebUri);
					$photonic_photo->buy_link  = esc_url($image->WebUri . '/buy');
				}

				if (!empty($image->IsVideo)) {
					$photonic_photo->video = esc_url($image_sizes->{$short_code['video_size'] . 'VideoUrl'});
				}

				if (isset($image->ArchivedUri)) {
					$photonic_photo->download = esc_url($image->ArchivedUri);
				}

				$photonic_photo->id = $image->ImageKey;

				if (!empty($image->DateTimeOriginal)) {
					$photonic_photo->taken_on = sanitize_text_field($image->DateTimeOriginal);
				}

				if (!empty($image->DateTimeUploaded)) {
					$photonic_photo->uploaded_on = sanitize_text_field($image->DateTimeUploaded);
				}

				$photo_objects[] = $photonic_photo;
			}
		}
		return $photo_objects;
	}

	public function build_level_2_objects($objects_or_response, array $short_code, array $filter_list = [], array &$options = [], Pagination &$pagination = null): array {
		global $photonic_smug_hide_password_protected_thumbnail, $photonic_gallery_template_page;

		$named_albums = $filter_list;
		foreach ($named_albums as $idx => $album) {
			if (false !== stripos($album, '_')) {
				$named_albums[$idx] = substr($album, stripos($album, '_') + 1);
			}
		}

		$objects = [];
		if (is_array($objects_or_response) && count($objects_or_response) > 0) {
			$main = "{$short_code['main_size']}ImageUrl";
			$tile = (empty($short_code['tile_size']) || 'same' === $short_code['tile_size']) ? $main : "{$short_code['tile_size']}ImageUrl";

			$sizes = [
				'thumb_size' => $short_code['thumb_size'],
				'tile_size'  => (empty($short_code['tile_size']) || 'same' === $short_code['tile_size']) ? $short_code['main_size'] : $short_code['tile_size'],
			];

			foreach ($objects_or_response as $album) {
				if (!empty($photonic_smug_hide_password_protected_thumbnail) && isset($album->SecurityType) && 'None' !== $album->SecurityType) {
					continue;
				}

				$highlight = $album->Uris->HighlightImage;
				if ((!isset($album->ImageCount) || 0 !== $album->ImageCount)) {
					$photonic_album = new Album();

					if (isset($highlight->Image)) {
						$thumbURL = esc_url($highlight->Image->Uris->ImageSizes->ImageSizes->{$short_code['thumb_size'] . 'ImageUrl'});
						$tileURL  = esc_url($highlight->Image->Uris->ImageSizes->ImageSizes->$tile);
						$this->calculate_sizes($highlight->Image, $photonic_album, $sizes);
					}
					else {
						if (in_array($short_code['thumb_size'], ['Small', 'Thumb', 'Tiny', 'Sm', 'Th', 'Ti'], true)) {
							$thumbURL = esc_url(trailingslashit(PHOTONIC_URL) . 'include/images/placeholder-' . substr($short_code['thumb_size'], 0, 2) . '.png');
						}
						else {
							$thumbURL = esc_url(trailingslashit(PHOTONIC_URL) . 'include/images/placeholder.png');
						}
						$tileURL = esc_url(trailingslashit(PHOTONIC_URL) . 'include/images/placeholder.png');
					}

					if (isset($album->AlbumKey)) {
						$photonic_album->id = $album->AlbumKey;
					}
					else {
						$uri                = $album->Uris->Album->Uri;
						$uri                = explode('/', $uri);
						$photonic_album->id = $uri[count($uri) - 1];
					}
					$photonic_album->thumbnail  = $thumbURL;
					$photonic_album->tile_image = $tileURL;

					$photonic_album->main_page = esc_url($album->WebUri);

					$photonic_album->title       = wp_kses_post($album->Name);
					$photonic_album->description = !empty($album->Description) ? wp_kses_post($album->Description) : '';

					if ('page' === $short_code['popup'] && !empty($photonic_gallery_template_page) && is_string(get_post_status($photonic_gallery_template_page))) {
						$internal_short_code           = $short_code;
						$internal_short_code['view']   = 'album';
						$internal_short_code['album']  = $photonic_album->id;
						$internal_short_code['layout'] = empty($short_code['photo_layout']) ? $short_code['layout'] : $short_code['photo_layout'];

						$photonic_album->gallery_url = $this->get_gallery_url($internal_short_code, ['title' => $photonic_album->title]);
					}

					if (isset($album->ImageCount)) {
						$photonic_album->counter = $album->ImageCount;
					}

					if (isset($album->SecurityType) && 'Password' === $album->SecurityType) {
						$photonic_album->classes    = ['photonic-smug-passworded'];
						$photonic_album->passworded = 1;
					}

					if (empty($named_albums) || (!empty($named_albums) && in_array($album->AlbumKey, $named_albums, true) && 'exclude' !== strtolower($short_code['filter_type'])) ||
						(!empty($named_albums) && !in_array($album->AlbumKey, $named_albums, true) && 'exclude' === strtolower($short_code['filter_type']))) {
						$objects[] = $photonic_album;
					}
				}
			}
		}
		return $objects;
	}

	private function calculate_sizes($image, &$photo_object, $sizes) {
		if (isset($image->Uris->ImageSizeDetails) && isset($image->Uris->LargestImage)) {
			$image_size_details = $image->Uris->ImageSizeDetails;
			$largest_image      = $image->Uris->LargestImage;

			foreach ($sizes as $size => $size_value) {
				if ((isset($largest_image->LargestImage) && 'Largest' === $size_value) || isset($image_size_details->ImageSizeDetails->{'ImageSize' . $size_value})) {
					if ('Largest' === $size_value) {
						$ref = $largest_image->LargestImage;
					}
					else {
						$ref = $image_size_details->ImageSizeDetails->{'ImageSize' . $size_value};
					}
					$photo_object->{$size} = [
						'w' => $ref->Width,
						'h' => $ref->Height,
					];
				}
			}
		}
	}

	/**
	 * Access Token URL
	 *
	 * @return string
	 */
	public function access_token_URL(): string {
		return 'https://api.smugmug.com/services/oauth/1.0a/getAccessToken';
	}

	/**
	 * Authenticate URL
	 *
	 * @return string
	 */
	public function authenticate_URL(): string {
		return 'https://api.smugmug.com/services/oauth/1.0a/authorize';
	}

	/**
	 * Authorize URL
	 *
	 * @return string
	 */
	public function authorize_URL(): string {
		return 'https://api.smugmug.com/services/oauth/1.0a/authorize';
	}

	/**
	 * Request Token URL
	 *
	 * @return string
	 */
	public function request_token_URL(): string {
		return 'https://api.smugmug.com/services/oauth/1.0a/getRequestToken';
	}

	/**
	 * Tests to see if the OAuth Access Token that is cached is still valid. This is important because a user might have manually revoked
	 * access for your app through the provider's control panel.
	 *
	 * @param $token
	 * @return array|WP_Error
	 */
	public function check_access_token() {
		$signed_parameters = $this->sign_call($this->base_url . 'user/sayontan', 'GET', []);
		$end_point         = $this->base_url . 'user/sayontan?' . Authenticator::build_query($signed_parameters);
		return Photonic::http($end_point, 'GET', null);
	}

	/**
	 * Takes the response for the "Check access token", then tries to determine whether the check was successful or not.
	 *
	 * @param $response
	 * @return bool
	 */
	public function is_access_token_valid($response): bool {
		if (is_wp_error($response)) {
			return false;
		}

		$response = $response['response'];
		if (200 === $response['code']) {
			return true;
		}
		return false;
	}

	/**
	 * @param $pages
	 * @param array $short_code
	 * @return Pagination
	 */
	public function get_pagination($pages, array $short_code = []): Pagination {
		$pagination = new Pagination();
		if (!empty($pages)) {
			$pagination->total    = $pages->Total;
			$pagination->start    = $pages->Start;
			$pagination->end      = $pages->Start + $pages->Count - 1;
			$pagination->per_page = $pages->RequestedCount;
		}
		return $pagination;
	}
}
