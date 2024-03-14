import React, { useRef, useEffect } from 'react'

import { ListItemSelectPlayers, ListItemSelectPlaylists } from '../../components/ListItemSelects'
import { toTime, toDuration } from '../utils/time'
import { useMedia } from '../../library/utils/context/MediaContext'
import PlaylistIcon from '../../images/playlist-icon.png'
import styles from './listItem.module.scss'

type ListItemType = {
    item: IMedia
    onClick: (event: React.MouseEvent<HTMLElement>, id: string) => void
    isOpen: boolean
    setCurrentPlayer: (value: string) => void
    setCurrentPlaylist: (value: string) => void
}

export const ListItem: React.FC<ListItemType> = ({
    item,
    onClick,
    isOpen,
    setCurrentPlayer,
    setCurrentPlaylist,
}: ListItemType) => {
    const { payload_id: id, image, duration, updated_at = null, items = '', title, type: playlistType = '' } = item
    const itemRef = useRef<HTMLLIElement | null>(null)
    const { typeIs } = useMedia()

    const numVideos = items ? `${items} videos` : ''
    const info = [toTime(updated_at), numVideos, playlistType].filter((el) => el).join(' | ')

    useEffect(() => {
        if (itemRef && itemRef.current && isOpen) {
            itemRef.current.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest',
            })
        }
    }, [isOpen])

    return (
        <li id={id} className={`${styles.container} ${isOpen ? styles.active : ''}`} ref={itemRef} tabIndex={0}>
            <div
                className={`${styles.innerContainer} ${typeIs === 'playlist' ? styles.playlist : ''}`}
                role="button"
                onClick={(e) => onClick(e, id)}
            >
                <div className={styles.media}>
                    <img
                        src={typeIs === 'playlist' ? PlaylistIcon : image}
                        alt="video"
                        className={`${typeIs === 'playlist' ? styles.playlistImage : styles.image}`}
                    />
                    <h5 className={styles.duration}>{toDuration(duration)}</h5>
                </div>
                <div>
                    <h3 className={styles.title}>{title}</h3>
                    <h4 className={styles.text}>{info}</h4>
                    <h4 className={styles.text}>Media ID: {id}</h4>
                </div>
            </div>
            <div className={`${styles.dropdowns} ${isOpen ? styles.open : ''}`}>
                <ListItemSelectPlayers itemId={id} setCurrentPlayer={setCurrentPlayer} isOpen={isOpen} />
                {typeIs !== 'playlist' && (
                    <ListItemSelectPlaylists
                        itemId={'playlist' + id}
                        setCurrentPlaylist={setCurrentPlaylist}
                        isOpen={isOpen}
                    />
                )}
            </div>
        </li>
    )
}
