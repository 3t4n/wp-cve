import React, { FormEventHandler, FormHTMLAttributes, useState, useEffect } from 'react'

import { Input } from '../../library'
import { submitUpload } from '../../library/utils/api'
import { getMD5FromFile } from '../../library/utils/getMD5FromFile'

import styles from './form.module.scss'

interface UploadFormProps extends FormHTMLAttributes<HTMLFormElement> {
    formData: FormDataType
    setUploadStatus: (value: string) => void
}

const UploadForm: React.FC<UploadFormProps> = ({ formData, setUploadStatus, children }: UploadFormProps) => {
    const [isDisabled, setIsDisabled] = useState(true)
    const {
        title,
        creator,
        file,
        description = '',
        tags = '',
        category,
        isOptOut = true,
        customParams,
        customParamsError,
        filePath,
    } = formData

    useEffect(() => {
        if (!title || !creator || !filePath || customParamsError) {
            setIsDisabled(true)
            return
        }
        setIsDisabled(false)
    }, [title, creator, filePath, customParamsError])

    const handleSubmit: FormEventHandler<HTMLFormElement> = async (event) => {
        event.preventDefault()
        let content_md5 = ''
        try {
            content_md5 = await getMD5FromFile(file)
        } catch (error) {
            console.error(error)
        }

        const fileExtension = file.name.match(/[^.]+$/)
        const submitData: SubmitFormType = {
            content_length: file.size,
            file_extension: fileExtension?.length ? fileExtension[0] : '',
            content_md5,
            title,
            creator,
            opt_out_publish_external: isOptOut ? 'on' : '',
            ...(description && { description }),
            ...(tags && { tags }),
            ...(category && { category: category.value }),
            ...(Object.keys(customParams).length && { custom_params: JSON.stringify(customParams) }),
        }

        const data = new FormData()
        const videoFile = window.document.getElementById('uploadVideoFile') as HTMLInputElement | null

        if (videoFile?.files) {
            data.append('file', videoFile.files[0])
        }

        Object.keys(submitData).forEach((key) => data.append(key, submitData[key]))

        setUploadStatus('pending')
        try {
            const status = await submitUpload(data)
            setUploadStatus(status)
        } catch (e) {
            console.error(e)
        }
    }

    return (
        <form className={styles.formContainer} id="uploadForm" onSubmit={handleSubmit}>
            {children}
            <div className={styles.submitContainer}>
                <Input type="submit" value="Upload" className={styles.submitButton} disabled={isDisabled} />
                <span>By uploading the content you confirm your right to use it</span>
            </div>
        </form>
    )
}

export default UploadForm
