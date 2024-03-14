this["pcTemplates"] = this["pcTemplates"] || {};
this["pcTemplates"]["item"] = Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  return "";
},"useData":true});
this["pcTemplates"]["post"] = this["pcTemplates"]["post"] || {};
this["pcTemplates"]["post"]["empty-list"] = Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "<p>\n    You have no checklist items.\n    <a href=\""
    + escapeExpression(((helper = (helper = helpers.pageLink || (depth0 != null ? depth0.pageLink : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"pageLink","hash":{},"data":data}) : helper)))
    + "\">Create one.</a>\n</p>";
},"useData":true});
this["pcTemplates"]["post"]["list-instance"] = Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  return "<p><strong>Default Checklist</strong></p>";
  },"useData":true});
this["pcTemplates"]["post"]["list-item-instance"] = Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "<label class=\"pc-list-item-instance__description\">\n    <input type=\"checkbox\"/>\n    "
    + escapeExpression(((helpers.unescapeHTML || (depth0 && depth0.unescapeHTML) || helperMissing).call(depth0, (depth0 != null ? depth0.description : depth0), {"name":"unescapeHTML","hash":{},"data":data})))
    + "\n</label>";
},"useData":true});
this["pcTemplates"]["views"] = this["pcTemplates"]["views"] || {};
this["pcTemplates"]["views"]["dialog-notification"] = Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "<div class=\"js-pc-popup-source pc-popup pc-popup--notification zoom-anim-dialog\">\n    <div class=\"pc-popup__content\">\n        "
    + escapeExpression(((helper = (helper = helpers.message || (depth0 != null ? depth0.message : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"message","hash":{},"data":data}) : helper)))
    + "\n    </div>\n    <div class=\"pc-popup__button-group\">\n        <button class=\"pc-popup__button js-pc-popup-accept\">\n            "
    + escapeExpression(((helper = (helper = helpers.confirmText || (depth0 != null ? depth0.confirmText : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"confirmText","hash":{},"data":data}) : helper)))
    + "\n        </button>\n        <button class=\"pc-popup__button js-pc-popup-close\">\n            "
    + escapeExpression(((helper = (helper = helpers.cancelText || (depth0 != null ? depth0.cancelText : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"cancelText","hash":{},"data":data}) : helper)))
    + "\n        </button>\n    </div>\n</div>";
},"useData":true});
this["pcTemplates"]["views"]["dialog"] = Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "<div class=\"js-pc-popup-source pc-popup zoom-anim-dialog\">\n    <div class=\"pc-popup__content\">\n        "
    + escapeExpression(((helper = (helper = helpers.message || (depth0 != null ? depth0.message : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"message","hash":{},"data":data}) : helper)))
    + "\n    </div>\n    <div class=\"pc-popup__button-group\">\n        <button class=\"pc-popup__button js-pc-popup-accept\">\n            "
    + escapeExpression(((helper = (helper = helpers.confirmText || (depth0 != null ? depth0.confirmText : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"confirmText","hash":{},"data":data}) : helper)))
    + "\n        </button>\n        <button class=\"pc-popup__button js-pc-popup-close\">\n            "
    + escapeExpression(((helper = (helper = helpers.cancelText || (depth0 != null ? depth0.cancelText : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"cancelText","hash":{},"data":data}) : helper)))
    + "\n        </button>\n    </div>\n</div>";
},"useData":true});
this["pcTemplates"]["admin"] = this["pcTemplates"]["admin"] || {};
this["pcTemplates"]["admin"]["list"] = this["pcTemplates"]["admin"]["list"] || {};
this["pcTemplates"]["admin"]["list"]["empty-list-view"] = Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  return "<p class=\"pc-center\">\n    You have no checklist items in your list.\n</p>";
  },"useData":true});
this["pcTemplates"]["admin"]["list"]["layout"] = Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  return "<h3>Default Checklist</h3>\n\n<p>\n    These items will be added to the Publish Checklist for all new posts.\n</p>\n\n<div class=\"pc-edit-list\">\n    <div class=\"pc-edit-list__header\">\n        <p>Item Description <span><i>(click text to edit)</i></span></p>\n    </div>\n    <!--</thead>-->\n    <!--<tbody>-->\n    <!--<tr class=\"is-only-child\">-->\n        <!--<td colspan=\"2\">-->\n            <!--<p class=\"pc-center\">You have no checklist items in your default-->\n                <!--list.</p>-->\n        <!--</td>-->\n    <!--</tr>-->\n    <!--</tbody>-->\n    <div class=\"js-list-region\"></div>\n    <ul class=\"js-new-item-region pc-list-items\"></ul>\n</div>\n\n<p>\n    <button class=\"button button-primary js-add-checklist-item\">Add\n        Checklist Item\n    </button>\n</p>";
  },"useData":true});
this["pcTemplates"]["admin"]["list"]["list-item-view"] = Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "<div class=\"pc-grid__row\">\n    <div class=\"pc-grid__flexible\">\n        <p>"
    + escapeExpression(((helpers.unescapeHTML || (depth0 && depth0.unescapeHTML) || helperMissing).call(depth0, (depth0 != null ? depth0.description : depth0), {"name":"unescapeHTML","hash":{},"data":data})))
    + "</p>\n        <input type=\"text\" class=\"pc-wide\"/>\n    </div>\n    <div class=\"pc-grid__fixed\">\n        <button class=\"button button-primary pc-wide pc-list-item__remove-button js-remove\">Remove</button>\n        <button class=\"button button-primary pc-wide pc-list-item__save-button js-save\">Save</button>\n    </div>\n</div>";
},"useData":true});
this["pcTemplates"]["admin"]["list"]["list-items"] = Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  return "";
},"useData":true});
this["pcTemplates"]["admin"]["list"]["new-item-view"] = Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  return "<div class=\"pc-grid__row\">\n    <div class=\"pc-grid__flexible\">\n        <input class=\"list-item__description\"\n               type=\"text\"\n               placeholder=\"New item description\"/>\n    </div>\n    <div class=\"pc-grid__fixed\">\n        <button class=\"button button-primary pc-wide\">Save Item</button>\n    </div>\n</div>";
  },"useData":true});
this["pcTemplates"]["admin"]["settings"] = this["pcTemplates"]["admin"]["settings"] || {};
this["pcTemplates"]["admin"]["settings"]["layout"] = Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "<h3>Settings<span class=\"js-save-settings save-widget\">All Settings Saved</span></h3>\n\n<label><strong>"
    + escapeExpression(((helper = (helper = helpers.title || (depth0 != null ? depth0.title : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"title","hash":{},"data":data}) : helper)))
    + ":</strong></label>\n\n<fieldset>\n    <!--<div class=\"pc-radio-item\"><label>-->\n        <!--<div>-->\n            <!--<input type=\"radio\"-->\n                   <!--name=\"publish-attempt\"-->\n                   <!--value=\"stop\"-->\n            <!--<?php is_checked($pc_on_publish, 'stop') ?>/>-->\n        <!--</div>-->\n        <!--<div>-->\n            <!--<p class=\"pc-radio-item__title\">Prevent Publishing</p>-->\n            <!--<p class=\"pc-radio-item__body\"></p>-->\n        <!--</div></label>-->\n    <!--</div>-->\n\n    <!--<div class=\"pc-radio-item\"><label>-->\n        <!--<div>-->\n            <!--<input type=\"radio\"-->\n                   <!--name=\"publish-attempt\"-->\n                   <!--value=\"warn\"-->\n            <!--<?php is_checked($pc_on_publish, 'warn') ?>/>-->\n        <!--</div>-->\n        <!--<div>-->\n            <!--<p class=\"pc-radio-item__title\">Warn User</p>-->\n            <!--<p class=\"pc-radio-item__body\"></p>-->\n        <!--</div></label>-->\n    <!--</div>-->\n\n    <!--<div class=\"pc-radio-item\"><label>-->\n        <!--<div>-->\n            <!--<input type=\"radio\"-->\n                   <!--name=\"publish-attempt\"-->\n                   <!--value=\"nothing\"-->\n            <!--<?php is_checked($pc_on_publish, 'nothing') ?>/>-->\n        <!--</div>-->\n        <!--<div>-->\n            <!--<p class=\"pc-radio-item__title\">Do Nothing</p>-->\n            <!--<p class=\"pc-radio-item__body\"></p>-->\n        <!--</div></label>-->\n    <!--</div>-->\n</fieldset>";
},"useData":true});
this["pcTemplates"]["admin"]["settings"]["setting-item"] = Handlebars.template({"compiler":[6,">= 2.0.0-beta.1"],"main":function(depth0,helpers,partials,data) {
  var helper, functionType="function", helperMissing=helpers.helperMissing, escapeExpression=this.escapeExpression;
  return "<div class=\"pc-radio-item\"><label>\n    <div>\n        <input type=\"radio\"\n               name=\"publish-attempt\"\n               value=\""
    + escapeExpression(((helper = (helper = helpers.id || (depth0 != null ? depth0.id : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"id","hash":{},"data":data}) : helper)))
    + "\"\n            />\n    </div>\n    <div>\n        <p class=\"pc-radio-item__title\">"
    + escapeExpression(((helper = (helper = helpers.title || (depth0 != null ? depth0.title : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"title","hash":{},"data":data}) : helper)))
    + "</p>\n        <p class=\"pc-radio-item__body\">"
    + escapeExpression(((helper = (helper = helpers.body || (depth0 != null ? depth0.body : depth0)) != null ? helper : helperMissing),(typeof helper === functionType ? helper.call(depth0, {"name":"body","hash":{},"data":data}) : helper)))
    + "</p>\n    </div></label>\n</div>";
},"useData":true});