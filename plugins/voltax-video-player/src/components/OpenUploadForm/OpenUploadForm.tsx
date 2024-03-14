import React from 'react'

import { Button } from '../../library'
import { useMedia } from '../../library/utils/context/MediaContext'
import { ReactComponent as IconUpload } from '../../images/icon-upload.svg'

import styles from './openUploadForm.module.scss'

const OpenUploadForm: React.FC<{ setIsLayoutOpen: (value: boolean) => void }> = ({
    setIsLayoutOpen,
}: {
    setIsLayoutOpen: (value: boolean) => void
}) => {
    const { setIsUploadOpen } = useMedia()

    const handleOpen = () => {
        setIsLayoutOpen(false)
        setIsUploadOpen(true)
    }

    return (
        <Button onClick={handleOpen} buttonClass={styles.button}>
            <IconUpload />
        </Button>
    )
}

export default OpenUploadForm
