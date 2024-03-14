<?php
namespace WPRemoteMediaExt\RemoteMediaExt\Interfaces;

interface RMLFeature
{
    public function registerScripts();
    public function adminScripts();
    public function enqueueScripts();
    public function themeScripts();
}
