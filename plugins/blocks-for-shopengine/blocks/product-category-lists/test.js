const sameClassMultiVal = (selector, property, value) => {
    cssHelper.add(selector, {}, (val) => (`
    ${property}: ${value};
`));
}

const gridData = {
    'grid-template-columns': `repeat(${settings.shopengine_grid_columns.desktop}, 1fr);`,
    'grid-column-gap': `${settings.shopengine_column_gap.desktop};`,
    'grid-row-gap': `${settings.shopengine_row_gap.desktop};`,
}

for (const item in gridData) {
    sameClassMultiVal('.shopengine-product-category-lists .shopengine-category-lists-grid', item, gridData[item]);
}


//actual css
cssHelper.add('.shopengine-product-category-lists .shopengine-category-lists-grid', settings.shopengine_grid_columns, (val) => (`
grid-template-columns: repeat(${val}, 1fr);
`)).add('.shopengine-product-category-lists .shopengine-category-lists-grid', settings.shopengine_column_gap, (val) => (`
grid-column-gap: ${val}px;
`)).add('.shopengine-product-category-lists .shopengine-category-lists-grid', settings.shopengine_row_gap, (val) => (`
grid-row-gap: ${val}px;
`));