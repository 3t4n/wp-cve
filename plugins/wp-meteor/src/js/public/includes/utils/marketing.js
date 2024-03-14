import { EVENT_THE_END } from "@aguidrevitch/fpo-inpage-events";
import dispatcher from "./dispatcher";
export default class Marketing {
  constructor(rest_url) {
    this.rest_url = rest_url;
  }
  init() {
    const detected = [];
    dispatcher.on("s", (s) => {
      if (s) {
        if (s.match(/js\/forms2\/js\/forms2.min.js/)) {
          detected.push("marketo");
        } else if (s.match(/js\.hsforms\.net\/forms\//)) {
          detected.push("hubspot");
        }
      }
    });
    dispatcher.on(EVENT_THE_END, () => {
      if (detected.length) {
        setTimeout(() => {
          const xhttp = new XMLHttpRequest();
          xhttp.open("POST", this.rest_url + "wpmeteor/v1/detect/", true);
          xhttp.setRequestHeader("Content-Type", "application/json");
          xhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
          xhttp.send(JSON.stringify({ data: detected }));
        }, 2e4);
      }
    });
  }
}
//# sourceMappingURL=marketing.js.map
