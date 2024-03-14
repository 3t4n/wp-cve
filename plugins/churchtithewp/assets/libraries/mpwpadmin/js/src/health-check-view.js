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
window.MP_WP_Admin_Health_Check_View = class MP_WP_Admin_Health_Check_View extends React.Component {

	constructor( props ){
		super(props);

		this.state = {
			lightbox_open: false,
			title: null,
			body: null
		};

	}

	componentDidMount() {

		this.setState( {
			total_unhealthy_checks: this.props.section_info.total_unhealthy_checks
		}, () => {

			if ( this.state.total_unhealthy_checks !== this.props.main_component.state.total_unhealthy_checks ) {
				// Set the number of unhealthy checks
				this.props.main_component.setState( {
					total_unhealthy_checks: this.state.total_unhealthy_checks
				} );
			}

		} );

	}

	componentDidUpdate() {

		if ( this.state.total_unhealthy_checks !== this.props.main_component.state.total_unhealthy_checks ) {
			// Set the number of unhealthy checks
			this.props.main_component.setState( {
				total_unhealthy_checks: this.state.total_unhealthy_checks
			} );
		}

	}

	set_lightbox_for_health_check( health_check_key, event ) {

		return new Promise( (resolve, reject) => {

			this.props.main_component.set_all_current_visual_states( false, {
				[health_check_key]: {}
			} ).then( () => {
				resolve();
			} );

		} );

	}

	reset_lightbox_for_health_check( health_check_key, suffix, event ) {

		// Force the "is_healthy" paramater to be false before we load the lightbox for this health check
		this.setState({
			[health_check_key + '_fixing_it_again']: true
		}, () => {

			this.setState({
				[health_check_key + '_fixing_it_again']: false
			}, () => {
				this.set_lightbox_for_health_check( health_check_key + suffix, event );
			} );

		} );

	}

	render_lightbox( health_checks, health_check_key, suffix ) {

		var wizard_steps = this.props.section_info.wizard_step_vars;

		var next_wizard_step = this.get_next_wizard_step_key( wizard_steps, health_check_key );

		if ( health_checks[health_check_key]['react_component'] ) {
			var DynamicReactComponent = eval( health_checks[health_check_key]['react_component'] );
			var dynamic_react_component = <DynamicReactComponent
				main_component={ this.props.main_component }
				data={ health_checks }
				health_check_key={ health_check_key }
				slug_suffix={ suffix }
				this_lightbox_slug={ health_check_key + suffix }
				next_lightbox_slug={ next_wizard_step ? next_wizard_step + '_wizard_step' : null }
				fixing_it_again={ this.state[health_check_key + '_fixing_it_again'] }
			/>
		} else {
			var dynamic_react_component = null;
		}

		return (
			<MP_WP_Admin_Lightbox
				main_component={ this.props.main_component }
				slug={ health_check_key + suffix }
				mode={ 'custom_react_component' }
				custom_react_component={ dynamic_react_component }
			/>
		);

	}

	get_next_wizard_step_key( wizard_steps, current_wizard_step_key ) {
		var current_key_match_found = false;

		for (var wizard_step_key in wizard_steps) {

			if ( current_key_match_found ) {
				return wizard_step_key;
			}

			if ( current_wizard_step_key == wizard_step_key ) {
				current_key_match_found = true;
			}
		}
	}

	// The Wizard steps are not shown on the health check page, but we need to render their lightboxes out
	render_wizard_steps( wizard_steps ) {

		var mapper = [];

		var total_unhealthy_checks = 0;

		// This lets us loop through the object
		for (var wizard_step_key in wizard_steps) {

			// If this check is a wizard step
			if ( wizard_steps[wizard_step_key]['is_wizard_step'] ) {
				mapper.push(
					<div key={ wizard_step_key } className={ 'mpwpadmin-wizard-step-container ' + 'mpwpadmin-wizard-step-' + wizard_step_key + '-container' }>
						{ this.render_lightbox( wizard_steps, wizard_step_key, '_wizard_step' ) }
					</div>
				)
			}
		}

		// This lets us output the health checks one by one
		return mapper.map((view, index) => {
			return view;
		})

	}

	render_unhealthy_icon( health_checks, health_check_key ) {

		var unhealthy_icon = 'dashicons-no-alt';

		if ( health_checks[health_check_key]['unhealthy']['health_check_icon'] ) {
			unhealthy_icon = health_checks[health_check_key]['unhealthy']['health_check_icon'];
		}

		return( <span className={ 'dashicons ' + unhealthy_icon }></span> );
	}

	render_health_checks( health_checks ) {

		var mapper = [];

		var total_unhealthy_checks = 0;

		// This lets us loop through the object
		for (var health_check_key in health_checks) {

			// If this check is healthy
			if ( health_checks[health_check_key]['is_healthy'] && health_checks[health_check_key]['is_health_check'] ) {

				mapper.push(
					<div key={ health_check_key } className={ 'mpwpadmin-health-check-container ' + 'mpwpadmin-health-check-' + health_check_key + '-container' }>
						<div className={ 'mpwpadmin-health-check-icon-container' }>
							<span className={ 'dashicons dashicons-yes' }></span>
						</div>
						<div className={ 'mpwpadmin-health-check-description-container' }>
							<div className={ 'mpwpadmin-health-check-description-text-container' }>
								{ health_checks[health_check_key]['healthy']['instruction'] }
							</div>
							{ ( () => {
								if ( health_checks[health_check_key]['healthy']['fix_it_again_button_text'] ) {
									return(
										<div className={ 'mpwpadmin-health-check-description-action-container' }>
											<button onClick={ this.reset_lightbox_for_health_check.bind( this, health_check_key, '_health_check' ) } className="button">{ health_checks[health_check_key]['healthy']['fix_it_again_button_text'] }</button>
										</div>
									)
								}
							} )() }
						</div>
						{ this.render_lightbox( health_checks, health_check_key, '_health_check' ) }
					</div>
				)

			}
			// If this check is unhealthy
			else {

				if ( health_checks[health_check_key]['is_health_check'] ) {
					mapper.push(
						<div key={ health_check_key } className={ 'mpwpadmin-health-check-container ' + 'mpwpadmin-health-check-' + health_check_key + '-container' }>
							<div className={ 'mpwpadmin-health-check-icon-container' }>
								{ this.render_unhealthy_icon( health_checks, health_check_key ) }
							</div>
							<div className={ 'mpwpadmin-health-check-description-container' }>
								<div className={ 'mpwpadmin-health-check-description-text-container' }>
									{ health_checks[health_check_key]['unhealthy']['instruction'] }
								</div>
								<div className={ 'mpwpadmin-health-check-description-action-container' }>
									<button onClick={ this.set_lightbox_for_health_check.bind( this, health_check_key + '_health_check' ) } className="button">{ health_checks[health_check_key]['unhealthy']['fix_it_button_text'] }</button>
								</div>
							</div>
							{ this.render_lightbox( health_checks, health_check_key, '_health_check' ) }
						</div>
					)
				}

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
			<div className={ 'mpwpadmin-settings-view' }>
				<div className="mpwpadmin-breadcrumb">
					<h2>{ this.render_breadcrumbs() }</h2>
				</div>
				{ this.render_health_checks( this.props.section_info.health_check_vars ) }
				{ this.render_wizard_steps( this.props.section_info.wizard_step_vars ) }
			</div>
		)
	}

}
