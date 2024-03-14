import React, { useState, useEffect } from 'react'

import { SearchInput } from '../../library'
import { useMedia } from '../../library/utils/context/MediaContext'
import { getSearchResults as getResults } from '../../library/utils/api'

const VideoSearchInput: React.FC = () => {
    const [search, setSearch] = useState('')
    const { setVideoSearchResults, setIsSearchError } = useMedia()

    useEffect(() => {
        let abortController = new window.AbortController()
        const getSearchResults = async (search: string) => {
            abortController.abort()
            abortController = new window.AbortController()
            const signal = abortController.signal
            try {
                const results = await getResults(search, 'video', signal)
                setVideoSearchResults(results)
                if (results.length && results[0].payload_id === 'error') {
                    setIsSearchError(true)
                    return
                }
                setIsSearchError(false)
            } catch (error) {
                if (error.name === 'AbortError') return
                console.log(`Search results could not be found for video`, error)
            }
        }
        getSearchResults(search)
    }, [search, setVideoSearchResults])

    return (
        <>
            <SearchInput search={search} setSearch={setSearch} />
        </>
    )
}

export default VideoSearchInput
