/**
 * Internal dependencies
 */
import "./block.scss";

/**
 * WordPress dependencies
 */
const {
	__,
} = wp.i18n;

const {
	addFilter,
} = wp.hooks;

const {
	Button,
	DropZone,
} = wp.components;

const {
	MediaPlaceholder,
	MediaUpload,
	mediaUpload,
} = wp.blockEditor;

const {
    createBlock,
} = wp.blocks;

/**
 * Custom block Edit output for Slider Gallery block.
 *
 * @param {JSX} edit Original block edit.
 * @param {Object} blockProps Block data.
 *
 * @return {JSX} Block edit.
 */
function editRender( edit, blockProps ) {
	if ( 'canvas/slider-gallery' !== blockProps.name ) {
		return edit;
	}

	const {
		attributes,
		setAttributes,
		isSelected,
	} = blockProps;

	const {
		images,
	} = attributes;

	const ALLOWED_MEDIA_TYPES = [ 'image' ];

	const hasImages = images && images.length;

	// show gallery with images.
	if ( hasImages ) {
		return (
			<div>
				{ edit }
				<DropZone
					onFilesDrop={ ( files ) => {
						const currentImages = images || [];
						mediaUpload( {
							allowedTypes: ALLOWED_MEDIA_TYPES,
							filesList: files,
							onFileChange: ( images ) => {
								const result = images.map( ( image ) => {
									return image.id;
								} );

								setAttributes( {
									images: currentImages.concat( result ),
								} );
							},
							onError( e ) {
								// eslint-disable-next-line no-console
								console.log( e );
							},
						} );
					} }
				/>
				{ isSelected ? (
					<div className="cnvs-block-jg-add-media-button">
						<MediaUpload
							onSelect={ ( images ) => {
								const result = images.map( ( image ) => {
									return image.id;
								} );

								setAttributes( {
									images: result,
								} );
							} }
							allowedTypes={ ALLOWED_MEDIA_TYPES }
							multiple
							gallery
							value={ images }
							render={ ( { open } ) => (
								<Button isDefault={ true } onClick={ open }>{ __( 'Edit Gallery' ) }</Button>
							) }
						/>
					</div>
				) : '' }
			</div>
		);
	}

	// add media upload if no images selected.
	return (
		<div>
			<MediaPlaceholder
				icon="format-gallery"
				labels={ {
					title: __( 'Gallery' ),
					instructions: __( 'Drag images, upload new ones or select files from your library.' ),
				} }
				onSelect={ ( images ) => {
					const result = images.map( ( image ) => {
						return image.id;
					} );

					setAttributes( {
						images: result,
					} );
				} }
				accept="image/*"
				allowedTypes={ ALLOWED_MEDIA_TYPES }
				multiple
				value={ undefined }
				onError={ ( e ) => {
					// eslint-disable-next-line no-console
					console.log( e );
				} }
			/>
		</div>
	);
}

/**
 * Block transformations.
 *
 * @param {Object} blockData Block data.
 *
 * @return {Object} Block data.
 */
function registerData( blockData ) {
	if ( 'canvas/slider-gallery' === blockData.name ) {
		blockData.transforms = {
			from: [
				{
					type: 'block',
					blocks: [ 'canvas/justified-gallery' ],
					transform: function( attrs ) {
						return createBlock(
							'canvas/slider-gallery',
							{
								images: attrs.images,
								imageSize: attrs.imageSize,
								linkTo: attrs.linkTo,
							}
						);
					},
				},
			],
		};
	}

    return blockData;
}

addFilter( 'canvas.customBlock.editRender', 'canvas/slider-gallery/editRender', editRender );
addFilter( 'canvas.customBlock.registerData', 'canvas/slider-gallery/registerData', registerData );
