/**
 * Inspector Controls
 */

import times from "lodash/times";
import FontIconPicker from "@fonticonpicker/react-fonticonpicker";
import renderSVG from "../../../renderIcon";
import ResponsiveBlocksIcon from "../../../ResponsiveBlocksIcon.json";
import fontOptions from "../../../utils/googlefonts";
import { loadGoogleFont } from "../../../utils/font";
import InspectorTab from "../../../components/InspectorTab";
import InspectorTabs from "../../../components/InspectorTabs";
import BlockBorderHelperControl from "../../../settings-components/BlockBorderSettings";
import TypographyHelperControl from "../../../settings-components/TypographySettings";
import ResponsiveSpacingControl from "../../../settings-components/ResponsiveSpacingSettings";

// Setup the block
const { __ } = wp.i18n;
const { Component, Fragment } = wp.element;

// Import block components
const {
  InspectorControls,
  PanelColorSettings,
  AlignmentToolbar,
  ColorPalette,
} = wp.blockEditor;

// Import Inspector components
const {
  PanelBody,
  RangeControl,
  SelectControl,
  BaseControl,
  ToggleControl,
  Dashicon,
  Button,
  TabPanel,
} = wp.components;

let svg_icons = Object.keys(ResponsiveBlocksIcon);

/**
 * Create an Inspector Controls wrapper Component
 */
export default class Inspector extends Component {
  constructor(props) {
    super(...arguments);
  }

  render() {
    // Setup the attributes
    const {
      attributes: {
        count,
        gutter,
        countUp,
        textColor,
        contentAlign,
        itemBackgroundColor,
        dateFontFamily,
        dateLineHeight,
        dateFontWeight,
        dateFontSize,
        dateFontSizeMobile,
        dateFontSizeTablet,
        headingLineHeight,
        headingFontFamily,
        headingFontSize,
		headingFontWeight,
        headingFontSizeMobile,
        headingFontSizeTablet,
        contentFontFamily,
        contentLineHeight,
        contentFontWeight,
        contentFontSize,
        contentFontSizeMobile,
        contentFontSizeTablet,
        icon,
        resshowIcon,
        resshowTitle,
        resshowDesc,
        blockBorderStyle,
        blockBorderWidth,
        blockBorderRadius,
        blockBorderColor,
        opacity,
        icon_color,
        iconsize,
        numColor,
        titleColor,
        titleSpace,
        numSpace,
        contentSpace,
        resshowNum,
        iconStyle,
        iconShapeColor,
        shapeBorderRadius,
        shapePadding,
        shapeBorder,
        contentSpacing,
        iconSpacing,
		contentSpacingTablet,
		contentSpacingMobile,
		iconSpacingTablet,
		iconSpacingMobile,
		titleSpaceTablet,
		titleSpaceMobile,
		numSpaceTablet,
		numSpaceMobile,
		contentSpaceTablet,
		contentSpaceMobile,
      },
      setAttributes,
    } = this.props;

    var data_copy = [...countUp];

    // Font Weight Options
    const fontWeightOptions = [
      {
        value: "100",
        label: __("100", "responsive-block-editor-addons"),
      },
      {
        value: "200",
        label: __("200", "responsive-block-editor-addons"),
      },
      {
        value: "300",
        label: __("300", "responsive-block-editor-addons"),
      },
      {
        value: "400",
        label: __("400", "responsive-block-editor-addons"),
      },
      {
        value: "500",
        label: __("500", "responsive-block-editor-addons"),
      },
      {
        value: "600",
        label: __("600", "responsive-block-editor-addons"),
      },
      {
        value: "700",
        label: __("700", "responsive-block-editor-addons"),
      },
      {
        value: "800",
        label: __("800", "responsive-block-editor-addons"),
      },
      {
        value: "900",
        label: __("900", "responsive-block-editor-addons"),
      },
    ];

    // Update color value
    const onChangeTextColor = (value) => setAttributes({ textColor: value });
    const onChangeBackgroundColor = (value) =>
      setAttributes({ itemBackgroundColor: value });
    const onChangeTitleColor = (value) => setAttributes({ titleColor: value });
    const onChangeNumColor = (value) => setAttributes({ numColor: value });

    const frontIconControls = (index) => {
      const icons = [
        "menu",
        "admin-site",
        "dashboard",
        "admin-post",
        "admin-media",
        "admin-links",
        "admin-page",
        "admin-comments",
        "admin-appearance",
        "admin-plugins",
        "admin-users",
        "admin-tools",
        "admin-settings",
        "admin-network",
        "admin-home",
        "admin-generic",
        "admin-collapse",
        "welcome-write-blog",
        "welcome-add-page",
        "welcome-view-site",
        "welcome-widgets-menus",
        "welcome-comments",
        "welcome-learn-more",
        "format-aside",
        "format-image",
        "format-gallery",
        "format-video",
        "format-status",
        "format-quote",
        "format-chat",
        "format-audio",
        "camera",
        "images-alt",
        "images-alt2",
        "video-alt",
        "video-alt2",
        "video-alt3",
        "image-crop",
        "image-rotate-left",
        "image-rotate-right",
        "image-flip-vertical",
        "image-flip-horizontal",
        "undo",
        "redo",
        "editor-bold",
        "editor-italic",
        "editor-ul",
        "editor-ol",
        "editor-quote",
        "editor-alignleft",
        "editor-aligncenter",
        "editor-alignright",
        "editor-insertmore",
        "editor-spellcheck",
        "editor-distractionfree",
        "editor-kitchensink",
        "editor-underline",
        "editor-justify",
        "editor-textcolor",
        "editor-paste-word",
        "editor-paste-text",
        "editor-removeformatting",
        "editor-video",
        "editor-customchar",
        "editor-outdent",
        "editor-indent",
        "editor-help",
        "editor-strikethrough",
        "editor-unlink",
        "editor-rtl",
        "align-left",
        "align-right",
        "align-center",
        "align-none",
        "lock",
        "calendar",
        "visibility",
        "post-status",
        "edit",
        "trash",
        "arrow-up",
        "arrow-down",
        "arrow-right",
        "arrow-left",
        "arrow-up-alt",
        "arrow-down-alt",
        "arrow-right-alt",
        "arrow-left-alt",
        "arrow-up-alt2",
        "arrow-down-alt2",
        "arrow-right-alt2",
        "arrow-left-alt2",
        "sort",
        "leftright",
        "list-view",
        "exerpt-view",
        "share",
        "share-alt",
        "share-alt2",
        "twitter",
        "rss",
        "facebook",
        "facebook-alt",
        "googleplus",
        "networking",
        "hammer",
        "art",
        "migrate",
        "performance",
        "wordpress",
        "wordpress-alt",
        "pressthis",
        "update",
        "screenoptions",
        "info",
        "cart",
        "feedback",
        "cloud",
        "translation",
        "tag",
        "category",
        "yes",
        "no",
        "no-alt",
        "plus",
        "minus",
        "dismiss",
        "marker",
        "star-filled",
        "star-half",
        "star-empty",
        "flag",
        "location",
        "location-alt",
        "vault",
        "shield",
        "shield-alt",
        "search",
        "slides",
        "analytics",
        "chart-pie",
        "chart-bar",
        "chart-line",
        "chart-area",
        "groups",
        "businessman",
        "id",
        "id-alt",
        "products",
        "awards",
        "forms",
        "portfolio",
        "book",
        "book-alt",
        "download",
        "upload",
        "backup",
        "lightbulb",
        "smiley",
      ];

      return (
        <PanelBody
          key={index}
          title={
            __("CountUp Box ", "responsive-block-editor-addons") +
            " " +
            (index + 1)
          }
          initialOpen={false}
          className={"rbea-repeater-panel"}
        >
          <FontIconPicker
            icons={svg_icons}
            renderFunc={renderSVG}
            theme="default"
            value={countUp[index]["icon"]}
            onChange={(value) => {
              var new_content = {
                icon: value,
                title: data_copy[index]["title"],
                amount: data_copy[index]["amount"],
                features: data_copy[index]["features"],
              };
              data_copy[index] = new_content;
              setAttributes({ countUp: data_copy });
            }}
            isMulti={false}
            noSelectedPlaceholder={__(
              "Select Icon",
              "responsive-block-editor-addons"
            )}
          />
        </PanelBody>
      );
    };

    return (
      <InspectorControls key="inspector">
        <InspectorTabs>
          <InspectorTab key={"content"}>
            <PanelBody
              title={__("General", "responsive-block-editor-addons")}
              initialOpen={false}
            >
              <BaseControl>
                <BaseControl.VisualLabel>
                  {__("Alignment", "responsive-block-editor-addons")}
                </BaseControl.VisualLabel>
                <AlignmentToolbar
                  value={contentAlign}
                  onChange={(value) =>
                    setAttributes({
                      contentAlign: value,
                    })
                  }
                  controls={["left", "center", "right"]}
                  isCollapsed={false}
                />
              </BaseControl>

              <RangeControl
                label={__("Columns", "responsive-block-editor-addons")}
                value={count}
                onChange={(newCount) => {
                  let cloneTest_block = [...countUp];
                  if (cloneTest_block.length < newCount) {
                    const incAmount = Math.abs(
                      newCount - cloneTest_block.length
                    );

                    {
                      times(incAmount, (n) => {
                        cloneTest_block.push({
                          icon: "lightbulb",
                          title: "Title " + newCount,
                          amount: "1,234",
                          features: "Description",
                        });
                      });
                    }
                    setAttributes({ countUp: cloneTest_block });
                  } else {
                    const incAmount = Math.abs(
                      newCount - cloneTest_block.length
                    );
                    let data_new = cloneTest_block;
                    for (var i = 0; i < incAmount; i++) {
                      data_new.pop();
                    }
                    setAttributes({ countUp: data_new });
                  }
                  setAttributes({ count: newCount });
                }}
                min={1}
                max={4}
                step={1}
              />
            </PanelBody>

            <PanelBody
              title={__("Content", "responsive-block-editor-addons")}
              initialOpen={false}
            >
              <ToggleControl
                label={__("Enable Title", "responsive-block-editor-addons")}
                checked={resshowTitle}
                onChange={(value) =>
                  setAttributes({ resshowTitle: !resshowTitle })
                }
              />
              <ToggleControl
                label={__("Enable Number", "responsive-block-editor-addons")}
                checked={resshowNum}
                onChange={(value) => setAttributes({ resshowNum: !resshowNum })}
              />
              <ToggleControl
                label={__(
                  "Enable Description",
                  "responsive-block-editor-addons"
                )}
                checked={resshowDesc}
                onChange={(value) =>
                  setAttributes({ resshowDesc: !resshowDesc })
                }
              />
              <ToggleControl
                label={__("Enable Icon", "responsive-block-editor-addons")}
                checked={resshowIcon}
                onChange={(value) =>
                  setAttributes({ resshowIcon: !resshowIcon })
                }
              />
            </PanelBody>
          </InspectorTab>
          <InspectorTab key={"style"}>
            {resshowIcon && (
              <PanelBody
                title={__("Icon", "responsive-block-editor-addons")}
                initialOpen={true}
              >
                <Fragment>
                  <p className="components-base-control__label">
                    {__("Select Icon", "responsive-block-editor-addons")}
                  </p>
                  {times(count, (n) => frontIconControls(n))}
                </Fragment>
                <RangeControl
                  label={__("Icon Size", "responsive-block-editor-addons")}
                  value={iconsize}
                  onChange={(value) =>
                    setAttributes({
                      iconsize: value !== undefined ? value : 16,
                    })
                  }
                  min={0}
                  max={300}
                  allowReset
                />
                <p className="responsive-block-editor-addons-setting-label">
                  {__("Icon Color", "responsive-block-editor-addons")}
                  <span className="components-base-control__label">
                    <span
                      className="component-color-indicator"
                      style={{ backgroundColor: icon_color }}
                    ></span>
                  </span>
                </p>
                <ColorPalette
                  value={icon_color}
                  onChange={(value) => setAttributes({ icon_color: value })}
                  allowReset
                />
                <SelectControl
                  label={__("Design", "responsive-block-editor-addons")}
                  value={iconStyle}
                  onChange={(value) => setAttributes({ iconStyle: value })}
                  options={[
                    { value: "none", label: __("Plain", "responsive-block-editor-addons") },
                    { value: "shaped", label: __("Shaped", "responsive-block-editor-addons") },
                    { value: "outline", label: __("Outline", "responsive-block-editor-addons") },
                  ]}
                />
                {"none" != iconStyle && (
                  <Fragment>
                    <p className="responsive-block-editor-addons-setting-label">
                      {__(
                        "Icon Shape / Outline Color",
                        "responsive-block-editor-addons"
                      )}
                      <span className="components-base-control__label">
                        <span
                          className="component-color-indicator"
                          style={{ backgroundColor: iconShapeColor }}
                        ></span>
                      </span>
                    </p>
                    <ColorPalette
                      value={iconShapeColor}
                      onChange={(value) =>
                        setAttributes({ iconShapeColor: value })
                      }
                      allowReset
                    />
                    <RangeControl
                      label={__("Shape / Outline Border Radius", "responsive-block-editor-addons")}
                      value={shapeBorderRadius}
                      onChange={(value) =>
                        setAttributes({ shapeBorderRadius: value })
                      }
                      min={0}
                      max={50}
                    />
                    <RangeControl
                      label={__("Shape / Outline Padding", "responsive-block-editor-addons")}
                      value={shapePadding}
                      onChange={(value) =>
                        setAttributes({ shapePadding: value })
                      }
                      min={0}
                      max={100}
                    />
                    <RangeControl
                      label={__("Outline Width", "responsive-block-editor-addons")}
                      value={shapeBorder}
                      onChange={(value) =>
                        setAttributes({ shapeBorder: value })
                      }
                      min={0}
                      max={9}
                    />
                  </Fragment>
                )}
              </PanelBody>
            )}

            <PanelBody
              title={__("Typography", "responsive-block-editor-addons")}
              initialOpen={false}
            >
				<TypographyHelperControl
					title={__("Number Typography", "responsive-block-editor-addons")}
					attrNameTemplate="date%s"
					values = {{
					family: dateFontFamily,
					size: dateFontSize,
					sizeMobile: dateFontSizeMobile,
					sizeTablet: dateFontSizeTablet,
					weight: dateFontWeight,
					height: dateLineHeight,
					}}
					showLetterSpacing = { false }
					showTextTransform = { false }
					setAttributes={ setAttributes }
					{...this.props}
				/>
				<TypographyHelperControl
					title={__("Heading Typography", "responsive-block-editor-addons")}
					attrNameTemplate="heading%s"
					values = {{
					family: headingFontFamily,
					size: headingFontSize,
					sizeMobile: headingFontSizeMobile,
					sizeTablet: headingFontSizeTablet,
					weight: headingFontWeight,
					height: headingLineHeight,
					}}
					showLetterSpacing = { false }
					showTextTransform = { false }
					setAttributes={ setAttributes }
					{...this.props}
				/>
				<TypographyHelperControl
					title={__("Description Typography", "responsive-block-editor-addons")}
					attrNameTemplate="content%s"
					values = {{
					family: contentFontFamily,
					size: contentFontSize,
					sizeMobile: contentFontSizeMobile,
					sizeTablet: contentFontSizeTablet,
					weight: contentFontWeight,
					height: contentLineHeight,
					}}
					showLetterSpacing = { false }
					showTextTransform = { false }
					setAttributes={ setAttributes }
					{...this.props}
				/>
            </PanelBody>

            <PanelBody
              title={__("Spacing", "responsive-block-editor-addons")}
              initialOpen={false}
            >
				<ResponsiveSpacingControl
					title={"Content Padding"}
					attrNameTemplate="contentSpacing%s"
					values={{
						desktop: contentSpacing,
						tablet: contentSpacingTablet,
						mobile: contentSpacingMobile,
					}}
					setAttributes={setAttributes}
					{...this.props}
				/>
				<ResponsiveSpacingControl
					title={"Icon Bottom Spacing"}
					attrNameTemplate="iconSpacing%s"
					values={{
						desktop: iconSpacing,
						tablet: iconSpacingTablet,
						mobile: iconSpacingMobile,
					}}
					setAttributes={setAttributes}
					{...this.props}
				/>
				<ResponsiveSpacingControl
					title={"Title Bottom Margin"}
					attrNameTemplate="titleSpace%s"
					values={{
						desktop: titleSpace,
						tablet: titleSpaceTablet,
						mobile: titleSpaceMobile,
					}}
					setAttributes={setAttributes}
					{...this.props}
				/>
				<ResponsiveSpacingControl
					title={"Number Bottom Margin"}
					attrNameTemplate="numSpace%s"
					values={{
						desktop: numSpace,
						tablet: numSpaceTablet,
						mobile: numSpaceMobile,
					}}
					setAttributes={setAttributes}
					{...this.props}
				/>
				<ResponsiveSpacingControl
					title={"Description Bottom Margin"}
					attrNameTemplate="contentSpace%s"
					values={{
						desktop: contentSpace,
						tablet: contentSpaceTablet,
						mobile: contentSpaceMobile,
					}}
					setAttributes={setAttributes}
					{...this.props}
				/>
            </PanelBody>

            <PanelColorSettings
              title={__("Color Settings", "responsive-block-editor-addons")}
              initialOpen={false}
              colorSettings={[
                {
                  value: titleColor,
                  onChange: onChangeTitleColor,
                  label: __("Title Color", "responsive-block-editor-addons"),
                },
                {
                  value: numColor,
                  onChange: onChangeNumColor,
                  label: __("Number Color", "responsive-block-editor-addons"),
                },
                {
                  value: textColor,
                  onChange: onChangeTextColor,
                  label: __("Text Color", "responsive-block-editor-addons"),
                },
                {
                  value: itemBackgroundColor,
                  onChange: onChangeBackgroundColor,
                  label: __(
                    "Background Color",
                    "responsive-block-editor-addons"
                  ),
                },
              ]}
            >
              <RangeControl
                label={__("Opacity", "responsive-block-editor-addons")}
                value={opacity}
                onChange={(value) =>
                  setAttributes({ opacity: value !== undefined ? value : 10 })
                }
                min={0}
                max={100}
                allowReset
              />
            </PanelColorSettings>
            <PanelBody
              title={__("Border", "responsive-block-editor-addons")}
              initialOpen={false}
            >
                <BlockBorderHelperControl
                    attrNameTemplate="block%s"
                    values = {{radius: blockBorderRadius, style: blockBorderStyle, width: blockBorderWidth, color: blockBorderColor}}
                    setAttributes={ setAttributes }
                    {...this.props}
                />
            </PanelBody>
          </InspectorTab>
          <InspectorTab key={"advance"}></InspectorTab>
        </InspectorTabs>
      </InspectorControls>
    );
  }
}
