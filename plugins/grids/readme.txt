=== Grids: Layout builder for WordPress ===
Contributors: evolvesnc,andg,simo_m,marialaurascarnera
Tags: page builder, gutenberg, gutenberg blocks, blocks, block-editor, grid-layout
Requires at least: 5.0.0
Tested up to: 6.4.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Requires PHP: 5.6
Stable tag: 1.3.10

The most advanced page and layout builder for Gutenberg and the new Block Editor, with columns, rows and responsive controls.

== Description ==

👉 For more information about the plugin, visit the <a href="https://justevolve.it/grids/documentation/">documentation website</a>.

A layout builder is a tool that helps you creating visual structures in your page, from a simple layout made by adjacent columns, to more complex compositions. Grids is entirely based on the WordPress block editor, which means that you'll be able to use it together with the myriad of content blocks that developers and designers from all around the World are creating.

With Grids, we're bringing a visual structure to the content written with the WordPress Block Editor.

=== 👉 Sections & areas ===

A <strong>Section</strong> is a portion of the page that aims at being visually distinct from the rest of the content. Sections are usually top-level blocks that you add one after the other; the sequence of sections is what makes your page layout.

Each Section that you create is composed by several different <strong>Areas</strong>. In their most basic form, you can think of Areas as columns, which are nothing else than containers for content blocks.

For more elaborated compositions, the <strong>Grid Designer</strong> allows you to create exactly the grid structure you want within the boundaries of a Section, by using CSS Grid.

👉 <strong>Want to know more? Read the plugin documentation!</strong> 👉 <a href="https://justevolve.it/grids/documentation/">https://justevolve.it/grids/documentation/</a>

👉 <strong>Still struggling?</strong> That may depend on one of the known issues 👉 <a href="https://justevolve.it/grids/documentation/known-issues/">https://justevolve.it/grids/documentation/known-issues/</a>

😎 <strong>Watch Grids while it's shown on stage by Matt Mullenweg at WordCamp Europe 2019!</strong>
<iframe width="640" height="360" src="https://www.youtube.com/embed/UE18IsncB7s?start=13654&end=13675" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/grids` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress

== Frequently Asked Questions ==

**Will it work on my theme too?**

YES! Whether your theme is free or premium, Grids will seamlessly integrate with it.

**Can I switch themes? Will I lose content in the process?**

Your content will NOT be lost when switching themes, regardless of the one you choose to use. Also, since the plugin doesn't rely on shortcodes, even deactivating the Grids plugin completely will leave you with perfectly readable content in your pages.

**How does support work?**

Support on the plugin page on WordPress.org is completely voluntary. Feel free to post bug reports, or questions about the plugin functionality.

== Screenshots ==

1. The advanced Grid Designer.
2. Section layout template selection.
3. Grids allows you to be in control of the display of your pages.
4. Grids offers multiple advanced controls to modify the appearance of your content.

== Changelog ==

=== 👉 1.3.10 ===

* FIX: Compatibility issues with WordPress 6.4

=== 👉 1.3.9 ===

* FIX: Security fix.

=== 👉 1.3.8 ===

* FIX: Fixed display settings functionality for sections and areas.

=== 👉 1.3.7 ===

* FIX: Fixed a fallback variable issue for Sections and Areas background.

=== 👉 1.3.6 ===

* FIX: Fixed an issue with the margins, paddings association on different media queries.
* FIX: Fixed background repeat and position default behavior on frontend.

=== 👉 1.3.4 & 1.3.5 ===

* FIX: Synced row dimension logic on frontend/backend: empty rows will collapse also on the Block Editor screen.

=== 👉 1.3.3 ===

* FIX: Fixed 500 error that occurred when using wide-aligned sections.

=== 👉 1.3.2 ===

* FIX: Changed the grid column declaration to minmax(0,1fr) in order to prevent grid blowout.
* FIX: Fixed nested sections issues.

=== 👉 1.3.1 ===

* FIX: Reverted row dimension logic: in Sections created with the advanced editor, empty rows will collapse.

=== 👉 1.3.0 ===

* ENHANCEMENT: Added compatibility with the Full Site Editor.
* ENHANCEMENT: Enhanced compatibility with single page application websites.
* FIX: Fixed style being applied to nested sections.
* Updated compatibility to WordPress 5.9.

POSSIBLY BREAKING CHANGES:

* In this version, we have removed the global page setting that allowed for the editor area to be wider when editing a page with the Block Editor. We feel that this should be better handled by the theme itself, especially if it supports the Full Site Editing mode.
* We have also removed the global page setting that determined the horizontal gutter between areas. This is now handled individually by Section blocks (look for Gap settings). As before, both horizontal and vertical gaps are subject to media queries.
* We have removed the <code>grids-s-$section_id</code> and <code>grids-a-$area_id</code> classes from their respective elements, since they were no longer needed to generate the element's style. If you need to specifically identify a section or area, you can use their custom class/anchor controls provided by the Block Editor.

=== 👉 1.2.28 ===

* ENHANCEMENT: Added the ability to specify the types of blocks allowed in content areas of a section through the grids/area filter.
* ENHANCEMENT: Added the ability to specify the size units used in Grids, as well as the default size unit suggested by the editor, through the grids/general filter.
* FIX: Removed unwanted log to console.
* Updated compatibility to WordPress 5.7.

=== 👉 1.2.27 ===

* FIX: Fixed an issue that occurred when editing custom-created sections and affected previously created areas.
* FIX: Fixed an issue that prevented post meta to be saved when using Grids with a Custom Post Type.

=== 👉 1.2.26 ===

* FIX: Added compatibility with WordPress 5.6 and the latest version of the Gutenberg plugin.

=== 👉 1.2.25 ===

* FIX: Minor backend UI fix.
* FIX: Fixed the dimensioning of blocks in content areas on frontend.

=== 👉 1.2.24 ===

* Enhanced support for AJAX-powered content pages.

=== 👉 1.2.23 ===

* FIX: Fixed React warnings and errors that occurred when the SCRIPT_DEBUG constant was set to true.
* FIX: Fixed section styling when in preview mode.
* FIX: Removed double scrollbar when dragging areas.

=== 👉 1.2.22 ===

* FIX: Increased compatibility with WordPress 5.4.

=== 👉 1.2.21 ===

* FIX: Fixed a bug that caused section IDs not to be rendered in the page markup.

=== 👉 1.2.20 ===

* FIX: Fixed compatibility with Gutenberg 7.3.

=== 👉 1.2.19 ===

* FIX: Fixed blocks toolbar issue.

=== 👉 1.2.18 ===

* FIX: Fixed reusable block functionality on Sections.
* FIX: Various minor UI tweaks.
* FIX: Minor bugs.

=== 👉 1.2.17 ===

* FIX: Fixed compatibility with Gutenberg 7.2.
* FIX: Minor UI tweaks.

=== 👉 1.2.16 ===

* FIX: Fixed grid display issues on Internet Explorer 11. Thanks to <a href="https://profiles.wordpress.org/amddtim/">@amddtim</a> for providing the fixes.

=== 👉 1.2.15 ===

* Improved the visualization of units in spacing controls.
* Added an option to expand the editor width.

=== 👉 1.2.14 ===

* FIX: Fixed compatibility with WordPress 5.3.

=== 👉 1.2.13 ===

* FIX: Fixed a z-index issue for the area selection highlight.
* FIX: Fixed an issue that prevented the correct display of duplicated sections.

=== 👉 1.2.12 ===

* FIX: Improved the editing of blocks in two vertical adjacent areas in an advanced grid.
* FIX: Fixed an area width display issue in the block preview.

=== 👉 1.2.11 ===

* Added visual feedback to elements when using dimension controls.
* FIX: Fixed a regression that prevented the correct use of vertical align in Areas.

=== 👉 1.2.10 ===

* FIX: Fixed a bug that prevented Sections to be saved correctly when they have an anchor specified.
* FIX: Minor UI tweaks.

=== 👉 1.2.9 ===

* Added anchor support to Sections: you can now add IDs to Section elements on frontend.
* Various UI tweaks to highlight the grid structure and rendering.
* Removed the Section block from the Grids section, and made available in the Layout blocks section.
* Changed the Section and Area block icons to match the plugin brand.
* FIX: First block selection after inserting a Section in page.
* FIX: Minor bugs.

=== 👉 1.2.8 ===

* FIX: Style alignment for areas when duplicating sections in the Block Editor.

=== 👉 1.2.7 ===

* FIX: Restore block insertion mode for newly created areas when using the Gutenberg plugin.
* FIX: Compatibility fixes with Gutenberg 5.9.0.

=== 👉 1.2.6 ===

* FIX: Behavior of content areas when adding lateral spacing.

=== 👉 1.2.5 ===

* Added a new "Stretch" option for backgrounds on sections with "wide" alignment.
* FIX: Minor bugs.

=== 👉 1.2.4 ===

* Submitted Grids to the Blocks directory on WordPress.org.
* Include JS and SCSS source files.

=== 👉 1.2.3 ===

* FIX: Fixed an issue that prevented to properly visualize the Block Inserter when the `z-index` of an area had been tweaked.
* FIX: Fixed an issue that could cause multiple areas to be added to a section, without using the Grid Designer component.
* FIX: Minor bug fixes.

=== 👉 1.2.2 ===

* FIX: Fixed an issue that prevented custom accent colors to work as intended on the Twenty Nineteen default theme.

=== 👉 1.2.1 ===

* FIX: Minor visual tweaks.
* FIX: Editing of sections composed with the Grid Designer.

=== 👉 1.2 ===

* NEW FEATURE: Added page-wide gutter control.
* ENHANCEMENT: Grid Designer is now available when editing a section, and areas can be rearranged.
* FIX: CSS style for grids not displaying in loops.
* FIX: Sections can now be used as reusable blocks.
* FIX: Minor bugs.

=== 👉 1.1.1 ===

* FIX: Translation support.
* FIX: Issue with blocks toolbar.

=== 👉 1.1.0 ===

* NEW FEATURE: Grid Designer.
* FIX: Translation support.
* FIX: Minor bugs.

=== 👉 1.0.0 ===

* Initial public release.

== Upgrade Notice ==
