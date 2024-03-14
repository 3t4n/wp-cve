import { BlockEditProps } from "@wordpress/blocks";
import { TypographyProps } from "../controls/typography/types";

export type MagazineBlocksLocalized = {
	isNotPostEditor: boolean;
	isWP59OrAbove: boolean;
	nonce: string;
	ajaxUrl: string;
	temperature: string;
	weather: string;
	location: string;
	mediaItems: Array<{
		ID: number;
		alt_text: string;
		comment_count: string;
		comment_status: string;
		filter: string;
		guid: string;
		media_details: {
			file: string;
			filesize: number;
			height: number;
			width: number;
			image_meta: {
				aperture: string;
				camera: string;
				caption: string;
				copyright: string;
				created_timestamp: string;
				credit: string;
				focal_length: string;
				iso: string;
				keywords: Array<string>;
				orientation: string;
				shutter_speed: string;
				title: string;
			};
			sizes: {
				[key: string]: {
					file: string;
					width: number;
					height: number;
					"mime-type": string;
					filesize: number;
				};
			};
		};
		menu_order: number;
		mime_type: string;
		ping_status: string;
		pinged: string;
		post_author: string;
		post_content: string;
		post_date: string;
		post_date_gmt: string;
		post_content_filtered: string;
		post_excerpt: string;
		post_mime_type: string;
		post_modified: string;
		post_modified_gmt: string;
		post_name: string;
		post_parent: number;
		post_password: string;
		post_status: string;
		post_title: string;
		post_type: string;
		source_url: string;
		to_ping: string;
	}>;
	configs: {
		blocks: Record<string, boolean>;
		performance: {
			"allow-only-selected-fonts": boolean;
			"allowed-fonts": Array<{
				id: string;
				lastModified: string;
				family: string;
				popularity?: number;
				defSubset?: string;
				defVariant: string;
				subsets: string[];
				variants: string[];
				version: string;
			}>;
		};
		editor: {
			"auto-collapse-panels": boolean;
			"copy-paste-styles": boolean;
			"design-library": boolean;
			"editor-blocks-spacing": number;
			"section-width": number;
		};
		"global-styles": GlobalStyles;
	};
	googleFonts: Array<{
		id: string;
		lastModified: string;
		family: string;
		popularity?: number;
		defSubset?: string;
		defVariant: string;
		subsets: string[];
		variants: string[];
		version: string;
	}>;
	icons: {
		"font-awesome": Array<FontAwesomeIcon>;
		"magazine-blocks": Array<Icon>;
		all: Record<"string", Icon | FontAwesomeIcon>;
	};
};

interface GlobalCommonStyle {
	name: string;
	id: string;
}

export interface GlobalColorStyle extends GlobalCommonStyle {
	value?: string;
}

export interface GlobalTypographyStyle extends GlobalCommonStyle {
	value?: TypographyProps["value"];
}

export type GlobalStyles = {
	colors: Array<GlobalColorStyle>;
	typographies: Array<GlobalTypographyStyle>;
};

type FontAwesomeIcon = Icon & {
	style: FontAwesomeStyles;
};

export type Icon = {
	label: string;
	style?: FontAwesomeStyles;
	svg: string;
	id: string;
};

export type FontAwesomeStyles = "solid" | "brands" | "regular";

export type TemplateData = {
	ID: number;
	category: Array<{
		name: string;
		slug: string;
	}>;
	children: Array<TemplateData>;
	included_blocks: Array<{
		value: string;
		label: string;
	}>;
	permalink: string;
	post_name: string;
	post_parent: number;
	post_thumbnail: string;
	post_title: string;
	slug: string;
};

export type LibraryDataResponse = {
	categorized_sections: Record<
		string,
		{
			name: string;
			slug: string;
			count: number;
			items: Array<TemplateData>;
		}
	>;
	categorized_templates: LibraryDataResponse["categorized_sections"];
};

export interface EditProps<T extends Record<string, any>>
	extends BlockEditProps<T> {
	name: string;
}

export type Prettify<T> = {
	[K in keyof T]: T[K];
} & {};
