/*global module, SktbuilderStorage, SktbuilderController, PageModel, SktbuilderLayout*/
/**
 * Initialize page sktbuilder
 *
 * @version 0.0.1
 * @class  Sktbuilder
 * @param {Object} options [current page id and {Object} data]
 */
//module.exports.Sktbuilder = Sktbuilder;
function Skin() {
    this.assets = {
        "dev": [
            { "type": "js", "name": "jquery-ui", "src": "js/libs/jquery-ui.js", "min_src": "js/libs/jquery-ui.min.js", "dep": ["jquery"] },
            { "type": "js", "name": "jquery-ui-touch-punch", "src": "js/libs/jquery.ui.touch-punch.js", "min_src": "js/libs/jquery.ui.touch-punch.min.js", "dep": ["jquery-ui"] },
            { "type": "js", "name": "jquery-ui-droppable-iframe", "src": "js/libs/jquery-ui-droppable-iframe.js", "min_src": "js/libs/jquery-ui-droppable-iframe.min.js", "dep": ["jquery-ui"] },
            { "type": "js", "name": "hammer", "src": "js/libs/hammer.js", "min_src": "js/libs/hammer.min.js", "dep": ["jquery"] },
            { "type": "js", "name": "sktbuilder-select", "src": "js/libs/sktbuilder-select.js", "dep": ["jquery"] },
            { "type": "js", "name": "sktbuilder-dropdown", "src": "js/libs/sktbuilder-dropdown.js", "dep": ["jquery"] },
            { "type": "js", "name": "current-device", "src": "js/libs/current-device.js", "min_src": "js/libs/current-device.min.js", "dep": ["jquery"] },
            { "type": "js", "name": "html2canvas", "src": "js/libs/html2canvas.js", "min_src": "js/libs/html2canvas.min.js", "dep": ["jquery"] },
            { "type": "js", "name": "jquery.easing", "src": "js/libs/jquery.easing.js", "min_src": "js/libs/jquery.easing.min.js", "dep": ["jquery"] },
            { "type": "js", "name": "date-time-picker", "src": "js/libs/date-time-picker.js", "min_src": "js/libs/date-time-picker.min.js", "dep": ["jquery"] },
            { "type": "js", "name": "sktbuilder-controller", "src": "js/controllers/sktbuilder-controller.js", "dep": ["backbone"] },
            { "type": "js", "name": "block-model", "src": "js/models/block-model.js", "dep": ["backbone"] },
            { "type": "js", "name": "page-model", "src": "js/models/page-model.js", "dep": ["backbone"] },
            { "type": "js", "name": "sktbuilder-block-view", "src": "js/views/sktbuilder-block-view.js", "dep": ["backbone"] },
            { "type": "js", "name": "sktbuilder-block-wrapper-view", "src": "js/views/sktbuilder-block-wrapper-view.js", "dep": ["backbone"] },
            { "type": "js", "name": "sktbuilder-edit-mode-button-view", "src": "js/views/sktbuilder-edit-mode-button-view.js", "dep": ["backbone"] },
            { "type": "js", "name": "sktbuilder-field-view", "src": "js/views/sktbuilder-field-view.js", "dep": ["backbone"] },
            { "type": "js", "name": "sktbuilder-fields-view", "src": "js/views/sktbuilder-fields-view.js", "dep": ["backbone"] },
            { "type": "js", "name": "sktbuilder-layout", "src": "js/views/sktbuilder-layout.js", "dep": ["backbone"] },
            { "type": "js", "name": "sktbuilder-sidebar-view", "src": "js/views/sktbuilder-sidebar-view.js", "dep": ["backbone"] },
            { "type": "js", "name": "sktbuilder-menu-view", "src": "js/views/sktbuilder-menu-view.js", "dep": ["backbone"] },
            { "type": "js", "name": "sktbuilder-toolbar-view", "src": "js/views/sktbuilder-toolbar-view.js", "dep": ["backbone"] },
            { "type": "js", "name": "sktbuilder-viewport-view", "src": "js/views/sktbuilder-viewport-view.js", "dep": ["backbone"] },
            { "type": "js", "name": "sktbuilder-menu-blocks-preview-view", "src": "js/views/sktbuilder-menu-blocks-preview-view.js", "dep": ["backbone"] },
            { "type": "js", "name": "sktbuilder-menu-groups-view", "src": "js/views/sktbuilder-menu-groups-view.js", "dep": ["backbone"] },
            { "type": "js", "name": "sktbuilder-menu-save-page-view", "src": "js/views/sktbuilder-menu-save-page-template-view.js", "dep": ["backbone"] },
            { "type": "js", "name": "sktbuilder-settings-view", "src": "js/views/sktbuilder-settings-view.js", "dep": ["sktbuilder-fields-view"] },
            { "type": "js", "name": "sktbuilder-import-export-view", "src": "js/views/sktbuilder-import-export-view.js", "dep": ["backbone"] },
            { "type": "js", "name": "sktbuilder-page-templates-view", "src": "js/views/sktbuilder-page-templates-view.js", "dep": ["backbone"] },
            { "type": "js", "name": "field-accordion", "src": "js/views/fields/field-accordion.js", "dep": ["backbone"] },
            { "type": "js", "name": "field-accordion-item", "src": "js/views/fields/field-accordion-item.js", "dep": ["backbone"] },
            { "type": "js", "name": "field-accordion-item-settings", "src": "js/views/fields/field-accordion-item-settings.js", "dep": ["backbone"] },
            { "type": "js", "name": "field-accordion-flip", "src": "js/views/fields/field-accordion-flip.js", "dep": ["backbone"] },
            { "type": "js", "name": "field-accordion-item-flip", "src": "js/views/fields/field-accordion-flip-item.js", "dep": ["backbone"] },
            { "type": "js", "name": "field-accordion-item-flip-settings", "src": "js/views/fields/field-accordion-flip-item-settings.js", "dep": ["backbone"] },
            { "type": "js", "name": "field-checkbox-switch", "src": "js/views/fields/field-checkbox-switch.js", "dep": ["backbone"] },
            { "type": "js", "name": "field-checkbox", "src": "js/views/fields/field-checkbox.js", "dep": ["backbone"] },
            { "type": "js", "name": "field-colorpicker", "src": "js/views/fields/field-colorpicker.js", "dep": ["backbone"] },
            { "type": "js", "name": "field-icon", "src": "js/views/fields/field-icon.js", "dep": ["backbone"] },
            { "type": "js", "name": "field-image", "src": "js/views/fields/field-image.js", "dep": ["backbone"] },
            { "type": "js", "name": "field-select", "src": "js/views/fields/field-select.js", "dep": ["backbone"] },
            { "type": "js", "name": "field-radio", "src": "js/views/fields/field-radio.js", "dep": ["backbone"] },
            { "type": "js", "name": "field-slider", "src": "js/views/fields/field-slider.js", "dep": ["backbone"] },
            { "type": "js", "name": "field-text-autocomplete", "src": "js/views/fields/field-text-autocomplete.js", "dep": ["backbone"] },
            { "type": "js", "name": "field-text", "src": "js/views/fields/field-text.js", "dep": ["backbone"] },
            { "type": "js", "name": "field-textarea", "src": "js/views/fields/field-textarea.js", "dep": ["backbone"] },
            { "type": "js", "name": "field-video", "src": "js/views/fields/field-video.js", "dep": ["backbone"] },
            { "type": "js", "name": "field-datepicker", "src": "js/views/fields/field-datepicker.js", "dep": ["backbone"] },
            { "type": "js", "name": "icon-center-view", "src": "js/views/fields/icon-center-view.js", "dep": ["backbone"] },
            { "type": "js", "name": "image-center-view", "src": "js/views/fields/image-center-view.js", "dep": ["backbone"] },
            { "type": "js", "name": "video-center-view", "src": "js/views/fields/video-center-view.js", "dep": ["backbone"] },
            { "type": "js", "name": "template-adapter-handlebars", "src": "js/extensions/template-adapter-handlebars.js", "dep": ["backbone"] },
            { "type": "js", "name": "template-adapter-underscore", "src": "js/extensions/template-adapter-underscore.js", "dep": ["backbone"] },
            { "type": "js", "name": "sktbuilder-storage", "src": "js/sktbuilder-storage.js", "dep": ["backbone"] },
            { "type": "js", "name": "sktbuilder-utils", "src": "js/sktbuilder-utils.js", "dep": ["backbone"] },
            { "type": "js", "name": "quill", "src": "js/libs/quill/quill.js", "min_src": "js/libs/quill/quill.min.js", "dep": ["jquery"] },
            { "type": "css", "name": "font-awesome.css", "src": "css/font-awesome.css" },
            { "type": "css", "name": "glyphicons.css", "src": "css/glyphicons.css" },
            { "type": "css", "name": "sktbuilder.css", "src": "css/sktbuilder-backend.css", "dep": ["jquery-ui.css"] }
        ],
        "prod": [
            { "type": "css", "name": "font-awesome.min.css", "src": "css/font-awesome.min.css" },
            { "type": "css", "name": "glyphicons.min.css", "src": "css/glyphicons.min.css" },
            { "type": "css", "name": "sktbuilder.min.css", "src": "css/sktbuilder-backend.min.css", "dep": ["jquery-ui.css"]  },
            { "type": "js", "name": "skin_assets", "src": "skin.concated.js" }
        ],
        "all": [
            { "type": "css", "name": "jquery-ui.css", "src": "css/jquery-ui.css" },
            { "type": "css", "name": "quill.snow.css", "src": "js/libs/quill/quill.snow.css" },
            { "type": "json", "name": "skin_templates", "src": "tmpl/templates.json" }
        ]
    };
}

/**
 * Activate page sktbuilder
 */
Skin.prototype.activate = function(options) {

    var self = this;

    this.options = {
        blockTemplateAdapter: 'hbs',
        blockPreviewUrl: "preview.png",
        skinUrl: options.skinUrl
    };
    _.extend(this.options, options);

    this.loader = this.options.loader;

    this.storage = new SktbuilderStorage(this.options);

    this.controller = new SktbuilderController();

    this.pageModel = new PageModel();

    this.layout = new SktbuilderLayout({
        "model": this.pageModel,
        "storage": this.storage,
        "controller": this.controller
    });

    this.controller.setLayout(this.layout);
    this.controller.setPageModel(this.pageModel);
    this.controller.setStorage(this.storage);

    //Creating and appending sktbuilder layout
    jQuery(window).resize(function() {
        self.layout.resize();
    });


    //Blocks loaded to viewPort
    self.layout.viewPort.once('blocks_loaded', function() {
        self.loader.trigger('skin_loaded');
        Backbone.history.start({ pushState: false });
    });

    //If iframe ready to load blocks. All libraries css and js have already loaded to iframe
    self.layout.viewPort.once('iframe_loaded', function() {

        var iframe = self.layout.viewPort.getWindowIframe();

        //css is loaded to iframe
        iframe.loader.once('loaded', function() {
            // self.layout.viewPort.getWindowIframe().onbeforeunload = function() {
            //     return false;
            // };

            //  load default templates
            self.layout.viewPort.createBlankPage();

            //Start loading blocks
            self.controller.load(self.storage.pageData.blocks);
        });
        //add css styles for overlay and drop zone
        iframe.loader.add({ "name": "frontend-sktbuilder-css", "src": self.options.skinUrl + "css/sktbuilder-frontend.css", "type": "css" })

        return;

    });

    //Render layout
    jQuery('body').prepend(self.layout.render().el);

    this.layout.resize();
};


if (typeof(module) != 'undefined' && module.exports) {
    module.exports = Skin;
}