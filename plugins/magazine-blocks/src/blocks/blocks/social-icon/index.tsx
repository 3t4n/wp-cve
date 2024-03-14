import React from "react";
import { Icon } from "../../components";
import "../assets/sass/blocks/social-icons/style.scss";
import attributes from "./attributes";
import metadata from "./block.json";
import Edit from "./Edit";
import Save from "./Save";

export const name = metadata.name;

export const settings = {
	...metadata,
	icon: <Icon type="blockIcon" name="social-icon" size={24} />,
	attributes,
	edit: Edit,
	save: Save,
};
