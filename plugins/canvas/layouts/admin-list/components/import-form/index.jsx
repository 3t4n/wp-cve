/**
 * WordPress dependencies
 */
const { Component } = wp.element;
const { withInstanceId } = wp.compose;
const { __ } = wp.i18n;
const { Button, Notice } = wp.components;

/**
 * Internal dependencies
 */
import './style.scss';
import importLayoutBlock from '../../utils/import';

class ImportForm extends Component {
	constructor() {
		super( ...arguments );
		this.state = {
			isLoading: false,
			error: null,
			file: null,
		};

		this.isStillMounted = true;
		this.onChangeFile = this.onChangeFile.bind( this );
		this.onSubmit = this.onSubmit.bind( this );
	}

	componentWillUnmount() {
		this.isStillMounted = false;
	}

	onChangeFile( event ) {
		this.setState( { file: event.target.files[ 0 ] } );
	}

	onSubmit( event ) {
		event.preventDefault();
		const { file } = this.state;
		const { onUpload } = this.props;
		if ( ! file ) {
			return;
		}
		this.setState( { isLoading: true } );
		importLayoutBlock( file )
			.then( ( layoutBlock ) => {
				if ( ! this.isStillMounted ) {
					return;
				}

				this.setState( { isLoading: false } );
				onUpload( layoutBlock );
			} )
			.catch( ( error ) => {
				if ( ! this.isStillMounted ) {
					return;
				}

				let uiMessage;
				switch ( error.message ) {
					case 'Invalid JSON file':
						uiMessage = __( 'Invalid JSON file', 'canvas' );
						break;
					case 'Invalid Layout Block JSON file':
						uiMessage = __( 'Invalid Layout Block JSON file', 'canvas' );
						break;
					default:
						uiMessage = __( 'Unknown error', 'canvas' );
				}

				this.setState( { isLoading: false, error: uiMessage } );
			} );
	}

	render() {
		const { instanceId } = this.props;
		const { file, isLoading, error } = this.state;
		const inputId = 'list-layout-blocks-import-form-' + instanceId;
		return (
			<form
				className="list-layout-blocks-import-form"
				onSubmit={ this.onSubmit }
			>
				{ error && (
					<Notice status="error">
						{ error }
					</Notice>
				) }
				<label
					htmlFor={ inputId }
					className="list-layout-blocks-import-form__label"
				>
					{ __( 'File', 'canvas' ) }
				</label>
				<input
					id={ inputId }
					type="file"
					onChange={ this.onChangeFile }
				/>
				<Button
					type="submit"
					isBusy={ isLoading }
					disabled={ ! file || isLoading }
					isDefault
					className="list-layout-blocks-import-form__button"
				>
					{ __( 'Import', 'canvas' ) }
				</Button>
			</form>
		);
	}
}

export default withInstanceId( ImportForm );
