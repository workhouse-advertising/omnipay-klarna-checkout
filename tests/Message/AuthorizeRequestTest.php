<?php

namespace MyOnlineStore\Tests\Omnipay\KlarnaCheckout\Message;

use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AuthorizeRequest;
use MyOnlineStore\Omnipay\KlarnaCheckout\Message\AuthorizeResponse;
use Omnipay\Common\Exception\InvalidRequestException;

class AuthorizeRequestTest extends RequestTestCase
{
    use ItemDataTestTrait;

    /**
     * @var AuthorizeRequest
     */
    private $authorizeRequest;

    /**
     * @inheritdoc
     */
    protected function setUp()
    {
        parent::setUp();
        $this->authorizeRequest = new AuthorizeRequest($this->httpClient, $this->getHttpRequest());
    }

    /**
     * @return array
     */
    public function invalidRequestDataProvider()
    {
        $data = [
            'amount' => true,
            'currency' => true,
            'items' => [],
            'locale' => true,
            'notifyUrl' => true,
            'returnUrl' => true,
            'tax_amount' => true,
            'terms_url' => true,
        ];

        $cases = [];

        foreach ($data as $key => $value) {
            $cases[] = [array_diff_key($data, [$key => $value])];
        }

        return $cases;
    }

    /**
     * @dataProvider invalidRequestDataProvider
     *
     * @param array $requestData
     */
    public function testGetDataWillThrowExceptionForInvalidRequest(array $requestData)
    {
        $this->authorizeRequest->initialize($requestData);

        $this->setExpectedException(InvalidRequestException::class);
        $this->authorizeRequest->getData();
    }

    public function testGetDataWillReturnCorrectData()
    {
        $this->authorizeRequest->initialize([
            'locale' => 'nl_NL',
            'amount' => '100.00',
            'tax_amount' => 21,
            'returnUrl' => 'localhost/return',
            'notifyUrl' => 'localhost/notify',
            'termsUrl' => 'localhost/terms',
            'currency' => 'EUR',
            'validationUrl' => 'localhost/validate',
        ]);
        $this->authorizeRequest->setItems([$this->getItemMock()]);

        self::assertEquals(
            [
                'locale' => 'nl-NL',
                'order_amount' => 10000,
                'order_tax_amount' => 2100,
                'order_lines' => [$this->getExpectedOrderLine()],
                'merchant_urls' => [
                    'checkout' => 'localhost/return',
                    'confirmation' => 'localhost/return',
                    'push' => 'localhost/notify',
                    'terms' => 'localhost/terms',
                    'validation' => 'localhost/validate',
                ],
                'purchase_country' => 'NL',
                'purchase_currency' => 'EUR',
            ],
            $this->authorizeRequest->getData()
        );
    }

    public function testSendDataWillCreateOrderAndReturnResponse()
    {
        $inputData = ['request-data' => 'yey?'];
        $expectedData = ['response-data' => 'yey!'];

        $this->setExpectedPostRequest($inputData, $expectedData, self::BASE_URL.'/checkout/v3/orders');

        $this->authorizeRequest->initialize([
            'base_url' => self::BASE_URL,
            'merchant_id' => self::MERCHANT_ID,
            'secret' => self::SECRET,
        ]);
        $this->authorizeRequest->setRenderUrl('localhost/render');

        $authorizeResponse = $this->authorizeRequest->sendData($inputData);

        self::assertInstanceOf(AuthorizeResponse::class, $authorizeResponse);
        self::assertSame($expectedData, $authorizeResponse->getData());
        self::assertEquals('localhost/render', $authorizeResponse->getRedirectUrl());
    }

    public function testSendDataWillFetchOrderAndReturnResponseIfTransactionIdAlreadySet()
    {
        $inputData = ['request-data' => 'yey?'];
        $expectedData = ['response-data' => 'yey!'];

        $this->setExpectedGetRequest(
            $expectedData,
            self::BASE_URL.'/checkout/v3/orders/f60e69e8-464a-48c0-a452-6fd562540f37'
        );

        $this->authorizeRequest->initialize([
            'base_url' => self::BASE_URL,
            'merchant_id' => self::MERCHANT_ID,
            'secret' => self::SECRET,
            'transactionReference' => 'f60e69e8-464a-48c0-a452-6fd562540f37',
        ]);

        $response = $this->authorizeRequest->sendData($inputData);

        self::assertInstanceOf(AuthorizeResponse::class, $response);
        self::assertSame($expectedData, $response->getData());
    }
}
