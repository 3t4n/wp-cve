var retriggerQueryComponent = {
    template: document.querySelector('#retriggerquery-component').innerHTML,

    data: function data() {
        var element = document.querySelector('#retriggerquery-query-data');
        var initialState = JSON.parse(element.textContent);
        return {
            query: initialState.query,
            triggers: initialState.triggers,
        };
    },
    mounted: function mounted () {
        this.updateProvidedObjects();
    },
    updated: function updated () {
        this.updateProvidedObjects();
    },
    methods: {
        updateProvidedObjects: function updateProvidedObjects() {
            if (!this.query.objectType || this.query.objectType === null) {
                return;
            }

            var providedObjects = this.triggers[this.query.objectType].providedObjects.slice();
            this.$root.updateObjects(-1, providedObjects);
        },
    },
};

var reTriggerScheduleComponent = {
    template: document.querySelector('#retriggerschedule-component').innerHTML,

    data: function data() {
        var element = document.querySelector('#retriggerquery-schedule-data');
        var initialState = JSON.parse(element.textContent);
        return {
            schedule: initialState.schedule,
        };
    },
};

/**
 * clipboardCopy
 *
 * @param text
 * @returns {Promise<void>|any}
 */

// We still ned jQuery for some odd stuff
(function ($) {
    $('body').on('focus', '.wunderauto-action-fields input, .wunderauto-action-fields textarea', function (e) {
    });
})( jQuery );

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

var reTriggerEditorApp = Vue.createApp({
    data: function data() {
        var element = document.querySelector('#shared-data');
        var shared = JSON.parse(element.textContent);
        return {
            objects: [],
            formData: '',
            shared: shared,
        }
    },
    methods: {
        save: function save() {
            this.formData = JSON.stringify({
                query: this.$refs.query.$data.query,
                schedule: this.$refs.schedule.$data.schedule,
                steps: this.$refs.steps.$data.steps,
                version: WunderAutoData.reTriggerVersion
            });
        },
        updateObjects: function updateObjects(currentStep, objects) {
            if (JSON.stringify(objects) !== JSON.stringify(this.objects[currentStep + 1])) {
                this.objects[currentStep + 1] = objects;
            }
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

reTriggerEditorApp.component('retriggerquery', retriggerQueryComponent);
reTriggerEditorApp.component('retriggerschedule', reTriggerScheduleComponent);
reTriggerEditorApp.component('multiselect', VueformMultiselect);
reTriggerEditorApp.component('steps', stepsComponent);
reTriggerEditorApp.mount('#retrigger-editor-app');
