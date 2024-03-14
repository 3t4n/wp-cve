<?php
if ( ! class_exists( 'WP_Snow' ) ) :

	/**
	 * Main WP_Snow Class.
	 *
	 * @since 1.0.0
	 * @author Ironikus <info@ironikus.com>
	 */
	class WP_Snow{

		public function __construct() {
			$this->add_hooks();
			$this->is_inactive = ( get_option( 'wps_flakes_deactivate' ) === 'yes' ) ? true : false;
			$this->is_font_awesome_active = ( get_option( 'wps_activate_font_awesome' ) === 'yes' ) ? true : false;
			$this->settings = $this->load_settings();
		}

		public function add_hooks(){
			add_action( 'wp_enqueue_scripts', array( $this, 'add_snow_and_settings' ), 10 );
			add_action( 'admin_enqueue_scripts', array( $this, 'add_snow_and_settings' ), 10 );

			add_action( 'admin_menu', array( $this, 'add_user_submenu' ), 150 );
		}

		/**
		 * ##################
         * ###
         * #### MENU LOGIC
         * ###
		 * ##################
		 */
		
		public function get_admin_cap(){
		    return apply_filters( 'wp_snow_admin_capability', 'manage_options' );
        }

		/**
		 * Add our custom admin user page
		 */
		public function add_user_submenu(){
			add_submenu_page( 'options-general.php', __( 'WP Snow', 'wp-snow' ), __( 'WP Snow', 'wp-snow' ), $this->get_admin_cap(), sanitize_title( WPSNOW_NAME ), array( $this, 'render_admin_submenu_page' ) );
		}

		/**
		 * Render the admin submenu page
		 *
		 * You need the specified capability to edit it.
		 */
		public function render_admin_submenu_page(){
			if( ! current_user_can( $this->get_admin_cap() ) ){
				wp_die( __( 'Sorry, but you don\'t have enough permission.', 'wp-snow' ) );
			}

			include( WPSNOW_PLUGIN_DIR . 'core/partials/settings.php' );

		}

		/**
		 * ##################
         * ###
         * #### SETTINGS LOGIC
         * ###
		 * ##################
		 */

		public function reload_settings(){
			$this->settings = $this->load_settings();
        }

		private function load_settings(){

			$fa_icons = array_merge( array( 'empty' => 'No Icon' ), array_flip( $this->get_fontawesome_icons() ) );

			$fields = array(

				'wps_flakes_deactivate' => array(
					'id'          => 'wps_flakes_deactivate',
					'type'        => 'checkbox',
					'label'       => __( 'Deactivate Snowflakes', 'wp-snow' ),
					'placeholder' => '',
					'required'    => false,
					'description' => __( 'Check this button in case you want to temporarily deactivate the snow flakes.', 'wp-snow' )
				),

				'wps_flakes_number' => array(
					'id'          => 'wps_flakes_number',
					'type'        => 'number',
					'label'       => __( 'Number of flakes', 'wp-snow' ),
					'placeholder' => 45,
					'required'    => false,
					'description' => __( 'This is the total number of flakes that should be visible on one page at once.', 'wp-snow' )
				),

				'wps_flakes_falling_speed' => array(
					'id'          => 'wps_flakes_falling_speed',
					'type'        => 'text',
					'label'       => __( 'Flakes falling speed', 'wp-snow' ),
					'placeholder' => 0.5,
					'required'    => false,
					'description' => __( 'The falling speed of the flakes. Please include a value like 0.1 or 5.0 - the higher the value, the faster they will fall.', 'wp-snow' )
				),

				'wps_flakes_max_size' => array(
					'id'          => 'wps_flakes_max_size',
					'type'        => 'number',
					'label'       => __( 'Flakes maximum size', 'wp-snow' ),
					'placeholder' => 30,
					'required'    => false,
					'description' => __( 'Please include a number for the maximum size of your flakes. The higher the bigger.', 'wp-snow' )
				),

				'wps_flakes_min_size' => array(
					'id'          => 'wps_flakes_min_size',
					'type'        => 'number',
					'label'       => __( 'Flakes minimum size', 'wp-snow' ),
					'placeholder' => 12,
					'required'    => false,
					'description' => __( 'Please include a number for the minimum size of your flakes. The lower the smaller.', 'wp-snow' )
				),

				'wps_flakes_refresh_time' => array(
					'id'          => 'wps_flakes_refresh_time',
					'type'        => 'number',
					'label'       => __( 'Flakes refresh time', 'wp-snow' ),
					'placeholder' => 50,
					'required'    => false,
					'description' => __( 'Please include a number for the refresh time of your flakes. This is the time it takes to make the next move. Measured in milliseconds.', 'wp-snow' )
				),

				'wps_flakes_z_index' => array(
					'id'          => 'wps_flakes_z_index',
					'type'        => 'number',
					'label'       => __( 'Flakes z-index', 'wp-snow' ),
					'placeholder' => 50,
					'required'    => false,
					'description' => __( 'The z-index determines the depth of your flakes. the higher the number, the more likely it is that they are placed over all available elements.', 'wp-snow' )
				),

				'wps_flakes_entity' => array(
					'id'          => 'wps_flakes_entity',
					'type'        => 'text',
					'label'       => __( 'Flakes Entity', 'wp-snow' ),
					'placeholder' => "*",
					'required'    => false,
					'description' => __( 'This is what will be shown as a flake. Please note, that special characters are sanitized so that they will be visible within the frontend. Double qotes are replaced with single quotes.', 'wp-snow' )
				),

				'wps_flakes_styles' => array(
					'id'          => 'wps_flakes_styles',
					'type'        => 'text',
					'label'       => __( 'Additional Flake Styles', 'wp-snow' ),
					'placeholder' => "color:green;border:1px solid #000;",
					'required'    => false,
					'description' => __( 'In here you can add custom styles you want to attach to every snow flake.', 'wp-snow' )
				),

				'wps_flakes_color' => array(
					'id'          => 'wps_flakes_color',
					'type'        => 'text',
					'label'       => __( 'Flakes color', 'wp-snow' ),
					'placeholder' => "#aaaacc,#ddddff,#ccccdd,#f3f3f3,#f0ffff",
					'required'    => false,
					'description' => __( 'You can add multiple colors for your flakes with a comma separated. You can pick your colors easily on <a target="_blank" href="https://www.color-hex.com/">https://www.color-hex.com/</a>', 'wp-snow' )
				),

				'wps_flakes_font' => array(
					'id'          => 'wps_flakes_font',
					'type'        => 'text',
					'label'       => __( 'Flakes Fonts', 'wp-snow' ),
					'placeholder' => "Times,Arial,Times,Verdana",
					'required'    => false,
					'description' => __( 'You can add multiple fonts for your flakes with a comma separated. These fonts will affect how your snowflake looks.', 'wp-snow' )
				),

				'wps_activate_font_awesome' => array(
					'id'          => 'wps_activate_font_awesome',
					'type'        => 'checkbox',
					'label'       => __( 'Activate Font Awesome', 'wp-snow' ),
					'placeholder' => "",
					'required'    => false,
					'description' => __( 'This allows you to add font awesome icons as your falling snow flakes. The additional settings appear once you click the save button.', 'wp-snow' )
				),

				'wps_choose_fa_icon' => array(
					'id'          => 'wps_choose_fa_icon',
					'type'        => 'select',
					'label'       => __( 'Choose Fontawesome Icon', 'wp-snow' ),
					'choices'     => $fa_icons,
					'disabled'    => ( get_option( 'wps_activate_font_awesome' ) === 'yes' ) ? false : true,
					'placeholder' => "",
					'required'    => false,
					'description' => __( 'If you choose an icon from this list, the default snowflake entity will be overwritten.', 'wp-snow' )
				),

				'wps_show_on_specific_pages_only' => array(
					'id'          => 'wps_show_on_specific_pages_only',
					'type'        => 'text',
					'label'       => __( 'Show only on specific posts or pages.', 'wp-snow' ),
					'placeholder' => "18,174,2",
					'required'    => false,
					'description' => __( 'Please specify the post ids or page ids on which you want to display the snow. If you set values within this field, the snow doesn\' show up anywhere else except of this posts and pages.', 'wp-snow' )
				),

			);

			foreach( $fields as $key => $field ){
				$value = get_option( $key );

				$fields[ $key ]['value'] = $value;

				if( $fields[ $key ]['type'] == 'checkbox' ){
					if( empty( $fields[ $key ]['value'] ) || $fields[ $key ]['value'] == 'no' ){
						$fields[ $key ]['value'] = 'no';
					} else {
						$fields[ $key ]['value'] = 'yes';
					}
				}
			}

			return apply_filters('wp_snow_filter_snow_settings', $fields);
		}

		public function get_fontawesome_icons(){
		    $icons = '["ad","address-book","address-card","adjust","air-freshener","align-center","align-justify","align-left","align-right","allergies","ambulance","american-sign-language-interpreting","anchor","angle-double-down","angle-double-left","angle-double-right","angle-double-up","angle-down","angle-left","angle-right","angle-up","angry","ankh","apple-alt","archive","archway","arrow-alt-circle-down","arrow-alt-circle-left","arrow-alt-circle-right","arrow-alt-circle-up","arrow-circle-down","arrow-circle-left","arrow-circle-right","arrow-circle-up","arrow-down","arrow-left","arrow-right","arrow-up","arrows-alt","arrows-alt-h","arrows-alt-v","assistive-listening-systems","asterisk","at","atlas","atom","audio-description","award","baby","baby-carriage","backspace","backward","bacon","balance-scale","ban","band-aid","barcode","bars","baseball-ball","basketball-ball","bath","battery-empty","battery-full","battery-half","battery-quarter","battery-three-quarters","bed","beer","bell","bell-slash","bezier-curve","bible","bicycle","binoculars","biohazard","birthday-cake","blender","blender-phone","blind","blog","bold","bolt","bomb","bone","bong","book","book-dead","book-medical","book-open","book-reader","bookmark","bowling-ball","box","box-open","boxes","braille","brain","bread-slice","briefcase","briefcase-medical","broadcast-tower","broom","brush","bug","building","bullhorn","bullseye","burn","bus","bus-alt","business-time","calculator","calendar","calendar-alt","calendar-check","calendar-day","calendar-minus","calendar-plus","calendar-times","calendar-week","camera","camera-retro","campground","candy-cane","cannabis","capsules","car","car-alt","car-battery","car-crash","car-side","caret-down","caret-left","caret-right","caret-square-down","caret-square-left","caret-square-right","caret-square-up","caret-up","carrot","cart-arrow-down","cart-plus","cash-register","cat","certificate","chair","chalkboard","chalkboard-teacher","charging-station","chart-area","chart-bar","chart-line","chart-pie","check","check-circle","check-double","check-square","cheese","chess","chess-bishop","chess-board","chess-king","chess-knight","chess-pawn","chess-queen","chess-rook","chevron-circle-down","chevron-circle-left","chevron-circle-right","chevron-circle-up","chevron-down","chevron-left","chevron-right","chevron-up","child","church","circle","circle-notch","city","clinic-medical","clipboard","clipboard-check","clipboard-list","clock","clone","closed-captioning","cloud","cloud-download-alt","cloud-meatball","cloud-moon","cloud-moon-rain","cloud-rain","cloud-showers-heavy","cloud-sun","cloud-sun-rain","cloud-upload-alt","cocktail","code","code-branch","coffee","cog","cogs","coins","columns","comment","comment-alt","comment-dollar","comment-dots","comment-medical","comment-slash","comments","comments-dollar","compact-disc","compass","compress","compress-arrows-alt","concierge-bell","cookie","cookie-bite","copy","copyright","couch","credit-card","crop","crop-alt","cross","crosshairs","crow","crown","crutch","cube","cubes","cut","database","deaf","democrat","desktop","dharmachakra","diagnoses","dice","dice-d20","dice-d6","dice-five","dice-four","dice-one","dice-six","dice-three","dice-two","digital-tachograph","directions","divide","dizzy","dna","dog","dollar-sign","dolly","dolly-flatbed","donate","door-closed","door-open","dot-circle","dove","download","drafting-compass","dragon","draw-polygon","drum","drum-steelpan","drumstick-bite","dumbbell","dumpster","dumpster-fire","dungeon","edit","egg","eject","ellipsis-h","ellipsis-v","envelope","envelope-open","envelope-open-text","envelope-square","equals","eraser","ethernet","euro-sign","exchange-alt","exclamation","exclamation-circle","exclamation-triangle","expand","expand-arrows-alt","external-link-alt","external-link-square-alt","eye","eye-dropper","eye-slash","fast-backward","fast-forward","fax","feather","feather-alt","female","fighter-jet","file","file-alt","file-archive","file-audio","file-code","file-contract","file-csv","file-download","file-excel","file-export","file-image","file-import","file-invoice","file-invoice-dollar","file-medical","file-medical-alt","file-pdf","file-powerpoint","file-prescription","file-signature","file-upload","file-video","file-word","fill","fill-drip","film","filter","fingerprint","fire","fire-alt","fire-extinguisher","first-aid","fish","fist-raised","flag","flag-checkered","flag-usa","flask","flushed","folder","folder-minus","folder-open","folder-plus","font","football-ball","forward","frog","frown","frown-open","funnel-dollar","futbol","gamepad","gas-pump","gavel","gem","genderless","ghost","gift","gifts","glass-cheers","glass-martini","glass-martini-alt","glass-whiskey","glasses","globe","globe-africa","globe-americas","globe-asia","globe-europe","golf-ball","gopuram","graduation-cap","greater-than","greater-than-equal","grimace","grin","grin-alt","grin-beam","grin-beam-sweat","grin-hearts","grin-squint","grin-squint-tears","grin-stars","grin-tears","grin-tongue","grin-tongue-squint","grin-tongue-wink","grin-wink","grip-horizontal","grip-lines","grip-lines-vertical","grip-vertical","guitar","h-square","hamburger","hammer","hamsa","hand-holding","hand-holding-heart","hand-holding-usd","hand-lizard","hand-middle-finger","hand-paper","hand-peace","hand-point-down","hand-point-left","hand-point-right","hand-point-up","hand-pointer","hand-rock","hand-scissors","hand-spock","hands","hands-helping","handshake","hanukiah","hard-hat","hashtag","hat-wizard","haykal","hdd","heading","headphones","headphones-alt","headset","heart","heart-broken","heartbeat","helicopter","highlighter","hiking","hippo","history","hockey-puck","holly-berry","home","horse","horse-head","hospital","hospital-alt","hospital-symbol","hot-tub","hotdog","hotel","hourglass","hourglass-end","hourglass-half","hourglass-start","house-damage","hryvnia","i-cursor","ice-cream","icicles","id-badge","id-card","id-card-alt","igloo","image","images","inbox","indent","industry","infinity","info","info-circle","italic","jedi","joint","journal-whills","kaaba","key","keyboard","khanda","kiss","kiss-beam","kiss-wink-heart","kiwi-bird","landmark","language","laptop","laptop-code","laptop-medical","laugh","laugh-beam","laugh-squint","laugh-wink","layer-group","leaf","lemon","less-than","less-than-equal","level-down-alt","level-up-alt","life-ring","lightbulb","link","lira-sign","list","list-alt","list-ol","list-ul","location-arrow","lock","lock-open","long-arrow-alt-down","long-arrow-alt-left","long-arrow-alt-right","long-arrow-alt-up","low-vision","luggage-cart","magic","magnet","mail-bulk","male","map","map-marked","map-marked-alt","map-marker","map-marker-alt","map-pin","map-signs","marker","mars","mars-double","mars-stroke","mars-stroke-h","mars-stroke-v","mask","medal","medkit","meh","meh-blank","meh-rolling-eyes","memory","menorah","mercury","meteor","microchip","microphone","microphone-alt","microphone-alt-slash","microphone-slash","microscope","minus","minus-circle","minus-square","mitten","mobile","mobile-alt","money-bill","money-bill-alt","money-bill-wave","money-bill-wave-alt","money-check","money-check-alt","monument","moon","mortar-pestle","mosque","motorcycle","mountain","mouse-pointer","mug-hot","music","network-wired","neuter","newspaper","not-equal","notes-medical","object-group","object-ungroup","oil-can","om","otter","outdent","pager","paint-brush","paint-roller","palette","pallet","paper-plane","paperclip","parachute-box","paragraph","parking","passport","pastafarianism","paste","pause","pause-circle","paw","peace","pen","pen-alt","pen-fancy","pen-nib","pen-square","pencil-alt","pencil-ruler","people-carry","pepper-hot","percent","percentage","person-booth","phone","phone-slash","phone-square","phone-volume","piggy-bank","pills","pizza-slice","place-of-worship","plane","plane-arrival","plane-departure","play","play-circle","plug","plus","plus-circle","plus-square","podcast","poll","poll-h","poo","poo-storm","poop","portrait","pound-sign","power-off","pray","praying-hands","prescription","prescription-bottle","prescription-bottle-alt","print","procedures","project-diagram","puzzle-piece","qrcode","question","question-circle","quidditch","quote-left","quote-right","quran","radiation","radiation-alt","rainbow","random","receipt","recycle","redo","redo-alt","registered","reply","reply-all","republican","restroom","retweet","ribbon","ring","road","robot","rocket","route","rss","rss-square","ruble-sign","ruler","ruler-combined","ruler-horizontal","ruler-vertical","running","rupee-sign","sad-cry","sad-tear","satellite","satellite-dish","save","school","screwdriver","scroll","sd-card","search","search-dollar","search-location","search-minus","search-plus","seedling","server","shapes","share","share-alt","share-alt-square","share-square","shekel-sign","shield-alt","ship","shipping-fast","shoe-prints","shopping-bag","shopping-basket","shopping-cart","shower","shuttle-van","sign","sign-in-alt","sign-language","sign-out-alt","signal","signature","sim-card","sitemap","skating","skiing","skiing-nordic","skull","skull-crossbones","slash","sleigh","sliders-h","smile","smile-beam","smile-wink","smog","smoking","smoking-ban","sms","snowboarding","snowflake","snowman","snowplow","socks","solar-panel","sort","sort-alpha-down","sort-alpha-up","sort-amount-down","sort-amount-up","sort-down","sort-numeric-down","sort-numeric-up","sort-up","spa","space-shuttle","spider","spinner","splotch","spray-can","square","square-full","square-root-alt","stamp","star","star-and-crescent","star-half","star-half-alt","star-of-david","star-of-life","step-backward","step-forward","stethoscope","sticky-note","stop","stop-circle","stopwatch","store","store-alt","stream","street-view","strikethrough","stroopwafel","subscript","subway","suitcase","suitcase-rolling","sun","superscript","surprise","swatchbook","swimmer","swimming-pool","synagogue","sync","sync-alt","syringe","table","table-tennis","tablet","tablet-alt","tablets","tachometer-alt","tag","tags","tape","tasks","taxi","teeth","teeth-open","temperature-high","temperature-low","tenge","terminal","text-height","text-width","th","th-large","th-list","theater-masks","thermometer","thermometer-empty","thermometer-full","thermometer-half","thermometer-quarter","thermometer-three-quarters","thumbs-down","thumbs-up","thumbtack","ticket-alt","times","times-circle","tint","tint-slash","tired","toggle-off","toggle-on","toilet","toilet-paper","toolbox","tools","tooth","torah","torii-gate","tractor","trademark","traffic-light","train","tram","transgender","transgender-alt","trash","trash-alt","trash-restore","trash-restore-alt","tree","trophy","truck","truck-loading","truck-monster","truck-moving","truck-pickup","tshirt","tty","tv","umbrella","umbrella-beach","underline","undo","undo-alt","universal-access","university","unlink","unlock","unlock-alt","upload","user","user-alt","user-alt-slash","user-astronaut","user-check","user-circle","user-clock","user-cog","user-edit","user-friends","user-graduate","user-injured","user-lock","user-md","user-minus","user-ninja","user-nurse","user-plus","user-secret","user-shield","user-slash","user-tag","user-tie","user-times","users","users-cog","utensil-spoon","utensils","vector-square","venus","venus-double","venus-mars","vial","vials","video","video-slash","vihara","volleyball-ball","volume-down","volume-mute","volume-off","volume-up","vote-yea","vr-cardboard","walking","wallet","warehouse","water","wave-square","weight","weight-hanging","wheelchair","wifi","wind","window-close","window-maximize","window-minimize","window-restore","wine-bottle","wine-glass","wine-glass-alt","won-sign","wrench","x-ray","yen-sign","yin-yang","500px","accessible-icon","accusoft","acquisitions-incorporated","adn","adobe","adversal","affiliatetheme","airbnb","algolia","alipay","amazon","amazon-pay","amilia","android","angellist","angrycreative","angular","app-store","app-store-ios","apper","apple","apple-pay","artstation","asymmetrik","atlassian","audible","autoprefixer","avianex","aviato","aws","bandcamp","battle-net","behance","behance-square","bimobject","bitbucket","bitcoin","bity","black-tie","blackberry","blogger","blogger-b","bluetooth","bluetooth-b","bootstrap","btc","buffer","buromobelexperte","buysellads","canadian-maple-leaf","cc-amazon-pay","cc-amex","cc-apple-pay","cc-diners-club","cc-discover","cc-jcb","cc-mastercard","cc-paypal","cc-stripe","cc-visa","centercode","centos","chrome","chromecast","cloudscale","cloudsmith","cloudversify","codepen","codiepie","confluence","connectdevelop","contao","cpanel","creative-commons","creative-commons-by","creative-commons-nc","creative-commons-nc-eu","creative-commons-nc-jp","creative-commons-nd","creative-commons-pd","creative-commons-pd-alt","creative-commons-remix","creative-commons-sa","creative-commons-sampling","creative-commons-sampling-plus","creative-commons-share","creative-commons-zero","critical-role","css3","css3-alt","cuttlefish","d-and-d","d-and-d-beyond","dashcube","delicious","deploydog","deskpro","dev","deviantart","dhl","diaspora","digg","digital-ocean","discord","discourse","dochub","docker","draft2digital","dribbble","dribbble-square","dropbox","drupal","dyalog","earlybirds","ebay","edge","elementor","ello","ember","empire","envira","erlang","ethereum","etsy","evernote","expeditedssl","facebook","facebook-f","facebook-messenger","facebook-square","fantasy-flight-games","fedex","fedora","figma","firefox","first-order","first-order-alt","firstdraft","flickr","flipboard","fly","font-awesome","font-awesome-alt","font-awesome-flag","fonticons","fonticons-fi","fort-awesome","fort-awesome-alt","forumbee","foursquare","free-code-camp","freebsd","fulcrum","galactic-republic","galactic-senate","get-pocket","gg","gg-circle","git","git-square","github","github-alt","github-square","gitkraken","gitlab","gitter","glide","glide-g","gofore","goodreads","goodreads-g","google","google-drive","google-play","google-plus","google-plus-g","google-plus-square","google-wallet","gratipay","grav","gripfire","grunt","gulp","hacker-news","hacker-news-square","hackerrank","hips","hire-a-helper","hooli","hornbill","hotjar","houzz","html5","hubspot","imdb","instagram","intercom","internet-explorer","invision","ioxhost","itch-io","itunes","itunes-note","java","jedi-order","jenkins","jira","joget","joomla","js","js-square","jsfiddle","kaggle","keybase","keycdn","kickstarter","kickstarter-k","korvue","laravel","lastfm","lastfm-square","leanpub","less","line","linkedin","linkedin-in","linode","linux","lyft","magento","mailchimp","mandalorian","markdown","mastodon","maxcdn","medapps","medium","medium-m","medrt","meetup","megaport","mendeley","microsoft","mix","mixcloud","mizuni","modx","monero","napster","neos","nimblr","nintendo-switch","node","node-js","npm","ns8","nutritionix","odnoklassniki","odnoklassniki-square","old-republic","opencart","openid","opera","optin-monster","osi","page4","pagelines","palfed","patreon","paypal","penny-arcade","periscope","phabricator","phoenix-framework","phoenix-squadron","php","pied-piper","pied-piper-alt","pied-piper-hat","pied-piper-pp","pinterest","pinterest-p","pinterest-square","playstation","product-hunt","pushed","python","qq","quinscape","quora","r-project","raspberry-pi","ravelry","react","reacteurope","readme","rebel","red-river","reddit","reddit-alien","reddit-square","redhat","renren","replyd","researchgate","resolving","rev","rocketchat","rockrms","safari","salesforce","sass","schlix","scribd","searchengin","sellcast","sellsy","servicestack","shirtsinbulk","shopware","simplybuilt","sistrix","sith","sketch","skyatlas","skype","slack","slack-hash","slideshare","snapchat","snapchat-ghost","snapchat-square","soundcloud","sourcetree","speakap","speaker-deck","spotify","squarespace","stack-exchange","stack-overflow","staylinked","steam","steam-square","steam-symbol","sticker-mule","strava","stripe","stripe-s","studiovinari","stumbleupon","stumbleupon-circle","superpowers","supple","suse","symfony","teamspeak","telegram","telegram-plane","tencent-weibo","the-red-yeti","themeco","themeisle","think-peaks","trade-federation","trello","tripadvisor","tumblr","tumblr-square","twitch","twitter","twitter-square","typo3","uber","ubuntu","uikit","uniregistry","untappd","ups","usb","usps","ussunnah","vaadin","viacoin","viadeo","viadeo-square","viber","vimeo","vimeo-square","vimeo-v","vine","vk","vnv","vuejs","waze","weebly","weibo","weixin","whatsapp","whatsapp-square","whmcs","wikipedia-w","windows","wix","wizards-of-the-coast","wolf-pack-battalion","wordpress","wordpress-simple","wpbeginner","wpexplorer","wpforms","wpressr","xbox","xing","xing-square","y-combinator","yahoo","yammer","yandex","yandex-international","yarn","yelp","yoast","youtube","youtube-square","zhihu"]';

			return apply_filters('wp_snow_filter_fontawesome_icons', json_decode( $icons ));
		}

		/**
		 * ##################
         * ###
         * #### MENU LOGIC
         * ###
		 * ##################
		 */

		public function add_snow_and_settings(){
		    if( $this->is_inactive ){
		        return;
            }

            $include_posts = get_option( 'wps_show_on_specific_pages_only' );
		    if( ! empty( $include_posts ) && $include_posts !== 'no' ){
		        $single_ids = explode( ',', $include_posts );
		        if( ! in_array( get_the_ID(), $single_ids ) ){
		            return;
                }
            }

            if( is_admin() ){
                if( isset( $_GET['page'] ) && $_GET['page'] === sanitize_title( WPSNOW_NAME ) ){
	                if( $this->is_font_awesome_active ){
		                wp_enqueue_style('font-awesome-css', 'https://use.fontawesome.com/releases/v5.8.1/css/all.css', array(), WPSNOW_VERSION, 'all');
	                }
                }

                return; // Return here for admin
            }

            if( $this->is_font_awesome_active ){
	            wp_enqueue_style('font-awesome-css', 'https://use.fontawesome.com/releases/v5.8.1/css/all.css', array(), WPSNOW_VERSION, 'all');
            }

            wp_enqueue_script( 'snow-js', WPSNOW_PLUGIN_URL . 'core/assets/dist/js/snow-js.min.js', array( 'jquery' ), '1.0' );
            wp_add_inline_script( 'snow-js', $this->get_snow_settings() );

		}

		public function get_snow_settings(){

			if( $this->is_inactive ){
				return '';
			}

		    $flakes_number = get_option( 'wps_flakes_number' );
		    if( empty( $flakes_number ) || ! is_numeric( $flakes_number ) ){
			    $flakes_number = 45;
            }

		    $wps_flakes_falling_speed = get_option( 'wps_flakes_falling_speed' );
		    if( empty( $wps_flakes_falling_speed ) || ! is_numeric( $wps_flakes_falling_speed ) ){
			    $wps_flakes_falling_speed = 0.5;
            }

		    $wps_flakes_max_size = get_option( 'wps_flakes_max_size' );
		    if( empty( $wps_flakes_max_size ) || ! is_numeric( $wps_flakes_max_size ) ){
			    $wps_flakes_max_size = 30;
            }

		    $wps_flakes_min_size = get_option( 'wps_flakes_min_size' );
		    if( empty( $wps_flakes_min_size ) || ! is_numeric( $wps_flakes_min_size ) ){
			    $wps_flakes_min_size = 12;
            }

		    $wps_flakes_refresh_time = get_option( 'wps_flakes_refresh_time' );
		    if( empty( $wps_flakes_refresh_time ) || ! is_numeric( $wps_flakes_refresh_time ) ){
			    $wps_flakes_refresh_time = 50;
            }

		    $wps_flakes_z_index = get_option( 'wps_flakes_z_index' );
		    if( empty( $wps_flakes_z_index ) || ! is_numeric( $wps_flakes_z_index ) ){
			    $wps_flakes_z_index = 2500;
            }

		    $wps_flakes_entity = get_option( 'wps_flakes_entity' );
		    if( empty( $wps_flakes_entity ) || ! is_string( $wps_flakes_entity ) ){
			    $wps_flakes_entity = '*';
            }

		    $wps_flakes_color = get_option( 'wps_flakes_color' );
		    if( empty( $wps_flakes_color ) || ! is_string( $wps_flakes_color ) ){
			    $wps_flakes_color = '"#aaaacc","#ddddff","#ccccdd","#f3f3f3","#f0ffff"';
            } else {
		        $flakes_color_array = explode( ',', $wps_flakes_color );
			    $wps_flakes_color = '';
		        foreach( $flakes_color_array as $scolor ){
			        $wps_flakes_color .= '"' . $scolor . '",';
                }

			    $wps_flakes_color = trim( $wps_flakes_color, ',' );
            }

		    $wps_flakes_font = get_option( 'wps_flakes_font' );
		    if( empty( $wps_flakes_font ) || ! is_string( $wps_flakes_font ) ){
			    $wps_flakes_font = '"Times","Arial","Times","Verdana"';
            } else {
		        $flakes_font_array = explode( ',', $wps_flakes_font );
			    $wps_flakes_font = '';
		        foreach( $flakes_font_array as $sfont ){
			        $wps_flakes_font .= '"' . $sfont . '",';
                }
			    $wps_flakes_font = trim( $wps_flakes_font, ',' );
            }

            $fa_icon = get_option( 'wps_choose_fa_icon' );
		    if( $this->is_font_awesome_active && ! empty( $fa_icon ) && $fa_icon !== 'empty' ){
                $fa_icon = "<i class='fas fa-" . sanitize_title( $fa_icon ) . "'></i>";
            } else {
			    $fa_icon = false;
            }

		    $wps_flakes_styles = get_option( 'wps_flakes_styles' );
		    if( empty( $wps_flakes_styles ) || ! is_string( $wps_flakes_styles ) ){
			    $wps_flakes_styles = 'cursor: default; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; -o-user-select: none; user-select: none;';
            } else {
			    $wps_flakes_styles = 'cursor: default; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; -o-user-select: none; user-select: none;' . $wps_flakes_styles;
            }

			ob_start();
			?>
			(function( $ ) {
				var snowMax = <?php echo $flakes_number; ?>;
				var snowColor = new Array(<?php echo $wps_flakes_color; ?>);
				var snowType = new Array(<?php echo $wps_flakes_font; ?>);
				var snowEntity = "<?php echo ( ! empty( $fa_icon ) ) ? $fa_icon : str_replace( '"', '\'', do_shortcode( $wps_flakes_entity ) ); ?>";
				var snowSpeed = <?php echo $wps_flakes_falling_speed; ?>;
				var snowMaxSize = <?php echo $wps_flakes_max_size; ?>;
				var snowMinSize = <?php echo $wps_flakes_min_size; ?>;
                var snowRefresh = <?php echo $wps_flakes_refresh_time; ?>;
                var snowZIndex = <?php echo $wps_flakes_z_index; ?>;
                var snowStyles = "<?php echo $wps_flakes_styles; ?>";

				jQuery(document).trigger( 'loadWPSnow', [ snowMax, snowColor, snowType, snowEntity, snowSpeed, snowMaxSize, snowMinSize, snowRefresh, snowZIndex, snowStyles ] );

			})( jQuery );
			<?php
			$js = ob_get_clean();

			return apply_filters( 'wp_snow_filter_snow_settings_js', $js );
		}

	} // End class

endif;