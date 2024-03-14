var page = '';
var action = '';
var dataTemplate = [];
/* Old shortcode from popup taken during update */
var editable_template = '';
/* Old name from popup taken during update */
var editable_name = '';
var content_changed_status = 1;
var builder_tab_avtivated = 0;

jQuery('document').ready(function () {


  jQuery(document).on("click", ".cf7b-row span[class^='cf7b-label']", function() {
    var lbl = jQuery(this);
    var o = lbl.text();
    var txt = jQuery('<input type="text" class="cf7b-editable-label-text" value="'+o+'" />');
    lbl.replaceWith(txt);
    txt.focus();

    txt.blur(function() {
      var no = jQuery(this).val();
      lbl.text(no);
      txt.replaceWith(lbl);
      if(o != no) {
        replace_template();
      }
    });
  });

  jQuery.urlParam = function(name) {
    var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
    if( typeof results != 'undefined' && results != null && typeof results[1] != 'undefined') {
      return results[1] || 0;
    }
    return '';
  }

  // example.com?param1=name&param2=&id=6
  page = jQuery.urlParam('page'); // name
  action = jQuery.urlParam('action'); // name
  var post = jQuery.urlParam('post'); // name
  if( page == 'wpcf7-new' || (page == 'wpcf7' && post != '' ) ) {
    cf7b_row();
    if( jQuery("#visual-panel-tab").hasClass('ui-tabs-active') ) {
      jQuery("#cf7b_preview").removeClass("cf7b-hidden");
    }
  }

  jQuery("#visual-panel-tab, .insert-tag").on("click", function() {
    builder_tab_avtivated = 1;

    var name = jQuery("#TB_ajaxContent .tag-generator-panel:not('.hidden') .tg-name").val();
    var textarea = jQuery("#wpcf7-form").val();
    var textareanew = textarea.replaceAll('temp-name', name);
    jQuery(document).find("#wpcf7-form").empty();
    jQuery(document).find("#wpcf7-form").val(textareanew);

    if(content_changed_status == 1) {
      create_form();
    }
    if( jQuery("#visual-panel-tab").hasClass('ui-tabs-active') ) {
      jQuery("#cf7b_preview").removeClass("cf7b-hidden");
    }
  });

  jQuery(document).on("click", "#visual-panel .dashicons-edit-large", function() {
    edit_popup(this);
  });

  jQuery(document).on("click", ".cf7b-row .dashicons-trash", function() {
    var r = confirm("Are you sure you want to delete this field?");
    if ( r === true ) {
      jQuery(this).parent().parent().remove();
      replace_template();
    }
  });

  jQuery(document).on("click", "#form-panel-tab", function() {
    if(builder_tab_avtivated == 1) {
      replace_template();
    }
  });

/*
  jQuery(document).on("click", ".cf7b-update-tag", function() {
     replace_template();
     /!* TODO 0 *!/
    content_changed_status = 1;
  });
*/

  jQuery(document).on("click", "#tag-generator-list a", function() {
    content_changed_status = 1;
  });

  jQuery(document).on("click","#TB_ajaxContent .tag-generator-panel .cf7b-updat-tag", function() {
    replace_mail_template();
    /* Replace temp-name with real field name during Insert Tag button click in popup */
    var new_template = jQuery("#TB_ajaxContent .tag-generator-panel .insert-box input").val();
    var new_name = jQuery("#TB_ajaxContent .tag-generator-panel .tg-name").val();
    var wpcf7_view = jQuery("#wpcf7-form").val();
    wpcf7_view = wpcf7_view.replace('cf7b-group-'+editable_name,'cf7b-group-'+new_name);
    wpcf7_view = wpcf7_view.replace('cf7b-label-'+editable_name,'cf7b-label-'+new_name);
    wpcf7_view = wpcf7_view.replace(editable_template,new_template);
    jQuery("#wpcf7-form").val(wpcf7_view);
    editable_template = '';
    editable_name = '';
    jQuery("#TB_title .tb-close-icon").click();
    create_form();
    content_changed_status = 1;
  });

  jQuery(document).on("change", "#wpcf7-form", function() {
    content_changed_status = 1;
  });

  jQuery(document).on("click","#cf7b_revision", function () {
    jQuery(".cf7b-popup-overlay").removeClass("cf7b-hidden");
    return false;
  });

  jQuery(document).on("click",".cf7b-popup-overlay:not(#cf7b-revision-popup), .cf7b-popup-close", function () {
    jQuery(".cf7b-popup-overlay").addClass("cf7b-hidden");
  });

  /* Revision button click */
  jQuery(document).on("click",".cf7b-revision-btn", function () {
    var data = {
      task: "cf7b_change_to_revision",
      action: "cf7b_ajax",
      id: jQuery(this).attr("data-id"),
    };

    jQuery.ajax({
      type: "POST",
      url: ajaxurl,
      data: data
    }).success(function( response ) {
      jQuery(document).find("#wpcf7-form").val('');
      jQuery(document).find("#wpcf7-form").val(response);

      create_form();
      jQuery(".cf7b-popup-overlay").addClass("cf7b-hidden");

      var notice = '<div class="notice notice-warning cf7b-notice"><p>You need to click the "Save" button to save the changes.</p></div>';
      if( jQuery(".cf7b-notice").length ) {
        jQuery(".cf7b-notice").remove();
      }
      if( jQuery("#message").length ) {
        jQuery("#message").remove();
      }
      jQuery("#wpcf7-admin-form-element").before(notice);
      /* TODO 0 */
      content_changed_status = 1;
    });
  });

  /* Creating empty field in template textarea during click on add buttons */
  jQuery(".thickbox.button").on("click", function() {
    add_temp_row();
  });


  /* Add new column */
  jQuery(document).on("click",".cf7b-addColumn .dashicons-plus", function() {
    jQuery(this).parent().before("<div class='cf7b-section sortable ui-sortable'><div class='cf7b-section-title'><span class='dashicons dashicons-trash'></span></div><div class='cf7b-col sortable ui-sortable cf7b-unvisible'></div></div>");
    if( jQuery(document).find(".cf7b-section").length === 3 ) {
      jQuery(".cf7b-addColumn").remove();
    }
    sortable_start();
    replace_template();
  });

  /* Remove column */
  jQuery(document).on("click",".cf7b-section .cf7b-section-title .dashicons-trash", function() {
    var alertText = "Are you sure?\n If you click Yes, all fields will be removed with the column.";
    if( true === confirm(alertText) ) {
      jQuery(this).parents(".cf7b-section").remove();
      if( jQuery("#visual-panel .cf7b-addColumn").length === 0 && jQuery(document).find(".cf7b-section").length < 3) {
        html = '<div class="cf7b-addColumn"><span class="dashicons dashicons-plus" title="Add Column"></span></div>';
        jQuery("#visual-panel #cf7b-content").append(html);
      }
      replace_template();
    }
  });

  /* Remove temp row html if popup closed without add */
  jQuery('body').on( 'thickbox:removed', function() {
    var template = jQuery("#wpcf7-form").val();
    var emptyhtml = '<div class="cf7b-row"><span class="cf7b-label-temp-name">temp-name</span><div class="cf7b-group-temp-name"></div></div>';
    if( template.indexOf(emptyhtml) > 0 ) {
      template = template.replaceAll(emptyhtml, '');
      jQuery("#wpcf7-form").val(template);
    }
  });
});

function add_temp_row() {
  if( editable_template !== '' ) {
    return;
  }
  var element = document.getElementById("wpcf7-form");
  var n = element.value.lastIndexOf( "</div></div>");
  var value = element.value;
  var emptyhtml = '<div class="cf7b-col sortable ui-sortable"><div class="cf7b-row"><span class="cf7b-label-temp-name">temp-name</span><div class="cf7b-group-temp-name">cf7b_temp</div></div></div>';
  var output = [value.slice(0, n), emptyhtml, value.slice(n)].join('');

  n = output.indexOf("cf7b_temp");
  output = output.replace('cf7b_temp','');
  element.value = output;
  element.focus();
  element.setSelectionRange(n,n);

}

jQuery(window).load(function() {
  if(jQuery("#visual-panel-tab").hasClass('ui-tabs-active')) {
    builder_tab_avtivated = 1;
    create_form();
    if( jQuery("#visual-panel-tab").hasClass('ui-tabs-active') ) {
      jQuery("#cf7b_preview").removeClass("cf7b-hidden");
    }
  }

  var old_email = "";new_email = "";
  jQuery("#tag-generator-panel-email-name").on('focus', function () {
    // Store the current value on focus and on change
    old_email = this.value;
  }).change(function() {
    // Make sure the previous value is updated
    new_email = this.value;
    replace_mail_template(old_email, new_email)
  });
});

function replace_mail_template(old_email, new_email) {
  var mail_tab_contenet_old = jQuery("#wpcf7-mail-additional-headers").val();
  var mail_body = jQuery("#wpcf7-mail-body").val();
  var old = '['+old_email+']';
  var new_m = '['+new_email+']';

  var mail_tab_contenet_new = mail_tab_contenet_old.replace(old, new_m);
  jQuery("#wpcf7-mail-additional-headers").val(mail_tab_contenet_new);
  var mail_body_new = mail_body.replace(old, new_m);
  jQuery("#wpcf7-mail-body").val(mail_body_new);
}

function edit_popup(that) {
  var key = jQuery(that).parent().parent().find('[class^="cf7b-group-"]').attr('class');
  editable_name = key.replace("cf7b-group-","");
  if( editable_name.indexOf('checkbox-') > -1 ) {
    editable_name = editable_name+'[]';
  }
  var attributes = dataTemplate[editable_name]['cf7_template_attributes'];
  editable_template = dataTemplate[editable_name]['cf7_template'];

  get_popup_trigger_link(attributes['type']);
  replace_popup(attributes,editable_template);
}

function replace_popup(attr, shortcode) {
  var popup = jQuery("#TB_ajaxContent .tag-generator-panel");

  popup.find(".submitbox").html('').append("<button class='button button-primary cf7b-updat-tag'>Update Tag</button>");

  form_element = '';
    jQuery.each(attr, function(index, value) {
      var selector = (index == 'akismet') ? "[name^='"+index+"']" : "[name='"+index+"']";
      form_element = popup.find(selector);
      if( form_element.attr('type') == 'checkbox' && value == 'true') {
        form_element.prop('checked',true).val('on');
      } else {
        popup.find("[name='"+index+"']").val(value.replaceAll('"',''));
        if(index == 'name') {
          popup.find("span.mail-tag").text(value);
        }
      }
    });

  popup.find(".insert-box input[type=text]").val(shortcode);
  popup.find(".insert-tag").val("Update Tag").addClass('cf7b-update-tag').removeClass('insert-tag');
}

/* Find a with href and trigger click acti0n */
function get_popup_trigger_link( type ) {
  link = '';
  switch( type ) {
    case 'text':
      link = 'inlineId=tag-generator-panel-text';
      break;
    case 'email':
      link = 'inlineId=tag-generator-panel-email';
      break;
    case 'url':
      link = 'inlineId=tag-generator-panel-url';
      break;
    case 'tel':
      link = 'inlineId=tag-generator-panel-tel';
      break;
    case 'number':
      link = 'inlineId=tag-generator-panel-number';
      break;
    case 'range':
      link = 'inlineId=tag-generator-panel-number';
      break;
    case 'date':
      link = 'inlineId=tag-generator-panel-date';
      break;
    case 'textarea':
      link = 'inlineId=tag-generator-panel-textarea';
      break;
    case 'select':
      link = 'inlineId=tag-generator-panel-menu';
      break;
    case 'checkbox':
      link = 'inlineId=tag-generator-panel-checkbox';
      break;
    case 'radio':
      link = 'inlineId=tag-generator-panel-radio';
      break;
    case 'acceptance':
      link = 'inlineId=tag-generator-panel-acceptance';
      break;
    case 'quiz':
      link = 'inlineId=tag-generator-panel-quiz';
      break;
    case 'file':
      link = 'inlineId=tag-generator-panel-file';
      break;
    case 'submit':
      link = 'inlineId=tag-generator-panel-submit';
      break;
  }

  var href='#TB_inline?width=900&height=500&'+link;
  jQuery(document).find("[href='"+href+"']").click();
}

function replace_template() {
  var html = jQuery("#cf7b-content").prop("outerHTML");
  if( typeof html === 'undefined' ) {
    return;
  }
  html = html.replaceAll('disabled','');
  var data = {
    task: "cf7b_add_form_to_template",
    action: "cf7b_ajax",
    htmlForm: html,
    post_id: jQuery("#post_ID").val(),
  };

  jQuery.ajax({
    type: "POST",
    url: ajaxurl,
    data: data
  }).success(function( response ) {
    jQuery(document).find("#wpcf7-form").val(response);
    /* TODO 0 */
    content_changed_status = 1;
  });
}

function sortable_start() {
  jQuery( ".cf7b-section, .cf7b-col" ).sortable({
    connectWith: ".cf7b-section, .cf7b-col",
    items: "> .cf7b-row",
    placeholder: "cf7b-highlight",
    tolerance: "pointer",
    cursor: 'move',
    start: function() {
      jQuery(".cf7b-col").removeClass("cf7b-unvisible");
    },
    stop: function() {
      cf7b_columns_refresh();
      replace_template()
    },
  });
}

function cf7b_columns_refresh() {
  jQuery(".cf7b-col:empty").remove();
  jQuery(".cf7b-col").after("<div class='cf7b-col sortable ui-sortable cf7b-unvisible'></div>");
  sortable_start();
}

function create_form() {
  var data = {
    task: "cf7b_add_template_to_form",
    action: "cf7b_ajax",
    post_id: jQuery("#post_ID").val(),
    template: jQuery(document).find("#wpcf7-form").val(),
  };
  jQuery("#cf7b_loading").removeClass("cf7b-hidden");
  jQuery.ajax({
      type: "POST",
      url: ajaxurl,
      data: data
  }).success(function( response ) {
      var obj = JSON.parse(response);
      dataTemplate = obj.data;
      response = obj.form;
      jQuery("#visual-panel #cf7b-content").remove();
      jQuery("#visual-panel").append(response);

      var html = "";
      if( jQuery("#visual-panel .cf7b-addColumn").length === 0 && jQuery(document).find(".cf7b-section").length < 3) {
        html = '<div class="cf7b-addColumn"><span class="dashicons dashicons-plus" title="Add Column"></span></div>';
        jQuery("#visual-panel #cf7b-content").append(html);
      }
      if( jQuery("#visual-panel .cf7b-section .cf7b-section-title").length === 0 ) {
        html = '<div class="cf7b-section-title"><span class="dashicons dashicons-trash"></span></div>';
        jQuery("#visual-panel .cf7b-section").prepend(html);
      }

/*
      jQuery("#visual-panel .cf7b-section").each(function(){
        jQuery("#visual-panel .cf7b-section").addClass('sortable');
        if( jQuery(this).find(".cf7b-col").length === 0 ) {
          html = "<div class='cf7b-col sortable ui-sortable cf7b-unvisible'></div>";
          jQuery(this).append(html);
        }
      });
*/
      jQuery("#visual-panel .cf7b-section").addClass('sortable');


  }).done(function() {
      jQuery(document).find("#visual-panel #cf7b_loading").remove();
      jQuery('#visual-panel input').prop("disabled", true);
      jQuery('#visual-panel textarea').prop("disabled", true);
      jQuery('#visual-panel select').prop("disabled", true);
      /* TODO 0 */
      content_changed_status = 1;

    cf7b_columns_refresh();
  });
}

function cf7b_row() {
  var panelHtml = '<div id="cf7b-title-row"><h1>CF Builder Panel</h1>' +
    '<a href="'+cf7b_object.preview_url+'" target="_blank" class="button button-primary cf7b-hidden" id="cf7b_preview">Preview</a href="'+cf7b_object.preview_url+'">' +
    '<button class="button button-large" id="cf7b_revision">Revisions</button>' +
    '</div>';

  jQuery("#post-body-content").append(panelHtml);
  var fields = jQuery("#tag-generator-list").html();
  jQuery("#visual-panel").prepend(fields);
  jQuery("#visual-panel").append("<div id='cf7b_loading' class='cf7b-hidden'><img alt='loading' src='"+cf7b_object.loader_url+"'></div>");
  jQuery("#visual-panel #cf7b_loading img").load();

}

