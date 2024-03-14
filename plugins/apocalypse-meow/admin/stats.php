<?php
/**
 * Admin: Statistics
 *
 * @package Apocalypse Meow
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

/**
 * Do not execute this file directly.
 */
if (! \defined('ABSPATH')) {
	exit;
}

use blobfolio\wp\meow\admin;
use blobfolio\wp\meow\ajax;
use blobfolio\wp\meow\options;
use blobfolio\wp\meow\vendor\common;



$data = array(
	'forms'=>array(
		'search'=>array(
			'action'=>'meow_ajax_stats',
			'n'=>ajax::get_nonce(),
			'errors'=>array(),
			'loading'=>false,
		),
		'download'=>array(
			'action'=>'meow_ajax_activity_csv',
			'n'=>ajax::get_nonce(),
			'errors'=>array(),
			'loading'=>false,
		),
	),
	'stats'=>array(),
	'hasStats'=>false,
	'searched'=>false,
	'modal'=>false,
	'modals'=>array(
		'attempts'=>array(
			\__('This indicates the average number of login attempts made *while banned*. This number can be high if your site is routinely attacked by stupid robots.', 'apocalypse-meow'),
		),
		'usernames'=>array(
			\__('This shows the total number of unique usernames submitted during failed login attempts.', 'apocalypse-meow'),
			\__('Note: WordPress allows users to login using either their username or email address. This plugin normalizes all entries to the username to keep things tidy.', 'apocalypse-meow'),
		),
		'invalid'=>array(
			\__('This shows the percentage of failed login attempts using non-existent usernames. While such attempts are fruitless, they do still represent a waste in server resources.', 'apocalypse-meow'),
		),
		'valid'=>array(
			\__('This shows the percentage of failed login attempts using *valid* usernames. Left unchecked, a robot could eventually gain access to the site.', 'apocalypse-meow'),
		),
	),
	'download'=>'',
	'downloadName'=>'',
);

// JSON doesn't appreciate broken UTF.
admin::json_meowdata($data);
?>
<div class="wrap" id="vue-stats" v-cloak>
	<h1>Apocalypse Meow: <?php echo \__('Stats', 'apocalypse-meow'); ?></h1>

	<div class="error" v-for="error in forms.search.errors"><p>{{error}}</p></div>
	<div class="error" v-for="error in forms.download.errors"><p>{{error}}</p></div>
	<div class="updated" v-if="!searched"><p><?php echo \__('The stats are being crunched. Hold tight.', 'apocalypse-meow'); ?></p></div>
	<div class="error" v-if="searched && !hasStats"><p><?php echo \__('No stats were found.', 'apocalypse-meow'); ?></p></div>

	<?php if (options::get('prune-active')) { ?>
		<div class="notice notice-info">
			<p><?php \printf(
				\__('Login data is currently pruned after %s. To change this going forward, visit the %s page.', 'apocalypse-meow'),
				common\format::inflect(options::get('prune-limit'), \__('%d day', 'apocalypse-meow'), \__('%d days', 'apocalypse-meow')),
				'<a href="' . \esc_url(\admin_url('admin.php?page=meow-settings')) . '">' . \__('settings', 'apocalypse-meow') . '</a>'
			); ?></p>
		</div>
	<?php } ?>

	<div id="poststuff">
		<div id="post-body" class="metabox-holder meow-columns one-two fixed" v-if="searched">

			<!-- Results -->
			<div class="postbox-container two" v-if="hasStats">
				<!-- ==============================================
				Period
				=============================================== -->
				<div class="postbox" v-if="hasStats && stats.volume && stats.volume.labels && stats.volume.labels.length > 1">
					<h3 class="hndle"><?php echo \__('Login Activity', 'apocalypse-meow'); ?></h3>
					<div class="inside" style="position: relative">
						<chartist
							ratio="ct-major-seventh"
							type="Line"
							:data="stats.volume"
							:options="lineOptions">
						</chartist>

						<ul class="ct-legend" style="position: absolute; top: 0; right: 10px; margin: 0;">
							<li class="ct-series-a"><?php echo \__('Ban', 'apocalypse-meow'); ?></li>
							<li class="ct-series-b"><?php echo \__('Failure', 'apocalypse-meow'); ?></li>
							<li class="ct-series-c"><?php echo \__('Success', 'apocalypse-meow'); ?></li>
						</ulv>
					</div>
				</div>



				<!-- ==============================================
				Breakdown
				=============================================== -->
				<div class="postbox">
					<h3 class="hndle"><?php echo \__('Breakdown', 'apocalypse-meow'); ?></h3>
					<div class="inside">
						<table class="meow-stats">
							<thead>
								<tr>
									<th><?php echo \__('General', 'apocalypse-meow'); ?></th>
									<th class="middle"><?php echo \__('Failures', 'apocalypse-meow'); ?></th>
									<th><?php echo \__('Bans', 'apocalypse-meow'); ?></th>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td>
										<table class="meow-meta">
											<tbody>
												<tr>
													<th scope="row"><?php echo \__('Total', 'apocalypse-meow'); ?></th>
													<td>{{ stats.total }}</td>
												</tr>
												<tr>
													<th scope="row"><?php echo \__('First', 'apocalypse-meow'); ?></th>
													<td>{{ stats.date_min }}</td>
												</tr>
												<tr>
													<th scope="row"><?php echo \__('Last', 'apocalypse-meow'); ?></th>
													<td>{{ stats.date_max }}</td>
												</tr>
												<tr>
													<th scope="row"><?php echo \__('# Days', 'apocalypse-meow'); ?></th>
													<td>{{ stats.days }}</td>
												</tr>
												<tr>
													<th scope="row"><?php echo \__('Daily Avg', 'apocalypse-meow'); ?></th>
													<td>{{ Math.round(stats.total / stats.days * 100) / 100 }}</td>
												</tr>
											</tbody>
										</table>
									</td>
									<td class="middle">
										<table class="meow-meta" v-if="stats.fails.total">
											<tbody>
												<tr>
													<th scope="row"><?php echo \__('Total', 'apocalypse-meow'); ?></th>
													<td>{{ stats.fails.total }}</td>
												</tr>
												<tr>
													<th scope="row"><?php echo \__('Daily Avg', 'apocalypse-meow'); ?></th>
													<td>{{ Math.round(stats.fails.total / stats.days * 100) / 100 }}</td>
												</tr>
												<tr>
													<th scope="row">
														<?php echo \__('Unique Usernames', 'apocalypse-meow'); ?>
														<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'usernames'}" v-on:click.prevent="toggleModal('usernames')"></span>
													</th>
													<td>{{ stats.fails.usernames.unique }}</td>
												</tr>
												<tr>
													<th scope="row">
														<?php echo \__('Valid Users', 'apocalypse-meow'); ?>

														<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'valid'}" v-on:click.prevent="toggleModal('valid')"></span>
													</th>
													<td>{{ Math.round(stats.fails.usernames.valid / (stats.fails.usernames.invalid + stats.fails.usernames.valid) * 10000) / 100 }}%</td>
												</tr>
												<tr>
													<th scope="row">
														<?php echo \__('w/ Invalid Username', 'apocalypse-meow'); ?>

														<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'invalid'}" v-on:click.prevent="toggleModal('invalid')"></span>
													</th>
													<td>{{ Math.round(stats.fails.usernames.invalid / (stats.fails.usernames.invalid + stats.fails.usernames.valid) * 10000) / 100 }}%</td>
												</tr>
												<tr v-if="stats.fails.enumeration > 0">
													<th scope="row"><?php echo \__('Enumeration Attempts', 'apocalypse-meow'); ?></th>
													<td>{{ stats.fails.enumeration }}</td>
												</tr>
												<tr>
													<th scope="row"><?php echo \__('Unique IPs', 'apocalypse-meow'); ?></th>
													<td>{{ stats.fails.ips }}</td>
												</tr>
												<tr>
													<th scope="row"><?php echo \__('Unique Subnets', 'apocalypse-meow'); ?></th>
													<td>{{ stats.fails.subnets }}</td>
												</tr>
											</tbody>
										</table>
										<p v-else class="description"><?php echo \__('No failures have been recorded.', 'apocalypse-meow'); ?></p>
									</td>
									<td>
										<table class="meow-meta" v-if="stats.bans.total">
											<tbody>
												<tr>
													<th scope="row"><?php echo \__('Total', 'apocalypse-meow'); ?></th>
													<td>{{ stats.bans.total }}</td>
												</tr>
												<tr>
													<th scope="row"><?php echo \__('Daily Avg', 'apocalypse-meow'); ?></th>
													<td>{{ Math.round(stats.bans.total / stats.days * 100) / 100 }}</td>
												</tr>
												<tr>
													<th scope="row"><?php echo \__('Pardons', 'apocalypse-meow'); ?></th>
													<td>{{ stats.bans.pardons }}</td>
												</tr>
												<tr>
													<th scope="row">
														<?php echo \__('While Banned', 'apocalypse-meow'); ?>

														<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'attempts'}" v-on:click.prevent="toggleModal('attempts')"></span>
													</th>
													<td>{{ Math.round(stats.bans.attempts / stats.bans.total * 100) / 100 }}</td>
												</tr>
											</tbody>
										</table>
										<p v-else class="description"><?php echo \__('No bans have been recorded.', 'apocalypse-meow'); ?></p>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div><!--.postbox-container-->

			<!-- Search -->
			<div class="postbox-container one">
				<!-- ==============================================
				DOWNLOAD
				=============================================== -->
				<div class="postbox">
					<h3 class="hndle"><?php echo \__('Export Data', 'apocalypse-meow'); ?></h3>
					<div class="inside">
						<p v-if="!forms.download.loading && !download"><?php echo \__('Click the button below to generate a CSV containing all the login data for your site.', 'apocalypse-meow'); ?></p>
						<p v-if="forms.download.loading && !download"><?php echo \__('The CSV is being compiled. This might take a while if your site has a lot of data.', 'apocalypse-meow'); ?></p>

						<button type="button" class="button button-primary button-large" v-if="!download" v-on:click.prevent="downloadSubmit" v-bind:disabled="forms.download.loading"><?php echo \__('Start Export', 'apocalypse-meow'); ?></button>

						<a class="button button-primary button-large" v-if="download" v-bind:href="download" v-bind:download="downloadName"><?php echo \__('Download CSV', 'apocalypse-meow'); ?></a>
					</div>
				</div>

				<!-- ==============================================
				Status
				=============================================== -->
				<div class="postbox" v-if="hasStats">
					<h3 class="hndle"><?php echo \__('Activity by Type', 'apocalypse-meow'); ?></h3>
					<div class="inside">
						<chartist
							ratio="ct-square"
							type="Pie"
							:data="stats.status"
							:options="pieOptions">
						</chartist>
					</div>
				</div>

				<!-- ==============================================
				Username
				=============================================== -->
				<div class="postbox" v-if="hasStats && stats.username && stats.username.labels && stats.username.labels.length > 1">
					<h3 class="hndle"><?php echo \__('Failures by Username', 'apocalypse-meow'); ?></h3>
					<div class="inside">
						<chartist
							ratio="ct-square"
							type="Pie"
							:data="stats.username"
							:options="pieOptions">
						</chartist>
					</div>
				</div>

				<!-- ==============================================
				Network Type
				=============================================== -->
				<div class="postbox" v-if="hasStats && stats.ip.series[0] && stats.ip.series[1]">
					<h3 class="hndle"><?php echo \__('Failures by Network Type', 'apocalypse-meow'); ?></h3>
					<div class="inside">
						<chartist
							ratio="ct-square"
							type="Pie"
							:data="stats.ip"
							:options="pieOptions">
						</chartist>
					</div>
				</div>

			</div><!--.postbox-container-->

		</div><!--#post-body-->
	</div><!--#poststuff-->



	<!-- ==============================================
	HELP MODAL
	=============================================== -->
	<transition name="fade">
		<div v-if="modal" class="meow-modal">
			<span class="dashicons dashicons-dismiss meow-modal--close" v-on:click.prevent="toggleModal('')"></span>
			<img src="<?php echo \MEOW_PLUGIN_URL; ?>img/kitten.gif" class="meow-modal--cat" alt="Kitten" />
			<div class="meow-modal--inner">
				<p v-for="p in modals[modal]" v-html="p"></p>
			</div>
		</div>
	</transition>

</div><!--.wrap-->
