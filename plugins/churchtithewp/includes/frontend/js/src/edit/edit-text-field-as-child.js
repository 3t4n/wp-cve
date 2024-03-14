window.ChurchTitheWPContentEditableAsChild = class ChurchTitheWPContentEditableAsChild extends React.Component{

	constructor( props ){
		super(props);

		this.state = {
			input_value: null,
		};

		this.textInput = React.createRef();
	}

	componentDidMount() {
		this.setState({
			input_value: this.props.html_tag_contents
		});
	}

	handle_change( event ) {

		this.setState( {
			input_value: event.target.value
		} );

		church_tithe_wp_pass_value_to_block( this.props.main_component, this.props.editing_key, event.target.value, true );
	}

	render() {

		var HtmlTag = this.props.html_tag;

		// If we are in editing mode...
		if ( this.props.main_component.state.editing_mode ) {

			if ( this.props.is_focused ) {
				return (
					<div className={ this.props.editing_textarea_container_classname }>
							<textarea
								type="text"
								onChange={ this.handle_change.bind( this ) }
								value={ this.state.input_value }
							/>
					</div>
				)
			} else {

				if ( ! this.props.html_tag_attributes.dangerouslySetInnerHTML ) {
					return(
						<HtmlTag
							{ ...this.props.html_tag_attributes }
							onChange={ this.handle_change.bind( this ) }
						>
							{ (() => {
								if ( this.props.html_tag_contents ) {
									return this.props.html_tag_contents;
								} else {
									return this.props.instructional_html_tag_contents;
								}
							})() }
						</HtmlTag>
					);
				} else {
					return(
						<HtmlTag
							{ ...this.props.html_tag_attributes }
							onChange={ this.handle_change.bind( this ) }
						/>
					);
				}
			}
			// If we are not in editing mode...
		} else {

			// DangerouslySetInnerHtml can't have any HTML tag contents set. So only add them if that is not set.
			if ( ! this.props.html_tag_attributes.dangerouslySetInnerHTML ) {
				return (
					<HtmlTag { ...this.props.html_tag_attributes }>
						{
							this.props.html_tag_contents
						}
					</HtmlTag>
				)
			} else {
				return (
					<HtmlTag { ...this.props.html_tag_attributes } />
				)
			}
		}
	}

}
export default ChurchTitheWPContentEditableAsChild;
