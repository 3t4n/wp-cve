import { Icon } from "@blocks/components";
import React from "react";
import metadata from "./block.json";
import Edit from "./Edit";
import Save from "./Save";

export const name = metadata.name;

export const settings = {
	...metadata,
	icon: <Icon type="blockIcon" name="column" size={24} />,
	edit: Edit,
	save: Save,
};
