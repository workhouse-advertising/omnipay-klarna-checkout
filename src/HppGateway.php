<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\Hpp\AuthorizeRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\Hpp\CompleteAuthorizeRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\Hpp\CreateSessionRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\Hpp\UpdateSessionRequest;

class HppGateway extends Gateway
{
    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'KlarnaHPP';
    }

    /**
     * @inheritdoc
     */
    public function authorize(array $data = [])
    {
        return $this->createRequest(AuthorizeRequest::class, $data);
    }

    /**
     * @inheritdoc
     */
    public function createSession(array $data = [])
    {
        return $this->createRequest(CreateSessionRequest::class, $data);
    }

    /**
     * @inheritdoc
     */
    public function updateSession(array $data = [])
    {
        return $this->createRequest(UpdateSessionRequest::class, $data);
    }

    /**
     * @inheritdoc
     */
    public function completeAuthorize(array $data = [])
    {
        return $this->createRequest(CompleteAuthorizeRequest::class, $data);
    }
}
