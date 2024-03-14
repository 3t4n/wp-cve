import { useClientId, useCopyPasteStyles, useDeviceType } from "@blocks/hooks";
import { withSelect } from "@wordpress/data";
import { Fragment } from "@wordpress/element";
import { EditProps } from "blocks/types";
import classnames from "classnames";
import React from "react";
import IconRenderer from "../../components/common/IconRenderer";
import { useBlockStyle } from "../../hooks";
import "../assets/sass/blocks/grid-module/style.scss";
import InspectorControls from "./components/InspectorControls";

interface Props extends EditProps<any> {
	categories: Array<any>;
	posts: Array<any>;
	tags: Array<any>;
	numberOfPosts: number;
	page: number;
	author: Array<any>;
}

const Edit: React.ComponentType<Props> = (props) => {
	const {
		posts,
		attributes: { icon, label, hideOnDesktop },
		setAttributes,
	} = props;

	const { clientId } = useClientId(props);
	const { deviceType } = useDeviceType();
	const { CopyPasterStyleBlockControl } = useCopyPasteStyles();
	const { Style } = useBlockStyle({
		blockName: "news-ticker",
		clientId,
		attributes: props.attributes,
		deviceType,
	});

	const classNames = classnames(
		`mzb-news-ticker mzb-news-ticker-${clientId}`,
		hideOnDesktop && "magazine-blocks-hide-on-desktop"
	);

	return (
		<Fragment>
			<InspectorControls
				attributes={props.attributes}
				setAttributes={setAttributes}
			/>
			<CopyPasterStyleBlockControl withBlockControls />
			<Style />
			<div className={classNames}>
				<span className="mzb-weather">
					{" "}
					{icon.enable && (
						<IconRenderer
							type="newsTickerIcon"
							name={icon.icon}
							size={24}
						/>
					)}{" "}
				</span>
				<span className="mzb-heading">{label}</span>
				<div className={`mzb-news-ticker-box`}>
					<ul className={`mzb-news-ticker-list`}>
						{(posts || []).map((post, idx) => {
							return (
								<li key={idx}>
									{/* eslint-disable-next-line jsx-a11y/anchor-is-valid */}
									<a>{post.title.rendered}</a>
								</li>
							);
						})}
					</ul>
				</div>
			</div>
		</Fragment>
	);
};

// @ts-ignore
export default withSelect((select, props) => {
	const { getEntityRecords } = select("core");
	const {
		attributes: { category, order },
	} = props;
	const query = {
		order,
	};

	return {
		posts: getEntityRecords("postType", "post", {
			per_page: 4,
			categories: "all" === category ? undefined : parseInt(category),
		}),
		numberOfPosts:
			getEntityRecords("postType", "post", {
				per_page: -1,
				categories: "all" === category ? undefined : parseInt(category),
			})?.length || 0,
		categories:
			getEntityRecords("taxonomy", "category", { per_page: -1 }) || [],
	};
})(Edit);
