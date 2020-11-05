<?php
declare(strict_types=1);

namespace MyOnlineStore\Omnipay\KlarnaCheckout\Message\Hpp;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

class UpdateSessionResponse extends AbstractResponse
{
    /**
     * @inheritDoc
     */
    public function isSuccessful(): bool
    {
        // TODO: Consider checking that the returned data is valid, although that should be a given
        //       assuming that Klarna always returns a response code >= 400 any time the data is invalid.
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getTransactionReference()
    {
        return $this->data['session_id'] ?? null;
    }
}
