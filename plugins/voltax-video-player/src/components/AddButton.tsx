import React, { useState, useEffect } from 'react'

import { Button } from '../library'
import { useMedia } from '../library/utils/context/MediaContext'
import { insertVideo } from '../library/utils/insertVideo'

const AddButton: React.FC<{ setIsOpen: (value: boolean) => void }> = ({
    setIsOpen,
}: {
    setIsOpen: (value: boolean) => void
}) => {
    const [isDisabled, setIsDisabled] = useState(false)
    const { item, typeIs, player, playlist, handleReset, setFeaturedItem } = useMedia()

    useEffect(() => {
        setIsDisabled(!Object.keys(item).length)
    }, [item])

    const onClick = () => {
        if (typeIs !== 'featured') {
            insertVideo(item, typeIs, player, playlist)
        }
        if (typeIs === 'featured') {
            setFeaturedItem({ ...item, playerId: player, playlistId: playlist })
        }

        handleReset()
        setIsOpen(false)
        setIsDisabled(true)
    }

    return (
        <Button onClick={onClick} disabled={isDisabled}>
            Add
        </Button>
    )
}

export default AddButton
