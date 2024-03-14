<?php

namespace Photonic_Plugin\Admin;

if (!defined('ABSPATH')) {
	echo '<h1>WordPress not loaded!</h1>';
	exit;
}

require_once 'Admin_Page.php';

class Getting_Started extends Admin_Page {
	private static $instance;

	private function __construct() {
		// Empty
	}

	public static function get_instance() {
		if (null === self::$instance) {
			self::$instance = new Getting_Started();
		}
		return self::$instance;
	}

	public function render_content() {
		$this->tabs();
		$this->capabilities();
		$this->build_gallery();
		$this->layouts();
		$this->lightbox();
		$this->secret_menu();
		$this->helper_shortcode();
		$this->really_technical();
	}

	private function tabs() {
		?>
		<script type="text/javascript">
			document.addEventListener('DOMContentLoaded', function () {
				const photonicStartTabs = document.querySelectorAll('.photonic-options-header-bar .nav-tab');
				photonicStartTabs.forEach(function (tab) {
					tab.addEventListener('click', function (e) {
						e.preventDefault();
						const hash = tab.getAttribute('href');
						photonicShowSection(hash);
					});
				});

				function photonicShowSection(hash) {
					let check = 'photonic-start-capabilities';
					let foundHash = false;
					if (hash) {
						check = hash.substr(1);
						if (['photonic-start-capabilities', 'photonic-start-create', 'photonic-start-layouts', 'photonic-start-lightbox', 'photonic-start-secret', 'photonic-start-helpers', 'photonic-start-tech'].indexOf(check) < 0) {
							check = 'photonic-start-capabilities';
						}
						else {
							foundHash = true;
						}
					}

					photonicStartTabs.forEach(function (tab) {
						let panel;
						if (tab.getAttribute('href') === ('#' + check)) {
							tab.classList.add('nav-tab-active');
							panel = document.querySelector(tab.getAttribute('href'));
							panel.classList.add('photonic-panel-visible');
							if (foundHash) {
								window.history.pushState({}, document.title, '#' + check);
							}
							else {
								window.history.pushState({}, document.title, '');
							}
						}
						else {
							tab.classList.remove('nav-tab-active');
							panel = document.querySelector(tab.getAttribute('href'));
							panel.classList.remove('photonic-panel-visible');
						}
					});
				}

				let hash = location.hash;
				photonicShowSection(hash);
			});
		</script>
		<div class="photonic-tabbed-start">
			<div class="photonic-header-nav">
				<div class="photonic-options-header-bar fix">
					<h2 class='nav-tab-wrapper'>
						<a class='nav-tab' id='photonic-start-tab-capabilities' href='#photonic-start-capabilities'>Overview</a>
						<a class='nav-tab' id='photonic-start-tab-create' href='#photonic-start-create'>Create
							Galleries</a>
						<a class='nav-tab' id='photonic-start-tab-layouts' href='#photonic-start-layouts'>Layouts</a>
						<a class='nav-tab' id='photonic-start-tab-lightbox'
						   href='#photonic-start-lightbox'>Lightboxes</a>
						<a class='nav-tab' id='photonic-start-tab-secret' href='#photonic-start-secret'>Secret Menu</a>
						<a class='nav-tab' id='photonic-start-tab-helpers' href='#photonic-start-helpers'>Helpers</a>
						<a class='nav-tab' id='photonic-start-tab-tech' href='#photonic-start-tech'>Technical Stuff</a>
					</h2>
				</div>
			</div>
		</div><!-- /#photonic-tabbed-options -->
		<?php
	}

	private function capabilities() {
		?>
		<div id="photonic-start-capabilities" class="photonic-start-panel">
			<h2 class="photonic-section">Capabilities and Documentation</h2>
			<p>
				Photonic can show you photos and galleries not just from standard WordPress, but also from several
				third-party photo-hosting providers
				such as Flickr, SmugMug, Google Photos etc. The following table tells you what you need for each
				provider, and provides you with
				documentation links for how to show something using Photonic.
			</p>

			<table class="form-table photonic-form-table">
				<tr>
					<th rowspan="2" class="theader">Provider</th>
					<th colspan="4" class="theader">What Can You Show?</th>
					<th rowspan="2" class="theader">Authentication</th>
					<th rowspan="2" class="theader">Lightbox Support</th>
				</tr>

				<tr>
					<th class="theader">Single Photo<br/>(Level 0)</th>
					<th class="theader">Photos / Videos<br/>(Level 1)</th>
					<th class="theader">Albums / Sets / Galleries<br/>(Level 2)</th>
					<th class="theader">Collections / Groups<br/>(Level 3)</th>
				</tr>

				<tr>
					<th>Native WP</th>
					<td>Not supported</td>
					<td><a href="https://aquoid.com/plugins/photonic/wp-galleries/">Standard gallery photos</a>, no
						videos in WP galleries
					</td>
					<td><a href="https://aquoid.com/plugins/photonic/wp-galleries/">Standard galleries</a></td>
					<td>No such feature in WP</td>
					<td>No such feature in WP</td>
					<td>All</td>
				</tr>

				<tr>
					<th>
						Flickr<br/>
						<em><a href="https://www.flickr.com/services/api/misc.api_keys.html">API Key
								Required</a></em><br/>
						<em>See <a href="https://aquoid.com/plugins/photonic/flickr/#api-key">Instructions</a></em>
					</th>
					<td><a href="https://aquoid.com/plugins/photonic/flickr/flickr-photo/">Supported</a></td>
					<td><a href="https://aquoid.com/plugins/photonic/flickr/flickr-photos/">User Photos, Videos and
							Group Pools</a></td>
					<td><a href="https://aquoid.com/plugins/photonic/flickr/flickr-photosets/">Albums / Photosets</a>
						and <a href="https://aquoid.com/plugins/photonic/flickr/flickr-galleries/">Galleries</a></td>
					<td><a href="https://aquoid.com/plugins/photonic/flickr/flickr-collections/">Collections, with lazy
							loading</a></td>
					<td><a href="https://aquoid.com/plugins/photonic/flickr/flickr-authentication/">Required to share
							private photos</a></td>
					<td>All; Image Lightbox, PrettyPhoto and StripJS cannot handle videos; Lightcase shows Flash videos
						for Flickr
					</td>
				</tr>

				<tr>
					<th>
						SmugMug<br/>
						<em><a href="https://api.smugmug.com/api/developer/apply">API Key required for private
								photos</a></em><br/>
						<em>See <a href="https://aquoid.com/plugins/photonic/smugmug/#api-key">instructions</a></em>
					</th>
					<td>Not supported</td>
					<td><a href="https://aquoid.com/plugins/photonic/smugmug/smugmug-photos/">User photos and videos</a>
					</td>
					<td><a href="https://aquoid.com/plugins/photonic/smugmug/smugmug-albums/">Albums</a></td>
					<td><a href="https://aquoid.com/plugins/photonic/smugmug/smugmug-tree/">User tree</a> and <a
								href="https://aquoid.com/plugins/photonic/smugmug/folders/">Folders</a></td>
					<td><a href="https://aquoid.com/plugins/photonic/smugmug/smugmug-albums/#protected">Password-protection</a>,
						<a href="https://aquoid.com/plugins/photonic/authentication/#back-end">Authentication to share
							your private photos</a></td>
					<td>All; Image Lightbox, PrettyPhoto and StripJS cannot handle videos</td>
				</tr>

				<tr>
					<th>
						Google Photos<br/>
						<a href="https://console.developers.google.com/apis/">Client ID required</a><br/>
						<a href="https://aquoid.com/plugins/photonic/google-photos/#auth">Authentication required</a>
					</th>
					<td>Not supported</td>
					<td><a href="https://aquoid.com/plugins/photonic/google-photos/photos/">Photos and Videos</a></td>
					<td><a href="https://aquoid.com/plugins/photonic/google-photos/albums/">Albums</a></td>
					<td>No such feature in Google Photos</td>
					<td><a href="https://aquoid.com/plugins/photonic/google-photos/#auth">Back-end authentication</a>
					</td>
					<td>All; Fancybox, Featherlight, Image Lightbox, PrettyPhoto and StripJS cannot handle videos</td>
				</tr>

				<tr>
					<th>Zenfolio</th>
					<td><a href="https://aquoid.com/plugins/photonic/zenfolio/photos/#individual">Supported</a></td>
					<td><a href="https://aquoid.com/plugins/photonic/zenfolio/photosets/">User</a> and <a
								href="https://aquoid.com/plugins/photonic/zenfolio/photos/">Generic</a> photos and
						videos
					</td>
					<td><a href="https://aquoid.com/plugins/photonic/zenfolio/photosets/">Photosets (Galleries and
							Collections)</a></td>
					<td><a href="https://aquoid.com/plugins/photonic/zenfolio/groups/">Groups</a> and <a
								href="https://aquoid.com/plugins/photonic/zenfolio/group-hierarchy/">Group
							hierarchies</a></td>
					<td><a href="https://aquoid.com/plugins/photonic/zenfolio/groups/">Password-protection supported</a>,
						authentication not supported
					</td>
					<td>All; Image Lightbox, PrettyPhoto and StripJS cannot handle videos</td>
				</tr>

				<tr>
					<th>
						Instagram<br/>
						<a href="https://aquoid.com/plugins/photonic/instagram/#auth-setup">Authentication required</a>
					</th>
					<td><a href="https://aquoid.com/plugins/photonic/instagram/#photo-of-the-day">Supported</a></td>
					<td><a href="https://aquoid.com/plugins/photonic/instagram/#own-photos">User photos and videos</a>
					</td>
					<td>No such feature in Instagram</td>
					<td>No such feature in Instagram</td>
					<td><a href="https://aquoid.com/plugins/photonic/instagram/#auth-setup">Back-end / server-side</a>
					</td>
					<td>All; Image Lightbox, PrettyPhoto and StripJS cannot handle videos</td>
				</tr>
			</table>
		</div>
		<?php
	}

	private function build_gallery() {
		?>
		<div id="photonic-start-create" class="photonic-start-panel">
			<h2 class="photonic-section">Showing a Gallery</h2>
			<p>
				While using the Classic Editor a gallery is displayed using the <code>[gallery]</code> shortcode. If the
				Gutenberg Block Editor is
				used a gallery is displayed using a Gutenberg Block instead. Given the plethora of options and
				configurations offered in Photonic,
				things have been made easier by virtue of a shortcode insertion UI. To use it:
			</p>

			<ol>
				<li>
					<h4>Getting Started</h4>
					If you are using the "Classic Editor" click on the "Add Media" button or the "Add / Edit Photonic
					Gallery" button:<br/>
					<img src="<?php echo esc_url(PHOTONIC_URL) . 'screenshot-2.jpg'; ?>" alt="'Add Media' Button"/><br/><br/>

					Correspondingly if you are using "Gutenberg" use the Photonic Gallery block:<br/>
					<img src="<?php echo esc_url(PHOTONIC_URL) . 'screenshot-1.png'; ?>" alt="Gutenberg Block"/>
				</li>

				<li>
					<h4>Building a Gallery</h4>
					<ol>
						<li>
							<strong>Via "Add Media"</strong>
							<ol>
								<li>
									Click on the "Photonic" tab:<br/>
									<img src="<?php echo esc_url(PHOTONIC_URL) . 'screenshot-5.jpg'; ?>"
										 alt="Media Uploader"/><br/>
								</li>
								<li>
									Pick your source:<br/>
									<img src="<?php echo esc_url(PHOTONIC_URL) . 'screenshot-6.jpg'; ?>"
										 alt="Sources in Media Uploader"/><br/>
								</li>
								<li>
									Fill out the attributes for the gallery:<br/>
									<img src="<?php echo esc_url(PHOTONIC_URL) . 'screenshot-8.png'; ?>"
										 alt="Gallery attributes"/><br/>
								</li>
							</ol>
						</li>
						<li>
							<strong>Wizard Via "Add / Edit Photonic Gallery", or the "Photonic Gallery" block</strong>
							<ol>
								<li>
									You are presented with a wizard to pick the source for your gallery:<br/>
									<img src="<?php echo esc_url(PHOTONIC_URL) . 'screenshot-3.png'; ?>"
										 alt="Wizard Starting Screen"/><br/>
								</li>
								<li>
									You will be shown a contextual set of options based on your choices:<br/>
									<img src="<?php echo esc_url(PHOTONIC_URL) . 'screenshot-4.png'; ?>"
										 alt="Contextual options in the Wizard"/><br/>
								</li>
							</ol>
						</li>
					</ol>
				</li>
				<li>
					Depending on which gallery you inserted you will be shown a placeholder of this sort:<br/>
					<img src="<?php echo esc_url(PHOTONIC_URL) . 'screenshot-7.png'; ?>"
						 alt="Gallery shows up with a placeholder"/>
				</li>
				<li>
					Clicking on the placeholder will let you edit the gallery you inserted.<br/>
					Note that if you are using the Visual Editor, you <em>may</em> encounter TinyMCE conflicts with
					other plugins. In case of such a conflict
					please <a href="https://wordpress.org/support/plugin/photonic/">report a bug</a>, and disable the
					Visual Editor integration from
					<em>Photonic &rarr; Settings &rarr; Generic Options &rarr; Generic Settings &rarr; Disable shortcode
						editing in Visual Editor</em>. The plugin will
					continue working normally &ndash; the only difference is that you will have to do shortcode edits by
					hand via the Text Editor rather than via a UI
					in the Visual Editor.
				</li>
			</ol>
		</div>
		<?php
	}

	private function layouts() {
		?>
		<div id="photonic-start-layouts" class="photonic-start-panel">
			<h2 class="photonic-section">Gallery Layouts</h2>
			<p>
				Photonic supports the following types of layouts:
			</p>
			<ol>
				<li><a href="https://aquoid.com/plugins/photonic/layouts/#square">Square thumbnails</a></li>
				<li><a href="https://aquoid.com/plugins/photonic/layouts/#circle">Circular thumbnails</a></li>
				<li><a href="https://aquoid.com/plugins/photonic/layouts/#slideshow">Slideshow</a></li>
				<li><a href="https://aquoid.com/plugins/photonic/layouts/#justified">Random Justified Grid</a></li>
				<li><a href="https://aquoid.com/plugins/photonic/layouts/#masonry">Masonry</a></li>
				<li><a href="https://aquoid.com/plugins/photonic/layouts/#mosaic">Mosaic</a></li>
			</ol>
		</div>
		<?php
	}

	private function lightbox() {
		$lightboxes = [
			'baguetteBox'   => [
				'name'     => 'BaguetteBox',
				'url'      => 'https://feimosi.github.io/baguetteBox.js/',
				'size'     => '~10KB JS, ~4KB CSS',
				'supports' => ['bundled', 'deepLinking', 'html5', 'noJQ', 'socialSharing', 'touch'],
				'license'  => 'MIT',
				'notes'    => 'No support for YouTube / Vimeo',
			],
			'bigPicture'    => [
				'name'     => 'BigPicture',
				'url'      => 'https://henrygd.me/bigpicture/',
				'size'     => '~9KB JS, no CSS',
				'supports' => ['bundled', 'deepLinking', 'html5', 'noJQ', 'socialSharing', 'touch', 'ytVimeo'],
				'license'  => 'MIT',
				'notes'    => 'No support for videos in galleries',
			],
			'colorbox'      => [
				'name'     => 'Colorbox',
				'url'      => 'https://jacklmoore.com/colorbox/',
				'size'     => '~10KB JS, 5KB CSS',
				'supports' => ['autoStart', 'bundled', 'deepLinking', 'html5', 'socialSharing', 'touch', 'ytVimeo'],
				'license'  => 'MIT',
			],
			'fancybox'      => [
				'name'     => 'Fancybox1',
				'url'      => 'http://fancybox.net/',
				'size'     => '~16KB JS, 9KB CSS',
				'supports' => ['autoStart', 'html5', 'touch', 'ytVimeo'],
				'license'  => 'MIT, GPL',
			],
			'fancybox2'     => [
				'name'     => 'Fancybox2',
				'url'      => 'https://fancyapps.com/fancybox/',
				'size'     => '~23KB JS, 5KB CSS',
				'supports' => ['autoStart', 'deepLinking', 'socialSharing', 'html5', 'thumbnails', 'touch', 'ytVimeo'],
				'license'  => 'CC-BY-NC 3.0',
			],
			'fancybox3'     => [
				'name'     => 'Fancybox3',
				'url'      => 'https://fancyapps.com/fancybox/3',
				'size'     => '~61KB JS, 14KB CSS',
				'supports' => ['autoStart', 'bundled', 'deepLinking', 'html5', 'socialSharing', 'thumbnails', 'touch', 'ytVimeo'],
				'license'  => 'GPL v3',
			],
			'featherlight'  => [
				'name'     => 'Featherlight',
				'url'      => 'https://noelboss.github.io/featherlight/',
				'size'     => '~13KB JS, 5KB CSS',
				'supports' => ['bundled', 'deepLinking', 'html5', 'socialSharing', 'touch', 'ytVimeo'],
				'license'  => 'MIT',
			],
			'glightbox'     => [
				'name'     => '"Gie" Lightbox (GLightbox)',
				'url'      => 'https://biati-digital.github.io/glightbox/',
				'size'     => '~54KB JS, 14KB CSS',
				'supports' => ['bundled', 'deepLinking', 'html5', 'noJQ', 'socialSharing', 'touch', 'ytVimeo'],
				'license'  => 'MIT',
			],
			'imageLightbox' => [
				'name'     => 'Image Lightbox',
				'url'      => 'https://osvaldas.info/image-lightbox-responsive-touch-friendly',
				'size'     => '~6KB JS, 5KB CSS',
				'supports' => ['bundled', 'deepLinking', 'socialSharing', 'touch'],
				'license'  => 'MIT',
				'notes'    => 'No video support',
			],
			'lightCase'     => [
				'name'     => 'LightCase',
				'url'      => 'https://cornel.bopp-art.com/lightcase/',
				'size'     => '~26KB JS, 14KB CSS',
				'supports' => ['autoStart', 'bundled', 'deepLinking', 'html5', 'socialSharing', 'touch', 'ytVimeo'],
				'license'  => 'GPL',
			],
			'lightGallery'  => [
				'name'     => 'LightGallery',
				'url'      => 'https://lightgalleryjs.com/',
				'size'     => '~52KB JS, 21KB CSS, ~26KB Fonts, + Additional Plugins',
				'supports' => ['autoStart', 'bundled', 'deepLinking', 'html5', 'noJQ', 'socialSharing', 'thumbnails', 'touch', 'ytVimeo'],
				'license'  => 'GPL v3',
			],
			'magnific'      => [
				'name'     => 'Magnific Popup',
				'url'      => 'https://dimsemenov.com/plugins/magnific-popup/',
				'size'     => '~20KB JS, 7KB CSS',
				'supports' => ['deepLinking', 'html5', 'socialSharing', 'touch', 'ytVimeo'],
				'license'  => 'MIT',
			],
			'photoSwipe'    => [
				'name'     => 'PhotoSwipe',
				'url'      => 'https://github.com/dimsemenov/PhotoSwipe/tree/v4.1.3',
				'size'     => '~41KB JS, 11KB CSS',
				'supports' => ['bundled', 'deepLinking', 'html5', 'noJQ', 'socialSharing', 'touch', 'ytVimeo'],
				'license'  => 'MIT',
				'notes'    => 'No video support for Flickr',
			],
			'photoSwipe5'    => [
				'name'     => 'PhotoSwipe v5',
				'url'      => 'https://photoswipe.com/',
				'size'     => '~66KB JS, 5KB CSS',
				'supports' => ['bundled', 'deepLinking', 'html5', 'noJQ', 'socialSharing', 'touch', 'ytVimeo'],
				'license'  => 'MIT',
				'notes'    => 'No video support for Flickr',
			],
			'prettyPhoto'   => [
				'name'     => 'PrettyPhoto',
				'url'      => 'http://www.no-margin-for-errors.com/projects/prettyphoto-jquery-lightbox-clone/',
				'size'     => '~23KB JS, 27KB CSS',
				'supports' => ['autoStart', 'deepLinking', 'socialSharing', 'thumbnails', 'touch', 'ytVimeo'],
				'license'  => 'GPL v2.0',
				'notes'    => 'YouTube / Vimeo supported, but not other videos'
			],
			'strip'         => [
				'name'     => 'Strip',
				'url'      => 'http://stripjs.com',
				'size'     => '~39KB JS, 13KB CSS',
				'supports' => ['bundled', 'deepLinking', 'touch', 'ytVimeo'],
				'license'  => 'CC-BY 4.0',
				'notes'    => 'YouTube / Vimeo supported, but not other videos',
			],
			'spotlight'     => [
				'name'     => 'Spotlight',
				'url'      => 'https://nextapps-de.github.io/spotlight/',
				'size'     => '~10KB JS, 11KB CSS',
				'supports' => ['autoStart', 'bundled', 'deepLinking', 'html5', 'noJQ', 'socialSharing', 'touch', 'ytVimeo'],
				'license'  => 'Apache 2.0',
			],
			'swipebox'      => [
				'name'     => 'Swipebox',
				'url'      => 'https://brutaldesign.github.io/swipebox/',
				'size'     => '~12KB JS, 5KB CSS',
				'supports' => ['bundled', 'deepLinking', 'html5', 'socialSharing', 'touch', 'ytVimeo'],
				'license'  => 'MIT',
			],
			'thickbox'      => [
				'name'     => 'Thickbox',
				'url'      => 'http://codylindley.com/thickbox/',
				'size'     => '~12KB JS',
				'supports' => ['bundled', 'deepLinking', 'html5', 'socialSharing', 'touch', 'ytVimeo'],
				'license'  => 'MIT',
				'notes'    => 'No video support'
			],
			'venobox'      => [
				'name'     => 'VenoBox',
				'url'      => 'https://veno.es/venobox/',
				'size'     => '~16KB JS, ~15KB CSS',
				'supports' => ['bundled', 'deepLinking', 'html5', 'noJQ', 'socialSharing', 'touch', 'ytVimeo'],
				'license'  => 'MIT',
			],
		];
		$lightboxes_json = wp_json_encode($lightboxes);

		$supports = [
			'autoStart'     => '<abbr title="When you open a gallery in a lightbox, this lets the lightbox run in a slideshow mode automatically without the user driving the navigation.">Auto-start slideshow</abbr>',
			'bundled'       => 'Bundled with Photonic',
			'deepLinking'   => '<abbr title="Deep-linking assigns a URL to every image in a gallery. If you enter that URL directly in a browser\'s address bar, it automatically opens the image in a lightbox.">Deep-linking</abbr>',
			'html5'         => 'HTML5 Videos from Flickr etc.',
			'noJQ'          => 'No <abbr title="jQuery adds a ~95KB file to the pages. It is commonly used by themes and plugins, so even if Photonic doesn\'t load jQuery, something else might.">jQuery</abbr> required',
			'socialSharing' => '<abbr title="If deep-linking is enabled, you have the option to display social sharing links. Photonic can help you share links on Facebook, Twitter and Google+.">Social Sharing</abbr>',
			'thumbnails'    => '<abbr title="This capability helps display thumbnails for all your images within the lightbox.">Thumbnails</abbr>',
			'touch'         => 'Touch / gestures',
			'ytVimeo'       => 'YouTube, Vimeo etc.',
		];
		?>
		<div id="photonic-start-lightbox" class="photonic-start-panel">
			<h2 class="photonic-section">Which Lightbox?</h2>
			<p>
				Photonic includes several lightboxes and supports some others that it cannot include due to licensing
				reasons. Each lightbox has
				its own strengths &mdash; some are lightweight, some are full-featured and some are very elegant
				looking. You can pick one based on the
				features that you feel are most important to you. Note that out-of-the-box, several of these don't
				support features such as touch gestures, or deep-linking,
				but for Photonic those capabilities have been integrated into the scripts via other approaches.
			</p>

			<table class="form-table photonic-form-table" id="photonic-lightboxes">
				<tr>
					<th>Features</th>
					<th>Lightboxes</th>
				</tr>

				<tr>
					<td id="lightbox-features">
						<ul>
							<?php
							foreach ($supports as $feature => $text) {
								?>
								<li>
									<label>
										<input type="checkbox" value="<?php echo esc_attr($feature); ?>" />
										<?php echo wp_kses_post($text); ?>
									</label>
								</li>
								<?php
							}
							?>
						</ul>
					</td>

					<td id="lightbox-list">
						<ul>
							<?php
							foreach ($lightboxes as $lb => $lightbox) {
								?>
								<li id="photonic-lb-<?php echo esc_attr($lb); ?>">
									<button id="photonic-lb-button-<?php echo esc_attr($lb); ?>"
											style="border: none; cursor: pointer; ">
										<span class="dashicons dashicons-forms"></span>
									</button>
									<a href="<?php echo esc_url($lightbox['url']); ?>"><?php echo wp_kses_post($lightbox['name']); ?></a>
									(<?php echo wp_kses_post($lightbox['size']); ?>)
									<strong>License</strong> &ndash; <?php echo wp_kses_post($lightbox['license']); ?>
									<?php
									if (!empty($lightbox['notes'])) {
										?>
										; <?php echo wp_kses_post($lightbox['notes']); ?>
										<?php
									}
									?>
								</li>
								<?php
							}
							?>
						</ul>
					</td>
				</tr>
				<script type="text/javascript">
					document.addEventListener('DOMContentLoaded', function () {
						const lightboxes = <?php echo wp_kses_post($lightboxes_json); ?>;
						const checkboxes = document.querySelectorAll('#lightbox-features input');
						let matches;
						checkboxes.forEach(function (checkbox) {
							checkbox.addEventListener('change', function () {
								photonicResetButtonsAndCheckboxes(false, false);
								const checked = document.querySelectorAll('#lightbox-features input:checked');
								matches = [];
								let iterator = 0;
								checked.forEach(function (feature) {
									for (const [key, value] of Object.entries(lightboxes)) {
										if (iterator === 0 || matches.indexOf(key) >= 0) {
											const inArray = (matches.indexOf(key) >= 0);
											const supportList = value.supports;
											let found = false;
											for (let idx = 0; idx < supportList.length; idx++) {
												if (typeof supportList[idx] === 'string') {
													if (feature.value === supportList[idx]) {
														if (!inArray) {
															matches.push(key)
														}
														found = true;
														break;
													}
												}
												else {
													if (feature.value === supportList[idx].type) {
														if (!inArray) {
															matches.push(key);
														}
														found = true;
														break;
													}
												}
											}

											if (!found) {
												const pos = matches.indexOf(key);
												if (pos > -1) {
													matches.splice(pos, 1);
												}
											}
										}
									}
									iterator++;
								});
								photonicShowLightboxMatches(checked, matches);
							});
						});

						const buttons = document.querySelectorAll('#lightbox-list button');
						buttons.forEach(function (button) {
							button.addEventListener('click', function (e) {
								e.preventDefault();
								photonicShowLightboxFeatures(button);
							});
						});

						function photonicShowLightboxMatches(checked, matches) {
							let all = document.querySelectorAll('#lightbox-list li');
							all.forEach(function (line) {
								const lb = line.getAttribute('id').substr(12);
								line.classList.remove('matching');
								line.classList.remove('not-matching');
								if (checked.length > 0 && matches.indexOf(lb) > -1) {
									line.classList.add('matching');
								}
								else if (checked.length > 0 && matches.indexOf(lb) < 0) {
									line.classList.add('not-matching');
								}
							});
						}

						function photonicShowLightboxFeatures(button) {
							const lightbox = button.getAttribute('id').substring('photonic-lb-button-'.length);
							if (lightboxes[lightbox]) {
								photonicResetButtonsAndCheckboxes(true, true);
								checkboxes.forEach(function (checkbox) {
									const li = checkbox.closest('li');
									if (lightboxes[lightbox]['supports'].indexOf(checkbox.value) > -1) {
										li.classList.remove('photonic-not-supported');
										li.classList.add('photonic-supported');
									}
									else {
										li.classList.remove('photonic-supported');
										li.classList.add('photonic-not-supported');
									}
								});

								button.closest('li').classList.add('lb-selected');
							}
						}

						function photonicResetButtons(unmatch) {
							let remove = ['lb-selected'];
							if (unmatch) {
								remove = remove.concat(['matching', 'not-matching']);
							}
							buttons.forEach(function (button) {
								const li = button.closest('li');
								remove.forEach(function (css) {
									li.classList.remove(css);
								})
							});
						}

						function photonicResetCheckboxes(uncheck) {
							checkboxes.forEach(function (checkbox) {
								if (uncheck) {
									checkbox.checked = false;
								}
								checkbox.closest('li').classList.remove('photonic-supported', 'photonic-not-supported');
							});
						}

						function photonicResetButtonsAndCheckboxes(unmatch, uncheck) {
							photonicResetButtons(unmatch);
							photonicResetCheckboxes(uncheck);
						}
					});
				</script>
			</table>
		</div>
		<?php
	}

	private function secret_menu() {
		?>
		<div id="photonic-start-secret" class="photonic-start-panel">
			<h2 class="photonic-section">The Secret Menu</h2>
			<p>
				While the shortcode generator for Photonic is fairly comprehensive, there are some additional hidden
				shortcode parameters that you can use. These are baked into the
				interactive shortcode builder / Photonic block for Gutenberg, but will not show up in the traditional
				shortcode interface:
			</p>
			<ol>
				<li><code>title_position</code> &ndash; Takes values <code>none</code>, <code>regular</code>, <code>tooltip</code>,
					<code>below</code>, <code>hover-slideup-show</code> and <code>slideup-stick</code>. It overrides the
					title positioning that you have set for that particular provider through your options.
				</li>
				<li><code>headers</code> &ndash; Takes a comma-separated list containing none or more of
					<code>thumbnail</code>, <code>title</code>, and <code>counter</code>.
					If a value is specified, that particular component will be shown in the level 2 entity's (i.e. album
					/ gallery / set) header, regardless of the back-end options.
					So, setting <code>headers='title,counter'</code> for a particular call to display a Zenfolio set,
					will show the title and photo-count for that set in the header, and not its thumbnail.
				</li>
				<li><code>fx</code> &ndash; Applicable to slideshow layouts. Takes values <code>fade</code> and <code>slide</code>
					to provide the transitioning effect between slides.
				</li>
				<li><code>speed</code> &ndash; Applicable to slideshow layouts. Takes numeric values in milliseconds,
					determines the speed of transitioning of slides.
				</li>
				<li><code>timeout</code> &ndash; Applicable to slideshow layouts. Takes numeric values in milliseconds,
					determines the pause between two slides.
				</li>
				<li><code>pause</code> &ndash; Applicable to slideshow layouts. Takes values <code>0</code> (no pause)
					and <code>1</code> (pause), determines if the slideshow should pause upon hovering on it.
				</li>
				<li><code>controls</code> &ndash; Applicable to slideshow layouts. Takes values <code>show</code> and
					<code>hide</code>, and shows "Previous" and "Next" buttons on the slideshow.
				</li>
				<li><code>strip-style</code> &ndash; Applicable to slideshow layouts. Takes values <code>thumbs</code>
					and <code>button</code>. If set, and if the <code>layout</code> / <code>style</code> parameter is
					set to <code>strip-below</code>, <code>strip-above</code>, <code>strip-right</code>, it shows
					buttons <strong>below</strong> the slideshow.
				</li>
				<li><code>popup</code> &ndash; Takes values <code>show</code> and <code>hide</code>. If set, and if a
					level 2 thumbnail (i.e. an Album, Photoset or Gallery thumbnail) is being displayed, this setting
					overrides <em>Photonic &rarr; Settings &rarr; Generic Options &rarr; Overlaid Popup Panel &rarr;
						Enable Interim Popup for Album Thumbnails</em>.
				</li>
			</ol>
		</div>
		<?php
	}

	private function helper_shortcode() {
		?>
		<div id="photonic-start-helpers" class="photonic-start-panel">
			<h2 class="photonic-section">The Helper Shortcode</h2>
			<p>
				There is a second utility shortcode present in the plugin, which displays the output of the helpers but
				<em>in the front-end</em>.
			</p>
			<ol>
				<li><strong>Short-code: </strong><code>photonic_helper</code></li>
				<li>
					<strong>Attributes: </strong> This primary attribute is <code>type</code>. Depending on the value of
					<code>type</code> other attributes can be passed:
					<ol>
						<li>
							<code>type='flickr'</code> - Additional attributes:
							<ol>
								<li><code>user='xxx'</code> - Passing the user name (i.e. from
									https://flickr.com/photos/xxx) will get the user_id for use in the Flickr shortcode
								</li>
								<li><code>group='yyy'</code> - Passing the group name will get the group_id for use in
									the Flickr shortcode
								</li>
							</ol>
						</li>
						<li>
							<code>type='google'</code> - Additional attributes:
							<ol>
								<li><code>album_type='self'</code> - Prints out the user's Google Photos albums in
									tabular form
								</li>
								<li><code>album_type='shared'</code> - Prints out the all Google Photos albums shared
									with and by the user in tabular form
								</li>
							</ol>
						</li>
					</ol>
				</li>
			</ol>
		</div>
		<?php
	}

	private function really_technical() {
		?>
		<div id="photonic-start-tech" class="photonic-start-panel">
			<h2 class="photonic-section">The Really Technical Stuff</h2>
			<p>
				You are running PHP with the following details:
			</p>
			<table class="form-table photonic-form-table">
				<tr>
					<th>PHP Version</th>
					<td><?php echo wp_kses_post(phpversion()); ?></td>
				</tr>
				<tr>
					<th>Loaded Extensions</th>
					<td><?php echo wp_kses_post(implode(', ', get_loaded_extensions())); ?></td>
				</tr>
			</table>
		</div>
		<?php
	}
}
