/**
 * import
 */
import classnames from 'classnames';

/**
 * memo: パッケージによって変える
 */
const ICON_NAMESPACE = '__PONHIRO_ICONS__';

/**
 * <LsIcon>
 */
export default ({ icon, label, size = '1em', className = '', returnItagIf404 = true }) => {
	let iconPrefix = '';
	const ICON_DATA = window[ICON_NAMESPACE] || {};
	const ALL_ICONS = ICON_DATA.src || {};

	// アイコン名のプレフィックスからアイコン種類を判別してアイコン生成
	if (icon.startsWith('Fa')) {
		iconPrefix = 'fa';
	}

	if (iconPrefix) {
		const theIcons = ALL_ICONS[iconPrefix] || {};
		if (!theIcons[icon]) return null;

		const TheIconComponent = theIcons[icon];

		// label の有無でaria属性を変える
		let ariaProps = {};
		if (label) {
			ariaProps = { role: 'img', 'aria-label': label, 'data-icon': icon };
		} else {
			ariaProps = { 'aria-hidden': true, 'data-icon': icon };
		}

		return (
			<TheIconComponent
				className={className || null}
				height={size}
				width={size}
				xmlns='http://www.w3.org/2000/svg'
				{...ariaProps}
			/>
		);
	}

	// svgに変換できなければiタグで返す
	return returnItagIf404 ? <i className={classnames(icon, className)}></i> : null;
};
