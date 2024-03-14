/**
 * Internal dependencies
 */
import './style.scss';

/**
 * WordPress dependencies
 */
const {
	jQuery: $,
	canvasLayouts,
} = window;

const {
	__,
} = wp.i18n;

const {
	registerBlockType,
	createBlock,
	parse,
} = wp.blocks;

const {
	Component,
} = wp.element;

const {
	Modal,
	SelectControl,
} = wp.components;

const {
	compose,
} = wp.compose;

const {
	withDispatch,
} = wp.data;

/**
 * Add layouts button to Gutenberg toolbar
 */
$(document).on('DOMContentLoaded', () => {

	 wp.data.subscribe(function () {
		setTimeout(function () {
			if (!document.getElementById('canvas-toolbar-import-layout')) {
				const $toolbar = $('.edit-post-header-toolbar');

				if (Object.keys(canvasLayouts.layouts).length && $toolbar.length) {
					$toolbar.append(`
						<div>
							<div id="canvas-toolbar-import-layout" class="canvas-toolbar-import-layout">
								<button class="components-button components-icon-button" aria-label="${__('Import Layout', 'canvas')}">
									<svg aria-hidden="true" role="img" focusable="false" class="dashicon dashicons-insert" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20"><path d="M10 1c-5 0-9 4-9 9s4 9 9 9 9-4 9-9-4-9-9-9zm0 16c-3.9 0-7-3.1-7-7s3.1-7 7-7 7 3.1 7 7-3.1 7-7 7zm1-11H9v3H6v2h3v3h2v-3h3V9h-3V6z"></path></svg>
									${__('Import Layout', 'canvas')}
								</button>
							</div>
						</div>
					` );
				}
			}
		}, 1)
	});

	// Insert `canvas/layout` block.
	$(document).on('click', '.canvas-toolbar-import-layout button', (e) => {
		e.preventDefault();

		const {
			insertBlocks,
		} = wp.data.dispatch('core/block-editor');

		insertBlocks(createBlock('canvas/layouts'));
	});
});

/**
 * Component
 */
class LayoutsBlockEdit extends Component {
	constructor() {
		super(...arguments);

		this.state = {
			category: '',
		};

		this.getLayouts = this.getLayouts.bind(this);
	}

	getLayouts() {
		const {
			category,
		} = this.state;

		const result = {};

		Object.keys(canvasLayouts.layouts).forEach((k) => {
			const layout = canvasLayouts.layouts[k];

			if (category) {
				if (layout.category && layout.category.indexOf(category) > -1) {
					result[k] = layout;
				}
			} else {
				result[k] = layout;
			}
		})

		return result;
	}

	render() {
		const {
			insertLayout,
			closeModal,
		} = this.props;

		const categories = [
			{
				label: __('--- Select Category ---', 'canvas'),
				value: '',
			},
			...Object.keys(canvasLayouts.categories).map((name) => {
				return {
					label: canvasLayouts.categories[name],
					value: name,
				};
			}),
		];

		const layouts = this.getLayouts();

		return (
			<Modal
				position="top"
				title={__('Layouts', 'canvas')}
				onRequestClose={() => {
					closeModal();
				}}
				shouldCloseOnClickOutside={false}
				className="cnvs-extension-layouts-modal"
			>
				<div className="cnvs-extension-layouts-content">
					{categories.length > 1 ? (
						<div className="cnvs-extension-layouts-categories">
							<SelectControl
								options={categories}
								onChange={(val) => {
									this.setState({
										category: val,
									});
								}}
							/>
						</div>
					) : ''}
					{Object.keys(layouts).length ? (
						<div className="cnvs-extension-layouts-count">
							{ __('Layouts:', 'canvas')}
							&nbsp;
							<strong>{Object.keys(layouts).length}</strong>
						</div>
					) : ''}
					{Object.keys(layouts).length ? (
						<div className="cnvs-extension-layouts-list">
							{ Object.keys(layouts).map((k) => {
								return (
									<button
										key={k}
										onClick={() => {
											insertLayout(layouts[k].content);

											closeModal();
										}}
									>
										{ layouts[k].thumbnail ? (
											<img src={layouts[k].thumbnail} />
										) : ''}
										<label>{layouts[k].title}</label>
									</button>
								);
							})}
						</div>
					) : (
							<p>{__('No layouts found.', 'canvas')}</p>
						)}
				</div>
			</Modal>
		);
	}
}

const LayoutsBlockEditWithSelect = compose(
	withDispatch((dispatch, ownProps) => {
		const { clientId } = ownProps;
		const {
			replaceBlocks,
			removeBlock,
		} = dispatch('core/block-editor');

		return {
			closeModal() {
				removeBlock(clientId);
			},
			insertLayout(content) {
				const parsedBlocks = parse(content);
				replaceBlocks(clientId, parsedBlocks);
			},
		};
	}),
)(LayoutsBlockEdit);

/**
 * Register block.
 */
registerBlockType('canvas/layouts', {
	title: __('Canvas Layouts', 'canvas'),
	description: __('Add a pre-configured layouts.', 'canvas'),
	category: 'common',
	supports: {
		customClassName: false,
		html: false,
		inserter: false,
		multiple: false,
		reusable: false,
	},
	edit: LayoutsBlockEditWithSelect,
	save: function () {
		return null
	}
});
