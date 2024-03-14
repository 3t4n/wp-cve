const {registerBlockType} = wp.blocks; //Blocks API
const {createElement,useState} = wp.element; //React.createElement
const {__} = wp.i18n; //translation functions
const {InspectorControls} = wp.blockEditor; //Block inspector wrapper
const {TextControl,SelectControl,ServerSideRender,PanelBody,ToggleControl} = wp.components; //WordPress form inputs and server-side renderer
const el = wp.element.createElement;
const iconEl = el('svg', { width: 20, height: 20 },
  	el('rect',{fill:"none",height:"24",width:"24"}),
  	el('rect',{height:"4",width:"4",x:"10",y:"4"}),
  	el('rect',{height:"4",width:"4",x:"4",y:"16"}),
  	el('rect',{height:"4",width:"4",x:"4",y:"10"}),
  	el('rect',{height:"4",width:"4",x:"4",y:"4"}),
  	el('rect',{height:"4",width:"4",x:"16",y:"4"}),
  	el('polygon', { points: "11,17.86 11,20 13.1,20 19.08,14.03 16.96,11.91" } ),
  	el('polygon', { points: "14,12.03 14,10 10,10 10,14 12.03,14" } ),
 	// el('polygon', { points: "11,17.86 11,20 13.1,20 19.08,14.03 16.96,11.91" } ),
  	el('path', { d: "M20.85,11.56l-1.41-1.41c-0.2-0.2-0.51-0.2-0.71,0l-1.06,1.06l2.12,2.12l1.06-1.06C21.05,12.07,21.05,11.76,20.85,11.56z" } )
);

var epEvents = '';
wp.apiFetch( { path: 'eventprime/v1/events' } ).then( ( events ) => {
    epEvents = events;
});

// event calendar block
registerBlockType( 'eventprime-blocks/event-calendar', {
	title: __( 'EventPrime Event Calendar' ), // Block title.
	category:  __( 'widgets' ), //category
        icon: iconEl,
        supports: {
		customClassName: false,
		className: false,
		html: false
	},
	edit(props){
		//Display block preview and UI
		return createElement('div', {}, [
			//Preview a block with a PHP render callback
			createElement( wp.serverSideRender, {
				block: 'eventprime-blocks/event-calendar'
			} )
		] )
	},
	save(){
		return null;//save has to exist. This all we need
	}
});

// event countdown block
registerBlockType( 'eventprime-blocks/event-countdown', {
	title: __( 'EventPrime Event Countdown' ), // Block title.
	category:  __( 'widgets' ), //category
        icon: iconEl,
        supports: {
		customClassName: false,
		className: false,
		html: false
	},
	attributes:  {
		eid : {
			default:( epEvents == '' ) ? 0 : epEvents[0].id,
			type: 'string',
		}
	},
	edit(props){
		const attributes =  props.attributes;
		const setAttributes =  props.setAttributes;
		//Function to update id attribute
		function changeEid( eid ) {
			setAttributes( { eid } );
		}
		return createElement('div', {}, [
			//Preview a block with a PHP render callback
			createElement( wp.serverSideRender, {
				block: 'eventprime-blocks/event-countdown',
				attributes: attributes
			} ),
			//Block inspector
			createElement( InspectorControls, {},
				[
					createElement( PanelBody, { title: 'Event Countdown Timer Settings', initialOpen: true },
						createElement(SelectControl, {
							value: attributes.eid,
							label: __( 'EventPrime Events' ),
							help:__('Select Event whose countdown timer you wish to display here.','eventprime-event-calendar-management'),
							onChange: changeEid,
							options:epEvents
						})
					)
				]
			),
		] )
	},
	save(){
		return null;//save has to exist. This all we need
	}
});

// event slider block
registerBlockType( 'eventprime-blocks/event-slider', {
	title: __( 'EventPrime Event Slider' ), // Block title.
	category:  __( 'widgets' ), //category
        icon: iconEl,
        supports: {
		customClassName: false,
		className: false,
		html: false
	},
	edit(props){
		return createElement('div', {}, [
			createElement( wp.serverSideRender, {
				block: 'eventprime-blocks/event-slider'
			} )
		] )
	},
	save(){
		return null;//save has to exist. This all we need
	}
});

// featured event organizer block
registerBlockType( 'eventprime-blocks/featured-event-organizers', {
	title: __( 'EventPrime Featured Event Organizers' ), // Block title.
	category:  __( 'widgets' ), //category
        icon: iconEl,
        supports: {
		customClassName: false,
		className: false,
		html: false
	},
	edit(props){
		const attributes =  props.attributes;
		const setAttributes =  props.setAttributes;
		function changeTitle( title ) {
			setAttributes( { title } );
		}   
		function changeNumber( number ){
			setAttributes( { number } );
		}    
		return createElement('div', {}, [
			createElement( wp.serverSideRender, {
				block: 'eventprime-blocks/featured-event-organizers',
				attributes: attributes
			} ),
			//Block inspector
			createElement( InspectorControls, {},
				[
					createElement( PanelBody, { title: 'EventPrime Featured Event Organizers', initialOpen: true },
						createElement(TextControl, {
							value: attributes.title,
							label: __( 'EventPrime Featured Event Organizers', 'eventprime-event-calendar-management' ),
							help: __( 'Enter title you wish to display here.', 'eventprime-event-calendar-management' ),
							onChange: changeTitle,
							//options:epEvents
						}),
						createElement(TextControl, {
							value: attributes.number,
							help: __( 'Number of organizers to show', 'eventprime-event-calendar-management' ),
							label: __( 'Number of organizers to show', 'eventprime-event-calendar-management' ),
							onChange: changeNumber,
						}),
					)
				]
			),
		] )
	},
	save(){
		return null;//save has to exist. This all we need
	}
});

// featured event performers block
registerBlockType( 'eventprime-blocks/featured-event-performers', {
	title: __( 'EventPrime Featured Event Performers' ), // Block title.
	category:  __( 'widgets' ), //category
        icon: iconEl,
        supports: {
		customClassName: false,
		className: false,
		html: false
	},
	edit(props){
		const attributes =  props.attributes;
		const setAttributes =  props.setAttributes;
		function changeTitle( title ) {
			setAttributes( { title } );
		}   
		function changeNumber( number ) {
			setAttributes( { number } );
		}          
		return createElement('div', {}, [
			createElement( wp.serverSideRender, {
				block: 'eventprime-blocks/featured-event-performers',
				attributes: attributes
			} ),
			//Block inspector
			createElement( InspectorControls, {},
				[
					createElement( PanelBody, { title: 'EventPrime Featured Event Performers', initialOpen: true },
						createElement(TextControl, {
							value: attributes.title,
							label: __( 'EventPrime Featured Event Performers','eventprime-event-calendar-management'),
							help:__('Enter title you wish to display here.','eventprime-event-calendar-management'),
							onChange: changeTitle,
							//options:epEvents
						}),
						//Number
						createElement(TextControl, {
							value: attributes.number,
							help:__('Number of performers to show' ,'eventprime-event-calendar-management'),
							label: __( 'Number of performers to show' ,'eventprime-event-calendar-management'),
							onChange: changeNumber,
						}),
					)
				]
			),
		] )
	},
	save(){
		return null;//save has to exist. This all we need
	}
});

// featured event types block
registerBlockType( 'eventprime-blocks/featured-event-types', {
	title: __( 'EventPrime Featured Event Types' ), // Block title.
	category:  __( 'widgets' ), //category
        icon: iconEl,
        supports: {
		customClassName: false,
		className: false,
		html: false
	},
	edit(props){
		const attributes =  props.attributes;
		const setAttributes =  props.setAttributes;
		function changeTitle( title ) {
			setAttributes( { title } );
		}   
		//Function to update number attribute
		function changeNumber( number ){
			setAttributes( { number } );
		}           
		return createElement('div', {}, [
			createElement( wp.serverSideRender, {
				block: 'eventprime-blocks/featured-event-types',
				attributes: attributes
			} ),
			createElement( InspectorControls, {},
				[
					createElement( PanelBody, { title: 'EventPrime Featured Event Types', initialOpen: true },
						createElement(TextControl, {
							value: attributes.title,
							label: __( 'EventPrime Featured Event Types', 'eventprime-event-calendar-management' ),
							help: __('Enter title you wish to display here.', 'eventprime-event-calendar-management' ),
							onChange: changeTitle,
						}),
						createElement(TextControl, {
							value: attributes.number,
							help: __( 'Number of event types to show', 'eventprime-event-calendar-management' ),
							label: __( 'Number of event types to show', 'eventprime-event-calendar-management' ),
							onChange: changeNumber,
						}),
					)
				]
			),
		] )
	},
	save(){
		return null;//save has to exist. This all we need
	}
});

// featured event venues block
registerBlockType( 'eventprime-blocks/featured-event-venues', {
	title: __( 'EventPrime Featured Event Venues' ), // Block title.
	category:  __( 'widgets' ), //category
        icon: iconEl,
        supports: {
		customClassName: false,
		className: false,
		html: false
	},
	edit(props){
		const attributes =  props.attributes;
		const setAttributes =  props.setAttributes;
		function changeTitle( title ) {
			setAttributes( { title } );
		}   
		function changeNumber( number ){
			setAttributes( { number } );
		}             
		return createElement('div', {}, [
			createElement( wp.serverSideRender, {
				block: 'eventprime-blocks/featured-event-venues',
				attributes: attributes
			} ),
			createElement( InspectorControls, {},
				[
					createElement( PanelBody, { title: 'EventPrime Featured Event Venues', initialOpen: true },
						createElement(TextControl, {
							value: attributes.title,
							label: __( 'EventPrime Featured Event Venues', 'eventprime-event-calendar-management' ),
							help: __( 'Enter title you wish to display here.', 'eventprime-event-calendar-management' ),
							onChange: changeTitle,
						}),
						createElement(TextControl, {
							value: attributes.number,
							help: __( 'Number of venues to show', 'eventprime-event-calendar-management' ),
							label: __( 'Number of venues to show', 'eventprime-event-calendar-management' ),
							onChange: changeNumber,
						}),
					)
				]
			),
		] )
	},
	save(){
		return null;//save has to exist. This all we need
	}
});

// popular event organizers block
registerBlockType( 'eventprime-blocks/popular-event-organizers', {
	title: __( 'EventPrime Popular Event Organizers' ), // Block title.
	category:  __( 'widgets' ), //category
        icon: iconEl,
        supports: {
		customClassName: false,
		className: false,
		html: false
	},
	edit(props){
		const attributes =  props.attributes;
		const setAttributes =  props.setAttributes;
		function changeTitle( title ){
			setAttributes( { title } );
		}   
		function changeNumber( number ){
			setAttributes( { number } );
		}       
		return createElement('div', {}, [
			createElement( wp.serverSideRender, {
				block: 'eventprime-blocks/popular-event-organizers',
				attributes: attributes
			} ),
			createElement( InspectorControls, {},
				[
					createElement( PanelBody, { title: 'EventPrime Popular Event Organizers', initialOpen: true },
						createElement(TextControl, {
							value: attributes.title,
							label: __( 'EventPrime Popular Event Organizers', 'eventprime-event-calendar-management' ),
							help: __( 'Enter title you wish to display here.', 'eventprime-event-calendar-management' ),
							onChange: changeTitle,
						}),
						createElement(TextControl, {
							value: attributes.number,
							help: __( 'Number of organizers to show', 'eventprime-event-calendar-management' ),
							label: __( 'Number of organizers to show', 'eventprime-event-calendar-management' ),
							onChange: changeNumber,
						}),
					)
				]
			),
		] )
	},
	save(){
		return null;//save has to exist. This all we need
	}
});

// popular event performers block
registerBlockType( 'eventprime-blocks/popular-event-performers', {
	title: __( 'EventPrime Popular Event Performers' ), // Block title.
	category:  __( 'widgets' ), //category
        icon: iconEl,
        supports: {
		customClassName: false,
		className: false,
		html: false
	},
	edit(props){
        const attributes =  props.attributes;
		const setAttributes =  props.setAttributes;
		function changeTitle( title ){
			setAttributes( { title } );
		}   
		function changeNumber( number ) {
			setAttributes( { number } );
		}            
		return createElement('div', {}, [
			createElement( wp.serverSideRender, {
				block: 'eventprime-blocks/popular-event-performers',
				attributes: attributes
			} ),
			createElement( InspectorControls, {},
				[
					createElement( PanelBody, { title: 'EventPrime Popular Event Performers', initialOpen: true },
						createElement(TextControl, {
							value: attributes.title,
							label: __( 'EventPrime Popular Event Performers', 'eventprime-event-calendar-management' ),
							help: __( 'Enter title you wish to display here.', 'eventprime-event-calendar-management' ),
							onChange: changeTitle,
						}),
						createElement(TextControl, {
							value: attributes.number,
							help: __( 'Number of performers to show', 'eventprime-event-calendar-management' ),
							label: __( 'Number of performers to show', 'eventprime-event-calendar-management' ),
							onChange: changeNumber,
						}),
					)
				]
			),
		] )
	},
	save(){
		return null;//save has to exist. This all we need
	}
});

// popular event types block
registerBlockType( 'eventprime-blocks/popular-event-types', {
	title: __( 'EventPrime Popular Event Types' ), // Block title.
	category:  __( 'widgets' ), //category
        icon: iconEl,
        supports: {
		customClassName: false,
		className: false,
		html: false
	},
	edit(props){
		const attributes =  props.attributes;
		const setAttributes =  props.setAttributes;
		function changeTitle( title ) {
			setAttributes( { title } );
		}   
		function changeNumber( number ) {
			setAttributes( { number } );
		}        
		return createElement('div', {}, [
			createElement( wp.serverSideRender, {
				block: 'eventprime-blocks/popular-event-types',
				attributes: attributes
			} ),
			createElement( InspectorControls, {},
				[
					createElement( PanelBody, { title: 'EventPrime Popular Event Types', initialOpen: true },
						createElement(TextControl, {
							value: attributes.title,
							label: __( 'EventPrime Popular Event Types', 'eventprime-event-calendar-management' ),
							help: __( 'Enter title you wish to display here.', 'eventprime-event-calendar-management' ),
							onChange: changeTitle,
						}),
						createElement(TextControl, {
							value: attributes.number,
							help: __( 'Number of event types to show', 'eventprime-event-calendar-management' ),
							label: __( 'Number of event types to show', 'eventprime-event-calendar-management' ),
							onChange: changeNumber,
						}),
					)
				]
			),
		] )
	},
	save(){
		return null;//save has to exist. This all we need
	}
});

// popular event venues block
registerBlockType( 'eventprime-blocks/popular-event-venues', {
	title: __( 'EventPrime Popular Event Venues' ), // Block title.
	category:  __( 'widgets' ), //category
        icon: iconEl,
        supports: {
		customClassName: false,
		className: false,
		html: false
	},
	edit(props){
		const attributes =  props.attributes;
		const setAttributes =  props.setAttributes;
		function changeTitle( title ){
			setAttributes( { title } );
		}   
		function changeNumber( number ){
			setAttributes( { number } );
		}       
		return createElement('div', {}, [
			createElement( wp.serverSideRender, {
				block: 'eventprime-blocks/popular-event-venues',
				attributes: attributes
			} ),
			createElement( InspectorControls, {},
				[
					createElement( PanelBody, { title: 'EventPrime Popular Event Venues', initialOpen: true },
						createElement(TextControl, {
							value: attributes.title,
							label: __( 'EventPrime Popular Event Venues', 'eventprime-event-calendar-management' ),
							help: __( 'Enter title you wish to display here.', 'eventprime-event-calendar-management' ),
							onChange: changeTitle,
						}),
						createElement(TextControl, {
							value: attributes.number,
							help: __( 'Number of venues to show', 'eventprime-event-calendar-management' ),
							label: __( 'Number of venues to show', 'eventprime-event-calendar-management' ),
							onChange: changeNumber,
						}),
					)
				]
			),
		] )
	},
	save(){
		return null;//save has to exist. This all we need
	}
});