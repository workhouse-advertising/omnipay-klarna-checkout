<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message\Hpp;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AbstractOrderRequest;
use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Http\Exception\NetworkException;
use Omnipay\Common\Http\Exception\RequestException;

class CompleteAuthorizeRequest extends AbstractOrderRequest
{
    /**
     * @inheritDoc
     *
     * @throws InvalidRequestException
     */
    public function getData()
    {
        $this->validate(
            'transactionReference'
        );

        $data = [];
        return $data;
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
        $response = $this->sendRequest('GET', '/hpp/v1/sessions/' . $this->getTransactionReference(), $data);

        if ($response->getStatusCode() >= 400) {
            throw new InvalidResponseException($response->getReasonPhrase());
        }

        return new CompleteAuthorizeResponse($this, $this->getResponseBody($response));
    }
}
