"use strict";
var __ = wp.i18n.__, 
    el = wp.element.createElement, 
    registerBlockType = wp.blocks.registerBlockType, 
    InspectorControls = wp.editor.InspectorControls,
    ServerSideRender = wp.components.ServerSideRender,
    Button = wp.components.Button,
    Dashicon = wp.components.Dashicon,
    IconButton = wp.components.IconButton,
    RichText = wp.editor.RichText,
    Editable = wp.blocks.Editable, 
    MediaUpload = wp.editor.MediaUpload,
    MediaUploadCheck = wp.editor.MediaUploadCheck,
    TextControl = wp.components.TextControl,
    SelectControl = wp.components.SelectControl,
    RadioControl = wp.components.RadioControl;

data.slider_ids = jQuery.parseJSON(data.slider_ids);
data.slider_names = jQuery.parseJSON(data.slider_names);

var available_sliders = [{ label: "", value: "" }];

for (var key in data.slider_ids) {
    available_sliders.push({ label: data.slider_names[key], value: data.slider_ids[key] });
}

var make_title_from_url = function(url) {
    var re = RegExp("/([^/]+?)(\\.pdf(\\?[^/]*)?)?$", "i");
    var matches = url.match(re);
    if (matches.length >= 2) {
        return matches[1];
    }
    return url;
};
registerBlockType(
    "transitionslider/embed", 
    {
        title: "Transition Slider", 
        description: "Premium Slider with advanced WebGL transitions and animated layers",

        icon: {
            src: "image-flip-horizontal"
        },

        category: "common", 
        attributes: {
            id: {
                type: "string"
            },
            name: {
                type: "string"
            }
        },

        edit: function(props) {
            var { attributes, setAttributes, focus, className } = props;

            var onSelectPDF = function(media) {
                return props.setAttributes({
                    pdf: media.url
                });
            };

            function onSelectImages(media) {}

            function onChangeWidth(v) {
                setAttributes({ width: v });
            }

            function onChangeHeight(v) {
                setAttributes({ height: v });
            }

            function onChangeMode(v) {
                setAttributes({ mode: v });
            }

            function onChangeId(v) {
                setAttributes({ id: v });
            }

            function onChangeToolbarfixed(v) {
                setAttributes({ toolbarfixed: v });
            }

            var attributes = props.attributes || "";
            var pdf = attributes.pdf || "";

            return [
                el("div", null, "Transitionslider"),
                el(SelectControl, {
                    label: "Select Slider",
                    value: attributes.id,
                    options: available_sliders,
                    onChange: onChangeId
                }),

                el(
                    InspectorControls,
                    { key: "inspector" }, 
                    el(
                        "div",
                        { className: "transitionslider" },
                        el("hr", {}),

                        el(SelectControl, {
                            label: "Select Slider",
                            value: attributes.id,
                            options: available_sliders,
                            onChange: onChangeId
                        })
                    )
                )
            ];
        },

        save: function save(props) {
            var attributes = props.attributes || "";
            attributes.id = attributes.id || "1";
            return '[transitionslider id="' + attributes.id + '"]';
        }
    }
);
