/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ "./src/blocks/edit.js":
/*!****************************!*\
  !*** ./src/blocks/edit.js ***!
  \****************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _assets_edit_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../assets/edit.scss */ "./src/assets/edit.scss");
/* harmony import */ var _slider__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./slider */ "./src/blocks/slider.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_3__);




function EditTomSIamgeSlider(tomsProps) {
  /*console.log(tomsProps.props)*/
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "toms-image-slider"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_slider__WEBPACK_IMPORTED_MODULE_2__["default"], {
    props: tomsProps.props
  })));
}
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (EditTomSIamgeSlider);

/***/ }),

/***/ "./src/blocks/slider.js":
/*!******************************!*\
  !*** ./src/blocks/slider.js ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
/* harmony export */ __webpack_require__.d(__webpack_exports__, {
/* harmony export */   "default": () => (__WEBPACK_DEFAULT_EXPORT__)
/* harmony export */ });
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _assets_slider_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../assets/slider.scss */ "./src/assets/slider.scss");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_5__);






function TomSIamgeSlider(slider) {
  function onChangeImageSlider(newValue) {
    slider.props.setAttributes({
      imageSlider: newValue
    });
  }
  function onChangeAutoPlay(newValue) {
    slider.props.setAttributes({
      autoPlay: newValue
    });
  }
  function onChangeThumnbail(newValue) {
    slider.props.setAttributes({
      thumnbail: newValue
    });
  }
  function onChangeSliderModal(newValue) {
    slider.props.setAttributes({
      sliderModal: newValue
    });
  }
  function onChangeEditorSliderModal(newValue) {
    slider.props.setAttributes({
      editorSliderModal: newValue
    });
  }
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_5__.InspectorControls, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.PanelBody, {
    title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Slider Settings", "toms-image-slider"),
    initialOpen: true
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Image Slider ", "toms-image-slider"),
    help: slider.props.attributes.imageSlider ? '' : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Disable this Image Slider if you do not want to show the slideshow to the frontend.", "toms-image-slider"),
    checked: slider.props.attributes.imageSlider,
    onChange: onChangeImageSlider
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Auto Play ", "toms-image-slider"),
    help: slider.props.attributes.autoPlay ? '' : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Disabled Auto Play", "toms-image-slider"),
    checked: slider.props.attributes.autoPlay,
    onChange: onChangeAutoPlay
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Thumnbail ", "toms-image-slider"),
    help: slider.props.attributes.thumnbail ? '' : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Disabled Thumnbail", "toms-image-slider"),
    checked: slider.props.attributes.thumnbail,
    onChange: onChangeThumnbail
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Modal ", "toms-image-slider"),
    help: slider.props.attributes.sliderModal ? '' : (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Disabled Modal", "toms-image-slider"),
    checked: slider.props.attributes.sliderModal,
    onChange: onChangeSliderModal
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.PanelRow, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.ToggleControl, {
    label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Modal preview in Editor", "toms-image-slider"),
    help: slider.props.attributes.editorSliderModal ? (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Enabled Modal preview in current Editor, to make this option work you need to enable Modal first.", "toms-image-slider") : '',
    checked: slider.props.attributes.editorSliderModal,
    onChange: onChangeEditorSliderModal
  })))), slider.props.attributes.imageSlider !== false ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(TomsImageSliderPreview, slider) /*调用自定义component*/ : (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    style: {
      width: '280px',
      height: '280px',
      backgroundColor: '#e5ffd5',
      fontSize: '20px',
      fontWeight: 'bold',
      padding: '10px',
      display: 'flex',
      justifyContent: 'center',
      alignItems: 'center',
      flexDirection: 'column',
      textAlign: 'center'
    }
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("p", null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    t: "1633403139510",
    className: "toms-disabled-icon",
    viewBox: "0 0 1024 1024",
    version: "1.1",
    xmlns: "http://www.w3.org/2000/svg",
    "p-id": "13033",
    width: "80",
    height: "80"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M511.175727 162.355715c-192.325283 0-349.669868 157.343561-349.669868 349.669868 0 192.276165 157.344584 349.619726 349.669868 349.619726s349.669868-157.343561 349.669868-349.619726C860.845595 319.699276 703.502034 162.355715 511.175727 162.355715M231.470327 512.024559c0-153.886833 125.890198-279.758612 279.7054-279.758612 62.945611 0 122.361839 20.974707 171.375136 59.46637l-391.670447 391.594723C252.46857 634.386398 231.470327 574.947657 231.470327 512.024559M511.175727 791.729959c-62.952774 0-122.360815-20.973684-171.329087-59.438741l391.617236-391.617236c38.465057 48.940642 59.416228 108.407012 59.416228 171.3516C790.881127 665.862273 665.036977 791.729959 511.175727 791.729959",
    "p-id": "13034",
    fill: "#d81e06"
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    t: "1633403574862",
    className: "toms-unvisible-icon",
    viewBox: "0 0 1024 1024",
    version: "1.1",
    xmlns: "http://www.w3.org/2000/svg",
    "p-id": "31920",
    width: "80",
    height: "80"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M930.9 456.1c-28.7-31.3-75.8-77.9-137.2-121.7-9.9-7.1-23.6-4.8-30.7 5.1-7.1 9.9-4.8 23.6 5.1 30.7 61.4 43.8 108 91.3 130.4 115.7 4.8 5.2 4.8 12.9 0 18.1-26.6 29-81.9 84.6-154.5 132.2-86.5 56.7-171.4 85.4-252.4 85.4-29.5 0-60-3.9-90.5-11.6-11.8-2.9-23.7 4.2-26.7 16-2.9 11.8 4.2 23.7 16 26.7 34.1 8.6 68.1 12.9 101.2 12.9 89.7 0 182.7-31.1 276.5-92.7 76.6-50.2 134.9-108.7 162.8-139.2 20.3-22.1 20.3-55.5 0-77.6zM870.7 115.9c-8.6-8.6-22.5-8.6-31.1 0L685 270.5c-65.6-30.6-130.7-46.1-193.4-46.1-89.7 0-182.7 31.2-276.5 92.6-76.6 50.2-134.9 108.7-162.8 139.2-20.3 22.1-20.3 55.5 0 77.6C88 572.7 161.2 644.9 257 698.5L112.4 843.1c-8.6 8.6-8.6 22.5 0 31.1 4.3 4.3 10 6.4 15.6 6.4s11.2-2.1 15.5-6.4L870.7 147c8.6-8.6 8.6-22.5 0-31.1zM425 317.5c41.7 0 75.5 33.8 75.5 75.5s-33.8 75.5-75.5 75.5-75.5-33.8-75.5-75.5c0-41.8 33.8-75.5 75.5-75.5z m198.9-24.8c9.3 3.3 18.6 7 27.9 11l-7.5 7.5c-6.4-6.6-13.2-12.7-20.4-18.5zM287.3 664.9C192.6 614 119.8 542.3 84.8 504.1c-4.8-5.2-4.8-12.9 0-18.1 26.5-29 81.8-84.6 154.4-132.2 40.3-26.4 80.2-46.7 119.6-60.9-49 39-80.4 99.2-80.4 166.7 0.1 59.9 25 114 64.8 152.7l-53.9 53.9c-0.6-0.5-1.3-0.9-2-1.3z",
    "p-id": "31921",
    fill: "#bfbfbf"
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M491.6 672.6c117.7 0 213.1-95.4 213.1-213.1 0-18.3-2.5-35.9-6.9-52.8l-259 259c16.9 4.3 34.5 6.9 52.8 6.9z",
    "p-id": "31922",
    fill: "#bfbfbf"
  }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", null, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("This Block has been", "toms-image-slider"), " ", (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    style: {
      color: '#ff0000',
      fontSize: '30px'
    }
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Disabled", "toms-image-slider")), " !")));
}
const TomsImageSliderPreview = slides => {
  /* 自定义component */
  var defaultImageFromPHP = tomsSlieshowDefaultFromPHP.defaultImage;
  const {
    isSelected
  } = slides.props;
  const [current, setCurrent] = (0,react__WEBPACK_IMPORTED_MODULE_2__.useState)(0);
  const [currentImg, setCurrentImg] = (0,react__WEBPACK_IMPORTED_MODULE_2__.useState)(slides.props.attributes.galleryImages[0]);
  const length = slides.props.attributes.galleryImages.length;
  const slideImg = slides.props.attributes.galleryImages;
  const nextSlide = () => {
    setCurrent(current === length - 1 ? 0 : current + 1), setCurrentImg(slideImg[current === length - 1 ? 0 : current + 1]);
    isOpen && setModalImg(slideImg[current === length - 1 ? 0 : current + 1]);
  };
  const prevSlide = () => {
    setCurrent(current === 0 ? length - 1 : current - 1), setCurrentImg(slideImg[current === 0 ? length - 1 : current - 1]);
    isOpen && setModalImg(slideImg[current === 0 ? length - 1 : current - 1]);
  };

  //自动滑动 autoplay
  const timeoutRef = (0,react__WEBPACK_IMPORTED_MODULE_2__.useRef)(null);
  function resetTimeout() {
    /*清空计时函数*/
    if (timeoutRef.current) {
      clearTimeout(timeoutRef.current);
    }
  }
  (0,react__WEBPACK_IMPORTED_MODULE_2__.useEffect)(() => {
    if (slides.props.attributes.autoPlay !== false && length >= 1) {
      resetTimeout(); /*清空计时*/

      timeoutRef.current = setTimeout(() => {
        setCurrent(current === length - 1 ? 0 : current + 1), setCurrentImg(slideImg[current === length - 1 ? 0 : current + 1]);
      }, 3600);
      return () => {
        resetTimeout(); /*返回清空计时*/
      };
    }
  }, [current]);

  //图片弹窗
  const [isOpen, setOpen] = (0,react__WEBPACK_IMPORTED_MODULE_2__.useState)(false);
  const openModal = () => slides.props.attributes.sliderModal !== false && slides.props.attributes.editorSliderModal === true && setOpen(true);
  const closeModal = () => setOpen(false);
  const [modalCurrent, setModalCurrent] = (0,react__WEBPACK_IMPORTED_MODULE_2__.useState)(0);
  const [modalImg, setModalImg] = (0,react__WEBPACK_IMPORTED_MODULE_2__.useState)(slides.props.attributes.galleryImages[0]);
  const modalNextSlide = () => {
    setModalCurrent(modalCurrent === length - 1 ? 0 : modalCurrent + 1);
    setModalImg(slideImg[modalCurrent === length - 1 ? 0 : modalCurrent + 1]);
  };
  const modalPrevSlide = () => {
    setModalCurrent(modalCurrent === 0 ? length - 1 : modalCurrent - 1);
    setModalImg(slideImg[modalCurrent === 0 ? length - 1 : modalCurrent - 1]);
  };

  //控制显示缩略图
  const [showState, setShowState] = (0,react__WEBPACK_IMPORTED_MODULE_2__.useState)({
    shown: true
  });
  function showThumnbail() {
    return setShowState({
      shown: !showState.shown
    });
  }
  if (!Array.isArray(slides.props.attributes.galleryImages)) {
    /*判断图片的值是否小于0 或者是数组*/
    return null;
  }
  function deleteSliderImage(indexToDelete) {
    const gelleryImage = slides.props.attributes.galleryImages.filter(function (x, gelleryImageIndex) {
      return gelleryImageIndex != indexToDelete;
    });
    slides.props.setAttributes({
      galleryImages: gelleryImage
    });
  }
  return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("section", {
    className: "toms-image-slider-preview"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "toms-image-slider-image"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "left-arrow",
    onClick: prevSlide
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.Icon, {
    icon: () => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
      t: "1632461798804",
      className: "icon",
      viewBox: "0 0 1024 1024",
      version: "1.1",
      xmlns: "http://www.w3.org/2000/svg",
      "p-id": "8849",
      width: "32",
      height: "32"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
      d: "M804.157345 1024H596.658663c-9.595315 0-18.790825-4.297901-24.787897-11.794241L247.229283 612.600878c-47.776672-58.771303-47.776672-142.93021 0-201.701512L571.470961 11.794241c6.097023-7.49634 15.192582-11.794241 24.787897-11.794241h206.799024c6.69673 0 10.494876 7.796193 6.196974 12.993655L436.936652 471.369839c-19.090678 23.488531-19.090678 57.172084 0 80.760566l373.417667 458.775988c4.19795 5.297413 0.499756 13.093607-6.196974 13.093607z",
      "p-id": "8850"
    }))
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "toms-image-slider-active-image",
    onClick: openModal
  }, !!slideImg[current] ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    src: slideImg[current],
    className: "toms-image-slider-gellery-image",
    width: "200",
    height: "200"
  }) : (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
    src: defaultImageFromPHP,
    className: "toms-image-slider-gellery-image",
    width: "200",
    height: "200"
  })), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "right-arrow",
    onClick: nextSlide
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.Icon, {
    icon: () => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
      t: "1632461740711",
      className: "icon",
      viewBox: "0 0 1024 1024",
      version: "1.1",
      xmlns: "http://www.w3.org/2000/svg",
      "p-id": "8565",
      width: "32",
      height: "32"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
      d: "M776.270961 612.600878l-324.641483 399.604881c-6.097023 7.49634-15.192582 11.794241-24.787897 11.794241H219.44285c-6.69673 0-10.494876-7.796193-6.196974-13.093607l373.317716-458.875939c19.090678-23.488531 19.090678-57.172084 0-80.760566L214.145437 12.993655c-4.297901-5.197462-0.499756-12.993655 6.196974-12.993655h206.799024c9.595315 0 18.790825 4.297901 24.787896 11.794241l324.34163 399.105125c47.776672 58.771303 47.776672 142.93021 0 201.701512z",
      "p-id": "8566"
    }))
  }))), slides.props.attributes.thumnbail !== false && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "toms-image-slider-thumnbail"
  }, slides.props.attributes.galleryImages.map(function (slide, index) {
    if (index >= 0) {
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
        className: "toms-image-slider-thumnbail-image"
      }, !!slide && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
        src: slide,
        className: index === current ? 'toms-image-slider-gellery-thumnbail-image toms-image-slider-gellery-thumnbail-image-active' : 'toms-image-slider-gellery-thumnbail-image',
        width: "200",
        height: "200",
        onClick: () => {
          setCurrentImg(slide), setCurrent(index);
        }
      }));
    }
  })), isOpen && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.Modal, {
    className: "toms-image-slider-modal-block",
    overlayClassName: "toms-image-slider-modal-overlay",
    onRequestClose: closeModal,
    style: {
      backgroundImage: `url(${!!slideImg[modalCurrent] ? slideImg[modalCurrent] : ''})`
    }
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "toms-image-slider-modal-block"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "modal-left-arrow",
    onClick: modalPrevSlide
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.Icon, {
    icon: () => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
      t: "1632461688518",
      className: "icon",
      viewBox: "0 0 1024 1024",
      version: "1.1",
      xmlns: "http://www.w3.org/2000/svg",
      "p-id": "8373",
      width: "64",
      height: "64"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
      d: "M804.157345 1024H596.658663c-9.595315 0-18.790825-4.297901-24.787897-11.794241L247.229283 612.600878c-47.776672-58.771303-47.776672-142.93021 0-201.701512L571.470961 11.794241c6.097023-7.49634 15.192582-11.794241 24.787897-11.794241h206.799024c6.69673 0 10.494876 7.796193 6.196974 12.993655L436.936652 471.369839c-19.090678 23.488531-19.090678 57.172084 0 80.760566l373.417667 458.775988c4.19795 5.297413 0.499756 13.093607-6.196974 13.093607z",
      "p-id": "8374"
    }))
  })), slides.props.attributes.thumnbail !== false && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "toms-image-slider-modal-show-thumnbail-icon",
    onClick: showThumnbail
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.Icon, {
    icon: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
      t: "1632467128148",
      className: "thumnbail-icon",
      viewBox: "0 0 1024 1024",
      version: "1.1",
      xmlns: "http://www.w3.org/2000/svg",
      "p-id": "15012",
      width: "48",
      height: "48"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
      d: "M992 96H160a32 32 0 0 0-32 32v96H32a32 32 0 0 0-32 32v640a32 32 0 0 0 32 32h832a32 32 0 0 0 32-32v-96h96a32 32 0 0 0 32-32V128a32 32 0 0 0-32-32zM832 288v506.416l-171.168-146.72a32 32 0 0 0-37.296-3.136l-137.056 82.24-206.4-235.872a32.048 32.048 0 0 0-23.008-10.912 32.192 32.192 0 0 0-23.68 9.36L64 658.752V288h768zM64 749.248l190.432-190.432 201.488 230.272a31.952 31.952 0 0 0 40.544 6.352l140.224-84.128L814.832 864H64v-114.752zM960 736h-64V256a32 32 0 0 0-32-32H192v-64h768v576z",
      "p-id": "15013"
    }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
      d: "M656 576c61.744 0 112-50.256 112-112s-50.256-112-112-112-112 50.256-112 112 50.256 112 112 112z m0-160c26.464 0 48 21.536 48 48s-21.536 48-48 48-48-21.536-48-48 21.536-48 48-48z",
      "p-id": "15014"
    }))
  })), showState.shown === true && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "toms-image-slider-modal-thumnbail"
  }, slides.props.attributes.galleryImages.map(function (slide, index) {
    if (index >= 0) {
      return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
        className: "toms-image-slider-modal-thumnbail-image"
      }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("img", {
        src: slide,
        className: index === modalCurrent ? 'toms-image-slider-gellery-modal-thumnbail-image toms-image-slider-gellery-modal-thumnbail-image-active' : 'toms-image-slider-gellery-modal-thumnbail-image',
        width: "200",
        height: "200",
        onClick: () => {
          setModalImg(slide), setModalCurrent(index);
        }
      }));
    }
  }))), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("span", {
    className: "modal-right-arrow",
    onClick: modalNextSlide
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.Icon, {
    icon: () => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
      t: "1632461620854",
      className: "icon",
      viewBox: "0 0 1024 1024",
      version: "1.1",
      xmlns: "http://www.w3.org/2000/svg",
      "p-id": "8135",
      width: "64",
      height: "64"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
      d: "M776.270961 612.600878l-324.641483 399.604881c-6.097023 7.49634-15.192582 11.794241-24.787897 11.794241H219.44285c-6.69673 0-10.494876-7.796193-6.196974-13.093607l373.317716-458.875939c19.090678-23.488531 19.090678-57.172084 0-80.760566L214.145437 12.993655c-4.297901-5.197462-0.499756-12.993655 6.196974-12.993655h206.799024c9.595315 0 18.790825 4.297901 24.787896 11.794241l324.34163 399.105125c47.776672 58.771303 47.776672 142.93021 0 201.701512z",
      "p-id": "8136"
    }))
  }))))), isSelected && slides.props.attributes.imageSlider !== false && (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "toms-image-slider-settings"
  }, slides.props.attributes.galleryImages.map(function (images, index) {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "toms-image-slider-image-url"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
      className: "toms-image-slider-image-urls"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.TextControl, {
      label: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Image Url", "toms-image-slider") + ' ' + index,
      className: "toms-image-slider-text-control",
      placeholder: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Image url", "toms-image-slider"),
      value: images,
      onChange: newImage => {
        const newImages = slides.props.attributes.galleryImages.concat([]);
        newImages[index] = newImage;
        slides.props.setAttributes({
          galleryImages: newImages
        });
      }
    })), index >= 1 ? (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.Button, {
      variant: "link",
      title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Remove this line!!!", "toms-image-slider"),
      className: "toms-image-slider-delete",
      onClick: () => deleteSliderImage(index)
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
      xmlns: "http://www.w3.org/2000/svg",
      width: "24",
      height: "24",
      className: "bi bi-x-octagon-fill",
      viewBox: "0 0 16 16"
    }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
      d: "M11.46.146A.5.5 0 0 0 11.107 0H4.893a.5.5 0 0 0-.353.146L.146 4.54A.5.5 0 0 0 0 4.893v6.214a.5.5 0 0 0 .146.353l4.394 4.394a.5.5 0 0 0 .353.146h6.214a.5.5 0 0 0 .353-.146l4.394-4.394a.5.5 0 0 0 .146-.353V4.893a.5.5 0 0 0-.146-.353L11.46.146zm-6.106 4.5L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z"
    }))) : (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.Button, {
      variant: "link",
      className: "toms-image-slider-delete",
      style: {
        width: '24px'
      }
    }));
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "toms-image-slider-button"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("div", {
    className: "add-image-button"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_4__.Button, {
    variant: "primary",
    onClick: () => {
      slides.props.setAttributes({
        galleryImages: slides.props.attributes.galleryImages.concat([''])
      });
    }
  }, (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_3__.__)("Add Image", "toms-image-slider"))))));
};
/* harmony default export */ const __WEBPACK_DEFAULT_EXPORT__ = (TomSIamgeSlider);

/***/ }),

/***/ "./src/assets/edit.scss":
/*!******************************!*\
  !*** ./src/assets/edit.scss ***!
  \******************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/assets/slider.scss":
/*!********************************!*\
  !*** ./src/assets/slider.scss ***!
  \********************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "./src/index.scss":
/*!************************!*\
  !*** ./src/index.scss ***!
  \************************/
/***/ ((__unused_webpack_module, __webpack_exports__, __webpack_require__) => {

__webpack_require__.r(__webpack_exports__);
// extracted by mini-css-extract-plugin


/***/ }),

/***/ "react":
/*!************************!*\
  !*** external "React" ***!
  \************************/
/***/ ((module) => {

module.exports = window["React"];

/***/ }),

/***/ "@wordpress/block-editor":
/*!*************************************!*\
  !*** external ["wp","blockEditor"] ***!
  \*************************************/
/***/ ((module) => {

module.exports = window["wp"]["blockEditor"];

/***/ }),

/***/ "@wordpress/components":
/*!************************************!*\
  !*** external ["wp","components"] ***!
  \************************************/
/***/ ((module) => {

module.exports = window["wp"]["components"];

/***/ }),

/***/ "@wordpress/element":
/*!*********************************!*\
  !*** external ["wp","element"] ***!
  \*********************************/
/***/ ((module) => {

module.exports = window["wp"]["element"];

/***/ }),

/***/ "@wordpress/i18n":
/*!******************************!*\
  !*** external ["wp","i18n"] ***!
  \******************************/
/***/ ((module) => {

module.exports = window["wp"]["i18n"];

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/compat get default export */
/******/ 	(() => {
/******/ 		// getDefaultExport function for compatibility with non-harmony modules
/******/ 		__webpack_require__.n = (module) => {
/******/ 			var getter = module && module.__esModule ?
/******/ 				() => (module['default']) :
/******/ 				() => (module);
/******/ 			__webpack_require__.d(getter, { a: getter });
/******/ 			return getter;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/************************************************************************/
var __webpack_exports__ = {};
// This entry need to be wrapped in an IIFE because it need to be isolated against other modules in the chunk.
(() => {
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! @wordpress/element */ "@wordpress/element");
/* harmony import */ var _wordpress_element__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _index_scss__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./index.scss */ "./src/index.scss");
/* harmony import */ var _blocks_edit__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./blocks/edit */ "./src/blocks/edit.js");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! react */ "react");
/* harmony import */ var react__WEBPACK_IMPORTED_MODULE_3___default = /*#__PURE__*/__webpack_require__.n(react__WEBPACK_IMPORTED_MODULE_3__);
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @wordpress/i18n */ "@wordpress/i18n");
/* harmony import */ var _wordpress_i18n__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__);
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! @wordpress/components */ "@wordpress/components");
/* harmony import */ var _wordpress_components__WEBPACK_IMPORTED_MODULE_5___default = /*#__PURE__*/__webpack_require__.n(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__);
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_6__ = __webpack_require__(/*! @wordpress/block-editor */ "@wordpress/block-editor");
/* harmony import */ var _wordpress_block_editor__WEBPACK_IMPORTED_MODULE_6___default = /*#__PURE__*/__webpack_require__.n(_wordpress_block_editor__WEBPACK_IMPORTED_MODULE_6__);







const TomSImageSliderIcon = () => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__.Icon, {
  icon: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
    t: "1679035883876",
    className: "toms-blocks-icon",
    viewBox: "0 0 1024 1024",
    version: "1.1",
    xmlns: "http://www.w3.org/2000/svg",
    "p-id": "1511",
    width: "24",
    height: "24"
  }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M288.655 117.236a20.319 20.319 0 0 0-20.319 20.157v60.957H207.38a20.319 20.319 0 0 0-20.319 20.318v406.053a20.319 20.319 0 0 0 20.32 20.318h527.964a20.319 20.319 0 0 0 20.319-20.318v-60.957h60.956a20.319 20.319 0 0 0 20.319-20.318V137.393a20.319 20.319 0 0 0-20.32-20.157z m20.319 40.476H796.3v365.576h-40.637v-304.62a20.319 20.319 0 0 0-20.32-20.318h-426.37z m-81.275 81.275h487.488v362.19a3.225 3.225 0 0 1-3.225 3.225H227.7V242.05z m-121.267 21.77a8.87 8.87 0 0 0-6.773 3.225L9.837 369.608a40.96 40.96 0 0 0 0 53.377L99.82 528.77c1.612 2.096 4.031 3.225 6.773 3.225h1.612v-70.954L62.408 407.02a16.449 16.449 0 0 1 0-21.448l45.797-54.022v-70.954z m809.363 0v70.954l45.797 53.861a16.449 16.449 0 0 1 0 21.448l-45.797 53.86v70.955h1.612a9.03 9.03 0 0 0 6.773-3.064l89.983-105.786a40.96 40.96 0 0 0 0-53.377l-89.822-105.626a9.03 9.03 0 0 0-6.773-3.225zM67.245 812.105v193.511h241.89V812.105z m333.324 0v193.511h241.89V812.105z m333.325 0v193.511h241.89V812.105zM83.21 828.23h209.8v161.42H83.37z m333.324 0h209.96v161.42h-209.96z m333.324 0h209.96v161.42h-209.96z m-489.585 16.126a16.126 16.126 0 0 0-15.642 16.125 16.126 16.126 0 1 0 32.252 0 16.126 16.126 0 0 0-16.61-16.125z m333.324 0a16.126 16.126 0 0 0-15.642 16.125 16.126 16.126 0 1 0 32.252 0 16.126 16.126 0 0 0-16.61-16.125z m333.325 0a16.126 16.126 0 0 0-15.643 16.125 16.126 16.126 0 1 0 32.252 0 16.126 16.126 0 0 0-16.61-16.125z m-784.69 23.382a4.031 4.031 0 0 0-2.258 1.129l-47.25 42.895a4.031 4.031 0 0 0-1.45 3.064v66.762h193.672v-43.702a4.031 4.031 0 0 0-1.935-3.386l-43.54-26.608a4.031 4.031 0 0 0-5.16 0.806l-19.19 23.544-0.162 0.323a4.031 4.031 0 0 1-5.644 0l-63.859-63.698a4.031 4.031 0 0 0-2.902-1.129 4.031 4.031 0 0 0-0.323 0z m333.323 0a4.031 4.031 0 0 0-2.257 1.129l-47.25 42.895a4.031 4.031 0 0 0-1.45 3.064v66.762H618.27v-43.702a4.031 4.031 0 0 0-1.774-3.386l-43.54-26.608a4.031 4.031 0 0 0-5.321 0.806l-19.19 23.544-0.162 0.323a4.031 4.031 0 0 1-5.644 0l-63.859-63.698a4.031 4.031 0 0 0-2.902-1.129 4.031 4.031 0 0 0-0.323 0z m333.324 0a4.031 4.031 0 0 0-2.257 1.129l-47.25 42.895a4.031 4.031 0 0 0-1.45 3.064v66.762h193.672v-43.702a4.031 4.031 0 0 0-1.774-3.386l-43.54-26.608a4.031 4.031 0 0 0-5.321 0.806l-19.19 23.544-0.161 0.323a4.031 4.031 0 0 1-5.645 0l-63.858-63.698a4.031 4.031 0 0 0-2.903-1.129 4.031 4.031 0 0 0-0.323 0z",
    fill: "#008000",
    "p-id": "1512"
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M274.91 327.128c-5.36 0-8 2.645-8 7.998v13.66c0 5.353 2.64 8.078 8 8.078h152.333c5.36 0 8.084-2.725 8.084-8.079v-13.659c0-5.353-2.724-7.998-8.084-7.998z m385.083 11.659c-12.162-3.16-25.428 1.71-38.416 9.079-12.99 7.37-25.728 17.184-32.084 27.167-6.356 9.982-6.366 20.124-2.583 30.667 3.783 10.547 11.328 21.504 20.167 26.416 8.839 4.919 18.993 3.741 25.083 0.645 6.09-3.08 8.119-8.079 4.416-13.497-3.701-5.419-13.158-11.24-17.75-17.666-4.591-6.435-4.257-13.466 1.5-20.667 5.757-7.209 16.965-14.594 27.917-15.836 10.951-1.29 21.876 3.773 29.5 4.66 7.623 0.807 12.06-2.306 8.75-9.498-3.31-7.192-14.338-18.34-26.5-21.499z m-355.25 22.333a111.007 111.007 0 0 0-25.583 69.25v67.916c0 0.161 0.158 0.322 0.334 0.322h52.25a0.312 0.312 0 0 0 0.334-0.322v-8.58a74.64 74.64 0 0 1-27-57.333 74.64 74.64 0 0 1 0.666-9.418 74.64 74.64 0 0 1 0.416-2.5 74.64 74.64 0 0 1 1.5-6.998 74.64 74.64 0 0 1 0.583-2.258 74.64 74.64 0 0 1 3-8.418 74.64 74.64 0 0 1 0.166-0.322 74.64 74.64 0 0 1 4.667-8.998 74.64 74.64 0 0 1 41.5-32.333z m182.834 13.836l-15.834 23.834a73.594 73.594 0 0 1 8.25 33.75 73.594 73.594 0 0 1-16 45.833v16.833a0.31 0.31 0 0 0 0.334 0.322h2.334a72.822 57.903 0 0 0 33.416-22.25v-68.083l12.917-30.25z m41.583 0l-12.917 30.25h11.75v0.083h28v-0.082h16.75c-3.615-10.37-3.595-20.248 2.667-30.083 0.02-0.033 0.063-0.136 0.083-0.162z m126.167 39.834c-3.583-0.077-5.674 3.403-5.917 8.918-0.337 7.676 2.88 19.301-0.083 29.917-1.857 6.644-6.103 13.046-11 18l27.666 6.08c7.325-10.16 12.268-20.766 11.25-31.5-1.187-12.513-10.513-25.172-17.083-29.584-1.848-1.29-3.431-1.806-4.833-1.839z m-113.25 1.29c-9.377 0-14.084 4.71-14.084 14.078v21.084c0 9.369 4.707 13.997 14.084 13.997 9.376 0 14-4.628 14-13.997v-21.084c0-9.385-4.624-14.078-14-14.078z m45.5 37.584c-2.732 0.137-5.328 1.903-7.584 4.999-4.01 5.515-6.779 15.32-3.333 24.834 3.445 9.498 13.109 18.754 22.916 24.166 9.808 5.402 19.717 7.015 30.584 2.338 8.553-3.692 17.699-11.256 25.75-20l-39-8.66c-3.005-0.484-5.76-2.032-8.25-4.5-5.616-5.563-9.909-15.9-14.667-20.417-2.082-1.967-4.292-2.854-6.417-2.757z",
    "p-id": "1513"
  }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
    d: "M404.946 491.724a13.288 13.288 0 0 1-18.07-1.129l-50.362-58.427a13.288 13.288 0 0 1 7.653-21.124l5.515-1.29-0.16-0.161 50.85-12.691 50.84-12.707-20.052 48.427-20.051 48.419-0.049-0.057-2.147 5.257a13.288 13.288 0 0 1-3.967 5.532z m0.887-39.162a5.733 5.733 0 0 0 1.706-2.386l0.926-2.274 0.03 0.034 8.646-20.887 8.653-20.9-21.94 5.482-21.932 5.467 0.063 0.074-2.388 0.645a5.733 5.733 0 0 0-3.291 9.128l21.723 25.201a5.733 5.733 0 0 0 7.799 0.484z",
    fill: "#FF0000",
    "p-id": "1514"
  }))
});
wp.blocks.registerBlockType("tomsneddon-image-slider/toms-image-slider", {
  title: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("TomS Image Slider", "toms-image-slider"),
  icon: TomSImageSliderIcon,
  category: "tomsneddon",
  attributes: {
    // Slider
    imageSlider: {
      type: 'boolean',
      default: true
    },
    galleryImages: {
      type: 'array',
      default: ['']
    },
    autoPlay: {
      type: 'boolean',
      default: true
    },
    thumnbail: {
      type: 'boolean',
      default: true
    },
    sliderModal: {
      type: 'boolean',
      default: true
    },
    editorSliderModal: {
      type: 'boolean',
      default: false
    }
  },
  description: (0,_wordpress_i18n__WEBPACK_IMPORTED_MODULE_4__.__)("Simply Image Slider block.", "toms-image-slider"),
  // example: {
  //     title: __("TomS Image Slider", "toms-image-slider")
  // },
  edit: function (props) {
    return (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.Fragment, null, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_blocks_edit__WEBPACK_IMPORTED_MODULE_2__["default"], {
      props: props
    }), /* Change the TomS Blocks Category Icon */
    function () {
      const TomSBlocksIcon = () => (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)(_wordpress_components__WEBPACK_IMPORTED_MODULE_5__.Icon, {
        icon: (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("svg", {
          t: "1633509965289",
          className: "toms-blocks-icon",
          viewBox: "0 0 1078 1024",
          version: "1.1",
          xmlns: "http://www.w3.org/2000/svg",
          "p-id": "2725",
          width: "24",
          height: "24"
        }, (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
          d: "M9.872126 282.058576a0.755905 0.755905 0 0 0-0.755906 0.755905v72.94488c0 0.423307 0.347717 0.755905 0.755906 0.755906h95.274328v334.866133c0 0.408189 0.332598 0.740787 0.755906 0.740788H232.818892a0.755905 0.755905 0 0 0 0.755905-0.755906v-23.039999a178.590232 178.590232 0 0 1-63.66236-136.45606 178.590232 178.590232 0 0 1 63.647242-136.697949v-38.657007h183.775744a0.755905 0.755905 0 0 0 0.755905-0.755906v-72.94488a0.755905 0.755905 0 0 0-0.755905-0.755905z m1011.47714 0.423307v3.462047h10.794331v26.95559h4.142362v-26.95559h10.79433v-3.477165z m29.707086 0v30.417637h3.991181V286.18582l7.831181 20.862991h4.15748l7.831181-20.862991v26.7137h4.021417v-30.417637h-6.137952l-7.800945 20.696692-7.75559-20.71181z m-244.883143 42.738896l-28.724409 17.794016v49.103621H488.919673c-0.377953 0-0.695433 0.302362-0.695433 0.650078V420.948651a178.590232 178.590232 0 0 1 38.853542 110.921572 178.590232 178.590232 0 0 1-38.853542 111.17858v40.758424c0 0.423307 0.332598 0.755905 0.755905 0.755906h85.901101a0.755905 0.755905 0 0 0 0.755905-0.755906v-218.305506h67.804723v145.118736c0 0.302362 0.24189 0.574488 0.55937 0.574489h66.852282c0.302362 0 0.574488-0.257008 0.574488-0.574489V465.486603h65.99055v91.116848l28.70929 17.748661H958.034622c0.377953 0 0.680315 0.302362 0.680315 0.680315v118.435272c0 0.362835-0.302362 0.680315-0.680315 0.680315h-118.420154a0.680315 0.680315 0 0 1-0.680315-0.680315v-54.364723h-61.515589V757.281241c0 0.362835 0.302362 0.665197 0.680314 0.665197h215.327239l24.642519-15.269291V525.89857l-29.480314-18.232441h-148.973854a0.665197 0.665197 0 0 1-0.680315-0.680315v-118.420154c0-0.377953 0.302362-0.680315 0.680315-0.680315h118.420154c0.377953 0 0.680315 0.302362 0.680315 0.680315V442.915265h59.353699v-117.014171a0.680315 0.680315 0 0 0-0.680315-0.680315z",
          opacity: ".96",
          "p-id": "2726"
        }), (0,_wordpress_element__WEBPACK_IMPORTED_MODULE_0__.createElement)("path", {
          d: "M267.363773 425.484084a26.910236 26.910236 0 0 0-26.275275 25.564724v156.215429a26.910236 26.910236 0 0 0 39.66992 22.284094l10.250079-5.215748v0.498898l94.790549-47.758109 94.77543-47.742991-94.790548-47.773227-94.775431-47.75811v0.151182l-10.250079-5.230866a26.910236 26.910236 0 0 0-13.394645-3.235276zM320.503929 484.38424a11.610708 11.610708 0 0 1 5.775118 1.405984l4.429606 2.267717v-0.090709l40.879369 20.605984 40.909606 20.621102-40.909606 20.590865-40.879369 20.605984v-0.196535l-4.429606 2.267716a11.610708 11.610708 0 0 1-17.1137-9.645354v-67.381416a11.610708 11.610708 0 0 1 11.338582-11.03622z",
          fill: "#FF0000",
          "p-id": "2727"
        }))
      });
      wp.blocks.updateCategory('tomsneddon', {
        icon: TomSBlocksIcon
      });
    }());
  },
  save: function (props) {
    return null;
  }
});
})();

/******/ })()
;
//# sourceMappingURL=index.js.map