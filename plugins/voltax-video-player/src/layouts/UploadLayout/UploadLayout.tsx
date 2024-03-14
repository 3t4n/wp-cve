import React, { useState, useEffect, ChangeEvent } from 'react'

import { UploadForm, UploadFile, Categories, CustomParameters } from '../../components/Form'
import { Title, Creator, Description, Tags, ExternalPublishing } from '../../components/Form/formInputs'
import UploadStatus from '../../components/UploadStatus/UploadStatus'
import { Modal } from '../../library'
import { useMedia } from '../../library/utils/context/MediaContext'

const UploadLayout: React.FC = () => {
    const [formData, setFormData] = useState<FormDataType>({
        filePath: '',
        file: {} as File,
        title: '',
        description: '',
        creator: '',
        tags: '',
        category: { label: 'Select a category', value: '' },
        isOptOut: true,
        customParams: {},
    })
    const [uploadStatus, setUploadStatus] = useState('')
    const { isUploadOpen, setIsUploadOpen, setTypeIs } = useMedia()

    useEffect(() => {
        !isUploadOpen && reset()
    }, [isUploadOpen])

    const reset = () => {
        setFormData({
            filePath: '',
            file: {} as File,
            title: '',
            description: '',
            creator: '',
            tags: '',
            category: { label: 'Select a category', value: '' },
            isOptOut: true,
            customParams: {},
        })
        setUploadStatus('')
    }

    const handleModal = (value: boolean) => {
        setIsUploadOpen(value)
        setTypeIs('upload')
    }

    const handleChange = (e: ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
        const target = e.target
        const name = target.name
        // @ts-ignore
        const value = target.type === 'checkbox' ? target.checked : target.value

        setFormData((prevState) => ({
            ...prevState,
            [name]: value,
        }))
    }

    return (
        <>
            {isUploadOpen && (
                <Modal handleModal={handleModal} ariaLabel="Upload Video" type="upload">
                    {!uploadStatus ? (
                        <UploadForm formData={formData} setUploadStatus={setUploadStatus}>
                            <UploadFile setFormData={setFormData} formData={formData} />
                            <Title handleChange={handleChange} title={formData.title} />
                            <Description handleChange={handleChange} description={formData.description} />
                            <Creator handleChange={handleChange} creator={formData.creator} />
                            <Tags handleChange={handleChange} tags={formData.tags} />
                            <Categories setFormData={setFormData} formData={formData} />
                            <ExternalPublishing handleChange={handleChange} isOptOut={formData.isOptOut} />
                            <CustomParameters setFormData={setFormData} formData={formData} />
                        </UploadForm>
                    ) : (
                        <UploadStatus status={uploadStatus} />
                    )}
                </Modal>
            )}
        </>
    )
}

export default UploadLayout
