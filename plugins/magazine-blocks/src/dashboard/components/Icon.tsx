import { chakra, IconProps } from "@chakra-ui/react";
import React from "react";

export const Search: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 18 18"
		xmlns="https://www.w3.org/2000/svg"
		ref={ref}
		{...props}
	>
		<path
			fillRule="evenodd"
			d="M8.25 3.25a5 5 0 1 0 0 10 5 5 0 0 0 0-10Zm-7 5a7 7 0 1 1 14 0 7 7 0 0 1-14 0Z"
			clipRule="evenodd"
		/>
		<path
			fillRule="evenodd"
			d="M11.78 11.78a1 1 0 0 1 1.415 0l3.262 3.263a1 1 0 0 1-1.414 1.414l-3.263-3.262a1 1 0 0 1 0-1.415Z"
			clipRule="evenodd"
		/>
	</chakra.svg>
));

export const Cog: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		ref={ref}
		{...props}
	>
		<path
			fillRule="evenodd"
			d="M12 9.738a2.262 2.262 0 1 0 0 4.524 2.262 2.262 0 0 0 0-4.524ZM8.833 12a3.167 3.167 0 1 1 6.334 0 3.167 3.167 0 0 1-6.334 0Z"
			clipRule="evenodd"
		/>
		<path
			fillRule="evenodd"
			// eslint-disable-next-line
			d="M12 3.405a1.192 1.192 0 0 0-1.193 1.192v.143a1.81 1.81 0 0 1-1.096 1.656.452.452 0 0 1-.154.036 1.81 1.81 0 0 1-1.904-.4l-.003-.003-.05-.05a1.194 1.194 0 1 0-1.688 1.688l.053.053a1.81 1.81 0 0 1 .368 1.983 1.81 1.81 0 0 1-1.651 1.171h-.085a1.192 1.192 0 1 0 0 2.385h.142a1.81 1.81 0 0 1 1.655 1.095 1.81 1.81 0 0 1-.363 1.994l-.003.003-.05.05a1.194 1.194 0 1 0 1.688 1.688l.053-.053a1.81 1.81 0 0 1 1.983-.368 1.81 1.81 0 0 1 1.171 1.651v.084a1.192 1.192 0 1 0 2.385 0v-.143a1.81 1.81 0 0 1 1.095-1.655 1.809 1.809 0 0 1 1.994.363l.003.003.05.05a1.194 1.194 0 1 0 1.688-1.688l-.053-.053a1.81 1.81 0 0 1-.363-1.994 1.81 1.81 0 0 1 1.655-1.094h.076a1.192 1.192 0 1 0 0-2.386h-.143a1.81 1.81 0 0 1-1.656-1.096.453.453 0 0 1-.036-.154 1.81 1.81 0 0 1 .4-1.904l.003-.003.05-.05a1.193 1.193 0 1 0-1.688-1.688l-.053.053a1.81 1.81 0 0 1-1.994.363 1.81 1.81 0 0 1-1.094-1.655v-.076A1.192 1.192 0 0 0 12 3.405Zm-1.483-.29a2.097 2.097 0 0 1 3.58 1.482v.073a.905.905 0 0 0 .549.828l.004.002a.905.905 0 0 0 .997-.18l.047-.047A2.098 2.098 0 1 1 18.66 8.24l-.047.048a.905.905 0 0 0-.18.996c.02.045.033.093.037.142a.904.904 0 0 0 .793.477h.139a2.098 2.098 0 0 1 0 4.194h-.073a.905.905 0 0 0-.828.549l-.002.004a.905.905 0 0 0 .18.997l.047.047a2.097 2.097 0 0 1-1.483 3.582 2.097 2.097 0 0 1-1.484-.615l-.048-.047a.906.906 0 0 0-.996-.18l-.004.003a.905.905 0 0 0-.549.827v.139a2.098 2.098 0 0 1-4.195 0v-.069a.905.905 0 0 0-.618-.834.906.906 0 0 0-.997.18l-.001.001-.046.046a2.097 2.097 0 0 1-3.423-.68 2.098 2.098 0 0 1 .456-2.287l.047-.048a.905.905 0 0 0 .18-.996l-.003-.004a.905.905 0 0 0-.827-.549h-.139a2.098 2.098 0 0 1 0-4.195h.069A.905.905 0 0 0 5.5 9.35a.905.905 0 0 0-.18-.997l-.047-.047A2.098 2.098 0 1 1 8.24 5.34l.048.047a.905.905 0 0 0 .996.18.453.453 0 0 1 .142-.037.905.905 0 0 0 .477-.793v-.139c0-.556.22-1.09.614-1.483Z"
			clipRule="evenodd"
		/>
	</chakra.svg>
));

export const Meter: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path
			fillRule="evenodd"
			d="M16.425 9.576a.6.6 0 0 1 0 .848l-4 4a.6.6 0 0 1-.849-.848l4-4a.6.6 0 0 1 .849 0Z"
			clipRule="evenodd"
		/>
		<path
			fillRule="evenodd"
			d="M12 4.6a9.4 9.4 0 0 0-8.14 14.1.6.6 0 0 1-1.04.6 10.6 10.6 0 1 1 18.36 0 .6.6 0 0 1-1.04-.6A9.4 9.4 0 0 0 12 4.6Z"
			clipRule="evenodd"
		/>
	</chakra.svg>
));

export const ArrowsRepeat: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path
			fillRule="evenodd"
			d="M11.998 2.4H12a9.6 9.6 0 0 1 9.6 9.6.6.6 0 1 1-1.2 0 8.4 8.4 0 0 0-8.398-8.4A9.15 9.15 0 0 0 5.68 6.168L3.425 8.425a.6.6 0 0 1-.849-.849L4.843 5.31a10.35 10.35 0 0 1 7.155-2.91Z"
			clipRule="evenodd"
		/>
		<path
			fillRule="evenodd"
			d="M3 2.4a.6.6 0 0 1 .6.6v4.4H8a.6.6 0 0 1 0 1.2H3a.6.6 0 0 1-.6-.6V3a.6.6 0 0 1 .6-.6Zm0 9a.6.6 0 0 1 .6.6 8.4 8.4 0 0 0 8.4 8.4 9.15 9.15 0 0 0 6.32-2.567l2.256-2.257a.6.6 0 0 1 .849.849l-2.268 2.267a10.35 10.35 0 0 1-7.154 2.908H12A9.6 9.6 0 0 1 2.4 12a.6.6 0 0 1 .6-.6Z"
			clipRule="evenodd"
		/>
		<path
			fillRule="evenodd"
			d="M15.4 16a.6.6 0 0 1 .6-.6h5a.6.6 0 0 1 .6.6v5a.6.6 0 1 1-1.2 0v-4.4H16a.6.6 0 0 1-.6-.6Z"
			clipRule="evenodd"
		/>
	</chakra.svg>
));

export const ArrowsUpDown: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 30 28"
		fill="none"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path d="M17.295 19.794a.498.498 0 0 1-.354-.146l-4.706-4.706a.5.5 0 1 1 .707-.707l3.854 3.854V4a.5.5 0 1 1 1 0v14.085l3.85-3.85a.5.5 0 0 1 .708.707l-4.68 4.678a.5.5 0 0 1-.379.174ZM6.325 3.675a.499.499 0 0 1 .734-.028l4.706 4.706a.5.5 0 1 1-.707.707L7.204 5.206v14.09a.5.5 0 1 1-1 0V5.21l-3.85 3.85a.5.5 0 0 1-.708-.707l4.68-4.678Z" />
	</chakra.svg>
));

export const Links: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path d="M7.5 22c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l8.3-8.3-8.3-8.3c-.4-.4-.4-1 0-1.4s1-.4 1.4 0l9 9c.4.4.4 1 0 1.4l-9 9c-.2.2-.4.3-.7.3z" />
	</chakra.svg>
));

export const ArrowRight: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 18 18"
		fill="none"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path
			stroke="currentColor"
			strokeLinecap="round"
			strokeLinejoin="round"
			strokeWidth="2"
			d="m6.75 13.5 4.5-4.5-4.5-4.5"
		/>
	</chakra.svg>
));

export const DotsHorizontal: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		strokeWidth="0"
		{...props}
		ref={ref}
	>
		<path d="M12 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm6 0c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zM6 10c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"></path>
	</chakra.svg>
));

export const Logo: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		width="10"
		height="10"
		viewBox="0 0 24 24"
		fill="none"
		xmlns="http://www.w3.org/2000/svg"
		aria-hidden="true"
		focusable="false"
	>
		<rect width="24" height="24" fill="white"></rect>
		<path
			d="M12 18.7957H4.60217V5.20432L12 9.82797V18.7957Z"
			fill="#690AA0"
		></path>
		<path
			d="M19.4194 18.7957H12V9.82797L19.4194 5.20432V18.7957Z"
			fill="#8D42CE"
		></path>
		<path
			d="M24 24H0V0H24V24ZM1.07527 22.9247H22.9247V1.07527H1.07527V22.9247Z"
			fill="#690AA0"
		></path>
	</chakra.svg>
));

export const ArrowRightFill: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		fill="none"
		{...props}
		ref={ref}
	>
		<path d="M22 12A10 10 0 1 1 12 2a10 10 0 0 1 10 10Zm-6.29-6.5a1 1 0 0 0-1.42 0l-6 6a1 1 0 0 0 0 1.42l6 6a1 1 0 0 0 1.42-1.42l-5.3-5.29 5.3-5.29a1 1 0 0 0 0-1.42Z" />
	</chakra.svg>
));

export const QuestionCircleFill: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 16 16"
		xmlns="https://www.w3.org/2000/svg"
		strokeWidth="0"
		{...props}
		ref={ref}
	>
		<path d="M8 1.332a6.667 6.667 0 1 0 0 13.333A6.667 6.667 0 0 0 8 1.332Zm0 10.333a.667.667 0 1 1 0-1.333.667.667 0 0 1 0 1.333Zm.16-2.433a.793.793 0 0 1-.214.033.675.675 0 1 1-.206-1.333c.433-.147 1.333-.627 1.333-1.167a1.133 1.133 0 0 0-2.2-.38A.667.667 0 0 1 5.62 6a2.467 2.467 0 0 1 4.793.82c0 1.613-2.02 2.333-2.253 2.413Z" />
	</chakra.svg>
));

export const Buttons: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path d="M19.27 12.91H4.73a1.83 1.83 0 0 0-1.82 1.82v5.45A1.83 1.83 0 0 0 4.73 22h14.54a1.83 1.83 0 0 0 1.82-1.82v-5.45a1.83 1.83 0 0 0-1.82-1.82Zm0 7.27H4.73v-5.45h14.54Z" />
		<path d="M7.45 18.36h9.1a.91.91 0 0 0 0-1.81h-9.1a.91.91 0 0 0 0 1.81ZM19.27 2H4.73a1.83 1.83 0 0 0-1.82 1.82v5.45a1.83 1.83 0 0 0 1.82 1.82h14.54a1.83 1.83 0 0 0 1.82-1.82V3.82A1.83 1.83 0 0 0 19.27 2Zm0 7.27H4.73V3.82h14.54Z" />
		<path d="M7.45 7.45h9.1a.85.85 0 0 0 .9-.9.85.85 0 0 0-.9-.91h-9.1a.85.85 0 0 0-.9.91.85.85 0 0 0 .9.9Z" />
	</chakra.svg>
));

export const ThreeCircleNodes: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path
			fillRule="evenodd"
			d="M12 15.6a2.4 2.4 0 1 0 0 4.8 2.4 2.4 0 0 0 0-4.8ZM8.4 18a3.6 3.6 0 1 1 7.2 0 3.6 3.6 0 0 1-7.2 0ZM6 3.6a2.4 2.4 0 1 0 0 4.8 2.4 2.4 0 0 0 0-4.8ZM2.4 6a3.6 3.6 0 1 1 7.2 0 3.6 3.6 0 0 1-7.2 0ZM18 3.6a2.4 2.4 0 1 0 0 4.8 2.4 2.4 0 0 0 0-4.8ZM14.4 6a3.6 3.6 0 1 1 7.2 0 3.6 3.6 0 0 1-7.2 0Z"
			clipRule="evenodd"
		/>
		<path
			fillRule="evenodd"
			d="M6 8.4a.6.6 0 0 1 .6.6v1A1.4 1.4 0 0 0 8 11.4h8a1.4 1.4 0 0 0 1.4-1.4V9a.6.6 0 1 1 1.2 0v1a2.6 2.6 0 0 1-2.6 2.6H8A2.6 2.6 0 0 1 5.4 10V9a.6.6 0 0 1 .6-.6Z"
			clipRule="evenodd"
		/>
		<path
			fillRule="evenodd"
			d="M12 11.4a.6.6 0 0 1 .6.6v3a.6.6 0 1 1-1.2 0v-3a.6.6 0 0 1 .6-.6Z"
			clipRule="evenodd"
		/>
	</chakra.svg>
));

export const Countdown: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		xmlSpace="preserve"
		{...props}
		ref={ref}
	>
		{/* eslint-disable-next-line */}
		<path d="m20.1 8.8.9-.9c.4-.4.4-1 0-1.4-.4-.4-1-.4-1.4 0l-1 1c-1-.6-2.3-1-3.6-1-3.9 0-7 3.1-7 7.1 0 3.7 3.2 6.9 6.9 6.9 3.9 0 7.1-3.1 7.1-7 0-1.8-.7-3.4-1.9-4.7zM15 18.5c-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5-2.2 5-5 5zm0-8c.6 0 1 .4 1 1v2c0 .5-.4 1-1 1-.5 0-1-.4-1-1v-2c0-.6.4-1 1-1zm-1-7h2c.5 0 1 .4 1 1 0 .5-.4 1-1 1h-2c-.5 0-1-.4-1-1s.4-1 1-1zm-10 5h2c.5 0 1 .4 1 1 0 .5-.4 1-1 1H4c-.5 0-1-.4-1-1s.4-1 1-1zm0 8h2c.5 0 1 .4 1 1 0 .5-.4 1-1 1H4c-.5 0-1-.4-1-1s.4-1 1-1zm-1-4h2c.5 0 1 .4 1 1 0 .5-.4 1-1 1H3c-.5 0-1-.4-1-1s.4-1 1-1z" />
	</chakra.svg>
));

export const Counter: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		xmlSpace="preserve"
		{...props}
		ref={ref}
	>
		{/* eslint-disable-next-line */}
		<path d="M19.664 17.739V7.646l1.316 1.316a.575.575 0 0 0 .812-.811l-2.296-2.295a.573.573 0 0 0-.403-.169h-.005a.572.572 0 0 0-.406.169l-2.294 2.295a.573.573 0 1 0 .812.811l1.315-1.316v10.092a.574.574 0 0 0 1.149.001zm-15.64-.866a.58.58 0 0 1-.42-.16.527.527 0 0 1-.165-.396v-4.603l.128.201-.667.493a.502.502 0 0 1-.32.101.526.526 0 0 1-.383-.164.537.537 0 0 1-.165-.393c0-.195.094-.353.283-.475L3.63 10.6a.6.6 0 0 1 .206-.091.929.929 0 0 1 .214-.027.56.56 0 0 1 .42.16.548.548 0 0 1 .155.397v5.279c0 .158-.057.29-.169.396a.604.604 0 0 1-.432.159zm6.018-1.005a.496.496 0 0 1 .511.511.472.472 0 0 1-.146.352.502.502 0 0 1-.365.142H6.937a.493.493 0 0 1-.375-.146c-.091-.097-.137-.222-.137-.374s.055-.286.164-.401l1.991-2.129c.225-.242.403-.492.534-.748s.197-.478.197-.667c0-.304-.088-.549-.265-.735s-.411-.278-.703-.278a.86.86 0 0 0-.356.082 1.467 1.467 0 0 0-.352.228c-.113.098-.218.21-.315.338a.482.482 0 0 1-.219.183.547.547 0 0 1-.571-.113.459.459 0 0 1-.169-.352c0-.104.035-.204.105-.302a2.763 2.763 0 0 1 1.355-.972c.2-.064.396-.096.584-.096.408 0 .763.081 1.064.242s.534.39.699.685c.164.295.247.644.247 1.045 0 .335-.099.708-.297 1.119a4.647 4.647 0 0 1-.799 1.155l-1.233 1.315-.1-.083h2.056zm2.493-.283a.662.662 0 0 1 .393.137c.104.079.227.149.369.21.144.061.311.091.498.091.189 0 .367-.047.535-.142a1.138 1.138 0 0 0 .58-1.027c0-.23-.049-.423-.143-.575s-.219-.268-.375-.347a1.073 1.073 0 0 0-.496-.118c-.135 0-.246.012-.334.035-.088.025-.174.051-.256.078s-.178.041-.287.041a.389.389 0 0 1-.325-.146.546.546 0 0 1-.114-.346.51.51 0 0 1 .05-.229c.033-.067.083-.141.151-.22l1.607-1.708.246.21h-2.246a.496.496 0 0 1-.511-.511c0-.14.049-.257.146-.352a.502.502 0 0 1 .365-.142h2.795c.188 0 .33.052.424.155a.555.555 0 0 1 .143.393c0 .079-.021.157-.064.233s-.094.145-.154.206l-1.617 1.735-.246-.311c.066-.03.16-.058.277-.082a1.596 1.596 0 0 1 1.235.242c.264.186.469.426.611.721s.215.613.215.954c0 .451-.102.841-.303 1.169a1.966 1.966 0 0 1-.848.758c-.365.177-.793.266-1.279.266-.226 0-.447-.028-.667-.083a2.213 2.213 0 0 1-.566-.219.626.626 0 0 1-.27-.242.555.555 0 0 1-.068-.251c0-.14.05-.272.151-.397a.465.465 0 0 1 .378-.186z" />
	</chakra.svg>
));

export const Section: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path d="M4 2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zm16 5H4V4h16zM2 12v8a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-8a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2zm18 0v8H4v-8zm-9 5H9v-2h2v-2h2v2h2v2h-2v2h-2z" />
	</chakra.svg>
));

export const Heading: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="http://www.w3.org/2000/svg"
		{...props}
	>
		<path
			d="m17 19v-14a1 1 0 0 0-1-1 1 1 0 0 0-1 1v6h-6v-6a1 1 0 0 0-1-1 0.94 0.94 0 0 0-1 1v14a1 1 0 0 0 1 1 1 1 0 0 0 1-1v-6h6v6a1 1 0 0 0 1 1 0.94 0.94 0 0 0 1-1z"
			fill="#690aa0"
		/>
	</chakra.svg>
));

export const Advertisement: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		xmlns="http://www.w3.org/2000/svg"
		viewBox="0 0 24 24"
		{...props}
	>
		<path
			fill="#690aa0"
			d="M7.77 6.81c-.12.48-.24 1.09-.37 1.56l-.5 1.71h1.77l-.5-1.71c-.17-.48-.29-1.08-.4-1.56Zm7.4-.02a3.42 3.42 0 0 0-.81.07v5.27a2.89 2.89 0 0 0 .62 0 2.49 2.49 0 0 0 2.7-2.82 2.3 2.3 0 0 0-2.51-2.52Z"
		/>
		<path
			fill="#690aa0"
			d="M2 2v16h1.9l-.85 4 10.29-4H22V2Zm7.54 11.51-.61-2.07H6.64l-.57 2.07H4.2l2.44-8.06H9l2.47 8.06Zm8.74-1a5.69 5.69 0 0 1-3.69 1 15.22 15.22 0 0 1-2-.12V5.56A15.49 15.49 0 0 1 15 5.39a5.07 5.07 0 0 1 3.27.86 3.57 3.57 0 0 1 1.35 3 4.13 4.13 0 0 1-1.34 3.3Z"
		/>
	</chakra.svg>
));

export const BannerPosts: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		xmlns="http://www.w3.org/2000/svg"
		viewBox="0 0 24 24"
		{...props}
	>
		<path d="M1.97 1.98h8v20h-8zm10.97 0h3v20h-3zm6.04.01h3v9h-3zm0 10.99h3v9h-3z" />
	</chakra.svg>
));

export const GridModule: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		xmlns="http://www.w3.org/2000/svg"
		viewBox="0 0 24 24"
		{...props}
	>
		<path d="M2.18 1.93h4.6v4.6h-4.6zm7.51.02h4.6v4.6h-4.6zm7.51-.02h4.6v4.6h-4.6zM2.18 9.69h4.6v4.6h-4.6zm7.51.02h4.6v4.6h-4.6zm7.51-.02h4.6v4.6h-4.6zm-15.02 7.7h4.6v4.6h-4.6zm7.51.03h4.6v4.6h-4.6zm7.51-.03h4.6v4.6h-4.6z" />
	</chakra.svg>
));

export const FeaturedPosts: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="http://www.w3.org/2000/svg"
		{...props}
	>
		<path d="M1.93 2.08h20v12h-20zm.05 14.91h20v2h-20zm0 3.06h10v2h-10z" />
	</chakra.svg>
));

export const FeaturedCategories: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		xmlns="http://www.w3.org/2000/svg"
		viewBox="0 0 24 24"
		{...props}
	>
		<path d="M13.01 2h9v12h-9zm-10.99.06h9v12h-9zM2.03 16h9v2h-9zm10.98 0h9v2h-9zM2.03 19.98h5.46v2.07H2.03zm10.98 0h5.46v2.07h-5.46z" />
	</chakra.svg>
));

export const TabPost: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		xmlns="http://www.w3.org/2000/svg"
		viewBox="0 0 24 24"
		{...props}
	>
		<path d="M8.66 7.95V2.12H2v20h20V7.95H8.66z" />
		<path d="M16.19 3.78h5v2.51h-5zm-5.86 0h5v2.51h-5z" />
	</chakra.svg>
));

export const PostList: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		xmlns="http://www.w3.org/2000/svg"
		viewBox="0 0 24 24"
		{...props}
	>
		<path d="M1.99 1.98h8.5v8.5h-8.5zm11.52 1.26h8.5v2h-8.5zm0 3.98h4.25v2h-4.25zM1.99 13.47h8.5v8.5h-8.5zm11.52 1.25h8.5v2h-8.5zm0 3.99h4.25v2h-4.25z" />
	</chakra.svg>
));

export const PostVideo: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		xmlns="http://www.w3.org/2000/svg"
		viewBox="0 0 24 24"
		{...props}
	>
		<path
			fill="#690aa0"
			d="M2 8v14h20V8Zm8.08 11v-7l5.84 3.5ZM3 5.07h18v1.5H3zM4 2h16v1.5H4z"
		/>
	</chakra.svg>
));

export const CategoryList: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		xmlns="http://www.w3.org/2000/svg"
		viewBox="0 0 24 24"
		{...props}
	>
		<path d="M2.03 2.06h9v9h-9zm0 10.94h9v9h-9zM13 2h9v9h-9z" />
	</chakra.svg>
));

export const NewsTicker: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		xmlns="http://www.w3.org/2000/svg"
		viewBox="0 0 24 24"
		{...props}
	>
		<path
			fill="#690aa0"
			d="M2 2v20h20V2Zm14 6.88-4.86 10.33a.39.39 0 0 1-.37.22h-.13a.4.4 0 0 1-.23-.17.36.36 0 0 1 0-.27l1.76-7.21-3.62.9H8.4a.4.4 0 0 1-.28-.1.34.34 0 0 1-.12-.36l1.8-7.36a.39.39 0 0 1 .2-.21.41.41 0 0 1 .25-.08h2.92a.38.38 0 0 1 .29.12.34.34 0 0 1 .12.26.41.41 0 0 1 0 .16L12 9.24l3.54-.87a.26.26 0 0 1 .11 0 .41.41 0 0 1 .3.13.33.33 0 0 1 .05.38Z"
		/>
	</chakra.svg>
));

export const DateWeather: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		xmlns="http://www.w3.org/2000/svg"
		viewBox="0 0 24 24"
		{...props}
	>
		<path
			fill="#690aa0"
			d="M8 18.33a.51.51 0 0 0-.75.24L6 21.07a.66.66 0 0 0 .21.85.56.56 0 0 0 .27.08.53.53 0 0 0 .52-.32l1.27-2.5a.66.66 0 0 0-.27-.85Zm3.36 0a.52.52 0 0 0-.76.24l-1.26 2.5a.66.66 0 0 0 .2.85.57.57 0 0 0 .28.08.55.55 0 0 0 .48-.32l1.26-2.5a.67.67 0 0 0-.2-.85Zm-6.65 0a.51.51 0 0 0-.75.24l-1.27 2.5a.66.66 0 0 0 .21.85.51.51 0 0 0 .75-.24l1.27-2.5a.66.66 0 0 0-.21-.85Zm17.01-7.59c-2.44.52-4.67-1.57-4.67-4.33a4.54 4.54 0 0 1 2-3.83.28.28 0 0 0-.05-.49 4.78 4.78 0 0 0-.95-.09 5.31 5.31 0 0 0-5 5.39 4.17 4.17 0 0 1 2 2.41 4.31 4.31 0 0 1 2.43 3.39 4.6 4.6 0 0 0 .52.06 4.81 4.81 0 0 0 3.9-2.09.25.25 0 0 0-.18-.42Zm-7.04 7.59a.51.51 0 0 0-.75.24l-1.27 2.5a.66.66 0 0 0 .21.85.51.51 0 0 0 .75-.24l1.27-2.5a.66.66 0 0 0-.21-.85Zm-.48-7.52a2.89 2.89 0 0 0-2.72-2.56 2.54 2.54 0 0 0-1.19.31A3.22 3.22 0 0 0 7.6 7a3.56 3.56 0 0 0-3.32 3.75s0 0 0 .06a3.06 3.06 0 0 0-2.23 3.07A3 3 0 0 0 4.83 17h8.86a3 3 0 0 0 2.77-3.12 3 3 0 0 0-2.26-3.07Z"
		/>
	</chakra.svg>
));

export const SocialIcons: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		xmlns="http://www.w3.org/2000/svg"
		viewBox="0 0 24 24"
		{...props}
	>
		<path d="M6 15c.9 0 1.7-.3 2.4-.9l6.3 3.6c-.1.3-.1.6-.1.9 0 1.9 1.6 3.5 3.5 3.6 1.9 0 3.5-1.6 3.6-3.5 0-1.9-1.6-3.5-3.5-3.6-.9 0-1.8.3-2.4.9l-6.3-3.6c.1-.2.1-.5.1-.8l6.1-3.5c1.4 1.3 3.6 1.2 4.9-.2s1.2-3.6-.2-4.9-3.6-1.2-4.9.2c-.6.6-.9 1.5-.9 2.4 0 .3 0 .6.1.8L8.9 9.6c-1-1.6-3.2-2.1-4.8-1S2 11.8 3 13.4c.7 1 1.8 1.6 3 1.6zm12 2c.8 0 1.5.7 1.5 1.5S18.8 20 18 20s-1.5-.7-1.5-1.5.6-1.5 1.5-1.5zm0-13c.8 0 1.5.7 1.5 1.5S18.8 7 18 7s-1.5-.7-1.5-1.5S17.1 4 18 4zM6 10c.8 0 1.5.7 1.5 1.5S6.8 13 6 13s-1.5-.7-1.5-1.5S5.1 10 6 10z" />
	</chakra.svg>
));

export const Slider: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		xmlns="http://www.w3.org/2000/svg"
		xmlSpace="preserve"
		viewBox="0 0 24 24"
		{...props}
	>
		<path
			fill="#690aa0"
			d="M3.5 20H2V5h1.5v15zM22 5h-1.5v15H22V5zm-3-3H5v20h14V2z"
		></path>
	</chakra.svg>
));

export const Paragraph: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path d="M9.15 17h2.3v4.1a.9.9 0 0 0 .9.9.9.9 0 0 0 .9-.9v-17h2.8v17a.9.9 0 0 0 .9.9h.1a.9.9 0 0 0 .9-.9v-17h3.1a.9.9 0 0 0 .9-.9V3a.9.9 0 0 0-.9-.9h-12a7.2 7.2 0 0 0-7 7.5 7.2 7.2 0 0 0 7.1 7.4zm-.1-12.9h2.3V15h-2.2a5.79 5.79 0 0 1-5.1-5.5 5.69 5.69 0 0 1 5-5.45z" />
	</chakra.svg>
));

export const Spacing: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path d="M21 2H3a1 1 0 0 0-1 1 .94.94 0 0 0 1 1h18a.94.94 0 0 0 1-1 .94.94 0 0 0-1-1ZM2 21a.94.94 0 0 0 1 1h18a.94.94 0 0 0 1-1 .94.94 0 0 0-1-1H3a.94.94 0 0 0-1 1Zm7-6V9a.94.94 0 0 0-1-1 1 1 0 0 0-1 1v6a.94.94 0 0 0 1 1 1 1 0 0 0 1-1Zm8 0V9a.94.94 0 0 0-1-1 .94.94 0 0 0-1 1v6a.94.94 0 0 0 1 1 1.08 1.08 0 0 0 1-1Zm-4 2V7a.94.94 0 0 0-1-1 .94.94 0 0 0-1 1v10a.94.94 0 0 0 1 1 .94.94 0 0 0 1-1Z" />
	</chakra.svg>
));

export const Tabs: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path
			fillRule="evenodd"
			d="M6 10V4H4v16h16V10H6Zm16-2v14H2V2h6v6h14Z"
			clipRule="evenodd"
		/>
		<path
			fillRule="evenodd"
			d="M13.5 3.5h-3v2h3v-2ZM9 2v5h6V2H9Zm11.5 1.5h-3v2h3v-2ZM16 2v5h6V2h-6Z"
			clipRule="evenodd"
		/>
	</chakra.svg>
));

export const Lottie: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path d="M20 4v16H4V4h16m0-2H4a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zM7 18a1 1 0 0 1 0-2c1.66 0 2.856-2.177 4.124-4.482C12.616 8.805 14.159 6 17 6a1 1 0 0 1 0 2c-1.66 0-2.856 2.177-4.124 4.482C11.384 15.195 9.841 18 7 18z" />
	</chakra.svg>
));

export const InfoBox: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path d="M22 4V3c0-.6-.4-1-1-1H3c-.6 0-1 .4-1 1v18c0 .6.4 1 1 1h18c.6 0 1-.4 1-1V4zM4 4h16v16H4V4zm8 5.5a2 2 0 1 0 .001-3.999A2 2 0 0 0 12 9.5zm3 1H9c-.6 0-1 .4-1 1s.4 1 1 1h6c.6 0 1-.4 1-1s-.5-1-1-1zm-4 8h2c.6 0 1-.4 1-1s-.4-1-1-1h-2c-.6 0-1 .4-1 1 0 .5.4 1 1 1zm-5-4c0 .6.4 1 1 1h10c.6 0 1-.4 1-1s-.4-1-1-1H7c-.6 0-1 .5-1 1z" />
	</chakra.svg>
));

export const Image: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path d="M19.9 2.2H4.3c-1.2 0-2 .8-2 2v15.6c0 1.2.7 2.1 2 2.1h15.6c1.2 0 2-.8 2-2V4.3c-.1-1.3-.8-2.1-2-2.1zM4.3 19.8V4.2h15.6v15.6H4.3z" />
		<path d="m10.1 14-1-1-3 4h12l-5-7-3 4z" />
	</chakra.svg>
));

export const TableOfContents: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		{/* eslint-disable-next-line */}
		<path d="M16.8 9h-6.5c-.2 0-.3-.1-.3-.2V7.3c0-.2.1-.3.3-.3h6.5c.1 0 .2.1.2.3v1.5c0 .1-.1.2-.2.2zm-.8 3.8v-1.5c0-.1-.1-.3-.3-.3h-5.5c-.1 0-.3.1-.3.3v1.5c0 .1.1.3.3.3h5.5c.2-.1.3-.2.3-.3zm-2 4v-1.5c0-.1-.1-.3-.3-.3h-3.5c-.1 0-.3.1-.3.3v1.5c0 .1.1.3.3.3h3.5c.2-.1.3-.2.3-.3zm-5-8V7.3c0-.2-.1-.3-.2-.3H7.3c-.2 0-.3.1-.3.3v1.5c0 .1.1.2.3.2h1.5c.1 0 .2-.1.2-.2zm0 4v-1.5c0-.2-.1-.3-.2-.3H7.3c-.2 0-.3.1-.3.3v1.5c0 .1.1.2.3.2h1.5c.1 0 .2-.1.2-.2zm0 4v-1.5c0-.2-.1-.3-.2-.3H7.3c-.2 0-.3.1-.3.3v1.5c0 .1.1.2.3.2h1.5c.1 0 .2-.1.2-.2zm9 5.2H6c-1.7 0-3-1.3-3-3V5c0-1.7 1.3-3 3-3h12c1.7 0 3 1.3 3 3v14c0 1.7-1.3 3-3 3zM6 4c-.6 0-1 .4-1 1v14c0 .6.4 1 1 1h12c.6 0 1-.4 1-1V5c0-.6-.4-1-1-1H6z" />
	</chakra.svg>
));

export const SocialShare: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		{/* eslint-disable-next-line */}
		<path d="M6 15c.9 0 1.7-.3 2.4-.9l6.3 3.6c-.1.3-.1.6-.1.9 0 1.9 1.6 3.5 3.5 3.6 1.9 0 3.5-1.6 3.6-3.5 0-1.9-1.6-3.5-3.5-3.6-.9 0-1.8.3-2.4.9l-6.3-3.6c.1-.2.1-.5.1-.8l6.1-3.5c1.4 1.3 3.6 1.2 4.9-.2 1.3-1.4 1.2-3.6-.2-4.9-1.4-1.3-3.6-1.2-4.9.2-.6.6-.9 1.5-.9 2.4 0 .3 0 .6.1.8L8.9 9.6c-1-1.6-3.2-2.1-4.8-1C2.5 9.7 2 11.8 3 13.4c.7 1 1.8 1.6 3 1.6Zm12 2c.8 0 1.5.7 1.5 1.5S18.8 20 18 20s-1.5-.7-1.5-1.5.6-1.5 1.5-1.5Zm0-13c.8 0 1.5.7 1.5 1.5S18.8 7 18 7s-1.5-.7-1.5-1.5S17.1 4 18 4ZM6 10c.8 0 1.5.7 1.5 1.5S6.8 13 6 13s-1.5-.7-1.5-1.5S5.1 10 6 10Z" />
	</chakra.svg>
));

export const Team: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path
			fillRule="evenodd"
			// eslint-disable-next-line
			d="M6.81 14.425a4.077 4.077 0 0 1 2.882-1.194h4.616a4.077 4.077 0 0 1 4.076 4.077v1.538a1 1 0 0 1-2 0v-1.538a2.077 2.077 0 0 0-2.076-2.077H9.692a2.077 2.077 0 0 0-2.077 2.077v1.538a1 1 0 0 1-2 0v-1.538c0-1.082.43-2.119 1.194-2.883ZM12 6a2.077 2.077 0 1 0 0 4.154A2.077 2.077 0 0 0 12 6ZM7.923 8.077a4.077 4.077 0 1 1 8.154 0 4.077 4.077 0 0 1-8.154 0Zm10.801 5.903a1 1 0 0 1 1.218-.718A4.077 4.077 0 0 1 23 17.207v1.54a1 1 0 0 1-2 0v-1.539a2.077 2.077 0 0 0-1.558-2.009 1 1 0 0 1-.718-1.218Zm-2.308-9.228a1 1 0 0 1 1.217-.72 4.077 4.077 0 0 1 0 7.898 1 1 0 1 1-.496-1.937 2.077 2.077 0 0 0 0-4.024 1 1 0 0 1-.721-1.217ZM5.276 13.98a1 1 0 0 0-1.218-.718A4.078 4.078 0 0 0 1 17.207v1.54a1 1 0 0 0 2 0v-1.539A2.077 2.077 0 0 1 4.558 15.2a1 1 0 0 0 .718-1.218Zm2.308-9.228a1 1 0 0 0-1.217-.72 4.077 4.077 0 0 0 0 7.898 1 1 0 1 0 .496-1.937 2.077 2.077 0 0 1 0-4.024 1 1 0 0 0 .721-1.217Z"
			clipRule="evenodd"
		/>
	</chakra.svg>
));

export const CirclesInsideCircle: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		{/* eslint-disable-next-line  */}
		<path d="M11.402 2h1.196c.084.03.17.052.259.067a9.92 9.92 0 0 1 8.745 7.282c.181.67.266 1.368.398 2.053v1.196c-.03.084-.052.17-.067.259a9.923 9.923 0 0 1-7.282 8.745c-.672.18-1.368.266-2.053.398h-1.196a1.511 1.511 0 0 0-.259-.067 9.934 9.934 0 0 1-8.824-7.589c-.14-.574-.215-1.164-.319-1.746v-1.196c.03-.084.052-.17.067-.259A9.931 9.931 0 0 1 9.656 2.32c.575-.14 1.164-.215 1.746-.319Zm-8.545 9.986c-.01 4.965 4.045 9.071 8.88 9.17 5.1.104 9.271-3.97 9.41-8.883.145-5.13-3.99-9.295-8.905-9.422-5.103-.134-9.418 4.028-9.385 9.136Z" />
		{/* eslint-disable-next-line  */}
		<path d="M7.258 10.008c1.115.015 1.963.892 1.952 2.024-.011 1.124-.883 1.97-2.013 1.96a1.971 1.971 0 0 1-1.978-2.042c.021-1.11.91-1.957 2.039-1.942Zm1.154 2.005c.006-.697-.498-1.206-1.196-1.207-.68 0-1.182.486-1.195 1.163-.015.697.478 1.216 1.172 1.226.694.01 1.213-.488 1.219-1.182Zm5.58-.011a1.992 1.992 0 1 1-1.98-1.992 1.955 1.955 0 0 1 1.98 1.992Zm-.797 0c0-.696-.508-1.2-1.204-1.195a1.155 1.155 0 0 0-1.185 1.174c-.01.697.491 1.21 1.184 1.215.694.005 1.204-.5 1.204-1.195l.001.001Zm3.585-1.994a1.957 1.957 0 0 1 1.993 1.983c0 1.144-.868 2.01-2.014 2.002a1.992 1.992 0 0 1 .021-3.984Zm-.006 3.187c.697 0 1.203-.5 1.201-1.195a1.155 1.155 0 0 0-1.168-1.191c-.697-.012-1.213.486-1.22 1.178-.008.693.493 1.205 1.187 1.208Z" />
	</chakra.svg>
));

export const ExternalLink: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 12 12"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path
			fillRule="evenodd"
			d="M2.5 4a.5.5 0 0 0-.5.5V10a.5.5 0 0 0 .5.5H8a.5.5 0 0 0 .5-.5V7a.5.5 0 0 1 1 0v3A1.5 1.5 0 0 1 8 11.5H2.5A1.5 1.5 0 0 1 1 10V4.5A1.5 1.5 0 0 1 2.5 3h3a.5.5 0 0 1 0 1h-3ZM7 2a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 .5.5v3a.5.5 0 0 1-1 0V2.5H7.5A.5.5 0 0 1 7 2Z"
			clipRule="evenodd"
		/>
		<path
			fillRule="evenodd"
			d="M10.854 1.646a.5.5 0 0 1 0 .708l-5.5 5.5a.5.5 0 1 1-.708-.708l5.5-5.5a.5.5 0 0 1 .708 0Z"
			clipRule="evenodd"
		/>
	</chakra.svg>
));

export const Docs: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 12 12"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path
			fillRule="evenodd"
			d="M1.94 1.44A1.5 1.5 0 0 1 3 1h4.25a.5.5 0 0 1 .354.146l2.75 2.75a.5.5 0 0 1 .146.354v6.25A1.5 1.5 0 0 1 9 12H3a1.5 1.5 0 0 1-1.5-1.5v-8c0-.398.158-.78.44-1.06ZM3 2a.5.5 0 0 0-.5.5v8a.5.5 0 0 0 .5.5h6a.5.5 0 0 0 .5-.5V4.457L7.043 2H3Z"
			clipRule="evenodd"
		/>
		<path
			fillRule="evenodd"
			d="M7 1a.5.5 0 0 1 .5.5V4H10a.5.5 0 0 1 0 1H7a.5.5 0 0 1-.5-.5v-3A.5.5 0 0 1 7 1Z"
			clipRule="evenodd"
		/>
	</chakra.svg>
));

export const DocsLines: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 20 20"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path
			fillRule="evenodd"
			d="M3.292 1.624A2.417 2.417 0 0 1 5 .916h7.083a.75.75 0 0 1 .53.22l4.584 4.583c.14.14.22.331.22.53v10.417A2.417 2.417 0 0 1 15 19.083H5a2.417 2.417 0 0 1-2.417-2.417V3.333c0-.641.255-1.256.708-1.71ZM5 2.416a.917.917 0 0 0-.917.917v13.333a.917.917 0 0 0 .917.917h10a.917.917 0 0 0 .916-.917V6.56l-4.144-4.144H5.001Z"
			clipRule="evenodd"
		/>
		<path
			fillRule="evenodd"
			d="M11.666.916a.75.75 0 0 1 .75.75v4.25h4.25a.75.75 0 0 1 0 1.5h-5a.75.75 0 0 1-.75-.75v-5a.75.75 0 0 1 .75-.75Zm-5.75 9.917a.75.75 0 0 1 .75-.75h6.667a.75.75 0 0 1 0 1.5H6.666a.75.75 0 0 1-.75-.75Zm0 3.333a.75.75 0 0 1 .75-.75h6.667a.75.75 0 0 1 0 1.5H6.666a.75.75 0 0 1-.75-.75Zm0-6.666a.75.75 0 0 1 .75-.75h1.667a.75.75 0 1 1 0 1.5H6.666a.75.75 0 0 1-.75-.75Z"
			clipRule="evenodd"
		/>
	</chakra.svg>
));

export const Video: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 20 20"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path
			fillRule="evenodd"
			d="M4.167 3.25a.917.917 0 0 0-.917.917v11.666c0 .507.41.917.917.917h11.666c.507 0 .917-.41.917-.917V4.167a.917.917 0 0 0-.917-.917H4.167Zm-2.417.917A2.417 2.417 0 0 1 4.167 1.75h11.666a2.417 2.417 0 0 1 2.417 2.417v11.666a2.417 2.417 0 0 1-2.417 2.417H4.167a2.417 2.417 0 0 1-2.417-2.417V4.167Z"
			clipRule="evenodd"
		/>
		<path
			fillRule="evenodd"
			d="M7.146 6.005a.75.75 0 0 1 .77.037l5 3.333a.75.75 0 0 1 0 1.248l-5 3.334a.75.75 0 0 1-1.166-.624V6.666a.75.75 0 0 1 .396-.661ZM8.25 8.067v3.864L11.148 10 8.25 8.067Z"
			clipRule="evenodd"
		/>
	</chakra.svg>
));

export const Star: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 20 20"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path
			fillRule="evenodd"
			d="M10 .917a.75.75 0 0 1 .672.418l2.4 4.864 5.37.784a.75.75 0 0 1 .414 1.28l-3.884 3.783.917 5.344a.75.75 0 0 1-1.089.79L10 15.657l-4.802 2.525a.75.75 0 0 1-1.088-.79l.917-5.345-3.884-3.783a.75.75 0 0 1 .415-1.28L6.926 6.2l2.4-4.864A.75.75 0 0 1 10 .917Zm0 2.444L8.096 7.216a.75.75 0 0 1-.564.41l-4.256.622 3.079 2.998a.75.75 0 0 1 .216.664l-.726 4.236 3.804-2.001a.75.75 0 0 1 .698 0l3.805 2-.726-4.235a.75.75 0 0 1 .216-.664l3.078-2.998-4.255-.622a.75.75 0 0 1-.564-.41L9.999 3.36Z"
			clipRule="evenodd"
		></path>
	</chakra.svg>
));

export const Bulb: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 20 20"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path
			fillRule="evenodd"
			// eslint-disable-next-line
			d="M5.934 2.601a5.75 5.75 0 0 1 9.816 4.066c0 1.262-.483 2.551-1.483 3.46-.608.61-.907 1.064-1.032 1.687a.75.75 0 1 1-1.47-.294c.207-1.04.738-1.75 1.455-2.467a.726.726 0 0 1 .028-.027c.656-.59 1.002-1.461 1.002-2.359a4.25 4.25 0 0 0-8.5 0c0 .687.12 1.477 1.03 2.386.626.626 1.246 1.42 1.455 2.467a.75.75 0 1 1-1.47.294c-.125-.62-.504-1.159-1.045-1.7-1.257-1.257-1.47-2.467-1.47-3.447a5.75 5.75 0 0 1 1.684-4.066ZM6.75 15a.75.75 0 0 1 .75-.75h5a.75.75 0 0 1 0 1.5h-5a.75.75 0 0 1-.75-.75Zm.833 3.333a.75.75 0 0 1 .75-.75h3.334a.75.75 0 0 1 0 1.5H8.333a.75.75 0 0 1-.75-.75Z"
			clipRule="evenodd"
		></path>
	</chakra.svg>
));

export const Chat: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 32 32"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path
			fillRule="evenodd"
			d="M5.333 3.666c-.914 0-1.667.753-1.667 1.667v12.252l3.627-3.626A1 1 0 0 1 8 13.666h8A1.666 1.666 0 0 0 17.666 12V5.333A1.667 1.667 0 0 0 16 3.666H5.333ZM1.666 5.333a3.675 3.675 0 0 1 3.667-3.667H16a3.667 3.667 0 0 1 3.666 3.667V12A3.667 3.667 0 0 1 16 15.666H8.414l-5.04 5.04A1 1 0 0 1 1.666 20V5.333Z"
			clipRule="evenodd"
		></path>
		<path
			fillRule="evenodd"
			d="M23 12a1 1 0 0 1 1-1h2.667a3.667 3.667 0 0 1 3.666 3.667v14.666a1 1 0 0 1-1.707.708L23.586 25H16a3.667 3.667 0 0 1-3.667-3.668V20a1 1 0 1 1 2 0v1.333A1.667 1.667 0 0 0 16 23h8a1 1 0 0 1 .707.293l3.626 3.626V14.667A1.667 1.667 0 0 0 26.667 13H24a1 1 0 0 1-1-1Z"
			clipRule="evenodd"
		></path>
	</chakra.svg>
));

export const UR: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 40 40"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path
			fill="#475BB2"
			d="M0 3.636A3.636 3.636 0 0 1 3.636 0h32.728A3.636 3.636 0 0 1 40 3.636v32.728A3.636 3.636 0 0 1 36.364 40H3.636A3.636 3.636 0 0 1 0 36.364V3.636Z"
		/>
		<path
			fill="#fff"
			// eslint-disable-next-line
			d="M28.99 10.623c-1.45.852-2.813 1.96-4.006 3.154-1.194 1.193-2.301 2.556-3.154 3.92a27.715 27.715 0 0 1 1.534 3.665c.767-1.62 1.705-3.068 2.898-4.347v4.602a6.29 6.29 0 0 1-4.602 6.052h-.17c-.171-2.216-.682-4.347-1.45-6.393a21.151 21.151 0 0 0-5.028-7.5c-1.193-1.193-2.557-2.215-4.006-3.153l-.34-.17v11.08c0 4.516 3.238 8.437 7.755 9.204h.085c1.023.17 2.046.17 2.983 0h.086c4.517-.767 7.755-4.688 7.755-9.205v-11.08l-.34.171Zm-15.256 10.91v-4.518c2.642 2.983 4.261 6.648 4.602 10.568a6.29 6.29 0 0 1-4.602-6.05Zm6.221-12.444a2.292 2.292 0 0 0-2.3 2.301 2.292 2.292 0 0 0 2.3 2.301 2.292 2.292 0 0 0 2.301-2.3 2.292 2.292 0 0 0-2.3-2.302Z"
		/>
	</chakra.svg>
));

export const MagazineBlocks: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 40 40"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<rect width="40" height="40" fill="#8D42CE" rx="3.636" />
		<path
			fill="#fff"
			d="M19.781 26.7h-7.025V13.797l7.025 4.387V26.7Zm7.01 0h-7.01v-8.517l7.01-4.387V26.7Z"
		/>
		<path
			fill="#fff"
			d="M30.077 30.576h-21V9.5h21v21.076Zm-20.05-.95h19.115V10.45H10.027v19.176Z"
		/>
	</chakra.svg>
));

export const EVF: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 40 40"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<rect width="40" height="40" fill="#7545BB" rx="3.636" />
		<path
			fill="#fff"
			d="M26.72 11h-4.31l1.32 2.224h4.309L26.72 11Zm2.71 4.447h-4.308l1.39 2.224h4.308l-1.39-2.224Zm-.069 11.119h-16.4l6.88-11.258 2.849 4.587h-2.85l-1.32 2.223h8.13l-6.81-10.98L9 28.79h21.751l-1.39-2.223Z"
		/>
	</chakra.svg>
));

export const Headphones: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 20 20"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path
			fillRule="evenodd"
			// eslint-disable-next-line
			d="M10 3.25A6.75 6.75 0 0 0 3.25 10v.917H5a2.417 2.417 0 0 1 2.417 2.416v2.5A2.417 2.417 0 0 1 5 18.25h-.833a2.417 2.417 0 0 1-2.417-2.417V10a8.25 8.25 0 1 1 16.5 0v5.833a2.417 2.417 0 0 1-2.417 2.417H15a2.417 2.417 0 0 1-2.417-2.417v-2.5A2.416 2.416 0 0 1 15 10.917h1.75V10A6.75 6.75 0 0 0 10 3.25Zm6.75 9.167H15a.916.916 0 0 0-.917.916v2.5a.917.917 0 0 0 .917.917h.833a.917.917 0 0 0 .917-.917v-3.416Zm-13.5 0v3.416a.917.917 0 0 0 .917.917H5a.917.917 0 0 0 .917-.917v-2.5A.916.916 0 0 0 5 12.417H3.25Z"
			clipRule="evenodd"
		/>
	</chakra.svg>
));

export const Masteriyo: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 40 40"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<rect width="40" height="40" fill="#E9E3FD" rx="3.636" />
		<path
			fill="#787DFF"
			d="m14.163 11.898 3.851 4.281c.607.64.946 1.489.946 2.37v10.009l-4.8-3.353.003-13.307Z"
			opacity=".3"
		/>
		<path
			fill="#787DFF"
			d="m11.173 11.3 6.346 4.519a3.373 3.373 0 0 1 1.438 2.559V28.55l-7.785-4.102.001-13.148Z"
			opacity=".5"
		/>
		<path
			fill="#787DFF"
			d="m18.96 28.656-7.014-2.245A3.48 3.48 0 0 1 9.5 23.305V12.953l7.62 2.943a2.831 2.831 0 0 1 1.835 2.452v.16l.005 10.148Z"
		/>
		<path
			fill="#FD739C"
			d="m25.837 11.898-3.851 4.281a3.444 3.444 0 0 0-.946 2.364v10.008l4.8-3.353-.003-13.3Z"
			opacity=".3"
		/>
		<path
			fill="#FD739C"
			d="m28.828 11.3-6.346 4.519a3.374 3.374 0 0 0-1.438 2.559V28.55l7.785-4.102-.001-13.148Z"
			opacity=".5"
		/>
		<path
			fill="#FD739C"
			d="m21.04 28.656 7.013-2.245c1.373-.44 2.377-1.667 2.447-3.106V12.953l-7.617 2.943a2.832 2.832 0 0 0-1.836 2.452v.16l-.007 10.148Z"
		/>
	</chakra.svg>
));

export const Blockquote: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path
			xmlns="http://www.w3.org/2000/svg"
			fillRule="evenodd"
			d="M2 6a1 1 0 0 1 1-1h14a1 1 0 1 1 0 2H3a1 1 0 0 1-1-1Zm5 6a1 1 0 0 1 1-1h13a1 1 0 1 1 0 2H8a1 1 0 0 1-1-1Zm0 6a1 1 0 0 1 1-1h13a1 1 0 1 1 0 2H8a1 1 0 0 1-1-1Zm-4-7a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1Z"
			clipRule="evenodd"
		/>
	</chakra.svg>
));

export const Timeline: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path d="M11 3a1 1 0 1 1 2 0v18a1 1 0 1 1-2 0V3Z"></path>
		<path
			fillRule="evenodd"
			d="M16.037 7.397 15 6.517l.953-.789c.262-.436.74-.728 1.286-.728H20.5a1.5 1.5 0 0 1 0 3h-3.261c-.492 0-.929-.237-1.202-.603Zm0 11L15 17.517l1.025-.898A1.498 1.498 0 0 1 17.239 16H20.5a1.5 1.5 0 0 1 0 3h-3.261c-.492 0-.929-.237-1.202-.603ZM8 12.847l1-.83-.966-.811A1.499 1.499 0 0 0 6.76 10.5H3.5a1.5 1.5 0 0 0 0 3h3.261c.514 0 .968-.259 1.238-.653Z"
			clipRule="evenodd"
		></path>
	</chakra.svg>
));

export const Progress: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path
			fillRule="evenodd"
			d="M20 9.7H4a.3.3 0 0 0-.3.3v4a.3.3 0 0 0 .3.3h16a.3.3 0 0 0 .3-.3v-4a.3.3 0 0 0-.3-.3ZM4 8a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2H4Z"
			clipRule="evenodd"
		></path>
		<path d="M3 9h7v6H3V9Z"></path>
	</chakra.svg>
));

export const CTA: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path
			fillRule="evenodd"
			d="M3 5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2h-4v-2h4V5H5v7h7.5v2H5a2 2 0 0 1-2-2V5Z"
			clipRule="evenodd"
		></path>
		<path
			fillRule="evenodd"
			d="M12.046 9.848a.699.699 0 0 1 .786.124l7.25 6.897a.703.703 0 0 1 .158.793.696.696 0 0 1-.692.413l-3.638-.292-2.085 3.045a.696.696 0 0 1-.753.28.703.703 0 0 1-.52-.617l-.897-9.95a.699.699 0 0 1 .392-.693Zm1.168 2.417.556 6.164 1.215-1.774a.696.696 0 0 1 .63-.301l2.072.166-4.473-4.255Z"
			clipRule="evenodd"
		></path>
		<path d="M7 8.25c0 .45.333.75.833.75h8.334c.5 0 .833-.3.833-.75s-.333-.75-.833-.75H7.833c-.5 0-.833.375-.833.75Z"></path>
	</chakra.svg>
));

export const Map: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path d="M12 14c2.206 0 4-1.794 4-4s-1.794-4-4-4-4 1.794-4 4 1.794 4 4 4Zm0-6c1.103 0 2 .897 2 2s-.897 2-2 2-2-.897-2-2 .897-2 2-2Z"></path>
		<path d="M11.42 21.814a.998.998 0 0 0 1.16 0C12.884 21.599 20.029 16.44 20 10c0-4.411-3.589-8-8-8S4 5.589 4 9.995c-.029 6.445 7.116 11.604 7.42 11.819ZM12 4c3.309 0 6 2.691 6 6.005.021 4.438-4.388 8.423-6 9.73-1.611-1.308-6.021-5.294-6-9.735 0-3.309 2.691-6 6-6Z"></path>
	</chakra.svg>
));

export const Testimonial: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path d="M20.2 1.5H4.8c-1.2 0-2.2 1-2.2 2.2v12.9c0 1.2 1 2.2 2.2 2.2h3.9l3.8 3.7 3.8-3.7h3.9c1.2 0 2.2-1 2.2-2.2V3.7c0-1.2-1-2.2-2.2-2.2Zm0 15.1h-4.9l-2.8 2.8-2.8-2.8H4.8V3.7h15.4v12.9Z"></path>
		<path d="M8 11.6a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm4.5 0a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Zm4.5 0a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3Z"></path>
	</chakra.svg>
));

export const Notice: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		ref={ref}
	>
		<path
			fillRule="evenodd"
			d="M12 3a9 9 0 1 0 0 18 9 9 0 0 0 0-18ZM1 12C1 5.925 5.925 1 12 1s11 4.925 11 11-4.925 11-11 11S1 18.075 1 12Z"
			clipRule="evenodd"
		/>
		<path
			fillRule="evenodd"
			d="M12 7a1 1 0 0 1 1 1v4a1 1 0 0 1-2 0V8a1 1 0 0 1 1-1Zm-1 9a1 1 0 0 1 1-1h.01a1 1 0 0 1 0 2H12a1 1 0 0 1-1-1Z"
			clipRule="evenodd"
		/>
	</chakra.svg>
));

export const Megaphone: React.ForwardRefExoticComponent<
	IconProps & React.RefAttributes<SVGSVGElement>
> = React.forwardRef((props, ref) => (
	<chakra.svg
		viewBox="0 0 42 42"
		xmlns="https://www.w3.org/2000/svg"
		{...props}
		fill="none"
		ref={ref}
	>
		<rect width="41" height="41" x=".5" y=".5" fill="#fff" rx="19.5" />
		<rect width="41" height="41" x=".5" y=".5" stroke="#DDD" rx="19.5" />
		<g clipPath="url(#a)">
			<path
				fill="#fff"
				d="M22.394 19.247c-1.098-4.806-.549-9.232 1.176-11.163a1.054 1.054 0 0 0-.502.242l-6.584 5.768-2.23 1.959a6.76 6.76 0 0 0-.497.816c-.66 1.276-1.39 2.887-1.157 4.451.336 2.276 2.13 4.059 3.91 5.049.275.153.56.285.855.395l2.372.359 8.4 1.645.126-.024c-2.38-.995-4.777-4.713-5.87-9.497Z"
			/>
			<path
				stroke="#E2E2E2"
				strokeMiterlimit="10"
				strokeWidth=".068"
				d="m30.987 28.236-2.726.509c-2.38-.991-4.775-4.713-5.868-9.497-1.098-4.806-.549-9.23 1.176-11.162l2.069-.38 5.349 20.53Z"
			/>
			<path
				fill="#E2E2E2"
				d="m13.812 23.726 9.819-.648 4.63 5.644-11.227-2.053s-3.132-.806-3.222-2.943Z"
			/>
			<path
				fill="#2E5DAD"
				d="M30.078 28.938a2.933 2.933 0 0 1-1.817-.193c-2.38-.991-4.775-4.713-5.868-9.497-1.098-4.806-.549-9.23 1.176-11.162.398-.47.932-.802 1.526-.952 2.868-.668 6.308 3.674 7.685 9.69 1.377 6.017.168 11.444-2.702 12.114Z"
			/>
			<path
				stroke="#fff"
				strokeMiterlimit="10"
				strokeWidth=".068"
				d="M22.394 19.247c-1.098-4.806-.549-9.232 1.176-11.163l-.284.052-6.802 5.958-2.23 1.959a6.76 6.76 0 0 0-.497.816c-.66 1.276-1.39 2.887-1.157 4.451.336 2.276 2.13 4.059 3.91 5.049.275.153.56.285.855.395l.631.013 1.744.341 8.4 1.646.127-.024c-2.385-.99-4.78-4.709-5.873-9.493Z"
			/>
			<path
				fill="#CBDEFA"
				d="M30.415 27.887c-.979.228-2.078-.456-3.101-1.776-.728-.935-1.419-2.19-2.003-3.663a23.67 23.67 0 0 1-1.069-3.465 24.559 24.559 0 0 1-.485-2.906 18.492 18.492 0 0 1-.056-3.417c.206-2.421.983-4.122 2.225-4.413 2.17-.505 4.932 3.48 6.171 8.904 1.24 5.423.486 10.23-1.683 10.736Z"
			/>
			<path
				fill="#B8C9E4"
				d="M28.982 25.042c-.492.741-1.243.984-1.668 1.066-1.249-1.606-2.388-4.15-3.068-7.132-.535-2.315-.694-4.519-.541-6.323.419-.416 1.069-.686 1.136-.686 1.71 0 3.448 2.567 4.31 6.341.657 2.889.549 5.507-.169 6.734Z"
			/>
			<path
				fill="#fff"
				d="m27.94 20.402-2.63 2.045a23.67 23.67 0 0 1-1.07-3.464 24.559 24.559 0 0 1-.485-2.907l3.116-.104v.018l1.056 4.376.013.036Z"
			/>
			<path
				fill="#2E5DAD"
				d="m14.253 16.08-4.918 2.161s-3.207 1.513-1.71 6.915c0 0 1.817 4.539 5.132 3.134l4.32-1.641S9.87 23.21 14.252 16.08Z"
			/>
			<path
				fill="#CEDAF7"
				d="M27.71 20.494c.522-.122.716-1.23.432-2.473-.284-1.244-.939-2.153-1.462-2.031-.523.122-.716 1.23-.432 2.473.284 1.244.938 2.153 1.461 2.031Z"
			/>
			<path
				stroke="#2E5DAD"
				strokeMiterlimit="10"
				strokeWidth=".137"
				d="M22.914 19.214c-1.098-4.806.856-11.237.856-11.237-.253.07-.49.19-.7.35l-6.584 5.767-2.232 1.96c-.185.26-.35.532-.497.815-.66 1.276-1.39 2.887-1.157 4.452.336 2.275 2.13 4.058 3.91 5.048.275.154.56.286.855.396l2.372.358 8.272 1.621c.19.003.382-.005.572-.022-2.379-.99-4.574-4.725-5.667-9.508Z"
			/>
			<path
				fill="#3C5385"
				d="m8.053 26.008 5.757-2.281s1.727 2.51 3.224 2.942l-4.672 1.762s-2.599 1.021-4.31-2.424Z"
			/>
			<path
				fill="#C9DDFB"
				d="m12.361 28.432 2 6.109c.125.381.206.821.778.784a1.36 1.36 0 0 1 .278.011l.107.017a.86.86 0 0 1 .673.53c.148.371.252.864-.126 1.051l.346-.027c.227-.017.441-.11.61-.265l.513-.466a.799.799 0 0 0 .25-.735 2.522 2.522 0 0 0-.067-.282.698.698 0 0 0-.582-.494 9.618 9.618 0 0 0-.573-.046.637.637 0 0 1-.578-.448l-.997-3.115-1.969-2.928-.663.304Z"
			/>
			<path
				fill="#2E5DAD"
				d="m15.638 30.794-.033.1-.014.044-1.604.432-.934-3.188 2.002-.756v.007l.577 1.713a2.62 2.62 0 0 1 .006 1.648Z"
			/>
			<path
				fill="#3E5589"
				d="m15.644 30.792-.034.108-1.57.416c.21-.634.228-1.317.054-1.961l-.374-1.389 1.337-.54.576 1.712c.183.535.186 1.116.011 1.654Z"
			/>
		</g>
		<defs>
			<clipPath id="a">
				<path fill="#fff" d="M7 7h27v30H7z" />
			</clipPath>
		</defs>
	</chakra.svg>
));

ArrowRight.displayName = "ArrowRight";
Megaphone.displayName = "Megaphone";
Notice.displayName = "Notice";
Testimonial.displayName = "Testimonial";
Map.displayName = "Map";
Slider.displayName = "Slider";
CTA.displayName = "CTA";
Progress.displayName = "Progress";
Timeline.displayName = "Timeline";
Blockquote.displayName = "Blockquote";
Headphones.displayName = "Headphones";
EVF.displayName = "EVF";
MagazineBlocks.displayName = "MagazineBlocks";
Masteriyo.displayName = "Masteriyo";
UR.displayName = "UR";
Chat.displayName = "Chat";
Bulb.displayName = "Bulb";
Star.displayName = "Star";
Video.displayName = "Video";
DocsLines.displayName = "DocsLines";
Docs.displayName = "Docs";
ExternalLink.displayName = "ExternalLink";
CirclesInsideCircle.displayName = "CirclesInsideCircle";
DotsHorizontal.displayName = "DotsHorizontal";
Search.displayName = "Search";
Cog.displayName = "Cog";
Meter.displayName = "Meter";
ArrowsRepeat.displayName = "ArrowsRepeat";
ArrowsUpDown.displayName = "ArrowsUpDown";
Links.displayName = "Links";
Logo.displayName = "Logo";
ArrowRightFill.displayName = "ArrowRightFill";
QuestionCircleFill.displayName = "QuestionCircleFill";
Buttons.displayName = "Buttons";
Countdown.displayName = "Countdown";
Counter.displayName = "Counter";
FeaturedPosts.displayName = "FeaturedPosts";
Paragraph.displayName = "Paragraph";
Section.displayName = "Section";
Spacing.displayName = "Spacing";
Tabs.displayName = "Tabs";
Lottie.displayName = "Lottie";
InfoBox.displayName = "InfoBox";
Image.displayName = "Image";
TableOfContents.displayName = "TableOfContents";
SocialShare.displayName = "SocialShare";
Team.displayName = "Team";
ThreeCircleNodes.displayName = "ThreeCircleNodes";
