import React, { useState, useContext, useMemo, useEffect, useCallback, ReactNode } from 'react'
import { getSearchResults as getResults } from '../api'

import { getOptionData } from '../api'

const MediaContext = React.createContext<IContext | null>(null)

export const MediaProvider: React.FC<{ children: Element[] | ReactNode[] }> = (props) => {
    const [videoSearchResults, setVideoSearchResults] = useState<IMedia[]>([])
    const [isSearchError, setIsSearchError] = useState(false)
    const [playlistSearchResults, setPlaylistSearchResults] = useState<IMedia[]>([])
    const [player, setPlayer] = useState('')
    const [playlist, setPlaylist] = useState('')
    const [item, setItem] = useState({})
    const [featuredItem, setFeaturedItem] = useState({})
    const [typeIs, setTypeIs] = useState('')
    const [playerOptions, setPlayerOptions] = useState<OptionType[]>([])
    const [playlistOptions, setPlaylistOptions] = useState<OptionType[]>([])
    const [canUpload, setCanUpload] = useState(false)
    const [isUploadOpen, setIsUploadOpen] = useState(false)

    useEffect(() => {
        // @ts-ignore
        const uploadVideoOption = window.mm_video_data.enableVideoUpload === '1'
        setCanUpload(uploadVideoOption)
    }, [])

    useEffect(() => {
        const getSearchResults = async (type: string) => {
            try {
                const results = await getResults('', type)
                if (type === 'video') {
                    setVideoSearchResults(results)
                }
                if (type === 'playlist') {
                    setPlaylistSearchResults(results)
                }
            } catch (e) {
                console.log(`Search results could not be found for ${type}`, e)
            }
        }

        getSearchResults('video')
        getSearchResults('playlist')
    }, [])

    useEffect(() => {
        const getOptions = async (type: string) => {
            let options: IMedia[] = []
            try {
                options = await getOptionData(type)
            } catch {
                console.log(`ERROR: Failed to get options for ${type}`)
            }

            if (options[0].error) {
                type === 'player' &&
                    setPlayerOptions([{ value: '', label: 'Players were unable to be retrieved.' }])
                type === 'playlist' &&
                    setPlaylistOptions([{ value: '', label: 'Playlists were unable to be retrieved.' }])
                return
            }

            const id = type === 'player' ? 'id' : 'payload_id'

            const formatOptions = options
                .filter((o: IMedia | undefined) => o)
                .map((option: IMedia) => ({ value: option[id], label: option.title }))

            if (type === 'player') {
                setPlayerOptions(formatOptions)
            }
            if (type === 'playlist') {
                setPlaylistOptions(formatOptions)
            }
        }

        getOptions('player')
        getOptions('playlist')
    }, [])

    const handleReset = useCallback(() => {
        setPlayer('')
        setPlaylist('')
        setItem('')
        setTypeIs('')
    }, [])

    const value = useMemo(
        () => ({
            videoSearchResults,
            setVideoSearchResults,
            playlistSearchResults,
            setPlaylistSearchResults,
            player,
            setPlayer,
            playlist,
            setPlaylist,
            item,
            setItem,
            typeIs,
            setTypeIs,
            playerOptions,
            playlistOptions,
            handleReset,
            featuredItem,
            setFeaturedItem,
            isSearchError,
            setIsSearchError,
            canUpload,
            isUploadOpen,
            setIsUploadOpen,
        }),
        [
            videoSearchResults,
            playlistSearchResults,
            typeIs,
            player,
            playlist,
            item,
            playerOptions,
            playlistOptions,
            handleReset,
            featuredItem,
            isSearchError,
            canUpload,
            isUploadOpen,
        ],
    )
    return <MediaContext.Provider value={value} {...props} />
}

export const useMedia = (): IContext => {
    const context = useContext(MediaContext)
    if (!context) {
        throw new Error(`No media context available`)
    }

    return context
}
