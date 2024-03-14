import { FontAwesomeStyles, Icon } from "../../types";
import { localized } from "../../utils";

type Icons = Array<Icon>;

let FONT_AWESOME_SOLID: Icons = [],
	FONT_AWESOME_REGULAR: Icons = [],
	FONT_AWESOME_BRANDS: Icons = [],
	MAGAZINE_BLOCKS_ICONS: Icons = localized.icons["magazine-blocks"];

const pushFontAwesomeIcon = (
	icons: Icons,
	style: FontAwesomeStyles,
	icon: Icon
) => {
	if (style === icon.style) {
		icons.push(icon);
	}
};

for (const icon of localized.icons["font-awesome"]) {
	pushFontAwesomeIcon(FONT_AWESOME_BRANDS, "brands", icon);
	pushFontAwesomeIcon(FONT_AWESOME_SOLID, "solid", icon);
	pushFontAwesomeIcon(FONT_AWESOME_REGULAR, "regular", icon);
}

const ALL_ICONS = [
	...MAGAZINE_BLOCKS_ICONS,
	...localized.icons["font-awesome"],
];

export {
	ALL_ICONS,
	MAGAZINE_BLOCKS_ICONS,
	FONT_AWESOME_BRANDS,
	FONT_AWESOME_REGULAR,
	FONT_AWESOME_SOLID,
};
