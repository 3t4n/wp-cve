<?php
$utm = '?utm_source=dashboard&utm_medium=workfloweditor&utm_campaign=installed_users';
?>

<div id="v-wunderauto-filter" style="display: none" >
    <div class="wunderauto-filter">

        <div v-if="filters.length == 0">
            <p>
                <?php _e('Create one or more filters to add conditional logic to the Workflow. ' .
                    'All filters in a group must pass for the group to pass, they are joined with logical AND. At ' .
                    'least one group needs to pass, groups are joined with a logical OR', 'wunderauto');?>
            </p>
            <p>
                <?php
                _e('Click <b>Add filter group</b> to create the first filter in the first group.', 'wunderauto');
                ?>
            </p>
        </div>

        <div class="tw-flex tw-flex-col" v-for="(group, groupKey) in filters">

            <template v-for="(filter, filterKey) in group"
                      v-if="filter.filter && typeof atts.filters[filter.filter] == 'undefined'">
                <div class="error">
                    <p>
                        Warning! This workflow is trying to use the filter <b>{{ filter.filter }}</b>
                        but it does not exist. Filter evaluation for this Workflow will always return
                        false and no actions will be executed. Probable causes:
                    </p>
                    <ol>
                        <li>
                            This filter was defined in an older version of WunderAutomation but have
                            since changed name / class. Please check the documentation.
                        </li>
                        <li>
                            You have deactivated a WunderAutomation plugin or function that provided this filter
                        </li>
                        <li>
                            You have deactivated a plugin that was required for this filter to work.
                            I.e. WooCommerce or Advanced Custom Fields.
                        </li>
                    </ol>

                    <p>
                        <button class="button button-primary button-small"
                                v-on:click.prevent="removeFilter(groupKey, filterKey)">
                            Remove this filter
                        </button>
                    </p>
                    <p>
                        &nbsp;
                    </p>
                </div>
            </template>

            <div class="wa-filter tw-mb-0" v-for="(filter, filterKey) in group"
                 v-if="filter.filter && typeof atts.filters[filter.filter] !== 'undefined'">

                <div v-if="filterKey > 0"
                     class="tw-mb-6 wa-OR-separator" style="font-size: 13px;">- <?php _e('AND', 'wunderauto')?> -
                </div>

                <div class="wa-filter-row tw-flex tw-flex-row">
                     <div class="wa-filter-inputs tw-flex tw-flex-col tw-flex-grow xl:tw-flex-row">
                        <div class="tw-flex tw-flex-col lg:tw-flex-row">
                            <div>
                                <select v-model="filter.filter"
                                        @change="filterChange(stepKey, groupKey, filterKey)"
                                        class="wa-sel-filter-name">
                                    <option value=""><?php _e('[Select filter...]', 'wunderauto')?></option>
                                    <optgroup v-for="(group, groupKey) in atts.groups" :label="groupKey">
                                        <option v-for="(item) in group"
                                                :value="item.class" :selected="item.class == filter.filter"
                                                :disabled="!objectsEnabled(atts.filters[item.class].objects)">
                                            {{item.title}}
                                        </option>
                                    </optgroup>
                                </select>
                                <div v-if="filter.filter && atts.filters[filter.filter].usesCustomField"
                                     style="margin-top: 5px;">
                                    <input v-model="filter.field"
                                           :placeholder="atts.filters[filter.filter].customFieldPlaceholder" size="30"/>
                                </div>
                                <div v-if="filter.filter && atts.filters[filter.filter].usesObjectPath"
                                     style="margin-top: 5px;">
                                    <input v-model="filter.path"
                                           placeholder="Object path" size="30"/>
                                </div>
                            </div>
                            <div class="wa-filter-operators lg:tw-ml-1">
                                <select v-if="atts.filters[filter.filter].operators"
                                        v-model="filter.compare">
                                    <option value=""><?php _e('[Select operator...]', 'wunderauto')?></option>
                                    <option v-for="(item, key) in atts.filters[filter.filter].operators"
                                            :value="key" :selected="key == filter.compare">
                                        {{ item }}
                                    </option>
                                </select>
                                <select disabled v-else></select>
                            </div> <!-- end wa-filter-operators -->
                        </div>

                        <div class="wa-filter-values tw-flex-grow lg:tw-ml-1">
                            <div v-if="filter.filter">
                                <?php do_action('wunderauto_filter_fields') ?>
                            </div>
                        </div> <!-- end wa-filter-values -->
                    </div> <!-- end wa-filter-inputs -->

                    <div class="wa-filter-buttons xl:tw-ml-2" style="min-width: 52px;">
                        <button class="wunder-small-button" v-on:click.prevent="addFilter(groupKey, filterKey)">
                            +
                        </button>
                        <button class="wunder-small-button" v-on:click.prevent="removeFilter(groupKey, filterKey)">
                            -
                        </button>
                    </div> <!-- end wa-filter-buttons -->
                </div> <!-- end wa-filter-row -->
                <div v-if="filter.filter && delayed" style="margin-top: 5px;">
                    Re-evaluate filter at delayed run
                    <input v-model="filter.evaluate" type="checkbox" value="both"/>
                </div>
            </div> <!-- end wa-filter -->

            <div v-if="groupKey +1 < filters.length"
                 class="wa-OR-separator tw-mb-6"> - <?php _e('OR', 'wunderauto');?> - </div>

        </div>

        <a href="<?php echo esc_url(wa_make_link('/docs/filters/', $utm));?>"
            target="_blank">
            <?php _e('Filters documentation', 'wunderauto');?>
        </a>
    </div>
</div>

