<?php
/**
 * Admin: Activity
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



// Find the boundaries.
global $wpdb;
$date_min = $wpdb->get_var("SELECT MIN(DATE(`date_created`)) FROM `{$wpdb->prefix}meow2_log`");
if (! \is_null($date_min)) {
	$date_max = $wpdb->get_var("SELECT MAX(DATE(`date_created`)) FROM `{$wpdb->prefix}meow2_log`");
}
else {
	$date_min = \current_time('Y-m-d');
	$date_max = \current_time('Y-m-d');
}



$orders = array(
	'date_created'=>\__('Date', 'apocalypse-meow'),
	'ip'=>\__('IP', 'apocalypse-meow'),
	'type'=>\__('Status', 'apocalypse-meow'),
	'username'=>\__('Username', 'apocalypse-meow'),
);



$data = array(
	'date_min'=>$date_min,
	'date_max'=>$date_max,
	'forms'=>array(
		'search'=>array(
			'action'=>'meow_ajax_activity',
			'n'=>ajax::get_nonce(),
			'date_min'=>$date_min,
			'date_max'=>$date_max,
			'username'=>'',
			'usernameExact'=>1,
			'ip'=>'',
			'subnet'=>'',
			'type'=>'',
			'page'=>0,
			'pageSize'=>50,
			'orderby'=>'date_created',
			'order'=>'desc',
			'errors'=>array(),
			'loading'=>false,
		),
		'pardon'=>array(
			'action'=>'meow_ajax_pardon',
			'n'=>ajax::get_nonce(),
			'id'=>0,
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
	'lastSearch'=>0,
	'results'=>array(
		'page'=>0,
		'pages'=>0,
		'total'=>0,
		'items'=>array(),
		'bans'=>array(),
	),
	'searched'=>false,
	'showCommunityJail'=>false,
	'modal'=>false,
	'modals'=>array(
		'jail'=>array(
			\__('If an offender was wrongly accused, you can set things right by issuing a pardon. That will instantly clear the ban so they can try to login again.', 'apocalypse-meow'),
			\sprintf(
				\__("Note: they will still be subject to future bans if they haven't learned their lesson. To prevent someone from repeatedly ending up in jail, add their IP or Subnet to the %s", 'apocalypse-meow'),
				'<a href="' . \esc_url(\admin_url('admin.php?page=meow-settings')) . '">' . \__('whitelist', 'apocalypse-meow') . '</a>'
			),
		),
	),
	'download'=>'',
	'downloadName'=>'',
);

// JSON doesn't appreciate broken UTF.
admin::json_meowdata($data);
?>
<div class="wrap" id="vue-activity" v-cloak>
	<h1>Apocalypse Meow: <?php echo \__('Activity', 'apocalypse-meow'); ?></h1>

	<div class="error" v-for="error in forms.search.errors"><p>{{error}}</p></div>
	<div class="error" v-for="error in forms.pardon.errors"><p>{{error}}</p></div>
	<div class="error" v-for="error in forms.download.errors"><p>{{error}}</p></div>
	<div class="updated" v-if="!searched"><p><?php echo \__('The login activity is being fetched. Hold tight.', 'apocalypse-meow'); ?></p></div>

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

			<div class="postbox-container two">

				<!-- ==============================================
				RESULTS
				=============================================== -->
				<div class="postbox">
					<h3 class="hndle">
						<?php echo \__('Login Activity', 'apocalypse-meow'); ?>
						<span v-if="results.total">({{results.total}})</span>
					</h3>
					<div class="inside">
						<p v-if="!results.total"><?php echo \__('No records matched the search. Sorry.', 'apocalypse-meow'); ?></p>

						<table v-if="results.total" class="meow-results">
							<thead>
								<tr>
									<th><?php echo \__('Date', 'apocalypse-meow'); ?></th>
									<th><?php echo \__('Status', 'apocalypse-meow'); ?></th>
									<th><?php echo \__('Network', 'apocalypse-meow'); ?></th>
									<th v-if="returnedStatuses.length > 1 || returnedStatuses.indexOf('ban') === -1"><?php echo \__('Username', 'apocalypse-meow'); ?></th>
								</tr>
							</thead>
							<tbody>
								<tr v-for="item in results.items">
									<td>
										{{item.date_created}}
										<div v-if="item.banRemaining" class="status-ban">
											<?php echo \__('Expires', 'apocalypse-meow'); ?>: {{ item.banRemaining | relativeTime }}
										</div>
									</td>
									<td v-bind:class="['status-' + item.type, {'status-pardoned': item.pardoned}]">
										<a v-on:click.prevent="forms.search.type = item.type; searchSubmit()" style="cursor:pointer">{{ item.type | status }}</a>
										<div v-if="(item.type === 'ban') && item.community">
											<?php echo \__('Community Pool', 'apocalypse-meow'); ?>
										</div>
									<td>
										<a v-if="item.ip !== '0'" v-on:click.prevent="forms.search.ip = item.ip; searchSubmit()" style="cursor:pointer; display: block;">{{item.ip}}</a>
										<a v-if="item.subnet !== '0'" v-on:click.prevent="forms.search.subnet = item.subnet; searchSubmit()" style="cursor:pointer; display: block;">{{item.subnet}}</a>
									</td>
									<td v-if="returnedStatuses.length > 1 || returnedStatuses.indexOf('ban') === -1" v-bind:class="{ 'invalid-username' : !item.userExists, 'valid-username' : item.userExists }">
										<a v-on:click.prevent="forms.search.username = item.username; searchSubmit()" style="cursor:pointer">{{item.username}}</a>
									</td>
								</tr>
							</tbody>
						</table>



						<!-- ==============================================
						PAGINATION
						=============================================== -->
						<nav class="meow-pagination" v-if="results.pages > 0">
							<a v-bind:disabled="forms.search.loading || results.page === 0" v-on:click.prevent="!forms.search.loading && pageSubmit(-1)" class="meow-pagination--link"><span class="dashicons dashicons-arrow-left-alt2"></span> <?php echo \__('Back', 'apocalypse-meow'); ?></a>

							<span class="meow-pagination--current meow-fg-grey">{{results.page + 1}} / {{results.pages + 1}}</span>

							<a v-bind:disabled="forms.search.loading || results.page === results.pages" v-on:click.prevent="!forms.search.loading && pageSubmit(1)" class="meow-pagination--link"><?php
								echo \__('Next', 'apocalypse-meow');
							?> <span class="dashicons dashicons-arrow-right-alt2"></span></a>
						</nav>
					</div>
				</div>
			</div><!--.postbox-container-->

			<!-- Sidebar -->
			<div class="postbox-container one">
				<!-- ==============================================
				ACTIVE BANS
				=============================================== -->
				<div class="postbox">
					<h3 class="hndle">
						<?php echo \__('Login Jail', 'apocalypse-meow'); ?>

						<span class="dashicons dashicons-editor-help meow-info-toggle" v-bind:class="{'is-active' : modal === 'jail'}" v-on:click.prevent="toggleModal('jail')"></span>
					</h3>
					<div class="inside" v-if="!results.bans.length">
						<p><?php
							echo \__("Congratulations! Nobody is banned from the site at the moment. If that changes, you'll find them listed here.", 'apocalypse-meow');
						?></p>
					</div>
					<div class="inside" v-else>
						<div class="meow-jail" v-for="item in results.bans" v-if="!item.community || showCommunityJail">
							<table class="meow-meta">
								<tbody>
									<tr>
										<th scope="row"><?php echo \__('Offender', 'apocalypse-meow'); ?></th>
										<td>
											<a v-if="item.ip !== '0'" v-on:click.prevent="forms.search.ip = item.ip; searchSubmit()" style="cursor:pointer; display: block;">{{item.ip}}</a>
											<a v-if="item.subnet !== '0'" v-on:click.prevent="forms.search.subnet = item.subnet; searchSubmit()" style="cursor:pointer; display: block;">{{item.subnet}}</a>
										</td>
									</tr>
									<tr>
										<th scope="row"><?php echo \__('Banned', 'apocalypse-meow'); ?></th>
										<td>
											{{item.date_created}}
											<div v-if="item.community" class="meow-fg-red"><?php echo \__('Community Pool', 'apocalypse-meow'); ?></div>
										</td>
									</tr>
									<tr>
										<th scope="row"><?php echo \__('Remaining', 'apocalypse-meow'); ?></th>
										<td>
											{{ item.banRemaining | relativeTime }}
										</td>
									</tr>
									<tr>
										<td></td>
										<td>
											<button type="button" v-on:click.prevent="pardonSubmit(item.id)" v-bind:disabled="forms.pardon.loading" class="button button-small"><?php echo \__('Pardon', 'apocalypse-meow'); ?></button>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<div class="inside" v-if="hasCommunityJail">
						<p class="description" v-if="!showCommunityJail"><?php
							\printf(
								\__('Community Pool bans are hidden by default. Click %s to view them.', 'apocalypse-meow'),
								'<a href="#" v-on:click.prevent="showCommunityJail = !showCommunityJail">' . \__('here', 'apocalypse-meow') . '</a>'
							);
							?>
						</p>
					</div>
				</div>



				<!-- ==============================================
				DOWNLOAD
				=============================================== -->
				<div class="postbox">
					<h3 class="hndle"><?php echo \__('Export Activity', 'apocalypse-meow'); ?></h3>
					<div class="inside">
						<p v-if="!forms.download.loading && !download"><?php echo \__('Click the button below to generate a CSV containing all the login activity for your site.', 'apocalypse-meow'); ?></p>
						<p v-if="forms.download.loading && !download"><?php echo \__('The CSV is being compiled. This might take a while if your site has a lot of data.', 'apocalypse-meow'); ?></p>

						<button type="button" class="button button-primary button-large" v-if="!download" v-on:click.prevent="downloadSubmit" v-bind:disabled="forms.download.loading"><?php echo \__('Start Export', 'apocalypse-meow'); ?></button>

						<a class="button button-primary button-large" v-if="download" v-bind:href="download" v-bind:download="downloadName"><?php echo \__('Download CSV', 'apocalypse-meow'); ?></a>
					</div>
				</div>



				<!-- ==============================================
				SEARCH
				=============================================== -->
				<div class="postbox">
					<h3 class="hndle"><?php echo \__('Search', 'apocalypse-meow'); ?></h3>
					<div class="inside">
						<form name="searchForm" method="post" action="<?php echo \admin_url('admin-ajax.php'); ?>" v-on:submit.prevent="searchSubmit">
							<table class="meow-settings narrow">
								<tbody>
									<tr>
										<th scope="row"><label for="search-date_min"><?php echo \__('From', 'apocalypse-meow'); ?></label></th>
										<td>
											<input type="date" id="search-date_min" v-model="forms.search.date_min" required v-bind:min="date_min" v-bind:max="date_max" />
										</td>
									</tr>
									<tr>
										<th scope="row"><label for="search-date_max"><?php echo \__('To', 'apocalypse-meow'); ?></label></th>
										<td>
											<input type="date" id="search-date_max" v-model="forms.search.date_max" required v-bind:min="date_min" v-bind:max="date_max" />
										</td>
									</tr>
									<tr>
										<th scope="row"><label for="search-type"><?php echo \__('Status', 'apocalypse-meow'); ?></label></th>
										<td>
											<select id="search-type" v-model.trim="forms.search.type">
												<option value=""> --- </option>
												<option value="ban"><?php echo \__('Ban', 'apocalypse-meow'); ?></option>
												<option value="fail"><?php echo \__('Failure', 'apocalypse-meow'); ?></option>
												<option value="success"><?php echo \__('Success', 'apocalypse-meow'); ?></option>
											</select>
										</td>
									</tr>
									<tr>
										<th scope="row"><label for="search-username"><?php echo \__('Username', 'apocalypse-meow'); ?></label></th>
										<td>
											<input type="text" id="search-username" v-model.trim="forms.search.username" />

											<p v-if="forms.search.username.length >= 3"><label><input type="checkbox" v-model.number="forms.search.usernameExact" v-bind:true-value="1" v-bind:false-value="0" /> <?php echo \__('Exact Match', 'apocalypse-meow'); ?></label></p>
										</td>
									</tr>
									<tr>
										<th scope="row"><label for="search-ip"><?php echo \__('IP', 'apocalypse-meow'); ?></label></th>
										<td>
											<input type="text" id="search-ip" v-model.trim="forms.search.ip" minlength="7" />
										</td>
									</tr>
									<tr>
										<th scope="row"><label for="search-subnet"><?php echo \__('Subnet', 'apocalypse-meow'); ?></label></th>
										<td>
											<input type="text" id="search-subnet" v-model.trim="forms.search.subnet" minlength="9" />
										</td>
									</tr>
									<tr>
										<th scope="row"><label for="search-pageSize"><?php echo \__('Page Size', 'apocalypse-meow'); ?></label></th>
										<td>
											<input type="number" id="search-pageSize" v-model.number="forms.search.pageSize" min="1" max="500" step="1" />

											<p class="description"><?php echo \__('Search results are paginated. This value indicates how much you want to see per page.', 'apocalypse-meow'); ?></p>
										</td>
									</tr>
									<tr>
										<th scope="row"><label for="search-pageSize"><?php echo \__('Order By', 'apocalypse-meow'); ?></label></th>
										<td>
											<select v-model="forms.search.orderby">
												<?php foreach ($orders as $k=>$v) { ?>
													<option value="<?php echo $k; ?>"><?php echo $v; ?></option>
												<?php } ?>
											</select>
											<select v-model="forms.search.order">
												<option value="asc"><?php echo \__('ASC', 'apocalypse-meow'); ?></option>
												<option value="desc"><?php echo \__('DESC', 'apocalypse-meow'); ?></option>
											</select>
										</td>
									</tr>
									<tr>
										<th scope="row">&nbsp;</th>
										<td>
											<button type="submit" class="button button-large button-primary" v-bind:disabled="forms.search.loading"><?php echo \__('Search', 'apocalypse-meow'); ?></button>
										</td>
									</tr>
								</tbody>
							</table>
						</form>
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
