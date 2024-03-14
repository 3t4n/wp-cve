import { RichText } from "@wordpress/block-editor";
import { BlockSaveProps } from "@wordpress/blocks";
import { Fragment } from "@wordpress/element";
import { __ } from "@wordpress/i18n";
import classnames from "classnames";
import { escape } from "lodash";
import React from "react";
import "../assets/sass/blocks/heading/style.scss";

const Save: React.ComponentType<BlockSaveProps<any>> = (props) => {
	const {
		className,
		attributes: {
			enableSubHeading,
			label,
			text,
			cssID,
			typography,

			clientId,
			markup,

			headingLayout1AdvancedStyle,
			headingLayout2AdvancedStyle,
			headingLayout,

			size,
			hideOnDesktop,
		},
	} = props;

	const classNames = classnames(
		`mzb-heading mzb-heading-${clientId} mzb-${headingLayout}`,
		size && `is-${size}`,
		className,
		typography?._className,
		hideOnDesktop && "magazine-blocks-hide-on-desktop",
		{
			[`mzb-${headingLayout1AdvancedStyle}`]:
				headingLayout === `heading-layout-1`,
			[`mzb-${headingLayout2AdvancedStyle}`]:
				headingLayout === `heading-layout-2`,
		}
	);

	return (
		<Fragment>
			<div className={classNames}>
				<div className="mzb-heading-inner">
					<RichText.Content
						id={cssID ? cssID : null}
						tagName={markup}
						placeholder={__("This is heading", "magazine-blocks")}
						value={text}
					/>
				</div>

				{enableSubHeading && (
					<div className="sub-heading">
						<RichText.Content
							id={cssID ? cssID : null}
							tagName="p"
							value={escape(label)}
						/>
					</div>
				)}
			</div>
		</Fragment>
	);
};

export default Save;
