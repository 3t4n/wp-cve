import React, { Dispatch, SetStateAction, useEffect, useState } from 'react'

import { Dropdown } from '../../library'
import { getCategories } from '../../library/utils/api'

import styles from './form.module.scss'

type CategoriesProps = {
    formData: FormDataType
    setFormData: Dispatch<SetStateAction<FormDataType>>
}

const Categories: React.FC<CategoriesProps> = ({ formData, setFormData }: CategoriesProps) => {
    const [options, setOptions] = useState<OptionType[]>([])

    useEffect(() => {
        const getCategoryOptions = async () => {
            try {
                const results = await getCategories()
                const formatOptions = results.map((category: string) => ({
                    value: category.toLowerCase(),
                    label: category,
                }))
                setOptions([{ label: 'Select A Category', value: '' }, ...formatOptions])
            } catch (e) {
                console.log(e)
            }
        }

        if (!options.length) {
            getCategoryOptions()
        }
    }, [])

    return (
        <Dropdown
            id="category"
            label="Add Category"
            options={options}
            option={formData.category}
            onChange={(option: OptionType) => setFormData({ ...formData, category: option })}
            hasPortalTarget={false}
            containerClass={styles.categoryContainer}
            itemId="category"
            hasCapitalize
        />
    )
}

export default Categories
