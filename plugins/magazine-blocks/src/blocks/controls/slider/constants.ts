export const DEFAULT_UNIT = "px";

export const PERCENT_UNITS = {
	min: 0,
	max: 100,
};

export const EM_REM_UNITS = {
	min: 0,
	max: 20,
	step: 0.01,
};

export const SIZE_UNITS = {
	"%": PERCENT_UNITS,
	vh: PERCENT_UNITS,
	vw: PERCENT_UNITS,
	em: EM_REM_UNITS,
	rem: EM_REM_UNITS,
	px: {
		step: 1,
	},
};
