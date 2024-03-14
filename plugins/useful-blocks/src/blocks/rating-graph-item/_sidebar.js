/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import {
	PanelBody,
	Button,
	ButtonGroup,
	BaseControl,
	RadioControl,
	CheckboxControl,
	TextControl,
} from '@wordpress/components';
import { useMemo, createInterpolateElement } from '@wordpress/element';
import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';

/**
 * Internal dependencies
 */
import FreePreview from '@blocks/freePreview';
import LsIconPicker from '@components/LsIconPicker';
import { textDomain, isPro } from '@blocks/config';

/**
 * 設定項目
 */
const markTypeOptions = [
	{
		label: __('Dot', textDomain),
		value: 'dot',
	},
	{
		label: __('Icon', textDomain),
		value: 'icon',
	},
	{
		label: __('Image', textDomain),
		value: 'image',
	},
];

export default ({ attributes, setAttributes }) => {
	const { activePoint, maxStep, markType, iconClass, mediaId, mediaUrl } = attributes;

	const activePoints = activePoint.split(',');

	/* eslint jsx-a11y/anchor-has-content: 0 */
	const faNote = createInterpolateElement(
		__(
			'You can specify the class name of the "solid" type <a>Font Awesome icon</a>.',
			textDomain
		),
		{
			a: (
				<a
					href='https://fontawesome.com/icons?d=gallery'
					target='_blank'
					rel='noopener noreferrer'
				/>
			),
		}
	);

	const setImage = (media) => {
		setAttributes({
			mediaId: media.id,
			mediaUrl: media.url,
		});
	};

	const removeImage = () => {
		setAttributes({
			mediaId: 0,
			mediaUrl: '',
		});
	};

	// console.log('activePoints', activePoints);
	const pointControls = useMemo(() => {
		const mapNums = maxStep === 5 ? [1, 2, 3, 4, 5] : [1, 2, 3];

		return mapNums.map((num) => {
			const numStr = String(num);
			return (
				<CheckboxControl
					key={`checkbox_key_${num}`}
					checked={activePoints.includes(numStr)}
					onChange={(checked) => {
						let newActivePoints = activePoints;
						if (checked) {
							newActivePoints.push(numStr);
						} else {
							newActivePoints = newActivePoints.filter((point) => {
								return point !== numStr;
							});
						}
						setAttributes({ activePoint: newActivePoints.join(',') });
					}}
				/>
			);
		});
	}, [maxStep, activePoint, activePoints]);

	return (
		<>
			<PanelBody title={__('Graph setting', textDomain)} initialOpen={true}>
				<div className='pb-rating-pointControls'>
					<div className='components-base-control__label'>
						{__('Position of active point', textDomain)}
					</div>
					<div className='__checks'>{pointControls}</div>
				</div>
				<FreePreview
					description={__('you can set the number of steps in the graph.', textDomain)}
				>
					<BaseControl>
						<BaseControl.VisualLabel>
							{__('Number of steps in the graph', textDomain)}
						</BaseControl.VisualLabel>
						<ButtonGroup className='pb-btn-group'>
							<Button
								isPrimary={3 === maxStep}
								onClick={() => {
									setAttributes({ maxStep: 3 });
								}}
							>
								{__('3 stages', textDomain)}
							</Button>
							<Button
								isPrimary={5 === maxStep}
								onClick={() => {
									setAttributes({ maxStep: 5 });
								}}
							>
								{__('5 stages', textDomain)}
							</Button>
						</ButtonGroup>
					</BaseControl>
				</FreePreview>
				<FreePreview description={__('you can use icons and images.', textDomain)}>
					<RadioControl
						label={__('Active point shape', textDomain)}
						selected={markType}
						options={markTypeOptions}
						onChange={(val) => {
							if (!isPro) return;
							setAttributes({ markType: val });
						}}
					/>
					{'icon' === markType && (
						<>
							<BaseControl>
								<BaseControl.VisualLabel>
									{__('Select Icon', textDomain)}
								</BaseControl.VisualLabel>
								<LsIconPicker
									value={iconClass}
									onChange={(val) => {
										setAttributes({ iconClass: val });
									}}
									clearable={false}
								/>
							</BaseControl>
							{/* <TextControl
								label={__('Icon class', textDomain)}
								value={iconClass}
								help={faNote}
								onChange={(val) => {
									setAttributes({ iconClass: val });
								}}
							/> */}
						</>
					)}
					{'image' === markType && (
						<div className='pb-media-setting -rating-graph'>
							<div className='pb-media-setting__preview'>
								{mediaUrl && <img src={mediaUrl} alt='' />}
							</div>
							<div className='pb-media-setting__btns'>
								<MediaUploadCheck>
									<MediaUpload
										onSelect={(media) => {
											// console.log(media);
											if (media) {
												setImage(media);
											} else {
												removeImage();
											}
										}}
										allowedTypes={['image', 'video']}
										value={mediaId}
										render={({ open }) => (
											<Button isPrimary onClick={open}>
												{mediaUrl
													? __('Change media', textDomain)
													: __('Select media', textDomain)}
											</Button>
										)}
									/>
								</MediaUploadCheck>
								{mediaUrl && (
									<Button
										isSecondary
										className='__delete'
										onClick={() => {
											removeImage();
										}}
									>
										{__('Delete', textDomain)}
									</Button>
								)}
							</div>
						</div>
					)}
				</FreePreview>
			</PanelBody>
		</>
	);
};
