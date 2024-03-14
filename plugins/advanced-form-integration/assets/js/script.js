Vue.component('webhook-row', {
    props: ["trigger", "action", "fielddata"],
    template: '#webhook-row-template'
});

Vue.component('elementorpro', {
    props: ["trigger", "action", "fielddata"],
    template: '#elementorpro-template'
});

Vue.component('calderaforms', {
    props: ["trigger", "action", "fielddata"],
    template: '#calderaforms-template'
});

Vue.component('everestforms', {
    props: ["trigger", "action", "fielddata"],
    template: '#everestforms-template'
});

Vue.component('fluentforms', {
    props: ["trigger", "action", "fielddata"],
    template: '#fluentforms-template'
});

Vue.component('formcraft', {
    props: ["trigger", "action", "fielddata"],
    template: '#formcraft-template'
});

Vue.component('formcraftb', {
    props: ["trigger", "action", "fielddata"],
    template: '#formcraftb-template'
});

Vue.component('formidable', {
    props: ["trigger", "action", "fielddata"],
    template: '#formidable-template'
});

Vue.component('forminator', {
    props: ["trigger", "action", "fielddata"],
    template: '#forminator-template'
});

Vue.component('gravityforms', {
    props: ["trigger", "action", "fielddata"],
    template: '#gravityforms-template'
});

Vue.component('happyforms', {
    props: ["trigger", "action", "fielddata"],
    template: '#happyforms-template'
});

Vue.component('liveforms', {
    props: ["trigger", "action", "fielddata"],
    template: '#liveforms-template'
});

Vue.component('ninjaforms', {
    props: ["trigger", "action", "fielddata"],
    template: '#ninjaforms-template'
});

Vue.component('quform', {
    props: ["trigger", "action", "fielddata"],
    template: '#quform-template'
});

Vue.component('smartforms', {
    props: ["trigger", "action", "fielddata"],
    template: '#smartforms-template'
});

Vue.component('weforms', {
    props: ["trigger", "action", "fielddata"],
    template: 'weforms-template'
});

Vue.component('wpforms', {
    props: ["trigger", "action", "fielddata"],
    template: '#wpforms-template'
});

Vue.component( 'lifterlms', {
    props: ["trigger", "action", "fielddata"],
    template: '#lifterlms-template',
    mounted: function() {
        console.log(this.trigger.extraFields.courseId);
        if (typeof this.trigger.extraFields.courseId == 'undefined') {
            this.trigger.extraFields.courseId = '';
        }
    }
});

Vue.component('cl-main', {
    props: ["trigger", "action", "fielddata"],
    template: '#cl-main-template',
    data: function() {
        return{}
    },
    methods: {
        clAddCondition: function(event) {
            var conditionL = adfoinNewIntegration.action.cl.conditions.length;
            adfoinNewIntegration.action.cl.conditions.push({id: conditionL+1, field: "", operator: "equal_to", value: ""});
        }
    }
});

Vue.component('conditional-logic', {
    props: ["trigger", "action", "fielddata", "condition"],
    template: '#conditional-logic-template',
    data: function() {
        return{
            selected2: ''
        }
    },
    methods: {
        clRemoveCondition: function(condition) {
            const conditionIndex = adfoinNewIntegration.action.cl.conditions.indexOf(condition);
            adfoinNewIntegration.action.cl.conditions.splice(conditionIndex, 1);
        },
        updateFieldValue: function(e) {
            if(this.selected2 || this.selected2 == 0) {
                if (this.condition.field || "0" == this.condition.field ) {
                    this.condition.field += ' {{' + this.selected2 + '}}';
                } else {
                    this.condition.field = '{{' + this.selected2 + '}}';
                }
            }
        }
    }
});

Vue.component('editable-field', {
    props: ["trigger", "action", "fielddata", "field"],
    template: '#editable-field-template',
    data: function() {
        return{
            selected: ''
        }
    },
    methods: {
        updateFieldValue: function(e) {
            if(this.selected || this.selected == 0) {
                if (this.fielddata[this.field.value] || "0" == this.fielddata[this.field.value]) {
                    this.fielddata[this.field.value] += ' {{' + this.selected + '}}';
                } else {
                    this.fielddata[this.field.value] = '{{' + this.selected + '}}';
                }
            }
        },
        
        inArray: function(needle, haystack) {
            var length = haystack.length;
            for(var i = 0; i < length; i++) {
                if(haystack[i] == needle) return true;
            }
            return false;
        }
    }
});

Vue.component('mailchimp', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe', 'unsubscribe'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        if (typeof this.fielddata.doubleoptin == 'undefined') {
            this.fielddata.doubleoptin = false;
        }

        if (typeof this.fielddata.doubleoptin != 'undefined') {
            if(this.fielddata.doubleoptin == "false") {
                this.fielddata.doubleoptin = false;
            }
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_mailchimp_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#mailchimp-action-template'
});

Vue.component('sendfox', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.firstName == 'undefined') {
            this.fielddata.firstName = '';
        }

        if (typeof this.fielddata.lastName == 'undefined') {
            this.fielddata.lastName = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_sendfox_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#sendfox-action-template'
});

Vue.component('sendx', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'company', title: 'Company', task: ['subscribe'], required: false},
                {type: 'text', value: 'birthday', title: 'Birthday', task: ['subscribe'], required: false, description: 'YYYY-MM-DD'},
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {},
    template: '#sendx-action-template'
});

Vue.component('woodpecker', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe', 'unsubscribe'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.firstName == 'undefined') {
            this.fielddata.firstName = '';
        }

        if (typeof this.fielddata.lastName == 'undefined') {
            this.fielddata.lastName = '';
        }
    },
    template: '#woodpecker-action-template'
});

Vue.component('mautic', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['add_contact'], required: true},
                {type: 'text', value: 'title', title: 'Title', task: ['add_contact'], required: false},
                {type: 'text', value: 'firstname', title: 'First Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'lastname', title: 'Last Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'mobile', title: 'Mobile Number', task: ['add_contact'], required: false},
                {type: 'text', value: 'phone', title: 'Phone Number', task: ['add_contact'], required: false},
                {type: 'text', value: 'fax', title: 'Fax', task: ['add_contact'], required: false},
                {type: 'text', value: 'company', title: 'Company', task: ['add_contact'], required: false},
                {type: 'text', value: 'position', title: 'Position', task: ['add_contact'], required: false},
                {type: 'text', value: 'address1', title: 'Address Line 1', task: ['add_contact'], required: false},
                {type: 'text', value: 'address2', title: 'Address Line 2', task: ['add_contact'], required: false},
                {type: 'text', value: 'city', title: 'City', task: ['add_contact'], required: false},
                {type: 'text', value: 'state', title: 'State', task: ['add_contact'], required: false},
                {type: 'text', value: 'zipcode', title: 'ZIP', task: ['add_contact'], required: false},
                {type: 'text', value: 'country', title: 'Country', task: ['add_contact'], required: false},
                {type: 'text', value: 'website', title: 'Website', task: ['add_contact'], required: false},
                {type: 'text', value: 'facebook', title: 'Facebook', task: ['add_contact'], required: false},
                {type: 'text', value: 'instagram', title: 'Instagram', task: ['add_contact'], required: false},
                {type: 'text', value: 'linkedin', title: 'LinkedIn', task: ['add_contact'], required: false},
                {type: 'text', value: 'twitter', title: 'Twitter', task: ['add_contact'], required: false},
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {},
    template: '#mautic-action-template'
});

Vue.component('livestorm', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            eventLoading: false,
            sessionLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['add_people'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['add_people'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['add_people'], required: false}
            ]

        }
    },
    methods: {
        getSessions: function() {
            this.sessionLoading = true;
            var that = this;

            var sessionRequestData = {
                'action': 'adfoin_get_livestorm_sessions',
                'eventId': this.fielddata.eventId,
                '_nonce': adfoin.nonce
            };

        jQuery.post( ajaxurl, sessionRequestData, function( response ) {
            that.fielddata.sessions = response.data;
            that.sessionLoading = false;
        });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.eventId == 'undefined') {
            this.fielddata.eventId = '';
        }

        if (typeof this.fielddata.sessionId == 'undefined') {
            this.fielddata.sessionId = '';
        }

        this.eventLoading = true;

        var eventRequestData = {
            'action': 'adfoin_get_livestorm_events',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, eventRequestData, function( response ) {
            that.fielddata.events = response.data;
            that.eventLoading = false;
        });

        if( this.fielddata.eventId ) {
            this.getSessions();
        }
    },
    template: '#livestorm-action-template'
});

Vue.component('demio', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            eventLoading: false,
            sessionLoading: false,
            fields: [                
                {type: 'text', value: 'email', title: 'Email', task: ['reg_people'], required: true},
                {type: 'text', value: 'name', title: 'Name', task: ['reg_people'], required: true},
                // {type: 'text', value: 'last_name', title: 'Last Name', task: ['reg_people'], required: false},
                // {type: 'text', value: 'company', title: 'Company', task: ['reg_people'], required: false},
                // {type: 'text', value: 'website', title: 'Website', task: ['reg_people'], required: false},
                // {type: 'text', value: 'phone_number', title: 'Phone Number', task: ['reg_people'], required: false},
                // {type: 'text', value: 'gdpr', title: 'GDPR', task: ['reg_people'], required: false},
                // {type: 'text', value: 'refUrl', title: 'Event Registration page URL', task: ['reg_people'], required: false},
                
            ]

        }
    },
    methods: {
        getSessions: function() {
            this.sessionLoading = true;
            var that = this;
 
            var sessionRequestData = {
                'action': 'adfoin_get_demio_sessions',
                'eventId': this.fielddata.eventId,
                '_nonce': adfoin.nonce
            };
 
        jQuery.post( ajaxurl, sessionRequestData, function( response ) {
            that.fielddata.sessions = response.data;
            that.sessionLoading = false;
        });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.eventId == 'undefined') {
            this.fielddata.eventId = '';
        }

        if (typeof this.fielddata.sessionId  == 'undefined') {
            this.fielddata.sessionId  = '';
        }

        this.eventLoading = true;
 
        var eventRequestData = {
            'action': 'adfoin_get_demio_events',
            '_nonce': adfoin.nonce
        };
 
        jQuery.post( ajaxurl, eventRequestData, function( response ) {
            that.fielddata.events = response.data;
            that.eventLoading = false;
        });
 
        if( this.fielddata.eventId ) {
            this.getSessions();
        }

    },
    
    template: '#demio-action-template'
});

Vue.component('aweber', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            accountLoading: false,
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe', 'unsubscribe'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {
        getLists: function() {
            var that = this;
            this.listLoading = true;

            var listData = {
                'action': 'adfoin_get_aweber_lists',
                '_nonce': adfoin.nonce,
                'accountId': this.fielddata.accountId,
                'task': this.action.task
            };

            jQuery.post( ajaxurl, listData, function( response ) {
                var lists = response.data;
                that.fielddata.lists = lists;
                that.listLoading = false;
            });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.accounts == 'undefined') {
            this.fielddata.accounts = '';
        }

        if (typeof this.fielddata.accountId == 'undefined') {
            this.fielddata.accountId = '';
        }

        if (typeof this.fielddata.lists == 'undefined') {
            this.fielddata.lists = '';
        }

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.firstName == 'undefined') {
            this.fielddata.firstName = '';
        }

        if (typeof this.fielddata.lastName == 'undefined') {
            this.fielddata.lastName = '';
        }

        this.accountLoading = true;

        var accountRequestData = {
            'action': 'adfoin_get_aweber_accounts',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, accountRequestData, function( response ) {
            that.fielddata.accounts = response.data;
            that.accountLoading = false;
        });

        if( this.fielddata.accountId ) {
            this.getLists();
        }
    },
    template: '#aweber-action-template'
});

Vue.component('activecampaign', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            automationLoading: false,
            pipelineLoading: false,
            accountLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email [Contact]', task: ['subscribe'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name [Contact]', task: ['subscribe'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name [Contact]', task: ['subscribe'], required: false},
                {type: 'text', value: 'phoneNumber', title: 'Phone [Contact]', task: ['subscribe'], required: false},
                {type: 'text', value: 'note', title: 'Note', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        if (typeof this.fielddata.automationId == 'undefined') {
            this.fielddata.automationId = '';
        }

        if (typeof this.fielddata.accountId == 'undefined') {
            this.fielddata.accountId = '';
        }

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.firstName == 'undefined') {
            this.fielddata.firstName = '';
        }

        if (typeof this.fielddata.lastName == 'undefined') {
            this.fielddata.lastName = '';
        }

        if (typeof this.fielddata.phoneNumber == 'undefined') {
            this.fielddata.phoneNumber = '';
        }

        if (typeof this.fielddata.update == 'undefined') {
            this.fielddata.update = false;
        }

        if (typeof this.fielddata.update != 'undefined') {
            if(this.fielddata.update == "false") {
                this.fielddata.update = false;
            }
        }

        this.listLoading = true;
        this.automationLoading = true;
        this.pipelineLoading = true;
        this.accountLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_activecampaign_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });

        var automationRequestData = {
            'action': 'adfoin_get_activecampaign_automations',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, automationRequestData, function( response ) {
            that.fielddata.automations = response.data;
            that.automationLoading = false;
        });

        var accountRequestData = {
            'action': 'adfoin_get_activecampaign_accounts',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, accountRequestData, function( response ) {
            that.fielddata.accounts = response.data;
            that.accountLoading = false;
        });

        var dealRequestData = {
            'action': 'adfoin_get_activecampaign_deal_fields',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, dealRequestData, function( response ) {

            if( response.success ) {
                if( response.data ) {
                    response.data.map(function(single) {
                        that.fields.push( { type: 'text', value: single.key, title: single.value, task: ['subscribe'], required: false, description: single.description } );
                    });
                }
            }
        });
    },
    template: '#activecampaign-action-template'
});

Vue.component('agilecrm', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email [Contact]', task: ['add_contact'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name [Contact]', task: ['add_contact'], required: true},
                {type: 'text', value: 'lastName', title: 'Last Name [Contact]', task: ['add_contact'], required: false},
                {type: 'text', value: 'title', title: 'Title [Contact]', task: ['add_contact'], required: false},
                {type: 'text', value: 'company', title: 'Company [Contact]', task: ['add_contact'], required: false},
                {type: 'text', value: 'phone', title: 'Phone [Contact]', task: ['add_contact'], required: false},
                {type: 'text', value: 'address', title: 'Address [Contact]', task: ['add_contact'], required: false},
                {type: 'text', value: 'city', title: 'City [Contact]', task: ['add_contact'], required: false},
                {type: 'text', value: 'state', title: 'State [Contact]', task: ['add_contact'], required: false},
                {type: 'text', value: 'zip', title: 'Zip [Contact]', task: ['add_contact'], required: false},
                {type: 'text', value: 'country', title: 'Country [Contact]', task: ['add_contact'], required: false}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.firstName == 'undefined') {
            this.fielddata.firstName = '';
        }

        if (typeof this.fielddata.lastName == 'undefined') {
            this.fielddata.lastName = '';
        }

        var pipelineRequestData = {
            'action': 'adfoin_get_agilecrm_pipelines',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, pipelineRequestData, function( response ) {

            if( response.success ) {
                if( response.data ) {
                    response.data.map(function(single) {
                        that.fields.push( { type: 'text', value: single.key, title: single.value, task: ['add_contact'], required: false, description: single.description } );
                    });
                }
            }
        });
    },
    template: '#agilecrm-action-template'
});

Vue.component('keap', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['add_contact'], required: true},
                {type: 'text', value: 'title', title: 'Title', task: ['add_contact'], required: false},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'middleName', title: 'Middle Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'suffix', title: 'Suffix', task: ['add_contact'], required: false},
                {type: 'text', value: 'contactType', title: 'Contact Type', task: ['add_contact'], description: 'Lead, Customer, Other', required: false},
                {type: 'text', value: 'company', title: 'Company Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'optin', title: 'Opt-In', task: ['add_contact'], description: 'Has this person opted-in to receiving marketing communications from you? Insert "true" to send them email through Keap.', required: false},
                {type: 'text', value: 'jobTitle', title: 'Job Title', task: ['add_contact'], required: false},
                {type: 'text', value: 'website', title: 'Website', task: ['add_contact'], required: false},
                {type: 'text', value: 'email2', title: 'Email 2', task: ['add_contact'], required: false},
                {type: 'text', value: 'email3', title: 'Email 3', task: ['add_contact'], required: false},
                {type: 'text', value: 'mobilePhone', title: 'Mobile Phone', task: ['add_contact'], required: false},
                {type: 'text', value: 'workPhone', title: 'Work Phone', task: ['add_contact'], required: false},
                {type: 'text', value: 'homePhone', title: 'Home Phone', task: ['add_contact'], required: false},
                {type: 'text', value: 'billingStreet1', title: 'Billing Street1', task: ['add_contact'], required: false},
                {type: 'text', value: 'billingStreet2', title: 'Billing Street2', task: ['add_contact'], required: false},
                {type: 'text', value: 'billingCity', title: 'Billing City', task: ['add_contact'], required: false},
                {type: 'text', value: 'billingState', title: 'Billing State', task: ['add_contact'], required: false},
                {type: 'text', value: 'billingZip', title: 'Billing Zip', task: ['add_contact'], required: false},
                {type: 'text', value: 'billingCountryCode', title: 'Billing Country Code', task: ['add_contact'], required: false},
                {type: 'text', value: 'shippingStreet1', title: 'Shipping Street1', task: ['add_contact'], required: false},
                {type: 'text', value: 'shippingStreet2', title: 'Shipping Street2', task: ['add_contact'], required: false},
                {type: 'text', value: 'shippingCity', title: 'Shipping City', task: ['add_contact'], required: false},
                {type: 'text', value: 'shippingState', title: 'Shipping State', task: ['add_contact'], required: false},
                {type: 'text', value: 'shippingZip', title: 'Shipping Zip', task: ['add_contact'], required: false},
                {type: 'text', value: 'shippingCountryCode', title: 'Shipping Country Code', task: ['add_contact'], required: false},
                {type: 'text', value: 'birthday', title: 'Birthday', task: ['add_contact'], required: false},
                {type: 'text', value: 'anniversary', title: 'Anniversary', task: ['add_contact'], required: false},
                {type: 'text', value: 'spouseName', title: 'Spouse Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'facebook', title: 'Facebook', task: ['add_contact'], required: false},
                {type: 'text', value: 'linkedin', title: 'LinkedIn', task: ['add_contact'], required: false},
                {type: 'text', value: 'twitter', title: 'Twitter', task: ['add_contact'], required: false},
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.firstName == 'undefined') {
            this.fielddata.firstName = '';
        }

        if (typeof this.fielddata.lastName == 'undefined') {
            this.fielddata.lastName = '';
        }

        // var pipelineRequestData = {
        //     'action': 'adfoin_get_keap_pipelines',
        //     '_nonce': adfoin.nonce
        // };

        // jQuery.post( ajaxurl, pipelineRequestData, function( response ) {

        //     if( response.success ) {
        //         if( response.data ) {
        //             response.data.map(function(single) {
        //                 that.fields.push( { type: 'text', value: single.key, title: single.value, task: ['add_contact'], required: false, description: single.description } );
        //             });
        //         }
        //     }
        // });
    },
    template: '#keap-action-template'
});

Vue.component('pushover', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'title', title: 'Title', task: ['push'], required: false},
                {type: 'text', value: 'message', title: 'Message', task: ['push'], required: false},
                {type: 'text', value: 'device', title: 'Device', task: ['push'], required: false}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.title == 'undefined') {
            this.fielddata.title = '';
        }

        if (typeof this.fielddata.message == 'undefined') {
            this.fielddata.message = '';
        }

        if (typeof this.fielddata.device == 'undefined') {
            this.fielddata.device = '';
        }
    },
    template: '#pushover-action-template'
});

Vue.component('twilio', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'to', title: 'To', task: ['subscribe'], required: true},
                {type: 'textarea', value: 'body', title: 'Body', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.from == 'undefined') {
            this.fielddata.from = '';
        }

        if (typeof this.fielddata.to == 'undefined') {
            this.fielddata.to = '';
        }

        if (typeof this.fielddata.body == 'undefined') {
            this.fielddata.body = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_twilio_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#twilio-action-template'
});

Vue.component('elasticemail', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.firstName == 'undefined') {
            this.fielddata.firstName = '';
        }

        if (typeof this.fielddata.lastName == 'undefined') {
            this.fielddata.lastName = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_elasticemail_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#elasticemail-action-template'
});

Vue.component('pabbly', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'name', title: 'Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'mobile', title: 'Mobile', task: ['subscribe'], required: false},
                {type: 'text', value: 'city', title: 'City', task: ['subscribe'], required: false},
                {type: 'text', value: 'country', title: 'Country', task: ['subscribe'], required: false},
                {type: 'text', value: 'website', title: 'Website', task: ['subscribe'], required: false},
                {type: 'text', value: 'facebook', title: 'Facebook', task: ['subscribe'], required: false},
                {type: 'text', value: 'age', title: 'Age', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.firstName == 'undefined') {
            this.fielddata.firstName = '';
        }

        if (typeof this.fielddata.lastName == 'undefined') {
            this.fielddata.lastName = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_pabbly_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#pabbly-action-template'
});

Vue.component('robly', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'fname', title: 'First Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'lname', title: 'Last Name', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_robly_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#robly-action-template'
});

Vue.component('selzy', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'name', title: 'Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'phone', title: 'Phone Number', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        if (typeof this.fielddata.doubleOptin == 'undefined') {
            this.fielddata.doubleOptin = false;
        }

        if (typeof this.fielddata.doubleOptin != 'undefined') {
            if(this.fielddata.doubleOptin == "false") {
                this.fielddata.doubleOptin = false;
            }
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_selzy_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#selzy-action-template'
});

Vue.component('mailerlite', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'name', title: 'Name', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.name == 'undefined') {
            this.fielddata.name = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_mailerlite_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#mailerlite-action-template'
});

Vue.component('mailerlite2', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fieldsLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'status', title: 'Status', task: ['subscribe'], required: false, description: 'active | unsubscribed | unconfirmed | bounced | junk'},
                {type: 'text', value: 'ip_address', title: 'IP Address', task: ['subscribe'], required: false},
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_mailerlite2_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });

        this.fieldsLoading = true;

        var customFieldData = {
            'action': 'adfoin_get_mailerlite2_custom_fields',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, customFieldData, function( response ) {

            if( response.success ) {
                if( response.data ) {
                    response.data.map(function(single) {
                        that.fields.push( { type: 'text', value: single.key, title: single.value, task: ['subscribe'], required: false, description: single.description } );
                    });

                    that.fieldsLoading = false;
                }
            }
        });
    },
    template: '#mailerlite2-action-template'
});

Vue.component('emailoctopus', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.firstName == 'undefined') {
            this.fielddata.firstName = '';
        }

        if (typeof this.fielddata.lastName == 'undefined') {
            this.fielddata.lastName = '';
        }

        if (typeof this.fielddata.doubleoptin != 'undefined') {
            if(this.fielddata.doubleoptin == "false") {
                this.fielddata.doubleoptin = false;
            }
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_emailoctopus_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#emailoctopus-action-template'
});

Vue.component('jumplead', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['add_contact'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['add_contact'], required: false}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.firstName == 'undefined') {
            this.fielddata.firstName = '';
        }

        if (typeof this.fielddata.lastName == 'undefined') {
            this.fielddata.lastName = '';
        }
    },
    template: '#jumplead-action-template'
});

Vue.component('klaviyo', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'title', title: 'Title', task: ['subscribe'], required: false},
                {type: 'text', value: 'organization', title: 'Organization', task: ['subscribe'], required: false},
                {type: 'text', value: 'phoneNumber', title: 'Phone Number', task: ['subscribe'], required: false, description: 'Should be passed with proper country code. For example: "+91xxxxxxxxxx"'},
                {type: 'text', value: 'address1', title: 'Address 1', task: ['subscribe'], required: false},
                {type: 'text', value: 'address2', title: 'Address 2', task: ['subscribe'], required: false},
                {type: 'text', value: 'city', title: 'City', task: ['subscribe'], required: false},
                {type: 'text', value: 'region', title: 'Region', task: ['subscribe'], required: false},
                {type: 'text', value: 'zip', title: 'ZIP', task: ['subscribe'], required: false},
                {type: 'text', value: 'country', title: 'Country', task: ['subscribe'], required: false},
                {type: 'text', value: 'latitude', title: 'Latitude', task: ['subscribe'], required: false},
                {type: 'text', value: 'longitude', title: 'Longitude', task: ['subscribe'], required: false},
                {type: 'text', value: 'timezone', title: 'Timezone', task: ['subscribe'], required: false, description: 'e.g. Asia/Dhaka'},
                {type: 'text', value: 'externalId', title: 'External ID', task: ['subscribe'], required: false},
                {type: 'text', value: 'source', title: 'Source', task: ['subscribe'], required: false},
            ]

        }
    },
    methods: {
        getLists: function(credId = null) {
            var that = this;

            this.listLoading = true;

            var listRequestData = {
                'action': 'adfoin_get_klaviyo_list',
                'credId': this.fielddata.credId,
                '_nonce': adfoin.nonce
            };

            jQuery.post( ajaxurl, listRequestData, function( response ) {
                that.fielddata.list = response.data;
                that.listLoading = false;
            });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.credId == 'undefined') {
            this.fielddata.credId = '';
        }

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        
        this.getLists(this.fielddata.credId);
    },
    template: '#klaviyo-action-template'
});

Vue.component('acelle', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'EMAIL', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'FIRST_NAME', title: 'First Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'LAST_NAME', title: 'Last Name', task: ['subscribe'], required: false},
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_acelle_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#acelle-action-template'
});

Vue.component('zohocampaigns', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false}
            ]
        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_zohocampaigns_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#zohocampaigns-action-template'
});

Vue.component('customerio', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['add_people'], required: true},                
                {type: 'text', value: 'firstName', title: 'First Name', task: ['add_people'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['add_people'], required: false},
              ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {},
    template: '#customerio-action-template'
});

Vue.component('kartra', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'middleName', title: 'Middle Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'lastName2', title: 'Last Name 2', task: ['subscribe'], required: false},
                {type: 'text', value: 'phoneCountryCode', title: 'Phone Country Code', task: ['subscribe'], required: false},
                {type: 'text', value: 'phone', title: 'Phone', task: ['subscribe'], required: false},
                {type: 'text', value: 'ip', title: 'IP', task: ['subscribe'], required: false},
                {type: 'text', value: 'address', title: 'Address 1', task: ['subscribe'], required: false},
                {type: 'text', value: 'zip', title: 'ZIP', task: ['subscribe'], required: false},
                {type: 'text', value: 'city', title: 'City', task: ['subscribe'], required: false},
                {type: 'text', value: 'state', title: 'State', task: ['subscribe'], required: false},
                {type: 'text', value: 'country', title: 'Country', task: ['subscribe'], required: false},
                {type: 'text', value: 'company', title: 'Company', task: ['subscribe'], required: false},
                {type: 'text', value: 'website', title: 'Website', task: ['subscribe'], required: false},
                {type: 'text', value: 'facebook', title: 'Facebook', task: ['subscribe'], required: false},
                {type: 'text', value: 'twitter', title: 'Twitter', task: ['subscribe'], required: false},
                {type: 'text', value: 'linkedin', title: 'LinkedIn', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.firstName == 'undefined') {
            this.fielddata.firstName = '';
        }

        if (typeof this.fielddata.lastName == 'undefined') {
            this.fielddata.lastName = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_kartra_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#kartra-action-template'
});

Vue.component('moosend', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'name', title: 'Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'mobile', title: 'Phone', task: ['subscribe'], required: false, 'description': 'Phone number should be passed with proper country code. For example: "+91xxxxxxxxxx"'}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.name == 'undefined') {
            this.fielddata.name = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_moosend_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#moosend-action-template'
});

Vue.component('mailercloud', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: []

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_mailercloud_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });

        var fieldRequestData = {
            'action': 'adfoin_get_mailercloud_contact_fields',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, fieldRequestData, function( response ) {

            if( response.success ) {
                if( response.data ) {
                    response.data.map(function(single) {
                        that.fields.push( { type: 'text', value: single.key, title: single.value, task: ['subscribe'], required: false, description: single.description } );
                    });
                }
            }
        });
    },
    template: '#mailercloud-action-template'
});

Vue.component('encharge', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            fieldLoading: false,
            fields: []

        }
    },
    methods: {},
    created: function() {},
    mounted: function() {
        var that = this;

        this.fieldLoading = true;

        var fieldRequestData = {
            'action': 'adfoin_get_encharge_fields',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, fieldRequestData, function( response ) {

            if( response.success ) {
                if( response.data ) {
                    response.data.map(function(single) {
                        that.fields.push( { type: 'text', value: single.key, title: single.value, task: ['subscribe'], required: false, description: single.description } );
                    });

                    that.fieldLoading = false;
                }
            }
        });
    },
    template: '#encharge-action-template'
});

Vue.component('sendy', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'name', title: 'Name', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.name == 'undefined') {
            this.fielddata.name = '';
        }
    },
    template: '#sendy-action-template'
});

Vue.component('convertkit', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            formsLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        if (typeof this.fielddata.formId == 'undefined') {
            this.fielddata.formId = '';
        }

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.firstName == 'undefined') {
            this.fielddata.firstName = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_convertkit_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });

        this.formsLoading = true;

        var formsRequestData = {
            'action': 'adfoin_get_convertkit_forms',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, formsRequestData, function( response ) {
            that.fielddata.forms = response.data;
            that.formsLoading = false;
        });
    },
    template: '#convertkit-action-template'
});

Vue.component('beehiiv', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'utm_source', title: 'UTM Source', task: ['subscribe'], required: false},
                {type: 'text', value: 'utm_campaign', title: 'UTM Campaign', task: ['subscribe'], required: false},
                {type: 'text', value: 'utm_medium', title: 'UTM Medium', task: ['subscribe'], required: false},
                {type: 'text', value: 'referring_site', title: 'Referring Site', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_beehiiv_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#beehiiv-action-template'
});

Vue.component('wealthbox', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            ownerLoading: false,
            fields: [
                {type: 'text', value: 'prefix', title: 'Prefix', task: ['add_contact'], required: false},               
                {type: 'text', value: 'firstName', title: 'First Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'middleName', title: 'Middle Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'suffix', title: 'Suffix', task: ['add_contact'], required: false},        
                {type: 'text', value: 'nickname', title: 'Nick Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'twitterName', title: 'Twitter Name', task: ['add_contact'], required: false}, 
                {type: 'text', value: 'linkedinUrl', title: 'LinkedIn URL', task: ['add_contact'], required: false},
                {type: 'text', value: 'contactSource', title: 'Contact Source', task: ['add_contact'], required: false, description: 'Referral | Conference | Direct Mail | Cold Call | Other'},
                {type: 'text', value: 'contactType', title: 'Contact Type', task: ['add_contact'], required: false, description: 'Client | Past Client | Prospect | Vendor | Organization'},
                {type: 'text', value: 'status', title: 'Status', task: ['add_contact'], required: false, description: 'Active | Inactive'},
                {type: 'text', value: 'maritalStatus', title: 'Marital Status', task: ['add_contact'], required: false, description: 'Married | Single | Divorced | Widowed | Life Partner | Seperated | Unknown'},
                {type: 'text', value: 'jobTitle', title: 'Job Title', task: ['add_contact', ], required: false},                
                {type: 'text', value: 'companyName', title: 'Company Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'backgroundInfo', title: 'Background Information', task: ['add_contact'], required: false},
                {type: 'text', value: 'gender', title: 'Gender', task: ['add_contact'], required: false, description: 'Female | Male | Non-binary | Unknown'},
                {type: 'text', value: 'householdTitle', title: 'Household Title', task: ['add_contact'], required: false},
                {type: 'text', value: 'householdName', title: 'Household Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'personalEmail', title: 'Pesonal Email', task: ['add_contact'], required: false},
                {type: 'text', value: 'workEmail', title: 'Work Email', task: ['add_contact'], required: false},
                {type: 'text', value: 'mobile', title: 'Mobile', task: ['add_contact'], required: false},
                {type: 'text', value: 'workPhone', title: 'Work Phone', task: ['add_contact'], required: false},
                {type: 'text', value: 'homePhone', title: 'Home Phone', task: ['add_contact'], required: false},
                {type: 'text', value: 'birthDate', title: 'Birth Date', task: ['add_contact'], required: false},
                {type: 'text', value: 'addressLine1', title: 'Address line 1', task: ['add_contact'], required: false},
                {type: 'text', value: 'addressLine2', title: 'Address line 2', task: ['add_contact'], required: false},
                {type: 'text', value: 'city', title: 'City', task: ['add_contact'], required: false},                
                {type: 'text', value: 'state', title: 'State', task: ['add_contact'], required: false},
                {type: 'text', value: 'country', title: 'Country', task: ['add_contact'], required: false},
                {type: 'text', value: 'zipCode', title: 'ZIP Code', task: ['add_contact'], required: false},
                {type: 'text', value: 'kind', title: 'Address Type', task: ['add_contact'], required: false, description: 'e.g. Work | Home'},
                {type: 'text', value: 'webAddress', title: 'Website', task: ['add_contact'], required: false},
                {type: 'text', value: 'webType', title: 'Web Address Type', task: ['add_contact'], required: false}
            ]
        }
    },
    methods: {},
    created: function() {},
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.owner == 'undefined') {
            this.fielddata.owner = '';
        }

        this.ownerLoading = true;

        var ownerRequestData = {
            'action': 'adfoin_get_wealthbox_owner_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, ownerRequestData, function( response ) {
            that.fielddata.ownerList = response.data;
            that.ownerLoading = false;
        });
    },
    template: '#wealthbox-action-template'
});

Vue.component('onehash', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['add_lead', 'add_customer', 'add_contact'], required: true},
                {type: 'text', value: 'fullName', title: 'Name', task: ['add_lead'], required: false},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'middleName', title: 'Middle Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'customerName', title: 'Customer Name', task: ['add_customer'], required: false},
                {type: 'text', value: 'customerType', title: 'Customer Type', task: ['add_customer'], required: false},
                {type: 'text', value: 'customerGroup', title: 'Customer Group', task: ['add_customer'], required: false},
                {type: 'text', value: 'territory', title: 'Territory', task: ['add_customer'], required: false},
                {type: 'text', value: 'leadName', title: 'Lead Name', task: ['add_customer'], required: false},
                {type: 'text', value: 'opportunityName', title: 'Opportunity Name', task: ['add_customer'], required: false},
                {type: 'text', value: 'company', title: 'Company Name', task: ['add_lead', ], required: false},
                {type: 'text', value: 'status', title: 'Status', task: ['add_lead'], required: false, description: 'Active | Inactive'},
                {type: 'text', value: 'salutation', title: 'Salutation', task: ['add_lead', 'add_customer', 'add_contact'], required: false},
                {type: 'text', value: 'designation', title: 'Designation', task: ['add_lead', 'add_customer', 'add_contact'], required: false},
                {type: 'text', value: 'gender', title: 'Gender', task: ['add_lead', 'add_customer', 'add_contact'], required: false},
                {type: 'text', value: 'source', title: 'Source', task: ['add_lead'], required: false},
                {type: 'text', value: 'campaignName', title: 'Campaign Name', task: ['add_lead'], required: false},
                {type: 'text', value: 'contactBy', title: 'Contact By', task: ['add_lead'], required: false},
                {type: 'text', value: 'contactDate', title: 'Contact Date', task: ['add_lead'], required: false},
                {type: 'text', value: 'endsOn', title: 'Ends On', task: ['add_lead'], required: false},
                {type: 'text', value: 'addressType', title: 'Address Type', task: ['add_lead', 'add_contact'], required: false},
                {type: 'text', value: 'addressTitle', title: 'Address Title', task: ['add_lead', 'add_contact'], required: false},
                {type: 'text', value: 'addressLine1', title: 'Address line 1', task: ['add_lead', 'add_customer', 'add_contact'], required: false},
                {type: 'text', value: 'addressLine2', title: 'Address line 2', task: ['add_lead', 'add_customer', 'add_contact'], required: false},
                {type: 'text', value: 'city', title: 'City', task: ['add_lead', 'add_customer', 'add_contact'], required: false},
                {type: 'text', value: 'county', title: 'County', task: ['add_lead', 'add_customer', 'add_contact'], required: false},
                {type: 'text', value: 'state', title: 'State', task: ['add_lead', 'add_customer', 'add_contact'], required: false},
                {type: 'text', value: 'country', title: 'Country', task: ['add_lead', 'add_customer', 'add_contact'], required: false},
                {type: 'text', value: 'pincode', title: 'Postal Code', task: ['add_lead', 'add_customer', 'add_contact'], required: false},
                {type: 'text', value: 'website', title: 'Website', task: ['add_lead', 'add_contact'], required: false},
                {type: 'text', value: 'phonNO', title: 'Phone', task: ['add_lead', 'add_contact'], required: false},
                {type: 'text', value: 'mobileNo', title: 'Mobile No.', task: ['add_lead', 'add_customer', 'add_contact'], required: false},
                {type: 'text', value: 'fax', title: 'Fax', task: ['add_lead', 'add_contact'], required: false},
                {type: 'text', value: 'doctype', title: 'Doctype', task: ['add_lead', 'add_customer'], required: false}
            ]
        }
    },
    methods: {},
    created: function() {},
    mounted: function() {},
    template: '#onehash-action-template'
});

Vue.component('nimble', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['add_contact'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['add_contact'], required: false}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.firstName == 'undefined') {
            this.fielddata.firstName = '';
        }
    },
    template: '#nimble-action-template'
});

Vue.component('companyhub', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['add_contact'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['add_contact'], required: false}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.firstName == 'undefined') {
            this.fielddata.firstName = '';
        }
    },
    template: '#companyhub-action-template'
});

Vue.component('autopilot', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'twitter', title: 'Twitter', task: ['subscribe'], required: false},
                {type: 'text', value: 'salutation', title: 'Salutation', task: ['subscribe'], required: false},
                {type: 'text', value: 'company', title: 'Company', task: ['subscribe'], required: false},
                {type: 'text', value: 'numberOfEmployees', title: 'Number Of Employees', task: ['subscribe'], required: false},
                {type: 'text', value: 'title', title: 'Title', task: ['subscribe'], required: false},
                {type: 'text', value: 'industry', title: 'Industry', task: ['subscribe'], required: false},
                {type: 'text', value: 'phone', title: 'Phone', task: ['subscribe'], required: false},
                {type: 'text', value: 'mobilePhone', title: 'MobilePhone', task: ['subscribe'], required: false},
                {type: 'text', value: 'fax', title: 'Fax', task: ['subscribe'], required: false},
                {type: 'text', value: 'website', title: 'Website', task: ['subscribe'], required: false},
                {type: 'text', value: 'mailingStreet', title: 'MailingStreet', task: ['subscribe'], required: false},
                {type: 'text', value: 'mailingCity', title: 'MailingCity', task: ['subscribe'], required: false},
                {type: 'text', value: 'mailingState', title: 'MailingState', task: ['subscribe'], required: false},
                {type: 'text', value: 'mailingPostalCode', title: 'MailingPostalCode', task: ['subscribe'], required: false},
                {type: 'text', value: 'mailingCountry', title: 'MailingCountry', task: ['subscribe'], required: false},
                {type: 'text', value: 'leadSource', title: 'LeadSource', task: ['subscribe'], required: false},
                {type: 'text', value: 'linkedIn', title: 'LinkedIn', task: ['subscribe'], required: false}

            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.firstName == 'undefined') {
            this.fielddata.firstName = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_autopilot_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#autopilot-action-template'
});

Vue.component('benchmark', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'middleName', title: 'Middle Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_benchmark_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#benchmark-action-template'
});

Vue.component('sendpulse', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'name', title: 'Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'phone', title: 'Phone', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.firstName == 'undefined') {
            this.fielddata.firstName = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_sendpulse_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#sendpulse-action-template'
});

Vue.component('getresponse', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'name', title: 'Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'ipAddress', title: 'IP Address', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_getresponse_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#getresponse-action-template'
});

Vue.component('engagebay', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['subscribe'], required: true},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'phone', title: 'Phone', task: ['subscribe'], required: false},
                {type: 'text', value: 'role', title: 'Role', task: ['subscribe'], required: false},
                {type: 'text', value: 'website', title: 'Website', task: ['subscribe'], required: false},
                {type: 'text', value: 'address', title: 'Address', task: ['subscribe'], required: false},
                {type: 'text', value: 'city', title: 'City', task: ['subscribe'], required: false},
                {type: 'text', value: 'sate', title: 'State', task: ['subscribe'], required: false},
                {type: 'text', value: 'zip', title: 'Zip', task: ['subscribe'], required: false},
                {type: 'text', value: 'country', title: 'Country', task: ['subscribe'], required: false},
                {type: 'text', value: 'company', title: 'Company', task: ['subscribe'], required: false},
            ]
        }
    },
    methods: {},
    created: function() {},
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_engagebay_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#engagebay-action-template'
});

Vue.component('easysendy', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false}
            ]
        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.name == 'undefined') {
            this.fielddata.name = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_easysendy_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#easysendy-action-template'
});

Vue.component('salesrocks', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'name', title: 'Name', task: ['subscribe'], required: false},
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_salesrocks_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#salesrocks-action-template'
});

Vue.component('mailwizz', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false},
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_mailwizz_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#mailwizz-action-template'
});

Vue.component('trello', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            boardLoading: false,
            listLoading: false,
            fields: [
                {type: 'text', value: 'name', title: 'Name', task: ['add_card'], required: true},
                {type: 'textarea', value: 'description', title: 'Description', task: ['add_card'], required: false},
                {type: 'text', value: 'pos', title: 'Position', task: ['add_card'], required: false, description: 'The position of the new card. top, bottom, or a positive float'}
            ]

        }
    },
    methods: {
        getLists: function() {
            var that = this;
            this.listLoading = true;

            var listData = {
                'action': 'adfoin_get_trello_lists',
                '_nonce': adfoin.nonce,
                'boardId': this.fielddata.boardId,
                'task': this.action.task
            };

            jQuery.post( ajaxurl, listData, function( response ) {
                var lists = response.data;
                that.fielddata.lists = lists;
                that.listLoading = false;
            });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.boardId == 'undefined') {
            this.fielddata.boardId = '';
        }

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        this.boardLoading = true;

        var boardRequestData = {
            'action': 'adfoin_get_trello_boards',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, boardRequestData, function( response ) {
            that.fielddata.boards = response.data;
            that.boardLoading = false;
        });

        if( this.fielddata.boardId ) {
            var that = this;
            this.listLoading = true;

            var listData = {
                'action': 'adfoin_get_trello_lists',
                '_nonce': adfoin.nonce,
                'boardId': this.fielddata.boardId,
                'task': this.action.task
            };

            jQuery.post( ajaxurl, listData, function( response ) {
                var lists = response.data;
                that.fielddata.lists = lists;
                that.listLoading = false;
            });
        }
    },
    template: '#trello-action-template'
});

Vue.component('mailjet', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'name', title: 'Name', task: ['subscribe'], required: false}
            ]

        }
    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.firstName == 'undefined') {
            this.fielddata.firstName = '';
        }

        if (typeof this.fielddata.lastName == 'undefined') {
            this.fielddata.lastName = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_mailjet_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#mailjet-action-template'
});

Vue.component('mailify', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'phone', title: 'Phone', task: ['subscribe'], required: false},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false}
            ]

        }
    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.firstName == 'undefined') {
            this.fielddata.firstName = '';
        }

        if (typeof this.fielddata.lastName == 'undefined') {
            this.fielddata.lastName = '';
        }

        if (typeof this.fielddata.phone == 'undefined') {
            this.fielddata.phone = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_mailify_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#mailify-action-template'
});

Vue.component('lemlist', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false}
            ]

        }
    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.firstName == 'undefined') {
            this.fielddata.firstName = '';
        }

        if (typeof this.fielddata.lastName == 'undefined') {
            this.fielddata.lastName = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_lemlist_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#lemlist-action-template'
});

Vue.component('directiq', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false}
            ]

        }
    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.firstName == 'undefined') {
            this.fielddata.firstName = '';
        }

        if (typeof this.fielddata.lastName == 'undefined') {
            this.fielddata.lastName = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_directiq_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#directiq-action-template'
});

Vue.component('revue', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false}
            ]

        }
    },
    methods: {
    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.firstName == 'undefined') {
            this.fielddata.firstName = '';
        }

        if (typeof this.fielddata.doptin == 'undefined') {
            this.fielddata.doptin = false;
        }

        if (typeof this.fielddata.doptin != 'undefined') {
            if(this.fielddata.doptin == "false") {
                this.fielddata.doptin = false;
            }
        }
    },
    template: '#revue-action-template'
});

Vue.component('slack', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'textarea', value: 'message', title: 'Message', task: ['sendmsg'], required: false}
            ]

        }
    },
    methods: {
    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.url == 'undefined') {
            this.fielddata.url = '';
        }

        if (typeof this.fielddata.message == 'undefined') {
            this.fielddata.message = '';
        }
    },
    template: '#slack-action-template'
});

Vue.component('liondesk', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['add_contact'], required: true},
                {type: 'text', value: 'secondaryEmail', title: 'Secondary Email', task: ['add_contact'], required: false},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'mobilePhone', title: 'Mobile Phone', task: ['add_contact'], required: false},
                {type: 'text', value: 'homePhone', title: 'Home Phone', task: ['add_contact'], required: false},
                {type: 'text', value: 'officePhone', title: 'Office Phone', task: ['add_contact'], required: false},
                {type: 'text', value: 'fax', title: 'Fax', task: ['add_contact'], required: false},
                {type: 'text', value: 'company', title: 'Company', task: ['add_contact'], required: false},
                {type: 'text', value: 'birthday', title: 'Birthday', task: ['add_contact'], required: false},
                {type: 'text', value: 'spouseName', title: 'Spouse Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'spouseEmail', title: 'Spouse Email', task: ['add_contact'], required: false},
                {type: 'text', value: 'spousePhone', title: 'Spouse Phone', task: ['add_contact'], required: false},
                {type: 'text', value: 'spouseBirthday', title: 'Spouse Birthday', task: ['add_contact'], required: false},
                {type: 'text', value: 'address1_type', title: 'Address1 Type', task: ['add_contact'], required: false},
                {type: 'text', value: 'address1_street1', title: 'Address1 Street1', task: ['add_contact'], required: false},
                {type: 'text', value: 'address1_street2', title: 'Address1 Street2', task: ['add_contact'], required: false},
                {type: 'text', value: 'address1_zip', title: 'Address1 ZIP', task: ['add_contact'], required: false},
                {type: 'text', value: 'address1_city', title: 'Address1 City', task: ['add_contact'], required: false},
                {type: 'text', value: 'address1_state', title: 'Address1 State', task: ['add_contact'], required: false},
                {type: 'text', value: 'address2_type', title: 'Address2 Type', task: ['add_contact'], required: false},
                {type: 'text', value: 'address2_street1', title: 'Address2 Street1', task: ['add_contact'], required: false},
                {type: 'text', value: 'address2_street2', title: 'Address2 Street2', task: ['add_contact'], required: false},
                {type: 'text', value: 'address2_zip', title: 'Address2 ZIP', task: ['add_contact'], required: false},
                {type: 'text', value: 'address2_city', title: 'Address2 City', task: ['add_contact'], required: false},
                {type: 'text', value: 'address2_state', title: 'Address2 State', task: ['add_contact'], required: false},
            ]

        }
    },
    template: '#liondesk-action-template'
});

Vue.component('curated', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true}
            ]

        }
    },
    methods: {
    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }
    },
    template: '#curated-action-template'
});

Vue.component('sendinblue', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'sms', title: 'SMS', task: ['subscribe'], required: false, description: 'Mobile Number should be passed with proper country code. For example: "+91xxxxxxxxxx" or "0091xxxxxxxxxx"'}
            ]

        }
    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.firstName == 'undefined') {
            this.fielddata.firstName = '';
        }

        if (typeof this.fielddata.lastName == 'undefined') {
            this.fielddata.lastName = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_sendinblue_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#sendinblue-action-template'
});

Vue.component('zapier', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {}
    },
    mounted: function() {

        if (typeof this.fielddata.webhookUrl == 'undefined') {
            this.fielddata.webhookUrl = '';
        }
    },
    template: '#zapier-action-template'
});

Vue.component('webhook', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {}
    },
    mounted: function() {

        if (typeof this.fielddata.webhookUrl == 'undefined') {
            this.fielddata.webhookUrl = '';
        }
    },
    template: '#webhook-action-template'
});

Vue.component('drip', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            accountLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['create_subscriber'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['create_subscriber'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['create_subscriber'], required: false},
                {type: 'text', value: 'phone', title: 'Phone', task: ['create_subscriber'], required: false},
                {type: 'text', value: 'address1', title: 'Address 1', task: ['create_subscriber'], required: false},
                {type: 'text', value: 'address2', title: 'Address 2', task: ['create_subscriber'], required: false},
                {type: 'text', value: 'city', title: 'City', task: ['create_subscriber'], required: false},
                {type: 'text', value: 'state', title: 'State', task: ['create_subscriber'], required: false},
                {type: 'text', value: 'zip', title: 'ZIP', task: ['create_subscriber'], required: false},
                {type: 'text', value: 'country', title: 'Country', task: ['create_subscriber'], required: false},
            ]

        }
    },
    methods: {
        getList: function() {
            var that = this;
            this.accountLoading = true;

            var listData = {
                'action': 'adfoin_get_drip_list',
                '_nonce': adfoin.nonce,
                'accountId': this.fielddata.accountId
            };

            jQuery.post( ajaxurl, listData, function( response ) {
                var list = response.data;
                that.fielddata.list = list;
                //that.accountLoading = false;

                var workflowData = {
                    'action': 'adfoin_get_drip_workflows',
                    '_nonce': adfoin.nonce,
                    'accountId': that.fielddata.accountId
                };

                jQuery.post( ajaxurl, workflowData, function( response ) {
                    var workflows = response.data;
                    that.fielddata.workflows = workflows;
                    that.accountLoading = false;
                });
            });


        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.accountId == 'undefined') {
            this.fielddata.accountId = '';
        }

        if (typeof this.fielddata.campaignId == 'undefined') {
            this.fielddata.campaignId = '';
        }

        if (typeof this.fielddata.workflowId == 'undefined') {
            this.fielddata.workflowId = '';
        }

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.firstName == 'undefined') {
            this.fielddata.firstName = '';
        }

        if (typeof this.fielddata.lastName == 'undefined') {
            this.fielddata.lastName = '';
        }

        this.listLoading = true;

        var accountRequestData = {
            'action': 'adfoin_get_drip_accounts',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, accountRequestData, function( response ) {
            that.fielddata.accounts = response.data;
            that.listLoading = false;
        });
    },
    template: '#drip-action-template'
});

Vue.component('asana', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            workspaceLoading: false,
            projectLoading: false,
            sectionLoading: false,
            userLoading: false,
            fields: [
                {type: 'text', value: 'name', title: 'Name', task: ['create_task'], required: true},
                {type: 'textarea', value: 'notes', title: 'Notes', task: ['create_task'], required: false},
                {type: 'text', value: 'dueOn', title: 'Due On', task: ['create_task'], required: false, description: 'Use YYYY-MM-DD format'},
                {type: 'text', value: 'dueOnX', title: 'Due After X Days', task: ['create_task'], required: false, description: 'Accepts numeric value. If filled, due date will be calculated and set'},
            ]

        }
    },
    methods: {
        getProjects: function() {
            var that = this;
            this.projectLoading = true;
            this.userLoading = true;

            var projectData = {
                'action': 'adfoin_get_asana_projects',
                '_nonce': adfoin.nonce,
                'workspaceId': this.fielddata.workspaceId
            };

            jQuery.post( ajaxurl, projectData, function( response ) {
                var projects = response.data;
                that.fielddata.projects = projects;
                that.projectLoading = false;
            });

            var userData = {
                'action': 'adfoin_get_asana_users',
                '_nonce': adfoin.nonce,
                'workspaceId': this.fielddata.workspaceId
            };

            jQuery.post( ajaxurl, userData, function( response ) {
                var users = response.data;
                that.fielddata.users = users;
                that.userLoading = false;
            });
        },
        getSections: function() {
            var that = this;
            this.sectionLoading = true;

            var sectionData = {
                'action': 'adfoin_get_asana_sections',
                '_nonce': adfoin.nonce,
                'projectId': this.fielddata.projectId
            };

            jQuery.post( ajaxurl, sectionData, function( response ) {
                var sections = response.data;
                that.fielddata.sections = sections;
                that.sectionLoading = false;
            });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.workspaceId == 'undefined') {
            this.fielddata.workspaceId = '';
        }

        if (typeof this.fielddata.projectId == 'undefined') {
            this.fielddata.projectId = '';
        }

        if (typeof this.fielddata.sectionId == 'undefined') {
            this.fielddata.sectionId = '';
        }

        if (typeof this.fielddata.userId == 'undefined') {
            this.fielddata.userId = '';
        }

        this.workspaceLoading = true;

        var workspaceRequestData = {
            'action': 'adfoin_get_asana_workspaces',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, workspaceRequestData, function( response ) {
            that.fielddata.workspaces = response.data;
            that.workspaceLoading = false;
        });

        if( this.fielddata.workspaceId ) {
            this.getProjects();
        }

        if( this.fielddata.workspaceId && this.fielddata.projectId ) {
            this.getSections();
        }
    },
    template: '#asana-action-template'
});

Vue.component('clickup', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            workspaceLoading: false,
            spaceLoading: false,
            folderLoading: false,
            listLoading: false,
            fields: [
                {type: 'text', value: 'name', title: 'Name', task: ['create_task'], required: true},
                {type: 'textarea', value: 'description', title: 'Description', task: ['create_task'], required: false},
                {type: 'text', value: 'startDate', title: 'Start Date', task: ['create_task'], required: false},
                {type: 'text', value: 'dueDate', title: 'Due Date', task: ['create_task'], required: false},
                {type: 'text', value: 'dueOnX', title: 'Due After X Days', task: ['create_task'], required: false, description: 'Accepts numeric value. If filled, due date will be calculated and set'},
                {type: 'text', value: 'priorityId', title: 'Priority ID', task: ['create_task'], required: false, description: 'Urgent: 1, Hight: 2. Normal: 3, Low: 4'},
                {type: 'text', value: 'assignees', title: 'Assignee Emails', task: ['create_task'], required: false, description: 'Enter assignee email. Use comma for multiple emails.'},
            ]
        }
    },
    methods: {
        getSpaces: function() {
            var that = this;
            this.spaceLoading = true;

            var spaceData = {
                'action': 'adfoin_get_clickup_spaces',
                '_nonce': adfoin.nonce,
                'workspaceId': this.fielddata.workspaceId
            };

            jQuery.post( ajaxurl, spaceData, function( response ) {
                var spaces = response.data;
                that.fielddata.spaces = spaces;
                that.spaceLoading = false;
            });
        },
        getFolders: function() {
            var that = this;
            this.folderLoading = true;

            var folderData = {
                'action': 'adfoin_get_clickup_folders',
                '_nonce': adfoin.nonce,
                'spaceId': this.fielddata.spaceId
            };

            jQuery.post( ajaxurl, folderData, function( response ) {
                var folders = response.data;
                that.fielddata.folders = folders;
                that.folderLoading = false;
            });

            if(!this.fielddata.folderId) {
                this.getLists();
            }
        },
        getLists: function() {
            var that = this;
            this.listLoading = true;

            var listData = {
                'action': 'adfoin_get_clickup_lists',
                '_nonce': adfoin.nonce,
                'spaceId': this.fielddata.spaceId,
                'folderId': this.fielddata.folderId
            };

            jQuery.post( ajaxurl, listData, function( response ) {
                var lists = response.data;
                that.fielddata.lists = lists;
                that.listLoading = false;
            });
        },
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.workspaceId == 'undefined') {
            this.fielddata.workspaceId = '';
        }

        if (typeof this.fielddata.spaceId == 'undefined') {
            this.fielddata.spaceId = '';
        }

        if (typeof this.fielddata.folderId == 'undefined') {
            this.fielddata.folderId = '';
        }

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        this.workspaceLoading = true;

        var workspaceRequestData = {
            'action': 'adfoin_get_clickup_workspaces',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, workspaceRequestData, function( response ) {
            that.fielddata.workspaces = response.data;
            that.workspaceLoading = false;
        });

        if( this.fielddata.workspaceId ) {
            this.getSpaces();
        }

        if( this.fielddata.workspaceId && this.fielddata.spaceId ) {
            this.getFolders();
        }

        if( this.fielddata.workspaceId && this.fielddata.spaceId && this.fielddata.folderId ) {
            this.getLists();
        }
    },
    template: '#clickup-action-template'
});

Vue.component('everwebinar', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            webinarLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['register_webinar'], required: true, description: 'Required'},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['register_webinar'], required: true, description: 'Required'},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['register_webinar'], required: false},
                {type: 'text', value: 'ipAddress', title: 'IP Address', task: ['register_webinar'], required: false},
                {type: 'text', value: 'phoneCountryCode', title: 'Phone Country Code', task: ['register_webinar'], required: false},
                {type: 'text', value: 'phone', title: 'Phone Number', task: ['register_webinar'], required: false},
                {type: 'text', value: 'timezone', title: 'Timezone', task: ['register_webinar'], required: false},
                {type: 'text', value: 'date', title: 'Date', task: ['register_webinar'], required: false}
            ]

        }
    },
    methods: {
        getSchedule: function() {
            var that = this;
            this.webinarLoading = true;

            var scheduleData = {
                'action': 'adfoin_get_everwebinar_schedules',
                '_nonce': adfoin.nonce,
                'webinarId': this.fielddata.webinarId,
                'task': this.action.task
            };

            jQuery.post( ajaxurl, scheduleData, function( response ) {
                var schedules = response.data;
                that.fielddata.schedules = schedules;
                that.webinarLoading = false;
            });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.webinarId == 'undefined') {
            this.fielddata.webinarId = '';
        }

        if (typeof this.fielddata.scheduleId == 'undefined') {
            this.fielddata.scheduleId = '';
        }

        this.webinarLoading = true;

        var webinarRequestData = {
            'action': 'adfoin_get_everwebinar_webinars',
            '_nonce': adfoin.nonce
        };
        jQuery.post( ajaxurl, webinarRequestData, function( response ) {
            that.fielddata.webinars = response.data;
            that.webinarLoading = false;
        });
    },
    template: '#everwebinar-action-template'
});

Vue.component('webinarjam', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            webinarLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['register_webinar'], required: true, description: 'Required'},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['register_webinar'], required: true, description: 'Required'},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['register_webinar'], required: false},
                {type: 'text', value: 'ipAddress', title: 'IP Address', task: ['register_webinar'], required: false},
                {type: 'text', value: 'phoneCountryCode', title: 'Phone Country Code', task: ['register_webinar'], required: false},
                {type: 'text', value: 'phone', title: 'Phone Number', task: ['register_webinar'], required: false},
                {type: 'text', value: 'timezone', title: 'Timezone', task: ['register_webinar'], required: false},
                {type: 'text', value: 'date', title: 'Date', task: ['register_webinar'], required: false}
            ]

        }
    },
    methods: {
        getSchedule: function() {
            var that = this;
            this.webinarLoading = true;

            var scheduleData = {
                'action': 'adfoin_get_webinarjam_schedules',
                '_nonce': adfoin.nonce,
                'webinarId': this.fielddata.webinarId,
                'task': this.action.task
            };

            jQuery.post( ajaxurl, scheduleData, function( response ) {
                var schedules = response.data;
                that.fielddata.schedules = schedules;
                that.webinarLoading = false;
            });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.webinarId == 'undefined') {
            this.fielddata.webinarId = '';
        }

        if (typeof this.fielddata.scheduleId == 'undefined') {
            this.fielddata.scheduleId = '';
        }

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.firstName == 'undefined') {
            this.fielddata.firstName = '';
        }

        if (typeof this.fielddata.lastName == 'undefined') {
            this.fielddata.lastName = '';
        }

        this.webinarLoading = true;

        var webinarRequestData = {
            'action': 'adfoin_get_webinarjam_webinars',
            '_nonce': adfoin.nonce
        };
        jQuery.post( ajaxurl, webinarRequestData, function( response ) {
            that.fielddata.webinars = response.data;
            that.webinarLoading = false;
        });
    },
    template: '#webinarjam-action-template'
});

Vue.component('constantcontact', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe', 'unsubscribe'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'jobTitle', title: 'Job Title', task: ['subscribe'], required: false},
                {type: 'text', value: 'companyName', title: 'Company Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'workPhone', title: 'Work Phone', task: ['subscribe'], required: false},
                {type: 'text', value: 'homePhone', title: 'Home Phone', task: ['subscribe'], required: false},
                {type: 'text', value: 'mobilePhone', title: 'Cell Phone', task: ['subscribe'], required: false},
                {type: 'text', value: 'birthdayMonth', title: 'Birthday Month', task: ['subscribe'], required: false},
                {type: 'text', value: 'birthdayDay', title: 'Birthday Day', task: ['subscribe'], required: false},
                {type: 'text', value: 'anniversary', title: 'Anniversary', task: ['subscribe'], required: false},
                {type: 'text', value: 'addressType', title: 'Address Type', task: ['subscribe'], required: false, description: 'home, work, other'},
                {type: 'text', value: 'address1', title: 'Address Line 1', task: ['subscribe'], required: false},
                {type: 'text', value: 'city', title: 'City', task: ['subscribe'], required: false},
                {type: 'text', value: 'state', title: 'State', task: ['subscribe'], required: false},
                {type: 'text', value: 'zip', title: 'ZIP', task: ['subscribe'], required: false},
                {type: 'text', value: 'country', title: 'Country', task: ['subscribe'], required: false},
                
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        if (typeof this.fielddata.permission == 'undefined') {
            this.fielddata.permission = 'explicit';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_constantcontact_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#constantcontact-action-template'
});

Vue.component('verticalresponse', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'first_name', title: 'First Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'last_name', title: 'Last Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'home_phone', title: 'Home Phone', task: ['subscribe'], required: false},
                {type: 'text', value: 'mobile_phone', title: 'Mobile Phone', task: ['subscribe'], required: false},
                {type: 'text', value: 'work_phone', title: 'Work Phone', task: ['subscribe'], required: false},
                {type: 'text', value: 'fax', title: 'Fax', task: ['subscribe'], required: false},
                {type: 'text', value: 'birthdate', title: 'Birth Date', task: ['subscribe'], required: false},
                {type: 'text', value: 'gender', title: 'Gender', task: ['subscribe'], required: false},
                {type: 'text', value: 'marital_status', title: 'Marital Status', task: ['subscribe'], required: false},
                {type: 'text', value: 'company', title: 'Company', task: ['subscribe'], required: false},
                {type: 'text', value: 'title', title: 'Title', task: ['subscribe'], required: false},
                {type: 'text', value: 'website', title: 'Website', task: ['subscribe'], required: false},
                {type: 'text', value: 'street_address', title: 'Street Address', task: ['subscribe'], required: false},
                {type: 'text', value: 'extended_address', title: 'Extended Address', task: ['subscribe'], required: false},
                {type: 'text', value: 'city', title: 'City', task: ['subscribe'], required: false},
                {type: 'text', value: 'state', title: 'state', task: ['subscribe'], required: false},
                {type: 'text', value: 'postal_code', title: 'Postal Code', task: ['subscribe'], required: false},
                {type: 'text', value: 'country', title: 'Country', task: ['subscribe'], required: false},
            ]
        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_verticalresponse_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#verticalresponse-action-template'
});

Vue.component('zohocrm', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            userLoading: false,
            moduleLoading: false,
            fieldsLoading: false,
            fields: []
 
        }
    },
    methods: {
        getFields: function() {
            var that = this;
            this.moduleLoading = true;
            this.fields = [];
 
            var fieldsRequestData = {
                'action': 'adfoin_get_zohocrm_module_fields',
                '_nonce': adfoin.nonce,
                'module': this.fielddata.moduleId,
                'task': this.action.task
            };
 
            jQuery.post( ajaxurl, fieldsRequestData, function( response ) {
 
                if( response.success ) {
                    if( response.data ) {
                        response.data.map(function(single) {
                            that.fields.push( { type: 'text', value: single.key, title: single.value, task: ['subscribe'], required: false, description: single.description } );
                        });
 
                        that.moduleLoading = false;
                    }
                }
            });
        }
    },
    created: function() {
 
    },
    mounted: function() {
        var that = this;
 
        if (typeof this.fielddata.userId == 'undefined') {
            this.fielddata.userId = '';
        }

        if (typeof this.fielddata.moduleId == 'undefined') {
            this.fielddata.moduleId = '';
        }
 
        this.userLoading = true;
 
        var userRequestData = {
            'action': 'adfoin_get_zohocrm_users',
            '_nonce': adfoin.nonce
        };
 
        jQuery.post( ajaxurl, userRequestData, function( response ) {
            that.fielddata.users = response.data;
            that.userLoading = false;
        });

        this.moduleLoading = true;
 
        var moduleRequestData = {
            'action': 'adfoin_get_zohocrm_modules',
            '_nonce': adfoin.nonce
        };
 
        jQuery.post( ajaxurl, moduleRequestData, function( response ) {
            that.fielddata.modules = response.data;
            that.moduleLoading = false;
        });

        if( this.fielddata.moduleId ) {
            this.getFields();
        }
       
    },
    watch: {},
    template: '#zohocrm-action-template'
});

Vue.component('bigin', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            userLoading: false,
            moduleLoading: false,
            fieldsLoading: false,
            fields: []
 
        }
    },
    methods: {
        getFields: function() {
            var that = this;
            this.moduleLoading = true;
            this.fields = [];
 
            var fieldsRequestData = {
                'action': 'adfoin_get_bigin_module_fields',
                '_nonce': adfoin.nonce,
                'module': this.fielddata.moduleId,
                'task': this.action.task
            };
 
            jQuery.post( ajaxurl, fieldsRequestData, function( response ) {
 
                if( response.success ) {
                    if( response.data ) {
                        response.data.map(function(single) {
                            that.fields.push( { type: 'text', value: single.key, title: single.value, task: ['subscribe'], required: false, description: single.description } );
                        });
 
                        that.moduleLoading = false;
                    }
                }
            });
        }
    },
    created: function() {
 
    },
    mounted: function() {
        var that = this;
 
        if (typeof this.fielddata.userId == 'undefined') {
            this.fielddata.userId = '';
        }

        if (typeof this.fielddata.moduleId == 'undefined') {
            this.fielddata.moduleId = '';
        }
 
        this.userLoading = true;
 
        var userRequestData = {
            'action': 'adfoin_get_bigin_users',
            '_nonce': adfoin.nonce
        };
 
        jQuery.post( ajaxurl, userRequestData, function( response ) {
            that.fielddata.users = response.data;
            that.userLoading = false;
        });

        this.moduleLoading = true;
 
        var moduleRequestData = {
            'action': 'adfoin_get_bigin_modules',
            '_nonce': adfoin.nonce
        };
 
        jQuery.post( ajaxurl, moduleRequestData, function( response ) {
            that.fielddata.modules = response.data;
            that.moduleLoading = false;
        });

        if( this.fielddata.moduleId ) {
            this.getFields();
        }
       
    },
    watch: {},
    template: '#bigin-action-template'
});

Vue.component('zohocampaigns', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false}
            ]
        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_zohocampaigns_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });
    },
    template: '#zohocampaigns-action-template'
});

Vue.component('wordpress', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            postTypeLoading: false,
            selected: '',
            fields:[],
            title: '',
            slug: '',
            author: '',
            content: '',
            postMeta: '',
            username: '',
            email: '',
            firstName: '',
            lastName: '',
            website: '',
            password: '',
            role: '',
            userMeta: ''

        }
    },
    methods: {
        updateFieldValue: function(value) {
            if(this.selected || this.selected == 0) {
                if (this.fielddata[value] || "0" == this.fielddata[value]) {
                    this.fielddata[value] += ' {{' + this[value] + '}}';
                } else {
                    this.fielddata[value] = '{{' + this[value] + '}}';
                }
            }
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.postTypeId == 'undefined') {
            this.fielddata.postTypeId = '';
        }

        if (typeof this.fielddata.status == 'undefined') {
            this.fielddata.status = '';
        }

        if (typeof this.fielddata.role == 'undefined') {
            this.fielddata.role = '';
        }

        this.postTypeLoading = true;

        var postTypeRequestData = {
            'action': 'adfoin_get_wordpress_post_types',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, postTypeRequestData, function( response ) {
            that.fielddata.postTypes = response.data;
            that.postTypeLoading = false;
        });
    },
    watch: {},
    template: '#wordpress-action-template'
});

Vue.component('googlecalendar', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            selected: '',
            fields:[],
            title: '',
            description: '',
            start: '',
            end: '',
            timezone: '',
            location: '',
            attendees: ''
        }
    },
    methods: {
        updateFieldValue: function(value) {
            if(this.selected || this.selected == 0) {
                if (this.fielddata[value] || "0" == this.fielddata[value]) {
                    this.fielddata[value] += ' {{' + this[value] + '}}';
                } else {
                    this.fielddata[value] = '{{' + this[value] + '}}';
                }
            }
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.calendarId == 'undefined') {
            this.fielddata.calendarId = '';
        }

        if (typeof this.fielddata.allDayEvent == 'undefined') {
            this.fielddata.allDayEvent = false;
        }

        if (typeof this.fielddata.allDayEvent != 'undefined') {
            if(this.fielddata.allDayEvent == "false") {
                this.fielddata.allDayEvent = false;
            }
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_googlecalendar_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.calendarList = response.data;
            that.listLoading = false;
        });
    },
    watch: {},
    template: '#googlecalendar-action-template'
});

Vue.component('googlesheets', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            worksheetLoading: false,
            fields: []

        }
    },
    methods: {
        getWorksheets: function() {
            if(!this.fielddata.spreadsheetId) {
                return;
            }

            this.fielddata.worksheetList = [];
            this.fielddata.worksheetId = '';
            this.fields = [];

            var that = this;
            this.worksheetLoading = true;

            var listData = {
                'action': 'adfoin_googlesheets_get_worksheets',
                '_nonce': adfoin.nonce,
                'spreadsheetId': this.fielddata.spreadsheetId,
                'task': this.action.task
            };

            jQuery.post( ajaxurl, listData, function( response ) {
                that.fielddata.worksheetList = response.data;
                that.worksheetLoading = false;
            });
        },
        getHeaders: function() {
            if(this.fielddata.worksheetId == 0 || this.fielddata.worksheetId) {

                this.fields = [];
                var that = this;
                this.worksheetLoading = true;
                this.fielddata.worksheetName = this.fielddata.worksheetList[parseInt(this.fielddata.worksheetId)];

                var requestData = {
                    'action': 'adfoin_googlesheets_get_headers',
                    '_nonce': adfoin.nonce,
                    'spreadsheetId': this.fielddata.spreadsheetId,
                    'worksheetName': this.fielddata.worksheetName,
                    'task': this.action.task
                };

                jQuery.post( ajaxurl, requestData, function( response ) {
                    if(response.success) {
                        if(response.data) {
                            for(var key in response.data) {
                                that.fielddata[key] = '';
                                that.fields.push({type: 'text', value: key, title: response.data[key], task: ['add_row'], required: false});
                            }
                        }
                    }

                    that.worksheetLoading = false;
                });
            }


        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.spreadsheetId == 'undefined') {
            this.fielddata.spreadsheetId = '';
        }

        if (typeof this.fielddata.worksheetId == 'undefined') {
            this.fielddata.worksheetId = '';
        }

        if(typeof this.fielddata.worksheetName == 'undefined') {
            this.fielddata.worksheetName = '';
        }

        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_spreadsheet_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.spreadsheetList = response.data;
            that.listLoading = false;
        });

        if(this.fielddata.spreadsheetId && this.fielddata.worksheetName ) {
            var that = this;
            this.worksheetLoading = true;

            var requestData = {
                'action': 'adfoin_googlesheets_get_headers',
                '_nonce': adfoin.nonce,
                'spreadsheetId': this.fielddata.spreadsheetId,
                'worksheetName': this.fielddata.worksheetName,
                'task': this.action.task
            };

            jQuery.post( ajaxurl, requestData, function( response ) {
                if(response.success) {
                    if(response.data) {
                        for(var key in response.data) {
                            that.fields.push({type: 'text', value: key, title: response.data[key], task: ['add_row'], required: false});
                        }
                    }
                }

                that.worksheetLoading = false;
            });
        }

        if(this.fielddata.worksheetList) {
            this.fielddata.worksheetList = JSON.parse( this.fielddata.worksheetList.replace(/\\/g, '') );
        }
    },
    watch: {},
    template: '#googlesheets-action-template'
});

Vue.component('smartsheet', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: []
        }
    },
    methods: {
        getFields: function() {
            var that = this;
            this.listLoading = true;

            var listData = {
                'action': 'adfoin_get_smartsheet_fields',
                '_nonce': adfoin.nonce,
                'listId': this.fielddata.listId,
                'task': this.action.task
            };

            jQuery.post( ajaxurl, listData, function( response ) {
                if(response.success) {
                    if(response.data) {
                        for(var key in response.data) {
                            that.fields.push({type: 'text', value: key, title: response.data[key], task: ['add_row'], required: false});
                        }
                    }
                }

                that.listLoading = false;
            });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }


        this.listLoading = true;

        var listRequestData = {
            'action': 'adfoin_get_smartsheet_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, listRequestData, function( response ) {
            that.fielddata.list = response.data;
            that.listLoading = false;
        });

        if(this.fielddata.listId ) {
            var that = this;
            this.listLoading = true;

            var listData = {
                'action': 'adfoin_get_smartsheet_fields',
                '_nonce': adfoin.nonce,
                'listId': this.fielddata.listId,
                'task': this.action.task
            };

            jQuery.post( ajaxurl, listData, function( response ) {
                if(response.success) {
                    if(response.data) {
                        for(var key in response.data) {
                            that.fields.push({type: 'text', value: key, title: response.data[key], task: ['add_row'], required: false});
                        }
                    }
                }

                that.listLoading = false;
            });
        }
    },
    template: '#smartsheet-action-template'
});

Vue.component('airtable', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            baseLoading: false,
            tableLoading: false,
            fieldLoading: false,
            fields: []
        }
    },
    methods: {
        getTables: function() {
            var that = this;
            this.tableLoading = true;

            var tableData = {
                'action': 'adfoin_get_airtable_tables',
                '_nonce': adfoin.nonce,
                'baseId': this.fielddata.baseId,
                'task': this.action.task
            };

            jQuery.post( ajaxurl, tableData, function( response ) {
                if(response.success) {
                    if(response.data) {
                        var tables = response.data;
                        that.fielddata.tables = tables;
                        that.tableLoading = false;
                    }
                }
            });
        },
        getFields: function() {
            var that = this;
            this.fieldLoading = true;

            var fieldData = {
                'action': 'adfoin_get_airtable_fields',
                '_nonce': adfoin.nonce,
                'baseId': this.fielddata.baseId,
                'tableId': this.fielddata.tableId,
                'task': this.action.task
            };

            jQuery.post( ajaxurl, fieldData, function( response ) {
                if(response.success) {
                    if(response.data) {
                        response.data.map(function(single) {
                            that.fields.push( { type: 'text', value: single.key, title: single.value, task: ['add_row'], required: false, description: single.description } );
                        });
                    }
                }

                that.fieldLoading = false;
            });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.baseId == 'undefined') {
            this.fielddata.baseId = '';
        }

        if (typeof this.fielddata.tableId == 'undefined') {
            this.fielddata.tableId = '';
        }

        this.baseLoading = true;

        var baseRequestData = {
            'action': 'adfoin_get_airable_bases',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, baseRequestData, function( response ) {
            that.fielddata.bases = response.data;
            that.baseLoading = false;
        });

        if(this.fielddata.baseId ) {
            this.getTables();

            if(this.fielddata.tableId ) {
                this.getFields();
            }
        }
    },
    template: '#airtable-action-template'
});

Vue.component('zohosheet', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            workbookLoading: false,
            worksheetLoading: false,
            fieldLoading: false,
            fields: []
        }
    },
    methods: {
        getWorksheets: function() {
            var that = this;
            this.worksheetLoading = true;

            var worksheetData = {
                'action': 'adfoin_get_zohosheet_worksheets',
                '_nonce': adfoin.nonce,
                'workbookId': this.fielddata.workbookId,
                'task': this.action.task
            };

            jQuery.post( ajaxurl, worksheetData, function( response ) {
                if(response.success) {
                    if(response.data) {
                        var worksheets = response.data;
                        that.fielddata.worksheets = worksheets;
                        that.worksheetLoading = false;
                    }
                }
            });
        },
        getFields: function() {
            var that = this;
            this.fieldLoading = true;

            var fieldData = {
                'action': 'adfoin_get_zohosheet_fields',
                '_nonce': adfoin.nonce,
                'workbookId': this.fielddata.workbookId,
                'worksheetId': this.fielddata.worksheetId,
                'task': this.action.task
            };

            jQuery.post( ajaxurl, fieldData, function( response ) {
                if(response.success) {
                    if(response.data) {
                        response.data.map(function(single) {
                            that.fields.push( { type: 'text', value: single.key, title: single.value, task: ['add_row'], required: false, description: single.description } );
                        });
                    }
                }

                that.fieldLoading = false;
            });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.workbookId == 'undefined') {
            this.fielddata.workbookId = '';
        }

        if (typeof this.fielddata.worksheetId == 'undefined') {
            this.fielddata.worksheetId = '';
        }

        this.workbookLoading = true;

        var workbookRequestData = {
            'action': 'adfoin_get_zohosheet_workbooks',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, workbookRequestData, function( response ) {
            that.fielddata.workbooks = response.data;
            that.workbookLoading = false;
        });

        if(this.fielddata.workbookId ) {
            this.getWorksheets();

            if(this.fielddata.worksheetId ) {
                this.getFields();
            }
        }
    },
    template: '#zohosheet-action-template'
});

Vue.component('pipedrive', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            ownerLoading: false,
            worksheetLoading: false,
            fields: [
                {type: 'text', value: 'note_content', title: 'Content [Note]', task: ['add_ocdna'], required: false, description: ''},
                {type: 'text', value: 'act_subject', title: 'Subject [Activity]', task: ['add_ocdna'], required: false, description: 'Required for creating an activity'},
                {type: 'text', value: 'act_type', title: 'Type [Activity]', task: ['add_ocdna'], required: false, description: 'Example: call, meeting, task, deadline, email, lunch'},
                {type: 'text', value: 'act_due_date', title: 'Due Date [Activity]', task: ['add_ocdna'], required: false, description: 'Format: YYYY-MM-DD'},
                {type: 'text', value: 'act_after_days', title: 'Due Date After X days [Activity]', task: ['add_ocdna'], required: false, description: 'Accepts numeric value. If filled, due date will be calculated and set'},
                {type: 'text', value: 'act_due_time', title: 'Due Time [Activity]', task: ['add_ocdna'], required: false, description: 'Format: HH:MM'},
                {type: 'text', value: 'act_duration', title: 'Duration [Activity]', task: ['add_ocdna'], required: false, description: 'Format: HH:MM'},
                {type: 'text', value: 'act_note', title: 'Note [Activity]', task: ['add_ocdna'], required: false, description: ''},
            ]

        }
    },
    methods: {},
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.duplicate == 'undefined') {
            this.fielddata.duplicate = false;
        }

        if (typeof this.fielddata.duplicate != 'undefined') {
            if(this.fielddata.duplicate == "false") {
                this.fielddata.duplicate = false;
            }
        }

        if (typeof this.fielddata.owner == 'undefined') {
            this.fielddata.owner = '';
        }

        this.ownerLoading = true;

        var ownerRequestData = {
            'action': 'adfoin_get_pipedrive_owner_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, ownerRequestData, function( response ) {

            that.fielddata.ownerList = response.data;
            that.ownerLoading = false;
        });

        var orgRequestData = {
            'action': 'adfoin_get_pipedrive_org_fields',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, orgRequestData, function( response ) {

            if( response.success ) {
                if( response.data ) {
                    response.data.map(function(single) {
                        that.fields.push( { type: 'text', value: single.key, title: single.value, task: ['add_ocdna'], required: false, description: single.description } );
                    });
                }
            }
        });

        var personRequestData = {
            'action': 'adfoin_get_pipedrive_person_fields',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, personRequestData, function( response ) {

            if( response.success ) {
                if( response.data ) {
                    response.data.map(function(single) {
                        that.fields.push( { type: 'text', value: single.key, title: single.value, task: ['add_ocdna'], required: false, description: single.description } );
                    });
                }
            }
        });

        var dealRequestData = {
            'action': 'adfoin_get_pipedrive_deal_fields',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, dealRequestData, function( response ) {

            if( response.success ) {
                if( response.data ) {
                    response.data.map(function(single) {
                        that.fields.push( { type: 'text', value: single.key, title: single.value, task: ['add_ocdna'], required: false, description: single.description } );
                    });
                }
            }
        });

    },
    watch: {},
    template: '#pipedrive-action-template'
});

Vue.component('capsulecrm', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            ownerLoading: false,
            fieldsLoading: false,
            fields: []

        }
    },
    methods: {
        getFields: function() {
            this.fields = [];
            var that = this;
            this.fieldsLoading = true;
            var selectedObjects = [];
            if(this.fielddata.organisation__chosen) {selectedObjects.push('organisation')}
            if(this.fielddata.person__chosen) {selectedObjects.push('person')}
            if(this.fielddata.opportunity__chosen) {selectedObjects.push('opportunity')}
            if(this.fielddata.case__chosen) {selectedObjects.push('case')}
            if(this.fielddata.task__chosen) {selectedObjects.push('task')}

            var allFieldsRequestData = {
                'action': 'adfoin_get_capsulecrm_all_fields',
                '_nonce': adfoin.nonce,
                'selectedObjects': selectedObjects,
                'task': this.action.task
            };

            jQuery.post( ajaxurl, allFieldsRequestData, function( response ) {

                if( response.success ) {
                    if( response.data ) {
                        response.data.map(function(single) {
                            that.fields.push( { type: 'text', value: single.key, title: single.value, task: ['add_party'], required: false, description: single.description } );
                        });

                        that.fieldsLoading = false;
                    }
                }
            });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.owner == 'undefined') {
            this.fielddata.owner = '';
        }

        this.ownerLoading = true;

        var ownerRequestData = {
            'action': 'adfoin_get_capsulecrm_owner_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, ownerRequestData, function( response ) {

            that.fielddata.ownerList = response.data;
            that.ownerLoading = false;
        });

        if (typeof this.fielddata.organisation__chosen == 'undefined') {
            this.fielddata.organisation__chosen = false;
        }

        if (typeof this.fielddata.organisation__chosen != 'undefined') {
            this.fielddata.organisation__chosen = (this.fielddata.organisation__chosen === "true");
        }

        if (typeof this.fielddata.person__chosen == 'undefined') {
            this.fielddata.person__chosen = false;
        }

        if (typeof this.fielddata.person__chosen != 'undefined') {
            this.fielddata.person__chosen = (this.fielddata.person__chosen === "true");
        }

        if (typeof this.fielddata.opportunity__chosen == 'undefined') {
            this.fielddata.opportunity__chosen = false;
        }

        if (typeof this.fielddata.opportunity__chosen != 'undefined') {
            this.fielddata.opportunity__chosen = (this.fielddata.opportunity__chosen === "true");
        }

        if (typeof this.fielddata.case__chosen == 'undefined') {
            this.fielddata.case__chosen = false;
        }

        if (typeof this.fielddata.case__chosen != 'undefined') {
            this.fielddata.case__chosen = (this.fielddata.case__chosen === "true");
        }

        if (typeof this.fielddata.task__chosen == 'undefined') {
            this.fielddata.task__chosen = false;
        }

        if (typeof this.fielddata.task__chosen != 'undefined') {
            this.fielddata.task__chosen = (this.fielddata.task__chosen === "true");
        }

        if( this.fielddata.organisation__chosen || this.fielddata.person__chosen || this.fielddata.opportunity__chosen || this.fielddata.case__chosen || this.fielddata.task__chosen ) {
            this.getFields();
        }

        
    },
    watch: {},
    template: '#capsulecrm-action-template'
});

Vue.component('flowlu', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            ownerLoading: false,
            fieldsLoading: false,
            fields: []

        }
    },
    methods: {
        getFields: function() {
            this.fields = [];
            var that = this;
            this.fieldsLoading = true;
            var selectedObjects = [];
            if(this.fielddata.organization__chosen) {selectedObjects.push('organization')}
            if(this.fielddata.contact__chosen) {selectedObjects.push('contact')}
            if(this.fielddata.opportunity__chosen) {selectedObjects.push('opportunity')}

            var allFieldsRequestData = {
                'action': 'adfoin_get_flowlu_all_fields',
                '_nonce': adfoin.nonce,
                'selectedObjects': selectedObjects,
                'task': this.action.task
            };

            jQuery.post( ajaxurl, allFieldsRequestData, function( response ) {

                if( response.success ) {
                    if( response.data ) {
                        response.data.map(function(single) {
                            that.fields.push( { type: 'text', value: single.key, title: single.value, task: ['add_record'], required: false, description: single.description } );
                        });

                        that.fieldsLoading = false;
                    }
                }
            });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.owner == 'undefined') {
            this.fielddata.owner = '';
        }

        this.ownerLoading = true;

        var ownerRequestData = {
            'action': 'adfoin_get_flowlu_owner_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, ownerRequestData, function( response ) {

            that.fielddata.ownerList = response.data;
            that.ownerLoading = false;
        });

        if (typeof this.fielddata.organization__chosen == 'undefined') {
            this.fielddata.organization__chosen = false;
        }

        if (typeof this.fielddata.organization__chosen != 'undefined') {
            this.fielddata.organization__chosen = (this.fielddata.organization__chosen === "true");
        }

        if (typeof this.fielddata.contact__chosen == 'undefined') {
            this.fielddata.contact__chosen = false;
        }

        if (typeof this.fielddata.contact__chosen != 'undefined') {
            this.fielddata.contact__chosen = (this.fielddata.contact__chosen === "true");
        }

        if (typeof this.fielddata.opportunity__chosen == 'undefined') {
            this.fielddata.opportunity__chosen = false;
        }

        if (typeof this.fielddata.opportunity__chosen != 'undefined') {
            this.fielddata.opportunity__chosen = (this.fielddata.opportunity__chosen === "true");
        }

        if( this.fielddata.organization__chosen || this.fielddata.contact__chosen || this.fielddata.opportunity__chosen ) {
            this.getFields();
        }

        
    },
    watch: {},
    template: '#flowlu-action-template'
});

Vue.component('salesflare', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            ownerLoading: false,
            fieldsLoading: false,
            fields: []

        }
    },
    methods: {
        getFields: function() {
            this.fields = [];
            var that = this;
            this.fieldsLoading = true;
            var selectedObjects = [];
            if(this.fielddata.account__chosen) {selectedObjects.push('account')}
            if(this.fielddata.contact__chosen) {selectedObjects.push('contact')}
            if(this.fielddata.opportunity__chosen) {selectedObjects.push('opportunity')}
            if(this.fielddata.task__chosen) {selectedObjects.push('task')}

            var allFieldsRequestData = {
                'action': 'adfoin_get_salesflare_all_fields',
                '_nonce': adfoin.nonce,
                'selectedObjects': selectedObjects,
                'task': this.action.task
            };

            jQuery.post( ajaxurl, allFieldsRequestData, function( response ) {

                if( response.success ) {
                    if( response.data ) {
                        response.data.map(function(single) {
                            that.fields.push( { type: 'text', value: single.key, title: single.value, task: ['add_data'], required: false, description: single.description } );
                        });

                        that.fieldsLoading = false;
                    }
                }
            });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.owner == 'undefined') {
            this.fielddata.owner = '';
        }

        this.ownerLoading = true;

        var ownerRequestData = {
            'action': 'adfoin_get_salesflare_owner_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, ownerRequestData, function( response ) {

            that.fielddata.ownerList = response.data;
            that.ownerLoading = false;
        });

        if (typeof this.fielddata.account__chosen == 'undefined') {
            this.fielddata.account__chosen = false;
        }

        if (typeof this.fielddata.account__chosen != 'undefined') {
            this.fielddata.account__chosen = (this.fielddata.account__chosen === "true");
        }

        if (typeof this.fielddata.contact__chosen == 'undefined') {
            this.fielddata.contact__chosen = false;
        }

        if (typeof this.fielddata.contact__chosen != 'undefined') {
            this.fielddata.person__chosen = (this.fielddata.contact__chosen === "true");
        }

        if (typeof this.fielddata.opportunity__chosen == 'undefined') {
            this.fielddata.opportunity__chosen = false;
        }

        if (typeof this.fielddata.opportunity__chosen != 'undefined') {
            this.fielddata.opportunity__chosen = (this.fielddata.opportunity__chosen === "true");
        }

        if (typeof this.fielddata.task__chosen == 'undefined') {
            this.fielddata.task__chosen = false;
        }

        if (typeof this.fielddata.task__chosen != 'undefined') {
            this.fielddata.task__chosen = (this.fielddata.task__chosen === "true");
        }

        if( this.fielddata.account__chosen || this.fielddata.contact__chosen || this.fielddata.opportunity__chosen || this.fielddata.task__chosen ) {
            this.getFields();
        }

        
    },
    watch: {},
    template: '#salesflare-action-template'
});

Vue.component('vtiger', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            ownerLoading: false,
            fieldsLoading: false,
            fields: []

        }
    },
    methods: {
        getFields: function() {
            this.fields = [];
            var that = this;
            this.fieldsLoading = true;
            var selectedObjects = [];
            if(this.fielddata.organization__chosen) {selectedObjects.push('organization')}
            if(this.fielddata.contact__chosen) {selectedObjects.push('contact')}
            if(this.fielddata.action__chosen) {selectedObjects.push('action')}
            // if(this.fielddata.case__chosen) {selectedObjects.push('case')}
            // if(this.fielddata.task__chosen) {selectedObjects.push('task')}

            var allFieldsRequestData = {
                'action': 'adfoin_get_vtiger_all_fields',
                '_nonce': adfoin.nonce,
                'selectedObjects': selectedObjects,
                'task': this.action.task
            };

            jQuery.post( ajaxurl, allFieldsRequestData, function( response ) {

                if( response.success ) {
                    if( response.data ) {
                        response.data.map(function(single) {
                            that.fields.push( { type: 'text', value: single.key, title: single.value, task: ['add_fields'], required: false, description: single.description } );
                        });

                        that.fieldsLoading = false;
                    }
                }
            });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.owner == 'undefined') {
            this.fielddata.owner = '';
        }

        this.ownerLoading = true;

        var ownerRequestData = {
            'action': 'adfoin_get_vtiger_owner_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, ownerRequestData, function( response ) {

            that.fielddata.ownerList = response.data;
            that.ownerLoading = false;
        });

        if (typeof this.fielddata.organization__chosen == 'undefined') {
            this.fielddata.organization__chosen = false;
        }

        if (typeof this.fielddata.organization__chosen != 'undefined') {
            this.fielddata.organization__chosen = (this.fielddata.organization__chosen === "true");
        }

        if (typeof this.fielddata.contact__chosen == 'undefined') {
            this.fielddata.contact__chosen = false;
        }

        if (typeof this.fielddata.contact__chosen != 'undefined') {
            this.fielddata.contact__chosen = (this.fielddata.contact__chosen === "true");
        }

        if (typeof this.fielddata.action__chosen == 'undefined') {
            this.fielddata.action__chosen = false;
        }

        if (typeof this.fielddata.action__chosen != 'undefined') {
            this.fielddata.action__chosen = (this.fielddata.action__chosen === "true");
        }

        // if (typeof this.fielddata.case__chosen == 'undefined') {
        //     this.fielddata.case__chosen = false;
        // }

        // if (typeof this.fielddata.case__chosen != 'undefined') {
        //     this.fielddata.case__chosen = (this.fielddata.case__chosen === "true");
        // }

        // if (typeof this.fielddata.task__chosen == 'undefined') {
        //     this.fielddata.task__chosen = false;
        // }

        // if (typeof this.fielddata.task__chosen != 'undefined') {
        //     this.fielddata.task__chosen = (this.fielddata.task__chosen === "true");
        // }

        if( this.fielddata.organization__chosen || this.fielddata.contact__chosen || this.fielddata.case__chosen || this.fielddata.case__chosen || this.fielddata.task__chosen ) {
            this.getFields();
        }

        
    },
    watch: {},
    template: '#vtiger-action-template'
});

Vue.component('hubspot', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            contactLoading: false,
            fields: []

        }
    },
    methods: {},
    created: function() {

    },
    mounted: function() {
        var that = this;

        var contactRequestData = {
            'action': 'adfoin_get_hubspot_contact_fields',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, contactRequestData, function( response ) {

            if( response.success ) {
                if( response.data ) {
                    response.data.map(function(single) {
                        that.fields.push( { type: 'text', value: single.key, title: single.value, task: ['add_contact'], required: false, description: single.description } );
                    });
                }
            }
        });

    },
    watch: {},
    template: '#hubspot-action-template'
});

Vue.component('autopilotnew', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['subscribe'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['subscribe'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['subscribe'], required: false}
            ]
        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {

    },
    template: '#autopilotnew-action-template'
});

Vue.component('omnisend', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['add_contact'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'phone', title: 'Phone', task: ['add_contact'], required: false},
                {type: 'text', value: 'address', title: 'Address', task: ['add_contact'], required: false},
                {type: 'text', value: 'city', title: 'City', task: ['add_contact'], required: false},
                {type: 'text', value: 'state', title: 'State', task: ['add_contact'], required: false},
                {type: 'text', value: 'zip', title: 'ZIP', task: ['add_contact'], required: false},
                {type: 'text', value: 'country', title: 'Country', task: ['add_contact'], required: false},
                {type: 'text', value: 'birthday', title: 'Birthday', task: ['add_contact'], required: false, description: 'required format YYYY-MM-DD'},
                {type: 'text', value: 'gender', title: 'Gender', task: ['add_contact'], required: false, description: 'e.g. Male, Female'}
            ]

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        },
    template: '#omnisend-action-template'
});

Vue.component('mailbluster', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['add_contact'], required: true},
                {type: 'text', value: 'firstName', title: 'First Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'lastName', title: 'Last Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'fullName', title: 'Full Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'timezone', title: 'Timezone', task: ['add_contact'], required: false},
                {type: 'text', value: 'ipAddress', title: 'IP Address', task: ['add_contact'], required: false},
            ]

        }
    },
    methods: {
    },
    created: function() {
    },
    mounted: function() {
        if (typeof this.fielddata.doptin == 'undefined') {
            this.fielddata.doptin = false;
        }

        if (typeof this.fielddata.doptin != 'undefined') {
            if(this.fielddata.doptin == "false") {
                this.fielddata.doptin = false;
            }
        }
    },
    template: '#mailbluster-action-template'
});

Vue.component('close', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            ownerLoading: false,
            fields: []

        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.owner == 'undefined') {
            this.fielddata.owner = '';
        }

        var allRequestData = {
            'action': 'adfoin_get_close_all_fields',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, allRequestData, function( response ) {

            if( response.success ) {
                if( response.data ) {
                    response.data.map(function(single) {
                        that.fields.push( { type: 'text', value: single.key, title: single.value, task: ['add_lead'], required: false, description: single.description } );
                    });
                }
            }
        });
    },
    template: '#close-action-template'
});

Vue.component('insightly', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            ownerLoading: false,
            fieldsLoading: false,
            fields: []
        }
    },
    methods: {},
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.owner == 'undefined') {
            this.fielddata.owner = '';
        }

        this.ownerLoading = true;

        var ownerRequestData = {
            'action': 'adfoin_get_insightly_owner_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, ownerRequestData, function( response ) {
            that.fielddata.ownerList = response.data;
        });

        var allRequestData = {
            'action': 'adfoin_get_insightly_all_fields',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, allRequestData, function( response ) {

            if( response.success ) {
                if( response.data ) {
                    response.data.map(function(single) {
                        that.fields.push( { type: 'text', value: single.key, title: single.value, task: ['add_contact'], required: false, description: single.description } );
                    });

                    that.ownerLoading = false;
                }
            }
        });

    },
    watch: {},
    template: '#insightly-action-template'
});

Vue.component('copper', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            ownerLoading: false,
            fieldsLoading: false,
            fields: []
        }
    },
    methods: {},
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.owner == 'undefined') {
            this.fielddata.owner = '';
        }

        this.ownerLoading = true;

        var ownerRequestData = {
            'action': 'adfoin_get_copper_owner_list',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, ownerRequestData, function( response ) {

            that.fielddata.ownerList = response.data;
        });

        var allRequestData = {
            'action': 'adfoin_get_copper_all_fields',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, allRequestData, function( response ) {

            if( response.success ) {
                if( response.data ) {
                    response.data.map(function(single) {
                        that.fields.push( { type: 'text', value: single.key, title: single.value, task: ['add_contact'], required: false, description: single.description } );
                    });

                    that.ownerLoading = false;
                }
            }
        });

    },
    watch: {},
    template: '#copper-action-template'
});

Vue.component('freshsales', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            listLoading: false,
            fields: []
        }
    },
    methods: {
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        var accountRequestData = {
            'action': 'adfoin_get_freshsales_account_fields',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, accountRequestData, function( response ) {

            if( response.success ) {
                if( response.data ) {
                    response.data.map(function(single) {
                        that.fields.push( { type: 'text', value: single.key, title: single.value, task: ['add_ocdna'], required: false, description: single.description } );
                    });
                }
            }
        });

        var contactRequestData = {
            'action': 'adfoin_get_freshsales_contact_fields',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, contactRequestData, function( response ) {

            if( response.success ) {
                if( response.data ) {
                    response.data.map(function(single) {
                        that.fields.push( { type: 'text', value: single.key, title: single.value, task: ['add_ocdna'], required: false, description: single.description } );
                    });
                }
            }
        });

        var dealRequestData = {
            'action': 'adfoin_get_freshsales_deal_fields',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, dealRequestData, function( response ) {

            if( response.success ) {
                if( response.data ) {
                    response.data.map(function(single) {
                        that.fields.push( { type: 'text', value: single.key, title: single.value, task: ['add_ocdna'], required: false, description: single.description } );
                    });
                }
            }
        });
    },
    template: '#freshsales-action-template'
});

Vue.component('campaignmonitor', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            accountLoading: false,
            listLoading: false,
            fields: [
                {type: 'text', value: 'email', title: 'Email', task: ['create_subscriber'], required: true},
                {type: 'text', value: 'name', title: 'Name', task: ['create_subscriber'], required: false}
            ]
        }
    },
    methods: {
        getList: function() {
            var that = this;
            this.listLoading = true;

            var listData = {
                'action': 'adfoin_get_campaignmonitor_list',
                '_nonce': adfoin.nonce,
                'accountId': this.fielddata.accountId,
                'task': this.action.task
            };

            jQuery.post( ajaxurl, listData, function( response ) {
                var list = response.data;
                that.fielddata.list = list;
                that.listLoading = false;
            });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;

        if (typeof this.fielddata.accountId == 'undefined') {
            this.fielddata.accountId = '';
        }

        if (typeof this.fielddata.listId == 'undefined') {
            this.fielddata.listId = '';
        }

        if (typeof this.fielddata.email == 'undefined') {
            this.fielddata.email = '';
        }

        if (typeof this.fielddata.name == 'undefined') {
            this.fielddata.name = '';
        }

        this.accountLoading = true;

        var accountRequestData = {
            'action': 'adfoin_get_campaignmonitor_accounts',
            '_nonce': adfoin.nonce
        };

        jQuery.post( ajaxurl, accountRequestData, function( response ) {
            that.fielddata.accounts = response.data;
            that.accountLoading = false;
        });

        if(this.fielddata.accountId){
            this.getList();
        }
    },
    template: '#campaignmonitor-action-template'
});

Vue.component('clinchpad', {
    props: ["trigger", "action", "fielddata"],
    data: function () {
        return {
            userLoading: false,
            pipelineLoading: false,
            stageLoading: false,
            fields: [
                {type: 'text', value: 'lead', title: 'Lead Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'value', title: 'Lead Value', task: ['add_contact'], required: false},
                {type: 'text', value: 'note', title: 'Note', task: ['add_contact'], required: false},
                {type: 'text', value: 'name', title: 'Contact Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'designation', title: 'Designation', task: ['add_contact'], required: false},
                {type: 'text', value: 'email', title: 'Email', task: ['add_contact'], required: false},
                {type: 'text', value: 'phone', title: 'Phone', task: ['add_contact'], required: false},
                {type: 'text', value: 'address', title: 'Address', task: ['add_contact'], required: false},
                {type: 'text', value: 'organization', title: 'Organization', task: ['add_contact'], required: false},
                {type: 'text', value: 'org_email', title: 'Organization Email', task: ['add_contact'], required: false},
                {type: 'text', value: 'org_phone', title: 'Organization Phone', task: ['add_contact'], required: false},
                {type: 'text', value: 'website', title: 'Website', task: ['add_contact'], required: false},                
                {type: 'text', value: 'org_address', title: 'Organization Address', task: ['add_contact'], required: false},
                {type: 'text', value: 'product_name', title: 'Product Name', task: ['add_contact'], required: false},
                {type: 'text', value: 'product_price', title: 'Product Price', task: ['add_contact'], required: false}
            ]

        }
    },
    methods: {
        getStage: function() {
            var that = this;
            this.stageLoading = true;

            var stageData = {
                'action': 'adfoin_get_clinchpad_stage',
                '_nonce': adfoin.nonce,
                'pipelineId': this.fielddata.pipelineId,
                'task': this.action.task
            };

            jQuery.post( ajaxurl, stageData, function( response ) {
                var stages = response.data;
                that.fielddata.stages = stages;
                that.stageLoading = false;
            });
        }
    },
    created: function() {

    },
    mounted: function() {
        var that = this;


        if (typeof this.fielddata.userId == 'undefined') {
            this.fielddata.userId = '';
        }

        if (typeof this.fielddata.stageId == 'undefined') {
            this.fielddata.stageId = '';
        }

        this.userLoading = true;

        var userRequestData = {
            'action': 'adfoin_get_clinchpad_user',
            '_nonce': adfoin.nonce
        };
        
        jQuery.post( ajaxurl, userRequestData, function( response ) {
            
            that.fielddata.userList = response.data;
            that.userLoading = false;
        });

        if (typeof this.fielddata.pipelineId == 'undefined') {
            this.fielddata.pipelineId = '';
        }

        this.pipelineLoading = true;

        var pipelineRequestData = {
            'action': 'adfoin_get_clinchpad_pipeline',
            '_nonce': adfoin.nonce
        };
        
        jQuery.post( ajaxurl, pipelineRequestData, function( response ) {
            
            that.fielddata.pipelineList = response.data;
            that.pipelineLoading = false;
        });

        if( this.fielddata.pipelineId ) {
            this.getStage();
        }
    },
    template: '#clinchpad-action-template'
});

// Vue.config.productionTip = false;

var adfoinNewIntegration = new Vue({
    el: '#adfoin-new-integration',
    data: {
        trigger: {
            integrationTitle: '',
            formProviderId: '',
            forms: '',
            formId: '',
            formName: '',
            formFields: [],
            extraFields: {}
        },
        formValidated: 0,
        actionValidated: 0,
        action: {
            actionProviderId: '',
            task: '',
            cl: {
                active: "no",
                match: "any",
                conditions: []
            },
            tasks: []
        },
        formLoading: false,
        fieldLoading: false,
        actionLoading: false,
        functionLoading: false,
        fieldData: {}

    },
    methods: {
        changeFormProvider: function(event) {
            
            this.formValidated  = 1;
            adfoinNewIntegration.formLoading = true;
            this.trigger.formId = '';
            if(this.trigger.formProviderId == '') {
                adfoinNewIntegration.trigger.forms = '';
                adfoinNewIntegration.formValidated = 0;
                adfoinNewIntegration.formLoading = false;
            }

            var formProviderData = {
                'action': 'adfoin_get_forms',
                'nonce': adfoin.nonce,
                'formProviderId': this.trigger.formProviderId
            };

            jQuery.post( ajaxurl, formProviderData, function( response ) {
                adfoinNewIntegration.trigger.forms = response.data;
                adfoinNewIntegration.formValidated = 0;
                adfoinNewIntegration.formLoading = false;
            });
        },
        updateFormProvider: function() {
            var that = this;
            this.formLoading = true;

            var formProviderData = {
                'action': 'adfoin_get_forms',
                'nonce': adfoin.nonce,
                'formProviderId': this.trigger.formProviderId
            };

            jQuery.post( ajaxurl, formProviderData, function( response ) {
                adfoinNewIntegration.trigger.forms = response.data;
                that.formLoading = false;
            });
        },
        changedForm: function(event) {
            adfoinNewIntegration.fieldLoading = true;

            var formData = {
                'action': 'adfoin_get_form_fields',
                'formProviderId': this.trigger.formProviderId,
                'nonce': adfoin.nonce,
                'formId': this.trigger.formId
            };

            jQuery.post( ajaxurl, formData, function( response ) {
                var values             = response.data;
                adfoinNewIntegration.trigger.formFields = values;
                adfoinNewIntegration.fieldLoading = false;
            });
        },
        changeActionProvider: function(event) {
            this.actionValidated  = 1;
            adfoinNewIntegration.actionLoading = true;
            this.action.task = '';
            if(this.actionProviderId == '') {
                adfoinNewIntegration.action.tasks = '';
                adfoinNewIntegration.actionValidated = 0;
                adfoinNewIntegration.actionLoading = false;
            }

            var actionProviderData = {
                'action': 'adfoin_get_tasks',
                'nonce': adfoin.nonce,
                'actionProviderId': this.action.actionProviderId
            };

            jQuery.post( ajaxurl, actionProviderData, function( response ) {
                adfoinNewIntegration.action.tasks         = response.data;
                adfoinNewIntegration.actionValidated = 0;
                adfoinNewIntegration.actionLoading = false;
            });
        }
    },
    calculated: {
        calculatedTrigger: function() {
            return JSON.stringify( this.trigger );
        },
        calculatedAction: function() {
            return JSON.stringify( this.action );
        }
    },
    mounted: function() {
        var that = this;
        if (typeof integrationTitle != 'undefined') {
            this.trigger.integrationTitle = integrationTitle;
        }

        if (typeof triggerData != 'undefined') {
            this.trigger = triggerData;
        }


        if (typeof actionData != 'undefined') {
            this.action = actionData;
        }


        if (typeof fieldData != 'undefined') {
            this.fieldData = fieldData;
        }

        
        if( this.trigger.formProviderId ) {
            this.updateFormProvider();
        }
    },
    watch: {
        'trigger.formId': function(val) {
            adfoinNewIntegration.trigger.formName = this.trigger.forms[val];
        }
    }

});

jQuery(document).ready(function() {
    jQuery(".adfoin-integration-delete").on("click", function(e) {
        if(confirm(adfoin.delete_confirm)) {
            return;
        } else {
            e.preventDefault();
        }
    });

    jQuery( '.adfoin-toggle-form input' ).on( 'change', function(e) {

        e.stopPropagation();

        var requestData = {
            'action': 'adfoin_enable_integration',
            '_nonce': adfoin.nonce,
            'id': jQuery( this ).data( 'id' ),
            'enabled': jQuery( this ).prop( 'checked' ) ? 1 : 0
        };

        jQuery.post( ajaxurl, requestData);
        
    });

    jQuery('.icon-copy-full-log').on( 'click', function(e) {
        e.preventDefault();
        var $this = jQuery(this);
        $this.css('color', 'green');
        navigator.clipboard.writeText(JSON.stringify($this.data('full-log')));
        setTimeout(function() {
            $this.removeClass("dashicons-admin-page");
            $this.addClass("dashicons-saved");
            $this.prop('title', 'Copied to Clipboard');
        }, 1000);
    });
});

new Vue({
    el: '#klaviyo-auth',
    data: {
        tableData: [],
        rowData: {
            id: '',
            title: '',
            publicKey: '',
            privateKey: ''
        },
        isEditing: false,
        editIndex: -1,
        deleteIndex: -1
    },
    created() {
        this.fetchTableData();
    },
    methods: {
        addOrUpdateRow() {
            if (this.isEditing) {
                this.tableData[this.editIndex] = { ...this.rowData };
                this.isEditing = false;
            } else {
                this.rowData.id = this.generateUniqueId();
                this.tableData.push({ ...this.rowData });
            }
            this.clearForm();
            this.sendTableData();
        },
        editRow(index) {
            this.isEditing = true;
            this.editIndex = index;
            this.rowData = { ...this.tableData[index] };
            this.sendTableData();
        },
        confirmDelete(index) {
            if (confirm("Are you sure you want to delete this information?")) {
                this.deleteRow(index);
                this.sendTableData();
            }
        },
        deleteRow(index) {
            this.tableData.splice(index, 1);
            this.clearForm();
            this.sendTableData();
        },
        clearForm() {
            this.rowData = {
                id: '',
                title: '',
                publicKey: '',
                privateKey: ''
            };
            this.isEditing = false;
        },
        formatApiKey(apiKey) {
            // Display the first 4 characters followed by 4 asterisks
            return apiKey.substring(0, 6) + '****';
        },
        generateUniqueId() {
            // Generate a unique 8-character ID
            return Math.random().toString(36).substr(2, 8);
        },
        fetchTableData() {
            var that = this;
            var requestData = {
                'action': 'adfoin_get_klaviyo_credentials',
                '_nonce': adfoin.nonce
            };
    
            jQuery.post( ajaxurl, requestData, function( response ) {
                that.tableData = response.data;
            });
        },
        sendTableData() {
            var that = this;
            var requestData = {
                'action': 'adfoin_save_klaviyo_credentials',
                '_nonce': adfoin.nonce,
                'platform': 'klaviyo',
                'data': this.tableData
            };
    
            jQuery.post( ajaxurl, requestData, function( response ) {
                console.log(response);
    
            });
        }
    }
});

new Vue({
    el: '#acelle-auth',
    data: {
        tableData: [],
        rowData: {
            id: '',
            title: '',
            apiEndpoint: '',
            apiToken: ''
        },
        isEditing: false,
        editIndex: -1,
        deleteIndex: -1
    },
    created() {
        this.fetchTableData();
    },
    methods: {
        addOrUpdateRow() {
            if (this.isEditing) {
                this.tableData[this.editIndex] = { ...this.rowData };
                this.isEditing = false;
            } else {
                this.rowData.id = this.generateUniqueId();
                this.tableData.push({ ...this.rowData });
            }
            this.clearForm();
            this.sendTableData();
        },
        editRow(index) {
            this.isEditing = true;
            this.editIndex = index;
            this.rowData = { ...this.tableData[index] };
            this.sendTableData();
        },
        confirmDelete(index) {
            if (confirm("Are you sure you want to delete this information?")) {
                this.deleteRow(index);
                this.sendTableData();
            }
        },
        deleteRow(index) {
            this.tableData.splice(index, 1);
            this.clearForm();
            this.sendTableData();
        },
        clearForm() {
            this.rowData = {
                id: '',
                title: '',
                apiEndpoint: '',
                apiToken: ''
            };
            this.isEditing = false;
        },
        formatApiKey(apiKey) {
            // Display the first 4 characters followed by 4 asterisks
            return apiKey.substring(0, 6) + '****';
        },
        generateUniqueId() {
            // Generate a unique 8-character ID
            return Math.random().toString(36).substr(2, 8);
        },
        fetchTableData() {
            var that = this;
            var requestData = {
                'action': 'adfoin_get_acelle_credentials',
                '_nonce': adfoin.nonce
            };
    
            jQuery.post( ajaxurl, requestData, function( response ) {
                that.tableData = response.data;
            });
        },
        sendTableData() {
            var that = this;
            var requestData = {
                'action': 'adfoin_save_acelle_credentials',
                '_nonce': adfoin.nonce,
                'platform': 'acelle',
                'data': this.tableData
            };
    
            jQuery.post( ajaxurl, requestData, function( response ) {
                console.log(response);
    
            });
        }
    }
});