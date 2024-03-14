window.Church_Tithe_WP_Modal = class Church_Tithe_WP_Modal extends React.Component{

	constructor( props ){
		super(props);

		this.state = {
			has_mounted: false,
			modal_open: false
		}

		this.handle_key_press = this.handle_key_press.bind( this );
		this.toggle_modal = this.toggle_modal.bind( this );
	}

	componentDidMount() {
		this.handle_open_status_based_on_url();
	}

	componentDidUpdate() {

		if ( ! this.state.modal_open ) {
			// Remove a listener for the ESC key when the modal is closed
			document.removeEventListener("keydown", this.handle_key_press, false);
		} else {

			if ( ! this.state.has_mounted ) {
				this.setState( {
					has_mounted: true
				} );
			}

			// Add a listener for the ESC key when the modal is open
			document.addEventListener("keydown", this.handle_key_press, false);
		}

		this.handle_open_status_based_on_url();
	}

	handle_open_status_based_on_url() {

		// If a modal is open based on the setting in the main component
		if ( this.props.main_component && this.props.main_component.state.modal_visual_state ) {
			// Check if that modal is us!
			if ( this.props.slug == Object.keys(this.props.main_component.state.modal_visual_state)[0] ) {
				// Open this modal if it isn't already open
				if ( ! this.state.modal_open ) {
					this.setState( {
						modal_open:  true
					} );
				}
			}
			// If the current modal in the URL is not us, close this one.
			else {
				if ( this.state.modal_open ) {
					this.setState( {
						modal_open:  false
					} );
				}
			}
		}
	}

	get_modal_visible_class() {
		if ( this.state.modal_open ) {
			return ' church-tithe-wp-modal-open';
		} else {
			return ' church-tithe-wp-modal-closed';
		}
	}

	handle_key_press( event ) {

		if( event.keyCode === 27 ) {
			this.toggle_modal( this.state );
		}

	}

	toggle_modal( state ) {

		// If the modal is open, close it
		if ( state.modal_open ) {
			this.props.main_component.set_all_current_visual_states( false, {} );
		} else {
			this.props.main_component.set_all_current_visual_states( false, {
				[this.props.slug]: {}
			} );
		}

	}

	render(){
		return (
			<div className={ 'church-tithe-wp-modal-background church-tithe-wp-modal' + this.get_modal_visible_class() }>
				<div className={ 'church-tithe-wp-modal-outside-click-to-close' } onClick={ this.toggle_modal.bind( null, this.state ) } />
				<div className={ 'church-tithe-wp-modal-relative' }>
					<div className={ 'church-tithe-wp-modal-absolute' }>
						<div className={ 'church-tithe-wp-modal-inner' }>
							{ this.props.modal_contents }
						</div>
					</div>
				</div>
			</div>
		);
	}
}
export default Church_Tithe_WP_Modal;
