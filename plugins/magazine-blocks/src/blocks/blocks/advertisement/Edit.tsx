import { useClientId, useCopyPasteStyles, useDeviceType } from "@blocks/hooks";
import { MediaPlaceholder } from "@wordpress/block-editor";
import { withSelect } from "@wordpress/data";
import { Fragment } from "@wordpress/element";
import { EditProps } from "blocks/types";
import classnames from "classnames";
import React from "react";
import { useBlockStyle } from "../../hooks";
import "../assets/sass/blocks/featured-posts/style.scss";
import InspectorControls from "./components/InspectorControls";

interface Props extends EditProps<any> {
	noticeOperations: number;
	noticeUI: JSX.Element;
	value: number;
	src: string;
	id: number;
}

const Edit: React.ComponentType<Props> = (props) => {
	const {
		className,
		attributes: { imageSize, advertisementImage, hideOnDesktop, size },
		setAttributes,
		noticeUI,
	} = props;
	const id = advertisementImage?.id ?? undefined;
	const url = advertisementImage?.url ?? undefined;
	const alt = advertisementImage?.alt ?? "";

	const { clientId } = useClientId(props);
	const { deviceType } = useDeviceType();
	const { CopyPasterStyleBlockControl } = useCopyPasteStyles();

	const { Style } = useBlockStyle({
		blockName: "advertisement",
		clientId,
		attributes: props.attributes,
		deviceType,
	});

	const classNames = classnames(
		`mzb-advertisement mzb-advertisement-${clientId}`,
		size && `is-${size}`,
		className,
		hideOnDesktop && "magazine-blocks-hide-on-desktop"
	);

	interface NoticeOperations {
		removeAllNotices: () => void;
		createErrorNotice: (message: string) => void;
	}

	const onError = (message: string, noticeOperations: NoticeOperations) => {
		noticeOperations.removeAllNotices();
		noticeOperations.createErrorNotice(message);
	};
	const onSelect = (media: any) => {
		if (!media?.url) {
			return;
		}
		const { url: u, id: i, height: h, width: w, alt: a } = media;
		setAttributes({
			advertisementImage: { url: u, id: i, height: h, width: w, alt: a },
		});
	};

	const onSelectURL = (u: string) => {
		setAttributes({
			advertisementImage: {
				url: u,
				id: undefined,
				height: undefined,
				width: undefined,
				alt: undefined,
			},
		});
	};

	return (
		<Fragment>
			<InspectorControls
				attributes={props.attributes}
				setAttributes={setAttributes}
			/>
			<CopyPasterStyleBlockControl withBlockControls />
			<Style />
			<div className={classNames}>
				<div className={`mzb-advertisement-content`}>
					<div className={`mzb-advertisement-${imageSize}`}>
						{url ? (
							<img src={url} alt={alt ?? ""} />
						) : (
							<MediaPlaceholder
								onSelect={onSelect}
								notices={noticeUI}
								accept="image/*"
								allowedTypes={["image"]}
								onSelectURL={onSelectURL}
							/>
						)}
					</div>
				</div>
			</div>
		</Fragment>
	);
};

// @ts-ignore
export default withSelect(() => {})(Edit);
