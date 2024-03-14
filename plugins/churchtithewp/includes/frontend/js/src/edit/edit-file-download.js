window.ChurchTitheWPEditFileDownload = class ChurchTitheWPEditFileDownload extends React.Component{

	constructor( props ){
		super(props);

		this.state = {};

		this.textInput = React.createRef();
	}

	componentDidMount() {
		this.setState( this.props.main_component.state.unique_settings.file_download_attachment_data );
	}

	wp_open_media_dialog() {

		// create and open new file frame
		var mp_core_file_frame = wp.media({
			//Title of media manager frame
			title: church_tithe_wp_editing_strings.choose_file_to_be_delivered,
			button: {
				//Button text
				text: church_tithe_wp_editing_strings.use_uploaded_item
			},
			//Do not allow multithele files, if you want multithele, set true
			multithele: false,
		});

		var this_component = this;

		//callback for selected image
		mp_core_file_frame.on('select', function() {

			var selection = mp_core_file_frame.state().get('selection');

			selection.map(function(attachment) {

				attachment = attachment.toJSON();

				this_component.setState( {
					file_download_mode_enabled: true,
					attachment_id: attachment.id,
					attachment_filename: attachment.filename,
				}, () => {
					this_component.handle_file_change();
				} );

			});

		});

		// open file frame
		mp_core_file_frame.open();
	}

	handle_file_change() {
		church_tithe_wp_pass_value_to_block( this.props.main_component, this.props.editing_key, this.state, false );
	}

	handle_remove_click() {

		this.setState( {
			file_download_mode_enabled: false,
		} );

		church_tithe_wp_pass_value_to_block( this.props.main_component, this.props.editing_key, this.state, false );
	}

	toggle_email_required() {
		if ( this.state.email_required ) {
			this.setState( {
				email_required: false
			}, () => {
				church_tithe_wp_pass_value_to_block( this.props.main_component, this.props.editing_key, this.state, false );
			} );
		} else {
			this.setState( {
				email_required: true
			}, () => {
				church_tithe_wp_pass_value_to_block( this.props.main_component, this.props.editing_key, this.state, false );
			} );
		}
	}

	handle_instructions_title( event ) {

		this.setState( {
			instructions_title: event.target.value
		}, () => {
			church_tithe_wp_pass_value_to_block( this.props.main_component, this.props.editing_key, this.state, true );
		} );
	}

	handle_instructions_description( event ) {

		this.setState( {
			instructions_description: event.target.value
		}, () => {
			church_tithe_wp_pass_value_to_block( this.props.main_component, this.props.editing_key, this.state, true );
		} );
	}

	render_enable_button() {

		if ( ! this.state.file_download_mode_enabled ) {

			return(
					<button
						className="button church-tithe-wp-edit-button"
						onClick={ this.wp_open_media_dialog.bind( this ) }
					>
						{ church_tithe_wp_editing_strings.enable_file_download_mode }
					</button>
			);

		}
	}

	render_disable_button() {

		if ( this.state.file_download_mode_enabled ) {
			return(
				<button
					className="button church-tithe-wp-edit-top-right-close-button"
					onClick={ this.handle_remove_click.bind( this ) }
				>
				{
					church_tithe_wp_editing_strings.disable_file_download_mode
				}
				</button>
			);
		}
	}

	render_area_header() {

		return (
			<div className="church-tithe-wp-edit-container-admin-only-header">
			<span className="church-tithe-wp-edit-container-admin-only-title">File Download Mode</span>
			{ this.render_enable_button() }
			{ this.render_disable_button() }
			</div>
		);
	}

	render_file_select_option() {
		return(
			<div className="church-tithe-wp-edit-container-admin-only-setting">
				<div className="church-tithe-wp-edit-container-admin-only-setting-title">
					{ church_tithe_wp_editing_strings.deliverable_file_title }
				</div>
				<div className="church-tithe-wp-edit-container-admin-only-setting-description">
					{ church_tithe_wp_editing_strings.deliverable_file_description }
				</div>
				<div className="church-tithe-wp-edit-container-admin-only-setting-value">
					<button
						className="button"
						onClick={ this.wp_open_media_dialog.bind( this ) }
					>
					{ (() => {
						if ( this.state.attachment_filename ) {
							return( this.state.attachment_filename + ' (' + church_tithe_wp_editing_strings.edit + ')' );
						}
					})()}
					</button>
				</div>
			</div>
		);
	}

	render_email_required_option() {
		return(
			<div className="church-tithe-wp-edit-container-admin-only-setting">
				<div className="church-tithe-wp-edit-container-admin-only-setting-title">
					{ church_tithe_wp_editing_strings.require_users_email_title }
				</div>
				<div className="church-tithe-wp-edit-container-admin-only-setting-description">
					{ church_tithe_wp_editing_strings.require_users_email_description }
				</div>
				<div
					className="church-tithe-wp-edit-container-admin-only-setting-value"
					onClick={ this.toggle_email_required.bind( this ) }
				>
					<input type="checkbox"
						onChange={ this.toggle_email_required.bind( this ) }
						value={ this.state.email_required ? true : false }
						checked={ this.state.email_required ? true : false }
					/>
					{ (() => {
						if ( this.state.email_required ) {
							return( church_tithe_wp_editing_strings.email_required );
						} else {
							return( church_tithe_wp_editing_strings.email_not_required );
						}
					})()}
				</div>
			</div>
		);
	}

	render_file_instructions_option() {
		return(
			<div className="church-tithe-wp-edit-container-admin-only-setting">
				<div className="church-tithe-wp-edit-container-admin-only-setting-title">
					{ church_tithe_wp_editing_strings.instructions_to_user_title }
				</div>
				<div className="church-tithe-wp-edit-container-admin-only-setting-description">
					{ church_tithe_wp_editing_strings.instructions_to_user_description }
				</div>
				<div
					className="church-tithe-wp-edit-container-admin-only-setting-value"
				>
					<span>{ church_tithe_wp_editing_strings.instructions_title }</span>
					<input type="text"
						onChange={ this.handle_instructions_title.bind( this ) }
						value={ this.state.instructions_title }
					/>

					<span>{ church_tithe_wp_editing_strings.instructions_description }</span>
					<textarea
						onChange={ this.handle_instructions_description.bind( this ) }
						value={ this.state.instructions_description }
					/>
				</div>
			</div>
		);
	}

	render_body() {
		if ( this.state.file_download_mode_enabled ) {
			return (
				<React.Fragment>
					{ this.render_file_select_option() }
					{ this.render_email_required_option() }
					{ this.render_file_instructions_option() }
				</React.Fragment>
			);
		} else {
			return ( church_tithe_wp_editing_strings.file_download_mode_description );
		}


	}

	render() {

		// If we are in editing mode...
		if ( this.props.main_component.state.editing_mode ) {
			return (
				<div>
					<div className="church-tithe-wp-edit-container-admin-only">
						{ this.render_area_header() }
						<div className="church-tithe-wp-edit-container-admin-only-body">
							{ this.render_body() }
						</div>
					</div>
				</div>
			)
			// If we are not in editing mode, show nothing here.
		} else {
			return '';
		}
	}

}
export default ChurchTitheWPEditFileDownload;
