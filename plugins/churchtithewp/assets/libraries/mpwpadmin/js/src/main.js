/*
* Church Tithe WP Admin
* https://www.churchtithewp.com
*
* Licensed under the GPL license.
*
* Author: Church Tithe WP
* Version: 1.0
* Date: April 18, 2018
*/

window.mp_wp_admin_admin_lightbox_vars = {
	title: null,
	description: null,
};

window.MP_WP_Admin = class MP_WP_Admin extends React.Component {

	constructor( props ){
		super(props);

		this.state = {
			all_initial_visual_states: this.props.data.general_config.all_current_visual_states,
			all_current_visual_states: this.props.data.general_config.all_current_visual_states, // This is an object containing the entire visual state for the Single Page App
			lightbox_initial_visual_state: this.props.data.general_config.lightbox_visual_state,
			lightbox_visual_state: this.props.data.general_config.lightbox_visual_state,
			single_page_app_base_url: this.props.data.general_config.base_url,
			dom_node: null,
			refresh_sections: false
		};

		this.get_current_view_class = this.get_current_view_class.bind( this );
		this.set_all_current_visual_states = this.set_all_current_visual_states.bind( this );
	}

	componentDidMount() {

		// Grab the props passed in and pass them to the state
		this.setState( {
			data: this.props.data
		}, () => {

			// Set the initial view state based on the initialization props
			this.setState( {
				all_initial_visual_states: this.state.data.general_config.all_current_visual_states,
				all_current_visual_states: this.state.data.general_config.all_current_visual_states, // This is an object containing the entire visual state for the Single Page App
				lightbox_initial_visual_state: this.state.data.general_config.lightbox_visual_state,
				lightbox_visual_state: this.state.data.general_config.lightbox_visual_state
			}, () => {
				this.set_all_current_visual_states( this.state.all_initial_visual_states, this.state.lightbox_visual_state );
			} );

		} );

		// Create an event listener to respond to back button clicks
		window.addEventListener('popstate', (e) => {
			this.on_web_history_change( e, this );
		});

	}

	componentDidUpdate() {
		this.maybe_refresh_parent_dom_node();
	}

	refresh_mpwpadmin() {

		return new Promise( (resolve, reject) => {

			var this_component = this;

			// Format the data that we'll send to the server
			var postData = new FormData();
			postData.append('mpwpadmin_refresh_nonce', this.state.data.general_config.mpwpadmin_refresh_nonce);

			fetch( this.state.data.general_config.server_endpoint_url_refresh_mpwpadmin, {
				method: "POST",
				mode: "same-origin",
				credentials: "same-origin",
				headers: {},
				body: postData
			} ).then(
				function( response ) {
					if ( response.status !== 200 ) {
						console.log('Looks like there was a problem. Status Code: ' +
						response.status);
						reject();
						return;
					}

					// Examine the text in the response
					response.json().then(
						function( data ) {
							// If we fetched brand new mpwpadmin data, refresh it
							if ( data.success ) {
								// If the data is exact the same, do nothing
								if ( JSON.stringify( data.data ) == JSON.stringify( this_component.state.data ) ) {
									resolve();
								} else {
									// Save the refreshed data to the state of the main component
									this_component.setState( {
										data: data.data
									}, () => {
										// Once saved, tell the section components they can/should refresh. Whether/How they do is handled by the components themselves.
										this_component.setState( {
											refresh_sections: true
										}, () => {
											// Now that the "refresh" trigger has been applied with refreshed data, set the refresh back to false for the sections so they don't loop infiniitely.
											this_component.setState( {
												refresh_sections: false
											}, () => {
												// Now that everything we want to do here is done, resolve the promise.
												resolve();
											} );
										} );
									} );
								}
							} else {
								reject();
							}
						}
					);
				}
			).catch(
				function( err ) {
					console.log('Fetch Error :-S', err);
					reject();
				}
			);

		} );

	}

	maybe_refresh_parent_dom_node() {

		if ( this.state.dom_node !== ReactDOM.findDOMNode(this).parentNode ) {
			this.setState( {
				dom_node: ReactDOM.findDOMNode(this).parentNode
			} );
		}

	}

	build_new_url_path( obj, new_url_path, depth ) {
		depth = depth + 1;
		for (var component_visual_state in obj) {
				new_url_path = this.build_new_url_path(obj[component_visual_state], new_url_path + '&mpwpadmin' + depth + '=' + component_visual_state, depth );
		}
		return new_url_path;
	}

	set_all_current_visual_states( new_state = false, new_lightbox_state = false ) {

		return new Promise( (resolve, reject) => {

			// If no new state was passed, we're probably just updating the lightbox state.
			if ( ! new_state ) {
				new_state = this.state.all_current_visual_states;
			}
			if ( ! new_lightbox_state ) {
				new_lightbox_state = this.state.lightbox_visual_state
			}

			// Start refreshing the state of the entire mpwpadmin single page app. This happens in the background, almost like magic.

			this.setState( {
				all_current_visual_states: new_state,
				lightbox_visual_state: new_lightbox_state
			}, () => {

				// If we are on mobile, make sure this app is in view as it's easy to get out of view on small screens when the height changes
				if ( 700 > screen.width ) {
					this.state.dom_node.scrollIntoView( true, {
						behavior: 'smooth'
					} );
				}

				// New URL
				var new_url = this.state.data.general_config.base_url + this.build_new_url_path( this.state.all_current_visual_states, '', 0 );

				// If there is a lightbox open, add it to the end of the URL
				if ( Object.keys(this.state.lightbox_visual_state)[0] ) {
					new_url = new_url + '&mpwpadmin_lightbox=' + Object.keys(this.state.lightbox_visual_state)[0];
				}

				// Take a snapshot of the current visual state and add it to the web history
				history.pushState({
					[this.state.data.general_config.app_slug + '_visual_state']: this.state.all_current_visual_states,
					[this.state.data.general_config.app_slug + '_lightbox_visual_state']: this.state.lightbox_visual_state
				}, new_state, new_url);

				/*
				this.refresh_mpwpadmin().then( () => {
					resolve( new_state );
				} );
				*/

			} );

		} );

	}

	on_web_history_change( e, this_component ) {

		var history_state = e.state;

		// If there's no state in the back button, we're in the initial state.
		if (history_state == null) {
			this_component.setState( {
				'all_current_visual_states': this_component.state.all_initial_visual_states,
				'lightbox_visual_state': this_component.state.initial_lightbox_visual_state
			} );
		}
		// If there is a state in the history, set the current state to that
		else {
			this_component.setState( {
				'all_current_visual_states': history_state[this.state.data.general_config.app_slug + '_visual_state'],
				'lightbox_visual_state': history_state[this.state.data.general_config.app_slug + '_lightbox_visual_state']
			} );
		}

	}

	get_current_view_class( view_in_question ) {

		var currently_in_view_class_name = 'mpwpadmin-current-view';
		var hidden_class_name = 'mpwpadmin-hidden-view';
		var current_visual_state = Object.keys(this.state.all_current_visual_states)[0]; // This grabs the name of the the first key, which is the visual state of this component

		// If the current visual state matches the view we are getting the class for
		if( current_visual_state == view_in_question ) {

			return ' ' + currently_in_view_class_name;

		} else {

			return ' ' + hidden_class_name;

		}

	}

	get_current_button_class( view_in_question ) {

		var current_button_class_name = 'mpwpadmin-current-tab';
		var current_visual_state = Object.keys(this.state.all_current_visual_states)[0]; // This grabs the name of the the first key, which is the visual state of this component

		// If the current visual state matches the view we are getting the class for
		if( current_visual_state == view_in_question ) {

			return ' ' + current_button_class_name;

		} else {

			return '';

		}

	}

	render_left_side_navigation_buttons() {

		var current_visual_state = Object.keys(this.state.all_current_visual_states)[0]; // This grabs the name of the the first key, which is the visual state of this component

		var mapper = [];

		// This lets us loop through the object
		for (var key in this.state.data.views) {

			mapper.push( <MP_WP_Admin_View_Button
				key={key}
				main_component={ this }
				view_slug={key}
				view_info={ this.state.data.views[key] }
				is_top_level={ true } />
			)

		}

		// This lets us output the buttons one by one
		return mapper.map((view, index) => {
			return view;
		})
	}

	render_actual_views( views, doing_sub_tabs = false, breadcrumbs = [] ) {

		var current_visual_state = Object.keys(this.state.all_current_visual_states)[0]; // This grabs the name of the the first key, which is the visual state of this component

		var mapper = [];

		// This lets us loop through the object
		for (var key in views ) {

			var DynamicReactComponent = eval( views[key]['react_component'] );

			// Append this section's title to the breadcrumbs
			breadcrumbs[key] = views[key]['visual_name'];

			mapper.push( <DynamicReactComponent
				key={ key }
				main_component={ this }
				view_slug={ key }
				view_info={ views[key] }
				current_view_class={ this.get_current_view_class( key ) }
				the_breadcrumbs={ breadcrumbs }
			/> )

			var sub_tabs = views[key]['sub_tabs'];

			// If this section (even if this is a subsection) has subsections within/below it
			if ( sub_tabs ) {

				mapper.push( this.render_actual_views( sub_tabs, true, breadcrumbs ) );

				// We're done with all the subsections in this section
				breadcrumbs = [];
			} else {
				breadcrumbs = [];
			}
		}

		// If we are doing a sub-section, return the mapper here so it gets rejoined with the parent mapper. We're doing some method inception here.
		if ( doing_sub_tabs ) {

			return mapper;

		} else {

			// This lets us output the buttons one by one
			return mapper.map((view, index) => {
				return view;
			})
		}
	}

	render() {

		if ( ! this.state.data || ! this.state.all_current_visual_states ) {
			return( '' );
		}

		var current_visual_state = Object.keys(this.state.all_current_visual_states)[0]; // This grabs the name of the the first key, which is the visual state of this component

		return (
			<div className={ 'mpwpadmin-container mpwpadmin-current-view-is-' + current_visual_state }>
				<div className="mpwpadmin-left-side-navigation">
					<ul>
						{ this.render_left_side_navigation_buttons() }
					</ul>
				</div>

				<div className='mpwpadmin-current-view-container'>
					{ this.render_actual_views( this.state.data.views ) }
				</div>

				<MP_WP_Admin_Lightbox />

			</div>
		);
	}
}

// This component outputs all of the left-size navigation
window.MP_WP_Admin_View_Button = class MP_WP_Admin_View_Button extends React.Component {

	constructor( props ){
		super(props);
	}

	render_submenu() {

		var sub_menus = this.props.view_info.sub_tabs;

		if ( ! sub_menus ) {
			return false;
		}

		var mapper = [];

		// This lets us loop through the object
		for (var key in sub_menus) {

			var view_info = sub_menus[key] ? sub_menus[key] : false;

			mapper.push(
				<MP_WP_Admin_View_Button
					key={ key }
					main_component={ this.props.main_component }
					view_slug={ key }
					view_info={ view_info }
					is_top_level={ false }
				/>
			)

		}

		// This lets us output the buttons one by one
		return mapper.map((view, index) => {
			return view;
		})

	}

	handle_button_click( new_state, event ) {
		this.props.main_component.set_all_current_visual_states( {
			[this.props.view_slug]: {}
		} );
	}

	render() {

		if ( this.props.is_top_level ) {
			return (
				<li className={ "mpwpadmin-left-tab-button" + this.props.main_component.get_current_button_class( this.props.view_slug )  }>
					<button onClick={ this.handle_button_click.bind( this, {
						[this.props.view_slug]: {}
					} ) }>
						<span className="mpwpadmin-left-tab-text">{ this.props.view_info.visual_name }</span>
					</button>
					<ul>
						{ this.render_submenu() }
					</ul>
				</li>
			);
		} else {
			return (
				<li className={ "mpwpadmin-left-tab-button-subtab" + this.props.main_component.get_current_button_class( this.props.view_slug )  }>
					<button onClick={ this.handle_button_click.bind( this, {
						[this.props.view_slug]: {}
					} ) }>
						<i className="mpwpadmin-left-tab-arrow"></i>
						<span className="mpwpadmin-left-tab-text">{ this.props.view_info.visual_name }</span>
					</button>
					<ul>
						{ this.render_submenu() }
					</ul>
				</li>
			);
		}
	}
}

window.MP_WP_Admin_Lightbox = class MP_WP_Admin_Lightbox extends React.Component {

	constructor( props ){
		super(props);

		this.state = {
			has_mounted: false,
			lightbox_open: false
		}

		this.handle_key_press = this.handle_key_press.bind( this );
		this.toggle_lightbox = this.toggle_lightbox.bind( this );
	}

	componentDidMount() {
		this.handle_open_status_based_on_url();
	}

	componentDidUpdate() {

		if ( ! this.state.lightbox_open ) {
			// Remove a listener for the ESC key when the lightbox is closed
			document.removeEventListener("keydown", this.handle_key_press, false);
		} else {

			if ( ! this.state.has_mounted ) {
				this.setState( {
					has_mounted: true
				} );
			}

			// Add a listener for the ESC key when the lightbox is open
			document.addEventListener("keydown", this.handle_key_press, false);
		}

		this.handle_open_status_based_on_url();
	}

	handle_open_status_based_on_url() {

		// If a lightbox is open based on the setting in the main component
		if ( this.props.main_component && this.props.main_component.state.lightbox_visual_state ) {
			// Check if that lightbox is us!
			if ( this.props.slug == Object.keys(this.props.main_component.state.lightbox_visual_state)[0] ) {
				// Open this lightbox if it isn't already open
				if ( ! this.state.lightbox_open ) {
					this.setState( {
						lightbox_open:  true
					} );
				}
			}
			// If the current lightbox in the URL is not us, close this one.
			else {
				if ( this.state.lightbox_open ) {
					this.setState( {
						lightbox_open:  false
					} );
				}
			}
		}
	}

	get_lightbox_visible_class() {
		if ( this.state.lightbox_open ) {
			return ' mpwpadmin-lightbox-open';
		} else {
			return ' mpwpadmin-lightbox-closed';
		}
	}

	toggle_lightbox( state ) {

		// If the lightbox is open, close it
		if ( state.lightbox_open ) {
			this.props.main_component.set_all_current_visual_states( false, {} );
		} else {
			this.props.main_component.set_all_current_visual_states( false, {
				[this.props.slug]: {}
			} );
		}

	}

	handle_key_press( event ) {

		if( event.keyCode === 27 ) {
			this.toggle_lightbox( this.state );
		}

	}

	handle_close_button_click() {
		this.props.main_component.set_all_current_visual_states( false, {} );
	}

	render_close_button() {

		return (
			<div className="mpwpadmin-close-btn" aria-label="Close" onClick={ this.handle_close_button_click.bind( this ) }><span className="dashicons dashicons-no"></span></div>
		);
	}

	render_icon() {

		if ( this.props.main_component && this.props.main_component.state.data.general_config.default_icon ) {
			return (
				<div className={ 'mpwpadmin-lightbox-icon-container' }>
					<div className="mpwpadmin-lightbox-icon">
						<img src={ this.props.main_component.state.data.general_config.default_icon } />
					</div>
				</div>
			)
		}
	}

	render_title() {

		if ( this.props.title ) {
			return this.props.title;
		} else {
			return '';
		}
	};

	render_body() {

		return (
			<React.Fragment>
				{ this.render_icon() }
				<h2 className={ 'mpwpadmin-lightbox-input-title' }>{ this.render_title() }</h2>
				<div className={ 'mpwpadmin-lightbox-input-description' }>
					{ this.props.body }
				</div>
			</React.Fragment>
		);

	};

	render_component() {
		return ( this.props.custom_react_component );
	};

	render_based_on_mode() {

		if ( 'custom_react_component' == this.props.mode ) {
			return this.render_component();
		}

		return this.render_body();
	}

	render() {

		return (
			<div className={ 'mpwpadmin-lightbox-background mpwpadmin-lightbox' + this.get_lightbox_visible_class() }>
				<div className={ 'mpwpadmin-lightbox-outside-click-to-close' } onClick={ this.toggle_lightbox.bind( null, this.state ) } />
				<div className={ 'mpwpadmin-lightbox-relative' }>
					<div className={ 'mpwpadmin-lightbox-absolute' }>
						<div className={ 'mpwpadmin-lightbox-inner' }>
							{ this.render_close_button() }
							{ this.render_based_on_mode() }
						</div>
					</div>
				</div>
			</div>
		);
	}
}

window.MP_WP_Admin_Spinner = class MP_WP_Admin_Spinner extends React.Component{
	render(){
		return(
			<div className="mpwpadmin-spinner">
				<div className="mpwpadmin-double-bounce1"></div>
				<div className="mpwpadmin-double-bounce2"></div>
			</div>
		)
	}
}

// Accepts an all visual states object, a visual tree object acting as a map for the location of the component in question, and a boolean
// It then recursively goes throguh the component tree object, checking if
function mpwpadmin_visual_state_should_become( all_current_visual_states, map_of_visual_states, visual_state_should_become, default_visual_state ) {

	// The component tree is a "map" that tells use where this component lives within the parent
	for ( var level in map_of_visual_states ) {
		// If the current component's top-level parent is set in the current visual states object, great! Keep going.
		if ( all_current_visual_states[level] ) {

			// If there are other components ahead of this component in the tree
			if ( typeof map_of_visual_states[level] !== 'undefined' && typeof map_of_visual_states[level] === 'object' && Object.keys(map_of_visual_states[level]).length !== 0){
				// Recursively nest down into the next parent component to see if it is set in the current visual states object.
				visual_state_should_become = mpwpadmin_visual_state_should_become( all_current_visual_states[level], map_of_visual_states[level], visual_state_should_become, default_visual_state );
			} else {

				// If we are at the end of the component tree, and the component level is in the current visual states object, it's in view!
				visual_state_should_become = level;

			}
		} else if ( 'variable' === level ) {

			// If we are at the end of the component tree, and the component level is variable, grab the value from the master view object at this level
			if ( Object.keys(all_current_visual_states)[0] ) {
				visual_state_should_become = Object.keys(all_current_visual_states)[0];
			} else {
				visual_state_should_become = default_visual_state;
			}

		} else {
			visual_state_should_become = default_visual_state;
		}

	}

	return visual_state_should_become;
}

window.mpwpadmin_set_visual_state_of_component = function mpwpadmin_set_visual_state_of_component( settings ) {

	return new Promise( (resolve, reject) => {

		// If we don't have the variables we require, fail.
		if ( ! settings.component ) {
			throw new Error( 'The function "mpwpadmin_set_visual_state_of_component" is missing required variables' );
		}

		if ( ! settings.component.props.main_component.state.all_current_visual_states ) {
			throw new Error( 'A visual states object is required.' );
		}

		if ( ! settings.component.state.map_of_visual_states ) {
			throw new Error( 'The component must have a map of the view states stored in the state with the key "map_of_visual_states"' );
		}

		var new_map_of_visual_states = mpwpadmin_get_default_map_of_visual_states( settings.component.state.map_of_visual_states, settings.default_visual_state );

		// Figure out if the parent component is in view
		for ( var level in settings.component.props.main_component.state.all_current_visual_states ) {
			if ( ! new_map_of_visual_states[level] ) {
				parent_component_is_in_view = false;
				break;
			} else {
				parent_component_is_in_view = true;
			}
		}

		// If the parent component is in view, just set it to a default and do nothing else
		if ( ! parent_component_is_in_view ) {
			var visual_state_should_become = settings.default_visual_states.parent_not_in_view;
		} else {
			var visual_state_should_become = mpwpadmin_visual_state_should_become( settings.component.props.main_component.state.all_current_visual_states, settings.component.state.map_of_visual_states, false, settings.default_visual_states.parent_in_view );
		}

		// If the state of the component is already the current state in the master visual states object, do nothing.
		if ( settings.component.state[settings.name_of_visual_state_variable] == visual_state_should_become ) {

			resolve( visual_state_should_become );
			return;

		} else {

			if ( ! visual_state_should_become ) {

				settings.component.setState( {
					[settings.name_of_visual_state_variable]: settings.default_visual_states.parent_not_in_view
				}, function() {
					//console.log( 'setting default state to: ' + settings.default_visual_states.parent_not_in_view + ' where default was ' + settings.default_visual_states.parent_in_view );
					resolve( settings.default_visual_states.parent_not_in_view );
				} );

			} else {

				settings.component.setState( {
					[settings.name_of_visual_state_variable]: visual_state_should_become
				}, function() {
					//console.log( 'setting visual state to: ' + visual_state_should_become + ' where default was ' + settings.default_visual_states.parent_in_view );
					resolve( visual_state_should_become );
				} );

			}

		}

	});

}

function mpwpadmin_get_default_map_of_visual_states( map_of_visual_states, default_visual_state, new_map_of_visual_states = {}, previous_level = false ) {

	for ( var level in map_of_visual_states ) {

		// If there's another level, keep going
		if ( typeof map_of_visual_states[level] !== 'undefined' && typeof map_of_visual_states[level] === 'object' && Object.keys(map_of_visual_states[level]).length !== 0) {

			// If we at a level great than 1
			if ( previous_level ) {
				var temp = {};
				temp[level] = {};
				new_map_of_visual_states[previous_level] = mpwpadmin_get_default_map_of_visual_states( map_of_visual_states[level], default_visual_state, temp, level );
			}
			// If we are at the top level
			else {
				new_map_of_visual_states[level] = {};
				new_map_of_visual_states = mpwpadmin_get_default_map_of_visual_states( map_of_visual_states[level], default_visual_state, new_map_of_visual_states, level );
			}

			return new_map_of_visual_states;
		}
		// If the last level is reached and it's variable, or empty, set it to the default state and be done. No more nesting and looping.
		else if ( 'variable' === level || 0 === Object.keys(map_of_visual_states[level]).length) {
			new_map_of_visual_states[previous_level][default_visual_state] = {};
			return new_map_of_visual_states;
		}
		// If there's no more levels, and the last level isn't "variable", set it to the current level and be done. No more nesting and looping.
		else {
			new_map_of_visual_states[previous_level][level] = {};
			return new_map_of_visual_states;
		}
	}

}
