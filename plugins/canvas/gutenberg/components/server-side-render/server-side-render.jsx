/*
 * This is a copy of Gutenberg component https://github.com/WordPress/gutenberg/blob/master/packages/server-side-render/src/server-side-render.js
 * With the changes in our component:
 *  - callbacks before and after new content render
 *  - slightly different rendering process - don't remove previously rendered content. So, we will not see page jumps after every attribute change.
 */

/**
 * External dependencies
 */
import classnames from 'classnames';
const { isEqual, debounce } = window.lodash;

/**
 * Internal dependencies
 */
import './style.scss';

/**
 * WordPress dependencies
 */
const {
	Component,
	RawHTML,
} = wp.element;

const { __, sprintf } = wp.i18n;

const apiFetch = wp.apiFetch;

const { addQueryArgs } = wp.url;

const {
	Placeholder,
	Spinner,
} = wp.components;

const {
	doAction,
} = wp.hooks;

export function rendererPath( block, attributes = null, urlQueryArgs = {} ) {
	return addQueryArgs( `/wp/v2/canvas-renderer/${ block }`, {
		context: 'edit',
		...( null !== attributes ? { attributes } : {} ),
		...urlQueryArgs,
	} );
}

export class ServerSideRender extends Component {
	constructor( props ) {
		super( props );
		this.state = {
			response: null,
			prevResponse: null,
		};
	}

	componentDidMount() {
		this.isStillMounted = true;
		this.fetch( this.props );
		// Only debounce once the initial fetch occurs to ensure that the first
		// renders show data as soon as possible.
		this.fetch = debounce( this.fetch, 500 );
	}

	componentWillUnmount() {
		this.isStillMounted = false;
	}

	componentDidUpdate( prevProps ) {
		const prevAttributes = prevProps.attributes;
		const curAttributes = this.props.attributes;

		if ( ! isEqual( prevAttributes, curAttributes ) ) {
			this.fetch( this.props );
		}
	}

	fetch( props ) {
		if ( ! this.isStillMounted ) {
			return;
		}

		const {
			block,
			attributes = null,
			urlQueryArgs = {},
			onBeforeChange = () => {},
			onChange = () => {},
		} = props;

		if ( null !== this.state.response ) {
			this.setState( {
				response: null,
				prevResponse: this.state.response,
			} );
		}

		const path = rendererPath( block );

		// Store the latest fetch request so that when we process it, we can
		// check if it is the current request, to avoid race conditions on slow networks.
		const fetchRequest = this.currentFetchRequest = apiFetch( { method: 'POST', path, data: { attributes: attributes } } )
			.then( ( response ) => {
				if ( this.isStillMounted && fetchRequest === this.currentFetchRequest && response ) {
					onBeforeChange();
					doAction( 'canvas.components.serverSideRender.onBeforeChange', this.props );

					this.setState( {
						response: response.rendered,
						prevResponse: null,
					}, () => {
						onChange();
						doAction( 'canvas.components.serverSideRender.onChange', this.props );
					} );
				}
			} )
			.catch( ( error ) => {
				if ( this.isStillMounted && fetchRequest === this.currentFetchRequest ) {
					onBeforeChange();
					doAction( 'canvas.components.serverSideRender.onBeforeChange', this.props );

					this.setState( {
						response: {
							error: true,
							errorMsg: error.message,
						},
						prevResponse: null,
					}, () => {
						onChange();
						doAction( 'canvas.components.serverSideRender.onChange', this.props );
					} );
				}
			} );
		return fetchRequest;
	}

	render() {
		const {
			response,
			prevResponse,
		} = this.state;

		let { className } = this.props;

		className = classnames(
			className,
			'cnvs-component-server-side-render'
		);

		if ( response === '' ) {
			return (
				<Placeholder
					className={ className }
				>
					{ __( 'Block rendered as empty.' ) }
				</Placeholder>
			);
		} else if ( ! response && prevResponse ) {
			className = classnames(
				className,
				'cnvs-component-server-side-render-loading'
			);

			return (
				<div className={ className }>
					<Spinner />
					<RawHTML
						key="html"
						className="cnvs-component-server-side-render-content"
					>
						{ prevResponse }
					</RawHTML>
				</div>
			);
		} else if ( ! response ) {
			return (
				<Placeholder
					className={ className }
				>
					<Spinner />
				</Placeholder>
			);
		} else if ( response.error ) {
			// translators: %s: error message describing the problem
			const errorMessage = sprintf( __( 'Error loading block: %s' ), response.errorMsg );
			return (
				<Placeholder
					className={ className }
				>
					{ errorMessage }
				</Placeholder>
			);
		}

		return (
			<div className={ className }>
				<RawHTML
					key="html"
					className="cnvs-component-server-side-render-content"
				>
					{ response }
				</RawHTML>
			</div>
		);
	}
}

export default ServerSideRender;
