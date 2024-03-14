var church_tithe_wp_vars = church_tithe_wp_js_vars.tithe_form_vars;

window.Church_Tithe_WP_Manage_Payments_Nav = class Church_Tithe_WP_Manage_Payments_Nav extends React.Component {

	constructor( props ){
		super(props);

		this.state = {};

	}

	set_view_to_transactions() {
		this.props.main_component.set_all_current_visual_states( {
			manage_payments: {
				transactions: {}
			}
		} )
	}

	set_view_to_arrangements() {
		this.props.main_component.set_all_current_visual_states( {
			manage_payments: {
				arrangements: {}
			}
		} )
	}

	get_current_button_class( button_in_question ) {
		if ( this.props.current_visual_state == button_in_question ) {
			return ' church-tithe-wp-manage-nav-current-btn';
		} else {
			return '';
		}
	}

	render() {

		if ( this.props.main_component.state.user_logged_in ) {
			return(
				<div className="church-tithe-wp-manage-payments-nav-container-full">
					<div className="church-tithe-wp-manage-payments-nav-container-center">
						<div className="church-tithe-wp-manage-payments-nav">
							<div className={ "church-tithe-wp-arrangements-btn" + this.get_current_button_class( 'arrangements' ) }>
								<button className="church-tithe-wp-text-button" onClick={ this.set_view_to_arrangements.bind( this ) }>{ this.props.main_component.state.unique_settings.strings.arrangements_title }</button>
							</div>
							<div className={ "church-tithe-wp-transactions-btn"  + this.get_current_button_class( 'transactions' ) }>
								<button className="church-tithe-wp-text-button" onClick={ this.set_view_to_transactions.bind( this ) }>{ this.props.main_component.state.unique_settings.strings.transactions_title }</button>
							</div>
						</div>
					</div>
				</div>
			);
		} else {
			return( '' );
		}
	}
}
export default Church_Tithe_WP_Manage_Payments_Nav;
