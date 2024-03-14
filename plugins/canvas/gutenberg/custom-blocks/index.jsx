/**
 * Internal dependencies
 */
import './style.scss';
import FieldsRender from '../components/fields-render';
import FieldsCSSOutput from '../components/fields-css-output';
import ServerSideRender from '../components/server-side-render';
import ImageSelector from '../components/image-selector';
import getParentBlock from '../utils/get-parent-block';

/**
 * WordPress dependencies
 */
const {
	__,
} = wp.i18n;

const {
	registerBlockType,
} = wp.blocks;

const {
	Component,
	Fragment,
	RawHTML,
} = wp.element;

const {
	BaseControl,
	Placeholder,
	PanelBody,
	Disabled,
	Notice,
} = wp.components;

const {
	InspectorControls,
} = wp.blockEditor;

const {
	applyFilters,
} = wp.hooks;

const {
	withSelect,
} = wp.data;

const {
	blocks,
} = window.pk_custom_blocks_localize;

const { select, subscribe } = wp.data;

const allLocations = {
	root: __('Block editor root'),
	'section-wide': __('Fullwidth section'),
	'section-full': __('Fullwidth section with alignment set to "Full"'),
	'section-content': __('The content part of a sidebar section'),
	'section-sidebar': __('The sidebar part of a sidebar section'),
};

// Value, that will not be saved in field attribute.
// Used in columns block.
const CNVS_PREVENT_UPDATE = 'CNVS_PREVENT_UPDATE';

// Template Switcher.
class TemplateSwitcher {

	constructor() {
		this.template = null;
	}

	init() {

		subscribe(() => {

			const newTemplate = select('core/editor').getEditedPostAttribute( 'template' );

			if ( newTemplate !== this.template ) {
				this.template = newTemplate;

				this.changeTemplate();
			}
		});
	}

	changeTemplate() {
		let blocks = wp.data.select('core/block-editor').getBlocks();

		blocks.forEach((el) => {
			wp.data.dispatch('core/block-editor').updateBlock(el.clientId, { attributes: {} });
		});
	}
}

new TemplateSwitcher().init();

/**
 * Get block icons and convert string `<svg...` to react component
 *
 * @param {String} { icon } - icon string
 * @return {String|JSX}
 */
function getBlockIcon({ icon }) {
	if ('string' === typeof icon && '<svg' === icon.trim().substr(0, 4)) {
		icon = <RawHTML>{icon}</RawHTML>;
	}

	return icon || '';
}

/**
 * Get block Edit class.
 *
 * @param {Object} blockData block data
 * @return {Class}
 */
function getBlockEdit(blockData) {
	const {
		name,
		title,
		fields,
		sections,
		layouts,
	} = blockData;

	const icon = getBlockIcon(blockData);

	class CustomBlockEdit extends Component {
		constructor() {
			super(...arguments);

			this.maybeUpdateLocationAttribute = this.maybeUpdateLocationAttribute.bind(this);
			this.maybeSetDefaultLayout = this.maybeSetDefaultLayout.bind(this);
			this.getAllowedLocations = this.getAllowedLocations.bind(this);
			this.getAllowedLocationsNotice = this.getAllowedLocationsNotice.bind(this);
			this.isAllowed = this.isAllowed.bind(this);
			this.getLayoutSelector = this.getLayoutSelector.bind(this);

			this.maybeUpdateLocationAttribute(true);
		}

		componentDidMount() {
			this.maybeSetDefaultLayout();
			this.maybeUpdateLocationAttribute();
		}
		componentDidUpdate() {
			this.maybeUpdateLocationAttribute();
		}

		/**
		 * Maybe update location attribute.
		 */
		maybeUpdateLocationAttribute(force) {
			if (this.props.location !== this.props.attributes.canvasLocation) {
				if (force) {
					this.props.attributes.canvasLocation = this.props.location;
				}
				this.props.setAttributes({
					canvasLocation: this.props.location,
				});
			}
		}

		/**
		 * Maybe set default layout, when available only 1 layout.
		 */
		maybeSetDefaultLayout() {
			const {
				attributes,
				setAttributes,
			} = this.props;

			const {
				layout,
			} = attributes;

			const layoutsCount = layouts ? Object.keys(layouts).length : 0;

			if (1 === layoutsCount) {
				const isLayoutDifferent = Object.keys(layouts)[0] !== layout;

				if (isLayoutDifferent) {
					setAttributes({
						layout: Object.keys(layouts)[0],
					});
				}
			}
		}

		/**
		 * Get array with all allowed locations.
		 *
		 * @return {Array}
		 */
		getAllowedLocations() {
			const {
				attributes,
			} = this.props;

			const {
				layout,
			} = attributes;

			// get locations from the current layout
			// and from the block settings.
			return [
				...(layouts && layouts[layout] && layouts[layout].location ? layouts[layout].location : []),
				...blockData.location,
			];
		}

		/**
		 * Get notice about allowed locations.
		 *
		 * @param {Array} checkLocations array of locations to check. If empty, use locations from the block settings and layout
		 * @return {JSX}
		 */
		getAllowedLocationsNotice(checkLocations) {
			// get locations from the current layout
			// and from the block settings.
			if (!checkLocations) {
				checkLocations = this.getAllowedLocations();
			}

			return (
				<Fragment>
					<p>{__('The block output is allowed in following locations only:')}</p>
					<ul>
						{checkLocations.map((locationName) => {
							return (
								<li key={`location-${locationName}`}>
									{allLocations[locationName] || locationName}
								</li>
							);
						})}
					</ul>
				</Fragment>
			);
		}

		/**
		 * Check if block is allowed in current location.
		 *
		 * @param {Array} checkLocations array of locations to check. If empty, use locations from the block settings and layout
		 * @return {boolean}
		 */
		isAllowed(checkLocations) {
			const {
				location,
			} = this.props;

			let result = true;

			// get locations from the current layout
			// and from the block settings.
			if (!checkLocations) {
				checkLocations = this.getAllowedLocations();
			}

			if (checkLocations && checkLocations.length) {
				result = false;

				checkLocations.forEach((locationName) => {
					if (location === locationName) {
						result = true;
					}
				});
			}

			return result;
		}

		/**
		 * Returns layout selector.
		 *
		 * @return {JSX} ImageSelector.
		 */
		getLayoutSelector() {
			const {
				setAttributes,
			} = this.props;

			const {
				layout,
			} = this.props.attributes;

			const items = Object.keys(layouts).map((layoutName) => {
				const layoutData = layouts[layoutName];
				const isDisabled = !this.isAllowed(layoutData.location);

				return {
					content: <RawHTML>{layoutData.icon}</RawHTML>,
					value: layoutName,
					label: layoutData.name,
					isDisabled,
					disabledNotice: isDisabled ? this.getAllowedLocationsNotice(layoutData.location) : '',
				};
			});

			return (
				<ImageSelector
					value={layout}
					onChange={(val) => {
						setAttributes({
							layout: val,
						});
					}}
					items={items}
				/>
			);
		}

		render() {
			const {
				attributes,
				setAttributes,
			} = this.props;

			const {
				layout,
				canvasClassName,
			} = attributes;

			const isLayoutsAvailable = layouts && Object.keys(layouts).length > 1;

			// layout selector.
			if (isLayoutsAvailable && !layout) {
				return (
					<Placeholder
						className="canvas-component-custom-blocks-placeholder"
						icon={icon}
						label={title}
						instructions={__('Select the block layout.')}
					>
						{this.getLayoutSelector()}
					</Placeholder>
				);
			}

			// Block render if all checks passed.
			const blockRender = applyFilters('canvas.customBlock.editRender', (
				<Disabled>
					<ServerSideRender
						block={name}
						blockProps={this.props}
						attributes={attributes}
					/>
				</Disabled>
			), this.props);

			return (
				<div className="canvas-component-custom-blocks">
					{this.isAllowed() ? (
						blockRender
					) : (
							<Placeholder
								className="canvas-component-custom-blocks-placeholder"
								icon={icon}
								label={title}
							>
								<Notice status="warning" isDismissible={false}>
									{this.getAllowedLocationsNotice()}
								</Notice>
							</Placeholder>
						)}
					{fields ? (
						<Fragment>
							<FieldsCSSOutput
								selector={canvasClassName ? `.${canvasClassName}` : false}
								fields={fields}
								attributes={attributes}
							/>
							<InspectorControls>
								{isLayoutsAvailable ? (
									<PanelBody
										title={__('Layout')}
										initialOpen={ false }
									>
										<BaseControl>
											{this.getLayoutSelector()}
										</BaseControl>
									</PanelBody>
								) : ''}
								<FieldsRender
									fields={fields}
									sections={sections}
									attributes={attributes}
									blockProps={this.props}
									onChange={(key, val) => {
										val = applyFilters('canvas.customBlock.onFieldChange', val, key, this.props);

										if ( CNVS_PREVENT_UPDATE !== val ) {
											setAttributes({ [key]: val });
										}
									}}
								/>
							</InspectorControls>
						</Fragment>
					) : ''}
				</div>
			);
		}
	}

	/*
	 * Prepare location type name of the current block.
	 *
	 * Available types:
	 *   - root               - block inserted in root
	 *   - section-wide       - block inserted in Section block with layout = wide
	 *   - section-full       - block inserted in Section block with layout = full
	 *   - section-content    - block inserted in Section block inside Content column
	 *                        - block inserted in Row block inside column with size [5-11]
	 *   - section-sidebar    - block inserted in Section block inside Sidebar column
	 *                        - block inserted in Row block inside column with size [1-4]
	 */
	return withSelect((select, ownProps) => {
		const {
			getBlockHierarchyRootClientId,
			getBlock,
		} = select('core/block-editor');

		const rootBlock = getBlock(getBlockHierarchyRootClientId(ownProps.clientId));
		let parentBlock = getParentBlock(rootBlock, ownProps);
		let isRoot = parentBlock && parentBlock.clientId === ownProps.clientId;

		// Skip block `core/group` from this check as we can use it for Query Settings.
		if ( 'core/group' === parentBlock.name && ! isRoot ) {
			const postGroupId = parentBlock.clientId;
			parentBlock = getParentBlock(rootBlock, parentBlock);
			isRoot = parentBlock && parentBlock.clientId === postGroupId;
		}

		let location = 'default';

		// root
		if ( isRoot ) {
			location = 'root';

			// inside section content
		} else if (parentBlock && 'canvas/section-content' === parentBlock.name) {
			const sectionBlock = getParentBlock(rootBlock, parentBlock);

			if ('full' === sectionBlock.attributes.layout) {
				if ('full' === sectionBlock.attributes.layoutAlign) {
					location = 'section-full';
				} else {
					location = 'section-wide';
				}
			} else if ('with-sidebar' === sectionBlock.attributes.layout) {
				location = 'section-content';
			}

			// inside section sidebar
		} else if (parentBlock && 'canvas/section-sidebar' === parentBlock.name) {
			location = 'section-sidebar';

			// inside row block
		} else if (parentBlock && 'canvas/column' === parentBlock.name) {
			if (parentBlock.attributes.size < 5) {
				location = 'section-sidebar';
			} else {
				location = 'section-content';
			}
		}

		return {
			location,
		};
	})(CustomBlockEdit);
}

/**
 * Register Custom Blocks
 */
jQuery(() => {
	if (blocks && blocks.length) {
		blocks.forEach((blockData) => {
			let {
				supports = {},
			} = blockData;

			if ( 'canvas/section' === blockData.name && 'page' !== canvasLocalize.postType ) {
				return;
			}

			const resultBlockData = applyFilters( 'canvas.customBlock.registerData', {
				...blockData,
				supports,
				icon: getBlockIcon(blockData),
				edit: getBlockEdit(blockData),
				save() {
					// Render in PHP.
					return null;
				},
			});

			// Register block.
			registerBlockType(blockData.name, resultBlockData);

			// Register block style.
			if (blockData.styles && blockData.length) {
				blockData.styles.forEach((styleData) => {
					registerBlockStyle(blockData.name, styleData);
				});
			}
		});
	}
});
