<?php
/**
 * Created by PhpStorm.
 * User: Michael
 * Date: 18/06/2015
 * Time: 11:15
 */
namespace My_Bootstrap_Menu_Plugin_Namespace {

    abstract class My_Plugin_Icons
    {

        public static function get_dashicon_list()
        {
            //https://developer.wordpress.org/resource/dashicons/#lock

            $html = "<div id='my_plugin_dashicon-select_overlay'>

                    <div id='my_plugin_dashicon-select_list'>
                        <span class='my_plugin_close_me dashicons dashicons-no-alt'></span>
                        <h4>Admin Menu</h4>

                        <!-- admin menu -->
                        <div alt='f333' class='dashicons dashicons-menu'>menu</div>
                        <div alt='f319' class='dashicons dashicons-admin-site'>site</div>
                        <div alt='f226' class='dashicons dashicons-dashboard'>dashboard</div>
                        <div alt='f109' class='dashicons dashicons-admin-post'>post</div>
                        <div alt='f104' class='dashicons dashicons-admin-media'>media</div>
                        <div alt='f103' class='dashicons dashicons-admin-links'>links</div>
                        <div alt='f105' class='dashicons dashicons-admin-page'>page</div>
                        <div alt='f101' class='dashicons dashicons-admin-comments'>comments</div>
                        <div alt='f100' class='dashicons dashicons-admin-appearance'>appearance</div>
                        <div alt='f106' class='dashicons dashicons-admin-plugins'>plugins</div>
                        <div alt='f110' class='dashicons dashicons-admin-users'>users</div>
                        <div alt='f107' class='dashicons dashicons-admin-tools'>tools</div>
                        <div alt='f108' class='dashicons dashicons-admin-settings'>settings</div>
                        <div alt='f112' class='dashicons dashicons-admin-network'>network</div>
                        <div alt='f102' class='dashicons dashicons-admin-home'>home</div>
                        <div alt='f111' class='dashicons dashicons-admin-generic'>generic</div>
                        <div alt='f148' class='dashicons dashicons-admin-collapse'>collapse</div>

                        <h4>Welcome Screen</h4>

                        <!-- welcome screen -->
                        <div alt='f119' class='dashicons dashicons-welcome-write-blog'>write blog</div>
                        <!--<div alt='f119' class='dashicons dashicons-welcome-edit-page'></div> Duplicate -->
                        <div alt='f133' class='dashicons dashicons-welcome-add-page'>add page</div>
                        <div alt='f115' class='dashicons dashicons-welcome-view-site'>view site</div>
                        <div alt='f116' class='dashicons dashicons-welcome-widgets-menus'>widgets and menus</div>
                        <div alt='f117' class='dashicons dashicons-welcome-comments'>comments</div>
                        <div alt='f118' class='dashicons dashicons-welcome-learn-more'>learn more</div>

                        <h4>Post Formats</h4>

                        <!-- post formats -->
                        <!--<div alt='f109' class='dashicons dashicons-format-standard'></div> Duplicate -->
                        <div alt='f123' class='dashicons dashicons-format-aside'>aside</div>
                        <div alt='f128' class='dashicons dashicons-format-image'>image</div>
                        <div alt='f161' class='dashicons dashicons-format-gallery'>gallery</div>
                        <div alt='f126' class='dashicons dashicons-format-video'>video</div>
                        <div alt='f130' class='dashicons dashicons-format-status'>status</div>
                        <div alt='f122' class='dashicons dashicons-format-quote'>quote</div>
                        <!--<div alt='f103' class='dashicons dashicons-format-links'>links</div> Duplicate -->
                        <div alt='f125' class='dashicons dashicons-format-chat'>chat</div>
                        <div alt='f127' class='dashicons dashicons-format-audio'>audio</div>
                        <div alt='f306' class='dashicons dashicons-camera'>camera</div>
                        <div alt='f232' class='dashicons dashicons-images-alt'>images (alt)</div>
                        <div alt='f233' class='dashicons dashicons-images-alt2'>images (alt 2)</div>
                        <div alt='f234' class='dashicons dashicons-video-alt'>video (alt)</div>
                        <div alt='f235' class='dashicons dashicons-video-alt2'>video (alt 2)</div>
                        <div alt='f236' class='dashicons dashicons-video-alt3'>video (alt 3)</div>

                        <h4>Media</h4>

                        <!-- media -->
                        <div alt='f501' class='dashicons dashicons-media-archive'>archive</div>
                        <div alt='f500' class='dashicons dashicons-media-audio'>audio</div>
                        <div alt='f499' class='dashicons dashicons-media-code'>code</div>
                        <div alt='f498' class='dashicons dashicons-media-default'>default</div>
                        <div alt='f497' class='dashicons dashicons-media-document'>document</div>
                        <div alt='f496' class='dashicons dashicons-media-interactive'>interactive</div>
                        <div alt='f495' class='dashicons dashicons-media-spreadsheet'>spreadsheet</div>
                        <div alt='f491' class='dashicons dashicons-media-text'>text</div>
                        <div alt='f490' class='dashicons dashicons-media-video'>video</div>
                        <div alt='f492' class='dashicons dashicons-playlist-audio'>audio playlist</div>
                        <div alt='f493' class='dashicons dashicons-playlist-video'>video playlist</div>
                        <div alt='f522' class='dashicons dashicons-controls-play'>play player</div>
                        <div alt='f523' class='dashicons dashicons-controls-pause'>player pause</div>
                        <div alt='f519' class='dashicons dashicons-controls-forward'>player forward</div>
                        <div alt='f517' class='dashicons dashicons-controls-skipforward'>player skip forward</div>
                        <div alt='f518' class='dashicons dashicons-controls-back'>player back</div>
                        <div alt='f516' class='dashicons dashicons-controls-skipback'>player skip back</div>
                        <div alt='f515' class='dashicons dashicons-controls-repeat'>player repeat</div>
                        <div alt='f521' class='dashicons dashicons-controls-volumeon'>player volume on</div>
                        <div alt='f520' class='dashicons dashicons-controls-volumeoff'>player volume off</div>

                        <h4>Image Editing</h4>

                        <!-- image editing -->
                        <div alt='f165' class='dashicons dashicons-image-crop'>crop</div>
                        <div alt='f166' class='dashicons dashicons-image-rotate-left'>rotate left</div>
                        <div alt='f167' class='dashicons dashicons-image-rotate-right'>rotate right</div>
                        <div alt='f168' class='dashicons dashicons-image-flip-vertical'>flip vertical</div>
                        <div alt='f169' class='dashicons dashicons-image-flip-horizontal'>flip horizontal</div>
                        <div alt='f171' class='dashicons dashicons-undo'>undo</div>
                        <div alt='f172' class='dashicons dashicons-redo'>redo</div>

                        <h4>TinyMCE</h4>

                        <!-- tinymce -->
                        <div alt='f200' class='dashicons dashicons-editor-bold'>bold</div>
                        <div alt='f201' class='dashicons dashicons-editor-italic'>italic</div>
                        <div alt='f203' class='dashicons dashicons-editor-ul'>ul</div>
                        <div alt='f204' class='dashicons dashicons-editor-ol'>ol</div>
                        <div alt='f205' class='dashicons dashicons-editor-quote'>quote</div>
                        <div alt='f206' class='dashicons dashicons-editor-alignleft'>alignleft</div>
                        <div alt='f207' class='dashicons dashicons-editor-aligncenter'>aligncenter</div>
                        <div alt='f208' class='dashicons dashicons-editor-alignright'>alignright</div>
                        <div alt='f209' class='dashicons dashicons-editor-insertmore'>insertmore</div>
                        <div alt='f210' class='dashicons dashicons-editor-spellcheck'>spellcheck</div>
                        <!-- <div alt='f211' class='dashicons dashicons-editor-distractionfree'></div> Duplicate -->
                        <div alt='f211' class='dashicons dashicons-editor-expand'>expand</div>
                        <div alt='f506' class='dashicons dashicons-editor-contract'>contract</div>
                        <div alt='f212' class='dashicons dashicons-editor-kitchensink'>kitchen sink</div>
                        <div alt='f213' class='dashicons dashicons-editor-underline'>underline</div>
                        <div alt='f214' class='dashicons dashicons-editor-justify'>justify</div>
                        <div alt='f215' class='dashicons dashicons-editor-textcolor'>textcolor</div>
                        <div alt='f216' class='dashicons dashicons-editor-paste-word'>paste</div>
                        <div alt='f217' class='dashicons dashicons-editor-paste-text'>paste</div>
                        <div alt='f218' class='dashicons dashicons-editor-removeformatting'>remove formatting</div>
                        <div alt='f219' class='dashicons dashicons-editor-video'>video</div>
                        <div alt='f220' class='dashicons dashicons-editor-customchar'>custom chararcter</div>
                        <div alt='f221' class='dashicons dashicons-editor-outdent'>outdent</div>
                        <div alt='f222' class='dashicons dashicons-editor-indent'>indent</div>
                        <div alt='f223' class='dashicons dashicons-editor-help'>help</div>
                        <div alt='f224' class='dashicons dashicons-editor-strikethrough'>strikethrough</div>
                        <div alt='f225' class='dashicons dashicons-editor-unlink'>unlink</div>
                        <div alt='f320' class='dashicons dashicons-editor-rtl'>rtl</div>
                        <div alt='f474' class='dashicons dashicons-editor-break'>break</div>
                        <div alt='f475' class='dashicons dashicons-editor-code'>code</div>
                        <div alt='f476' class='dashicons dashicons-editor-paragraph'>paragraph</div>

                        <h4>Posts Screen</h4>

                        <!-- posts -->
                        <div alt='f135' class='dashicons dashicons-align-left'>align left</div>
                        <div alt='f136' class='dashicons dashicons-align-right'>align right</div>
                        <div alt='f134' class='dashicons dashicons-align-center'>align center</div>
                        <div alt='f138' class='dashicons dashicons-align-none'>align none</div>
                        <div alt='f160' class='dashicons dashicons-lock'>lock</div>
                        <div alt='f145' class='dashicons dashicons-calendar'>calendar</div>
                        <div alt='f508' class='dashicons dashicons-calendar-alt'>calendar</div>
                        <div alt='f177' class='dashicons dashicons-visibility'>visibility</div>
                        <div alt='f173' class='dashicons dashicons-post-status'>post status</div>
                        <div alt='f464' class='dashicons dashicons-edit'>edit pencil</div>
                        <div alt='f182' class='dashicons dashicons-trash'>trash remove delete</div>

                        <h4>Sorting</h4>

                        <!-- sorting -->
                        <div alt='f504' class='dashicons dashicons-external'>external</div>
                        <div alt='f142' class='dashicons dashicons-arrow-up'>arrow-up</div>
                        <div alt='f140' class='dashicons dashicons-arrow-down'>arrow-down</div>
                        <div alt='f139' class='dashicons dashicons-arrow-right'>arrow-right</div>
                        <div alt='f141' class='dashicons dashicons-arrow-left'>arrow-left</div>
                        <div alt='f342' class='dashicons dashicons-arrow-up-alt'>arrow-up</div>
                        <div alt='f346' class='dashicons dashicons-arrow-down-alt'>arrow-down</div>
                        <div alt='f344' class='dashicons dashicons-arrow-right-alt'>arrow-right</div>
                        <div alt='f340' class='dashicons dashicons-arrow-left-alt'>arrow-left</div>
                        <div alt='f343' class='dashicons dashicons-arrow-up-alt2'>arrow-up</div>
                        <div alt='f347' class='dashicons dashicons-arrow-down-alt2'>arrow-down</div>
                        <div alt='f345' class='dashicons dashicons-arrow-right-alt2'>arrow-right</div>
                        <div alt='f341' class='dashicons dashicons-arrow-left-alt2'>arrow-left</div>
                        <div alt='f156' class='dashicons dashicons-sort'>sort</div>
                        <div alt='f229' class='dashicons dashicons-leftright'>left right</div>
                        <div alt='f503' class='dashicons dashicons-randomize'>randomize shuffle</div>
                        <div alt='f163' class='dashicons dashicons-list-view'>list view</div>
                        <div alt='f164' class='dashicons dashicons-exerpt-view'>exerpt view</div>
                        <div alt='f509' class='dashicons dashicons-grid-view'>grid view</div>

                        <h4>Social</h4>

                        <!-- social -->
                        <div alt='f237' class='dashicons dashicons-share'>share</div>
                        <div alt='f240' class='dashicons dashicons-share-alt'>share</div>
                        <div alt='f242' class='dashicons dashicons-share-alt2'>share</div>
                        <div alt='f301' class='dashicons dashicons-twitter'>twitter social</div>
                        <div alt='f303' class='dashicons dashicons-rss'>rss</div>
                        <div alt='f465' class='dashicons dashicons-email'>email</div>
                        <div alt='f466' class='dashicons dashicons-email-alt'>email</div>
                        <div alt='f304' class='dashicons dashicons-facebook'>facebook social</div>
                        <div alt='f305' class='dashicons dashicons-facebook-alt'>facebook social</div>
                        <div alt='f462' class='dashicons dashicons-googleplus'>googleplus social</div>
                        <div alt='f325' class='dashicons dashicons-networking'>networking social</div>

                        <h4>WordPress.org Specific: Jobs, Profiles, WordCamps</h4>

                        <!-- WPorg specific icons: Jobs, Profiles, WordCamps -->
                        <div alt='f308' class='dashicons dashicons-hammer'>hammer development</div>
                        <div alt='f309' class='dashicons dashicons-art'>art design</div>
                        <div alt='f310' class='dashicons dashicons-migrate'>migrate migration</div>
                        <div alt='f311' class='dashicons dashicons-performance'>performance</div>
                        <div alt='f483' class='dashicons dashicons-universal-access'>universal access accessibility</div>
                        <div alt='f507' class='dashicons dashicons-universal-access-alt'>universal access accessibility</div>
                        <div alt='f486' class='dashicons dashicons-tickets'>tickets</div>
                        <div alt='f484' class='dashicons dashicons-nametag'>nametag</div>
                        <div alt='f481' class='dashicons dashicons-clipboard'>clipboard</div>
                        <div alt='f487' class='dashicons dashicons-heart'>heart</div>
                        <div alt='f488' class='dashicons dashicons-megaphone'>megaphone</div>
                        <div alt='f489' class='dashicons dashicons-schedule'>schedule</div>

                        <h4>Products</h4>

                        <!-- internal/products -->
                        <div alt='f120' class='dashicons dashicons-wordpress'>wordpress</div>
                        <div alt='f324' class='dashicons dashicons-wordpress-alt'>wordpress</div>
                        <div alt='f157' class='dashicons dashicons-pressthis'>press this</div>
                        <div alt='f463' class='dashicons dashicons-update'>update</div>
                        <div alt='f180' class='dashicons dashicons-screenoptions'>screenoptions</div>
                        <div alt='f348' class='dashicons dashicons-info'>info</div>
                        <div alt='f174' class='dashicons dashicons-cart'>cart shopping</div>
                        <div alt='f175' class='dashicons dashicons-feedback'>feedback form</div>
                        <div alt='f176' class='dashicons dashicons-cloud'>cloud</div>
                        <div alt='f326' class='dashicons dashicons-translation'>translation language</div>

                        <h4>Taxonomies</h4>

                        <!-- taxonomies -->
                        <div alt='f323' class='dashicons dashicons-tag'>tag</div>
                        <div alt='f318' class='dashicons dashicons-category'>category</div>

                        <h4>Widgets</h4>

                        <!-- widgets -->
                        <div alt='f480' class='dashicons dashicons-archive'>archive</div>
                        <div alt='f479' class='dashicons dashicons-tagcloud'>tagcloud</div>
                        <div alt='f478' class='dashicons dashicons-text'>text</div>

                        <h4>Notifications</h4>

                        <!-- alerts/notifications/flags -->
                        <div alt='f147' class='dashicons dashicons-yes'>yes check checkmark</div>
                        <div alt='f158' class='dashicons dashicons-no'>no x</div>
                        <div alt='f335' class='dashicons dashicons-no-alt'>no x</div>
                        <div alt='f132' class='dashicons dashicons-plus'>plus add increase</div>
                        <div alt='f502' class='dashicons dashicons-plus-alt'>plus add increase</div>
                        <div alt='f460' class='dashicons dashicons-minus'>minus decrease</div>
                        <div alt='f153' class='dashicons dashicons-dismiss'>dismiss</div>
                        <div alt='f159' class='dashicons dashicons-marker'>marker</div>
                        <div alt='f155' class='dashicons dashicons-star-filled'>filled star</div>
                        <div alt='f459' class='dashicons dashicons-star-half'>half star</div>
                        <div alt='f154' class='dashicons dashicons-star-empty'>empty star</div>
                        <div alt='f227' class='dashicons dashicons-flag'>flag</div>

                        <h4>Misc</h4>

                        <!-- misc/cpt -->
                        <div alt='f230' class='dashicons dashicons-location'>location pin</div>
                        <div alt='f231' class='dashicons dashicons-location-alt'>location</div>
                        <div alt='f178' class='dashicons dashicons-vault'>vault safe</div>
                        <div alt='f332' class='dashicons dashicons-shield'>shield</div>
                        <div alt='f334' class='dashicons dashicons-shield-alt'>shield</div>
                        <div alt='f468' class='dashicons dashicons-sos'>sos help</div>
                        <div alt='f179' class='dashicons dashicons-search'>search</div>
                        <div alt='f181' class='dashicons dashicons-slides'>slides</div>
                        <div alt='f183' class='dashicons dashicons-analytics'>analytics</div>
                        <div alt='f184' class='dashicons dashicons-chart-pie'>pie chart</div>
                        <div alt='f185' class='dashicons dashicons-chart-bar'>bar chart</div>
                        <div alt='f238' class='dashicons dashicons-chart-line'>line chart</div>
                        <div alt='f239' class='dashicons dashicons-chart-area'>area chart</div>
                        <div alt='f307' class='dashicons dashicons-groups'>groups</div>
                        <div alt='f338' class='dashicons dashicons-businessman'>businessman</div>
                        <div alt='f336' class='dashicons dashicons-id'>id</div>
                        <div alt='f337' class='dashicons dashicons-id-alt'>id</div>
                        <div alt='f312' class='dashicons dashicons-products'>products</div>
                        <div alt='f313' class='dashicons dashicons-awards'>awards</div>
                        <div alt='f314' class='dashicons dashicons-forms'>forms</div>
                        <div alt='f473' class='dashicons dashicons-testimonial'>testimonial</div>
                        <div alt='f322' class='dashicons dashicons-portfolio'>portfolio</div>
                        <div alt='f330' class='dashicons dashicons-book'>book</div>
                        <div alt='f331' class='dashicons dashicons-book-alt'>book</div>
                        <div alt='f316' class='dashicons dashicons-download'>download</div>
                        <div alt='f317' class='dashicons dashicons-upload'>upload</div>
                        <div alt='f321' class='dashicons dashicons-backup'>backup</div>
                        <div alt='f469' class='dashicons dashicons-clock'>clock</div>
                        <div alt='f339' class='dashicons dashicons-lightbulb'>lightbulb</div>
                        <div alt='f482' class='dashicons dashicons-microphone'>microphone mic</div>
                        <div alt='f472' class='dashicons dashicons-desktop'>desktop monitor</div>
                        <div alt='f471' class='dashicons dashicons-tablet'>tablet ipad</div>
                        <div alt='f470' class='dashicons dashicons-smartphone'>smartphone iphone</div>
                        <div alt='f525' class='dashicons dashicons-phone'>phone</div>
                        <div alt='f510' class='dashicons dashicons-index-card'>index card</div>
                        <div alt='f511' class='dashicons dashicons-carrot'>carrot food vendor</div>
                        <div alt='f512' class='dashicons dashicons-building'>building</div>
                        <div alt='f513' class='dashicons dashicons-store'>store</div>
                        <div alt='f514' class='dashicons dashicons-album'>album</div>
                        <div alt='f527' class='dashicons dashicons-palmtree'>palm tree</div>
                        <div alt='f524' class='dashicons dashicons-tickets-alt'>tickets (alt)</div>
                        <div alt='f526' class='dashicons dashicons-money'>money</div>
                        <div alt='f328' class='dashicons dashicons-smiley'>smiley smile</div>
                    </div>

                </div>";

            return $html;
        }

        public static function get_glyphicons_list()
        {
            $html = "<div id='my_plugin_glyphicon-select_overlay'>

                    <div id='my_plugin_glyphicon-select_list'>

                        <span class='my_plugin_close_me glyphicons glyphicons-no-alt'></span>

                        <h4>Select Bootstrap Glyphicon</h4>

                        <div alt='asterisk' class='glyphicon glyphicon-asterisk'>glyphicon-asterisk</div>
                        <div alt='plus' class='glyphicon glyphicon-plus'>glyphicon-plus</div>
                        <div alt='euro' class='glyphicon glyphicon-euro'>glyphicon-euro</div>
                        <div alt='eur' class='glyphicon glyphicon-eur'>glyphicon-eur</div>
                        <div alt='minus' class='glyphicon glyphicon-minus'>glyphicon-minus</div>
                        <div alt='cloud' class='glyphicon glyphicon-cloud'>glyphicon-cloud</div>
                        <div alt='envelope' class='glyphicon glyphicon-envelope'>glyphicon-envelope</div>
                        <div alt='pencil' class='glyphicon glyphicon-pencil'>glyphicon-pencil</div>
                        <div alt='glass' class='glyphicon glyphicon-glass'>glyphicon-glass</div>
                        <div alt='music' class='glyphicon glyphicon-music'>glyphicon-music</div>
                        <div alt='search' class='glyphicon glyphicon-search'>glyphicon-search</div>
                        <div alt='heart' class='glyphicon glyphicon-heart'>glyphicon-heart</div>
                        <div alt='star' class='glyphicon glyphicon-star'>glyphicon-star</div>
                        <div alt='star-empty' class='glyphicon glyphicon-star-empty'>glyphicon-star-empty</div>
                        <div alt='user' class='glyphicon glyphicon-user'>glyphicon-user</div>
                        <div alt='film' class='glyphicon glyphicon-film'>glyphicon-film</div>
                        <div alt='th-large' class='glyphicon glyphicon-th-large'>glyphicon-th-large</div>
                        <div alt='th' class='glyphicon glyphicon-th'>glyphicon-th</div>
                        <div alt='th-list' class='glyphicon glyphicon-th-list'>glyphicon-th-list</div>
                        <div alt='ok' class='glyphicon glyphicon-ok'>glyphicon-ok</div>
                        <div alt='remove' class='glyphicon glyphicon-remove'>glyphicon-remove</div>
                        <div alt='zoom-in' class='glyphicon glyphicon-zoom-in'>glyphicon-zoom-in</div>
                        <div alt='zoom-out' class='glyphicon glyphicon-zoom-out'>glyphicon-zoom-out</div>
                        <div alt='off' class='glyphicon glyphicon-off'>glyphicon-off</div>
                        <div alt='signal' class='glyphicon glyphicon-signal'>glyphicon-signal</div>
                        <div alt='cog' class='glyphicon glyphicon-cog'>glyphicon-cog</div>
                        <div alt='trash' class='glyphicon glyphicon-trash'>glyphicon-trash</div>
                        <div alt='home' class='glyphicon glyphicon-home'>glyphicon-home</div>
                        <div alt='file' class='glyphicon glyphicon-file'>glyphicon-file</div>
                        <div alt='time' class='glyphicon glyphicon-time'>glyphicon-time</div>
                        <div alt='road' class='glyphicon glyphicon-road'>glyphicon-road</div>
                        <div alt='download-alt' class='glyphicon glyphicon-download-alt'>glyphicon-download-alt</div>
                        <div alt='download' class='glyphicon glyphicon-download'>glyphicon-download</div>
                        <div alt='upload' class='glyphicon glyphicon-upload'>glyphicon-upload</div>
                        <div alt='inbox' class='glyphicon glyphicon-inbox'>glyphicon-inbox</div>
                        <div alt='play-circle' class='glyphicon glyphicon-play-circle'>glyphicon-play-circle</div>
                        <div alt='repeat' class='glyphicon glyphicon-repeat'>glyphicon-repeat</div>
                        <div alt='refresh' class='glyphicon glyphicon-refresh'>glyphicon-refresh</div>
                        <div alt='list-alt' class='glyphicon glyphicon-list-alt'>glyphicon-list-alt</div>
                        <div alt='lock' class='glyphicon glyphicon-lock'>glyphicon-lock</div>
                        <div alt='flag' class='glyphicon glyphicon-flag'>glyphicon-flag</div>
                        <div alt='headphones' class='glyphicon glyphicon-headphones'>glyphicon-headphones</div>
                        <div alt='volume-off' class='glyphicon glyphicon-volume-off'>glyphicon-volume-off</div>
                        <div alt='volume-down' class='glyphicon glyphicon-volume-down'>glyphicon-volume-down</div>
                        <div alt='volume-up' class='glyphicon glyphicon-volume-up'>glyphicon-volume-up</div>
                        <div alt='qrcode' class='glyphicon glyphicon-qrcode'>glyphicon-qrcode</div>
                        <div alt='barcode' class='glyphicon glyphicon-barcode'>glyphicon-barcode</div>
                        <div alt='tag' class='glyphicon glyphicon-tag'>glyphicon-tag</div>
                        <div alt='tags' class='glyphicon glyphicon-tags'>glyphicon-tags</div>
                        <div alt='book' class='glyphicon glyphicon-book'>glyphicon-book</div>
                        <div alt='bookmark' class='glyphicon glyphicon-bookmark'>glyphicon-bookmark</div>
                        <div alt='print' class='glyphicon glyphicon-print'>glyphicon-print</div>
                        <div alt='camera' class='glyphicon glyphicon-camera'>glyphicon-camera</div>
                        <div alt='font' class='glyphicon glyphicon-font'>glyphicon-font</div>
                        <div alt='bold' class='glyphicon glyphicon-bold'>glyphicon-bold</div>
                        <div alt='italic' class='glyphicon glyphicon-italic'>glyphicon-italic</div>
                        <div alt='text-height' class='glyphicon glyphicon-text-height'>glyphicon-text-height</div>
                        <div alt='text-width' class='glyphicon glyphicon-text-width'>glyphicon-text-width</div>
                        <div alt='align-left' class='glyphicon glyphicon-align-left'>glyphicon-align-left</div>
                        <div alt='align-center' class='glyphicon glyphicon-align-center'>glyphicon-align-center</div>
                        <div alt='align-right' class='glyphicon glyphicon-align-right'>glyphicon-align-right</div>
                        <div alt='align-justify' class='glyphicon glyphicon-align-justify'>glyphicon-align-justify</div>
                        <div alt='list' class='glyphicon glyphicon-list'>glyphicon-list</div>
                        <div alt='indent-left' class='glyphicon glyphicon-indent-left'>glyphicon-indent-left</div>
                        <div alt='indent-right' class='glyphicon glyphicon-indent-right'>glyphicon-indent-right</div>
                        <div alt='facetime-video' class='glyphicon glyphicon-facetime-video'>glyphicon-facetime-video</div>
                        <div alt='picture' class='glyphicon glyphicon-picture'>glyphicon-picture</div>
                        <div alt='map-marker' class='glyphicon glyphicon-map-marker'>glyphicon-map-marker</div>
                        <div alt='adjust' class='glyphicon glyphicon-adjust'>glyphicon-adjust</div>
                        <div alt='tint' class='glyphicon glyphicon-tint'>glyphicon-tint</div>
                        <div alt='edit' class='glyphicon glyphicon-edit'>glyphicon-edit</div>
                        <div alt='share' class='glyphicon glyphicon-share'>glyphicon-share</div>
                        <div alt='check' class='glyphicon glyphicon-check'>glyphicon-check</div>
                        <div alt='move' class='glyphicon glyphicon-move'>glyphicon-move</div>
                        <div alt='step-backward' class='glyphicon glyphicon-step-backward'>glyphicon-step-backward</div>
                        <div alt='fast-backward' class='glyphicon glyphicon-fast-backward'>glyphicon-fast-backward</div>
                        <div alt='backward' class='glyphicon glyphicon-backward'>glyphicon-backward</div>
                        <div alt='play' class='glyphicon glyphicon-play'>glyphicon-play</div>
                        <div alt='pause' class='glyphicon glyphicon-pause'>glyphicon-pause</div>
                        <div alt='stop' class='glyphicon glyphicon-stop'>glyphicon-stop</div>
                        <div alt='forward' class='glyphicon glyphicon-forward'>glyphicon-forward</div>
                        <div alt='fast-forward' class='glyphicon glyphicon-fast-forward'>glyphicon-fast-forward</div>
                        <div alt='step-forward' class='glyphicon glyphicon-step-forward'>glyphicon-step-forward</div>
                        <div alt='eject' class='glyphicon glyphicon-eject'>glyphicon-eject</div>
                        <div alt='chevron-left' class='glyphicon glyphicon-chevron-left'>glyphicon-chevron-left</div>
                        <div alt='chevron-right' class='glyphicon glyphicon-chevron-right'>glyphicon-chevron-right</div>
                        <div alt='plus-sign' class='glyphicon glyphicon-plus-sign'>glyphicon-plus-sign</div>
                        <div alt='minus-sign' class='glyphicon glyphicon-minus-sign'>glyphicon-minus-sign</div>
                        <div alt='remove-sign' class='glyphicon glyphicon-remove-sign'>glyphicon-remove-sign</div>
                        <div alt='ok-sign' class='glyphicon glyphicon-ok-sign'>glyphicon-ok-sign</div>
                        <div alt='question-sign' class='glyphicon glyphicon-question-sign'>glyphicon-question-sign</div>
                        <div alt='info-sign' class='glyphicon glyphicon-info-sign'>glyphicon-info-sign</div>
                        <div alt='screenshot' class='glyphicon glyphicon-screenshot'>glyphicon-screenshot</div>
                        <div alt='remove-circle' class='glyphicon glyphicon-remove-circle'>glyphicon-remove-circle</div>
                        <div alt='ok-circle' class='glyphicon glyphicon-ok-circle'>glyphicon-ok-circle</div>
                        <div alt='ban-circle' class='glyphicon glyphicon-ban-circle'>glyphicon-ban-circle</div>
                        <div alt='arrow-left' class='glyphicon glyphicon-arrow-left'>glyphicon-arrow-left</div>
                        <div alt='arrow-right' class='glyphicon glyphicon-arrow-right'>glyphicon-arrow-right</div>
                        <div alt='arrow-up' class='glyphicon glyphicon-arrow-up'>glyphicon-arrow-up</div>
                        <div alt='arrow-down' class='glyphicon glyphicon-arrow-down'>glyphicon-arrow-down</div>
                        <div alt='share-alt' class='glyphicon glyphicon-share-alt'>glyphicon-share-alt</div>
                        <div alt='resize-full' class='glyphicon glyphicon-resize-full'>glyphicon-resize-full</div>
                        <div alt='resize-small' class='glyphicon glyphicon-resize-small'>glyphicon-resize-small</div>
                        <div alt='exclamation-sign' class='glyphicon glyphicon-exclamation-sign'>glyphicon-exclamation-sign</div>
                        <div alt='gift' class='glyphicon glyphicon-gift'>glyphicon-gift</div>
                        <div alt='leaf' class='glyphicon glyphicon-leaf'>glyphicon-leaf</div>
                        <div alt='fire' class='glyphicon glyphicon-fire'>glyphicon-fire</div>
                        <div alt='eye-open' class='glyphicon glyphicon-eye-open'>glyphicon-eye-open</div>
                        <div alt='eye-close' class='glyphicon glyphicon-eye-close'>glyphicon-eye-close</div>
                        <div alt='warning-sign' class='glyphicon glyphicon-warning-sign'>glyphicon-warning-sign</div>
                        <div alt='plane' class='glyphicon glyphicon-plane'>glyphicon-plane</div>
                        <div alt='calendar' class='glyphicon glyphicon-calendar'>glyphicon-calendar</div>
                        <div alt='random' class='glyphicon glyphicon-random'>glyphicon-random</div>
                        <div alt='comment' class='glyphicon glyphicon-comment'>glyphicon-comment</div>
                        <div alt='magnet' class='glyphicon glyphicon-magnet'>glyphicon-magnet</div>
                        <div alt='chevron-up' class='glyphicon glyphicon-chevron-up'>glyphicon-chevron-up</div>
                        <div alt='chevron-down' class='glyphicon glyphicon-chevron-down'>glyphicon-chevron-down</div>
                        <div alt='retweet' class='glyphicon glyphicon-retweet'>glyphicon-retweet</div>
                        <div alt='shopping-cart' class='glyphicon glyphicon-shopping-cart'>glyphicon-shopping-cart</div>
                        <div alt='folder-close' class='glyphicon glyphicon-folder-close'>glyphicon-folder-close</div>
                        <div alt='folder-open' class='glyphicon glyphicon-folder-open'>glyphicon-folder-open</div>
                        <div alt='resize-vertical' class='glyphicon glyphicon-resize-vertical'>glyphicon-resize-vertical</div>
                        <div alt='resize-horizontal' class='glyphicon glyphicon-resize-horizontal'>glyphicon-resize-horizontal</div>
                        <div alt='hdd' class='glyphicon glyphicon-hdd'>glyphicon-hdd</div>
                        <div alt='bullhorn' class='glyphicon glyphicon-bullhorn'>glyphicon-bullhorn</div>
                        <div alt='bell' class='glyphicon glyphicon-bell'>glyphicon-bell</div>
                        <div alt='certificate' class='glyphicon glyphicon-certificate'>glyphicon-certificate</div>
                        <div alt='thumbs-up' class='glyphicon glyphicon-thumbs-up'>glyphicon-thumbs-up</div>
                        <div alt='thumbs-down' class='glyphicon glyphicon-thumbs-down'>glyphicon-thumbs-down</div>
                        <div alt='hand-right' class='glyphicon glyphicon-hand-right'>glyphicon-hand-right</div>
                        <div alt='hand-left' class='glyphicon glyphicon-hand-left'>glyphicon-hand-left</div>
                        <div alt='hand-up' class='glyphicon glyphicon-hand-up'>glyphicon-hand-up</div>
                        <div alt='hand-down' class='glyphicon glyphicon-hand-down'>glyphicon-hand-down</div>
                        <div alt='circle-arrow-right' class='glyphicon glyphicon-circle-arrow-right'>glyphicon-circle-arrow-right</div>
                        <div alt='circle-arrow-left' class='glyphicon glyphicon-circle-arrow-left'>glyphicon-circle-arrow-left</div>
                        <div alt='circle-arrow-up' class='glyphicon glyphicon-circle-arrow-up'>glyphicon-circle-arrow-up</div>
                        <div alt='circle-arrow-down' class='glyphicon glyphicon-circle-arrow-down'>glyphicon-circle-arrow-down</div>
                        <div alt='globe' class='glyphicon glyphicon-globe'>glyphicon-globe</div>
                        <div alt='wrench' class='glyphicon glyphicon-wrench'>glyphicon-wrench</div>
                        <div alt='tasks' class='glyphicon glyphicon-tasks'>glyphicon-tasks</div>
                        <div alt='filter' class='glyphicon glyphicon-filter'>glyphicon-filter</div>
                        <div alt='briefcase' class='glyphicon glyphicon-briefcase'>glyphicon-briefcase</div>
                        <div alt='fullscreen' class='glyphicon glyphicon-fullscreen'>glyphicon-fullscreen</div>
                        <div alt='dashboard' class='glyphicon glyphicon-dashboard'>glyphicon-dashboard</div>
                        <div alt='paperclip' class='glyphicon glyphicon-paperclip'>glyphicon-paperclip</div>
                        <div alt='heart-empty' class='glyphicon glyphicon-heart-empty'>glyphicon-heart-empty</div>
                        <div alt='link' class='glyphicon glyphicon-link'>glyphicon-link</div>
                        <div alt='phone' class='glyphicon glyphicon-phone'>glyphicon-phone</div>
                        <div alt='pushpin' class='glyphicon glyphicon-pushpin'>glyphicon-pushpin</div>
                        <div alt='usd' class='glyphicon glyphicon-usd'>glyphicon-usd</div>
                        <div alt='gbp' class='glyphicon glyphicon-gbp'>glyphicon-gbp</div>
                        <div alt='sort' class='glyphicon glyphicon-sort'>glyphicon-sort</div>
                        <div alt='sort-by-alphabet' class='glyphicon glyphicon-sort-by-alphabet'>glyphicon-sort-by-alphabet</div>
                        <div alt='sort-by-alphabet-alt' class='glyphicon glyphicon-sort-by-alphabet-alt'>glyphicon-sort-by-alphabet-alt</div>
                        <div alt='sort-by-order' class='glyphicon glyphicon-sort-by-order'>glyphicon-sort-by-order</div>
                        <div alt='sort-by-order-alt' class='glyphicon glyphicon-sort-by-order-alt'>glyphicon-sort-by-order-alt</div>
                        <div alt='sort-by-attributes' class='glyphicon glyphicon-sort-by-attributes'>glyphicon-sort-by-attributes</div>
                        <div alt='sort-by-attributes-alt' class='glyphicon glyphicon-sort-by-attributes-alt'>glyphicon-sort-by-attributes-alt</div>
                        <div alt='unchecked' class='glyphicon glyphicon-unchecked'>glyphicon-unchecked</div>
                        <div alt='expand' class='glyphicon glyphicon-expand'>glyphicon-expand</div>
                        <div alt='collapse-down' class='glyphicon glyphicon-collapse-down'>glyphicon-collapse-down</div>
                        <div alt='collapse-up' class='glyphicon glyphicon-collapse-up'>glyphicon-collapse-up</div>
                        <div alt='log-in' class='glyphicon glyphicon-log-in'>glyphicon-log-in</div>
                        <div alt='flash' class='glyphicon glyphicon-flash'>glyphicon-flash</div>
                        <div alt='log-out' class='glyphicon glyphicon-log-out'>glyphicon-log-out</div>
                        <div alt='new-window' class='glyphicon glyphicon-new-window'>glyphicon-new-window</div>
                        <div alt='record' class='glyphicon glyphicon-record'>glyphicon-record</div>
                        <div alt='save' class='glyphicon glyphicon-save'>glyphicon-save</div>
                        <div alt='open' class='glyphicon glyphicon-open'>glyphicon-open</div>
                        <div alt='saved' class='glyphicon glyphicon-saved'>glyphicon-saved</div>
                        <div alt='import' class='glyphicon glyphicon-import'>glyphicon-import</div>
                        <div alt='export' class='glyphicon glyphicon-export'>glyphicon-export</div>
                        <div alt='send' class='glyphicon glyphicon-send'>glyphicon-send</div>
                        <div alt='floppy-disk' class='glyphicon glyphicon-floppy-disk'>glyphicon-floppy-disk</div>
                        <div alt='floppy-saved' class='glyphicon glyphicon-floppy-saved'>glyphicon-floppy-saved</div>
                        <div alt='floppy-remove' class='glyphicon glyphicon-floppy-remove'>glyphicon-floppy-remove</div>
                        <div alt='floppy-save' class='glyphicon glyphicon-floppy-save'>glyphicon-floppy-save</div>
                        <div alt='floppy-open' class='glyphicon glyphicon-floppy-open'>glyphicon-floppy-open</div>
                        <div alt='credit-card' class='glyphicon glyphicon-credit-card'>glyphicon-credit-card</div>
                        <div alt='transfer' class='glyphicon glyphicon-transfer'>glyphicon-transfer</div>
                        <div alt='cutlery' class='glyphicon glyphicon-cutlery'>glyphicon-cutlery</div>
                        <div alt='header' class='glyphicon glyphicon-header'>glyphicon-header</div>
                        <div alt='compressed' class='glyphicon glyphicon-compressed'>glyphicon-compressed</div>
                        <div alt='earphone' class='glyphicon glyphicon-earphone'>glyphicon-earphone</div>
                        <div alt='phone-alt' class='glyphicon glyphicon-phone-alt'>glyphicon-phone-alt</div>
                        <div alt='tower' class='glyphicon glyphicon-tower'>glyphicon-tower</div>
                        <div alt='stats' class='glyphicon glyphicon-stats'>glyphicon-stats</div>
                        <div alt='sd-video' class='glyphicon glyphicon-sd-video'>glyphicon-sd-video</div>
                        <div alt='hd-video' class='glyphicon glyphicon-hd-video'>glyphicon-hd-video</div>
                        <div alt='subtitles' class='glyphicon glyphicon-subtitles'>glyphicon-subtitles</div>
                        <div alt='sound-stereo' class='glyphicon glyphicon-sound-stereo'>glyphicon-sound-stereo</div>
                        <div alt='sound-dolby' class='glyphicon glyphicon-sound-dolby'>glyphicon-sound-dolby</div>
                        <div alt='sound-5-1' class='glyphicon glyphicon-sound-5-1'>glyphicon-sound-5-1</div>
                        <div alt='sound-6-1' class='glyphicon glyphicon-sound-6-1'>glyphicon-sound-6-1</div>
                        <div alt='sound-7-1' class='glyphicon glyphicon-sound-7-1'>glyphicon-sound-7-1</div>
                        <div alt='copyright-mark' class='glyphicon glyphicon-copyright-mark'>glyphicon-copyright-mark</div>
                        <div alt='registration-mark' class='glyphicon glyphicon-registration-mark'>glyphicon-registration-mark</div>
                        <div alt='cloud-download' class='glyphicon glyphicon-cloud-download'>glyphicon-cloud-download</div>
                        <div alt='cloud-upload' class='glyphicon glyphicon-cloud-upload'>glyphicon-cloud-upload</div>
                        <div alt='tree-conifer' class='glyphicon glyphicon-tree-conifer'>glyphicon-tree-conifer</div>
                        <div alt='tree-deciduous' class='glyphicon glyphicon-tree-deciduous'>glyphicon-tree-deciduous</div>
                        <div alt='cd' class='glyphicon glyphicon-cd'>glyphicon-cd</div>
                        <div alt='save-file' class='glyphicon glyphicon-save-file'>glyphicon-save-file</div>
                        <div alt='open-file' class='glyphicon glyphicon-open-file'>glyphicon-open-file</div>
                        <div alt='level-up' class='glyphicon glyphicon-level-up'>glyphicon-level-up</div>
                        <div alt='copy' class='glyphicon glyphicon-copy'>glyphicon-copy</div>
                        <div alt='paste' class='glyphicon glyphicon-paste'>glyphicon-paste</div>
                        <div alt='alert' class='glyphicon glyphicon-alert'>glyphicon-alert</div>
                        <div alt='equalizer' class='glyphicon glyphicon-equalizer'>glyphicon-equalizer</div>
                        <div alt='king' class='glyphicon glyphicon-king'>glyphicon-king</div>
                        <div alt='queen' class='glyphicon glyphicon-queen'>glyphicon-queen</div>
                        <div alt='pawn' class='glyphicon glyphicon-pawn'>glyphicon-pawn</div>
                        <div alt='bishop' class='glyphicon glyphicon-bishop'>glyphicon-bishop</div>
                        <div alt='knight' class='glyphicon glyphicon-knight'>glyphicon-knight</div>
                        <div alt='baby-formula' class='glyphicon glyphicon-baby-formula'>glyphicon-baby-formula</div>
                        <div alt='tent' class='glyphicon glyphicon-tent'>glyphicon-tent</div>
                        <div alt='blackboard' class='glyphicon glyphicon-blackboard'>glyphicon-blackboard</div>
                        <div alt='bed' class='glyphicon glyphicon-bed'>glyphicon-bed</div>
                        <div alt='apple' class='glyphicon glyphicon-apple'>glyphicon-apple</div>
                        <div alt='erase' class='glyphicon glyphicon-erase'>glyphicon-erase</div>
                        <div alt='hourglass' class='glyphicon glyphicon-hourglass'>glyphicon-hourglass</div>
                        <div alt='lamp' class='glyphicon glyphicon-lamp'>glyphicon-lamp</div>
                        <div alt='duplicate' class='glyphicon glyphicon-duplicate'>glyphicon-duplicate</div>
                        <div alt='piggy-bank' class='glyphicon glyphicon-piggy-bank'>glyphicon-piggy-bank</div>
                        <div alt='scissors' class='glyphicon glyphicon-scissors'>glyphicon-scissors</div>
                        <div alt='bitcoin' class='glyphicon glyphicon-bitcoin'>glyphicon-bitcoin</div>
                        <div alt='btc' class='glyphicon glyphicon-btc'>glyphicon-btc</div>
                        <div alt='xbt' class='glyphicon glyphicon-xbt'>glyphicon-xbt</div>
                        <div alt='yen' class='glyphicon glyphicon-yen'>glyphicon-yen</div>
                        <div alt='jpy' class='glyphicon glyphicon-jpy'>glyphicon-jpy</div>
                        <div alt='ruble' class='glyphicon glyphicon-ruble'>glyphicon-ruble</div>
                        <div alt='rub' class='glyphicon glyphicon-rub'>glyphicon-rub</div>
                        <div alt='scale' class='glyphicon glyphicon-scale'>glyphicon-scale</div>
                        <div alt='ice-lolly' class='glyphicon glyphicon-ice-lolly'>glyphicon-ice-lolly</div>
                        <div alt='ice-lolly-tasted' class='glyphicon glyphicon-ice-lolly-tasted'>glyphicon-ice-lolly-tasted</div>
                        <div alt='education' class='glyphicon glyphicon-education'>glyphicon-education</div>
                        <div alt='option-horizontal' class='glyphicon glyphicon-option-horizontal'>glyphicon-option-horizontal</div>
                        <div alt='option-vertical' class='glyphicon glyphicon-option-vertical'>glyphicon-option-vertical</div>
                        <div alt='menu-hamburger' class='glyphicon glyphicon-menu-hamburger'>glyphicon-menu-hamburger</div>
                        <div alt='modal-window' class='glyphicon glyphicon-modal-window'>glyphicon-modal-window</div>
                        <div alt='oil' class='glyphicon glyphicon-oil'>glyphicon-oil</div>
                        <div alt='grain' class='glyphicon glyphicon-grain'>glyphicon-grain</div>
                        <div alt='sunglasses' class='glyphicon glyphicon-sunglasses'>glyphicon-sunglasses</div>
                        <div alt='text-size' class='glyphicon glyphicon-text-size'>glyphicon-text-size</div>
                        <div alt='text-color' class='glyphicon glyphicon-text-color'>glyphicon-text-color</div>
                        <div alt='text-background' class='glyphicon glyphicon-text-background'>glyphicon-text-background</div>
                        <div alt='object-align-top' class='glyphicon glyphicon-object-align-top'>glyphicon-object-align-top</div>
                        <div alt='object-align-bottom' class='glyphicon glyphicon-object-align-bottom'>glyphicon-object-align-bottom</div>
                        <div alt='object-align-horizontal' class='glyphicon glyphicon-object-align-horizontal'>glyphicon-object-align-horizontal</div>
                        <div alt='object-align-left' class='glyphicon glyphicon-object-align-left'>glyphicon-object-align-left</div>
                        <div alt='object-align-vertical' class='glyphicon glyphicon-object-align-vertical'>glyphicon-object-align-vertical</div>
                        <div alt='object-align-right' class='glyphicon glyphicon-object-align-right'>glyphicon-object-align-right</div>
                        <div alt='triangle-right' class='glyphicon glyphicon-triangle-right'>glyphicon-triangle-right</div>
                        <div alt='triangle-left' class='glyphicon glyphicon-triangle-left'>glyphicon-triangle-left</div>
                        <div alt='triangle-bottom' class='glyphicon glyphicon-triangle-bottom'>glyphicon-triangle-bottom</div>
                        <div alt='triangle-top' class='glyphicon glyphicon-triangle-top'>glyphicon-triangle-top</div>
                        <div alt='console' class='glyphicon glyphicon-console'>glyphicon-console</div>
                        <div alt='superscript' class='glyphicon glyphicon-superscript'>glyphicon-superscript</div>
                        <div alt='subscript' class='glyphicon glyphicon-subscript'>glyphicon-subscript</div>
                        <div alt='menu-left' class='glyphicon glyphicon-menu-left'>glyphicon-menu-left</div>
                        <div alt='menu-right' class='glyphicon glyphicon-menu-right'>glyphicon-menu-right</div>
                        <div alt='menu-down' class='glyphicon glyphicon-menu-down'>glyphicon-menu-down</div>
                        <div alt='menu-up' class='glyphicon glyphicon-menu-up'>glyphicon-menu-up</div>

                    </div>
              </div>";
            return $html;
        }
    }
}