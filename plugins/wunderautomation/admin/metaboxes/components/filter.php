<div class="list-item tw-p-2 tw-flex tw-flex-col tw-border-2 md:tw-flex-col">
    <template v-for="(group, groupKey) in currentFilters" :key="groupKey">
        <div>
            <div v-for="(filter, filterKey) in group.filters" class="tw-mb-0" :set="selectedFilter=false">
                <div v-if="filterKey > 0"
                    class="tw-my-1 tw-text-center tw-text-sm">- <?php _e('AND', 'wunderauto')?> -
                </div>

                <div class="wa-filter-row tw-flex tw-flex-row">
                    <div class="wa-filter-inputs tw-flex tw-flex-col tw-flex-grow xl:tw-flex-row">
                        <div class="tw-flex tw-flex-col lg:tw-flex-row">
                            <div>
                                <select v-model="filter.filterKey"
                                        @beforechange="filterChange(stepKey, groupKey, filterKey)"
                                        class="wa-sel-filter-name">
                                    <option value="">[<?php _e('Select filter', 'wunderauto')?>...]</option>
                                    <optgroup v-for="(object, objectName) in $root.currentObjects(stepKey)"
                                              :label="object.id">
                                        <option v-for="(filterItem, filterClass)
                                                        in filtersForObjectIds(object.id, object.type)"
                                                :value="filterItem.objectFilterKey">
                                            {{ filterItem.title }} [{{ object.id }}]
                                        </option>
                                    </optgroup>
                                </select>
                                <div v-if="filter.filterKey"
                                     :set="selectedFilter=filters[filterClass(stepKey, groupKey, filterKey)]"
                                     class="tw-hidden"
                                ></div>
                                <div v-if="selectedFilter && selectedFilter.usesCustomField"
                                     style="margin-top: 5px;">
                                    <input v-model="filter.field"
                                           :placeholder="selectedFilter.customFieldPlaceholder"
                                           size="30"/>
                                </div>
                                <div v-if="selectedFilter && selectedFilter.usesAdvancedCustomField"
                                     style="margin-top: 5px;">
                                    <select v-model="filter.field">
                                        <option>Select a field</option>
                                        <optgroup v-for="[acfGroupKey, acfGroup]
                                                        in Object.entries($root.shared.acfFields)"
                                                  :label="acfGroupKey">
                                            <option v-for="acfItem in acfGroup"
                                                    :value="acfItem.value">
                                                {{ acfItem.label }}
                                            </option>
                                        </optgroup>
                                    </select>
                                </div>
                                <div v-if="selectedFilter && selectedFilter.usesObjectPath"
                                     style="margin-top: 5px;">
                                    <input v-model="filter.path"
                                           placeholder="Object path" size="30"/>
                                </div>
                            </div>
                            <div class="wa-filter-operators lg:tw-ml-1">
                                <select v-if="selectedFilter && Object.keys(selectedFilter.operators).length > 0"
                                        v-model="filter.compare">
                                    <option value="">[<?php _e('Select operator', 'wunderauto') ?>...]</option>
                                    <option v-for="[operatorKey, operatorItem]
                                                    in Object.entries(selectedFilter.operators)"
                                            :value="operatorKey"
                                            :selected="operatorKey == filter.compare"
                                    >
                                        {{ operatorItem }}
                                    </option>
                                </select>
                            </div> <!-- end wa-filter-operators -->
                        </div>
                        <div class="wa-filter-values tw-flex-grow lg:tw-ml-1">
                            <div v-if="selectedFilter">
                                <?php do_action('wunderauto_filter_fields') ?>
                            </div>
                        </div> <!-- end wa-filter-values -->
                    </div> <!-- end wa-filter-inputs -->

                    <div class="wa-filter-buttons xl:tw-ml-2" style="min-width: 58px;">
                        <button class="wunder-small-button"
                                @click.prevent="addFilter(stepKey, groupKey, filterKey)">
                            +
                        </button>
                        <button class="wunder-small-button"
                                @click.prevent="removeFilter(stepKey, groupKey, filterKey)">
                            -
                        </button>
                        <div :set="selectedFilter=null" class="tw-hidden"></div>
                    </div> <!-- end wa-filter-buttons -->
                </div> <!-- end wa-filter-row -->
            </div> <!-- end wa-filter -->

            <div v-if="groupKey +1 < currentFilters.length"
                 class="tw-my-2 tw-text-center tw-text-base">
                <hr>
                - <?php _e('OR', 'wunderauto'); ?> -
            </div>
        </template>
    </div>

    <div class="tw-flex tw-mt-4 tw-p-2">
        <button @click.prevent="addFilterGroup(stepKey)">
            <?php _e('Add new filter group', 'wunderatuto') ?>
        </button>
    </div>
</div>
