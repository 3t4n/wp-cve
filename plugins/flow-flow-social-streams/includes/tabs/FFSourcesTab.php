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
class FFSourcesTab implements LATab {
	public function __construct() {
	}

	public function id() {
		return 'sources-tab';
	}

	public function flaticon() {
		return 'flaticon-error';
	}

	public function title() {
		return 'Feeds';
	}

	public function includeOnce( $context ) {
		?>
		<div class="section-content" id="sources-cont" data-tab="sources-tab">
			<div class="section-sources" id="sources-list" data-view-mode="sources-list">
				<div class="section" id="feeds-list-section">
					<h1 class="desc-following contains-button"><span>List of feeds</span> <span class="admin-button green-button button-add">Create feed</span></h1>
                    <p class="desc">Each feed can be placed in multiple <a class="ff-pseudo-link" href="#streams-tab">streams</a>. Cache for feed is being built immediately on creation. You can disable any feed and it will be disabled in all streams where it's connected. Feeds with errors are automatically disabled. <a class="ff-pseudo-link ff-toggle-display" href="#">Show only error feeds</a> or <a class="ff-pseudo-link ff-search-display" href="#">filter by name</a>.</p>
					<div class="search-container">
                        <input type="text" id="feeds-search" placeholder="Enter minimum 3 symbols"/>
                    </div>
                    <div id="feeds-view">
						<table class="feeds-list">
							<thead>
							<tr>
								<th></th>
								<th>Feed</th>
								<th></th>
								<th>Settings</th>
								<th>Last update</th>
								<th>Live</th>
							</tr>
							</thead>
							<tbody id="feeds-list">
                                <tr><td colspan="6"><div  class="anim-loader-wrapper"><div class="anim-loader"></div></div></td></tr>
							</tbody>
						</table>
						<div class="holder"></div>
						<div class="popup">
							<div class="section">
								<i class="popupclose flaticon-close-4"></i>
								<div class="networks-choice add-feed-step">
									<h1>Create new feed</h1>
									<ul class="networks-list">
										<li class="network-twitter"
											data-network="twitter"
											data-network-name="Twitter">
											<i class="flaticon-twitter"></i>
										</li>
										<li class="network-facebook"
											data-network="facebook"
											data-network-name="Facebook">
											<i class="flaticon-facebook"></i>
										</li>
										<li class="network-instagram"
											data-network="instagram"
											data-network-name="Instagram">
											<i class="flaticon-instagram"></i>
										</li>
                                        <li class="network-pinterest"
                                            data-network="pinterest"
                                            data-network-name="Pinterest">
                                            <i class="flaticon-pinterest"></i>
                                        </li>
										<li class="network-youtube ff-feature"
											data-network="youtube"
											data-network-name="YouTube">
											<i class="flaticon-youtube"></i>
                                            <i class="ff-icon-lock"></i>
										</li>
										<li class="network-linkedin ff-feature"
											data-network="linkedin"
											data-network-name="LinkedIn">
											<i class="flaticon-linkedin"></i>
                                            <i class="ff-icon-lock"></i>
										</li>

										<li class="network-flickr ff-feature"
											data-network="flickr"
											data-network-name="Flickr" style="margin-right:0">
											<i class="flaticon-flickr"></i>
                                            <i class="ff-icon-lock"></i>
										</li>

										<br>

                                        <li class="network-tumblr ff-feature"
                                            data-network="tumblr"
                                            data-network-name="Tumblr">
                                            <i class="flaticon-tumblr"></i>
                                            <i class="ff-icon-lock"></i>
                                        </li>
										<li class="network-vimeo ff-feature"
											data-network="vimeo"
											data-network-name="Vimeo">
											<i class="flaticon-vimeo"></i>
                                            <i class="ff-icon-lock"></i>
										</li>
										<li class="network-wordpress ff-feature"
											data-network="wordpress"
											data-network-name="WordPress">
											<i class="flaticon-wordpress"></i>
                                            <i class="ff-icon-lock"></i>
										</li>
										<li class="network-foursquare ff-feature"
											data-network="foursquare"
											data-network-name="Foursquare">
											<i class="flaticon-foursquare"></i>
                                            <i class="ff-icon-lock"></i>
										</li>
										<li class="network-soundcloud ff-feature"
											data-network="soundcloud"
											data-network-name="SoundCloud">
											<i class="flaticon-soundcloud"></i>
                                            <i class="ff-icon-lock"></i>
										</li>
										<li class="network-dribbble ff-feature"
											data-network="dribbble"
											data-network-name="Dribbble">
											<i class="flaticon-dribbble"></i>
                                            <i class="ff-icon-lock"></i>
										</li>
										<li class="network-rss ff-feature"
											data-network="rss"
											data-network-name="RSS"
											style="margin-right:0">
											<i class="flaticon-rss"></i>
                                            <i class="ff-icon-lock"></i>
										</li>
									</ul>
								</div>
								<div class="networks-content  add-feed-step">
									<div id="feed-views"></div>
									<div id="filter-views"></div>
									<p class="feed-popup-controls add">
										<span id="feed-sbmt-1"
											  class="admin-button green-button submit-button">Create feed</span>
										<span
											  class="space"></span><span class="admin-button grey-button button-go-back">Back to first step</span>
									</p>
									<p class="feed-popup-controls edit">
										<span id="feed-sbmt-2"
											  class="admin-button green-button submit-button">Save changes</span>
									</p>
									<p class="feed-popup-controls enable">
										<span id="feed-sbmt-3"
											  class="admin-button blue-button submit-button">Save & Enable</span>
									</p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
				/** @noinspection PhpIncludeInspection */
				include($context['root']  . 'views/footer.php');
			?>
		</div>
	<?php
	}
}