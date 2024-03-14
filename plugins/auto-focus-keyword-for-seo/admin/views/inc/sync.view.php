<?php if ( ! defined( 'ABSPATH' ) ) { exit; } ?>

<div class="afkw-segment" v-cloak>

    <h2><?php echo esc_html__("STEP 2: Sync", "auto-focus-keyword-for-seo"); ?></h2>

    <p><?php echo esc_html__("Before adding your Focus Keywords, you should allow the plugin to identify where they are located. A quick “Fetch Items” and ... you're ready to “Sync”", "auto-focus-keyword-for-seo"); ?></p>

    <div v-if="total_items" class="row">

        <div class="col-xs">

            <div class="afkw-alert afkw-info">
                {{ total_items }}  <?php echo esc_html__('published items found without focus keyword(s). Click "Fetch Items" button to get ready for Sync.', "auto-focus-keyword-for-seo"); ?>
            </div>

            <div>
                <button type="submit" @click.prevent="stopFlag = false, batchFetch(), disabled = true" :class="['afkw-btn bulk', disabled ? 'disabled' : '']" :disabled="disabled">{{ stopFetchBtn ? "<?php echo esc_html__("Fetching...", "auto-focus-keyword-for-seo"); ?>" : "<?php echo esc_html__("Fetch Items", "auto-focus-keyword-for-seo"); ?>"}}</button>

                <button v-if="stopFetchBtn" @click.prevent="stopFlag = true, disabled = false, stopFetchBtn = false" class="afkw-btn danger bulk" style="margin-left: 5px;">Stop</button>

                <span v-if="syncDate" class="afkw-date"><?php echo esc_html__("Last Synced:", "auto-focus-keyword-for-seo"); ?> {{ syncDate }}</span>
            </div>

            <br />

            <div v-if="fetchingProgress" class="afkw-progress-container">
                <div class="afkw-progress" :style="{width: `${fetchingProgress}`+'%'}"></div>
                <div class="afkw-percentage" :style="{left: `${fetchingProgress}`+'%'}">{{ fetchingProgress }}%</div>
            </div>

            <div v-if="stopFetchBtn" class="afkw-alert afkw-note" style="padding: 10px; margin-top: 15px"><?php echo esc_html__("Do not close or move from this page. Progress will be cancelled.", "auto-focus-keyword-for-seo"); ?></div>

            <div v-if="sync && syncRequired" style="margin-top: 30px;">

                <div v-if="syncRequired.length > 0" class="afkw-alert afkw-info">
                <?php echo esc_html__("Great. You've", "auto-focus-keyword-for-seo"); ?> {{ syncRequired.length }} <?php echo esc_html__("items in the waiting list below. Modify list (by deleting posts you don't want to sync) and hit that <strong>SYNC NOW</strong> button.", "auto-focus-keyword-for-seo"); ?>
                </div>

                <h2 class="afkw-title"><?php echo esc_html__('Waiting List', 'auto-focus-keyword-for-seo'); ?></h2>
                <div v-if="syncRequired.length" class="afkw-segment" style="padding: 5px 20px; max-height: 150px; overflow: auto; margin-top: 5px;">
                    <ul class="afkw-sync-items">
                        <li v-for="item in syncRequired" :key="item.ID">
                            {{ item.post_title }}
                            <button @click="deleteItem(item.ID)" class="afkw-btn del danger">&#x2715</button>
                        </li>
                    </ul>                               
                </div>

                <div style="margin-top: 15px;">
                    <button type="submit" @click.prevent="stopFlag = false, bulkAdd()" :class="['afkw-btn bulk ', disabled ? 'disabled' : '']" :disabled="disabled">{{ stopStoreBtn ? "<?php echo esc_html__("Processing...", "auto-focus-keyword-for-seo"); ?>" : "<?php echo esc_html__("Sync Now", "auto-focus-keyword-for-seo"); ?>"}}</button>

                    <button v-if="stopStoreBtn" @click.prevent="stopFlag = true, bulkStop()" class="afkw-btn danger bulk" style="margin-left: 5px;">Stop</button>
                </div>

                <div v-if="storingProgress" class="afkw-progress-container" style="margin-top: 15px;">
                    <div class="afkw-progress" :style="{width: `${storingProgress}`+'%'}"></div>
                    <div class="afkw-percentage" :style="{left: `${storingProgress}`+'%'}">{{ storingProgress }}%</div>
                </div>

                <ul class="afkw-log" v-if="logs.length > 0" v-cloak>
                    <li v-for="(log, i) in logs" :key="i" :class="[log.success == false ? 'error' : '']">
                        <div v-if="log.success == true">
                            [{{ log.created_at }}] {{ log.data }}
                        </div>
                        <div v-if="log.status == false">
                            <?php echo esc_html__("[Failed!!!] ", "auto-focus-keyword-for-seo"); ?>
                            {{ log.data }}
                        </div>
                    </li>
                </ul>
            </div>
            

        </div>

    </div>

    <div v-else>
        <div class="afkw-alert afkw-success">
            <?php echo esc_html__("Great. You've no items without focus keyword(s).", "auto-focus-keyword-for-seo"); ?>
        </div>
    </div>

</div>