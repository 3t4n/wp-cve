<?php

namespace WPSocialReviews\App\Models;

class OptimizeImage extends Model
{
    protected $table = 'wpsr_optimize_images';

    public function getUserIds()
    {
        return static::select('*')->groupBy('user_name')->pluck('user_name')->toArray();
    }
    
    //return ids of successfully resized images
    public function getMediaIds($ids, $userNames)
    {
        return static::whereIn('user_name', $userNames)->whereIn('media_id', $ids)->where('images_resized', 1)->pluck('media_id')->toArray();
    }

    public function deleteMediaByUserName($userName)
    {
        static::where('user_name', $userName)->delete();
    }

    public function getOldPosts($limit)
    {
        return static::orderBy('last_requested')->limit($limit)->get();
    }

    public function updateLastRequestedTime($ids)
    {
        $dateFormat = date('Y-m-d H:i:s');
        $data = [
            'last_requested'    => $dateFormat,
            'updated_at'        => $dateFormat,
        ];
        static::whereIn('media_id', $ids)->update($data);
    }

    public function deleteMedia($mediaId, $userName)
    {
        static::where('media_id', $mediaId)->where('user_name', $userName)->delete();
    }

    public function updateData($mediaId, $userName, $data)
    {
       return static::updateOrCreate(['media_id' => $mediaId, 'user_name' => $userName], $data);
    }
}
