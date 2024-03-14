import React from 'react'

import styles from './uploadStatus.module.scss'

const UploadStatus: React.FC<{ status: string }> = ({ status }: { status: string }) => {
    const CONSTANTS = {
        success: {
            class: 'success',
            text: <h2>Video was uploaded successfully.</h2>,
        },
        pending: {
            class: 'pending',
            text: (
                <>
                    <h1>Video upload</h1>
                    <div className={styles.uploadMessage}>Please wait while video is being uploaded</div>
                    <div className={styles.spinningLoader}></div>
                </>
            ),
        },
        error: {
            class: 'error',
            text: (
                <>
                    <h2>There was an error uploading the video.</h2>
                    <div className={styles.uploadMessage}>Please try again at a later stage.</div>
                </>
            ),
        },
    }

    return (
        <div id="uploadStatus" className={CONSTANTS[status].class}>
            {CONSTANTS[status].text}
        </div>
    )
}

export default UploadStatus
