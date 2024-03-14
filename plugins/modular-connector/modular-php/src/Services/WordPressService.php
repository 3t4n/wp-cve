<?php

namespace Modular\SDK\Services;

class WordPressService extends AbstractService
{
    /**
     * @param string $requestId
     * @param array $opts
     * @return \Modular\SDK\Objects\BaseObject
     * @throws \ErrorException
     */
    public function handleRequest(string $requestId, array $opts = [])
    {
        $opts += [
            'auth' => true,
        ];

        return $this->request('get', $this->buildPath('/site/manager/request/%s', $requestId), [], $opts);
    }

    /**
     * @param mixed $data
     * @param array $opts
     * @return \Modular\SDK\Objects\BaseObject
     * @throws \ErrorException
     */
    public function handleHook($data, array $opts = [])
    {
        $opts += [
            'auth' => true,
        ];

        return $this->raw('post', $this->buildPath('/site/manager/hook'), $data, $opts);
    }

    /**
     * @return mixed
     * @throws \ErrorException
     */
    public function getWhiteLabel()
    {
        $opts = [
            'auth' => true,
        ];

        return $this->raw('get', $this->buildPath('/site/manager/white-label'), [], $opts);
    }
}
