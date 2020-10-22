<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message\Hpp;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AbstractOrderRequest;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Http\Exception\NetworkException;
use Omnipay\Common\Http\Exception\RequestException;

/**
 * Creates a Klarna HPP order if it does not exist
 */
class AuthorizeRequest extends AbstractOrderRequest
{
    use PaymentUrlsDataTrait;

    /**
     * @inheritDoc
     *
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate(
            'session_id'
        );

        $data = [
            'payment_session_url' => $this->getBaseUrl() . '/payments/v1/sessions/' . $this->getSessionId(),
            'merchant_urls' => $this->getPaymentMerchantUrls(),
        ];

        // TODO: Consider making this configurable.
        $data['options']['place_order_mode'] = 'PLACE_ORDER';

        return $data;
    }

    /**
     * @return string|null
     */
    public function getSessionId()
    {
        return $this->getParameter('session_id');
    }

    /**
     * @param string $sessionId
     * @return $this
     */
    public function setSessionId(string $sessionId): self
    {
        $this->setParameter('session_id', $sessionId);
        return $this;
    }

    /**
     * @inheritDoc
     *
     * @throws InvalidResponseException
     * @throws RequestException when the HTTP client is passed a request that is invalid and cannot be sent.
     * @throws NetworkException if there is an error with the network or the remote server cannot be reached.
     */
    public function sendData($data)
    {
        $response = $this->sendRequest('POST', '/hpp/v1/sessions', $data);

        if ($response->getStatusCode() >= 400) {
            throw new InvalidResponseException($response->getReasonPhrase());
        }

        return new AuthorizeResponse($this, $this->getResponseBody($response));
    }
}
