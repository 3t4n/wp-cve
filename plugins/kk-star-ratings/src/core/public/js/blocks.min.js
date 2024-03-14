/**
 * kk Star Ratings
 * @see https://github.com/kamalkhan/kk-star-ratings
 */
"use strict";!function(t,e){var r;t.kkStarRatingsBlocks={register:(r=kk_star_ratings_blocks,function(t,i){if(0>t.indexOf("/")&&(t="kk-star-ratings/"+t),!r[t])throw Error("The '"+t+"' block is not registered.");var s=i({name:t,data:r[t].data||{}}),a=Object.assign({},r[t].meta||{},s instanceof Function?{edit:s}:s);return e.registerBlockType(t,a)})}}(window,window.wp.blocks);
