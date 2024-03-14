import { Icon } from "@blocks/components";
import React from "react";
import metadata from "./block.json";
import Edit from "./Edit";
import "./editor.scss";
import Save from "./Save";
import "./style.scss";

export const name = metadata.name;

export const settings = {
	...metadata,
	icon: <Icon type="blockIcon" name="section" size={24} />,
	edit: Edit,
	save: Save,
};
