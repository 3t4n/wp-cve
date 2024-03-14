import React, { useState, useEffect } from 'react'

import { Modal, List } from '../library'
import PlaylistSearchInput from '../components/SearchInputs/PlaylistSearchInput'
import Footer from '../components/Footer/Footer'
import { useMedia } from '../library/utils/context/MediaContext'

const PlaylistLayout: React.FC = () => {
    const [isOpen, setIsOpen] = useState(false)
    const { setTypeIs, playlistSearchResults } = useMedia()

    useEffect(() => {
        window.document.getElementById('mm-playlist-editor')?.addEventListener('click', function (e) {
            e.preventDefault()
            setTypeIs('playlist')
            setIsOpen(true)
        })
    }, [setIsOpen])

    return (
        <>
            {isOpen && (
                <Modal handleModal={setIsOpen} ariaLabel="Embed Playlist" type="playlist">
                    <PlaylistSearchInput />
                    <List searchResults={playlistSearchResults} />
                    <Footer setIsOpen={setIsOpen} />
                </Modal>
            )}
        </>
    )
}

export default PlaylistLayout
