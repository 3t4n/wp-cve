import React from 'react'

import AddButton from '../AddButton'
import styles from './footer.module.scss'

const Footer: React.FC<{ setIsOpen: (value: boolean) => void }> = ({
    setIsOpen,
}: {
    setIsOpen: (value: boolean) => void
}) => (
    <div className={styles.container}>
        <AddButton setIsOpen={setIsOpen} />
    </div>
)

export default Footer
