window.ChurchTitheWPEditFileButton = class ChurchTitheWPEditFileButton extends React.Component{

	constructor( props ){
		super(props);

		this.state = {
		};

	}

	wp_open_media_dialog() {

		// create and open new file frame
		var mp_core_file_frame = wp.media({
			//Title of media manager frame
			title: church_tithe_wp_editing_strings.select_an_item_for_upload,
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

				//if this is an image, display the thumbnail above the upload button
				var ext = attachment.url.split('.').pop();

				this_component.handleChange( attachment.url );

			});

		});

		// open file frame
		mp_core_file_frame.open();
	}

	handleChange( new_value ) {
		church_tithe_wp_pass_value_to_block( this.props.main_component, this.props.editing_key, new_value, false );
	}

	maybe_render_edit_button() {
		if ( this.props.main_component.state.editing_mode ) {
			return(
				<React.Fragment>
					<div className="church-tithe-wp-edit-button-container">
						<button
							className="button church-tithe-wp-edit-button"
							onClick={ this.wp_open_media_dialog.bind( this ) }
						>
						{
							this.props.editing_string
						}
						</button>
					</div>
				</React.Fragment>
			)
		} else {
			return '';
		}
	}

	render() {

		return this.maybe_render_edit_button();

	}

}
export default ChurchTitheWPEditFileButton;
