import { registerBlockType } from "@wordpress/blocks";
import { applyFilters } from "@wordpress/hooks";
import * as advertisement from "./advertisement";
import * as bannerPosts from "./banner-posts";
import * as categoryList from "./category-list";
import * as column from "./column";
import * as dateWeather from "./date-weather";
import "./editor.scss";
import * as featuredCategories from "./featured-categories";
import * as featuredPosts from "./featured-posts";
import * as gridModule from "./grid-module";
import * as heading from "./heading";
import * as newsTicker from "./news-ticker";
import * as postList from "./post-list";
import * as postVideo from "./post-video";
import * as section from "./section";
import * as slider from "./slider";
import * as socialIcon from "./social-icon";
import * as socialIcons from "./social-icons";
import "./style.scss";
import * as tabPost from "./tab-post";
import "./variables/colors.scss";
import "./variables/structure.scss";

/* The `const blocks` is an array that contains references to various modules. Each
module represents a different block component, such as a column, section,
heading, paragraph, buttons, button, image, and spacing. These modules are
imported from their respective files and stored in the `blocks` array. */
const blocks = [
	section,
	column,
	heading,
	advertisement,
	bannerPosts,
	gridModule,
	featuredPosts,
	featuredCategories,
	tabPost,
	postList,
	postVideo,
	categoryList,
	newsTicker,
	dateWeather,
	slider,
	socialIcons,
	socialIcon,
];

/**
 * The function "registerBlocks" iterates over an array of blocks and calls the
 * "register" method on each block.
 */
export const registerBlocks = () => {
	for (const block of blocks) {
		const settings = applyFilters(
			"magazine-blocks.block.metadata",
			block.settings
		) as any;
		settings.edit = applyFilters(
			"magazine-blocks.block.edit",
			settings.edit,
			settings
		);
		registerBlockType(block.name, settings);
	}
};

export default registerBlocks;
