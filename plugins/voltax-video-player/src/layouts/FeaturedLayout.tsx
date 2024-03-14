import React, { useState } from 'react'

import { Modal, List } from '../library'
import VideoSearchInput from '../components/SearchInputs/VideoSearchInput'
import Footer from '../components/Footer/Footer'
import { FeaturedVideoMetaBox } from '../components/FeaturedVideoMetaBox/FeaturedVideoMetaBox'
import { useMedia } from '../library/utils/context/MediaContext'
import OpenUploadForm from '../components/OpenUploadForm/OpenUploadForm'

const FeaturedLayout: React.FC = () => {
    const [isOpen, setIsOpen] = useState(false)
    const { handleReset, featuredItem, videoSearchResults, canUpload } = useMedia()
    const meta = document.getElementById('mm-featured-video-meta-box') as HTMLElement

    const handleModal = (value: boolean) => {
        setIsOpen(value)
        handleReset()
    }

    return (
        <>
            {isOpen && (
                <Modal handleModal={handleModal} ariaLabel={'Set Featured Video'} type={'featured'}>
                    <VideoSearchInput />
                    {canUpload && <OpenUploadForm setIsLayoutOpen={setIsOpen} />}
                    <List featuredItem={featuredItem} searchResults={videoSearchResults} />
                    <Footer setIsOpen={setIsOpen} />
                </Modal>
            )}
            {meta && <FeaturedVideoMetaBox setIsOpen={setIsOpen} />}
        </>
    )
}

export default FeaturedLayout
