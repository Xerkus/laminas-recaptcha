<?php
/**
 * @see       https://github.com/zendframework/ZendService_ReCaptcha for the canonical source repository
 * @copyright Copyright (c) 2005-2018 Zend Technologies USA Inc. (https://www.zend.com)
 * @license   https://github.com/zendframework/ZendService_ReCaptcha/blob/master/LICENSE.md New BSD License
 */

namespace ZendServiceTest\ReCaptcha;

use PHPUnit\Framework\TestCase;
use ZendService\ReCaptcha;
use Zend\Http\Response;

class ResponseTest extends TestCase
{
    /**
     * @var ReCaptcha\Response
     */
    protected $response;

    protected function setUp()
    {
        $this->response = new ReCaptcha\Response();
    }

    public function testSetAndGet()
    {
        /* Set and get status */
        $status = true;
        $this->response->setStatus($status);
        $this->assertTrue($this->response->getStatus());

        $status = false;
        $this->response->setStatus($status);
        $this->assertFalse($this->response->getStatus());

        /* Set and get the error codes */
        $errorCodes = 'foobar';
        $this->response->setErrorCodes($errorCodes);
        $this->assertSame([$errorCodes], $this->response->getErrorCodes());

        $errorCodes = ['foo', 'bar'];
        $this->response->setErrorCodes($errorCodes);
        $this->assertSame($errorCodes, $this->response->getErrorCodes());
    }

    public function testIsValid()
    {
        $this->response->setStatus(true);
        $this->assertTrue($this->response->isValid());
    }

    public function testIsInvalid()
    {
        $this->response->setStatus(false);
        $this->assertFalse($this->response->isValid());
    }

    public function testSetFromHttpResponse()
    {
        $status       = false;
        $errorCodes    = ['foo', 'bar'];
        $responseBody = json_encode([
            'success' => $status,
            'error-codes' => $errorCodes
        ]);
        $httpResponse = new Response();
        $httpResponse->setStatusCode(200);
        $httpResponse->getHeaders()->addHeaderLine('Content-Type', 'text/html');
        $httpResponse->setContent($responseBody);

        $this->response->setFromHttpResponse($httpResponse);

        $this->assertFalse($this->response->getStatus());
        $this->assertSame($errorCodes, $this->response->getErrorCodes());
    }

    public function testConstructor()
    {
        $status = true;
        $errorCodes = ['ok'];

        $response = new ReCaptcha\Response($status, $errorCodes);

        $this->assertTrue($response->getStatus());
        $this->assertSame($errorCodes, $response->getErrorCodes());
    }

    public function testConstructorWithHttpResponse()
    {
        $status       = false;
        $errorCodes   = ['foobar'];
        $responseBody = json_encode([
            'success' => $status,
            'error-codes' => $errorCodes
        ]);
        $httpResponse = new Response();
        $httpResponse->setStatusCode(200);
        $httpResponse->getHeaders()->addHeaderLine('Content-Type', 'text/html');
        $httpResponse->setContent($responseBody);

        $response = new ReCaptcha\Response(null, null, $httpResponse);

        $this->assertFalse($response->getStatus());
        $this->assertSame($errorCodes, $response->getErrorCodes());
    }
}
