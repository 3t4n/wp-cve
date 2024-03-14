import React from "react";
import { Icon } from "../../components";
import attributes from "./attributes";
import metadata from "./block.json";
import Edit from "./Edit";
import "./style.scss";

export const name = metadata.name;

export const settings = {
	...metadata,
	icon: <Icon type="blockIcon" name="dateWeather" size={24} />,
	attributes,
	edit: Edit,
};
