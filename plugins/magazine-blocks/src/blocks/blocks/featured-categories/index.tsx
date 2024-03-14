import React from "react";
import { Icon } from "../../components";
import "../assets/sass/blocks/featured-categories/style.scss";
import metadata from "./block.json";
import Edit from "./Edit";

export const name = metadata.name;

export const settings = {
	...metadata,
	icon: <Icon type="blockIcon" name="featuredCategories" size={24} />,
	edit: Edit,
	save: () => null,
};
