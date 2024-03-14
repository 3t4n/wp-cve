// 各アイコンパックのセットにも使う
export default function (prefix, tabIcon, icons, pickableList) {
	const ICON_NAMESPACE = '__PONHIRO_ICONS__';

	wp.domReady(function () {
		if (undefined === window) return;

		// まだアイコンオブジェクトなければ
		if (undefined === window[ICON_NAMESPACE])
			window[ICON_NAMESPACE] = {
				src: {},
				list: {},
				tabs: [],
			};

		try {
			window[ICON_NAMESPACE].src[prefix] = icons;
			window[ICON_NAMESPACE].list[prefix] = pickableList;
			window[ICON_NAMESPACE].tabs.push({
				prefix,
				icon: tabIcon,
			});
		} catch (e) {
			// eslint-disable-next-line no-console
			console.log(e);
		}
	});
}
