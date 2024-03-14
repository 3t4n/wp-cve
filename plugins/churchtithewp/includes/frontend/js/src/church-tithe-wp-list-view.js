var church_tithe_wp_vars = church_tithe_wp_js_vars.tithe_form_vars;

class Church_Tithe_WP_List_View extends React.Component {

	constructor( props ){
		super(props);

		this.state = {
			current_page: 1,
			items_per_page: 10,
			search_term: '',
		};

		this.render_rows = this.render_rows.bind( this );

	}

	get_total_pages() {
		if ( ! this.props.total_items ) {
			return 0;
		} else {
			return Math.ceil( this.props.total_items / this.state.items_per_page );
		}
	}

	handle_page_number_change( event ) {

		this.setState( {
			current_page: event.target.value,
			rows: null,
			columns: null,
		}, function() {
			this.props.get_rows_and_columns( this.state.current_page, this.state.items_per_page, this.state.search_term )
		} );

	}

	handle_items_per_page_change( event ) {

		this.setState( {
			current_page: 1,
			items_per_page: event.target.value,
		}, function() {
			this.props.get_rows_and_columns( this.state.current_page, this.state.items_per_page, this.state.search_term )
		} );

	}

	handle_search_change( event ) {

		this.setState( {
			search_term: event.target.value,
		}, () => {

			// Set up a delay which waits to save the tithe until .5 seconds after they stop typing.
			if( this.input_delay ) {
					// Clear the keypress delay if the user just typed
					clearTimeout( this.input_delay );
					this.input_delay = null;
			}

			var this_component = this;

			// (Re)-Set up the save to fire in 500ms
			this.input_delay = setTimeout( () => {
					clearTimeout( this.input_delay );

					this.setState( {
						current_page: 1,
						rows: null,
						columns: null,
					}, function() {
						this.props.get_rows_and_columns( this.state.current_page, this.state.items_per_page, this.state.search_term )
					} );

			}, 50);

		} );

	}

	render_columns( columns ) {

		if ( ! columns ) {
			return false;
		}

		var mapper = [];

		// Loop through each column
		for (var key in columns) {
			mapper.push(
				<div key={ key } scope="col" className="church-tithe-wp-list-view-column">
					<span>{ columns[key] }</span>
				</div>
			);
		}

		return mapper;
	}

	render_rows( rows, columns ) {

		var mapper = [];
		var td_mapper = [];
		var value;

		if ( ! rows || 0 == this.props.total_items ) {

			var first = true;

			for (var key in columns) {
				td_mapper.push(

					// Render the contents of this table cell
					(() => {

						// If this is the first item in the row.
						if ( first ) {

							first = false;

							return (
								<div key={ key } className="church-tithe-wp-list-view-cell">
									{ 'None Found' }
								</div>
							);
						} else {

							first = false;

							return (
								<div key={ key } className="church-tithe-wp-list-view-cell">
									{ '' }
								</div>
							);
						}

					})()

				);
			}

			// Add all of that combined data to the main mapper array
			mapper.push(
				<div key={ 'none_found' } className="church-tithe-wp-list-view-row">
				{ td_mapper }
				</div>
			);

			return mapper;
		}

		// Loop through each row
		for (var row in rows) {

			// Combine all of the table data (cells in this row)
			for (var key in columns) {
			//for (var key in rows[row]) {
				td_mapper.push(

					// Render the contents of this table cell
					(() => {

						// If we should show this peice of data in the list view
						if ( rows[row][key]['show_in_list_view'] ) {

							// Format the value according to it's format function (if one was included)
							if ( rows[row][key]['value_format_function'] ) {
								value = eval( rows[row][key]['value_format_function'] )( rows[row][key] )
							} else {
								value = rows[row][key]['value']
							}

							return (
								<div key={ key } className="church-tithe-wp-list-view-cell">
									<button className="church-tithe-wp-text-button" onClick={ this.props.on_row_click.bind( this, rows[row] ) }>{ value }</button>
								</div>
							);
						}

					})()

				);
			}

			// Add all of that combined data to the main mapper array
			mapper.push(
				<div key={ row } className="church-tithe-wp-list-view-row">
				{ td_mapper }
				</div>
			);

			// Get the row data array ready for a new row
			td_mapper = [];
		}

		return mapper;

	}

	render_search() {

		return (
			<div className="church-tithe-wp-list-view-search">
				<span>{ this.props.main_component.state.unique_settings.strings.uppercase_search }</span>
				<input type="text" value={ this.state.search_term } onChange={ this.handle_search_change.bind( this ) } />
			</div>
		)

	}

	render_pagination() {

		return (
			<div className="church-tithe-wp-list-view-pagination-controls">
				<div className="church-tithe-wp-list-view-items-per-page">
					<span>{ this.props.main_component.state.unique_settings.strings.uppercase_items  + ' ' + this.props.main_component.state.unique_settings.strings.lowercase_per + ' ' + this.props.main_component.state.unique_settings.strings.lowercase_page }</span>
					<input type="number" min={ 1 } onChange={ this.handle_items_per_page_change.bind( this ) } value={ this.state.items_per_page } />
				</div>
				<div className="church-tithe-wp-list-view-pagination">
					<span>{ this.props.main_component.state.unique_settings.strings.uppercase_page }</span>
					<input type="number" min={ 1 } max={ this.get_total_pages() } onChange={ this.handle_page_number_change.bind( this ) } value={ this.state.current_page } />
					<span>{ ' ' + this.props.main_component.state.unique_settings.strings.lowercase_of + ' ' + this.get_total_pages() }</span>
				</div>
			</div>
		)

	}

	render_list_table() {

		return (
			<React.Fragment>
				<div className="church-tithe-wp-list-view-table">
					<div className="church-tithe-wp-list-view-table-header">
						<div className="church-tithe-wp-list-view-header-row">
							{ this.render_columns( this.props.columns ) }
						</div>
					</div>

					<div className="church-tithe-wp-list-view-body">
						{ this.render_rows( this.props.rows, this.props.columns ) }
					</div>
				</div>
				{
					( () => {
						if ( 'fetching_data' === this.props.current_visual_state ) {
							// return ( <Church_Tithe_WP_Spinner /> );
						}
					})()
				}
			</React.Fragment>
		);

	}

	render_list_view() {

		return(
			<div className="church-tithe-wp-list-view">
				<div className="church-tithe-wp-list-view-before-controls">
					{ this.render_search() }
				</div>
				{ this.render_list_table() }
				<div className="church-tithe-wp-list-view-after-controls">
					{ this.render_pagination() }
				</div>
			</div>
		)

	}

	render() {

		return (
			<div className={ 'church-tithe-wp-list-view' }>
			<div className="church-tithe-wp-list-view-content-area">

			{ this.render_list_view() }

			</div>
			</div>
		)
	}
}
export default Church_Tithe_WP_List_View;
