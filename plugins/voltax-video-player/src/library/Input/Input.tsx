import React, { InputHTMLAttributes } from 'react'

interface InputProps extends InputHTMLAttributes<HTMLInputElement | HTMLTextAreaElement> {
    inputClass?: string
    labelClass?: string
    labelChildren?: JSX.Element | string
    hasLabel?: boolean
    hasLabelAfter?: boolean
}

export const Input: React.FC<InputProps> = ({
    inputClass,
    labelClass = '',
    labelChildren = '',
    hasLabel = false,
    id,
    hasLabelAfter = false,
    ...rest
}: InputProps) => (
    <>
        {hasLabel && (
            <label className={labelClass} htmlFor={id}>
                {labelChildren}
            </label>
        )}
        <input id={id} className={inputClass ?? ''} {...rest} />
        {hasLabelAfter && (
            <label className={labelClass} htmlFor={id}>
                {labelChildren}
            </label>
        )}
    </>
)
