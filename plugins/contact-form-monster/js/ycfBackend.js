function ycfBackend() {

}

ycfBackend.prototype.init = function() {
	this.deleteContactFormFormDb();
    this.addTypElemntsWrapper();
	this.addToHiddenContent();
	this.initTab();
	//this.addSortbleColumn();
	this.ycfElementOptions();
	this.ysfRemoveElement();
	this.changeElementValueFromList();
	this.confButtonInit();
	this.addNewFieldInit();
};

ycfBackend.prototype.confButtonInit = function () {

	jQuery('.ycf-element-conf-wrapper').hover(function () {
		jQuery(this).find(jQuery('.ycf-conf-element')).removeClass('ycf-hide-element');;
	},function () {
		jQuery('.ycf-conf-edit, .ycf-delete-element').addClass('ycf-hide-element');
	});
};

ycfBackend.prototype.deleteContactFormFormDb = function () {

	jQuery('.ycf-delete-form').bind('click', function () {

		var boolData =  confirm('Are you sure');

		if(!boolData) {
			return false;
		}

		var formId = jQuery(this).attr('data-id');
		var data = {
			action: 'delete_contact_form',
			formId: formId
		};

        jQuery.post(ajaxurl, data, function(response) {
			window.location.reload();
        });
    })
};

ycfBackend.prototype.changeElementValueFromList = function () {

	var that = this;
	jQuery('.ycf-element-sub-option').bind('change', function () {

		var editElementData = {};
        editElementData.formCurrentId = jQuery("#ycf-form-id").val();
        editElementData.changedElementValue = jQuery(this).val();
        editElementData.changedElementId = jQuery(this).attr('data-id');
        editElementData.changedElementKey = jQuery(this).attr('data-key');

        var data = {
            action: 'change-element-data',
            editElementData: editElementData
		};

        jQuery.post(ajaxurl, data, function(response) {
			console.log(response);
		});
    })
};

ycfBackend.prototype.addTypElemntsWrapper = function () {
	
	jQuery('.ycf-add-a-field').bind('click', function () {
		var currentStatus = jQuery('.sortable-all-elements-wrapper').attr('data-toggle-status');

		if(currentStatus == "true") {
            jQuery('.sortable-all-elements-wrapper').attr('data-toggle-status', false);
            jQuery('.sortable-all-elements-wrapper').removeClass('ycf-hide-element');
            jQuery('.ycf-add-a-field').text('Cancel adding a field');
		}
		else {
            jQuery('.sortable-all-elements-wrapper').attr('data-toggle-status', true);
            jQuery('.sortable-all-elements-wrapper').addClass('ycf-hide-element');
            jQuery('.ycf-add-a-field').text("Add A Field");
		}
    })
};

ycfBackend.prototype.ycfElementOptions = function() {

	jQuery('.ycf-view-element-wrapper').bind('click', function() {

		elementOptionsToggle = jQuery(this).attr('data-options');
		
		if(elementOptionsToggle == "false") {
			jQuery(this).next().removeClass('ycf-hide-element');
			jQuery(this).attr('data-options', "true");
		}
		if(elementOptionsToggle == "true") {
			jQuery(this).next().addClass('ycf-hide-element');
			jQuery(this).attr('data-options', "false");
		}
	});
};

ycfBackend.prototype.ysfRemoveElement = function() {

	var that = this;
	jQuery('.ycf-delete-element').bind('click', function() {

		var data = {};
		var removeElementId = jQuery(this).attr('data-id');
		jQuery("#"+removeElementId).remove();
		data.id = removeElementId;
		that.removeElementViaAjax(data);
	});
};

ycfBackend.prototype.removeElementViaAjax = function(data) {

	var data = {
		action: 'remove_element_from_list',
		modification: 'delete',
		removeElementData: data,
		beforeSend: function() {
		}
	};

	jQuery.post(ajaxurl, data, function(response,d) {
		console.log(response);
	});
};

ycfBackend.prototype.addSortbleColumn = function() {
	
	var that = this;
	var position = {};
    // jQuery("#sortable-all-elements").sortable({
    // 	connectWith: ".connectedSortable",
    // 	 remove: function(event, ui) {
    // 	 	// console.log(ui.item);
    //         ui.item.clone().appendTo('#active-elements');
    //         jQuery(this).sortable('cancel');
    //     }
    // });
    jQuery("#active-elements").sortable({
    	connectWith: ".connectedSortable",
	    start: function(event, ui) {
		    position.start = ui.item.index();
	    },
	    update: function(event, ui) {
		    position.end = ui.item.index();
		    var data = {
			    action: 'shape-form-element',
			    modification: 'reposition',
			    position: position
		    };
		    jQuery.post(ajaxurl, data, function(response,d) {
			    console.log(response);
		    });
	    },
	    stop: function () {
    		console.log(position);

	    }
    });


};

ycfBackend.prototype.addNewFieldInit = function () {

	var that = this;
	jQuery(".sortable-custom-element").bind("click", function() {
		that.addToFormElementsList(jQuery(this));
	});
};

ycfBackend.prototype.getRandomName = function(){
    
    var randomName = Math.floor(Math.random() * Date.now()).toString().substr(0, 5);

    return randomName;
};

ycfBackend.prototype.addToFormElementsList = function(element) {

	var that = this;
	var id = this.getRandomName();
	var type = element.attr('data-element-type');
	var label = element.find("span").text();
	var name = "ycf-"+id;

	var formElement = {};
	formElement.id = id;
	formElement.type = type;
	formElement.name = name;
	formElement.label = label;
	formElement.value = '';
	formElement.options = '';
	
	var data = {
		action: 'shape-form-element',
		modification: 'add-element',
		formElements: formElement,
		contactFormId: jQuery("#ycf-form-id").val(),
		beforeSend: function() {
		}
	};

	this.doAjaxShapeFormList(data);
};

ycfBackend.prototype.doAjaxShapeFormList = function(data) {

	var that = this;
	jQuery.post(ajaxurl, data, function(response) {
		console.log=(response);
		if(response != '') {

			jQuery("#ycf-submit-wrapper").before(response);
			that.ycfElementOptions();
			that.confButtonInit();
		}
		
	});
};

ycfBackend.prototype.initTab = function() {
	jQuery(".nav-tabs a").click(function(){
		jQuery(this).tab('show');
	});
};

ycfBackend.prototype.addToHiddenContent = function() {
	jQuery('.sortable-all-elements').sortable(
		console.log(jQuery(this))
	);
	// jQuery("#contact-form-save").submit(function(e) {
	// 	e.preventDefault();

	// 	var contactFormHtml = document.getElementById('live-form-wrapper').innerHTML;


	// 	//jQuery("#hidden-form-content").html(contactFormHtml);
	// });
};

jQuery(document).ready(function() {
	var obj = new ycfBackend();
	obj.init();
});