/**
 * Includes the general functions
 * 
 */
const { __ } = wp.i18n;
const { escapeHTML } = wp.escapeHtml;
const { ExternalLink } = wp.components;

const ConvertGoogleFontVariant = ( variant ) => {
    switch ( variant ) {
        case '100':
            return escapeHTML( __( '100', 'wp-magazine-modules-lite' ) );
            break;

        case '100italic':
            return escapeHTML( __( '100 Italic', 'wp-magazine-modules-lite' ) );
            break;

        case '200':
            return escapeHTML( __( '200', 'wp-magazine-modules-lite' ) );
            break;

        case '200italic':
            return escapeHTML( __( '200 Italic', 'wp-magazine-modules-lite' ) );
            break;

        case '300':
            return escapeHTML( __( '300', 'wp-magazine-modules-lite' ) );
            break;

        case '300italic':
            return escapeHTML( __( '300 Italic', 'wp-magazine-modules-lite' ) );
            break;

        case 'regular':
                return escapeHTML( __( '400', 'wp-magazine-modules-lite' ) );
                break;

        case '400':
            return escapeHTML( __( '400', 'wp-magazine-modules-lite' ) );
            break;

        case '400italic':
            return escapeHTML( __( '400 Italic', 'wp-magazine-modules-lite' ) );
            break;

        case 'italic':
            return escapeHTML( __( '400 Italic', 'wp-magazine-modules-lite' ) );
            break;

        case '500':
            return escapeHTML( __( '500', 'wp-magazine-modules-lite' ) );
            break;

        case '500italic':
            return escapeHTML( __( '500 Italic', 'wp-magazine-modules-lite' ) );
            break;

        case '600':
            return escapeHTML( __( '600', 'wp-magazine-modules-lite' ) );
            break;

        case '600italic':
            return escapeHTML( __( '600 Italic', 'wp-magazine-modules-lite' ) );
            break;

        case '700':
            return escapeHTML( __( '700', 'wp-magazine-modules-lite' ) );
            break;

        case '700italic':
            return escapeHTML( __( '700 Italic', 'wp-magazine-modules-lite' ) );
            break;

        case '800':
            return escapeHTML( __( '800', 'wp-magazine-modules-lite' ) );
            break;

        case '800italic':
            return escapeHTML( __( '800 Italic', 'wp-magazine-modules-lite' ) );
            break;

        case '900':
            return escapeHTML( __( '900', 'wp-magazine-modules-lite' ) );
            break;

        case '900italic':
            return escapeHTML( __( '900 Italic', 'wp-magazine-modules-lite' ) );
            break;
        
        default:
            break;
    }
}
export default ConvertGoogleFontVariant;

export const CategoryColorLink = () => {
    return (
        <ExternalLink href={ BlocksBuildObject.pluginPage }>{ escapeHTML( __( 'Manage category colors', 'wp-magazine-modules' ) ) }</ExternalLink>
    )
}