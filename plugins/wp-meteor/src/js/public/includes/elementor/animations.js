import delta from "../utils/delta";
import dispatcher from "../utils/dispatcher";
import getCurrentDeviceMode from "./device-mode";
import {
  getAttribute,
  setAttribute,
  addEventListener,
  querySelectorAll,
  L
} from "../literals";
import {
  w,
  d,
  de,
  c
} from "../globals";
const getClass = (el) => {
  return el[getAttribute]("class") || "";
};
const setClass = (el, value) => {
  return el[setAttribute]("class", value);
};
export default () => {
  w[addEventListener](L, function() {
    const mode = getCurrentDeviceMode();
    const vw = Math.max(de.clientWidth || 0, w.innerWidth || 0);
    const vh = Math.max(de.clientHeight || 0, w.innerHeight || 0);
    const keys = ["_animation_" + mode, "animation_" + mode, "_animation", "_animation", "animation"];
    Array.from(d[querySelectorAll](".elementor-invisible")).forEach((el) => {
      const viewportOffset = el.getBoundingClientRect();
      if (viewportOffset.top + w.scrollY <= vh && viewportOffset.left + w.scrollX < vw) {
        try {
          const settings = JSON.parse(el[getAttribute]("data-settings"));
          if (settings.trigger_source) {
            return;
          }
          const animationDelay = settings._animation_delay || settings.animation_delay || 0;
          let animation, key;
          for (var i = 0; i < keys.length; i++) {
            if (settings[keys[i]]) {
              key = keys[i];
              animation = settings[key];
              break;
            }
          }
          if (animation) {
            process.env.DEBUG && c(delta(), "animating with" + animation, el);
            const oldClass = getClass(el);
            const newClass = animation === "none" ? oldClass : oldClass + " animated " + animation;
            const animate = () => {
              setClass(el, newClass.replace(/\belementor-invisible\b/, ""));
              keys.forEach((key2) => delete settings[key2]);
              el[setAttribute]("data-settings", JSON.stringify(settings));
            };
            let timeout = setTimeout(animate, animationDelay);
            dispatcher.on("fi", () => {
              clearTimeout(timeout);
              setClass(el, getClass(el).replace(new RegExp("\\b" + animation + "\\b"), ""));
            });
          }
        } catch (e) {
          console.error(e);
        }
      }
    });
  });
};
//# sourceMappingURL=animations.js.map
