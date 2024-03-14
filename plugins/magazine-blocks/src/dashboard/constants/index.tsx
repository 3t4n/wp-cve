import { __ } from "@wordpress/i18n";
import * as Icon from "../components/Icon";

export const ROUTES = [
	{
		route: "/dashboard",
		label: __("Dashboard", "magazine-blocks"),
	},
	{
		route: "/blocks",
		label: __("Blocks", "magazine-blocks"),
	},
	{
		route: "/products",
		label: __("Products", "magazine-blocks"),
	},
	{
		route: "/settings",
		label: __("Settings", "magazine-blocks"),
	},
	// {
	// 	route: '/free-vs-pro',
	// 	label: __('Free vs Pro', 'magazine-blocks'),
	// },
	{
		route: "/help",
		label: __("Help", "magazine-blocks"),
	},
];

export const CHANGELOG_TAG_COLORS = {
	fix: {
		color: "primary.500",
		bgColor: "primary.100",
		scheme: "primary",
	},
	feature: {
		color: "green.500",
		bgColor: "green.50",
		scheme: "green",
	},
	enhancement: {
		color: "teal.500",
		bgColor: "teal.50",
		scheme: "teal",
	},
	added: {
		color: "pink.500",
		bgColor: "pink.50",
		scheme: "pink",
	},
	update: {
		color: "orange.500",
		bgColor: "orange.50",
		scheme: "orange",
	},
	tweak: {
		color: "purple.500",
		bgColor: "purple.50",
		scheme: "purple",
	},
};

export const BLOCKS = {
	section: {
		label: __("Section", "magazine-blocks"),
		icon: Icon.Section,
	},
	heading: {
		label: __("Heading", "magazine-blocks"),
		icon: Icon.Heading,
	},
	advertisement: {
		label: __("Advertisement", "magazine-blocks"),
		icon: Icon.Advertisement,
	},
	"banner-posts": {
		label: __("Banner Posts", "magazine-blocks"),
		icon: Icon.BannerPosts,
	},
	"grid-module": {
		label: __("Grid Module", "magazine-blocks"),
		icon: Icon.GridModule,
	},
	"featured-posts": {
		label: __("Featured Posts", "magazine-blocks"),
		icon: Icon.FeaturedPosts,
	},
	"featured-categories": {
		label: __("Featured Categories", "magazine-blocks"),
		icon: Icon.FeaturedCategories,
	},
	"tab-post": {
		label: __("Tab Post", "magazine-blocks"),
		icon: Icon.TabPost,
	},
	"post-list": {
		label: __("Post List", "magazine-blocks"),
		icon: Icon.PostList,
	},
	"post-video": {
		label: __("Post Video", "magazine-blocks"),
		icon: Icon.PostVideo,
	},
	"category-list": {
		label: __("Category List", "magazine-blocks"),
		icon: Icon.CategoryList,
	},
	"news-ticker": {
		label: __("News Ticker", "magazine-blocks"),
		icon: Icon.NewsTicker,
	},
	"date-weather": {
		label: __("Date & Weather", "magazine-blocks"),
		icon: Icon.DateWeather,
	},
	"social-icons": {
		label: __("Social Icons", "magazine-blocks"),
		icon: Icon.SocialIcons,
	},
	slider: {
		label: __("Slider", "magazine-blocks"),
		icon: Icon.Slider,
	},
};

export const SETTINGS_TABS = [
	{
		label: __("Editor Options", "magazine-blocks"),
		icon: Icon.Cog,
	},
	{
		label: __("Asset Generation", "magazine-blocks"),
		icon: Icon.ArrowsRepeat,
	},
	{
		label: __("Performance", "magazine-blocks"),
		icon: Icon.Meter,
	},
	{
		label: __("Version Control", "magazine-blocks"),
		icon: Icon.ThreeCircleNodes,
	},
	{
		label: __("Integrations", "magazine-blocks"),
		icon: Icon.ArrowsUpDown,
	},
	{
		label: __("Maintenance Mode", "magazine-blocks"),
		icon: Icon.CirclesInsideCircle,
	},
];
