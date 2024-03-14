import React, { useState, useEffect, useMemo } from 'react'

import { Dropdown } from '../../library'
import { useMedia } from '../../library/utils/context/MediaContext'

type ListItemSelectPlaylistsType = {
    itemId: string
    setCurrentPlaylist: (value: string) => void
    isOpen: boolean
}

const ListItemSelectPlaylists: React.FC<ListItemSelectPlaylistsType> = ({
    itemId,
    setCurrentPlaylist,
    isOpen,
}: ListItemSelectPlaylistsType) => {
    const [option, setOption] = useState<OptionType>()
    const defaultOption: OptionType = useMemo(() => ({ value: 'none', label: 'None' }), [])

    const { playlistOptions, typeIs, featuredItem } = useMedia()

    const hasError = !playlistOptions[0].value

    const options = useMemo(() => {
        return !hasError ? [defaultOption, ...playlistOptions] : [...playlistOptions]
    }, [playlistOptions, defaultOption, hasError])

    const featuredPlaylistOption = (options: OptionType[]) => {
        return options.filter((opt: OptionType) => {
            return opt.value === featuredItem.playlistId
        })[0]
    }

    useEffect(() => {
        if (typeIs === 'featured' && `playlist${featuredItem.payload_id}` === itemId && featuredItem.playlistId) {
            setOption(featuredPlaylistOption(options))
            return
        }
        setOption(options[0])
    }, [options, typeIs, featuredItem])

    useEffect(() => {
        if (!option) return
        if (!isOpen && typeIs !== 'featured') {
            setCurrentPlaylist('')
            return
        }

        isOpen && setCurrentPlaylist(option.value)
    }, [option, setCurrentPlaylist, isOpen])

    const handleChange = (opt: OptionType) => {
        setOption(opt)
    }

    return (
        <Dropdown
            type="playlist"
            label="Attach a playlist to your video (Optional)"
            tooltip="Adding a playlist at the end of the video will ensure content keeps 
            playing even when the user finished watching the initial video"
            options={options}
            option={option}
            onChange={handleChange}
            itemId={itemId}
        />
    )
}

export default ListItemSelectPlaylists
