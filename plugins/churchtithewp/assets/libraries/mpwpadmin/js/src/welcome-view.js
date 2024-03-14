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
window.MP_WP_Admin_Welcome_View = class MP_WP_Admin_Welcome_View extends React.Component {

	constructor( props ){
		super(props);
	}

	render_sections( sections ) {

		var mapper = [];

		// This lets us loop through the object
		for (var section_key in sections) {

			if ( sections[section_key] ) {
				var DynamicReactComponent = eval( sections[section_key]['react_component'] );

				mapper.push(
					<div key={ section_key } className={ 'mpwpadmin-welcome-section-container ' + 'mpwpadmin-welcome-section-' + section_key + '-container' }>
						<h4>{ sections[section_key]['visual_name'] }</h4>
						<p>{ sections[section_key]['description'] }</p>
						<DynamicReactComponent
							main_component={ this.props.main_component }
							section_info={ sections[section_key] }
						/>
					</div>
				)
			}

		}

		// This lets us output the health checks one by one
		return mapper.map((view, index) => {
			return view;
		})

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

		return (
			<div className={ 'mpwpadmin-settings-view' + this.props.current_view_class }>
				<div className="mpwpadmin-breadcrumb">
					<h2>{ this.render_breadcrumbs() }</h2>
				</div>
				{ this.render_sections( this.props.view_info.sections ) }
			</div>
		)
	}

}


// This component outputs the settings view and its contents
window.MP_WP_Admin_Instruction = class MP_WP_Admin_Instruction extends React.Component {

	constructor( props ){
		super(props);
	}

	render() {

		return (
			<div className={ 'mpwpadmin-instruction' }>
				{ this.props.section_info.instruction }
			</div>
		)
	}

}
