import domReady from "@wordpress/dom-ready";
import { createRoot, render } from "@wordpress/element";
import React from "react";
import App from "./App";

domReady(() => {
	const root = document.getElementById("mzb");
	if (!root) return;
	if (createRoot) {
		createRoot(root).render(<App />);
	} else {
		render(<App />, root);
	}
});
