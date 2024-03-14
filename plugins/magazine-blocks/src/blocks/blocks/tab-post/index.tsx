import React from "react";
import { Icon } from "../../components";
import "../assets/sass/blocks/tab-post/style.scss";
import metadata from "./block.json";
import Edit from "./Edit";

export const name = metadata.name;

export const settings = {
	...metadata,
	icon: <Icon type="blockIcon" name="tabPost" size={24} />,
	edit: Edit,
};
