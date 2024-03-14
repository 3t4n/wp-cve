const { __ } = wp.i18n;
const { compose } = wp.compose;
const { Component } = wp.element;
const { BaseControl, Button, TextControl, RangeControl } = wp.components;
const { MediaUpload, MediaUploadCheck } = wp.editor;
const { PluginDocumentSettingPanel } = wp.editPost;
const { withSelect, withDispatch, } = wp.data;
const { registerPlugin } = wp.plugins;

if ( 'sight-projects' === sightVideoSettings.postType ) {
	// Fetch the post meta.
	const applyWithSelect = withSelect( ( select ) => {
		const { getEditedPostAttribute } = select( 'core/editor' );

		return {
			meta: getEditedPostAttribute( 'meta' ),
		};
	} );

	// Provide method to update post meta.
	const applyWithDispatch = withDispatch( ( dispatch, { meta } ) => {
		const { editPost } = dispatch( 'core/editor' );

		return {
			updateMeta( newMeta ) {
				editPost( { meta: { ...meta, ...newMeta } } );
			},
		};
	} );

	// Create Component.
	class ThemeVideoOptions extends Component {
		render() {
			const {
				meta: {
					sight_post_video_url: sight_post_video_url,
					sight_post_video_bg_start_time: sight_post_video_bg_start_time,
					sight_post_video_bg_end_time: sight_post_video_bg_end_time,
				} = {},
				updateMeta,
			} = this.props;

			return (
				<PluginDocumentSettingPanel title={__("Video Background", "sight")}>
					<BaseControl>
						<TextControl
							label={__('Local or YouTube URL', 'sight')}
							value={ sight_post_video_url }
							onChange={ ( value ) => {
								updateMeta( { sight_post_video_url: value || '' } );
							} }
						/>

						<MediaUploadCheck>
							<MediaUpload
								onSelect={ ( media ) => {
									updateMeta( { sight_post_video_url: media.url || '' } );
								} }
								accept="video/mp4"
								allowedTypes={ [ 'video' ] }
								render={ ( { open } ) => (
									<Button isSecondary onClick={ open }>
										{__('Select Video File', "sight")}
									</Button>
								) }
							/>
						</MediaUploadCheck>
					</BaseControl>

					<RangeControl
						label={__('Start Time (sec)', 'sight')}
						value={ sight_post_video_bg_start_time }
						onChange={ ( value ) => {
							updateMeta( { sight_post_video_bg_start_time: value || 0 } );
						} }
						step={1}
						min={0}
						max={10000}
					/>

					<RangeControl
						label={__('End Time (sec)', 'sight')}
						value={ sight_post_video_bg_end_time }
						onChange={ ( value ) => {
							updateMeta( { sight_post_video_bg_end_time: value || 0 } );
						} }
						step={1}
						min={0}
						max={10000}
					/>
				</PluginDocumentSettingPanel>
			);
		}
	}

	// Combine the higher-order components.
	const render = compose( [
		applyWithSelect,
		applyWithDispatch
	] )( ThemeVideoOptions );

	// Register panel.
	registerPlugin( 'sight-video-options', { icon: false, render } );
}
