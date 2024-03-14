import React, { useState, useEffect } from 'react'

import { Modal, List } from '../library'
import VideoSearchInput from '../components/SearchInputs/VideoSearchInput'
import Footer from '../components/Footer/Footer'
import OpenUploadForm from '../components/OpenUploadForm/OpenUploadForm'
import { useMedia } from '../library/utils/context/MediaContext'

const VideoLayout: React.FC = () => {
    const [isOpen, setIsOpen] = useState(false)
    const { handleReset, setTypeIs, videoSearchResults, canUpload } = useMedia()

    useEffect(() => {
        window.document.getElementById('mm-video-editor')?.addEventListener('click', function (e) {
            e.preventDefault()
            setTypeIs('video')
            setIsOpen(true)
        })
    }, [])

    const handleModal = (value: boolean) => {
        setIsOpen(value)
        handleReset()
    }

    return (
        <>
            {isOpen && (
                <Modal handleModal={handleModal} ariaLabel={'Embed Video'} type={'video'}>
                    <VideoSearchInput />
                    {canUpload && <OpenUploadForm setIsLayoutOpen={setIsOpen} />}
                    <List searchResults={videoSearchResults} />
                    <Footer setIsOpen={setIsOpen} />
                </Modal>
            )}
        </>
    )
}

export default VideoLayout
