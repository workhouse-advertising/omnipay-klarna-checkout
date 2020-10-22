<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message\Hpp;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\RuntimeException;

trait PaymentUrlsDataTrait
{
    /**
     * @return array
     *
     * @throws InvalidRequestException
     */
    public function getPaymentMerchantUrls(): array
    {
        // $this->validate('backUrl', 'returnUrl', 'failureUrl', 'errorUrl');
        // TODO: Consider removing validation here as these URLs are all marked as optional in the Klarna docs.
        $this->validate('backUrl', 'cancelUrl', 'returnUrl');

        // TODO: Check to see if we can just provide `null` values instead of checking for a value for each URL.
        $merchantUrls = [
            'success' => $this->getReturnUrl(),
            'cancel' => $this->getCancelUrl(),
            'back' => $this->getBackUrl(),
        ];

        if ($notifyUrl = $this->getNotifyUrl()) {
            $merchantUrls['status_update'] = $notifyUrl;
        }

        if ($failureUrl = $this->getFailureUrl()) {
            $merchantUrls['failure'] = $failureUrl;
        }

        if ($errorUrl = $this->getErrorUrl()) {
            $merchantUrls['error'] = $errorUrl;
        }

        return $merchantUrls;
    }

    /**
     * @return string|null
     */
    abstract public function getCancelUrl();

    /**
     * @return string|null
     */
    abstract public function getNotifyUrl();

    /**
     * @return string|null
     */
    abstract public function getReturnUrl();

    /**
     * @return string|null
     */
    public function getBackUrl()
    {
        return $this->getParameter('backUrl');
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setBackUrl(string $url): self
    {
        $this->setParameter('backUrl', $url);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getFailureUrl()
    {
        return $this->getParameter('failureUrl');
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setFailureUrl(string $url): self
    {
        $this->setParameter('failureUrl', $url);
        return $this;
    }

    /**
     * @return string|null
     */
    public function getErrorUrl()
    {
        return $this->getParameter('errorUrl');
    }

    /**
     * @param string $url
     *
     * @return $this
     */
    public function setErrorUrl(string $url): self
    {
        $this->setParameter('errorUrl', $url);
        return $this;
    }

    /**
     * @param string $validatable,... a variable length list of required parameters
     *
     * @throws InvalidRequestException
     */
    abstract public function validate();

    /**
     * @param string $key
     *
     * @return mixed
     */
    abstract protected function getParameter($key);

    /**
     * Set a single parameter
     *
     * @param string $key   The parameter key
     * @param mixed  $value The value to set
     *
     * @return AbstractRequest Provides a fluent interface
     *
     * @throws RuntimeException if a request parameter is modified after the request has been sent.
     */
    abstract protected function setParameter($key, $value);
}
