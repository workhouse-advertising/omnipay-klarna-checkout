<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message\Hpp;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AbstractResponse;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Message\RequestInterface;

class AuthorizeResponse extends AbstractResponse implements RedirectResponseInterface
{
    /**
     * @inheritDoc
     */
    public function getRedirectData()
    {
        return null;
    }

    /**
     * @inheritDoc
     */
    public function getRedirectUrl()
    {
        // TODO: Need to validate that a redirect URL has been returned.
        return $this->data['redirect_url'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function isRedirect(): bool
    {
        return (bool) ($this->data['redirect_url'] ?? null);
    }

    /**
     * @inheritDoc
     */
    public function isSuccessful(): bool
    {
        // Authorize is only successful once it has been acknowledged
        return false;
    }

    /**
     * @inheritDoc
     */
    public function getTransactionReference()
    {
        return $this->data['session_id'] ?? null;
    }
}
