/**
 * @WordPress dependencies
 */
import {
	__,
	//_x
} from '@wordpress/i18n';
import { useState } from '@wordpress/element';
import { Button, ButtonGroup, TextControl, TabPanel, Modal } from '@wordpress/components';

/**
 * @Others dependencies
 */
// import classnames from 'classnames';

/**
 * @Inner dependencies
 */
import { textDomain } from '@blocks/config';
import LsIcon from '@components/LsIcon';
import {
	ICON_STYLE_LIST,
	//ICON_STYLE_LABELS,
	ICON_CATEGORIES,
} from './config';

/**
 * memo: パッケージによって変える
 */
const ICON_NAMESPACE = '__PONHIRO_ICONS__';

/**
 * TEXT
 */
const TEXTS = {
	style: __('Style', textDomain),
	select: __('Select icon', textDomain),
	search: __('Search icon', textDomain),
	nofound: __('Icon not found.', textDomain),
};

const IconBtns = ({ icons, value, onChange, isFilterd }) => {
	// icons: ['__catA__', icon1, icon2, ..., '__catB__', icon1, icon2, ...],

	return (
		<ButtonGroup className='__iconList' data-filterd={isFilterd ? '1' : '0'}>
			{icons.map((iconName, idx) => {
				if (iconName.startsWith('--')) {
					if (isFilterd) return null;
					const catName = ICON_CATEGORIES[iconName] ?? iconName;
					return (
						<span key={idx} className='__iconCategory'>
							{catName}
						</span>
					);
				}
				return (
					<Button
						key={idx}
						variant={iconName === value ? 'primary' : ''}
						onClick={() => {
							onChange(iconName);
						}}
					>
						<LsIcon icon={iconName} size='20px' />
					</Button>
				);
			})}
		</ButtonGroup>
	);
};

const IconPickerTab = ({ value = '', onChange, searchValue }) => {
	const ICON_DATA = window[ICON_NAMESPACE] || {};
	const PICKER_LIST = ICON_DATA.list || {};
	let ICON_TABS = ICON_DATA.tabs || [];
	ICON_TABS = ICON_TABS.map((tabData) => {
		return {
			name: tabData.prefix,
			title: <LsIcon icon={tabData.icon} />,
		};
	});
	const INITIAL_TAB = ICON_TABS[0].name;

	let slectedTab = '';
	if (value.startsWith('Fa') || value.startsWith('fa')) {
		slectedTab = 'fa';
	}

	const [currentTabName, setCurrentTabName] = useState(slectedTab || INITIAL_TAB);
	const theIconsList = PICKER_LIST[currentTabName] || {};

	// const iconStyles = Object.keys(theIconsList);
	const iconStyles = ICON_STYLE_LIST[currentTabName];
	// const iconStyleOptions = iconStyles.map((_style) => {
	// 	return {
	// 		value: _style,
	// 		label: ICON_STYLE_LABELS[_style] ?? _style,
	// 	};
	// });

	// const iconType = useMemo(() => {},[]);
	const [iconType, setIconType] = useState(iconStyles[0] || 'regular');

	const theIcons = theIconsList[iconType] || [];

	// フィルタ後のリスト
	let filteredIcons = null;

	// 検索ワードからアイコンを絞り込みしているとき
	if (searchValue) {
		filteredIcons = theIcons.filter((i) => i.toLowerCase().includes(searchValue.toLowerCase()));
	}

	return (
		<TabPanel
			className='ls-iconModal__tab'
			activeClass='is-active'
			onSelect={(tabName) => {
				setCurrentTabName(tabName);

				// タブ切り替え時に同じアイコンタイプがなければリセット
				if (!ICON_STYLE_LIST[tabName].includes(iconType)) {
					setIconType(ICON_STYLE_LIST[tabName][0]);
				}
			}}
			tabs={ICON_TABS}
			initialTabName={currentTabName}
		>
			{/* eslint-disable-next-line no-unused-vars */}
			{(tab) => {
				return (
					<>
						{/* <ButtonGroup className='__iconStyles'>
							<span className='__label'>{TEXTS.style} : </span>
							{iconStyleOptions.map((_style, idx) => {
								return (
									<Button
										key={idx}
										variant={_style.value === iconType ? 'primary' : ''}
										onClick={() => {
											setIconType(_style.value);
										}}
									>
										{_style.label}
									</Button>
								);
							})}
						</ButtonGroup> */}
						{(() => {
							if (filteredIcons) {
								return filteredIcons.length ? (
									<IconBtns
										icons={filteredIcons}
										value={value}
										onChange={onChange}
										isFilterd={true}
									/>
								) : (
									<div className='__noIcon'>{TEXTS.nofound}</div>
								);
							}
							// if (value.startsWith('ls')) {
							return <IconBtns icons={theIcons} value={value} onChange={onChange} />;
						})()}
					</>
				);
			}}
		</TabPanel>
	);
};

/**
 * LsIconModal
 */
export default function ({
	value,
	onChange,
	onClose,
	// svg,
	// onSetSvg,
	renderBottom,
	position = 'sidebar',
	continueable = false,
}) {
	const [searchValue, setSearchValue] = useState('');

	return (
		<Modal
			title={TEXTS.select}
			className={`ls-iconModal -position-${position}`}
			onRequestClose={() => {
				onClose();
				setSearchValue('');
			}}
		>
			<div className='ls-iconModal__inner'>
				<div className='ls-iconModal__top'>
					<TextControl
						placeholder={TEXTS.search}
						className='ls-iconModal__s'
						value={searchValue}
						// autoFocus={false}
						autoComplete='false'
						name='icon-search'
						onChange={(newSearchValue) => {
							setSearchValue(newSearchValue);
						}}
					/>
				</div>

				<IconPickerTab
					onChange={(val) => {
						onChange(val);
						if (!continueable) onClose();
					}}
					{...{
						value,
						searchValue,
					}}
				/>
				<div className='ls-iconModal__bottom'>{renderBottom && renderBottom()}</div>
			</div>
		</Modal>
	);
}
