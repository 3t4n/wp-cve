import React, { ChangeEvent, useState, InputHTMLAttributes } from 'react'

import { Input, Textarea } from '../../library'

import styles from './form.module.scss'

interface InputProps extends InputHTMLAttributes<HTMLInputElement> {
    handleChange: (e: ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => void
    title?: string
    creator?: string
    tags?: string
    description?: string
    isOptOut?: boolean
}

/**
 * Title Input
 */
export const Title: React.FC<InputProps> = ({ handleChange, title }: InputProps) => (
    <div className={`${styles.titleContainer} ${styles.formElement}`}>
        <Input
            type="text"
            id="title"
            name="title"
            placeholder="Enter title"
            value={title}
            className={`${styles.bold} ${styles.input}`}
            onChange={handleChange}
            hasLabel
            labelChildren="Video Title"
            required
        />
    </div>
)

/**
 * Creator Name Input
 */
export const Creator: React.FC<InputProps> = ({ handleChange, creator }: InputProps) => (
    <div className={styles.formElement}>
        <Input
            type="text"
            id="creator"
            name="creator"
            placeholder="Enter creator"
            className={styles.input}
            value={creator}
            onChange={handleChange}
            hasLabel
            labelChildren="Creator Name"
            required
        />
    </div>
)

/**
 * Description textarea
 */
export const Description: React.FC<InputProps> = ({ handleChange, description }: InputProps) => {
    const [count, setCount] = useState(0)
    return (
        <div className={styles.formElement}>
            <Textarea
                id="description"
                name="description"
                maxLength={2000}
                placeholder="Enter description"
                className={styles.textarea}
                value={description}
                onChange={(e) => {
                    handleChange(e)
                    setCount(e.target.value.length)
                }}
                hasLabel
                labelFor="description"
                labelChildren="Add a video description (Optional)"
            />
            <p className={styles.characterCount}>{count}/2000</p>
        </div>
    )
}

export const Tags: React.FC<InputProps> = ({ handleChange, tags }: InputProps) => (
    <div className={styles.formElement}>
        <Input
            type="text"
            id="tags"
            name="tags"
            placeholder="Add relevant tags to the video"
            className={styles.input}
            value={tags}
            onChange={handleChange}
            hasLabel
            labelChildren="Video Tags"
        />
        <p className={styles.emphasize}>Enter tags as a comma separated list</p>
    </div>
)

export const ExternalPublishing: React.FC<InputProps> = ({ handleChange, isOptOut }: InputProps) => (
    <div className={styles.checkboxContainer}>
        <Input
            type="checkbox"
            id="opt_out_publish_external"
            name="isOptOut"
            checked={isOptOut}
            onChange={handleChange}
            hasLabelAfter
            labelChildren="Opt out of external publishing services"
        />
    </div>
)
