<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<div class="wrap container-fluid afkw-container">
	<div class="afkw-inner-container">
		<?php include 'inc/top.view.php'; ?>

		<div id="afkw__app" v-cloak>
			<div v-if="sync_logs && sync_logs.length < 1" class="afkw-segment">
				<p><?php echo esc_html__('No logs available.', 'auto-focus-keyword-for-seo'); ?></p>
			</div>

			<div v-else class="afkw-segment">
				<div class="afkw-alert afkw-note">
					<?php echo esc_html__("Note: Deleting an item from sync logs will remove the focus keyword value as well (if it's not modified) and it will be available to sync again. If you modify the focus keyword in any way after sync, then the focus keyword value won't be removed. The delete button will simply remove the log entry without affecting your custom focus keyword value. If you don't want to add the focus keyword for a post then make sure to add it to the blacklist on the Settings tab.", "auto-focus-keyword-for-seo"); ?>
				</div>

				<div class="row middle-xs between-md" style="margin-bottom: 10px;">
					<div class="col-xs-12 col-md-6">
						<h2 class="afkw-title"><?php echo esc_html__('Sync Logs', 'auto-focus-keyword-for-seo'); ?></h2>
					</div>
					<div class="col-xs-12 col-md-4">
						<div class="afkw-stats">
							<?php echo esc_html__('Total number of keywords synced:', 'auto-focus-keyword-for-seo'); ?>
							<strong>{{ sync_logs.length }}</strong>
						</div>
					</div>
				</div>

				<div v-if="errors.length > 0" class="afkw-alert afkw-error">
					<ul>
						<li v-for="(error, i) in errors" :key="i">
							{{ error }}
						</li>
					</ul>
				</div>

				<div v-if="deletingProgress" class="afkw-progress-container" style="margin: 15px 0 25px;">
					<div class="afkw-progress del" :style="{width: `${deletingProgress}`+'%'}"></div>
					<div class="afkw-percentage del" :style="{left: `${deletingProgress}`+'%'}">{{ deletingProgress }}%</div>
				</div>

				<div class="row middle-xs afkw-log-header" style="margin: 0">
					<div class="col-xs-9">
						<div class="row middle-xs" style="margin: 0">
							<div class="col-xs-2 col-md-1" style="padding-left: 0.2rem; max-width: 40px;">
								<input type="checkbox" v-model="selectAllCheckbox" @change="selectAll($event)" />
							</div>
							<div class="col-xs">
								<button v-if="ids.length" @click.prevent="stopFlag = false, bulkDeleteLogs(), disabled = true" :class="['afkw-btn del', disabled ? 'disabled' : '']" class="afkw-btn del" style="display: inline-block; width: auto; padding: 8px 14px; margin-left: 5px;" v-cloak>
									<?php echo esc_html__('Delete Selected Items', 'auto-focus-keyword-for-seo'); ?>
								</button>

								<button v-if="stopDeleteBtn" @click.prevent="stopFlag = true, disabled = false, stopDeleteBtn = false" class="afkw-btn del" style="display: inline-block; width: auto; padding: 8px 14px; margin-left: 5px;" v-cloak>
									<?php echo esc_html__('Stop Deleting Progress', 'auto-focus-keyword-for-seo'); ?>
								</button>
							</div>
						</div>
					</div>
					<div class="col-xs-3">
						<input class="afkw-input" type="text" v-model="search" placeholder="<?php echo esc_html__('Search', 'auto-focus-keyword-for-seo'); ?>" />
					</div>
				</div>

				<div v-for="item in filteredItems" :key="item.post_id" class="afkw-log-item">
					<div class="row">
						<div class="col-xs-2 col-md-1 afkw-selected" style="max-width: 50px;">
							<input type="checkbox" :value="item.post_id" v-model="ids" style="margin-left: 10px" />
						</div>
						<div class="col-xs">
							"<a :href="'post.php?post='+item.post_id+'&action=edit'" target="_blank">{{ item.post_title }}</a>"
							<span v-if="item.created_at == item.updated_at"><?php echo esc_html__('created at ', 'auto-focus-keyword-for-seo'); ?></span>
							<span v-else><?php echo esc_html__('updated at ', 'auto-focus-keyword-for-seo'); ?></span>
							<span v-html="convertTimestamp(item.updated_at)"></span>.
							<a href="#" class="del" @click.prevent="deleteLogItem(item.post_id)"><?php echo esc_html__('Delete', 'auto-focus-keyword-for-seo'); ?></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
