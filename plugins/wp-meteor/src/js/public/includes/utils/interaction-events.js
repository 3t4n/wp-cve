import dispatcher from "../utils/dispatcher";
import delta from "../utils/delta";
import listenerOptions from "./listener-options";
const c = process.env.DEBUG ? console.log : () => {
};
const w = window;
const d = document;
const a = "addEventListener";
const r = "removeEventListener";
const ra = "removeAttribute";
const ga = "getAttribute";
const sa = "setAttribute";
const DCL = "DOMContentLoaded";
const interactionEvents = ["mouseover", "keydown", "touchmove", "touchend", "wheel"];
const captureEvents = ["mouseover", "mouseout", "touchstart", "touchmove", "touchend", "click"];
const prefix = "data-wpmeteor-";
const separator = "----";
export default class InteractionEvents {
  init() {
    let firstInteractionFired = false;
    let firstInteractionTimeout = false;
    const onFirstInteraction = (e) => {
      process.env.DEBUG && c(delta(), separator, "firstInteraction event MAYBE fired", (e || {}).type);
      if (!firstInteractionFired) {
        process.env.DEBUG && c(delta(), separator, "firstInteraction fired");
        firstInteractionFired = true;
        process.env.DEBUG && c(delta(), separator, "firstInteraction event listeners removed");
        interactionEvents.forEach((event) => d.body[r](event, onFirstInteraction, listenerOptions));
        clearTimeout(firstInteractionTimeout);
        dispatcher.emit("fi");
      }
    };
    const synteticCick = (e) => {
      process.env.DEBUG && c(delta(), "creating syntetic click event for", e);
      const event = new MouseEvent("click", {
        view: e.view,
        bubbles: true,
        cancelable: true
      });
      Object.defineProperty(event, "target", { writable: false, value: e.target });
      return event;
    };
    dispatcher.on("i", () => {
      if (!firstInteractionFired) {
        onFirstInteraction();
      }
    });
    const capturedEvents = [];
    const captureEvent = (e) => {
      if (e.target && "dispatchEvent" in e.target) {
        process.env.DEBUG && c(delta(), "captured", e.type, e.target);
        if (e.type === "click") {
          e.preventDefault();
          e.stopPropagation();
          capturedEvents.push(synteticCick(e));
        } else if (e.type !== "touchmove") {
          capturedEvents.push(e);
        }
        e.target[sa](prefix + e.type, true);
      }
    };
    dispatcher.on("l", () => {
      process.env.DEBUG && c(delta(), separator, "removing mouse event listeners");
      captureEvents.forEach((name) => w[r](name, captureEvent));
      let e;
      while (e = capturedEvents.shift()) {
        var target = e.target;
        if (target[ga](prefix + "touchstart") && target[ga](prefix + "touchend") && !target[ga](prefix + "click")) {
          if (target[ga](prefix + "touchmove")) {
            process.env.DEBUG && c(delta(), " touchmove happened, so not dispatching click to ", e.target);
          } else {
            target[ra](prefix + "touchmove");
            capturedEvents.push(synteticCick(e));
          }
          target[ra](prefix + "touchstart");
          target[ra](prefix + "touchend");
        } else {
          target[ra](prefix + e.type);
        }
        process.env.DEBUG && c(delta(), " dispatching " + e.type + " to ", e.target);
        target.dispatchEvent(e);
      }
    });
    const installFirstInteractionListeners = () => {
      process.env.DEBUG && c(delta(), separator, "installing firstInteraction listeners");
      interactionEvents.forEach((event) => d.body[a](event, onFirstInteraction, listenerOptions));
      process.env.DEBUG && c(delta(), separator, "installing mouse event listeners");
      captureEvents.forEach((name) => w[a](name, captureEvent));
      d[r](DCL, installFirstInteractionListeners);
    };
    d[a](DCL, installFirstInteractionListeners);
  }
}
//# sourceMappingURL=interaction-events.js.map
