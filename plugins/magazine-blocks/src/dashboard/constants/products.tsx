import { __ } from "@wordpress/i18n";
import * as Icon from "../components/Icon";
import blockartBlocks from "../images/blockart-blocks.webp";
import colormag from "../images/colormag.webp";
import evf from "../images/evf.webp";
import masteriyo from "../images/masteriyo.webp";
import ur from "../images/ur.webp";
import zakra from "../images/zakra.webp";

export const PLUGINS = [
	{
		label: "Masteriyo",
		slug: "learning-management-system/lms.php",
		description: __(
			"WordPress LMS & e-Learning plugin to create and sell online courses. Easy quiz creation with inbuilt quiz builder.",
			"magazine-blocks"
		),
		type: "plugin",
		image: masteriyo,
		website: "https://masteriyo.com/",
		shortDescription: __(
			"WordPress e-Learning Plugin with Quiz Builder.",
			"magazine-blocks"
		),
		logo: Icon.Masteriyo,
	},
	{
		label: "User Registration",
		slug: "user-registration/user-registration.php",
		description: __(
			"The best Drag and drop user registration form and login form builder with a user profile page, email notification, user roles assignment, and more.",
			"magazine-blocks"
		),
		type: "plugin",
		image: ur,
		website: "https://wpuserregistration.com/",
		shortDescription: __(
			"User Forms, Profiles, Roles, Notifications.",
			"magazine-blocks"
		),
		logo: Icon.UR,
	},
	{
		label: "Everest Forms",
		slug: "everest-forms/everest-forms.php",
		description: __(
			"Fast, Lightweight & Secure Contact Form plugin. Beautiful & Responsive Pre-Built Templates.",
			"magazine-blocks"
		),
		type: "plugin",
		image: evf,
		website: "https://everestforms.net/",
		shortDescription: __(
			"Quick, Secure Contact Form with Templates.",
			"magazine-blocks"
		),
		logo: Icon.EVF,
	},
	{
		label: "BlockArt Blocks",
		slug: "blockart-blocks/blockart.php",
		description: __(
			"Craft your website beautifully using Gutenberg blocks like section/column, heading, button, etc. Unlimited possibilities of design with features like colors, backgrounds, typography, layouts, spacing, etc.",
			"magazine-blocks"
		),
		type: "plugin",
		image: blockartBlocks,
		website: "https://wpblockart.com/magazine-blocks/",
		shortDescription: __(
			"Gutenberg Editor, Website Builder, Page Builder with Sections, Template Library & Starter Sites",
			"magazine-blocks"
		),
		logo: Icon.MagazineBlocks,
	},
];

export const THEMES = [
	{
		label: "Zakra",
		slug: "zakra",
		description: __(
			"A powerful and versatile multipurpose theme that makes it easy to create beautiful and professional websites. With over free 40 pre-designed starter demo sites to choose from, you can quickly build a unique and functional site that fits your specific needs.",
			"magazine-blocks"
		),
		type: "theme",
		image: zakra,
		website: "https://zakratheme.com/",
	},
	{
		label: "ColorMag",
		slug: "colormag",
		description: __(
			"ColorMag is always the best choice when it comes to magazine, news, and blog WordPress themes. You can create elegant and modern websites for news portals, online magazines, and publishing sites.",
			"magazine-blocks"
		),
		type: "theme",
		image: colormag,
		website: "https://themegrill.com/themes/colormag/",
	},
];
