export const EBGetIconType = (value) => {
    if (value.includes('fa-')) {
        return 'fontawesome';
    }
    return 'dashicon';
}

export const EBRenderIcon = (iconType, className, icon) => {
    if (iconType === 'dashicon') {
        // Render Dashicon
        return '<span class="dashicon dashicons ' + icon + ' ' + className + '"></span>';
    } else if (iconType === 'fontawesome') {
        // Render FontAwesome icon
        return '<i class="' + icon + ' ' + className + '"></i>';
    }

    // Handle other icon types or return an error message if needed.
    return 'Invalid icon type';
}

export const EBGetIconClass = (value) => {
    if (!value) {
        return ''
    }
    if (!value.includes("fa-")) {
        return "dashicon dashicons " + value;
    }

    return value;
};
