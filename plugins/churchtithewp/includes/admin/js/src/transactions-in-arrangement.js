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

window.ChurchTitheWPTransactionsInArrangement = class ChurchTitheWPTransactionsInArrangement extends React.Component {

	constructor( props ){
		super(props);

		this.state = {
			rows: null,
			columns: null,
			current_page: 1,
			total_items: 1,
			items_per_page: 1,
			search_term: '',
			current_single_item: null,
			arrangement_id: null
		}

		this.get_single_item_from_server = this.get_single_item_from_server.bind( this );
	}

	componentDidMount() {

		this.setState( {
			arrangement_id: this.props.arrangement_id,
			total_items: this.props.view_info.total_items,
			items_per_page: this.props.view_info.items_per_page,
			rows: this.props.view_info.rows,
			columns: this.props.view_info.columns,
		}, () => {
			this.get_rows_from_server();
		} );

	}

	get_rows_from_server() {

		if ( ! this.state.items_per_page ) {
			var items_per_page = 20;
		} else {
			var items_per_page = this.state.items_per_page;
		}

		// Format the data that we'll send to the server
		var postData = new FormData();
		postData.append('mpwpadmin_list_view_id', this.props.view_slug);
		postData.append('mpwpadmin_list_view_search_term', this.state.search_term);
		postData.append('mpwpadmin_list_view_page_id', this.state.current_page);
		postData.append('mpwpadmin_items_per_page', items_per_page);
		postData.append('mpwpadmin_list_view_items_per_page', this.state.items_per_page);
		postData.append('church_tithe_wp_arrangement_id', this.state.arrangement_id);
		postData.append('mpwpadmin_nonce', this.props.view_info.nonce);

		var this_component = this;

		fetch( this.props.view_info.server_api_endpoint_url, {
			method: "POST",
			mode: "same-origin",
			credentials: "same-origin",
			headers: {},
			body: postData
		} ).then(
			function( response ) {
				if ( response.status !== 200 ) {
					console.log('Looks like there was a problem. Status Code: ' +
					response.status);
					return;
				}

				// Examine the text in the response
				response.json().then(
					function( data ) {
						if ( data.success ) {

							this_component.setState( {
								columns: data.columns,
								rows: data.rows,
								total_items: data.total_items
							} );

						} else {
							console.log( data );

							this_component.setState( {
								rows: 'Unable to fetch rows...'
							} );
						}
					}
				);
			}
		).catch(
			function( err ) {
				console.log('Fetch Error :-S', err);
			}
		);

	}

	handle_page_number_change( event ) {

		this.setState( {
			current_page: event.target.value,
			rows: null,
			columns: null,
		}, function() {
			this.get_rows_from_server();
		} );

	}

	get_single_item_from_server( item_to_fetch ) {

		// Format the data that we'll send to the server
		var postData = new FormData();
		postData.append('mpwpadmin_list_view_id', this.props.view_slug);
		postData.append('mpwpadmin_list_view_item_id', item_to_fetch);
		postData.append('mpwpadmin_nonce', this.props.view_info.nonce);

		var this_component = this;

		fetch( this.props.view_info.server_api_endpoint_url_single_item, {
			method: "POST",
			mode: "same-origin",
			credentials: "same-origin",
			headers: {},
			body: postData
		} ).then(
			function( response ) {
				if ( response.status !== 200 ) {
					console.log('Looks like there was a problem. Status Code: ' +
					response.status);
					return;
				}

				// Examine the text in the response
				response.json().then(
					function( data ) {
						if ( data.success && data.current_single_item ) {

							this_component.setState( {
								current_single_item: data.current_single_item,
							} );

						} else {

							this_component.setState( {
								current_single_item: 'none_found'
							} );
						}
					}
				);
			}
		).catch(
			function( err ) {
				console.log('Fetch Error :-S', err);
			}
		);

	}

	handle_items_per_page_change( event ) {

		this.setState( {
			current_page: 1,
			items_per_page: event.target.value,
		}, function() {
			this.get_rows_from_server();
		} );

	}

	handle_search_change( event ) {

		this.setState( {
			search_term: event.target.value,
			current_page: 1,
			rows: null,
			columns: null,
		}, function() {
			this.get_rows_from_server();
		} );

	}

	handle_single_row_click( single_item, event ) {

		this.props.main_component.set_all_current_visual_states( {
			[this.props.view_slug]: {
				single_data_view: {
					[single_item['id']['value']]: {} // Use the value in the first column as the URL slug
				}
			}
		} ).then( () => {
			this.setState( {
				current_single_item: single_item
			} );
		} );

	}

	handle_list_view_button_click(){

		this.props.main_component.set_all_current_visual_states( {
			[this.props.view_slug]: {
				list_view: {}
			}
		} );

	}

	get_total_pages() {
		return Math.ceil( this.state.total_items / this.state.items_per_page );
	}

	render_columns( columns ) {

		if ( ! columns ) {
			return false;
		}

		var mapper = [];

		// Loop through each column
		for (var key in columns) {
			mapper.push(
				<div key={ key } scope="col" className="mpwpadmin-list-view-column">
					<span>{ columns[key] }</span>
				</div>
			);
		}

		return mapper;
	}

	render_rows( rows, columns ) {

		var mapper = [];
		var td_mapper = [];

		if ( ! rows || 0 == this.state.total_items ) {

			var first = true;

			for (var key in columns) {
				td_mapper.push(

					// Render the contents of this table cell
					(() => {

						// If we should show this peice of data in the list view
						if ( first ) {

							first = false;

							return (
								<div key={ key } className="mpwpadmin-list-view-cell">
									{ 'None Found' }
								</div>
							);
						} else {

							first = false;

							return (
								<div key={ key } className="mpwpadmin-list-view-cell">
									{ '' }
								</div>
							);
						}

					})()

				);
			}

			// Add all of that combined data to the main mapper array
			mapper.push(
				<div key={ 'none_found' } className="mpwpadmin-list-view-row">
				{ td_mapper }
				</div>
			);

			return mapper;
		}

		// Loop through each row
		for (var row in rows) {

			// Combine all of the table data (cells in this row)
			for (var key in columns) {
				td_mapper.push(

					// Render the contents of this table cell
					(() => {

						// If we should show this peice of data in the list view
						if ( rows[row][key]['show_in_list_view'] ) {
							return (
								<div key={ key } className="mpwpadmin-list-view-cell">
									<button onClick={ this.handle_single_row_click.bind( this, rows[row] ) }>
									{(() => {
										if ( rows[row][key]['value_format_function'] ) {
											return eval( rows[row][key]['value_format_function'] )( rows[row][key] )
										} else {
											return rows[row][key]['value']
										}
									})()}
								</button>
								</div>
							);
						}

					})()

				);
			}

			// Add all of that combined data to the main mapper array
			mapper.push(
				<div key={ row } className="mpwpadmin-list-view-table-row">
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
			<div className="mpwpwadmin-list-view-search">
			<span>{ this.props.view_info.strings.uppercase_search }</span>
			<input type="text" value={ this.state.search_term } onChange={ this.handle_search_change.bind( this ) } />
			</div>
		)

	}

	render_pagination() {

		return (
			<div className="mpwpwadmin-list-view-pagination-controls">
				<div className="mpwpwadmin-list-view-items-per-page">
					<span>{ this.props.view_info.strings.uppercase_items  + ' ' + this.props.view_info.strings.lowercase_per + ' ' + this.props.view_info.strings.lowercase_page }</span>
					<input type="number" min={ 1 } onChange={ this.handle_items_per_page_change.bind( this ) } value={ this.state.items_per_page } />
				</div>
				<div className="mpwpwadmin-list-view-pagination">
					<span>{ this.props.view_info.strings.uppercase_page }</span>
					<input type="number" min={ 1 } max={ this.get_total_pages() } onChange={ this.handle_page_number_change.bind( this ) } value={ this.state.current_page } />
					<span>{ ' ' + this.props.view_info.strings.lowercase_of + ' ' + this.get_total_pages() }</span>
				</div>
			</div>
		)

	}

	render_list_table() {

		if ( this.state.columns && this.state.rows ) {
			return (
				<div className={ 'mpwpadmin-list-view-table' }>
					<div className={ 'mpwpadmin-list-view-table-header' }>
						<div className={ 'mpwpadmin-list-view-table-row' }>
							{ this.render_columns( this.state.columns ) }
						</div>
					</div>

					<div className={ 'mpwpadmin-list-view-table-body' }>
						{ this.render_rows( this.state.rows, this.state.columns ) }
					</div>
				</div>
			)
		} else {
			return <MP_WP_Admin_Spinner />
		}

	}

	render_list_view() {

		return(
			<div className="mpwpadmin-list-view">
				<div className="mpwpadmin-list-view-controls">

					{ this.render_search() }

					{ this.render_pagination() }

				</div>

				{ this.render_list_table() }

				<div className="mpwpadmin-list-view-controls">

					{ this.render_search() }

					{ this.render_pagination() }

				</div>

			</div>
		)

	}

	render() {

		return (
			<div className={ 'mpwpadmin-list-view church-tithe-wp-transactions-in-arrangement' }>

			<div className="mpwpadmin-breadcrumb">
			<h2>Transactions in this Plan</h2>
			</div>

			<div className="mpwpadmin-list-view-content-area">

			{ this.render_list_view() }

			</div>
			</div>
		)
	}
}
