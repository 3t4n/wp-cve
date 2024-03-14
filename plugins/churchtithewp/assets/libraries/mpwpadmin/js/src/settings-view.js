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

// This component outputs the settings view and its contents
window.MP_WP_Admin_Settings_View = class MP_WP_Admin_Settings_View extends React.Component {

	constructor( props ){
		super(props);
	}

	render_settings( settings, doing_sub_setting = false ) {

		var mapper = [];

		// This lets us loop through the object
		for (var key in settings) {

			// If this area has its own sub settings
			if ( settings[key]['settings'] ) {

				// Call this function again to output all sub settings, inception style
				mapper.push(
					<div key={ key } className={ 'mpwpadmin-setting-container ' + 'mpwpadmin-setting-' + key + '-container' }>
						<h2>{ settings[key]['visual_name'] }</h2>
						{ this.render_settings( settings[key]['settings'], true ) }
					</div>
				);

			} else {

				var DynamicReactComponent = eval( settings[key]['react_component'] );

				mapper.push(
					<div key={ key } className={ 'mpwpadmin-setting-container ' + 'mpwpadmin-setting-' + key + '-container' }>
						<DynamicReactComponent
							main_component={ this.props.main_component }
							id={ key }
							slug={ key }
							props={ settings[key] }
							class_name={ 'mpwpadmin-setting mpwpadmin-setting-' + key }
							context_id={ null }
						/>
					</div>
				)
			}

		}

		// If we are doing a sub-setting, return the mapper here so it gets rejoined with the parent mapper. We're doing some method inception here.
		if ( doing_sub_setting ) {

			return mapper;

		} else {

			// This lets us output the settings one by one
			return mapper.map((view, index) => {
				return view;
			})
		}

	}

	render_breadcrumbs() {

		var breadcrumbs = this.props.the_breadcrumbs;

		var mapper = [];

		// This lets us loop through the object
		for (var key in breadcrumbs) {

			if ( key == this.props.view_slug ) {
				mapper.push( <span key={ key }>{ breadcrumbs[key] }</span>  )
				break;
			} else {
				mapper.push( <span key={ key }>{ breadcrumbs[key] } > </span>  )
			}

		}

		// This lets us output the breadcrumbs one by one
		return mapper.map((breadcrumb, index) => {
			return breadcrumb;
		})

	}

	render() {

		// If we should be refreshing the sections, unmount and remount everything.
		if ( this.props.main_component.state.refresh_sections ) {
			return ( '' );
		} else {
			return (
				<div className={ 'mpwpadmin-settings-view' + this.props.current_view_class }>
					<div className="mpwpadmin-breadcrumb">
						<h2>{ this.render_breadcrumbs() }</h2>
					</div>
					{ this.render_settings( this.props.view_info.settings ) }
				</div>
			)
		}
	}

}
