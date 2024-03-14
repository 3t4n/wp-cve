export type Prettify<T> = {
	[K in keyof T]: T[K];
} & {};

export type MagazineBlocksLocalized = {
	version: string;
	plugins: {
		"everest-forms/everest-forms.php":
			| "active"
			| "inactive"
			| "not-installed";
		"user-registration/user-registration.php":
			| "active"
			| "inactive"
			| "not-installed";
		"learning-management-system/lms.php":
			| "active"
			| "inactive"
			| "not-installed";
		"blockart-blocks/blockart.php": "active" | "inactive" | "not-installed";
	};
	adminUrl: string;
	themes: {
		zakra: "active" | "inactive" | "no-installed";
		colormag: "active" | "inactive" | "no-installed";
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
};

export type BlockSettingsMap = {
	"featured-posts": boolean;
	section: boolean;
};

export type EditorSettingsMap = {
	"section-width": number;
	"editor-blocks-spacing": number;
	"design-library": boolean;
	"responsive-breakpoints": {
		tablet: number;
		mobile: number;
	};
	"copy-paste-styles": boolean;
	"auto-collapse-panels": boolean;
};

export type PerformanceSettingsMap = {
	"local-google-fonts": boolean;
	"preload-local-fonts": boolean;
	"allow-only-selected-fonts": boolean;
	"allowed-fonts": Array<{
		label: string;
		value: string;
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

export type AssetGenerationSettingsMap = {
	"external-file": boolean;
};

export type VersionControlSettingsMap = {
	"beta-tester": boolean;
};

export type IntegrationsSettingsMap = {
	"google-map-embed-api-key": string;
};

export type MaintenanceModeSettingsMap = {
	mode: "none" | "maintenance" | "coming-soon";
	"maintenance-page": number;
};

export type SettingsMap = {
	blocks: Prettify<BlockSettingsMap>;
	editor: Prettify<EditorSettingsMap>;
	performance: Prettify<PerformanceSettingsMap>;
	"asset-generation": Prettify<AssetGenerationSettingsMap>;
	"version-control": Prettify<VersionControlSettingsMap>;
	integrations: Prettify<IntegrationsSettingsMap>;
	"maintenance-mode": Prettify<MaintenanceModeSettingsMap>;
};

export type ChangelogsMap = Array<{
	version: string;
	date: string;
	changes: {
		[key: string]: Array<string>;
	};
}>;

export type WPPagesResponse = Array<{
	id: number;
	title: {
		rendered: string;
	};
	[key: string]: unknown;
}>;

export type WPPluginsResponse = Array<{
	author: string;
	author_uri: string;
	description: {
		raw: string;
		rendered: string;
	};
	name: string;
	plugin: string;
	plugin_uri: string;
	requires_php: string;
	requires_wp: string;
	status: "active" | "inactive";
	textdomain: string;
	version: string;
	network_only: boolean;
	_link: {
		self: Array<{
			href: string;
		}>;
	};
}>;
