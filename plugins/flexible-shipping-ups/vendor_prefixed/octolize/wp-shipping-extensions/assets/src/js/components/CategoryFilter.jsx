import React from 'react';
import CategoryFilterItem from "./CategoryFilterItem";

export default function CategoryFilter({categories, activeCategory, filterCategory, textFilter}) {
    return <div className="oct-plugins-filter">
        <span>{textFilter}</span>

        <ul>
            {categories.map(item => <CategoryFilterItem key={item} name={item} activeCategory={activeCategory} filterCategory={filterCategory}/>)}
        </ul>
    </div>;
}
