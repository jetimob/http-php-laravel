<?php

namespace Jetimob\Http;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use Jetimob\Http\Contracts\HttpProviderContract;
use Jetimob\Http\Contracts\HydratableContract;
use Jetimob\Http\Exceptions\InvalidArgumentException;

/**
 * Helper class that can be used to speed up the development of SDK's.
 */
abstract class AbstractApi
{
    protected Http $http;
    protected ?string $exceptionClass = null;

    public function __construct(HttpProviderContract $httpProvider)
    {
        $this->http = $httpProvider->getHttpInstance();
    }

    public function getHttp(): Http
    {
        return $this->http;
    }

    /**
     * Creates base Guzzle request used by the other functions.
     * This is where you configure a request to be authorized and/or with default headers.
     *
     * @param       $method
     * @param       $path
     * @param array $headers
     * @param null  $body
     *
     * @return Request
     */
    abstract protected function makeBaseRequest($method, $path, array $headers = [], $body = null): Request;

    /**
     * Wraps a Guzzle exception into another type so that we can expose properties sent by a server response error.
     *
     * @param ClientException | RequestException $exception
     * @param string|null                        $into The class that the exception should be wrapped into
     *
     * @return \Throwable
     * @throws \JsonException
     */
    protected function wrapException($exception, ?string $into): \Throwable
    {
        if (is_null($into)) {
            return $exception;
        }

        if (!class_exists($into)) {
            return new InvalidArgumentException("$into is not a valid class");
        }

        if (!is_subclass_of($into, RequestException::class)) {
            return new InvalidArgumentException("$into MUST inherit from " . RequestException::class);
        }

        if (!is_subclass_of($into, HydratableContract::class)) {
            return new InvalidArgumentException("$into MUST implement " . HydratableContract::class);
        }

        $wrapped = new $into(
            $exception->getMessage(),
            $exception->getRequest(),
            $exception->getResponse(),
            $exception->getPrevious(),
            $exception->getHandlerContext(),
        );

        $response = $exception->getResponse();

        if (is_null($response)) {
            return $wrapped;
        }

        $data = $response->getBody()->getContents();
        $response->getBody()->rewind();

        try {
            $decodedData = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
            if (empty($decodedData)) {
                return $wrapped;
            }

            $wrapped->hydrate($decodedData);
        } catch (\JsonException) {
            if (method_exists($wrapped, 'handleServerError')) {
                $wrapped->handleServerError();
            }
        }

        return $wrapped;
    }

    /**
     * Makes a request, using {@see AbstractApi::makeBaseRequest()} as base, that will have its response mapped into the
     * given $responseClass.
     * This response class MUST inherit from \Jetimob\Http\Response.
     *
     * @return Response
     * @throws \Throwable
     * @throws \JsonException
     */
    protected function mappedRequest(
        string $method,
        string $path,
        string $responseClass,
        $options
    ) {
        try {
            return $this->http->sendExpectingResponseClass(
                $this->makeBaseRequest($method, $path),
                $responseClass,
                $options
            );
        } catch (RequestException | ClientException $e) {
            throw $this->wrapException($e, $this->exceptionClass);
        }
    }

    /**
     * Makes a request, using {@see AbstractApi::makeBaseRequest()} as base.
     *
     * @throws \JsonException
     * @throws \Throwable
     */
    protected function request(string $method, string $path): \GuzzleHttp\Psr7\Response
    {
        try {
            return $this->http->send(
                $this->makeBaseRequest($method, $path)
            );
        } catch (RequestException | ClientException $e) {
            throw $this->wrapException($e, $this->exceptionClass);
        }
    }

    /**
     * @param string $path
     * @param string $responseClass
     * @param array  $options
     *
     * @return Response
     * @throws \JsonException
     * @throws \Throwable
     * @see AbstractApi::mappedRequest()
     */
    protected function mappedGet(
        string $path,
        string $responseClass,
        array $options = []
    ) {
        return $this->mappedRequest('get', $path, $responseClass, $options);
    }

    /**
     * @param string $path
     * @param string $responseClass
     * @param array  $options Guzzle Options
     *
     * @return Response
     * @throws \JsonException
     * @throws \Throwable
     * @see AbstractApi::mappedRequest()
     */
    protected function mappedPut(
        string $path,
        string $responseClass,
        array $options = []
    ) {
        return $this->mappedRequest('put', $path, $responseClass, $options);
    }

    /**
     * @param string $path
     * @param string $responseClass
     * @param array  $options Guzzle Options
     *
     * @return Response
     * @throws \JsonException
     * @throws \Throwable
     * @see AbstractApi::mappedRequest()
     */
    protected function mappedPost(
        string $path,
        string $responseClass,
        array $options = []
    ) {
        return $this->mappedRequest('post', $path, $responseClass, $options);
    }

    /**
     * @param string $path
     * @param string $responseClass
     * @param array  $options Guzzle Options
     *
     * @return Response
     * @throws \JsonException
     * @throws \Throwable
     * @see AbstractApi::mappedRequest()
     */
    protected function mappedPatch(
        string $path,
        string $responseClass,
        array $options = []
    ) {
        return $this->mappedRequest('patch', $path, $responseClass, $options);
    }
}
