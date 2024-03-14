<?php
$utm = '?utm_source=dashboard&utm_medium=workfloweditor&utm_campaign=installed_users';
?>
<div id="v-wunderauto-parameter" style="display: none">
    <div class="wunderauto-parameter" :refreshCount="sharedState.refreshCount">
        <div v-for="(group, groupkey) in atts.groups">
            <p>
                <span v-for="(item) in group">
                    <span v-if="objectsEnabled(atts.parameters[item.class].objects)"
                          class="parameter-pill" v-on:click="setActiveParameter(item)">
                        {{ item.title.replace(/\-/g, '\u2011') }}
                    </span>
                </span>
            </p>
        </div>
        <br>
        <a href="<?php esc_url(wa_make_link('/docs/parameters/', $utm))?>"
           target="_blank">
            <?php _e('Parameters documentation', 'wunderauto');?>
        </a>

        <wunderauto-modal v-if="showModal" @close="close">
            <h3 slot="header">{{ parameter }}</h3>
            <div slot="description">
                {{ atts.parameters[parameterClass].description }}
                <p v-if="atts.parameters[parameterClass].usesDateFormat || parameterTeatAs=='date'">
                    <?php _e('To read more about how format and modify date and time parameters,', 'wunderauto');?>
                        <a href="<?php esc_url(wa_make_link('/docs/working-with-date-parameters/', $utm))?>"
                           target="_blank"><?php _e('Click here', 'wunderauto');?></a>
                </p>
            </div>
            <div slot="body">
                <table border="0" class="parameter-input-table">
                    <tr v-if="atts.parameters[parameterClass].usesFieldName">
                        <td width="165">
                            <label for="format">{{ atts.parameters[parameterClass].customFieldNameCaption }}</label>
                        </td>
                        <td>
                            <input type="text" class="wunderAutoParamField" v-model="parameterFieldName">
                            <i>{{ atts.parameters[parameterClass].customFieldNameDesc }}</i>
                        </td>
                    </tr>
                    <tr v-if="atts.parameters[parameterClass].usesName">
                        <td width="165">
                            <label for="format">{{ atts.parameters[parameterClass].customFieldNameCaption }}</label>
                        </td>
                        <td>
                            <input type="text" class="wunderAutoParamField" v-model="parameterOptionName">
                            <i>{{ atts.parameters[parameterClass].customFieldNameDesc }}</i>
                        </td>
                    </tr>
                    <tr v-if="atts.parameters[parameterClass].usesObjectPath">
                        <td width="165">
                            <label for="format"><?php _e('Object path', 'wunderauto');?></label>
                        </td>
                        <td>
                            <input type="text" class="wunderAutoParamField" v-model="parameterObjectPath">
                            <i><?php
                                _e(
                                    'Optional. If the returned field is an array or object, use JSONPath notation to ' .
                                    'select the element or subfield',
                                    'wunderauto'
                                );
                                ?>
                            </i>
                        </td>
                    </tr>
                    <tr v-if="atts.parameters[parameterClass].usesAcfFieldName">
                        <td width="165">
                            <label for="format">Advanced Custom Field</label>
                        </td>
                        <td>
                            <select v-model="parameterAcfFieldKey" style="font-size: 13px;">
                                <optgroup v-for="(group, groupKey) in sharedState.acfFields" :label="groupKey">
                                    <option v-for="option in group" :value="option.code">
                                        {{ option.label }}
                                    </option>
                                </optgroup>
                            </select>
                        </td>
                    </tr>
                    <tr v-if="atts.parameters[parameterClass].usesReturnAs">
                        <td width="165">
                            <label for="default">Return field as</label>
                        </td>
                        <td>
                            <select class="wunderAutoParamField" v-model="parameterReturnAs">
                                <option value=""><?php _e('Value (default)', 'wunderauto')?></option>
                                <option value="label"><?php _e('Label', 'wunderauto')?></option>
                            </select><br>
                            <i>
                                <?php
                                _e(
                                    'By default, parameters will return its internal value. Some parameters can ' .
                                    'also have a label, or "human readable" representation. I.e the post status ' .
                                    '"publish" is almost always written using the label "Published". ',
                                    'wunderauto'
                                );
                                ?>
                            </i>
                        </td>
                    </tr>
                    <tr v-if="atts.parameters[parameterClass].usesTreatAsType">
                        <td width="165">
                            <label for="default">Treat field as</label>
                        </td>
                        <td>
                            <select class="wunderAutoParamField" v-model="parameterTeatAs">
                                <option value=""><?php _e('Text (default)', 'wunderauto')?></option>
                                <option value="date"><?php _e('Date', 'wunderauto')?></option>
                                <option value="phone"><?php _e('Phone number', 'wunderauto')?></option>
                            </select><br>
                            <i><?php _e('Enable formatting rules for some value types', 'wunderauto');?></i>
                        </td>
                    </tr>
                    <tr v-if="atts.parameters[parameterClass].usesDateFormat || parameterTeatAs=='date'">
                        <td width="165">
                            <label for="format">Format</label>
                        </td>
                        <td>
                            <input type="text" class="wunderAutoParamField" v-model="parameterFormat">
                            <i><?php
                                _e(
                                    'Formats the date using PHP date() function (I.e Y-m-d H:i:s). If left blank, ' .
                                    'default to using WunderAutomation standard date time format',
                                    'wunderauto'
                                );
                                ?>
                            </i>
                        </td>
                    </tr>
                    <tr v-if="atts.parameters[parameterClass].usesDateFormat || parameterTeatAs=='date'">
                        <td width="165">
                            <label for="format">Add or subtract</label>
                        </td>
                        <td>
                            <input type="text" class="wunderAutoParamField" v-model="parameterDateAdd">
                            <i><?php
                                _e(
                                    'Add or subtract time from the returned date. Uses PHP strtotime() modifiers. ',
                                    'wunderauto'
                                );
                                ?>
                            </i>
                        </td>
                    </tr>
                    <tr v-if="atts.parameters[parameterClass].usesPhoneFormat  || parameterTeatAs=='phone'">
                        <td width="165">
                            <label for="format">Format</label>
                        </td>
                        <td>
                            <select class="wunderAutoParamField" v-model="parameterPhoneFormat">
                                <option value=""><?php _e('No formatting', 'wunderauto');?></option>
                                <option value="e.164"><?php _e('E.164 (API usage)', 'wunderauto');?></option>
                            </select>
                            <br>
                            <i><?php
                                _e(
                                    'Optionally formats the phone number in E.164 format for sending. SMS etc. If no ' .
                                    'country code is typed in by customer, E.164 formatting will use ' .
                                    'country code from customer billing country or WooCommerce shop address',
                                    'wunderauto'
                                );
                                ?>
                            </i>
                        </td>
                    </tr>
                    <tr v-if="atts.parameters[parameterClass].usesOutputFormat">
                        <td width="165">
                            <label for="format">Output format</label>
                        </td>
                        <td>
                            <select class="wunderAutoParamField" v-model="parameterPhoneFormat">
                                <option v-for="(format, key) in atts.parameters[parameterClass].outputFormats"
                                        :value="key">
                                    {{ format }}
                                </option>
                            </select>
                        </td>
                    </tr>
                    <tr v-if="atts.parameters[parameterClass].usesDefault">
                        <td width="165">
                            <label for="default">Default value</label>
                        </td>
                        <td>
                            <input type="text" class="wunderAutoParamField" v-model="parameterDefault">
                            <i><?php _e('Fallback value when no value is found', 'wunderauto');?></i>
                        </td>
                    </tr>
                    <tr>
                        <td width="165">
                            <label for="default">URL Encode</label>
                        </td>
                        <td>
                            <input type="checkbox" class="wunderAutoParamField" style="width: 10px;"
                                   v-model="parameterUrlEncode">
                            <br>
                            <i><?php
                                _e(
                                    'URL Encode the return value, sometimes needed for some API usage',
                                    'wunderauto'
                                );
                                ?>
                            </i>
                        </td>
                    </tr>
                </table>


                <div slot="result" class="parameter-input-result">
                    <span id="parameter-input-copy">{{ parameterResult }}</span>
                </div>

            </div>
            <div slot="footer"></div>

        </wunderauto-modal>
    </div>
</div>

