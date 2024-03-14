/**
 * Check if core block support extensions.
 *
 * @param {string} name block name
 *
 * @return {boolean}
 */
export default function isCoreBlockWithExt(name) {
	return name &&
		/^core/.test(name) &&
		! /^core\/block$/.test(name) &&
		! /^core\/tag-cloud/.test(name) &&
		! /^core\/calendar/.test(name) &&
		! /^core\/latest-comments/.test(name) &&
		! /^core\/rss/.test(name) &&
		! /^core\/archives/.test(name) &&
		! /^core\/template/.test(name);
}
