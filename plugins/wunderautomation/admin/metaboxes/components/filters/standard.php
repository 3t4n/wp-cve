<span v-if="selectedFilter.inputType == 'scalar' && filter.compare != 'empty' && filter.compare != 'nempty'"
      class="wa-sel-filter-value">
    <div v-if="Array.isArray(filter.value)" :set="delete filter.value"></div>
    <input type="text" class="tw-w-full"
           v-model="filter.value"
           :type="selectedFilter.valueType">
</span>

<span v-if="selectedFilter.inputType == 'select'" class="wa-sel-filter-value">
    <select v-model="filter.value"
            class="tw-w-full">
        <option value="">[<?php _e('Select', 'wunderauto')?>...]</option>
        <template v-for="(compareItem, compareKey) in selectedFilter.compareValues">
            <option :value="compareItem.value">
                {{ compareItem.label }}
            </option>
        </template>
    </select>
</span>

<div v-if="selectedFilter.inputType == 'multiselect'"
     class="tw-w-full">

    <multiselect v-model="filter.arrValue"
                 mode="tags"
                 :object="true"
                 :searchable="true"
                 :createTag="true"
                 :options="selectedFilter.compareValues">
    </multiselect>
</div>

