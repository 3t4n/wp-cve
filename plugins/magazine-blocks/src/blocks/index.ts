import { registerBlocks } from "./blocks";
import "./editor.scss";
import {
	addPluginSidebar,
	addShortcutSidebar,
	autoRecoverBlocks,
	disableBlocks,
	setSectionDefaultWidth,
	updateBlockAttributes,
	updateBlockCategoryIcon,
} from "./helpers";
import { GlobalStylesProcessor } from "./hooks/useGlobalStyles";
import "./store/styles-store";
import { localized } from "./utils";
new GlobalStylesProcessor(localized.configs["global-styles"]).process();

addShortcutSidebar();
// disable blocks.
disableBlocks();

setSectionDefaultWidth();

// Register blocks.
registerBlocks();

updateBlockAttributes(); // update old attributes.
updateBlockCategoryIcon(); // Update Magazine Blocks category icon.
autoRecoverBlocks(); // Auto recover blocks in widget/customize screen.
addPluginSidebar();
new GlobalStylesProcessor(localized.configs["global-styles"]).process();
