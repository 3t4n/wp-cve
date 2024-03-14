import { Placeholder } from "@wordpress/components";
import { __ } from "@wordpress/i18n";
import { Prettify } from "blocks/components/types";
import React from "react";
import { LAYOUTS } from "../constants";

type Props = {
	hasModal: boolean;
	modalOnly: boolean;
	setAttributes: (attributes: any) => void;
	setDefaultLayout: (structure: any) => void;
	clientId: string;
};

const LayoutPlaceholder: React.ComponentType<Prettify<Props>> = (props) => {
	const { hasModal, modalOnly, setAttributes, setDefaultLayout, clientId } =
		props;
	return (
		<>
			<Placeholder
				label={__("Choose Your Layout", "magazine-blocks")}
				className="mzb-section-preset"
			>
				<div className="mzb-section-preset-group">
					{LAYOUTS.map(({ columns, structure }, index) => (
						<button
							key={index}
							className="mzb-section-preset-btn"
							onClick={() => {
								setAttributes({ columns });
								setDefaultLayout(structure);
							}}
						>
							{structure.desktop.map((s, k) => (
								<i key={k} style={{ width: s + "%" }} />
							))}
						</button>
					))}
				</div>
				{/*{!localized.isNotPostEditor && (*/}
				{/*	<div className="mzb-section-preset-import-btn">*/}
				{/*		<button*/}
				{/*			onClick={() => setAttributes({ hasModal: true })}*/}
				{/*		>*/}
				{/*			{__("View Templates", "magazine-blocks")}*/}
				{/*		</button>*/}
				{/*	</div>*/}
				{/*)}*/}
			</Placeholder>
		</>
	);
};

export default LayoutPlaceholder;
