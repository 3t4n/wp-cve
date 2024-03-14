<?php

namespace LitExtension;


class LitConnector
{
    const ACTION_INSTALL = 'installConnector';
    const ACTION_UNINSTALL = 'uninstallConnector';
    const ACTION_CHECK = 'checkConnector';

    const URL_DOWNLOAD_CONNECTOR = 'https://cm.litextension.com/api/get-connector';

    protected $_rootPath;
    protected $_connectorPath;
    protected $_connectorFile;
    protected $_response;

    public function __construct()
    {
    	$plugin_name = plugin_basename(__FILE__);
    	$plugin_name = explode('/', $plugin_name)[0];
        $this->_rootPath = rtrim(get_home_path(), '/');
        $this->_connectorPath = WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $plugin_name. DIRECTORY_SEPARATOR . 'le_connector';
        $this->_connectorFile = $this->_connectorPath . DIRECTORY_SEPARATOR . "connector.php";
        $this->_response = $this->createResponse('success');

    }

    public function createResponse($result, $data = null, $msg = '', $code = 200){
        return array(
            'result' => $result,
            'data' => $data,
            'msg' => $msg,
            'code' => $code
        );
    }

    public function responseSuccess($data, $msg = '', $code = ''){
        return $this->createResponse('success', $data, $msg, $code);
    }

    public function execute($action, $token)
    {
        try {

            switch ($action) {
                case self::ACTION_CHECK:

                    return $this->responseSuccess($this->isConnectorExist());
                    break;
                case self::ACTION_INSTALL:
                    $this->_installConnector($token);
                    break;
                case self::ACTION_UNINSTALL:
                    $this->_unInstallBridge();
                    break;
                default:
                    if (!$action) {
                        throw new \Exception('Action is required!');
                    }

                    throw new \Exception('Unknown Action: ' . $action);
            }
        } catch (\Throwable $e) {
            $this->_handleError($e);
        } catch (\Exception $e) {
            $this->_handleError($e);
        }

        return json_encode($this->_response);
    }

    /**
     * @param \Exception|\Throwable $exception
     */
    protected function _handleError($exception)
    {
        $this->_response['result'] = 'error';
        $this->_response['code'] = $exception->getCode();
        $this->_response['msg'] = $exception->getMessage();
    }

    public function isConnectorExist()
    {
        return file_exists($this->_connectorFile);
    }

    public function newException($error_code){
        throw new \Exception(Connector_Errors::getErrorMessage($error_code), $error_code);
    }

    protected function _installConnector($token)
    {
        if(!$token){
            $this->newException(Connector_Errors::MODULE_ERROR_EMPTY_TOKEN);

        }
        if ($this->isConnectorExist()) {
            if(!$this->_changeToken($token)){
                $this->newException(Connector_Errors::CONNECTOR_FILE_PERMISSION);

            }
            return;
        }

        $this->_downloadConnector($token);
    }


    protected function _changeToken($token){
        $connector = file_get_contents($this->_connectorFile);
        preg_match('/^\s*define\s*\(\s*\'LECM_TOKEN\',\s*(\'|\")(.+)(\'|\")\s*\)\s*;/m', $connector, $match);
        if(!$match){
            return false;
        }
        $old_token = $match[2];
        if($old_token == $token){
            return true;
        }
        $connector = str_replace($old_token, $token, $connector);
        if(!$this->_checkConnectorFilePermission()){
            $this->newException(Connector_Errors::CONNECTOR_FILE_PERMISSION);
        }
        return file_put_contents($this->_connectorFile, $connector);
    }

    protected function _downloadConnector($token)
    {
        if (!$token) {
            $this->newException(Connector_Errors::MODULE_ERROR_EMPTY_TOKEN);
        }

        if (!$this->_checkDirPermission($this->_rootPath)) {
            $this->newException(Connector_Errors::MODULE_ERROR_ROOT_DIR_PERMISSION);
        }
        if(!file_exists($this->_connectorPath)){
            mkdir($this->_connectorPath, 0755);
        }
        @mkdir($this->_connectorPath, 0755);

        if (!is_dir($this->_connectorPath)) {
            $this->newException(Connector_Errors::MODULE_ERROR_PERMISSION);
        }


        if (!$this->_checkDirPermission($this->_connectorPath)) {
            $this->newException(Connector_Errors::MODULE_ERROR_INSTALLED_PERMISSION);
        }
	    $response = wp_remote_get(self::URL_DOWNLOAD_CONNECTOR);
        $response = json_decode($response['body'], 1);
        file_put_contents($this->_connectorFile, str_replace('__SAMPLE__LECM__TOKEN__', $token, base64_decode($response['data'])));

        $this->_response['code']= Connector_Errors::MODULE_CONNECTOR_SUCCESSFULLY_INSTALLED;
    }

    protected function _unInstallBridge()
    {
        if (!$this->isConnectorExist()) {
            return true;
        }

        return $this->_deleteDir($this->_connectorPath);
    }

    protected function _checkDirPermission($path)
    {
        if (!is_writable($path)) {
            @chmod($path, 0755);
        }

        return is_writable($path);
    }


    protected function _checkConnectorFilePermission()
    {
        if (!is_writable($this->_connectorFile)) {
            @chmod($this->_connectorFile, 0777);
        }

        return is_writable($this->_connectorFile);
    }

    protected function _deleteDir($dirPath)
    {
        if (is_dir($dirPath)) {
            $objects = scandir($dirPath);
            foreach ($objects as $object) {
                if ($object != '.' && $object != '..') {
                    if (filetype($dirPath . DIRECTORY_SEPARATOR . $object) == 'dir') {
                        $this->_deleteDir($dirPath . DIRECTORY_SEPARATOR . $object);
                    } else {
                        if (!unlink($dirPath . DIRECTORY_SEPARATOR . $object)) {
                            return false;
                        }
                    }
                }
            }
            reset($objects);
            if (!rmdir($dirPath)) {
                return false;
            }
        } else {
            return false;
        }

        return true;
    }

}