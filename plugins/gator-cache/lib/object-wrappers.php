<?php
function wp_cache_init()
{
	GatorCache::initObjectCache();
}

/**
 * @note WordPress makes a distinction between addint new entry and replacing an existing one.
 * A new entry should not be added if it exists
 */ 
function wp_cache_add($key, $data, $group = '', $expire = 0)
{
    if (wp_suspend_cache_addition()) {
        return false;
    }
	return GatorCache::getObjectCache()->add($key, $data, $group, $expire);
}

/*
 * @depricated
 */ 
function wp_cache_close()
{
	return true;
}

function wp_cache_decr($key, $offset = 1, $group = '')
{
	return GatorCache::getObjectCache()->decrement($key, $offset, $group);
}

function wp_cache_delete($key, $group = '')
{
	return GatorCache::getObjectCache()->remove($key, $group);
}

function wp_cache_flush()
{
	return GatorCache::getObjectCache()->flush();
}

function wp_cache_get($key, $group = '', $force = false, &$found = null)
{
	return GatorCache::getObjectCache()->get($key, $group, $force, $found);
}

function wp_cache_incr($key, $offset = 1, $group = '')
{
	 GatorCache::getObjectCache()->increment( $key, $offset, $group);
}

function wp_cache_replace($key, $data, $group = '', $expire = 0)
{
	GatorCache::getObjectCache()->replace($key, $data, $group, (int) $expire);
}

function wp_cache_set($key, $data, $group = '', $expire = 0)
{
	GatorCache::getObjectCache()->set($key, $data, $group, (int) $expire);
}

/**
 * Switch the interal blog id.
 *
 * This changes the blog id used to create keys in blog specific groups.
 *
 * @since 3.5.0
 *
 * @param int $blog_id Blog ID
 */
function wp_cache_switch_to_blog($blog_id)
{
	return GatorCache::getObjectCache()->switchToBlog($blog_id);
}

function wp_cache_add_global_groups($groups)
{

	return GatorCache::getObjectCache()->addGlobalGroups($groups);
}

/**
 * Adds a group or set of groups to the list of non-persistent groups.
 *
 * @since 2.6.0
 *
 * @param string|array $groups A group or an array of groups to add
 */
function wp_cache_add_non_persistent_groups( $groups )
{
	// Default cache doesn't persist so nothing to do here.
}

/**
 * Reset internal cache keys and structures. If the cache backend uses global
 * blog or site IDs as part of its cache keys, this function instructs the
 * backend to reset those keys and perform any cleanup since blog or site IDs
 * have changed since cache init.
 *
 * This function is deprecated. Use wp_cache_switch_to_blog() instead of this
 * function when preparing the cache for a blog switch. For clearing the cache
 * during unit tests, consider using wp_cache_init(). wp_cache_init() is not
 * recommended outside of unit tests as the performance penality for using it is
 * high.
 *
 * @since 2.6.0
 * @deprecated 3.5.0
 */
function wp_cache_reset() {
	_deprecated_function( __FUNCTION__, '3.5' );
    return;
	global $wp_object_cache;

	return $wp_object_cache->reset();
}
