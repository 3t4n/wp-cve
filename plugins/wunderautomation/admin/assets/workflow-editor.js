/**
 * clipboardCopy
 *
 * @param text
 * @returns {Promise<void>|any}
 */
function clipboardCopy (text) {
    if (navigator.clipboard) {
        return navigator.clipboard.writeText(text).catch(function (err) {
            throw (err !== undefined ? err : new DOMException('The request is not allowed', 'NotAllowedError'))
        })
    }

    // Put the text to copy into a <span>
    var span = document.createElement('span');
    span.textContent = text;

    // Preserve consecutive spaces and newlines
    span.style.whiteSpace = 'pre';

    // Add the <span> to the page
    document.body.appendChild(span);

    // Make a selection object representing the range of text selected by the user
    var selection = window.getSelection();
    var range = window.document.createRange();
    selection.removeAllRanges();
    range.selectNode(span);
    selection.addRange(range);

    // Copy text to the clipboard
    var success = false;
    try {
        success = window.document.execCommand('copy');
    } catch (err) {
        console.log('error', err);
    }

    // Cleanup
    selection.removeAllRanges();
    window.document.body.removeChild(span);

    return success
        ? Promise.resolve()
        : Promise.reject(new DOMException('The request is not allowed', 'NotAllowedError'))
}

var currentElement = null;

function getCurrentElement() {
    return currentElement;
}

// We still ned jQuery for some odd stuff
(function ($) {
    $('body').on('focus', '.wunderauto-action-fields input, .wunderauto-action-fields textarea', function (e) {
        currentElement = document.activeElement;
    });
})( jQuery );

var parametersComponent = {
    template: document.querySelector('#parameters-component').innerHTML,
    props: ['stepkey' ],
    data: function data() {
        var element = document.querySelector('#parameters-data');
        var  initialState = JSON.parse(element.textContent);

        parameters = {
            // properites:
            parameters: initialState.parameters,
            editor: {
                visible: false,
                phpClass:   null,
                result:  '',
                values:  editorValues,
                config: editorConfig,
            },
            selectedTab: false,
            selectedGroup: false,
            stepKey: this.stepkey,
        };
        return parameters;
    },

    updated: function updated() {
        this.stepKey = this.stepkey;
    },

    computed: {
        editorResult: function editorResult() {
            var parameter = this.parameterTag;
            var modifiers = [];
            for ([key, value] of Object.entries(this.editor.values)) {
                if (key === 'counter') { continue; }
                if (this.editor.values[key]) {
                    var variable = this.editor.config[key].variable;
                    modifiers.push(variable + ": '" + value + "'");
                }
            }
            var result = '{{ ' + parameter;
            if (modifiers.length > 0) {
                result = result + ' | ' + modifiers.join(' , ');
            }
            result = result + ' }}';
            this.editor.result = result;
            return result;
        },
        parameterTag: function parameterTag() {
            var prefix = this.editor.object === 'general' || this.editor.object.includes('general') ?
                '' :
                this.editor.object + '.';
            return prefix + this.parameters[this.editor.phpClass].title;
        }
    },

    methods: {
        openEditor: function openEditor(phpClass, objectId, proPromo ) {
            if (this.editor.phpClass !== phpClass || this.editor.object !== objectId) {
                for ([key, value] of Object.entries(this.editor.values)) {
                    if (key === 'counter') { continue; }
                    if (this.editor.values[key]) {
                        this.editor.values[key] = null;
                    }
                }
            }

            this.editor.values.counter++;
            this.editor.phpClass = phpClass;
            this.editor.object = objectId;
            this.editor.proPromo = proPromo;
            this.editor.visible = true;
        },
        closeEditor: function closeEditor(how) {
            if (how == 'copy') {
                clipboardCopy(this.editorResult);
            }
            if (how == 'insert') {
                var currentElement = getCurrentElement();
                if (currentElement) {
                    var start = currentElement.selectionStart;
                    var end = currentElement.selectionEnd;
                    var oldVal = currentElement.value;
                    currentElement.value = oldVal.slice(0, start) + this.editorResult + oldVal.slice(end);
                    currentElement.focus();
                    currentElement.selectionStart = start + this.editorResult.length;
                    currentElement.selectionEnd = currentElement.selectionStart;

                    // trigger Vue to sync up the modified content
                    var e = document.createEvent('HTMLEvents');
                    e.initEvent('input', true, true);
                    currentElement.dispatchEvent(e);
                }
            }
            this.editor.visible = false;
        },
        paramsForObjectIds: function paramsForObjectIds(objectType) {
            var params = {};
            objectType = objectType === 'general' ? '*' : objectType;
            Object.entries(parameters.parameters).forEach(function (f) {
                if (f[1].objects.includes(objectType)) {
                    var group = f[1].group;
                    if (typeof params[group] === 'undefined') {
                        params[group] = {};
                    }
                    params[group][f[0]] = f[1];
                }
            });
            return params;
        },
    },
};

var stepsComponent = {
    template: document.querySelector('#steps-component').innerHTML,
    data: function data() {
        var element = document.querySelector('#steps-data');
        var initialState = JSON.parse(element.textContent);
        steps = {
            steps: initialState.steps,
            filters: initialState.filters,
            actions: initialState.actions,
            filterGroups: initialState.filterGroups,
            actionGroups: initialState.actionGroups,
            key: 0,
            pwdField: true,
            counter: 0,
        };

        // protect against steps without key
        var keyMax = -1;
        steps.steps.forEach(function(step) {
            if (typeof step.key !== 'undefined') { keyMax = Math.max(keyMax, step.key); }
        });
        keyMax++;
        steps.steps.forEach(function(step) {
            if (typeof step.key === 'undefined') { step.key = keyMax++; }
        });
        steps.key = keyMax;

        return steps;
    },
    mixins: stepsMixins,
    mounted: function mounted () {
        this.updateProvidedObjects();
    },
    updated: function updated () {
        this.updateProvidedObjects();
    },
    methods: {
        updateProvidedObjects: function updateProvidedObjects() {
            var actions = this.actions;
            var root = this.$root;
            this.steps.forEach(function(step, stepIndex) {
                if (step.type !== 'action') {
                    root.updateObjects(stepIndex, []);
                    return;
                }
                if (step.action.action.length === 0) {
                    root.updateObjects(stepIndex, []);
                    return;
                }

                var objects = [];
                var action = actions[step.action.action];
                if (action.emittedObjects.length > 0) {
                    objects = action.emittedObjects.slice();
                }

                if (Array.isArray(step.action.value.objectRows)) {
                    step.action.value.objectRows.forEach(function(el) {
                       objects.push({id: el.name, type: el.type, source: 'action'});
                    });
                }
                root.updateObjects(stepIndex, objects);
            });
        },
        addFilter: function addFilter(stepIndex, groupIndex, filterIndex) {
            this.steps[stepIndex].filterGroups[groupIndex].filters.push(this.newFilter());
        },
        removeFilter: function removeFilter(stepIndex, groupIndex, filterIndex) {
            this.steps[stepIndex].filterGroups[groupIndex].filters.splice(filterIndex,1);
            if (this.steps[stepIndex].filterGroups[groupIndex].filters.length == 0) {
                this.steps[stepIndex].filterGroups.splice(groupIndex,1);
            }
        },
        addFilterGroup: function addFilterGroup(stepIndex) {
            this.steps[stepIndex].filterGroups.push(this.newFilterGroup());
        },
        addFilterStep: function addFilterStep() {
            this.steps.push({ key: this.key++, type: 'filters', filterGroups: [this.newFilterGroup()],});
            this.counter++;
        },
        addActionStep: function addActionStep() {
            this.steps.push({key: this.key++, type: 'action', action: this.newAction(),});
            this.counter++;
        },
        removeStep: function removeStep(stepIndex) {
            this.steps.splice(stepIndex,1);
        },
        reorderStep: function reorderStep(stepIndex, direction) {
            var t = this.steps[stepIndex];
            this.steps[stepIndex] = this.steps[stepIndex + direction];
            this.steps[stepIndex + direction] = t;
            this.updateProvidedObjects();
        },
        toggleStep: function toggleStep(stepIndex) {
            this.steps[stepIndex].minimized = !this.steps[stepIndex].minimized;
        },
        newFilterGroup: function newFilterGroup() {
            return {filters: [this.newFilter()]};
        },
        newFilter: function newFilter() {
            return {filter: '', object: '', compare: '', value: null, arrValue: null}
        },
        newAction: function newAction() {
            return {action: '', value: {}}
        },
        filterClass: function filterClass(stepIndex, groupIndex, filterIndex) {
            var selected = this.steps[stepIndex].filterGroups[groupIndex].filters[filterIndex].filterKey;
            return selected.split('::').slice(-1)[0];
        },
        filterChange: function filterChange(stepIndex, groupIndex, filterIndex) {
            var value = this.steps[stepIndex].filterGroups[groupIndex].filters[filterIndex].value;
            if (value === null) {
                return;
            }
            var filterClass = this.steps[stepIndex].filterGroups[groupIndex].filters[filterIndex].filter;
            var filter = this.filters[filterClass];
            if (this.arrayTypeInputType(filter.inputType)) {
                if (!Array.isArray(value)) {
                    this.steps[stepIndex].filterGroups[groupIndex].filters[filterIndex].value = [];
                }
            } else {
                if (Array.isArray(value)) {
                    this.steps[stepIndex].filterGroups[groupIndex].filters[filterIndex].value = null;
                }
            }
        },
        arrayTypeInputType: function arrayTypeInputType(str) {
            var arrayTypes = ['multiselect', 'ajaxmultiselect'];
            return arrayTypes.includes(str);
        },
        actionClass: function actionClass(stepIndex) {
            return this.steps[stepIndex].action.action;
        },
        stepCaption: function stepCaption(stepIndex) {
            var step = this.steps[stepIndex];
            if (step.type === 'action' && step.action.action) {
                return ': ' + steps.actions[step.action.action].title;
            }
            return '';
        },
        addActionValueRow: function addActionValueRow(stepIndex, value) {
            if ( value === void 0 ) value = {};

            if (!Array.isArray(this.steps[stepIndex].action.value.rows)) {
                this.steps[stepIndex].action.value.rows = [];
            }
            this.steps[stepIndex].action.value.rows.push(value);
        },
        removeActionValueRow: function removeActionValueRow(stepIndex, rowIndex) {
            this.steps[stepIndex].action.value.rows.splice(rowIndex, 1);
        },
        addActionValueObjectRow: function addActionValueObjectRow(stepIndex, value) {
            if (!Array.isArray(this.steps[stepIndex].action.value.objectRows)) {
                this.steps[stepIndex].action.value.objectRows = [];
            }
            this.steps[stepIndex].action.value.objectRows.push(value);
            this.updateProvidedObjects();
        },
        removeActionValueObjectRow: function removeActionValueObjectRow(stepIndex, rowIndex) {
            this.steps[stepIndex].action.value.objectRows.splice(rowIndex, 1);
            this.updateProvidedObjects();
        },
        filtersForObjectIds: function filtersForObjectIds(objectId, objectType) {
            var filters = {};
            Object.entries(steps.filters).forEach(function (f) {
                if (f[1].objects.includes(objectType)) {
                    f[1].objectFilterKey = objectId + '::' + f[0];
                    filters[f[0]] = f[1];
                }
            });
            return filters;
        },
        ajaxMultiSelectSearch: function ajaxMultiSelectSearch(search, vueEl) {
            if (search === null || search === '') {
                return [];
            }

            var ajaxAction  = vueEl._object.ajaxaction;
            var nonceName   = vueEl._object.noncename;
            var term2       = vueEl._object.term2;
            var term3       = vueEl._object.term3;
            var nonce       = WunderAutoData[nonceName];

            var ret = [];

            var params = new URLSearchParams();
            params.append('action', ajaxAction);
            params.append('security', nonce);
            params.append('term', search);
            params.append('term2', term2);
            params.append('term3', term3);
            return axios.post(ajaxurl, params)
            .then(function (response) {
                for (var [key, value] of Object.entries(response.data)) {
                    console.log({value: key, label: value});
                    ret.push({value: key, label: value});
                }
                return ret;
            })
            .catch(function (error) {
                console.log(error);
                return ret;
            });
        },
        handleClick: function handleClick(func, p1, p2) {
            var fn = window[func];
            if (fn) {
                return fn(p1, p2);
            }
        },
    }
};

var triggersComponent = {
    template: document.querySelector('#triggers-component').innerHTML,
    data: function data() {
        var element = document.querySelector('#trigger-data');
        var  initialState = JSON.parse(element.textContent);
        trigger = {
            trigger: initialState.trigger,
            triggers: initialState.triggers,
            groups: initialState.groups,
            pwdField: true,
            lastTrigger: null,
        };
        return trigger;
    },
    mixins: triggerMixins,
    mounted: function mounted () {
        this.lastTrigger = this.trigger.trigger;
        this.updateProvidedObjects();
    },
    beforeUpdate: function beforeUpdate () {
        this.updateProvidedObjects();
    },
    methods: {
        updateProvidedObjects: function updateProvidedObjects() {
            if (!this.trigger.trigger) {
                return;
            }

            if (this.lastTrigger !== this.trigger.trigger) {
                this.trigger.value = trigger.triggers[this.trigger.trigger].defaultValue;
            }
            this.lastTrigger = this.trigger.trigger;

            var providedObjects = this.triggers[this.trigger.trigger].providedObjects.slice();
            if (Array.isArray(this.trigger.value.objects)) {
                this.trigger.value.objects.forEach(function(object) {
                    if (object.type) {
                        object.name = !object.name ? object.type : object.name;
                        providedObjects.push({id: object.name, type: object.type });
                    }
                });
            }
            this.$root.updateObjects(-1, providedObjects);
        },
        addTriggerValueObject: function addTriggerValueObject(value) {
            if ( value === void 0 ) value = {};

            if (!Array.isArray(this.trigger.value.objects)) {
                this.trigger.value.objects = [];
            }
            this.trigger.value.objects.push(value);
        },
        removeTriggerValueObject: function removeTriggerValueObject(rowIndex) {
            this.trigger.value.objects.splice(rowIndex, 1);
        },
    }
};

var scheduleComponent = {
    template: document.querySelector('#schedule-component').innerHTML,

    data: function data() {
        var element = document.querySelector('#schedule-data');
        var initialState = JSON.parse(element.textContent);
        schedule = {
            schedule: initialState.schedule,
        };
        return schedule;
    },
    mounted: function mounted () {
        this.$root.delayed = this.delayed;
    },
    updated: function updated () {
        this.$root.delayed = this.delayed;
        if (this.schedule.when == 'delayed' && !this.schedule.delayFor) {
            this.schedule.delayFor = 12;
            this.schedule.delayTimeUnit = 'hours';
        }
    },
    computed: {
        delayed: function delayed() {
            var delayed = this.schedule.when !== 'direct';
            this.$root.delayed = delayed;
            return delayed;
        }
    }
};

var optionsComponent = {
    template: document.querySelector('#options-component').innerHTML,

    data: function data() {
        var element = document.querySelector('#options-data');
        var initialState = JSON.parse(element.textContent);
        options = {
            options: initialState.options,
        };
        return options;
    },
};

var workflowEditorApp = Vue.createApp({
    data: function data() {
        var element = document.querySelector('#shared-data');
        var shared = JSON.parse(element.textContent);
        return {
            objects: [],
            delayed: false,
            formData: '',
            shared: shared,
        }
    },
    mixins: appMixins,
    methods: {
        save: function save() {
            this.formData = JSON.stringify({
                trigger: this.$refs.trigger.$data.trigger,
                steps: this.$refs.steps.$data.steps,
                schedule: this.$refs.schedule.$data.schedule,
                options: this.$refs.options.$data.options,
                version: WunderAutoData.workflowVersion
            });
        },
        updateObjects: function updateObjects(currentStep, objects) {
            if (currentStep == -1 && trigger.trigger.trigger) {
                objects.push({ id: 'currentuser', type: 'user', description: 'The currently logged in user (if any)' });
                objects.push({ id: 'general', type: '*', description: 'General WordPress variables' });
            }
            this.objects[currentStep + 1] = objects;
        },
        currentObjects: function currentObjects(currentStep, type, exclude) {
            if ( type === void 0 ) type = [];
            if ( exclude === void 0 ) exclude = [];

            var unique = {};
            this.objects.forEach(function (stepObjects, stepKey) {
                if (currentStep < (stepKey -1)) {
                    return;
                }

                stepObjects.forEach(function (stepObject) {
                    if (exclude.includes(stepObject.id)) { return; }
                    if (type.length > 0 && !type.includes(stepObject.type)) { return; }
                    unique[stepObject.id] = stepObject;
                });
            });
            return unique;
        },
    },
});

// Add some properties to VueformMultiselect
VueformMultiselect.props.ajaxaction = {type: String, required: false, default: ''};
VueformMultiselect.props.noncename = {type: String, required: false, default: ''};
VueformMultiselect.props.term2 = {type: String, required: false, default: ''};
VueformMultiselect.props.term3 = {type: String, required: false, default: ''};

workflowEditorApp.component('triggers', triggersComponent);
workflowEditorApp.component('parameters', parametersComponent);
workflowEditorApp.component('schedule', scheduleComponent);
workflowEditorApp.component('options', optionsComponent);
workflowEditorApp.component('steps', stepsComponent);
workflowEditorApp.component('multiselect', VueformMultiselect);
workflowEditorApp.mount('#workflow-editor-app');
