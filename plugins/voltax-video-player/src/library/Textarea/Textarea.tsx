import React, { TextareaHTMLAttributes } from 'react'

interface TextareaProps extends TextareaHTMLAttributes<HTMLTextAreaElement> {
    hasLabel?: boolean
    labelFor?: string
    labelClass?: string
    labelChildren?: JSX.Element | string
}

export const Textarea: React.FC<TextareaProps> = ({
    hasLabel,
    labelFor,
    labelClass,
    labelChildren,
    ...rest
}: TextareaProps) => (
    <>
        {hasLabel && (
            <label htmlFor={labelFor} className={labelClass ?? ''}>
                {labelChildren}
            </label>
        )}
        <textarea {...rest} />
    </>
)
