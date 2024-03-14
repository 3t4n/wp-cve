/**
 * WordPress dependencies
 */
const { Path, Rect, SVG } = wp.components;

export default {
    'full': (
        <SVG xmlns="http://www.w3.org/2000/svg" width="50" height="29" viewBox="0 0 50 29" fill="none">
            <Rect x="3" y="3" width="44" height="23" rx="2" stroke="black" strokeWidth="2" />
        </SVG>
    ),
    'with-sidebar': (
        <SVG xmlns="http://www.w3.org/2000/svg" width="50" height="29" viewBox="0 0 50 29" fill="none">
            <Rect x="3" y="3" width="44" height="23" rx="2" stroke="black" strokeWidth="2" />
            <Path d="M33 3.5V26" stroke="black" strokeWidth="2" />
        </SVG>
    ),
    'with-sidebar-left': (
        <SVG xmlns="http://www.w3.org/2000/svg" width="50" height="29" viewBox="0 0 50 29" fill="none">
            <Rect x="3" y="3" width="44" height="23" rx="2" stroke="black" strokeWidth="2"/>
            <Path d="M17 3V25.5" stroke="black" strokeWidth="2"/>			
        </SVG>
    ),
};
