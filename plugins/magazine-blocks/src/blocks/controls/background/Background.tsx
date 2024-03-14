import {
	Tab,
	TabList,
	TabPanel,
	TabPanels,
	Tabs,
	useControllableState,
} from "@chakra-ui/react";
import React from "react";
import ColorPicker from "../color-picker/ColorPicker";
import GradientPicker from "../gradient-picker/GradientPicker";
import BackgroundImage from "./background-image/BackgroundImage";
import { BACKGROUND_TYPES, DEFAULT_BACKGROUND_TYPE } from "./constants";
import { BackgroundProps } from "./types";

const tabStyles = {
	borderBottom: "0",
	p: "0",
	outline: "none",
	w: "22px",
	h: "22px",
	border: "1px",
	borderColor: "transparent",
	borderRadius: "base",
	_active: {
		border: "1px",
		borderColor: "primary.500",
	},
	svg: {
		h: "20px",
		w: "20px",
	},
};

export const Background = ({ value, onChange }: BackgroundProps) => {
	const [type, setType] = useControllableState({
		value: BACKGROUND_TYPES.indexOf(value?.type ?? DEFAULT_BACKGROUND_TYPE),
		onChange(i) {
			onChange({
				...(value ?? {}),
				type: BACKGROUND_TYPES[i] as "color" | "image" | "gradient",
			});
		},
	});
	return (
		<Tabs index={type} onChange={setType} size="sm">
			<TabList borderBottom="0" gap="8px" mb="4">
				<Tab {...tabStyles}>
					<svg
						viewBox="0 0 20 20"
						fill="none"
						xmlns="http://www.w3.org/2000/svg"
					>
						<rect
							x="0.5"
							y="0.5"
							width="19"
							height="19"
							rx="3.5"
							fill="#DBDBDB"
							stroke="#F4F4F4"
						/>
					</svg>
				</Tab>
				<Tab {...tabStyles}>
					<svg
						viewBox="0 0 20 20"
						fill="none"
						xmlns="http://www.w3.org/2000/svg"
					>
						<rect
							x="0.5"
							y="0.5"
							width="19"
							height="19"
							rx="3.5"
							fill="url(#paint0_linear_3987_3462)"
							stroke="#F4F4F4"
						/>
						<defs>
							<linearGradient
								id="paint0_linear_3987_3462"
								x1="10"
								y1="0"
								x2="10"
								y2="20"
								gradientUnits="userSpaceOnUse"
							>
								<stop stopColor="#222222" />
								<stop offset="1" stopColor="#CDCDCD" />
							</linearGradient>
						</defs>
					</svg>
				</Tab>
				<Tab {...tabStyles}>
					<svg
						viewBox="0 0 24 24"
						fill="none"
						xmlns="http://www.w3.org/2000/svg"
					>
						<path
							fillRule="evenodd"
							clipRule="evenodd"
							//  eslint-disable-next-line
							d="M6 4C5.46957 4 4.96086 4.21071 4.58579 4.58579C4.21071 4.96086 4 5.46957 4 6V13.5858L7.29289 10.2929L7.30661 10.2794C7.92076 9.68845 8.67726 9.33025 9.5 9.33025C10.3227 9.33025 11.0792 9.68845 11.6934 10.2794L11.7071 10.2929L14 12.5858L14.2929 12.2929L14.3066 12.2794C14.9208 11.6884 15.6773 11.3302 16.5 11.3302C17.3227 11.3302 18.0792 11.6884 18.6934 12.2794L18.7071 12.2929L20 13.5858V6C20 5.46957 19.7893 4.96086 19.4142 4.58579C19.0391 4.21071 18.5304 4 18 4H6ZM22 15.999V6C22 4.93913 21.5786 3.92172 20.8284 3.17157C20.0783 2.42143 19.0609 2 18 2H6C4.93913 2 3.92172 2.42143 3.17157 3.17157C2.42143 3.92172 2 4.93913 2 6V15.9998C2 15.9997 2 15.9999 2 15.9998V18C2 19.0609 2.42143 20.0783 3.17157 20.8284C3.92172 21.5786 4.93913 22 6 22H18C19.0609 22 20.0783 21.5786 20.8284 20.8284C21.5786 20.0783 22 19.0609 22 18V16.001C22 16.0003 22 15.9997 22 15.999ZM20 16.4142L17.3003 13.7146C16.989 13.4174 16.7118 13.3302 16.5 13.3302C16.2882 13.3302 16.011 13.4174 15.6997 13.7146L15.4142 14L16.7071 15.2929C17.0976 15.6834 17.0976 16.3166 16.7071 16.7071C16.3166 17.0976 15.6834 17.0976 15.2929 16.7071L10.3003 11.7146C9.98904 11.4174 9.71184 11.3302 9.5 11.3302C9.28816 11.3302 9.01096 11.4174 8.69965 11.7146L4 16.4142V18C4 18.5304 4.21071 19.0391 4.58579 19.4142C4.96086 19.7893 5.46957 20 6 20H18C18.5304 20 19.0391 19.7893 19.4142 19.4142C19.7893 19.0391 20 18.5304 20 18V16.4142ZM14 8C14 7.44772 14.4477 7 15 7H15.01C15.5623 7 16.01 7.44772 16.01 8C16.01 8.55228 15.5623 9 15.01 9H15C14.4477 9 14 8.55228 14 8Z"
							fill="#383838"
						/>
					</svg>
				</Tab>
			</TabList>
			<TabPanels>
				<TabPanel p="0">
					<ColorPicker
						onChange={(v) => {
							onChange({
								...(value ?? {}),
								color: v,
							});
						}}
						value={value?.color}
					/>
				</TabPanel>
				<TabPanel p="0">
					<GradientPicker
						onChange={(v) => {
							onChange({
								...(value ?? {}),
								gradient: v,
							});
						}}
						value={value?.gradient}
					/>
				</TabPanel>
				<TabPanel p="0">
					<BackgroundImage
						onChange={(v) => {
							onChange({
								...(value ?? {}),
								image: v,
							});
						}}
						value={value?.image}
					/>
				</TabPanel>
			</TabPanels>
		</Tabs>
	);
};

export default Background;
