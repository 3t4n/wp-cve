import React, { useRef, useEffect } from 'react'
import FocusTrap from 'focus-trap-react'
import { ReactComponent as CloseIcon } from '../../images/icon-close.svg'

import styles from './modal.module.scss'

type ModalType = {
    children: React.ReactNode[] | React.ReactNode
    handleModal: (value: boolean) => void
    ariaLabel?: string
    type: string
}

export const Modal: React.FC<ModalType> = ({ handleModal, children, ariaLabel, type }: ModalType) => {
    const modalRef = useRef<HTMLDivElement | null>(null)

    useEffect(() => {
        const mouseListener = (event: MouseEvent): void => {
            if (!modalRef.current || modalRef.current.contains(event.target as Node)) {
                return
            }

            handleModal(false)
        }
        const keydownListener = (event: KeyboardEvent): void => {
            if (event.key !== 'Escape') {
                return
            }

            handleModal(false)
        }
        document.addEventListener('mousedown', mouseListener)
        document.addEventListener('keydown', keydownListener)

        return () => {
            document.removeEventListener('mousedown', mouseListener)
            document.removeEventListener('keydown', keydownListener)
        }
    }, [modalRef, handleModal])

    return (
        <div className={styles.modalBackground}>
            <FocusTrap
                focusTrapOptions={{
                    onActivate: () => {
                        document.body.style.overflow = 'hidden'
                    },
                    onDeactivate: () => {
                        document.body.style.overflow = ''
                    },
                    allowOutsideClick: () => {
                        return true
                    },
                }}
            >
                <div
                    className={styles.modalBox}
                    aria-modal="true"
                    onClick={(e: React.MouseEvent<HTMLElement>) => e.stopPropagation()}
                    tab-index={-1}
                    role="dialog"
                    aria-label={ariaLabel}
                    ref={modalRef}
                >
                    <h2 className={styles.title}>
                        {type === 'featured' && 'Insert video from Voltax OVP'}
                        {(type === 'video' || type === 'playlist') && `Embed Voltax ${type}`}
                    </h2>
                    <button onClick={() => handleModal(false)} className={styles.closeButton} tabIndex={-1}>
                        <CloseIcon />
                    </button>
                    {children}
                </div>
            </FocusTrap>
        </div>
    )
}
