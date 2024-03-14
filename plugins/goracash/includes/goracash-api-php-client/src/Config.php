<?php
/**
 * Copyright 2015 Goracash
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Goracash;

class Config
{
    const GZIP_DISABLED = true;
    const GZIP_ENABLED = false;
    const GZIP_UPLOADS_ENABLED = true;
    const GZIP_UPLOADS_DISABLED = false;
    const USE_AUTO_IO_SELECTION = "auto";
    const TASK_RETRY_NEVER = 0;
    const TASK_RETRY_ONCE = 1;
    const TASK_RETRY_ALWAYS = -1;

    protected $configuration;

    /**
     * Create a new Goracash Config. Can accept an ini file location with the
     * local configuration. For example:
     *     application_name="My App"
     *
     * @param [$iniFileLocation] - optional - The location of the ini file to load
     */
    public function __construct($iniFileLocation = null)
    {
        $this->configuration = array(
            // The application_name is included in the User-Agent HTTP header.
            'application_name' => '',
            // Which Authentication, Storage and HTTP IO classes to use.
            'auth_class'    => 'Goracash\Service\Authentication',
            'io_class'      => self::USE_AUTO_IO_SELECTION,
            'logger_class'  => 'Goracash\Logger\Clean',
            // Don't change these unless you're working against a special development
            // or testing environment.
            'base_path' => 'https://ws.goracash.com',
            // Definition of class specific values, like file paths and so on.
            'classes' => array(
                'Goracash\IO\Primary' => array(
                    'request_timeout_seconds' => 100,
                ),
                'Goracash\Logger\Primary' => array(
                    'level' => 'debug',
                    'log_format' => "[%datetime%] %level%: %message% %context%\n",
                    'date_format' => 'd/M/Y:H:i:s O',
                    'allow_newlines' => true
                ),
                'Goracash\Logger\File' => array(
                    'file' => 'php://stdout',
                    'mode' => 0640,
                    'lock' => false,
                ),
                'Goracash\Http\Request' => array(
                    // Disable the use of gzip on calls if set to true. Defaults to false.
                    'disable_gzip' => self::GZIP_ENABLED,
                    // We default gzip to disabled on uploads even if gzip is otherwise
                    // enabled, due to some issues seen with small packet sizes for uploads.
                    // Please test with this option before enabling gzip for uploads in
                    // a production environment.
                    'enable_gzip_for_uploads' => self::GZIP_UPLOADS_DISABLED,
                ),
                // If you want to pass in OAuth 2.0 settings, they will need to be
                // structured like this.
                'Goracash\Service\Authentication' => array(
                    // Keys for OAuth 2.0 access, see the API console
                    'client_id' => '',
                    'client_secret' => '',
                    'access_token' => '',
                    'access_token_limit' => '',
                ),
            ),
        );
        if ($iniFileLocation) {
            $ini = parse_ini_file($iniFileLocation, true);
            if (is_array($ini) && count($ini)) {
                $mergedConfiguration = $ini + $this->configuration;
                if (isset($ini['classes']) && isset($this->configuration['classes'])) {
                    $mergedConfiguration['classes'] = $ini['classes'] + $this->configuration['classes'];
                }
                $this->configuration = $mergedConfiguration;
            }
        }
    }

    /**
     * Set configuration specific to a given class.
     * @param $class string The class name for the configuration
     * @param $config string key or an array of configuration values
     * @param $value string optional - if $config is a key, the value
     */
    public function setClassConfig($class, $config, $value = null)
    {
        if (!is_array($config)) {
            if (!isset($this->configuration['classes'][$class])) {
                $this->configuration['classes'][$class] = array();
            }
            $this->configuration['classes'][$class][$config] = $value;
            return;
        }
        $this->configuration['classes'][$class] = $config;
    }

    public function getClassConfig($class, $key = null)
    {
        if (!isset($this->configuration['classes'][$class])) {
            return null;
        }
        if ($key === null) {
            return $this->configuration['classes'][$class];
        }
        if (isset($this->configuration['classes'][$class][$key])) {
            return $this->configuration['classes'][$class][$key];
        }
        return null;
    }

    /**
     * Return the configured logger class.
     * @return string
     */
    public function getLoggerClass()
    {
        return $this->configuration['logger_class'];
    }

    /**
     * Set the logger class.
     *
     * @param $class string the class name to set
     */
    public function setLoggerClass($class)
    {
        $this->setConfigurationKeyClass('logger_class', $class);
    }

    /**
     * Return the configured Auth class.
     * @return string
     */
    public function getAuthClass()
    {
        return $this->configuration['auth_class'];
    }

    /**
     * Set the auth class.
     *
     * @param $class string the class name to set
     */
    public function setAuthClass($class)
    {
        $this->setConfigurationKeyClass('auth_class', $class);
    }

    /**
     * Set the IO class.
     *
     * @param $class string the class name to set
     */
    public function setIoClass($class)
    {
        $this->setConfigurationKeyClass('io_class', $class);
    }

    /**
     * Return the configured IO class.
     *
     * @return string
     */
    public function getIoClass()
    {
        return $this->configuration['io_class'];
    }

    /**
     * Set the application name, this is included in the User-Agent HTTP header.
     * @param string $name
     */
    public function setApplicationName($name)
    {
        $this->configuration['application_name'] = $name;
    }
    /**
     * @return string the name of the application
     */
    public function getApplicationName()
    {
        return $this->configuration['application_name'];
    }

    /**
     * Set the client ID for the auth class.
     * @param $clientId string - the API console client ID
     */
    public function setClientId($clientId)
    {
        $this->setAuthConfig('client_id', $clientId);
    }

    /**
     * Get the client ID for the auth class.
     */
    public function getClientId()
    {
        return $this->getClassConfig('Goracash\Service\Authentication', 'client_id');
    }

    /**
     * Set the client secret for the auth class.
     * @param $secret string - the API console client secret
     */
    public function setClientSecret($secret)
    {
        $this->setAuthConfig('client_secret', $secret);
    }

    /**
     * Set environment base path
     * @param $basePath
     */
    public function setBasePath($basePath)
    {
        $this->configuration['base_path'] = $basePath;
    }

    /**
     * Get the client secret for the auth class.
     */
    public function getClientSecret()
    {
        return $this->getClassConfig('Goracash\Service\Authentication', 'client_secret');
    }

    /**
     * Set the client token for the auth class.
     * @param $accessToken string - the API console client secret
     * @param $accessTokenLimit
     */
    public function setAccessToken($accessToken, $accessTokenLimit = null)
    {
        $this->setAuthConfig('access_token', $accessToken);
        if ($accessTokenLimit) {
            $this->setAuthConfig('access_token_limit', $accessTokenLimit);
        }
    }

    /**
     * Get the client token for the auth class.
     */
    public function getAccessToken()
    {
       return $this->getClassConfig('Goracash\Service\Authentication', 'access_token');
    }

    /**
     * Get the client token limit for the auth class.
     */
    public function getAccessTokenLimit()
    {
        return $this->getClassConfig('Goracash\Service\Authentication', 'access_token_limit');
    }

    /**
     * @return string the base URL to use for API calls
     */
    public function getBasePath()
    {
        return $this->configuration['base_path'];
    }

    /**
     * Set the auth configuration for the current auth class.
     * @param $key - the key to set
     * @param $value - the parameter value
     */
    private function setAuthConfig($key, $value)
    {
        if (!isset($this->configuration['classes'][$this->getAuthClass()])) {
            $this->configuration['classes'][$this->getAuthClass()] = array();
        }
        $this->configuration['classes'][$this->getAuthClass()][$key] = $value;
    }

    /**
     * @param $configurationKey
     * @param $className
     */
    protected function setConfigurationKeyClass($configurationKey, $className)
    {
        $prev = $this->configuration[$configurationKey];
        if (!isset($this->configuration['classes'][$className]) &&
            isset($this->configuration['classes'][$prev])) {
            $this->configuration['classes'][$className] =
                $this->configuration['classes'][$prev];
        }
        $this->configuration[$configurationKey] = $className;
    }

}