<template v-if="selectedFilter.inputType == 'ajaxmultiselect'">
    <div v-if="!Array.isArray(filter.value)" :set="delete filter.value"></div>
    <div class="wa-vsel wa-sel-filter-value">
        <multiselect multiple v-model="filter.arrValue"
                     mode="tags"
                     :delay="20"
                     :searchable="true"
                     :createTag="true"
                     :object="true"
                     :placeholder="selectedFilter.placeholder"
                     :ajaxAction="selectedFilter.ajaxAction"
                     :nonceName="selectedFilter.nonceName"
                     :options="async function(query) {
                        return await ajaxMultiSelectSearch(query, this);
                     }">
        </multiselect>
    </div>
    <br>
</template>