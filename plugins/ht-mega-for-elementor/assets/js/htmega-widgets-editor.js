;(function($){

    // To dynamic load css in panel view
      elementor.hooks.addFilter( 'editor/style/styleText', function( css, context ) {
  
          if (!context) { return; }
  
          var model = context.model,
              generatedCss = model.get('settings').get('htmega_custom_css');
          var selector = '.elementor-element.elementor-element-' + model.get('id');
          
          if ( 'document' === model.get('elType') ) {
              selector = elementor.config.document.settings.cssWrapperSelector;
          }
  
          if ( generatedCss ) {
              css += generatedCss.replace(/selector/g, selector);
          }
  
          return css;
      });
  
      elementor.hooks.addFilter("panel/elements/regionViews", function (panel) {
  
          if ( htmegaPanelSettings.htmega_pro_installed )
              return panel;
  
          var htmegaPromoHandler, proCategoryIndex,
              elementsView = panel.elements.view,
              categoriesView = panel.categories.view,
              widgets = panel.elements.options.collection,
              categories = panel.categories.options.collection,
              htmegaPorcategroy = [];
  
          _.each(htmegaPanelSettings.htmega_pro_widgets, function (widget, index) {
              widgets.add({
                  name: widget.key,
                  //title: wp.i18n.__('HTMega Pro ', 'htmega-addons') + widget.title,
                  title: widget.title,
                  icon: widget.icon,
                  categories: ["htmega-pro-addons"],
                  editable: false
              })
          });
  
          widgets.each(function (widget) {
              "htmega-pro-addons" === widget.get("categories")[0] && htmegaPorcategroy.push(widget)
          });
  
          proCategoryIndex = categories.findIndex({
              name: "htmega-addons"
          });
  
          proCategoryIndex && categories.add({
              name: "htmega-pro-addons",
              title: "HTMega Pro Addons",
              defaultActive: 1,
              items: htmegaPorcategroy
          }, {
              at: proCategoryIndex + 1
          });
  
  
          htmegaPromoHandler = {
              className: function () {
  
                  var className = 'elementor-element-wrapper';
  
                  if (!this.isEditable()) {
                      className += ' elementor-element--promotion';
                  }
  
                  if (this.model.get("name")) {
                      if (0 === this.model.get("name").indexOf("htmega-"))
                          className += ' htmega-promotion-element';
                  }
  
                  return className;
  
              },
  
              isHTMegaWidget: function () {
                  const hasProWidget = ((typeof this.model.get("name") === 'string') && (this.model.get("name").length > 0)) ? true : false;
                  return hasProWidget;
              },
  
              getElementObj: function (key) {
  
                  var widgetObj = htmegaPanelSettings.htmega_pro_widgets.find(function (widget, index) {
                      if (widget.key == key)
                          return true;
                  });
  
                  return widgetObj;
  
              },
  
              onMouseDown: function () {
  
                  if (this.isHTMegaWidget()) {
                      void this.constructor.__super__.onMouseDown.call(this);
  
                      var widgetObject = this.getElementObj(this.model.get("name"));
                          actionURL = widgetObject?.action_url;
                      if (undefined !== actionURL ) {
                          elementor.promotion.showDialog({
                              title: sprintf(wp.i18n.__('%s', 'elementor'), this.model.get("title")),
                              content: sprintf(wp.i18n.__('Use %s widget and dozens more pro features to extend your toolbox and build sites faster and better.', 'elementor'), this.model.get("title")),
                              top: "-7",
                              targetElement: this.$el,
                              actionButton: {
                                  url: actionURL,
                                  text: wp.i18n.__('See Demo', 'elementor')
                              }
                          });
                      }
                  }
              }
          }
  
  
          panel.elements.view = elementsView.extend({
              childView: elementsView.prototype.childView.extend(htmegaPromoHandler)
          });
  
          panel.categories.view = categoriesView.extend({
              childView: categoriesView.prototype.childView.extend({
                  childView: categoriesView.prototype.childView.prototype.childView.extend(htmegaPromoHandler)
              })
          });
  
          return panel;
  
      });
  
  
  })(jQuery);