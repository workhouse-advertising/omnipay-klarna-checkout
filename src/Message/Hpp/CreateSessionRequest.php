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
class CreateSessionRequest extends AbstractOrderRequest
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
            'tax_amount'
        );

        $data = $this->getOrderData();
        // $data['merchant_urls'] = $this->getMerchantUrls();

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
        // Create a payment session
        $response = $this->sendRequest('POST', '/payments/v1/sessions', $data);

        if ($response->getStatusCode() >= 400) {
            throw new InvalidResponseException($response->getReasonPhrase());
        }

        return new CreateSessionResponse($this, $this->getResponseBody($response));
    }
}
