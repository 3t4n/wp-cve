export interface Measurement {
	value?: number;
	unit?: string;
}

export type Maybe<T> = T | undefined;

export type Responsive<T> = {
	desktop?: Maybe<T>;
	tablet?: Maybe<T>;
	mobile?: Maybe<T>;
};

export type Prettify<T> = {
	[K in keyof T]: T[K];
} & {};
