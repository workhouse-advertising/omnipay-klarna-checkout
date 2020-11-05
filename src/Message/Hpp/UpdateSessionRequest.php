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
class UpdateSessionRequest extends AbstractOrderRequest
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
            'amount',
            'currency',
            'items',
            'locale',
            'purchase_country',
            'tax_amount',
            'session_id',
        );

        $data = $this->getOrderData();
        // $data['merchant_urls'] = $this->getMerchantUrls();

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
        // Update a payment session
        $response = $this->sendRequest('POST', '/payments/v1/sessions/' . $this->getSessionId(), $data);

        if ($response->getStatusCode() >= 400) {
            throw new InvalidResponseException($response->getReasonPhrase());
        }

        return new UpdateSessionResponse($this, $this->getResponseBody($response));
    }
}
