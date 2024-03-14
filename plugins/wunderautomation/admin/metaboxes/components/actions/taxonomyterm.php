<div v-if="step.action.action == '\\WunderAuto\\Types\\Actions\\TaxonomyTerm'">
    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Object', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <select v-model="step.action.value.type" class="tw-w-full">
                <option v-for="item in $root.currentObjects(stepKey, ['post'])"
                        :value="item.id">
                    {{ item.id }}
                </option>
            </select>
            <br>
            <i>
                <?php _e(
                    'The workflow object to add / change custom field on',
                    'wunderauto'
                );?>
            </i>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Add or remove', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <select v-model="step.action.value.action" class="tw-w-full">
                <option value="add"><?php _e('Add', 'wunderauto')?> </option>
                <option value="remove"><?php _e('Remove', 'wunderauto')?> </option>
            </select>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Taxonomy', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <select v-model="step.action.value.taxonomy" class="tw-w-full">
                <template v-for="item in $root.shared.taxonomies">
                    <option v-if="step.action.value.type && item.type.includes(step.action.value.type)"
                            :value="item.value">
                        {{ item.label }}
                    </option>
                </template>
            </select>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Term(s)', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <multiselect multiple v-model="step.action.value.term"
                         mode="tags"
                         :delay="20"
                         :searchable="true"
                         :createTag="true"
                         :object="true"
                         ajaxAction="wa_search_terms"
                         nonceName="search_tax_nonce"
                         :term2="step.action.value.taxonomy"
                         :options="async function(query) {
                            return await ajaxMultiSelectSearch(query, this);
                         }">
            </multiselect>
        </div>
    </div>

    <div class="tw-flex tw-mt-2 td-flex-row">
        <div class="tw-w-28"><?php _e('Remove existing', 'wunderauto'); ?></div>
        <div class="tw-w-full">
            <input type="checkbox"
                   v-model="step.action.value.removeExisting"
                   :disabled="step.action.value.action!='add'">
            <br>
            <i>
                <?php
                _e(
                    'Check this box if you want to replace all existing terms with the selected ones',
                    'wunderauto'
                );
                ?>
            </i>
        </div>
    </div>
</div>






