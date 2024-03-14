import React from 'react';
import { getStrings } from './../Helpers';
//styles
import '../styles/_tabSettings.scss';
// import Tooltip from './Tooltip';
import Tooltip from './TooltipTab';


const TabSettings = ({ currentTab, setCurrentTab }) => {
	return (
		<div className="tab-settings-wrap">
			<div className="hide-tab-group-title-wrap">
				<input
					type="checkbox"
					name="hide_tab_group_title"
					id="hide-tab-group-title"
					checked={!currentTab?.show_name || currentTab?.show_name == 0}
					onChange={(e) => {
						setCurrentTab({
							...currentTab,
							show_name: !e.target.checked,
						});
					}}
				/>
				<label htmlFor="hide-tab-group-title">
					{getStrings('hide-grp-title')}
					<span>
						<Tooltip content={getStrings('tooltip-41')} />
					</span>
				</label>
			</div>

			<div className="hide-tab-group-title-wrap">
				<label htmlFor="select-table-for-tab">{getStrings('Tab-position')}
					<span>
						<Tooltip content={getStrings('tooltip-42')} />
					</span>
				</label>
			</div>
			<div className="tab-positions-wrap">

				<button
					className={`tab-position after-table${!parseInt(currentTab?.reverse_mode) ? ' active'
						: ''}`}
					onClick={(e) => {
						setCurrentTab({
							...currentTab,
							reverse_mode: 0
						});
					}}
				>
					<span>
						{getStrings('Before-the-table')}
						<Tooltip content={getStrings('tooltip-43')} />
					</span>
					<div className="control__indicator"></div>
				</button>

				<button
					className={`tab-position before-table${parseInt(currentTab?.reverse_mode) ? ' active'
						: ''}`}
					onClick={(e) => {
						setCurrentTab({
							...currentTab,
							reverse_mode: 1
						});
					}}
				>
					<span>
						{getStrings('After-the-table')}
						<Tooltip content={getStrings('tooltip-44')} />
					</span>
					<div className="control__indicator"></div>
				</button>

			</div>
		</div>
	);
};

export default TabSettings;
