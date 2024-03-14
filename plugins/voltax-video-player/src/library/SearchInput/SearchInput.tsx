import React from 'react'

import { Input } from '../../library'
import { ReactComponent as SearchIcon } from '../../images/icon-search.svg'
import styles from './searchInput.module.scss'

type SearchInputProps = { search: string; setSearch: (value: string) => void }

export const SearchInput: React.FC<SearchInputProps> = ({ search, setSearch }: SearchInputProps) => {
    const onChange = (event: any) => {
        event.preventDefault()
        setSearch(event.target.value)
    }

    return (
        <div className={styles.container}>
            <Input
                id="search"
                type="search"
                placeholder="Search by name or ID"
                className={styles.input}
                onChange={onChange}
                value={search}
                tabIndex={0}
            />
            <button type="button" onClick={onChange} className={styles.searchButton}>
                <SearchIcon className={styles.searchIcon} />
            </button>
        </div>
    )
}
