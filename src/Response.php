<?php

namespace Jetimob\Http;

use Jetimob\Http\Traits\Serializable;
use Psr\Http\Message\ResponseInterface;

class Response extends \GuzzleHttp\Psr7\Response implements \JsonSerializable
{
    use Serializable;

    /**
     * @var array
     */
    protected array $container = [];

    /**
     * Hydrates this instance properties with the response's body.
     * i.e.: given a response with the payload of:
     * <code>
     * {
     *   "username": "_john_",
     *   "email": "john.doe@mail.co"
     *   "product_ids": [22, 59, 91]
     * }
     * </code>
     * If this instance declares the typed variables `string $username`, `string $email` and `array $product_ids`, they
     * will be attributed with their respective named properties from the response.
     * i.e.: $this->email === john.doe@mail.co
     * @return $this
     * @throws \JsonException
     */
    public function hydrateWithBody(): self
    {
        $data = $this->getBody()->getContents();
        // rewind so that the contents can be accessed again
        $this->getBody()->rewind();

        $decodedData = json_decode($data, true, 512, JSON_THROW_ON_ERROR);

        if (empty($decodedData)) {
            return $this;
        }

        $this->hydrate($decodedData);

        return $this;
    }

    public static function fromGuzzleResponse(ResponseInterface $response): self
    {
        return new static(
            $response->getStatusCode(),
            $response->getHeaders(),
            $response->getBody(),
            $response->getProtocolVersion(),
            $response->getReasonPhrase(),
        );
    }

    /**
     * @return array
     */
    public function getContainer(): array
    {
        return $this->container;
    }
}
