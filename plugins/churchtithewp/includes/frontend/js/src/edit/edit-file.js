window.ChurchTitheWPEditFile = class ChurchTitheWPEditFile extends React.Component{

	constructor( props ){
		super(props);

		this.state = {
			current_attachment_data: null,
			input_value: null,
			is_focused: false
		};

		this.textInput = React.createRef();
	}

	componentDidMount() {
		this.setState({
			current_attachment_data: this.props.attachment_data
		});
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

				this_component.setState( {
					current_attachment_data: attachment
				} );

				this_component.handle_change( attachment );

			});

		});

		// open file frame
		mp_core_file_frame.open();
	}

	remove() {

		this.setState( {
			current_attachment_data: null
		} );

		church_tithe_wp_pass_value_to_block( this.props.main_component, this.props.editing_key, null, false );
	}

	handle_change( attachment_data ) {
		church_tithe_wp_pass_value_to_block( this.props.main_component, this.props.editing_key, attachment_data, false );
	}

	set_focus( should_be_focused, context, event ) {
		event.preventDefault();
		this.setState( {
			is_focused: should_be_focused
		});
	}

	handleBlur( event ) {
		this.setState( {
			is_focused: false
		});
	}

	render_edit_and_delete_buttons() {
		return(
			<div className="church-tithe-wp-edit-button-container">
				<button
					className="button church-tithe-wp-edit-button"
					onClick={ this.wp_open_media_dialog.bind( this ) }
				>
				{
					church_tithe_wp_editing_strings.edit
				}
				</button>
				{ (() => {

					if ( this.state.current_attachment_data ) {
						return(
							<button
								className="button church-tithe-wp-edit-button"
								onClick={ this.remove.bind( this ) }
							>
							{
								church_tithe_wp_editing_strings.remove
							}
							</button>
						)
					}

				})()}
			</div>
		);
	}

	render() {

		var HtmlTag = this.props.html_tag;

		// If we are in editing mode...
		if ( this.props.main_component.state.editing_mode ) {
			return (
				<div>
					<div className="church-tithe-wp-edit-container">
						{ this.render_edit_and_delete_buttons() }
						<HtmlTag { ...this.props.html_tag_attributes }>
							{
								this.props.html_tag_contents
							}
						</HtmlTag>
					</div>
				</div>
			)
			// If we are not in editing mode...
		} else {
			if ( this.state.current_attachment_data ) {
				return (
					<HtmlTag { ...this.props.html_tag_attributes }>
						{
							this.props.html_tag_contents
						}
					</HtmlTag>
				)
			} else {
				return '';
			}
		}
	}

}
export default ChurchTitheWPEditFile;
