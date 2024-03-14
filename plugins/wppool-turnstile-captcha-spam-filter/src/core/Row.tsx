import React from 'react';

import './_row.scss';

type propTypes = {
	reverse?: boolean;
	startXs?: boolean;
	centerXs?: boolean;
	endXs?: boolean;
	topXs?: boolean;
	middleXs?: boolean;
	bottomXs?: boolean;
	aroundXs?: boolean;
	betweenXs?: boolean;
	startSm?: boolean;
	centerSm?: boolean;
	endSm?: boolean;
	topSm?: boolean;
	middleSm?: boolean;
	bottomSm?: boolean;
	aroundSm?: boolean;
	betweenSm?: boolean;
	startMd?: boolean;
	centerMd?: boolean;
	endMd?: boolean;
	topMd?: boolean;
	middleMd?: boolean;
	bottomMd?: boolean;
	aroundMd?: boolean;
	betweenMd?: boolean;
	startLg?: boolean;
	centerLg?: boolean;
	endLg?: boolean;
	topLg?: boolean;
	middleLg?: boolean;
	bottomLg?: boolean;
	aroundLg?: boolean;
	betweenLg?: boolean;
	customClass?: string;
	children: React.ReactNode;
};

const Row = ( {
	reverse,
	startXs,
	centerXs,
	endXs,
	topXs,
	middleXs,
	bottomXs,
	aroundXs,
	betweenXs,
	startSm,
	centerSm,
	endSm,
	topSm,
	middleSm,
	bottomSm,
	aroundSm,
	betweenSm,
	startMd,
	centerMd,
	endMd,
	topMd,
	middleMd,
	bottomMd,
	aroundMd,
	betweenMd,
	startLg,
	centerLg,
	endLg,
	topLg,
	middleLg,
	bottomLg,
	aroundLg,
	betweenLg,
	customClass,
	children,
}: propTypes ) => {
	const classes = () => {
		let c = reverse ? 'swptls-row reverse' : 'swptls-row ';
		c += startXs
			? ' start-xs'
			: centerXs
			? ' center-xs'
			: endXs
			? ' end-xs'
			: '';
		c += startSm
			? ' start-sm'
			: centerSm
			? ' center-sm'
			: endSm
			? ' end-sm'
			: '';
		c += startMd
			? ' start-md'
			: centerMd
			? ' center-md'
			: endMd
			? ' end-md'
			: '';
		c += startLg
			? ' start-lg'
			: centerLg
			? ' center-lg'
			: endLg
			? ' end-lg'
			: '';
		c += topXs
			? ' top-xs'
			: middleXs
			? ' middle-xs'
			: bottomXs
			? ' bottom-xs'
			: '';
		c += topSm
			? ' top-sm'
			: middleSm
			? ' middle-sm'
			: bottomSm
			? ' bottom-sm'
			: '';
		c += topMd
			? ' top-md'
			: middleMd
			? ' middle-md'
			: bottomMd
			? ' bottom-md'
			: '';
		c += topLg
			? ' top-lg'
			: middleLg
			? ' middle-lg'
			: bottomLg
			? ' bottom-lg'
			: '';
		c += aroundXs ? ' around-xs' : betweenXs ? ' between-xs' : '';
		c += aroundSm ? ' around-sm' : betweenSm ? ' between-sm' : '';
		c += aroundMd ? ' around-md' : betweenMd ? ' between-md' : '';
		c += aroundLg ? ' around-lg' : betweenLg ? ' between-lg' : '';
		return c;
	};
	return (
		<div className={ `${ classes() } ${ customClass ? customClass : '' }` }>
			{ children }
		</div>
	);
};

export default Row;
