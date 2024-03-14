import React, { useState, useEffect } from 'react'

import { useMedia } from '../utils/context/MediaContext'
import { ListItem } from '../ListItem/ListItem'
import styles from './list.module.scss'

type ListProps = {
    featuredItem?: IMedia | Record<string, never>
    searchResults?: IMedia[]
}

export const List: React.FC<ListProps> = ({ featuredItem, searchResults }: ListProps) => {
    const [currentListItemId, setCurrentListItemId] = useState('')
    const [currentPlayer, setCurrentPlayer] = useState('')
    const [currentPlaylist, setCurrentPlaylist] = useState('')
    const { setPlayer, setPlaylist, setItem, typeIs, isSearchError } = useMedia()

    useEffect(() => {
        setPlayer(currentPlayer)
        setPlaylist(currentPlaylist)
    }, [currentPlayer, setPlayer, currentPlaylist, setPlaylist])

    useEffect(() => {
        if (featuredItem) {
            const { payload_id, playerId, playlistId } = featuredItem
            setCurrentListItemId(payload_id)
            playerId && setCurrentPlayer(playerId)
            playlistId && setCurrentPlaylist(playlistId)

            setTimeout(() => {
                document.getElementById(payload_id)?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start',
                })
            }, 100)
        }
    }, [featuredItem, typeIs])

    const handleClick = (e: React.MouseEvent<HTMLElement>, itemId: string) => {
        e.preventDefault()

        // Resets the states when current list item card is closed
        if (itemId === currentListItemId) {
            setCurrentPlayer('')
            setCurrentPlaylist('')
            setItem({})
            setCurrentListItemId('')
            return
        }

        const currentItem = searchResults?.length
            ? searchResults.filter((item: IMedia) => item.payload_id === itemId)[0]
            : {}
        setCurrentListItemId(itemId)
        setItem(currentItem)
    }

    return (
        <ul className={`${styles.container} ${isSearchError ? styles.error : ''}`} tabIndex={-1}>
            {!isSearchError ? (
                <>
                    {searchResults ? (
                        searchResults.map((item: IMedia) => (
                            <ListItem
                                item={item}
                                key={typeIs + item.payload_id}
                                onClick={handleClick}
                                isOpen={currentListItemId === item.payload_id}
                                setCurrentPlayer={setCurrentPlayer}
                                setCurrentPlaylist={setCurrentPlaylist}
                            />
                        ))
                    ) : (
                        <li key="searching">Searching...</li>
                    )}
                </>
            ) : (
                <li id="error" key="error">
                    {searchResults?.length
                        ? searchResults[0].error
                        : 'An error has occurred with the search results'}
                </li>
            )}
        </ul>
    )
}
