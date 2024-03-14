<?php
namespace WPRemoteMediaExt\RemoteMediaExt\Accounts\Youtube;

use WPRemoteMediaExt\RemoteMediaExt\Accounts\AbstractRemoteMedia;

/*
* Youtube Media Class
*
* Based on these data examples:
*
Array
(
[kind] => youtube#playlistItem
[etag] => "k1sYjErg4tK7WaQQxvJkW5fVrfg/k9ST3LS2UtjDBCQYcJsTpoM3Ttw"
[id] => WLZ01W3P_cElgH9Oek8w19Lpm7_ZPmLetv
[snippet] => Array
    (
        [publishedAt] => 2014-09-24T01:48:37.000Z
        [channelId] => UCra07gkv69xNssAfd5GUyMA
        [title] => Licensing and You
        [description] => Presented by Chris Tankersly

No matter how much work developers do in the open source world, they are confronted by a myriad of different licenses for things they want to use. GPL, MIT, BSD, Apache… these are just a few of the different licenses PHP devs deal with. What is the difference, and if you release your own code, which should you use? In 10 minutes we’ll give a high level overview of licensing, how it works, what to watch for, and the proper ways to use other people’s code.
        [thumbnails] => Array
            (
                [default] => Array
                    (
                        [url] => https://i.ytimg.com/vi/GurZ1C3b6x4/default.jpg
                        [width] => 120
                        [height] => 90
                    )

                [medium] => Array
                    (
                        [url] => https://i.ytimg.com/vi/GurZ1C3b6x4/mqdefault.jpg
                        [width] => 320
                        [height] => 180
                    )

                [high] => Array
                    (
                        [url] => https://i.ytimg.com/vi/GurZ1C3b6x4/hqdefault.jpg
                        [width] => 480
                        [height] => 360
                    )

                [standard] => Array
                    (
                        [url] => https://i.ytimg.com/vi/GurZ1C3b6x4/sddefault.jpg
                        [width] => 640
                        [height] => 480
                    )

                [maxres] => Array
                    (
                        [url] => https://i.ytimg.com/vi/GurZ1C3b6x4/maxresdefault.jpg
                        [width] => 1280
                        [height] => 720
                    )

            )

        [channelTitle] => Louis-Michel Raynauld
        [playlistId] => WLra07gkv69xNssAfd5GUyMA
        [position] => 0
        [resourceId] => Array
            (
                [kind] => youtube#video
                [videoId] => GurZ1C3b6x4
            )

    )

)
*/
class RemoteMedia extends AbstractRemoteMedia
{

    public function __construct($metadata = array())
    {
        $this->metadata = $metadata;
        if (isset($metadata['snippet'])) {
            $this->metadata = $metadata['snippet'];
        }
        
        $this->metadata['youtubeid'] = '';
        //Need to check if is array as isset $metadata['id']['videoId'] might return true
        //even if index does not exist on php 5.3
        if (isset($metadata['id']) && isset($metadata['id']['videoId']) && is_array($metadata['id'])) {
            $this->metadata['youtubeid'] = $metadata['id']['videoId'];
        } elseif (isset($this->metadata['resourceId']) && isset($this->metadata['resourceId']['videoId'])) {
            $this->metadata['youtubeid'] = $this->metadata['resourceId']['videoId'];
            //Coming from send to remote editor after an upload only id is set
        } elseif (isset($metadata['id']) && !isset($metadata['id']['videoId'])) {
            $this->metadata['youtubeid'] = $metadata['id'];
        }
        
        if (!empty($this->metadata['youtubeid'])) {
            $this->metadata['url'] = "https://www.youtube.com/watch?v=".$this->metadata['youtubeid'];
        }
    }
    
    /**
     * Prepares a media object for JS, where it is expected
     * to be JSON-encoded and fit into an Attachment model.
     *
     * @return array Array of attachment details.
     */
    public function toMediaManagerAttachment()
    {
        $iconurl = '';
        if (isset($this->metadata['thumbnails']) &&
            isset($this->metadata['thumbnails']['medium']) &&
            !empty($this->metadata['thumbnails']['medium']['url'])
        ) {
            $iconurl = $this->metadata['thumbnails']['medium']['url'];
        }

        $attachment = array_merge(
            $this->getBasicAttachment(),
            array(
                'id'          => $this->metadata['youtubeid'],
                'title'       => $this->metadata['title'],
                'filename'    => $this->metadata['title'],
                'url'         => $this->metadata['url'],
                'link'        => $this->metadata['url'],
                'alt'         => '',
                // 'author'      => $this->metadata['channelTitle'],
                'description' => $this->metadata['description'],
                'caption'     => "", //limit word count
                'name'        => $this->metadata['title'],
                'status'      => 'inherit',
                'uploadedTo'  => 0,
                'date'        => strtotime($this->metadata['publishedAt']) * 1000,
                'modified'    => strtotime($this->metadata['publishedAt']) * 1000,
                'menuOrder'   => 0,
                'mime'        => 'remote/youtube',
                'subtype'     => "youtube",
                'icon'        => $iconurl,
                'dateFormatted' => mysql2date(get_option('date_format'), $this->metadata['publishedAt']),
                'nonces'      => array(
                    'update' => false,
                    'delete' => false,
                ),
                'editLink'   => false,
            )
        );
        
        $attachment['remotedata'] = $this->metadata;

        return $attachment;
    }
}
