<?php namespace flow\tabs;

use la\core\tabs\LATab;

if ( ! defined( 'WPINC' ) ) die;
/**
 * FlowFlow.
 *
 * @package   FlowFlow
 * @author    Looks Awesome <email@looks-awesome.com>
 *
 * @link      http://looks-awesome.com
 * @copyright Looks Awesome
 */

class FFStreamsTab implements LATab{
	public function __construct() {
	}

	public function id() {
		return 'streams-tab';
	}

	public function flaticon() {
		return 'flaticon-ctrl-left';
	}

	public function title() {
		return 'Streams';
	}

	public function includeOnce( $context ) {
		$arr = $context['streams'];

        $plugins_url = plugins_url() . '/' . $context['plugin_dir_name'];

		$export = array();
		foreach ($arr as $stream) {

			$item = array();

			foreach ($stream as $key => $value) {
                if ($key !== 'value') {
					if ($key === 'error') {
						$item['error'] = true;
					} else {
						if ($key === 'css') {
							$value = str_replace('"', "'", $value);
						}
						$item[$key] = $value;
					}
				}
			}

			$export[] = $item;
		}
//		debug
//		$export[0]['css'] = '';
//		$export[0]['heading'] = '';
		?>
		<script>
			var streams = <?php echo json_encode($export, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;
		</script>
		<div class="section-content" id="streams-cont" data-tab="streams-tab">
			<div class="section-stream" id="streams-list" data-view-mode="streams-list">
				<div class="section" id="streams-list-section">
					<h1 class="desc-following contains-button"><span>List of your streams</span> <span class="admin-button green-button button-add">create stream</span></h1>
                    <div id="how-it-works"><a href="#" class="ff-pseudo-link">Quick guide</a></div>
                    <p class="desc">Streams are containers for <a class="ff-pseudo-link" href="#sources-tab">feeds</a> you created. You decide which social feeds you add to or remove from container and how it looks on site pages. Stream status (green or red) shows if any of connected feeds have error. <a href="#" class="ff-pseudo-link tutorial-link">Show me quick tutorial</a></p>
					<table>
						<thead>
						<tr>
							<th></th>
							<th></th>
							<th>Stream</th>
							<th class="th-hint">Host Type <span class="desc hint-block">
                                    <span class="hint-link"><img src="<?php echo $plugins_url ?>/assets/info_icon.svg"></span>
                                    <span class="hint hint-pro">
                                        <h3>Self-Hosted</h3>
                                        <p class="shortcode-pages">Regular stream that's hosted on your site server. Feeds data is updated and stored also on your server.</p>
                                        <br/>
                                        <h3>Cloud</h3>
                                        <p class="shortcode-pages">Stream that contains <a target="_blank" href="https://social-streams.com/boosts/">boosted feeds</a>. All feeds data and updating routine will be hosted in cloud, and directly embedded on your site pages. It means zero load on your site server.</p>
                                    </span>
                                </span></th>
							<th>Feeds</th>
							<?php
							if (FF_USE_WP) echo '<th>Shortcode</th>';
							else echo '<th>ID</th>';
							?>
						</tr>
						</thead>
						<tbody>
						<?php

						foreach ($arr as $stream) {
							if (!isset($stream['id'])) continue;
							$id = $stream['id'];

							$status = $stream['status'] == 1 ? 'ok' : 'error';
							$additionalInfo = FF_USE_WP ?
								'<td><span class="shortcode">[ff id="' . $id . '"]</span><span class="desc hint-block">
                <span class="hint-link"><img src="' . $plugins_url . '/assets/info_icon.svg"></span>
                <span class="hint hint-pro">
                    <h3>Shortcode detected on pages:</h3>
                    <p class="shortcode-pages"></p>
                    </span>
            </span></td>' :
								'<td>' . $id . '</td>';

							if (isset($_REQUEST['debug']) && isset($stream['error'])) {
								$additionalInfo .= $stream['error'];
							}
							$info = '';

							if ( isset( $stream[ 'cloud' ] ) && $stream[ 'cloud' ] == 'yep' ) {
								$type = '<span class="stream-cloud-info"><span class="highlight hilite-boost"><i class="flaticon-cloud"></i></span> <span class="highlight">Cloud</span></span>';
                            } else {
								$type = '<span class="highlight">Self-Hosted</span>'; // default
							}

                            $boosted = 0;

							if (isset($stream['feeds']) && !empty($stream['feeds'])) {
								$feeds = $stream['feeds'];
								if (is_array($feeds) || is_object($feeds)){
									foreach ( $feeds as $feed ) {
										$info = $info . '<i class="flaticon-' . $feed['type'] . '"></i>';
										if ( $feed['boosted'] === 'yep' ) $boosted++;
									}
/*
                                    if ( $boosted === count( $feeds ) ) {
                                        $type = '<span class="stream-cloud-info"><span class="highlight hilite-boost"><i class="flaticon-cloud"></i></span> <span class="highlight">Cloud</span></span>';
                                    }
*/
								}
							}

							// not used anymore
							$layout = isset($stream['layout']) ? '<span class="highlight">' . $stream['layout'] . '</span>' : '';

							echo
								'<tr data-stream-id="' . $id . '">
							      <td class="controls"><div class="loader-wrapper"><div class="throbber-loader"></div></div><i class="flaticon-tool_edit"></i> <i class="flaticon-tool_clone"></i> <i class="flaticon-tool_delete"></i></td>
							      <td><span class="cache-status-'. $status .'"></span></td>
							      <td class="td-name">' . (!empty($stream['name']) ? stripslashes($stream['name']) : 'Unnamed') . '</td>
							      <td class="td-type">' . ($type) . '</td>
							      <td class="td-feed">' . (empty($info) ? '<span class="highlight-grey">No Feeds</span>' : $info) . '</td>'
								. $additionalInfo .
								'</tr>';
						}

						if (empty($arr)) {
							echo '<tr class="empty-row"><td class="empty-cell" colspan="6">Please add at least one stream</td></tr>';
						}

						?>
						</tbody>
					</table>
				</div>
                <div class="popup streams-popup">
                    <div class="section">
                        <h1><span>Pick stream type</span></h1>

                        <div class="stream-type-picker">
                            <div data-stream-type="self">
                                <h3>Self-Hosted</h3>
                                <p>Default type. Can contain only regular feeds. Your WordPress server requests and caches posts. App retrieves data from the site database to display feeds on pages. This is completely autonomous solution but depends on your server resources.</p>
                                <span class="stream-btn-cta"><i class="flaticon-arrow-back-2"></i></span>
                            </div>
                            <div data-stream-type="cloud">
                                <h3>Cloud</h3>
                                <p>For feeds hosted in our cloud network. Can contain only <a href="#addons-tab"  class="ff-pseudo-link" target="_blank">boosted feeds</a>. Posts data is cached and updated in cloud and stream is delivered directly from cloud to site pages thus offloads your WordPress website server completely. Exclusive features are upcoming for cloud streams soon.</p>
                                <span class="stream-btn-cta"><i class="flaticon-arrow-back-2"></i></span>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="popup tutorial-popup">

                    <div class="section">
                        <h1><span>How it works</span></h1>

                        <div class="popup-content-wrapper">
                            <i class="popupclose flaticon-close-4"></i>
                            <div class="timeline-element"></div>
                            <div class="steps-box">
                                <div class="steps-item steps-image steps-image-step-1"><img src="<?php echo $plugins_url ?>/assets/step1.png"></div>
                                <div class="steps-item">
                                    <h3>01</h3>
                                    <h2>Create feed</h2>
                                    <p><strong>Feeds</strong> are data. Create feed of desired source on <a class="ff-pseudo-link" href="#sources-tab">Feeds tab</a>. Now you have posts data from this source loaded and cached in database. <a class="ff-pseudo-link" href="#addons-tab">Boost</a> the feed if you want to host it in the cloud and offload your server.</p>
                                </div>
                                <div class="steps-item">
                                    <h3>02</h3>
                                    <h2>Create stream</h2>
                                    <p><strong>Streams</strong> define how your feeds look. Create container (stream) for your feeds on <a class="ff-pseudo-link" href="#streams-tab">Streams tab</a>. Use plenty of settings to customize layout and look of the stream. Streams are two type: self-hosted and cloud. First for regular feeds, second for boosted.</p>
                                </div>
                                <div class="steps-item steps-image steps-image-step-2 steps-image-overflow"><img src="<?php echo $plugins_url ?>/assets/step2.png"></div>
                                <div class="steps-item steps-image steps-image-step-3"><img src="<?php echo $plugins_url ?>/assets/step3.png"></div>
                                <div class="steps-item">
                                    <h3>03</h3>
                                    <h2>Place on page</h2>
                                    <p>Time to display feeds on page. Copy stream code from <a class="ff-pseudo-link" href="#streams-tab">Streams tab</a>. Add it on page using page editor. Self-hosted streams will query feed data from your site database. Cloud streams will get data from the cloud directly.</p>
                                </div>
                                <div class="steps-item steps-item-last"><h2>That's it!</h2>
                                <p><span class="tutorial-first-time">This message appears automatically only first time you visit admin page.<br></span>Tutorial can be found on Streams tab later.<br><br>
                                    <a class="ff-pseudo-link" href="#streams-tab">OK, close it</a></p></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="section rating-promo">
                    <div class="fb-wrapper"><div class="fb-page" data-href="https://www.facebook.com/SocialStreamApps/" data-small-header="true" data-adapt-container-width="true" data-hide-cover="true" data-show-facepile="false"><div class="fb-xfbml-parse-ignore"><blockquote cite="https://www.facebook.com/SocialStreamApps/"><a href="https://www.facebook.com/SocialStreamApps/">Looks Awesome</a></blockquote></div></div></div>
                    <h1 class="desc-following"><span>Help plugin to grow</span></h1>
                    <p class="">A lot of users only think to review Flow-Flow when something goes wrong while many more people use it satisfactory. Don't let this go unnoticed. If you find Flow-Flow useful please leave your honest rating and review on plugin's <a href="https://wordpress.org/support/plugin/flow-flow-social-streams/reviews/?filter=5" target="_blank">Review page</a> to help Flow-Flow grow and endorse its further development!</p>
                </div>
			</div>
		</div>
		<?php
	}
} 