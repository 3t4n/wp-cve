/**
 * Include all blocks
 */
// Button Block
import "./buttons";

// Pros and Cons Block
import "./pros-and-cons";

// Product Comparison Block
import "./product-comparison";

// Product Table Block
import "./product-table";

// CTA Block
import "./cta";

// Notice Block
import "./notice";
import "./verdict";
import "./single-product";

// Specifications
import "./specifications";

// Versus Line
import "./versus-line";

// Check Cross Icons
import "./checkIcons";

const customizationData = AffiliateX.customizationData;
if (customizationData.editorWidth === "custom") {
	let style = document.createElement("style");
	style.innerHTML = `
	#editor .editor-styles-wrapper .wp-block{
	max-width: ${customizationData.editorCustomWidth}px
	}
`;
	document.head.appendChild(style);
}
if (customizationData.editorSidebarWidth === "custom") {
	let style = document.createElement("style");
	style.innerHTML = `
	#editor .is-sidebar-opened .interface-interface-skeleton__sidebar, #editor .is-sidebar-opened .interface-complementary-area{
	width: ${customizationData.editorCustomSidebarWidth}px
	}
`;
	document.head.appendChild(style);
}
