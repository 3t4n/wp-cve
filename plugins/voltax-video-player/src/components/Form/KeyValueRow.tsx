import React, { useState, useEffect, SetStateAction, Dispatch, MouseEventHandler } from 'react'
import { Input, Button, CloseIcon } from '../../library'

import styles from './form.module.scss'

type KeyValueRowProps = {
    customParamId: number
    handleDelete: unknown
    setCustomParams: Dispatch<SetStateAction<Record<string, Record<string, unknown>>>>
    isHidden: boolean
}

const KeyValueRow: React.FC<KeyValueRowProps> = ({
    customParamId,
    handleDelete,
    setCustomParams,
    isHidden,
}: KeyValueRowProps) => {
    const [key, setKey] = useState('')
    const [value, setValue] = useState('')
    const [keyError, setKeyError] = useState(false)
    const errorMessage = 'Must include name of custom parameter'

    useEffect(() => {
        setCustomParams((prevState) => {
            return { ...prevState, [customParamId]: { [key]: value } }
        })

        const errorState = !!(!key && value)
        setKeyError(errorState)
    }, [key, value, customParamId])

    return (
        <div key={`${customParamId}`} className={styles.keyValueRowContainer}>
            <div className={styles.keyContainer}>
                <Input
                    name={`${customParamId}_key`}
                    type="text"
                    placeholder="Name"
                    inputClass={styles.keyValueInput}
                    value={key}
                    onChange={(e) => {
                        setKey(e.target.value)
                    }}
                />
                {keyError && <p className={styles.emphasize}>{errorMessage}</p>}
            </div>
            <Input
                name={`${customParamId}_value`}
                type="text"
                placeholder="Value"
                inputClass={styles.keyValueInput}
                value={value}
                onChange={(e) => {
                    setValue(e.target.value)
                }}
            />
            {!isHidden && (
                <Button
                    type="button"
                    id={customParamId.toString()}
                    className={styles.keyValueDelete}
                    onClick={handleDelete as MouseEventHandler<HTMLButtonElement>}
                >
                    <CloseIcon fill="#fff" />
                </Button>
            )}
        </div>
    )
}

export default KeyValueRow
