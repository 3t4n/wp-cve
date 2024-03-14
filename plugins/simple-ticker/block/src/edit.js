import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';
import { TextControl, ToggleControl, PanelBody } from '@wordpress/components';
import { InspectorControls, PanelColorSettings, useBlockProps } from '@wordpress/block-editor';

export default function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps();
	return (
		<div { ...blockProps }>
			<ServerSideRender
				block = 'simple-ticker/simpleticker-block'
				attributes = { attributes }
			/>
			<TextControl
				label = { __( 'Ticker1', 'simple-ticker' ) }
				value = { attributes.ticker1_text }
				onChange = { ( value ) => setAttributes( { ticker1_text: value } ) }
			/>
			<TextControl
				label = 'URL1'
				value = { attributes.ticker1_url }
				onChange = { ( value ) => setAttributes( { ticker1_url: value } ) }
			/>
			<TextControl
				label = { __( 'Ticker2', 'simple-ticker' ) }
				value = { attributes.ticker2_text }
				onChange = { ( value ) => setAttributes( { ticker2_text: value } ) }
			/>
			<TextControl
				label = 'URL2'
				value = { attributes.ticker2_url }
				onChange = { ( value ) => setAttributes( { ticker2_url: value } ) }
			/>
			<TextControl
				label = { __( 'Ticker3', 'simple-ticker' ) }
				value = { attributes.ticker3_text }
				onChange = { ( value ) => setAttributes( { ticker3_text: value } ) }
			/>
			<TextControl
				label = 'URL3'
				value = { attributes.ticker3_url }
				onChange = { ( value ) => setAttributes( { ticker3_url: value } ) }
			/>

			<InspectorControls>
				<PanelBody title = { __( 'Ticker1', 'simple-ticker' ) } initialOpen = { false }>
					<TextControl
						label = { __( 'Ticker1', 'simple-ticker' ) }
						value = { attributes.ticker1_text }
						onChange = { ( value ) => setAttributes( { ticker1_text: value } ) }
					/>
					<TextControl
						label = 'URL'
						value = { attributes.ticker1_url }
						onChange = { ( value ) => setAttributes( { ticker1_url: value } ) }
					/>
					<PanelColorSettings
						title = { __( 'Color Settings', 'simple-ticker' ) }
						colorSettings = { [
							{
								value: attributes.ticker1_color,
								onChange: ( colorValue ) => setAttributes( { ticker1_color: colorValue } ),
								label: __( 'Color', 'simple-ticker' ),
							}
						] }
					>
					</PanelColorSettings>
				</PanelBody>
				<PanelBody title = { __( 'Ticker2', 'simple-ticker' ) } initialOpen = { false }>
					<TextControl
						label = { __( 'Ticker2', 'simple-ticker' ) }
						value = { attributes.ticker2_text }
						onChange = { ( value ) => setAttributes( { ticker2_text: value } ) }
					/>
					<TextControl
						label = 'URL'
						value = { attributes.ticker2_url }
						onChange = { ( value ) => setAttributes( { ticker2_url: value } ) }
					/>
					<PanelColorSettings
						title = { __( 'Color Settings', 'simple-ticker' ) }
						colorSettings = { [
							{
								value: attributes.ticker2_color,
								onChange: ( colorValue ) => setAttributes( { ticker2_color: colorValue } ),
								label: __( 'Color', 'simple-ticker' ),
							}
						] }
					>
					</PanelColorSettings>
				</PanelBody>
				<PanelBody title = { __( 'Ticker3', 'simple-ticker' ) } initialOpen = { false }>
					<TextControl
						label = { __( 'Ticker3', 'simple-ticker' ) }
						value = { attributes.ticker3_text }
						onChange = { ( value ) => setAttributes( { ticker3_text: value } ) }
					/>
					<TextControl
						label = 'URL'
						value = { attributes.ticker3_url }
						onChange = { ( value ) => setAttributes( { ticker3_url: value } ) }
					/>
					<PanelColorSettings
						title = { __( 'Color Settings', 'simple-ticker' ) }
						colorSettings = { [
							{
								value: attributes.ticker3_color,
								onChange: ( colorValue ) => setAttributes( { ticker3_color: colorValue } ),
								label: __( 'Color', 'simple-ticker' ),
							}
						] }
					>
					</PanelColorSettings>
				</PanelBody>
				<PanelBody title = { __( 'Sticky Posts', 'simple-ticker' ) } initialOpen = { false }>
					<ToggleControl
						label = { __( 'Include sticky_posts', 'simple-ticker' ) }
						checked = { attributes.sticky_posts_display }
						onChange = { ( value ) => setAttributes( { sticky_posts_display: value } ) }
					/>
					<PanelColorSettings
						title = { __( 'Color Settings', 'simple-ticker' ) }
						colorSettings = { [
							{
								value: attributes.sticky_posts_title_color,
								onChange: ( colorValue ) => setAttributes( { sticky_posts_title_color: colorValue } ),
								label: __( 'Title color', 'simple-ticker' ),
							},
							{
								value: attributes.sticky_posts_content_color,
								onChange: ( colorValue ) => setAttributes( { sticky_posts_content_color: colorValue } ),
								label: __( 'Content color', 'simple-ticker' ),
							},
						] }
					>
					</PanelColorSettings>
				</PanelBody>
				<PanelBody title = { __( 'WooCommerce Sales', 'simple-ticker' ) } initialOpen = { false }>
					<ToggleControl
						label = { __( 'Include WooCommerce Sales', 'simple-ticker' ) }
						checked = { attributes.woo_sales_display }
						onChange = { ( value ) => setAttributes( { woo_sales_display: value } ) }
					/>
					<PanelColorSettings
						title = { __( 'Color Settings', 'simple-ticker' ) }
						colorSettings = { [
							{
								value: attributes.woo_sales_color,
								onChange: ( colorValue ) => setAttributes( { woo_sales_color: colorValue } ),
								label: __( 'Color', 'simple-ticker' ),
							}
						] }
					>
					</PanelColorSettings>
				</PanelBody>
			</InspectorControls>
		</div>
	);
}
