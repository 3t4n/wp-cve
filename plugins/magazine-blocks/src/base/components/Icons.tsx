import {
	chakra,
	forwardRef,
	IconProps as ChakraIconProps,
} from "@chakra-ui/react";
import React, { RefAttributes } from "react";

export type Prettify<T> = {
	[K in keyof T]: T[K];
} & {};

export type IconProps = Prettify<
	ChakraIconProps & RefAttributes<SVGSVGElement>
>;

export const Section = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path
			d="M4 2a2 2 0 00-2 2v3a2 2 0 002 2h16a2 2 0 002-2V4a2 2 0 00-2-2zm16 5H4V4h16zM2 12v8a2 2 0 002 2h16a2 2 0 002-2v-8a2 2 0 00-2-2H4a2 2 0 00-2 2zm18 0v8H4v-8zm-9 5H9v-2h2v-2h2v2h2v2h-2v2h-2z"
			fill="#690aa0"
		/>
	</chakra.svg>
));

export const Heading = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path
			d="m17 19v-14a1 1 0 0 0-1-1 1 1 0 0 0-1 1v6h-6v-6a1 1 0 0 0-1-1 0.94 0.94 0 0 0-1 1v14a1 1 0 0 0 1 1 1 1 0 0 0 1-1v-6h6v6a1 1 0 0 0 1 1 0.94 0.94 0 0 0 1-1z"
			fill="#690aa0"
		/>
	</chakra.svg>
));

export const Advertisement = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
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

export const BannerPosts = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path
			fill="#690aa0"
			d="M1.97 1.98h8v20h-8zm10.97 0h3v20h-3zm6.04.01h3v9h-3zm0 10.99h3v9h-3z"
		/>
	</chakra.svg>
));

export const CategoryList = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path
			fill="#690aa0"
			d="M2.03 2.06h9v9h-9zm0 10.94h9v9h-9zM13 2h9v9h-9z"
		/>
		<circle cx="17.5" cy="17.5" r="4.5" fill="#690aa0" />
	</chakra.svg>
));

export const DateWeather = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path
			fill="#690aa0"
			d="M8 18.33a.51.51 0 0 0-.75.24L6 21.07a.66.66 0 0 0 .21.85.56.56 0 0 0 .27.08.53.53 0 0 0 .52-.32l1.27-2.5a.66.66 0 0 0-.27-.85Zm3.36 0a.52.52 0 0 0-.76.24l-1.26 2.5a.66.66 0 0 0 .2.85.57.57 0 0 0 .28.08.55.55 0 0 0 .48-.32l1.26-2.5a.67.67 0 0 0-.2-.85Zm-6.65 0a.51.51 0 0 0-.75.24l-1.27 2.5a.66.66 0 0 0 .21.85.51.51 0 0 0 .75-.24l1.27-2.5a.66.66 0 0 0-.21-.85Zm17.01-7.59c-2.44.52-4.67-1.57-4.67-4.33a4.54 4.54 0 0 1 2-3.83.28.28 0 0 0-.05-.49 4.78 4.78 0 0 0-.95-.09 5.31 5.31 0 0 0-5 5.39 4.17 4.17 0 0 1 2 2.41 4.31 4.31 0 0 1 2.43 3.39 4.6 4.6 0 0 0 .52.06 4.81 4.81 0 0 0 3.9-2.09.25.25 0 0 0-.18-.42Zm-7.04 7.59a.51.51 0 0 0-.75.24l-1.27 2.5a.66.66 0 0 0 .21.85.51.51 0 0 0 .75-.24l1.27-2.5a.66.66 0 0 0-.21-.85Zm-.48-7.52a2.89 2.89 0 0 0-2.72-2.56 2.54 2.54 0 0 0-1.19.31A3.22 3.22 0 0 0 7.6 7a3.56 3.56 0 0 0-3.32 3.75s0 0 0 .06a3.06 3.06 0 0 0-2.23 3.07A3 3 0 0 0 4.83 17h8.86a3 3 0 0 0 2.77-3.12 3 3 0 0 0-2.26-3.07Z"
		/>
	</chakra.svg>
));

export const FeaturedCategories = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path
			fill="#690aa0"
			d="M13.01 2h9v12h-9zm-10.99.06h9v12h-9zM2.03 16h9v2h-9zm10.98 0h9v2h-9zM2.03 19.98h5.46v2.07H2.03zm10.98 0h5.46v2.07h-5.46z"
		/>
	</chakra.svg>
));

export const FeaturedPosts = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path
			fill="#690aa0"
			d="M1.93 2.08h20v12h-20zm.05 14.91h20v2h-20zm0 3.06h10v2h-10z"
		/>
	</chakra.svg>
));

export const GridModule = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path
			fill="#690aa0"
			d="M2.18 1.93h4.6v4.6h-4.6zm7.51.02h4.6v4.6h-4.6zm7.51-.02h4.6v4.6h-4.6zM2.18 9.69h4.6v4.6h-4.6zm7.51.02h4.6v4.6h-4.6zm7.51-.02h4.6v4.6h-4.6zm-15.02 7.7h4.6v4.6h-4.6zm7.51.03h4.6v4.6h-4.6zm7.51-.03h4.6v4.6h-4.6z"
		/>
	</chakra.svg>
));

export const NewsTicker = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path
			fill="#690aa0"
			d="M2 2v20h20V2Zm14 6.88-4.86 10.33a.39.39 0 0 1-.37.22h-.13a.4.4 0 0 1-.23-.17.36.36 0 0 1 0-.27l1.76-7.21-3.62.9H8.4a.4.4 0 0 1-.28-.1.34.34 0 0 1-.12-.36l1.8-7.36a.39.39 0 0 1 .2-.21.41.41 0 0 1 .25-.08h2.92a.38.38 0 0 1 .29.12.34.34 0 0 1 .12.26.41.41 0 0 1 0 .16L12 9.24l3.54-.87a.26.26 0 0 1 .11 0 .41.41 0 0 1 .3.13.33.33 0 0 1 .05.38Z"
		/>
	</chakra.svg>
));

export const PostList = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path
			fill="#690aa0"
			d="M1.99 1.98h8.5v8.5h-8.5zm11.52 1.26h8.5v2h-8.5zm0 3.98h4.25v2h-4.25zM1.99 13.47h8.5v8.5h-8.5zm11.52 1.25h8.5v2h-8.5zm0 3.99h4.25v2h-4.25z"
		/>
	</chakra.svg>
));

export const PostVideo = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path
			fill="#690aa0"
			d="M2 8v14h20V8Zm8.08 11v-7l5.84 3.5ZM3 5.07h18v1.5H3zM4 2h16v1.5H4z"
		/>
	</chakra.svg>
));

export const Slider = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path
			fill="#690aa0"
			d="M3.5 20H2V5h1.5v15zM22 5h-1.5v15H22V5zm-3-3H5v20h14V2z"
		></path>
	</chakra.svg>
));

export const SocialIcons = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path
			fill="#690aa0"
			d="M6 15c.9 0 1.7-.3 2.4-.9l6.3 3.6c-.1.3-.1.6-.1.9 0 1.9 1.6 3.5 3.5 3.6 1.9 0 3.5-1.6 3.6-3.5 0-1.9-1.6-3.5-3.5-3.6-.9 0-1.8.3-2.4.9l-6.3-3.6c.1-.2.1-.5.1-.8l6.1-3.5c1.4 1.3 3.6 1.2 4.9-.2s1.2-3.6-.2-4.9-3.6-1.2-4.9.2c-.6.6-.9 1.5-.9 2.4 0 .3 0 .6.1.8L8.9 9.6c-1-1.6-3.2-2.1-4.8-1S2 11.8 3 13.4c.7 1 1.8 1.6 3 1.6zm12 2c.8 0 1.5.7 1.5 1.5S18.8 20 18 20s-1.5-.7-1.5-1.5.6-1.5 1.5-1.5zm0-13c.8 0 1.5.7 1.5 1.5S18.8 7 18 7s-1.5-.7-1.5-1.5S17.1 4 18 4zM6 10c.8 0 1.5.7 1.5 1.5S6.8 13 6 13s-1.5-.7-1.5-1.5S5.1 10 6 10z"
		/>
	</chakra.svg>
));

export const TabPost = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path fill="#690aa0" d="M8.66 7.95V2.12H2v20h20V7.95H8.66z" />
		<path fill="#690aa0" d="M16.19 3.78h5v2.51h-5zm-5.86 0h5v2.51h-5z" />
	</chakra.svg>
));

export const Button = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path
			d="m4 7a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6a2 2 0 0 0-2-2zm16 8h-16v-6h16z"
			fill="#7E36F4"
		/>
		<path
			d="M18,12h0a.94.94,0,0,0-1-1H7a.94.94,0,0,0-1,1H6a.94.94,0,0,0,1,1H17A.94.94,0,0,0,18,12Z"
			fill="#7E36F4"
		/>
	</chakra.svg>
));

export const Buttons = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path d="M19.27 12.91H4.73a1.83 1.83 0 0 0-1.82 1.82v5.45A1.83 1.83 0 0 0 4.73 22h14.54a1.83 1.83 0 0 0 1.82-1.82v-5.45a1.83 1.83 0 0 0-1.82-1.82Zm0 7.27H4.73v-5.45h14.54Z" />
		<path d="M7.45 18.36h9.1a.91.91 0 0 0 0-1.81h-9.1a.91.91 0 0 0 0 1.81ZM19.27 2H4.73a1.83 1.83 0 0 0-1.82 1.82v5.45a1.83 1.83 0 0 0 1.82 1.82h14.54a1.83 1.83 0 0 0 1.82-1.82V3.82A1.83 1.83 0 0 0 19.27 2Zm0 7.27H4.73V3.82h14.54Z" />
		<path d="M7.45 7.45h9.1a.85.85 0 0 0 .9-.9.85.85 0 0 0-.9-.91h-9.1a.85.85 0 0 0-.9.91.85.85 0 0 0 .9.9Z" />
	</chakra.svg>
));

export const Image = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path d="M19.9 2.2H4.3c-1.2 0-2 .8-2 2v15.6c0 1.2.7 2.1 2 2.1h15.6c1.2 0 2-.8 2-2V4.3c-.1-1.3-.8-2.1-2-2.1zM4.3 19.8V4.2h15.6v15.6H4.3z" />
		<path d="M10.1 14l-1-1-3 4h12l-5-7-3 4z" />
	</chakra.svg>
));

export const CallToAction = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path
			fillRule="evenodd"
			d="M3 5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2h-4v-2h4V5H5v7h7.5v2H5a2 2 0 0 1-2-2V5Z"
			clipRule="evenodd"
		/>
		<path
			fillRule="evenodd"
			d="M12.046 9.848a.699.699 0 0 1 .786.124l7.25 6.897a.703.703 0 0 1 .158.793.696.696 0 0 1-.692.413l-3.638-.292-2.085 3.045a.696.696 0 0 1-.753.28.703.703 0 0 1-.52-.617l-.897-9.95a.699.699 0 0 1 .392-.693Zm1.168 2.417.556 6.164 1.215-1.774a.696.696 0 0 1 .63-.301l2.072.166-4.473-4.255Z"
			clipRule="evenodd"
		/>
		<path d="M7 8.25c0 .45.333.75.833.75h8.334c.5 0 .833-.3.833-.75s-.333-.75-.833-.75H7.833c-.5 0-.833.375-.833.75Z" />
	</chakra.svg>
));

export const Progress = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path
			fillRule="evenodd"
			d="M20 9.7H4a.3.3 0 0 0-.3.3v4a.3.3 0 0 0 .3.3h16a.3.3 0 0 0 .3-.3v-4a.3.3 0 0 0-.3-.3ZM4 8a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2H4Z"
			clipRule="evenodd"
		/>
		<path d="M3 9h7v6H3V9Z" />
	</chakra.svg>
));

export const Spacing = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path
			d="M21,2H3A1,1,0,0,0,2,3H2A.94.94,0,0,0,3,4H21a.94.94,0,0,0,1-1h0A.94.94,0,0,0,21,2Z"
			fill="#7E36F4"
		/>
		<path
			d="M2,21H2a.94.94,0,0,0,1,1H21a.94.94,0,0,0,1-1h0a.94.94,0,0,0-1-1H3A.94.94,0,0,0,2,21Z"
			fill="#7E36F4"
		/>
		<path
			d="M9,15V9A.94.94,0,0,0,8,8H8A1,1,0,0,0,7,9v6a.94.94,0,0,0,1,1H8A1,1,0,0,0,9,15Z"
			fill="#7E36F4"
		/>
		<path
			d="M17,15V9a.94.94,0,0,0-1-1h0a.94.94,0,0,0-1,1v6a.94.94,0,0,0,1,1h0A1.08,1.08,0,0,0,17,15Z"
			fill="#7E36F4"
		/>
		<path
			d="M13,17V7a.94.94,0,0,0-1-1h0a.94.94,0,0,0-1,1V17a.94.94,0,0,0,1,1h0A.94.94,0,0,0,13,17Z"
			fill="#7E36F4"
		/>
	</chakra.svg>
));

export const Team = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path
			fill="#7E36F4"
			fillRule="evenodd"
			// eslint-disable-next-line
			d="M6.81 14.425a4.077 4.077 0 0 1 2.882-1.194h4.616a4.077 4.077 0 0 1 4.076 4.077v1.538a1 1 0 0 1-2 0v-1.538a2.077 2.077 0 0 0-2.076-2.077H9.692a2.077 2.077 0 0 0-2.077 2.077v1.538a1 1 0 0 1-2 0v-1.538c0-1.082.43-2.119 1.194-2.883ZM12 6a2.077 2.077 0 1 0 0 4.154A2.077 2.077 0 0 0 12 6ZM7.923 8.077a4.077 4.077 0 1 1 8.154 0 4.077 4.077 0 0 1-8.154 0Zm10.801 5.903a1 1 0 0 1 1.218-.718A4.077 4.077 0 0 1 23 17.207v1.54a1 1 0 0 1-2 0v-1.539a2.077 2.077 0 0 0-1.558-2.009 1 1 0 0 1-.718-1.218Zm-2.308-9.228a1 1 0 0 1 1.217-.72 4.077 4.077 0 0 1 0 7.898 1 1 0 1 1-.496-1.937 2.077 2.077 0 0 0 0-4.024 1 1 0 0 1-.721-1.217ZM5.276 13.98a1 1 0 0 0-1.218-.718A4.078 4.078 0 0 0 1 17.207v1.54a1 1 0 0 0 2 0v-1.539A2.077 2.077 0 0 1 4.558 15.2a1 1 0 0 0 .718-1.218Zm2.308-9.228a1 1 0 0 0-1.217-.72 4.077 4.077 0 0 0 0 7.898 1 1 0 1 0 .496-1.937 2.077 2.077 0 0 1 0-4.024 1 1 0 0 0 .721-1.217Z"
			clipRule="evenodd"
		/>
	</chakra.svg>
));

export const SocialShare = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		{/* eslint-disable-next-line */}
		<path d="M6 15c.9 0 1.7-.3 2.4-.9l6.3 3.6c-.1.3-.1.6-.1.9 0 1.9 1.6 3.5 3.5 3.6 1.9 0 3.5-1.6 3.6-3.5 0-1.9-1.6-3.5-3.5-3.6-.9 0-1.8.3-2.4.9l-6.3-3.6c.1-.2.1-.5.1-.8l6.1-3.5c1.4 1.3 3.6 1.2 4.9-.2 1.3-1.4 1.2-3.6-.2-4.9-1.4-1.3-3.6-1.2-4.9.2-.6.6-.9 1.5-.9 2.4 0 .3 0 .6.1.8L8.9 9.6c-1-1.6-3.2-2.1-4.8-1C2.5 9.7 2 11.8 3 13.4c.7 1 1.8 1.6 3 1.6Zm12 2c.8 0 1.5.7 1.5 1.5S18.8 20 18 20s-1.5-.7-1.5-1.5.6-1.5 1.5-1.5Zm0-13c.8 0 1.5.7 1.5 1.5S18.8 7 18 7s-1.5-.7-1.5-1.5S17.1 4 18 4ZM6 10c.8 0 1.5.7 1.5 1.5S6.8 13 6 13s-1.5-.7-1.5-1.5S5.1 10 6 10Z" />
	</chakra.svg>
));

export const Tabs = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path
			fill="#7E36F4"
			fillRule="evenodd"
			d="M6 10V4H4v16h16V10H6Zm16-2v14H2V2h6v6h14Z"
			clipRule="evenodd"
		/>
		<path
			fill="#7E36F4"
			fillRule="evenodd"
			d="M13.5 3.5h-3v2h3v-2ZM9 2v5h6V2H9Zm11.5 1.5h-3v2h3v-2ZM16 2v5h6V2h-6Z"
			clipRule="evenodd"
		/>
	</chakra.svg>
));

export const Lottie = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path d="M20 4v16H4V4h16m0-2H4a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2zM7 18a1 1 0 0 1 0-2c1.66 0 2.856-2.177 4.124-4.482C12.616 8.805 14.159 6 17 6a1 1 0 0 1 0 2c-1.66 0-2.856 2.177-4.124 4.482C11.384 15.195 9.841 18 7 18z" />
	</chakra.svg>
));

export const Testimonial = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path d="M19.7 1.5H4.3c-1.2 0-2.2 1-2.2 2.2v12.9c0 1.2 1 2.2 2.2 2.2h3.9l3.8 3.7 3.8-3.7h3.9c1.2 0 2.2-1 2.2-2.2V3.7c0-1.2-1-2.2-2.2-2.2zm0 15.1h-4.9L12 19.4l-2.8-2.8H4.3V3.7h15.4v12.9z" />
		<circle cx="7.5" cy="10.1" r="1.5" />
		<circle cx="12" cy="10.1" r="1.5" />
		<circle cx="16.5" cy="10.1" r="1.5" />
	</chakra.svg>
));

export const Timeline = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path d="M11 3a1 1 0 1 1 2 0v18a1 1 0 1 1-2 0V3Z" />
		<path
			fillRule="evenodd"
			d="M16.037 7.397 15 6.517l.953-.789c.262-.436.74-.728 1.286-.728H20.5a1.5 1.5 0 0 1 0 3h-3.261c-.492 0-.929-.237-1.202-.603Zm0 11L15 17.517l1.025-.898A1.498 1.498 0 0 1 17.239 16H20.5a1.5 1.5 0 0 1 0 3h-3.261c-.492 0-.929-.237-1.202-.603ZM8 12.847l1-.83-.966-.811A1.499 1.499 0 0 0 6.76 10.5H3.5a1.5 1.5 0 0 0 0 3h3.261c.514 0 .968-.259 1.238-.653Z"
			clipRule="evenodd"
		/>
	</chakra.svg>
));

export const TableOfContents = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		{/* eslint-disable-next-line */}
		<path d="M16.8 9h-6.5c-.2 0-.3-.1-.3-.2V7.3c0-.2.1-.3.3-.3h6.5c.1 0 .2.1.2.3v1.5c0 .1-.1.2-.2.2zm-.8 3.8v-1.5c0-.1-.1-.3-.3-.3h-5.5c-.1 0-.3.1-.3.3v1.5c0 .1.1.3.3.3h5.5c.2-.1.3-.2.3-.3zm-2 4v-1.5c0-.1-.1-.3-.3-.3h-3.5c-.1 0-.3.1-.3.3v1.5c0 .1.1.3.3.3h3.5c.2-.1.3-.2.3-.3zm-5-8V7.3c0-.2-.1-.3-.2-.3H7.3c-.2 0-.3.1-.3.3v1.5c0 .1.1.2.3.2h1.5c.1 0 .2-.1.2-.2zm0 4v-1.5c0-.2-.1-.3-.2-.3H7.3c-.2 0-.3.1-.3.3v1.5c0 .1.1.2.3.2h1.5c.1 0 .2-.1.2-.2zm0 4v-1.5c0-.2-.1-.3-.2-.3H7.3c-.2 0-.3.1-.3.3v1.5c0 .1.1.2.3.2h1.5c.1 0 .2-.1.2-.2zm9 5.2H6c-1.7 0-3-1.3-3-3V5c0-1.7 1.3-3 3-3h12c1.7 0 3 1.3 3 3v14c0 1.7-1.3 3-3 3zM6 4c-.6 0-1 .4-1 1v14c0 .6.4 1 1 1h12c.6 0 1-.4 1-1V5c0-.6-.4-1-1-1H6z" />
	</chakra.svg>
));

export const InfoBox = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path d="M22 4V3c0-.6-.4-1-1-1H3c-.6 0-1 .4-1 1v18c0 .6.4 1 1 1h18c.6 0 1-.4 1-1V4zM4 4h16v16H4V4zm8 5.5a2 2 0 1 0 .001-3.999A2 2 0 0 0 12 9.5zm3 1H9c-.6 0-1 .4-1 1s.4 1 1 1h6c.6 0 1-.4 1-1s-.5-1-1-1zm-4 8h2c.6 0 1-.4 1-1s-.4-1-1-1h-2c-.6 0-1 .4-1 1 0 .5.4 1 1 1zm-5-4c0 .6.4 1 1 1h10c.6 0 1-.4 1-1s-.4-1-1-1H7c-.6 0-1 .5-1 1z" />
	</chakra.svg>
));

export const Column = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path d="M2 6h20v12H2z" fill="#7E36F4" />
		<path
			fill="#fff"
			d="M4 8h2.91v8H4zM8.36 8h2.91v8H8.36zM12.73 8h2.91v8h-2.91zM17.09 8H20v8h-2.91z"
		/>
	</chakra.svg>
));

export const Countdown = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		{/* eslint-disable-next-line */}
		<path d="m20.1 8.8.9-.9c.4-.4.4-1 0-1.4-.4-.4-1-.4-1.4 0l-1 1c-1-.6-2.3-1-3.6-1-3.9 0-7 3.1-7 7.1 0 3.7 3.2 6.9 6.9 6.9 3.9 0 7.1-3.1 7.1-7 0-1.8-.7-3.4-1.9-4.7zM15 18.5c-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5-2.2 5-5 5zm0-8c.6 0 1 .4 1 1v2c0 .5-.4 1-1 1-.5 0-1-.4-1-1v-2c0-.6.4-1 1-1zm-1-7h2c.5 0 1 .4 1 1 0 .5-.4 1-1 1h-2c-.5 0-1-.4-1-1s.4-1 1-1zm-10 5h2c.5 0 1 .4 1 1 0 .5-.4 1-1 1H4c-.5 0-1-.4-1-1s.4-1 1-1zm0 8h2c.5 0 1 .4 1 1 0 .5-.4 1-1 1H4c-.5 0-1-.4-1-1s.4-1 1-1zm-1-4h2c.5 0 1 .4 1 1 0 .5-.4 1-1 1H3c-.5 0-1-.4-1-1s.4-1 1-1z" />
	</chakra.svg>
));

export const Counter = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		{/* eslint-disable-next-line */}
		<path d="M19.664 17.739V7.646l1.316 1.316a.575.575 0 0 0 .812-.811l-2.296-2.295a.573.573 0 0 0-.403-.169h-.005a.572.572 0 0 0-.406.169l-2.294 2.295a.573.573 0 1 0 .812.811l1.315-1.316v10.092a.574.574 0 0 0 1.149.001zm-15.64-.866a.58.58 0 0 1-.42-.16.527.527 0 0 1-.165-.396v-4.603l.128.201-.667.493a.502.502 0 0 1-.32.101.526.526 0 0 1-.383-.164.537.537 0 0 1-.165-.393c0-.195.094-.353.283-.475L3.63 10.6a.6.6 0 0 1 .206-.091.929.929 0 0 1 .214-.027.56.56 0 0 1 .42.16.548.548 0 0 1 .155.397v5.279c0 .158-.057.29-.169.396a.604.604 0 0 1-.432.159zm6.018-1.005a.496.496 0 0 1 .511.511.472.472 0 0 1-.146.352.502.502 0 0 1-.365.142H6.937a.493.493 0 0 1-.375-.146c-.091-.097-.137-.222-.137-.374s.055-.286.164-.401l1.991-2.129c.225-.242.403-.492.534-.748s.197-.478.197-.667c0-.304-.088-.549-.265-.735s-.411-.278-.703-.278a.86.86 0 0 0-.356.082 1.467 1.467 0 0 0-.352.228c-.113.098-.218.21-.315.338a.482.482 0 0 1-.219.183.547.547 0 0 1-.571-.113.459.459 0 0 1-.169-.352c0-.104.035-.204.105-.302a2.763 2.763 0 0 1 1.355-.972c.2-.064.396-.096.584-.096.408 0 .763.081 1.064.242s.534.39.699.685c.164.295.247.644.247 1.045 0 .335-.099.708-.297 1.119a4.647 4.647 0 0 1-.799 1.155l-1.233 1.315-.1-.083h2.056zm2.493-.283a.662.662 0 0 1 .393.137c.104.079.227.149.369.21.144.061.311.091.498.091.189 0 .367-.047.535-.142a1.138 1.138 0 0 0 .58-1.027c0-.23-.049-.423-.143-.575s-.219-.268-.375-.347a1.073 1.073 0 0 0-.496-.118c-.135 0-.246.012-.334.035-.088.025-.174.051-.256.078s-.178.041-.287.041a.389.389 0 0 1-.325-.146.546.546 0 0 1-.114-.346.51.51 0 0 1 .05-.229c.033-.067.083-.141.151-.22l1.607-1.708.246.21h-2.246a.496.496 0 0 1-.511-.511c0-.14.049-.257.146-.352a.502.502 0 0 1 .365-.142h2.795c.188 0 .33.052.424.155a.555.555 0 0 1 .143.393c0 .079-.021.157-.064.233s-.094.145-.154.206l-1.617 1.735-.246-.311c.066-.03.16-.058.277-.082a1.596 1.596 0 0 1 1.235.242c.264.186.469.426.611.721s.215.613.215.954c0 .451-.102.841-.303 1.169a1.966 1.966 0 0 1-.848.758c-.365.177-.793.266-1.279.266-.226 0-.447-.028-.667-.083a2.213 2.213 0 0 1-.566-.219.626.626 0 0 1-.27-.242.555.555 0 0 1-.068-.251c0-.14.05-.272.151-.397a.465.465 0 0 1 .378-.186z" />
	</chakra.svg>
));

export const Map = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path d="M12 14c2.206 0 4-1.794 4-4s-1.794-4-4-4-4 1.794-4 4 1.794 4 4 4zm0-6c1.103 0 2 .897 2 2s-.897 2-2 2-2-.897-2-2 .897-2 2-2z" />
		<path d="M11.42 21.814a.998.998 0 0 0 1.16 0C12.884 21.599 20.029 16.44 20 10c0-4.411-3.589-8-8-8S4 5.589 4 9.995c-.029 6.445 7.116 11.604 7.42 11.819zM12 4c3.309 0 6 2.691 6 6.005.021 4.438-4.388 8.423-6 9.73-1.611-1.308-6.021-5.294-6-9.735 0-3.309 2.691-6 6-6z" />
	</chakra.svg>
));

export const Notice = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
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

export const Paragraph = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path
			d="M9.15 17h2.3v4.1a.9.9 0 00.9.9.9.9 0 00.9-.9v-17h2.8v17a.9.9 0 00.9.9h.1a.9.9 0 00.9-.9v-17h3.1a.9.9 0 00.9-.9V3a.9.9 0 00-.9-.9h-12a7.2 7.2 0 00-7 7.5 7.2 7.2 0 007.1 7.4zm-.1-12.9h2.3V15h-2.2a5.79 5.79 0 01-5.1-5.5 5.69 5.69 0 015-5.45z"
			fill="#7E36F4"
		/>
	</chakra.svg>
));

export const Slide = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path d="M15 20.5H5c-1.654 0-3-1.346-3-3v-7c0-1.654 1.346-3 3-3h10c1.654 0 3 1.346 3 3v7c0 1.654-1.346 3-3 3zM5 9.5c-.551 0-1 .449-1 1v7c0 .552.449 1 1 1h10a1 1 0 0 0 1-1v-7c0-.551-.448-1-1-1H5zm17 5v-6c0-2.757-2.243-5-5-5H8.47a1 1 0 0 0 0 2H17c1.654 0 3 1.346 3 3v6a1 1 0 1 0 2 0z" />
	</chakra.svg>
));

export const Search = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path
			xmlns="http://www.w3.org/2000/svg"
			fillRule="evenodd"
			d="M6.939 3.109a8 8 0 0 1 9.38 12.296l5.388 5.388a1 1 0 0 1-1.414 1.414l-5.388-5.387A8 8 0 1 1 6.94 3.109ZM10 4.5a6 6 0 1 0 0 12 6 6 0 0 0 0-12Z"
			clipRule="evenodd"
		/>
	</chakra.svg>
));

export const Trash = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		viewBox="0 0 24 24"
		xmlns="https://www.w3.org/2000/svg"
		h={6}
		w={6}
		{...props}
		ref={ref}
	>
		<path
			xmlns="http://www.w3.org/2000/svg"
			fillRule="evenodd"
			d="M8.586 3.086A2 2 0 0 1 10 2.5h4a2 2 0 0 1 2 2v2h4a1 1 0 1 1 0 2h-.08L19 19.546a3 3 0 0 1-3 2.954H8a3 3 0 0 1-3-2.954L4.08 8.5H4a1 1 0 0 1 0-2h4v-2a2 2 0 0 1 .586-1.414ZM6.086 8.5l.91 10.917A1 1 0 0 1 7 19.5a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1 1 1 0 0 1 .003-.083l.91-10.917H6.087ZM14 6.5h-4v-2h4v2Zm-4 4a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1Zm4 0a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1Z"
			clipRule="evenodd"
		/>
	</chakra.svg>
));

export const Reset = forwardRef<IconProps, "svg">((props, ref) => (
	<chakra.svg
		xmlns="http://www.w3.org/2000/svg"
		viewBox="0 0 24 24"
		fill="none"
		{...props}
	>
		<path
			xmlns="http://www.w3.org/2000/svg"
			fillRule="evenodd"
			d="M6.228 8.5c1.63-2.365 4.61-3.523 7.515-2.778a7 7 0 1 1-8.685 7.689 1 1 0 0 0-1.983.26 9 9 0 1 0 11.166-9.886c-3.484-.894-7.074.35-9.241 3.02V4.5a1 1 0 0 0-2 0v5a1 1 0 0 0 1 1h.543a.93.93 0 0 0 .047 0H9a1 1 0 1 0 0-2H6.228Z"
			clipRule="evenodd"
		/>
	</chakra.svg>
));
