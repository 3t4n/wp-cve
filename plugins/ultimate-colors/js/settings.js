/* global Ultimate_Colors */

/**
 * A Backbone app that handle add/remove List.
 */
(function () {
	'use strict';

	// Element model.
	var Element = Backbone.Model.extend( {
		defaults: {
			label: '',
			selector: '',
			property: ''
		}
	} );

	// Element collection.
	var Elements = Backbone.Collection.extend( {
		model: Element
	} );

	// Create our global collection of elements.
	var elements = new Elements();

	// Element view.
	var ElementView = Backbone.View.extend( {
		className: 'ultimate-colors-element',
		template: wp.template( 'ultimate-colors-element' ),
		events: {
			'click .ultimate-colors-element__delete': 'delete'
		},

		// The ElementView listens for changes to its model.
		initialize: function () {
			this.listenTo( this.model, 'destroy', this.remove );
		},

		// Delete the element, destroy the model and remove its view.
		delete: function () {
			this.model.destroy( {} );
		},

		// Render the HTML of the element.
		render: function () {
			var data = this.model.toJSON();
			data.index = this.model.cid;
			this.$el.html( this.template( data ) );
			return this;
		}
	} );

	// The application.
	var AppView = Backbone.View.extend( {

		// Instead of generating a new element, bind to the existing skeleton of the App already present in the HTML.
		el: '#ultimate-colors-elements',

		// Delegated events for creating new elements.
		events: {
			'click #ultimate-colors-add': 'create'
		},

		// At initialization we bind to the relevant events on the element collection, when items are added. Kick things off by loading any existing elements.
		initialize: function () {
			this.$button = this.$( '#ultimate-colors-add' );
			this.listenTo( elements, 'add', this.add );
			// Get elements from WordPress settings.
			elements.add( Ultimate_Colors.elements );
		},

		// Add a single element to the list by creating a view for it, and appending its element to the list.
		add: function ( element ) {
			var view = new ElementView( {model: element} );
			this.$button.before( view.render().el );
		},

		// Create new element.
		create: function () {
			elements.create();
		}
	} );

	// Creating the app.
	new AppView();
})();
