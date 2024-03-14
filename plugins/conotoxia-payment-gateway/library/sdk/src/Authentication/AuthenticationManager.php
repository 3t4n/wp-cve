<?php

declare(strict_types=1);

namespace CKPL\Pay\Authentication;

use CKPL\Pay\Endpoint\AuthenticationEndpoint;
use CKPL\Pay\Exception\ClientException;
use CKPL\Pay\Exception\Exception;
use CKPL\Pay\Model\Response\AccessTokenResponseModel;
use CKPL\Pay\Security\Token\Token;
use CKPL\Pay\Security\Token\TokenInterface;
use CKPL\Pay\Service\BaseService;
use CKPL\Pay\Storage\DataConverter\ConvertibleInterface;
use CKPL\Pay\Storage\StorageInterface;
use DateTime;

/**
 * Class AuthenticationManager.
 *
 * @package CKPL\Pay\Authentication
 */
class AuthenticationManager extends BaseService implements AuthenticationManagerInterface
{
    /**
     * @throws \Exception
     *
     * @return bool
     */
    public function isAuthenticated(): bool
    {
        $token = $this->dependencyFactory->getSecurityManager()->getToken();

        return $token && !$token->isExpired();
    }

    /**
     * @param bool $forceAuthentication
     *
     * @throws ClientException
     * @throws Exception
     * @throws \Exception
     *
     * @return TokenInterface|null
     */
    public function authenticate(bool $forceAuthentication = false): ?TokenInterface
    {
        if (!$this->isAuthenticated() || $forceAuthentication) {
            $requestTime = new DateTime();

            $client = $this->dependencyFactory->createClient(
                new AuthenticationEndpoint(),
                $this->configuration,
                $this->dependencyFactory->getSecurityManager()
            );

            $client->request()->send();

            $authorizationModel = $client->getResponse()->getProcessedOutput();

            if ($authorizationModel instanceof AccessTokenResponseModel) {
                $token = new Token(
                    $authorizationModel->getAccessToken(),
                    $authorizationModel->getExpiresIn(),
                    $authorizationModel->getTokenType(),
                    $requestTime
                );

                if ($token instanceof ConvertibleInterface) {
                    $convertedToken = $token->convert();

                    $this->configuration->getStorage()->setItem(StorageInterface::TOKEN, $convertedToken);
                }
            } else {
                throw new Exception(static::UNSUPPORTED_RESPONSE_MODEL_EXCEPTION);
            }
        }

        return $this->dependencyFactory->getSecurityManager()->getToken();
    }
}
