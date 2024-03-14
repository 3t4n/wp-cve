//Object.assign polyfill
if (typeof Object.assign !== 'function') {
  // Must be writable: true, enumerable: false, configurable: true
  Object.defineProperty(Object, "assign", {
    value: function assign(target, varArgs) { // .length of function is 2
      'use strict';
      if (target === null || target === undefined) {
        throw new TypeError('Cannot convert undefined or null to object');
      }

      var to = Object(target);

      for (var index = 1; index < arguments.length; index++) {
        var nextSource = arguments[index];

        if (nextSource !== null && nextSource !== undefined) { 
          for (var nextKey in nextSource) {
            // Avoid bugs when hasOwnProperty is shadowed
            if (Object.prototype.hasOwnProperty.call(nextSource, nextKey)) {
              to[nextKey] = nextSource[nextKey];
            }
          }
        }
      }
      return to;
    },
    writable: true,
    configurable: true
  });
}

var el = wp.element.createElement;
var registerStore = wp.data.registerStore;
var withSelect = wp.data.withSelect;

const store = registerStore( 'wp-manychat/manychat-embed', {
reducer: function( state, action ) {
    if (typeof state === 'undefined'){
        state = {mcWidgets: []};
    }
    if (action.type === 'SET_MC_WIDGETS') {
        newState =  Object.assign({}, state, {mcWidgets : action.mcWidgets});
        return newState;
    }

    return state;
},

actions: {
    setMcWidgets( mcWidgets ) {
        return {
            type: 'SET_MC_WIDGETS',
            mcWidgets: mcWidgets
        };
    },
},
selectors: {
    getManychatWidgets:function(state){
        return state.mcWidgets;
    }
}
});

wp.blocks.registerBlockType('wp-manychat/manychat-embed', {
    title: 'Manychat',		// Block name visible to the user within the editor
    icon: 'format-status',	// Toolbar icon displayed beneath the name of the block
    category: 'common',	// The category under which the block will appear in the Add block menu
    attributes: {			// The data this block will be storing
        type: { type: 'string', default: '' },
    },
    edit: withSelect( ( select ) => {
            return {
                mcWidgets: select('wp-manychat/manychat-embed').getManychatWidgets(),
            };
        } )(function(props) {
        // Defines how the block will render in the editor

        function updateType( newdata ) {
            props.setAttributes( { type: event.target.value } );
        }

        return el( 'div', 
            { 
                
            }, 
            el(
                'select', 
                {
                    onChange: updateType,
                    value: props.attributes.type,
                },
                props.mcWidgets.map(function(current){
                    return el("option", {value: current.widgetId}, current.name);
                })
            )

        );	// End return

    }),	// End edit()
    save: function(props) {
        // Defines how the block will render on the frontend
        return el( 'div', 
            { 
                'data-widget-id': props.attributes.type,
                className: 'mcwidget-embed'
            },
        );  // End return
    }   // End save()
});

window.mcAsyncInit = function() {
    var widgetList = MC.getWidgetList();
    var filteredWidgetList = widgetList.filter(widget => widget.type !== 'checkbox');
    wp.data.dispatch('wp-manychat/manychat-embed').setMcWidgets(filteredWidgetList);
};

