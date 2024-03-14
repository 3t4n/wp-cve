<?php

namespace Zahls\Models\Request;


class QrCodeScan extends \Zahls\Models\Base
{
    /**
     * mandatory
     *
     * @access  protected
     * @var     string
     */
    protected $sessionId;

    /**
     * @access  public
     * @return  string
     */
    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    /**
     * @access  public
     * @param   string   $sessionId
     * @return  void
     */
    public function setSessionId(string $sessionId): void
    {
        $this->sessionId = $sessionId;
    }

    /**
     * {@inheritdoc}
     */
    public function getResponseModel()
    {
        return new \Zahls\Models\Response\QrCodeScan();
    }
}