const siblings = function (el: HTMLElement): HTMLElement[] {
	if (el.parentNode === null) return [];

	return Array.prototype.filter.call(
		el.parentNode.children,
		function (child: HTMLElement) {
			return child !== el;
		}
	);
};

const initTab = () => {
	const magazineTabs =
		document.querySelectorAll<HTMLElement>(".mzb-tab-post");

	if (!magazineTabs?.length) {
		return;
	}

	for (const magazineTab of magazineTabs) {
		const tabs =
			magazineTab &&
			magazineTab.querySelectorAll<HTMLElement>(".mzb-tab-title");

		if (!tabs?.length) {
			return;
		}

		for (const tab of tabs) {
			tab.addEventListener("click", (e) => {
				const sibling = siblings(tab)?.[0];
				magazineTab.setAttribute(
					"data-active-tab",
					(e.target as HTMLElement).getAttribute("data-tab") || ""
				);
				tab.classList.add("active");
				sibling?.classList?.remove("active");
			});
		}
	}
};

initTab();
