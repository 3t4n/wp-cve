/**
 * BLOCK: in5-wp-embed
 *
 * Registering a basic block with Gutenberg.
 * Simple block, renders and saves the same content without any interactivity.
 */

//  Import CSS.
import './style.scss';
import './editor.scss';

const {RawHTML} = wp.element;
const {__} = wp.i18n; // Import __() from wp.i18n
const {registerBlockType} = wp.blocks; // Import registerBlockType() from wp.blocks
const {BlockControls, PlainText} = wp.editor;
const {withState} = wp.compose;
const {Disabled, SandBox, SVG, Path, Button, DropdownMenu} = wp.components;

/**
 * Register: aa Gutenberg Block.
 *
 * Registers a new block provided a unique name and an object defining its
 * behavior. Once registered, the block is made editor as an option to any
 * editor interface where blocks are implemented.
 *
 * @link https://wordpress.org/gutenberg/handbook/block-api/
 * @param  {string}   name     Block name.
 * @param  {Object}   settings Block settings.
 * @return {?WPBlock}          The block, if it has been successfully
 *                             registered; otherwise `undefined`.
 */
registerBlockType('cgb/block-in5-wp-embed', {
	// Block name. Block names must be string that contains a namespace prefix. Example: my-plugin/my-custom-block.
	title: __('in5 Embed'), // Block title.

	icon: <SVG viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
		<image id="image0" width="22" height="22" x="0" y="0" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAMAAADzapwJAAAABGdBTUEAALGPC/xhBQAAACBjSFJN
AAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAkFBMVEXgTCQAAAAAAADbSiTY
SiIAAADLRiDUSiLRRiIAAADTSCC6Px7ERCK1Ph0AAACvPBwAAACHLhYAAAA3EwcAAAC9QiCbNRm7
QB6VNBhQHA7kTibnVCbqWijvZCrqZDnlWjTwlHf608n72s35xK/1n3r////relzvnYXtYCj3s5Xz
jV7/9fPxp5Pzg1LpcE7xbjbTB3rwAAAAGnRSTlO7DzCF7SLVVtMWRLAloQiRBWoBPh+tdcKSWeLV
dsAAAAABYktHRCXDAckPAAAAB3RJTUUH4gsOBSYLUpDOTAAAAPJJREFUGNNt0dt2gyAQBdDBio1N
Sgixt0E0ipBE2+b//64zamovOW9sWAvOAIA3ImBhW86xmMDdN5dujk3lLcZMwv1IVY14aFrfjbzK
4YHRh4hN4HjmdQ4biyzxGKZ07oSPAhQy+5q2zv2Zj59wq0ATH6tDRexcH0LjStwpMCvEOIR45ZZZ
gwGLbfjNe+bpyoWppAFDfTytwsLpzPH65D4MnbOZJOaa76RNRTx8cMmCmGtiHT95JpeLo2c/5cSb
P6PiksT6H2+ZzU8uLY1op5iL53U2/g4Jpi+vb4VhNkrIPW1ZpkTmykxM0UoUSSKF0tP6C6OtMP9L
XovJAAAAJXRFWHRkYXRlOmNyZWF0ZQAyMDE4LTExLTE0VDEyOjM4OjExLTA3OjAwdB3nUwAAACV0
RVh0ZGF0ZTptb2RpZnkAMjAxOC0xMS0xNFQxMjozODoxMS0wNzowMAVAX+8AAAAASUVORK5CYII="/>
	</SVG>, // Block icon from Dashicons → https://developer.wordpress.org/resource/dashicons/.

	category: 'embed', // Block category — Group blocks together based on common traits E.g. common, formatting, layout widgets, embed.

	keywords: [
		__('in5 Embed'),
		__('create-guten-block'),
	],

	supports: {
		html: false,
	},

	attributes: {
		content: {
			type: 'string',
			selector: '.in5-embed-content'
		},
		active: {
			type: 'string',
			source: 'attribute',
			attribute: 'active',
		},
		mode: {
			type: 'string',
			selector: '.wp-block-cgb-block-in5-wp-embed',
			source: 'attribute',
			attribute: 'data-mode',
			default: 'edit',
		}
	},

	/**
	 * The edit function describes the structure of your block in the context of the editor.
	 * This represents what the editor will render when the block is used.
	 *
	 * The "edit" property must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	edit: (function ({attributes, className, setAttributes}) {
		const {content, active, mode} = attributes;

		const controls = [
			{
				title: __('Edit visually'),
				icon: 'edit',
				onClick: () => setAttributes({mode: 'edit'})
			},
			{
				title: __('Edit as HTML'),
				icon: 'html',
				onClick: () => setAttributes({mode: 'html'})
			},
			{
				title: __('Preview'),
				icon: 'visibility',
				onClick: () => setAttributes({mode: 'preview'})
			}
		];

		const onChangeContent = function (newContent) {
			setAttributes({content: newContent});
		};

		const in5Modal = function () {
			setAttributes({active: 'open'});

			jQuery('.in5-embed-popup').show();
			jQuery('.media-modal-backdrop').show();
			if (jQuery('.in5-file-list li').length < 1) {
				jQuery('.in5-library.pane').hide();
				jQuery('.in5-upload.pane').show();
				jQuery('.tab-library').removeClass('active');
				jQuery('.tab-upload').addClass('active');
			}
		};

		return (
			<div className="wp-block-embed wp-block-cgb-block-in5-wp-embed" data-mode={mode}>
				<BlockControls>
					<div className="components-toolbar in5-dropdown-mode">
						<DropdownMenu
							icon="menu"
							label={__('Select mode')}
							controls={ controls }
						/>
					</div>
				</BlockControls>
				<Disabled.Consumer>
					{(isDisabled) => (
						(mode === 'preview' || isDisabled) ? (
							<SandBox html={attributes.content}/>
						) : (
							(mode === 'edit') ? (
								<div className="components-placeholder wp-block-embed">
									<div className="components-placeholder__label">
										<SVG viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
											<image id="image0" width="25" height="25" x="0" y="0" href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABYAAAAWCAMAAADzapwJAAAABGdBTUEAALGPC/xhBQAAACBjSFJN
AAB6JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAkFBMVEXgTCQAAAAAAADbSiTY
SiIAAADLRiDUSiLRRiIAAADTSCC6Px7ERCK1Ph0AAACvPBwAAACHLhYAAAA3EwcAAAC9QiCbNRm7
QB6VNBhQHA7kTibnVCbqWijvZCrqZDnlWjTwlHf608n72s35xK/1n3r////relzvnYXtYCj3s5Xz
jV7/9fPxp5Pzg1LpcE7xbjbTB3rwAAAAGnRSTlO7DzCF7SLVVtMWRLAloQiRBWoBPh+tdcKSWeLV
dsAAAAABYktHRCXDAckPAAAAB3RJTUUH4gsOBSYLUpDOTAAAAPJJREFUGNNt0dt2gyAQBdDBio1N
Sgixt0E0ipBE2+b//64zamovOW9sWAvOAIA3ImBhW86xmMDdN5dujk3lLcZMwv1IVY14aFrfjbzK
4YHRh4hN4HjmdQ4biyzxGKZ07oSPAhQy+5q2zv2Zj59wq0ATH6tDRexcH0LjStwpMCvEOIR45ZZZ
gwGLbfjNe+bpyoWppAFDfTytwsLpzPH65D4MnbOZJOaa76RNRTx8cMmCmGtiHT95JpeLo2c/5cSb
P6PiksT6H2+ZzU8uLY1op5iL53U2/g4Jpi+vb4VhNkrIPW1ZpkTmykxM0UoUSSKF0tP6C6OtMP9L
XovJAAAAJXRFWHRkYXRlOmNyZWF0ZQAyMDE4LTExLTE0VDEyOjM4OjExLTA3OjAwdB3nUwAAACV0
RVh0ZGF0ZTptb2RpZnkAMjAxOC0xMS0xNFQxMjozODoxMS0wNzowMAVAX+8AAAAASUVORK5CYII="/>
										</SVG>
										In5 Embed
									</div>
									<div className="components-placeholder__fieldset">
										<Button
											onClick={in5Modal}
											className="button button-large in5-media-button"
											active={attributes.active}
										>
											{__('Click to embed here')}
										</Button>
									</div>
								</div>
							) : (
								<div>
									<PlainText
										value={attributes.content}
										onChange={onChangeContent}
										placeholder={__('Write HTML…')}
										aria-label={__('HTML')}
										className="in5-embed-content"
									/>
								</div>
							)
						)
					)}
				</Disabled.Consumer>
			</div>
		);
	}),

	/**
	 * The save function defines the way in which the different attributes should be combined
	 * into the final markup, which is then serialized by Gutenberg into post_content.
	 *
	 * The "save" property must be specified and must be a valid function.
	 *
	 * @link https://wordpress.org/gutenberg/handbook/block-api/block-edit-save/
	 */
	save({attributes}) {
		return <RawHTML data-mode={attributes.mode}>{attributes.content}</RawHTML>

	},
});
