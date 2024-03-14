import React, { useState, useEffect } from 'react'
import ReactDOM from 'react-dom'

import { useMedia } from '../../library/utils/context/MediaContext'
import { Input } from '../../library'
import styles from './featuredVideoMetaBox.module.scss'

type FeaturedVideoMetaBoxType = {
    setIsOpen: (value: boolean) => void
}

export const FeaturedVideoMetaBox: React.FC<FeaturedVideoMetaBoxType> = ({ setIsOpen }) => {
    const [isCurrentItem, setIsCurrentItem] = useState(false)

    const meta = document.getElementById('mm-featured-video-meta-box') as HTMLElement
    const { thumbnail, title, description, hasFeaturedVideo, videoId, playerId, playlistId } = meta?.dataset

    const { setTypeIs, setItem, handleReset, featuredItem, setFeaturedItem } = useMedia()

    useEffect(() => {
        if (hasFeaturedVideo === 'false') return
        const video = {
            image: thumbnail && JSON.parse(thumbnail),
            title: title && JSON.parse(title),
            description: description && JSON.parse(description),
            payload_id: videoId && JSON.parse(videoId),
            playerId: playerId && JSON.parse(playerId),
            playlistId: playlistId && JSON.parse(playlistId),
        }
        setFeaturedItem(video)
        setIsCurrentItem(true)
    }, [thumbnail, title, description, hasFeaturedVideo, setFeaturedItem, videoId])

    useEffect(() => {
        setIsCurrentItem(!!Object.keys(featuredItem).length)
    }, [featuredItem, setIsCurrentItem])

    const onClick = (event: React.MouseEvent<HTMLElement>) => {
        event.preventDefault()
        setIsOpen(true)
        setTypeIs('featured')

        // Sets list item card to active when featured video is previously set
        if (featuredItem) {
            setItem(featuredItem)
        }
    }

    const handleRemove = (event: React.MouseEvent<HTMLElement>) => {
        event.preventDefault()
        setFeaturedItem({})
        setIsCurrentItem(false)
        handleReset()
    }

    return ReactDOM.createPortal(
        <>
            {isCurrentItem && (
                <>
                    {featuredItem.image && (
                        <img
                            src={featuredItem.image}
                            alt={featuredItem.title ?? 'Featured Video'}
                            className={styles.img}
                        />
                    )}
                    {featuredItem.title && <h3 className={styles.title}>{featuredItem.title}</h3>}
                    {featuredItem.description && <p className={styles.description}>{featuredItem.description}</p>}
                </>
            )}
            <Input
                type="hidden"
                name="mm-featured-video-data"
                id="mm-featured-video-data"
                value={JSON.stringify(featuredItem)}
            />
            <div className={styles.buttonContainer}>
                <button id="mm-featured-video-set" onClick={onClick} className="button" type="button">
                    {isCurrentItem ? 'Update' : 'Set Featured Video'}
                </button>
                {isCurrentItem && (
                    <button
                        id="mm-featured-video-remove"
                        className={styles.removeButton}
                        onClick={handleRemove}
                        type="button"
                    >
                        Remove
                    </button>
                )}
            </div>
        </>,
        meta as Element,
    )
}
