export const insertVideo = (
    item: IMedia | Record<string, never>,
    type: string,
    playerId: string,
    playlistId: string,
): void => {
    const placeholder =
        type === 'playlist'
            ? `[mm-video type=${type} id=${item.payload_id} player_id=${playerId ? playerId : 'none'} image=${
                  item.image
              }]`
            : `[mm-video type=${type} id=${item.payload_id} playlist_id=${
                  playlistId ? playlistId : 'none'
              } player_id=${playerId ? playerId : 'none'} image=${item.image}]`

    /* @ts-ignore */
    if (window.tinyMCE.editors[wpActiveEditor]) {
        /* @ts-ignore */
        window.tinyMCE.editors[wpActiveEditor].insertContent(placeholder)
    }
}
