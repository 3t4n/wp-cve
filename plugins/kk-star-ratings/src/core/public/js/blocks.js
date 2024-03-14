/**
 * kk Star Ratings
 * @see https://github.com/kamalkhan/kk-star-ratings
 */

"use strict";

(function (window, blocks) {
  function createRegisterFn(payloads) {
    return function (name, callback) {
      if (name.indexOf("/") < 0) {
        name = "kk-star-ratings/" + name;
      }

      if (!payloads[name]) {
        throw new Error("The '" + name + "' block is not registered.");
      }

      var optionsOrEditFn = callback({
        name,
        data: payloads[name]["data"] || {},
      });

      var options = Object.assign(
        {},
        payloads[name]["meta"] || {},
        optionsOrEditFn instanceof Function
          ? {
              edit: optionsOrEditFn,
            }
          : optionsOrEditFn
      );

      return blocks.registerBlockType(name, options);
    };
  }

  window.kkStarRatingsBlocks = {
    register: createRegisterFn(kk_star_ratings_blocks),
  };
})(window, window.wp.blocks);
