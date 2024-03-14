interface IVideo {
    payload_id: string
    image: string
    duration: string
    updated_at: number
    title: string
    creator?: string
    custom_params?: any
    description?: string
    external_id?: string
    is_hosted?: boolean
    opt_out_publish_external?: boolean
    property?: string
    provider?: string
    pubdate?: Date
    restrictions?: any
    sources?: { file: string; label: string; type: string }[]
    status?: string
    tags?: string
    error?: string
    playerId?: string
    playlistId?: string
}

interface IMedia extends IVideo {
    items?: number
    type?: string
    all?: {
        created_at?: Date
        extra_data?: {
            exclude_all_tags?: string[]
            exclude_any_tags?: string[]
            include_all_tags?: string[]
            include_any_tags?: string[]
            limit?: number
            order_asc?: boolean
            order_by?: string
            video_ids?: any
        }
        playlist_id?: string
        playlist_type?: number
        property?: string
        title?: string
        updated_at?: number
    }
}

interface IContext {
    videoSearchResults: IMedia[] | never[]
    setVideoSearchResults: (video: IMedia[]) => void
    playlistSearchResults: IMedia[] | never[]
    setPlaylistSearchResults: (playlist: IMedia[]) => void
    player: string
    setPlayer: (player: string) => void
    playlist: string
    setPlaylist: (playlist: string) => void
    item: IMedia | Record<string, never>
    setItem: (item: IMedia | Record<string, never>) => void
    typeIs: string
    setTypeIs: (type: string) => void
    playerOptions: OptionType[]
    playlistOptions: OptionType[]
    handleReset: () => void
    featuredItem: IMedia | Record<string, never>
    isSearchError: boolean
    canUpload: boolean
    setIsSearchError: (value: boolean) => void
    setIsUploadOpen: (value: boolean) => void
    isUploadOpen: boolean
    setFeaturedItem: (
        item:
            | IMedia
            | {
                  image?: string
                  title?: string
                  description?: string
                  payload_id?: string
                  playlistId?: string
                  playerId?: string
              },
    ) => void
}

type OptionType = {
    value: string
    label: string
}

type FormDataType = {
    filePath: string
    file: File
    title: string
    description?: string
    creator: string
    tags?: string
    category?: OptionType
    isOptOut?: boolean
    customParams: Record<string, Record<string, string>>
    customParamsError?: boolean
}

type SubmitFormType = {
    title: string
    creator: string
    content_length: number
    content_md5: string
    file_extension: string
    description?: string
    tags?: string
    category?: string
    opt_out_publish_external?: string
    custom_params?: string
}
