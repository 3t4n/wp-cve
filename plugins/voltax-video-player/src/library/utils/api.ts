export const getSearchResults = async (
    query: string,
    listType: string,
    signal?: AbortSignal,
): Promise<IMedia[]> => {
    const cachebust = new Date().getTime()
    const params = {
        action: 'get_mm_data',
        query: query,
        type: listType,
    }
    /* @ts-ignore */
    const url = `${ajax_object.ajax_url}?${new URLSearchParams(params).toString()}&${cachebust}`

    return await fetch(url, {
        method: 'GET',
        credentials: 'same-origin',
        signal,
    })
        .then((response) => {
            return response.json()
        })
        .then((response) => {
            if (undefined !== response.data.error) {
                return [
                    {
                        error: `An error occurred (${response.data.error}), 
                        please check plugin settings. Contact support@minutemedia.com if the problem persists.`,
                        payload_id: 'error',
                    },
                ]
            }
            let results = listType === 'video' ? response.data.videos : response.data
            results = Object.values(results).filter((result) => result)

            if (!results.length && query !== '') {
                return [
                    {
                        error: 'There are no results matching your search. Please try different keywords',
                        payload_id: 'error',
                    },
                ]
            }

            return results
        })
        .catch((e) => console.error('error', e))
}

export const getOptionData = async (listType: string): Promise<IMedia[]> => {
    const cachebust = new Date().getTime()
    const params = {
        action: 'get_mm_data',
        type: listType,
    }

    /* @ts-ignore */
    const url = `${ajax_object.ajax_url}?${new URLSearchParams(params).toString()}&${cachebust}`
    const options: RequestInit = {
        method: 'GET',
        credentials: 'same-origin',
    }

    return await fetch(url, options)
        .then((response) => {
            return response.json()
        })
        .then((response) => {
            if (undefined !== response.data.error) {
                return [{ error: response.data.error }]
            }
            if (listType === 'player') {
                let playerOptions = response.data.players
                playerOptions = typeof playerOptions === 'object' && Object.values(playerOptions)

                return playerOptions
            }

            return response.data
        })
        .catch((e) => console.error(`Error retrieving ${listType}`, e))
}

export const getCategories = async (): Promise<string[]> => {
    const cachebust = new Date().getTime()
    const params = {
        action: 'mm_iab_categories',
    }

    /* @ts-ignore */
    const url = `${ajax_object.ajax_url}?${new URLSearchParams(params).toString()}&${cachebust}`
    const options: RequestInit = {
        method: 'GET',
        credentials: 'same-origin',
    }

    return await fetch(url, options)
        .then((response) => {
            return response.json()
        })
        .then((response) => {
            if (undefined !== response.data.error) {
                return [{ error: response.data.error }]
            }

            return response.data
        })
        .catch((e) => console.error('Error retrieving categories', e))
}

export const uploadVideoFile = async (data: FormData, url: string): Promise<unknown> => {
    const contentMD5 = data.get('content_md5')
    const fileData = data.get('file')

    const config: RequestInit = {
        method: 'PUT',
        headers: {
            'Content-MD5': contentMD5 as string,
        },
        body: fileData,
    }

    return await fetch(url, config)
        .then((res) => {
            if (!res.ok) {
                throw res.statusText
            }
            return res
        })
        .catch((e) => {
            console.error(e)
            return 'error'
        })
}

export const submitUpload = async (data: FormData): Promise<string> => {
    const params = {
        action: 'mm_upload_video',
    }

    /* @ts-ignore */
    const url = `${ajax_object.ajax_url}?${new URLSearchParams(params).toString()}`
    const options: RequestInit = {
        method: 'POST',
        credentials: 'same-origin',
        body: data,
    }

    return await fetch(url, options)
        .then((response) => {
            return response.json()
        })
        .then(async (res) => {
            try {
                await uploadVideoFile(data, res.data.signed_url)
            } catch (e) {
                console.error(e)
                return 'error'
            }
            return 'success'
        })
        .catch((e) => {
            console.error(e)
            return 'error'
        })
}
