import React, { useEffect, useRef, useState } from 'react'
import Select, { StylesConfig } from 'react-select'
import { SelectComponentsProps } from 'react-select/src/Select'

import { ReactComponent as InfoIcon } from '../../images/icon-info.svg'
import styles from './dropdown.module.scss'

interface DropdownType extends SelectComponentsProps {
    type?: string
    label: string
    tooltip?: string
    options: OptionType[]
    option: OptionType | undefined
    onChange: (option: OptionType) => void
    itemId: string
    hasPortalTarget?: boolean
    containerClass?: string
    hasCapitalize?: boolean
}

type customStylesType = {
    label: string
    value: string
}

type IsMulti = false

export const Dropdown: React.FC<DropdownType> = (props: DropdownType) => {
    const [portalTarget, setPortalTarget] = useState<HTMLElement | null>(null)
    const lightGray = '#d8dbed'
    const {
        id,
        type,
        label,
        tooltip,
        options,
        isLoading = false,
        option,
        onChange,
        itemId,
        hasCapitalize = false,
        containerClass,
        ...rest
    } = props
    const selectRef = useRef<HTMLDivElement | null>(null)
    const name = type ? type : id

    useEffect(() => {
        setPortalTarget(window.document.getElementById(itemId))
    }, [itemId])

    useEffect(() => {
        const handleScrollIntoView = () => {
            selectRef.current?.scrollIntoView({
                behavior: 'smooth',
                block: 'center',
            })
        }
        let cleanup: HTMLDivElement

        if (selectRef && selectRef.current) {
            cleanup = selectRef.current
            selectRef.current.addEventListener('click', handleScrollIntoView)
            return () => {
                cleanup.removeEventListener('click', handleScrollIntoView)
            }
        }
    }, [])

    const customStyles: StylesConfig<customStylesType, IsMulti> = {
        dropdownIndicator: (provided) => ({
            ...provided,
            color: lightGray,
        }),
        control: (provided) => ({
            ...provided,
            borderColor: lightGray,
        }),
        singleValue: (provided) => ({
            ...provided,
            textTransform: hasCapitalize ? 'capitalize' : 'none',
        }),
        menuList: (base) => ({
            ...base,
            maxHeight: '180px',
            textTransform: hasCapitalize ? 'capitalize' : 'none',

            '::-webkit-scrollbar': {
                backgroundColor: 'white',
                width: '5px',
                borderRadius: '50%',
            },
            '::-webkit-scrollbar-thumb': {
                background: lightGray,
                borderRadius: '4px',
            },
        }),
        menuPortal: (base) => {
            if (type) {
                return {
                    ...base,
                    left: '39px',
                    top: type === 'player' ? '205px' : '293px',
                    zIndex: 3,
                }
            }
            return { ...base }
        },
    }

    return (
        <div className={`${styles.container} ${containerClass ? containerClass : ''}`} id={itemId} ref={selectRef}>
            <label>
                {label} {tooltip ? <InfoIcon className={styles.infoIcon} title={tooltip} /> : null}
            </label>
            <Select
                id={name}
                name={name}
                styles={customStyles}
                components={{ IndicatorSeparator: () => null }}
                isSearchable
                className={styles.select}
                /* @ts-ignore */
                onChange={onChange}
                value={option}
                options={options}
                isLoading={isLoading}
                escapeClearsValue={false}
                aria-label={name}
                menuPortalTarget={type ? portalTarget : null}
                menuPlacement="bottom"
                menuPosition="absolute"
                menuShouldScrollIntoView={false}
                {...rest}
            />
        </div>
    )
}
