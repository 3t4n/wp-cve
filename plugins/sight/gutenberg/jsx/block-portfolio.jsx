/**
 * Import panels
 */
import './panels/panel-settings.jsx';
import './panels/panel-meta.jsx';
import './panels/panel-media.jsx';
import './panels/panel-typography.jsx';
import './panels/panel-query.jsx';
import './panels/panel-color.jsx';

/**
 * Import layouts
 */
import './layouts/layout-standard.jsx';

/**
 * Internal dependencies
 */
import { isFieldVisible } from './helpers.jsx';

/**
 * Components dependencies
 */
import ImageSelector from './components/image-selector';
import ServerSideRender from './components/server-side-render';

/**
 * WordPress dependencies
 */
const { __ } = wp.i18n;
const { registerBlockType } = wp.blocks;

const {
	Component,
	Fragment,
	RawHTML,
} = wp.element;

const {
	applyFilters,
} = wp.hooks;

const {
	BaseControl,
	PanelBody,
	Disabled,
} = wp.components;

const {
	InspectorControls,
} = wp.editor;

registerBlockType('sight/portfolio', {
	title: sightBlockConfig.name,
	icon: <RawHTML>{ sightBlockConfig.icon }</RawHTML>,
	category: sightBlockConfig.category,
	attributes: sightBlockConfig.attributes,
	edit: (props) => {
		const {
			attributes,
			setAttributes,
		} = props;

		const config = sightBlockConfig;

		// Merge props.
		props = {
			isFieldVisible,
			...props
		};

		// Register panels.
		const panelSettings   = applyFilters( 'sight.blockSettings.fields', null, props, config );
		const panelMeta       = applyFilters( 'sight.metaSettings.fields', null, props, config );
		const panelMedia      = applyFilters( 'sight.mediaSettings.fields', null, props, config );
		const panelTypography = applyFilters( 'sight.typographySettings.fields', null, props, config );
		const panelQuery      = applyFilters( 'sight.querySettings.fields', null, props, config );
		const panelPagination = applyFilters( 'sight.paginationSettings.fields', null, props, config );
		const panelColor      = applyFilters( 'sight.colorSettings.fields', null, props, config );

		// Render.
		return (
			<div className="sight-component-custom-blocks">
				<Disabled>
					<ServerSideRender
						block="sight/portfolio"
						blockProps={props}
						attributes={attributes}
					/>
				</Disabled>

				<Fragment>
					<InspectorControls>

							{ applyFilters( 'sight.InspectorControls.before', null, props, config ) }

							{ Object.keys(config.layouts).length > 1 ? (
								<PanelBody
									title={__('Layout')}
									initialOpen={ true }
								>
									<BaseControl>
										<ImageSelector
											value={ attributes['layout'] }
											onChange={(val) => {
												setAttributes({ 'layout': val });
											}}
											items={config.layouts}
										/>
									</BaseControl>
								</PanelBody>
							) : null }

							{ panelSettings ? (
								 <PanelBody
									title={__('Block Settings')}
									initialOpen={ true }
								>
									<BaseControl> { panelSettings } </BaseControl>
								</PanelBody>
							) : null }

							{ panelMeta ? (
								 <PanelBody
									title={__('Meta Settings')}
									initialOpen={ false }
								>
									<BaseControl> { panelMeta } </BaseControl>
								</PanelBody>
							) : null }

							{ panelMedia ? (
								 <PanelBody
									title={__('Media Settings')}
									initialOpen={ false }
								>
									<BaseControl> { panelMedia } </BaseControl>
								</PanelBody>
							) : null }

							{ panelTypography ? (
								 <PanelBody
									title={__('Typography Settings')}
									initialOpen={ false }
								>
									<BaseControl> { panelTypography } </BaseControl>
								</PanelBody>
							) : null }

							{ panelQuery ? (
								 <PanelBody
									title={__('Query Settings')}
									initialOpen={ false }
								>
									<BaseControl> { panelQuery } </BaseControl>
								</PanelBody>
							) : null }

							{ panelColor ? (
								 <PanelBody
									title={__('Color Settings')}
									initialOpen={ false }
								>
									<BaseControl> { panelColor } </BaseControl>
								</PanelBody>
							) : null }

							{ applyFilters( 'sight.InspectorControls.after', ( null ), props, config ) }
					</InspectorControls>
				</Fragment>
			</div>
		);
	},
	save() {
		// Render in PHP.
		return null;
	},
});
