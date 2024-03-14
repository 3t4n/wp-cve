import { Maybe } from "./utils";

export interface Responsive<T> {
	desktop?: Maybe<T>;
	tablet?: Maybe<T>;
	mobile?: Maybe<T>;
}
