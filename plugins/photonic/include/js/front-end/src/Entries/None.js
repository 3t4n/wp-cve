import {Core} from "../Core";
import * as Listeners from "../Listeners";
import * as Layout from "../Layouts/Layout";

document.addEventListener('DOMContentLoaded', () => {
	Core.executeCommon();
	Listeners.addAllListeners();
	Layout.initializeLayouts();
});
