<span v-if="selectedFilter.inputType == 'between'"
      class="wa-sel-filter-value">
    <div v-if="Array.isArray(filter.value)" :set="delete filter.value"></div>
    <input type="text" class="tw-w-32"
           v-model="filter.value"
           :type="selectedFilter.valueType">

    <?php _e('and', 'wunderauto');?>

    <input type="text" class="tw-w-32"
           v-model="filter.value2"
           :type="selectedFilter.valueType">
</span>