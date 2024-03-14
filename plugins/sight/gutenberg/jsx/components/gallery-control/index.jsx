
const { __ } = wp.i18n;

const {
	Component,
} = wp.element;

const {
	BaseControl,
	DropZone,
	Button,
} = wp.components;

const {
	MediaPlaceholder,
	MediaUpload,
	mediaUpload,
} = wp.editor;

/**
 * Component
 */
export default class ComponentGalleryControl extends Component {
	constructor() {
		super(...arguments);
	}

	render() {
		const ALLOWED_MEDIA_TYPES = [ 'image' ];

		const {
			val,
			label,
			help,
			onChange,
		} = this.props;

		return (
			<BaseControl
				label={ label || false }
				help={ help || false }
			>
				{ ! val || ! val.length ? (
					<MediaPlaceholder
						icon="format-gallery"
						labels={ {
							title: label,
							name: __( 'images' ),
						} }
						onSelect={ ( images ) => {
							const result = images.map( ( image ) => {
								return image.id;
							} );

							onChange( result );
						} }
						accept="image/*"
						allowedTypes={ ALLOWED_MEDIA_TYPES }
						disableMaxUploadErrorMessages
						multiple
						onError={ ( e ) => {
							// eslint-disable-next-line no-console
							console.log( e );
						} }
					/>
				) : '' }
				{ val && val.length ? (
					<MediaUpload
						onSelect={ ( images ) => {
							const result = images.map( ( image ) => {
								return image.id;
							} );

							onChange( result );
						} }
						allowedTypes={ ALLOWED_MEDIA_TYPES }
						multiple
						gallery
						value={ val }
						render={ ( { open } ) => (
							<div
								className="sight-gutenberg-component-gallery"
								onClick={ open }
								role="presentation"
							>
								<DropZone
									onFilesDrop={ ( files ) => {
										const currentImages = val || [];
										mediaUpload( {
											allowedTypes: ALLOWED_MEDIA_TYPES,
											filesList: files,
											onFileChange: ( images ) => {
												const result = images.map( ( image ) => {
													return image.id;
												} );

												onChange( currentImages.concat( result ) );
											},
											onError( e ) {
												// eslint-disable-next-line no-console
												console.log( e );
											},
										} );
									} }
								/>

								{ val ? (
								<div className="sight-gutenberg-component-gallery-list">
									{val.map(imageId => {
										return (
											<img src={ sightBlockConfig.ajax_url + '?action=sight_render_thumbnail&image_id=' + imageId } />
										)
									})}
								</div>
								) : '' }

								<div className="sight-gutenberg-component-gallery-button">
									<Button isDefault={ true }>{ __( 'Edit Gallery' ) }</Button>
								</div>
							</div>
						) }
					/>
				) : '' }
			</BaseControl>
		);
	}
}
