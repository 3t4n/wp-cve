import React, { useState, useEffect, useMemo } from 'react'

import { Dropdown } from '../../library'
import { useMedia } from '../../library/utils/context/MediaContext'

type ListItemSelectPlayersType = {
    itemId: string
    setCurrentPlayer: (value: string) => void
    isOpen: boolean
}

const ListItemSelectPlayers: React.FC<ListItemSelectPlayersType> = ({
    itemId,
    setCurrentPlayer,
    isOpen,
}: ListItemSelectPlayersType) => {
    const [option, setOption] = useState<OptionType>()
    /* @ts-ignore */
    const defaultOptionValue = window.mm_video_data.playerId
    const defaultOption: OptionType = useMemo(
        () => ({
            value: defaultOptionValue ?? '01ds00h8t07591k3p9',
            label: 'Default Player',
        }),
        [defaultOptionValue],
    )

    const { playerOptions, typeIs, featuredItem } = useMedia()
    const hasError = !playerOptions[0].value

    const options = useMemo(() => {
        return !hasError ? [defaultOption, ...playerOptions] : [...playerOptions]
    }, [defaultOption, playerOptions, hasError])

    const featuredPlayerOption = (options: OptionType[]) => {
        return options.filter((opt: OptionType) => {
            return opt.value === featuredItem.playerId
        })[0]
    }

    useEffect(() => {
        if (typeIs === 'featured' && featuredItem.payload_id === itemId && featuredItem.playerId) {
            setOption(featuredPlayerOption(options))
            return
        }
        setOption(options[0])
    }, [options, typeIs, featuredItem])

    useEffect(() => {
        if (!option) return
        if (!isOpen && typeIs !== 'featured') {
            setCurrentPlayer('')
            return
        }
        isOpen && setCurrentPlayer(option.value)
    }, [option, setCurrentPlayer, isOpen])

    const handleChange = (opt: OptionType) => {
        setOption(opt)
    }

    return (
        <Dropdown
            type="player"
            label="Select player"
            options={options}
            option={option}
            onChange={handleChange}
            itemId={itemId}
        />
    )
}

export default ListItemSelectPlayers
