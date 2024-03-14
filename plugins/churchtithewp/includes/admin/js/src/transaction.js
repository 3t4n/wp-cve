window.Church_Tithe_WP_Admin_Single_Transaction = class Church_Tithe_WP_Admin_Single_Transaction extends React.Component {

	constructor( props ) {
		super(props);

		this.state = {
			refunding_status: 'initial',
		};

	};

	refund_transaction() {

		// Do the double check for "are you sure you want to refund"
		if ( 'initial' == this.state.refunding_status ) {

			this.setState( {
				refunding_status: 'are_you_sure'
			} );

			return false;
		}

		this.setState( {
			refunding_status: 'refunding'
		} );

		// Format the data that we'll send to the server
		var postData = new FormData();
		postData.append('church_tithe_wp_transaction_being_refunded', this.props.current_single_item.id.value);
		postData.append('church_tithe_wp_nonce_refund_transaction', this.props.view_info.nonce_refund_transaction);

		var this_component = this;

		fetch( this.props.view_info.server_api_endpoint_url_refund_transaction, {
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

					this_component.setState( {
						refunding_status: 'failed'
					} );

					return;
				}

				// Examine the text in the response
				response.json().then(
					function( data ) {
						if ( data.success ) {

							if ( data.pending ) {
								this_component.setState( {
									refunding_status: 'pending'
								} );
							} else {
								this_component.setState( {
									refunding_status: 'refunded_succeeded'
								}, () => {

									// Wait for 1 second while the "succeess is shown"
									setTimeout( () => {

										this_component.setState( {
											refunding_status: 'refunded'
										} );

										// Get the updated transaction now that it's been refunded (depending on whether Stripe's webhook has arrived yet)
										this_component.props.get_single_item_from_server( this_component.props.current_single_item.id.value );
									}, 1000 );

								} );
							}

						} else {
							console.log( data );

							this_component.setState( {
								refunding_status: 'failed'
							} );

						}
					}
				);
			}
		).catch(
			function( err ) {
				console.log('Fetch Error :-S', err);

				this_component.setState( {
					refunding_status: 'failed'
				} );

			}
		);

	}

	render_refund_button() {

		// If this transaction was already refunded, or is a refund transaction itself, don't show a refund button
		if (
			'refunded' === this.props.current_single_item.status.raw_value.toLowerCase() ||
			'refund' === this.props.current_single_item.type.value.toLowerCase() ||
			// If the webhook failed, we can't refund it anyway, because Stripe never told us the charge ID.
			this.props.current_single_item.webhook_notice
		) {
			return ( '' );
		}

		var refund_string = this.props.view_info.strings.refund_transaction;
		var on_click = this.refund_transaction.bind( this );

		if ( 'are_you_sure' == this.state.refunding_status ) {
			refund_string = this.props.view_info.strings.refund_transaction_are_you_sure;
		}

		if ( 'refunding' == this.state.refunding_status ) {
			refund_string = this.props.view_info.strings.refund_transaction_refunding;
			on_click = null;
		}

		if ( 'pending' == this.state.refunding_status ) {
			refund_string = this.props.view_info.strings.refund_transaction_pending;
		}

		if ( 'failed' == this.state.refunding_status ) {
			refund_string = this.props.view_info.strings.refund_transaction_failed;
		}

		if ( 'refunded_succeeded' == this.state.refunding_status ) {
			refund_string = this.props.view_info.strings.refund_transaction_succeeded;
			on_click = null;
		}

		return( <button className="button" onClick={ this.refund_transaction.bind( this ) }>{ refund_string }</button> );

	}

	handle_mpwpadmin_button_click( new_state, lightbox_state, event ) {
		this.props.main_component.set_all_current_visual_states( new_state, lightbox_state );
	}

	render(){

		var single_item = this.props.current_single_item;

		return (
			<React.Fragment>
			<div className={ 'mpwpwadmin-list-view-single-data-item-controls' }>
			{ this.render_refund_button() }
			</div>
			<div className="mpwpadmin-single-data">
			{ (() => {

				var mapper = [];
				var value;

				// Loop through all of the items to show about this item
				for (var key in single_item) {
					mapper.push( <div className={ 'mpwpadmin-list-view-single-data-item' } key={ key }>{

						// Render the output for this data value about the current single item (which was selected from the list)
						(() => {

							// If we should show this peice of data in the list view
							if ( single_item[key]['show_in_single_data_view'] ) {
								return (
									<React.Fragment>
									<div className={ 'mpwpadmin-list-view-single-data-item-title' }>
									{ single_item[key]['title'] }
									</div>
									<div className={ 'mpwpadmin-list-view-single-data-item-value' }>
									{ ( () => {

										// Set the visual value
										if ( single_item[key]['value_format_function'] ) {
											value = eval( single_item[key]['value_format_function'] )( single_item[key] )
										} else {
											value = single_item[key]['value']
										}

										// If an mpwpadmin state should be fired when the is clicked
										if ( single_item[key]['mpwpadmin_visual_state_onclick'] || single_item[key]['mpwpadmin_lightbox_state_onclick'] ) {

											var visual_state = single_item[key]['mpwpadmin_visual_state_onclick'] ? single_item[key]['mpwpadmin_visual_state_onclick'] : false;
											var lightbox_state = single_item[key]['mpwpadmin_lightbox_state_onclick'] ? single_item[key]['mpwpadmin_lightbox_state_onclick'] : false;

											return(
												<React.Fragment>
												<a onClick={ this.handle_mpwpadmin_button_click.bind( this, visual_state, lightbox_state ) }>{ value }</a>
												</React.Fragment>
											)
										}

										// If a link should be directed to upon click on this item
										else if ( single_item[key]['link_url'] ) {

											var target = single_item[key]['link_target'] ? single_item[key]['link_url'] : null;

											return(
												<React.Fragment>
												<a target={ target } href={ single_item[key]['link_url'] }>{ value }</a>
												</React.Fragment>
											)
										}

										else {
											return value
										}

									})() }
									</div>
									</React.Fragment>
								);
							}

							return ( '' );

						})()

					}</div> );
				}

				return mapper;
			})() }
			</div>
			</React.Fragment>
		)
	}

};
