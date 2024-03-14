import React, { useState, ChangeEvent, Dispatch, SetStateAction } from 'react'

import { Input } from '../../library'
import { ReactComponent as IconUpload } from '../../images/icon-upload.svg'

import styles from './form.module.scss'

type UploadFileProps = {
    formData: FormDataType
    setFormData: Dispatch<SetStateAction<FormDataType>>
}

const UploadFile: React.FC<UploadFileProps> = ({ setFormData, formData }: UploadFileProps) => {
    const [fileName, setFileName] = useState('')
    const [error, setError] = useState('')

    const handleChange = (e: ChangeEvent<HTMLInputElement>) => {
        e.preventDefault()

        const fileUpload = e.target.files

        if (!fileUpload) {
            setError('No file chosen')
            setFormData({ ...formData, filePath: '' })
            return
        }

        if (!e.target.value || !fileUpload[0].type.includes('video')) {
            setError('No file chosen')
            setFormData({ ...formData, filePath: '' })
            return
        }

        setError('')
        setFileName(fileUpload[0].name)
        setFormData({ ...formData, filePath: e.target.value, file: fileUpload[0] })
        return
    }

    return (
        <>
            <Input
                type="file"
                id="uploadVideoFile"
                name="file"
                onChange={handleChange}
                value={formData.filePath}
                accept="video/mp4,.mp4,.mov"
                hasLabel
                labelClass={styles.fileButton}
                labelChildren={
                    <>
                        <IconUpload />
                        <span>Choose a video</span>
                    </>
                }
            />
            {(error || fileName) && <p className={styles.emphasize}>{error ? error : fileName}</p>}
        </>
    )
}

export default UploadFile
