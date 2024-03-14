import { __ } from "@wordpress/i18n";
import * as Icon from "../components/Icons";

export const BLOCKS = {
	section: {
		label: __("Section", "magazine-blocks"),
		icon: Icon.Section,
		blockName: "magazine-blocks/section",
	},
	heading: {
		label: __("Heading", "magazine-blocks"),
		icon: Icon.Heading,
		blockName: "magazine-blocks/heading",
	},
	paragraph: {
		label: __("Advertisement", "magazine-blocks"),
		icon: Icon.Advertisement,
		blockName: "magazine-blocks/advertisement",
	},
	button: {
		label: __("Banner Posts", "magazine-blocks"),
		icon: Icon.BannerPosts,
		blockName: "magazine-blocks/banner-posts",
	},
	image: {
		label: __("Category List", "magazine-blocks"),
		icon: Icon.CategoryList,
		blockName: "magazine-blocks/category-list",
	},
	spacing: {
		label: __("Date & Weather", "magazine-blocks"),
		icon: Icon.DateWeather,
		blockName: "magazine-blocks/date-weather",
	},
	socials: {
		label: __("Featured Categories", "magazine-blocks"),
		icon: Icon.FeaturedCategories,
		blockName: "magazine-blocks/featured-categories",
	},
	tabs: {
		label: __("Featured Posts", "magazine-blocks"),
		icon: Icon.FeaturedPosts,
		blockName: "magazine-blocks/featured-posts",
	},
	toc: {
		label: __("Grid Module", "magazine-blocks"),
		icon: Icon.GridModule,
		blockName: "magazine-blocks/grid-module",
	},
	counter: {
		label: __("News Ticker", "magazine-blocks"),
		icon: Icon.NewsTicker,
		blockName: "magazine-blocks/news-ticker",
	},
	lottie: {
		label: __("Post List", "magazine-blocks"),
		icon: Icon.PostList,
		blockName: "magazine-blocks/post-list",
	},
	team: {
		label: __("Post Video", "magazine-blocks"),
		icon: Icon.PostVideo,
		blockName: "magazine-blocks/post-video",
	},
	countdown: {
		label: __("Slider", "magazine-blocks"),
		icon: Icon.Slider,
		blockName: "magazine-blocks/slider",
	},
	info: {
		label: __("Social Icons", "magazine-blocks"),
		icon: Icon.SocialIcons,
		blockName: "magazine-blocks/social-icons",
	},
	blockquote: {
		label: __("Tab Post", "magazine-blocks"),
		icon: Icon.TabPost,
		blockName: "magazine-blocks/tab-post",
	},
};
