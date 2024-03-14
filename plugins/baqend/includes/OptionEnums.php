<?php

namespace Baqend\WordPress;

use MyCLabs\Enum\Enum;

/**
 * A class representing option names of the plugin.
 *
 * Created on 2020-03-11.
 *
 * @author Kevin Twesten
 */
class OptionEnums extends Enum
{
    const ADDITIONAL_FILES = 'additional_files';
    const ADDITIONAL_URLS = 'additional_urls';
    const API_TOKEN = 'api_token';
    const APP_NAME = 'app_name';
    const ARCHIVE_END_TIME = 'archive_end_time';
    const ARCHIVE_NAME = 'archive_name';
    const ARCHIVE_START_TIME = 'archive_start_time';
    const ARCHIVE_STATUS_MESSAGES = 'archive_status_messages';
    const AUTHORIZATION = 'authorization';
    const BBQ_PASSWORD = 'bbq_password';
    const BBQ_USERNAME = 'bbq_username';
    const CRON_ERROR = 'cron_error';
    const CUSTOM_CONFIG = 'custom_config';
    const DEBUGGING_MODE = 'debugging_mode';
    const DELETE_TEMP_FILES = 'delete_temp_files';
    const DELIVERY_METHOD = 'delivery_method';
    const DESTINATION_HOST = 'destination_host';
    const DESTINATION_SCHEME = 'destination_scheme';
    const DESTINATION_URL_TYPE = 'destination_url_type';
    const DYNAMIC_BLOCK_CONFIG = 'dynamic_block_config';
    const ENABLED_PAGES = 'enabled_pages';
    const ENABLED_PATHS = 'enabled_paths';
    const FETCH_ORIGIN_INTERVAL = 'fetch_origin_interval';
    const HTTP_BASIC_AUTH_DIGEST = 'http_basic_auth_digest';
    const IMAGE_OPTIMIZATION = 'image_optimization';
    const INSTALL_RESOURCE_URL = 'install_resource_url';
    const LOCAL_DIR = 'local_dir';
    const METRICS_ENABLED = 'metrics_enabled';
    const PASSWORD = 'password';
    const RELATIVE_PATH = 'relative_path';
    const REVALIDATION_ATTEMPTS = 'revalidation_attempts';
    const SPEED_KIT_APP_DOMAIN = 'speed_kit_app_domain';
    const SPEED_KIT_BLACKLIST = 'speed_kit_blacklist';
    const SPEED_KIT_CONTENT_TYPE = 'speed_kit_content_type';
    const SPEED_KIT_COOKIES = 'speed_kit_cookies';
    const SPEED_KIT_DISABLE_REASON = 'speed_kit_disable_reason';
    const SPEED_KIT_ENABLED = 'speed_kit_enabled';
    const SPEED_KIT_MAX_STALENESS = 'speed_kit_max_staleness';
    const SPEED_KIT_UPDATE_INTERVAL = 'speed_kit_update_interval';
    const SPEED_KIT_WHITELIST = 'speed_kit_whitelist';
    const STRIP_QUERY_PARAMS = 'strip_query_params';
    const TEMP_FILES_DIR = 'temp_files_dir';
    const UPDATE_ATTEMPTS = 'update_attempts';
    const URLS_TO_EXCLUDE = 'urls_to_exclude';
    const USER_AGENT_DETECTION = 'user_agent_detection';
    const USERNAME = 'username';
    const VERSION = 'version';
}
