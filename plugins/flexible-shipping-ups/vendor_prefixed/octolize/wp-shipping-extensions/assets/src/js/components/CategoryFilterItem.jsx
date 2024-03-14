import React from 'react';

export default function CategoryFilterItem({name, activeCategory, filterCategory}) {
    return <li><button onClick={() => {
        filterCategory(name)
    }} className={name === activeCategory ? 'oct-btn active' : 'oct-btn'}>{name}</button></li>;
}
