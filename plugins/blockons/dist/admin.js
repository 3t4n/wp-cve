/******/ (() => { // webpackBootstrap
/******/ 	"use strict";
var __webpack_exports__ = {};
// import React from "react";
// import ReactDOM from "react-dom";
// import UpdateNotice from "./UpdateNotice";

/*
 * Blockons Admin JS
 */

document.addEventListener("DOMContentLoaded", function () {
  var blockonsRemFsLinks = document.querySelectorAll(".fs-submenu-item.blockons");

  if (blockonsRemFsLinks) {
    blockonsRemFsLinks.forEach(function (item) {
      item.closest("li").remove();
    });
  } // const blockonsUpdate = document.getElementById("blockons-update");
  // if (typeof blockonsUpdate !== undefined && blockonsUpdate !== null) {
  // 	ReactDOM.render(
  // 		<UpdateNotice />,
  // 		document.getElementById("blockons-update")
  // 	);
  // }

});
/******/ })()
;