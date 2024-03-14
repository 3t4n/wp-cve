import React, { ButtonHTMLAttributes } from 'react'

import styles from './button.module.scss'

interface ButtonProps extends ButtonHTMLAttributes<HTMLButtonElement> {
    disabled?: boolean
    buttonClass?: string
    tabIndex?: number
}

export const Button: React.FC<ButtonProps> = ({
    disabled = false,
    buttonClass,
    onClick,
    children,
    tabIndex = 0,
    ...rest
}: ButtonProps) => {
    return (
        <button
            className={`${styles.container} ${disabled ? styles.disabled : ''} ${buttonClass ?? ''}`}
            disabled={disabled}
            onClick={onClick}
            tabIndex={tabIndex}
            {...rest}
        >
            {children}
        </button>
    )
}
