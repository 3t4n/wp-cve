class wsaeWidgetClass extends elementorModules.frontend.handlers.Base {
  getDefaultSettings() {
      return {
          selectors: {
              ampWrapper:'.wsae-wrapper',
              ampSelector:'.wsae-amp',              
          },
      };
  }

  getDefaultElements() {
      const selectors = this.getSettings( 'selectors' );
      
      return {
          $ampWrapper: this.$element.find( selectors.ampWrapper ),
          $ampSelector: this.$element.find( selectors.ampSelector ),
      };
  }

  bindEvents() {  
    var slector = this.elements.$ampWrapper;
    var ampSelector = this.elements.$ampSelector;
 
    if(slector.length>0){    
      var player = new AmpStoryPlayer(window, ampSelector[0]);
      player.load();
    }
  
  }  
  
}


jQuery( window ).on( 'elementor/frontend/init', () => {

  const addHandler = ( $element ) => {
      elementorFrontend.elementsHandler.addHandler( wsaeWidgetClass, {
        $element,           
      });
  };

  elementorFrontend.hooks.addAction( 'frontend/element_ready/webstory-widget-addon.default', addHandler );

});