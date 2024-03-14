import {number, string} from "prop-types";

const attributes = {
  block_id: {
    type: "string",
  },
  imageUrl: {
    type: "string",
    default: "",
  },
  altText: {
    type: "string",
    default: "",
  },
  caption: {
    type: "boolean",
    default: false,
  },
  captionText: {
    type: "string",
    default: "",
  },
  sourceType: {
    type: "string",
    default: "",
  },
  imageAlignment: {
    type: "string",
    default: "left",
  },
  imageAlignmentTablet: {
    type: "string",
    default: "left",
  },
  imageAlignmentMobile: {
    type: "string",
    default: "left",
  },
  captionimageAlignment: {
    type: "string",
    default: "left",
  },
  captionimageAlignmentTablet: {
    type: "string",
    default: "left",
  },
  captionimageAlignmentMobile: {
    type: "string",
    default: "left",
  },
  captionFontFamily: {
    type: "string",
    default: ""
  },
  captionFontSize: {
    type: "number",
  },
  captionFontSizeMobile: {
    type: "number",
  },
  captionFontSizeTablet: {
    type: "number",
  },
  captionFontWeight: {
    type: "string",
    default: "600",
  },
  captionLineHeight: {
    type: "number",
    default: 1,
  },
  captionLetterSpacing: {
    type: "number",
    default: 0,
  },
  captionTextTransform: {
    type: "string",
  },
  captionColor: {
    type: "string",
  },
  imageBorderColor: {
    type: "string",
  },
  imageBorderRadius: {
    type: "number",
    default: 0,
  },
  imageBorderStyle: {
    type: "string",
    default: "none",
  },
  imageBorderWidth: {
    type: "number",
    default: 0,
  },
  imageboxShadowColor: {
    type: "string",
  },
  imageboxShadowHOffset: {
    type: "number",
    default: 0,
  },
  imageboxShadowVOffset: {
    type: "number",
    default: 0,
  },
  imageboxShadowBlur: {
    type: "number",
    default: 0,
  },
  imageboxShadowSpread: {
    type: "number",
    default: 0,
  },
  imageboxShadowPosition: {
    type: "string",
    default: "outset",
  },
  imageboxShadowHoverPosition: {
    type: "string",
    default: "outset",
  },
  imageboxShadowHoverColor: {
    type: "string",
  },
  imageboxShadowHoverHOffset: {
    type: "number",
    default: 0,
  },
  imageboxShadowHoverVOffset: {
    type: "number",
    default: 0,
  },
  imageboxShadowHoverBlur: {
    type: "number",
    default: 0,
  },
  imageboxShadowHoverSpread: {
    type: "number",
    default: 0,
  },
  imageboxshadowSeprateHover: {
    type: "boolean",
    default: false,
  },
  imagetopmargin: {
    type: "number",
    default: 0,
  },
  imagebottommargin: {
    type: "number",
    default: 0,
  },
  imageleftmargin: {
    type: "number",
    default: 0,
  },
  imagerightmargin: {
    type: "number",
    default: 0,
  },
  imagetopmarginTablet: {
    type: "number",
    default: 0,
  },
  imagebottommarginTablet: {
    type: "number",
    default: 0,
  },
  imageleftmarginTablet: {
    type: "number",
    default: 0,
  },
  imagerightmarginTablet: {
    type: "number",
    default: 0,
  },
  imagetopmarginMobile: {
    type: "number",
    default: 0,
  },
  imagebottommarginMobile: {
    type: "number",
    default: 0,
  },
  imageleftmarginMobile: {
    type: "number",
    default: 0,
  },
  imagerightmarginMobile: {
    type: "number",
    default: 0,
  },
  captiontopmargin: {
    type: "number",
    default: 0,
  },
  captionbottommargin: {
    type: "number",
    default: 0,
  },
  captionleftmargin: {
    type: "number",
    default: 0,
  },
  captionrightmargin: {
    type: "number",
    default: 0,
  },
  captiontopmarginTablet: {
    type: "number",
    default: 0,
  },
  captionbottommarginTablet: {
    type: "number",
    default: 0,
  },
  captionleftmarginTablet: {
    type: "number",
    default: 0,
  },
  captionrightmarginTablet: {
    type: "number",
    default: 0,
  },
  captiontopmarginMobile: {
    type: "number",
    default: 0,
  },
  captionbottommarginMobile: {
    type: "number",
    default: 0,
  },
  captionleftmarginMobile: {
    type: "number",
    default: 0,
  },
  captionrightmarginMobile: {
    type: "number",
    default: 0,
  },
  MaskShape: {
    type: "string",
    default:"none"
  },
  MaskSize:{
    type:"string"
  },
  MaskPosition:{
    type:"string"
  },
  MaskRepeat:{
    type:"string"
  },
  imageObjectFit:{
  type:"string",
  default:"default"
  },
  imageOnHoverImage:{
    type:"string",
    default:"static"
  },
  imageWidth:{
    type:"number"
  },
  imageHeight:{
    type:"number"
  },
  imageWidthTablet:{
    type:"number"
  },
  imageHeightTablet:{
    type:"number"
  },
  imageWidthMobile:{
    type:"number",
    default:0
  },
  imageHeightMobile:{
    type:"number",
    default:0
  },
  Layoverswitch:{
    type:"boolean",
    default:false
  },
  LayoverHeading:{
    type:"string",
    default:""
  },
  LayoverContentPosition:{
    type:"string",
    default:"centercenter"
  },
  layoverimageBorderColor: {
    type: "string",
  },
  layoverimageBorderRadius: {
    type: "number",
    default: 0,
  },
  layoverimageBorderStyle: {
    type: "string",
    default: "none",
  },
  layoverimageBorderWidth: {
    type: "number",
    default: 0,
  },
  layoverInputDistance: {
    type:"number",
    default:"15"
  },
  layoverBackgroundcolor:{
    type:"string",
    default:""
  },
  layoverOpacity:{
    type:"number",
    default:20
  },
  layoverHoverOpacity:{
    type:"number",
    default:100
  },
  layoverHeadingTag:{
    type:"string",
    default:"h2"
  },
  layoverHeadingFontFamily: {
    type: "string",
    default: "",
  },
  layoverHeadingFontSize: {
    type: "number",
  },
  layoverHeadingFontSizeMobile: {
    type: "number",
  },
  layoverHeadingFontSizeTablet: {
    type: "number",
  },
  layoverHeadingFontWeight: {
    type: "string",
    default: "600",
  },
  layoverHeadingLineHeight: {
    type: "number",
    default: 1,
  },
  layoverHeadingLetterSpacing: {
    type: "number",
    default: 0,
  },
  layoverHeadingTextTransform: {
    type: "string",
  },
  layoverHeadingColor: {
    type: "string",
  },
  layoverHeadingtopmargin: {
    type: "number",
    default: 0,
  },
  layoverHeadingbottommargin: {
    type: "number",
    default: 0,
  },
  layoverHeadingleftmargin: {
    type: "number",
    default: 0,
  },
  layoverHeadingrightmargin: {
    type: "number",
    default: 0,
  },
  layoverHeadingtopmarginTablet: {
    type: "number",
    default: 0,
  },
  layoverHeadingbottommarginTablet: {
    type: "number",
    default: 0,
  },
  layoverHeadingleftmarginTablet: {
    type: "number",
    default: 0,
  },
  layoverHeadingrightmarginTablet: {
    type: "number",
    default: 0,
  },
  layoverHeadingtopmarginMobile: {
    type: "number",
    default: 0,
  },
  layoverHeadingbottommarginMobile: {
    type: "number",
    default: 0,
  },
  layoverHeadingleftmarginMobile: {
    type: "number",
    default: 0,
  },
  layoverHeadingrightmarginMobile: {
    type: "number",
    default: 0,
  },
  
};

export default attributes;
