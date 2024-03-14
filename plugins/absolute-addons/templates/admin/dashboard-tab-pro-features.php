<?php
/**
 * Dashboard Main Layout
 *
 * @package ABSP
 * @since 1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}
?>
	<div class="absp-settings-panel pro-features">
		<div class="absp-settings-panel__body">
			<div class="pro-left-content">
				<img src="<?php absp_plugin_url( 'assets/images/logo2.png' );?>" alt="<?php esc_attr_e( 'Logo', 'absolute-addons' ); ?>">
				<h2><?php esc_html_e( 'Get Pro and Experience all those existing great features and widgets.', 'absolute-addons'); ?></h2>
				<a href="https://absoluteplugins.com/wordpress-plugins/absolute-addons/pricing/?utm_source=plugin-dashboard&utm_medium=pro-features&utm_campaign=get-pro&utm_content=get-pro" class="button"><?php esc_html_e( 'Get Pro Now', 'absolute-addons' ); ?></a>
			</div>
			<div class="pro-right-content">
				<div class="pro-features-content">
					<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="96.305" height="80" viewBox="0 0 96.305 80">
						<defs>
							<linearGradient id="linear-gradient" x1="0.273" y1="0.224" x2="0.814" y2="0.952" gradientUnits="objectBoundingBox">
								<stop offset="0" stop-color="#ffdc1d"/>
								<stop offset="0.174" stop-color="#ffd420"/>
								<stop offset="0.437" stop-color="#ffbf29"/>
								<stop offset="0.754" stop-color="#ff9d37"/>
								<stop offset="0.907" stop-color="#ff8b40"/>
							</linearGradient>
							<linearGradient id="linear-gradient-2" x1="0.154" y1="0.174" x2="0.901" y2="1.044" gradientUnits="objectBoundingBox">
								<stop offset="0.253" stop-color="#ffe700" stop-opacity="0"/>
								<stop offset="0.318" stop-color="#ffe501" stop-opacity="0.031"/>
								<stop offset="0.416" stop-color="#ffe005" stop-opacity="0.11"/>
								<stop offset="0.534" stop-color="#ffd80d" stop-opacity="0.247"/>
								<stop offset="0.67" stop-color="#ffcd16" stop-opacity="0.431"/>
								<stop offset="0.819" stop-color="#ffbe23" stop-opacity="0.671"/>
								<stop offset="0.977" stop-color="#ffad32" stop-opacity="0.957"/>
								<stop offset="1" stop-color="#ffab35"/>
							</linearGradient>
						</defs>
						<g id="Group_21" data-name="Group 21" transform="translate(-9500.702 -500.09)">
							<g id="Group_20" data-name="Group 20">
								<path id="Path_22" data-name="Path 22" d="M9579.151,578.415h-60.708a1,1,0,0,1-.857-.494,1.89,1.89,0,0,1-.28-1.079v-5.9a1.919,1.919,0,0,1,.282-1.082,1,1,0,0,1,.855-.492h60.708a1,1,0,0,1,.854.492l0,0a1.925,1.925,0,0,1,.28,1.079v5.9a1.928,1.928,0,0,1-.28,1.079A1,1,0,0,1,9579.151,578.415Zm-60.708-8.3c-.043,0-.13,0-.236.161a1.187,1.187,0,0,0-.157.668v5.9a1.182,1.182,0,0,0,.156.665c.108.164.194.164.237.164h60.708c.042,0,.128,0,.236-.164a1.2,1.2,0,0,0,.155-.665v-5.9a1.213,1.213,0,0,0-.155-.668c-.109-.161-.194-.161-.236-.161Zm59.834-2.948h-58.961a2.033,2.033,0,0,1-1.331-.36,2.343,2.343,0,0,1-.661-1.1l-10.812-35.277a2.6,2.6,0,0,1-.165-.383,6.235,6.235,0,0,1-4.136-1.184,4.09,4.09,0,0,1-1.5-3.584,4.705,4.705,0,0,1,1.593-3.257,4.111,4.111,0,0,1,3.41-1.132,4.425,4.425,0,0,1,3.489,2.026,5.951,5.951,0,0,1,.7,4.119l25.045,10.658,11.766-28.775a7.147,7.147,0,0,1-2.485-3.744,3.935,3.935,0,0,1,1.248-3.723,4.625,4.625,0,0,1,3.5-1.365,4.437,4.437,0,0,1,3.386,1.606,3.984,3.984,0,0,1,1.062,3.763,6.253,6.253,0,0,1-2.421,3.48l11.759,28.756c5.787-2.488,14.207-6.071,25.037-10.653-.433-2.55.266-4.415,2.08-5.549a4.714,4.714,0,0,1,2.86-.546,5.218,5.218,0,0,1,2.7,1.117,4.641,4.641,0,0,1,1.441,2.442h0a4.245,4.245,0,0,1-.243,2.807c-.908,1.97-2.687,2.893-5.317,2.749l-1,3-10.043,32.639a1.521,1.521,0,0,1-.662,1.2A2.687,2.687,0,0,1,9578.277,567.169Zm-71.255-37.884v.387a1.3,1.3,0,0,0,.179.487l10.833,35.327a1.61,1.61,0,0,0,.428.751,1.345,1.345,0,0,0,.854.187h58.961a1.988,1.988,0,0,0,.962-.169c.144-.082.254-.335.308-.713l10.059-32.7,1.187-3.562.291.024c2.472.208,4.066-.545,4.873-2.3a3.476,3.476,0,0,0,.194-2.323h0a3.847,3.847,0,0,0-1.2-2.047,4.477,4.477,0,0,0-2.306-.946,3.97,3.97,0,0,0-2.4.449c-1.6,1-2.15,2.657-1.669,5.059l.059.3-.278.118c-11.183,4.731-19.815,8.4-25.658,10.918l-.349.15L9550.09,508.69l.233-.172a5.7,5.7,0,0,0,2.369-3.223,3.25,3.25,0,0,0-.894-3.1,3.683,3.683,0,0,0-2.849-1.355,3.807,3.807,0,0,0-2.948,1.147,3.19,3.19,0,0,0-1.045,3.046,6.643,6.643,0,0,0,2.443,3.505l.205.174-.1.25-12.155,29.721L9509.071,527.5l.051-.29a5.392,5.392,0,0,0-.549-3.887,3.675,3.675,0,0,0-2.943-1.685,3.355,3.355,0,0,0-2.823.941,3.932,3.932,0,0,0-1.354,2.752,3.339,3.339,0,0,0,1.228,2.964,5.787,5.787,0,0,0,3.953,1.009Z" fill="url(#linear-gradient)"/>
							</g>
							<path id="Path_23" data-name="Path 23" d="M9580.871,533.3q-10.2,4.33-16.27,6.94l-6.632-16.218c-.091,0-.18-.007-.271-.007a33.435,33.435,0,0,0-15.4,3.74l-5.105,12.485-6.416-2.73a33.617,33.617,0,0,0-4.741,31.338h54.287a2.353,2.353,0,0,0,1.146-.219,1.267,1.267,0,0,0,.492-.982l6.927-22.514A33.611,33.611,0,0,0,9580.871,533.3Zm.328,38.5h-53.951a33.617,33.617,0,0,0,5.5,8.3H9581.2a.645.645,0,0,0,.546-.327,1.57,1.57,0,0,0,.218-.874v-5.9a1.574,1.574,0,0,0-.218-.874A.645.645,0,0,0,9581.2,571.792Z" fill="url(#linear-gradient-2)"/>
						</g>
					</svg>
					<div class="pro-features-title">
						<h3><?php esc_html_e( 'Pro Features', 'absolute-addons');?></h3>
						<p><?php esc_html_e( 'Why upgrade to Pro version?', 'absolute-addons');?></p>
					</div>
				</div>

				<div class="pro-video-content">
					<div class="pro-video-item">
						<div class="pro-icon">
							<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 148.2 149.3">
								<polyline
									points="144.7,24 144.7,55.5 115.1,55.5"
									style="fill:none;stroke:#C8C8C8;stroke-width:7;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;"
								/>
								<path
									d="M4.1,62.4C9.9,28.9,39,3.5,74.1,3.5c32.7,0,60.2,22,68.5,52.1"
									style="fill:none;stroke:#C8C8C8;stroke-width:7;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;"
								/>
								<polyline
									points="3.5,125.2 3.5,93.7 33,93.7"
									style="fill:none;stroke:#C8C8C8;stroke-width:7;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;"
								/>
								<path
									d="M144.1,86.9c-5.8,33.4-34.9,58.9-70,58.9c-32.7,0-60.2-22-68.5-52.1"
									style="fill:none;stroke:#C8C8C8;stroke-width:7;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;"
								/>
								<polygon
									points="74.1,53.3 81,67.3 96.5,69.6 85.3,80.5 87.9,95.9 74.1,88.7 60.2,95.9 62.9,80.5 51.7,69.6 67.2,67.3"
									style="fill:none;stroke:#C8C8C8;stroke-width:7;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;"
								/>
							</svg>
						</div>
						<div class="pro-text">
							<h3><?php esc_html_e( 'All Pro Layout Preset', 'absolute-addons');?></h3>
							<p><?php esc_html_e( 'We have 103 free Preset layout and 135 pro Preset layout at 21 Widgets and 1500+ coming next on continuous updates. If you are using free version of the plugin you will not able to use pro preset layout and also can not able to use all blocks from importer. To use all blocks and layout you should buy our pro plugin.', 'absolute-addons');?></p>
						</div>
					</div>
					<div class="pro-video-item">
						<div class="pro-icon">
							<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 147 145">
								<polygon
									style="fill:none;stroke:#C8C8C8;stroke-width:7;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;"
									points="143.5,23.2 111.5,40.4 73.3,3.5 33.9,40.8 3.5,23.2 3.5,141.5 143.5,141.5"
								/>
								<line
									style="fill:none;stroke:#C8C8C8;stroke-width:7;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;"
									x1="3.8" y1="126.7" x2="142.8" y2="126.7"
								/>
								<line
									style="fill:none;stroke:#C8C8C8;stroke-width:7;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;"
									x1="3.8" y1="61.3" x2="142.8" y2="61.3"
								/>
								<polygon
									style="fill:none;stroke:#C8C8C8;stroke-width:7;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;"
									points="73.3,77.4 78.7,88.3 90.9,90.1 82.1,98.6 84.2,110.7 73.3,105 62.5,110.7 64.6,98.6 55.8,90.1 67.9,88.3"
								/>
							</svg>
						</div>
						<div class="pro-text">
							<h3><?php esc_html_e( 'All Pro Widget', 'absolute-addons');?></h3>
							<p><?php esc_html_e( 'We have 16 free and 5 pro widgets and 100s are coming on continuous updates which you can enjoy when you will buy our pro plugins. So all widgets and layout you can use under pro plan.', 'absolute-addons');?></p>
						</div>
					</div>
					<div class="pro-video-item">
						<div class="pro-icon">
							<svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 157 125.6">

								<path
									style="fill:none;stroke:#C8C8C8;stroke-width:7;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;"
									d="M135.3,3.5l18.2,31.3l-74,86.4c-1,1.3-1.3,1-2,0l-74-86.4L21.7,3.5H135.3z"/>
								<line
									style="fill:none;stroke:#C8C8C8;stroke-width:7;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;"
									x1="152.8" y1="34.7" x2="4.1" y2="34.7"/>
								<polyline
									style="fill:none;stroke:#C8C8C8;stroke-width:7;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;"
									points="21.7,3.5 78.5,33.8 135.3,3.5"/>
								<polyline
									style="fill:none;stroke:#C8C8C8;stroke-width:7;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;"
									points="78.5,5.2 78.5,121.2 40.8,36.5"/>
								<line
									style="fill:none;stroke:#C8C8C8;stroke-width:7;stroke-linecap:round;stroke-linejoin:round;stroke-miterlimit:10;"
									x1="77.5" y1="121.2" x2="117.8" y2="36.5"/>
							</svg>
						</div>
						<div class="pro-text">
							<h3><?php esc_html_e( 'All Pro Features', 'absolute-addons');?></h3>
							<p><?php esc_html_e( 'We are planned for weekly 2 updates and a major updates after 4 updates. We are coming with a lot of features and functionality which will be available only for pro member of our plugin.', 'absolute-addons');?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
// End of file dashboard-tab-pro-features.php.
