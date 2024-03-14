<div id="compare-component" class="full-container">
    <div v-bind:class="{ thwwac_overlay : isActive  }">
    <div class="thwwac-main-container" v-cloak>
    <div id="thwwac-header">
        <h1><?php esc_html_e('Settings', 'wishlist-and-compare'); ?></h1>
        <a href="<?php echo esc_url(admin_url('admin.php?page=th_wishlist_settings'))?>" class="thwwac-page-link"><?php esc_html_e('Wishlist', 'wishlist-and-compare'); ?></a>
        <div class="th-vertical"></div>
        <a href="<?php echo esc_url(admin_url('admin.php?page=th_compare_settings'))?>" class="thwwac-page-link"><?php esc_html_e('Comparison', 'wishlist-and-compare'); ?></a>
    </div>
    <div class="thwwac-main-page">
        <ul>
            <li v-for="(tab,index) in tabs" v-bind:style="{background: (opacity? 'rgba(130, 134, 131, 0.3)' : 'white')}">
                <div v-on:click="slide(tab)" class="tab-head">
                    <img v-bind:src="tab.icon" class="thwwac-icon">
                    <h3>{{ tab.name }}</h3>
                    <p class="tab-content">{{ tab.content }}</p>
                    <a class="thwwac-link">{{ tab.link }}</a>
                </div>
            </li>
        </ul>
        <div v-for="tab in tabs">
            <div v-bind:class="[tab.active ? 'slidetoleft' : 'slidetoright']">

                <div v-for="data in tab.settings">
                    <div class="slide-heading"><h2>{{ data.name }}</h2>
                    <span class="close-btn" v-on:click="cancel()">&times;</span>
                    </div>

                    <div v-if="data.click_open=='General Settings'">
                        <!-- <div v-html="setting_success"></div> -->
                        <div id="resp-table">
                            <div id="resp-table-body">
                                <form method="post" action="" @submit="submit" ref="formHTML"><?php
                                if (function_exists('wp_nonce_field')) {
                                    wp_nonce_field('save_compare', 'thwwac_compare_security'); 
                                } ?>
                                    <component v-for="field in data.fields" :key="field.index" :is="field.type" v-bind="field" v-bind:value="field.field_value" v-on:change="change(field)" v-if="field.dependant">
                                    </component>

                                    <div class="btn-fixed">
                                        <input type="submit" name="settings_save" class="wish-btn" value="<?php esc_attr_e('Save', 'wishlist-and-compare'); ?>">
                                        <input type="button" v-on:click="reset('compare_settings')" class="wish-cancel" value="<?php esc_attr_e('Reset to default', 'wishlist-and-compare'); ?>">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div v-else-if="data.click_open=='Table Settings'">
                        <!-- <div v-html="spagesuccess"></div> -->
                        <div id="resp-table">
                            <div id="resp-table-body">
                                <form method="post" action="" @submit="tablesubmit" ref="formHTML" enctype="multipart/form-data"><?php
                                    if (function_exists('wp_nonce_field')) {
                                        wp_nonce_field('save_table', 'thwwac_table_security'); 
                                    } ?>
                                    <component v-for="field in data.fields" :key="field.index" :is="field.type" v-bind="field" v-on:change="change(field)" v-if="field.dependant">
                                    </component>
                                    <hr>
                                    <div class="thwwac-drag">
                                        <p><?php esc_html_e('Fields to be displayed (Drag & drop the fields to rearrange)', 'wishlist-and-compare'); ?></p>
                                        <draggable v-model="data.drag_fields" :slide="onmove()">
                                            <component v-for="field in data.drag_fields" :key="field.index" :is="field.type" v-bind="field" v-on:change="change(field)" v-if="field.dependant">
                                            </component>
                                        </draggable>
                                    </div>
                                    <div class="btn-fixed">
                                        <input type="submit" name="settings_save" value="<?php esc_attr_e('Save', 'wishlist-and-compare'); ?>">
                                        <input type="button" v-on:click="reset('compare_table_settings')" class="wish-cancel" value="<?php esc_attr_e('Reset to default', 'wishlist-and-compare'); ?>">
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <div v-show="showsuccess" class="success-msg thwwc-save-success">
        <span class="thwwc-tick-icon">&#10003;</span>
        <p><?php esc_html_e('Saved successfully', 'wishlist-and-compare'); ?></p><span class="success-close-btn" v-on:click="successclose()">&times;</span>
    </div>
    <div v-show="resetsuccess" class="success-msg thwwc-save-success">
        <span class="thwwc-tick-icon">&#10003;</span>
        <p><?php esc_html_e('Settings successfully reset', 'wishlist-and-compare'); ?></p><span class="success-close-btn" v-on:click="resetclose()">&times;</span>
    </div>
    <div v-show="none" class="success-msg thwwc-save-fail">
        <p><?php esc_html_e('Your changes were not saved due to an error (or you made none!)', 'wishlist-and-compare'); ?></p><span class="success-close-btn" v-on:click="resetclose()">&times;</span>
    </div>
    <?php 
    $spinner_image = THWWC_URL.'/assets/libs/gif/spinner.gif' ?>
    <div class="thwwac-loader" v-if="loading">
        <img src="<?php echo esc_url($spinner_image) ?>">
    </div>
</div>
</div>
</div>