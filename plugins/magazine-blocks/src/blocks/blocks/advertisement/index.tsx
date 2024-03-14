import React from "react";
import { Icon } from "../../components";
import "../assets/sass/blocks/featured-posts/style.scss";
import metadata from "./block.json";
import Edit from "./Edit";
import Save from "./Save";

export const name = metadata.name;

export const settings = {
	...metadata,
	icon: <Icon type="blockIcon" name="advertisement" size={24} />,
	edit: Edit,
	save: Save,
};
