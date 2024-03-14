tinyMCEPopup.requireLangPack();
iHomefinderShortcodeSelector.onBuildShortcode = function(shortcode) {
	tinyMCEPopup.editor.execCommand("mceInsertContent", false, shortcode);
	tinyMCEPopup.close();
};
tinyMCEPopup.onInit.add(iHomefinderShortcodeSelector.init, iHomefinderShortcodeSelector);
