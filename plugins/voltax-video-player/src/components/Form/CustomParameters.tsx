import React, { useRef, Dispatch, SetStateAction, useState, useEffect, MouseEventHandler } from 'react'

import KeyValueRow from './KeyValueRow'
import { Button } from '../../library'
import styles from './form.module.scss'

type CustomParametersProps = {
    formData: FormDataType
    setFormData: Dispatch<SetStateAction<FormDataType>>
}

const CustomParameters: React.FC<CustomParametersProps> = ({ setFormData, formData }: CustomParametersProps) => {
    const [customParamIds, setCustomParamIds] = useState([1])
    const [customParams, setCustomParams] = useState({})
    const [isHidden, setIsHidden] = useState(true)
    const customParamRef = useRef<HTMLFieldSetElement | null>(null)

    const handleDelete = (event: MouseEventHandler<HTMLButtonElement> & { target: Element }) => {
        const paramId = event.target.id
        const currentId = parseInt(paramId)

        setCustomParamIds(() => {
            return customParamIds.filter((id) => id !== currentId)
        })
        setCustomParams(() => {
            delete customParams[paramId]
            return customParams
        })
    }

    const handleAdd = () => {
        const nextId = customParamIds.slice(-1)[0] + 1
        setCustomParamIds([...customParamIds, nextId])
    }

    useEffect(() => {
        if (customParamRef && customParamRef.current) {
            customParamRef.current.scrollIntoView({
                behavior: 'smooth',
                block: 'start',
            })
        }
        const missingKey = Object.values(customParams).filter((params: any) => !!params['']).length

        // Formats custom parameters for submitting form
        const formatCustomParams = Object.keys(customParams).reduce((a, c) => {
            if (Object.keys(customParams[c])[0] !== '') {
                a = { ...a, ...customParams[c] }
                return a
            }
            return a
        }, {})

        setFormData({
            ...formData,
            customParams: formatCustomParams,
            customParamsError: !!missingKey,
        })
    }, [customParams, Object.keys(customParams).length])

    useEffect(() => {
        if (customParamIds.length > 1) {
            setIsHidden(false)
        }
        if (customParamIds.length === 1) {
            setIsHidden(true)
        }
    }, [customParamIds])

    return (
        <fieldset className={styles.customParamContainer} ref={customParamRef}>
            <legend>Custom Parameters</legend>
            {customParamIds.map((id: number) => (
                <KeyValueRow
                    key={`custom_param_${id}`}
                    customParamId={id}
                    handleDelete={handleDelete}
                    setCustomParams={setCustomParams}
                    isHidden={isHidden}
                />
            ))}
            <Button type="button" onClick={handleAdd} className={styles.customParamButton}>
                <span className={styles.addSymbol}>+</span> Add Custom Parameter
            </Button>
        </fieldset>
    )
}

export default CustomParameters
