<?php

namespace Jetimob\Http\Tests\Unit\DummyData;

use Jetimob\Http\Traits\Serializable;

class JsonPlaceholderUser
{
    use Serializable;

    protected int $id;
    protected string $name;
    protected string $username;
    protected string $email;
    protected JsonPlaceholderAddress $address;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getAddress(): JsonPlaceholderAddress
    {
        return $this->address;
    }

    /**
     * @param int $id
     * @return JsonPlaceholderUser
     */
    public function setId(int $id): JsonPlaceholderUser
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $name
     * @return JsonPlaceholderUser
     */
    public function setName(string $name): JsonPlaceholderUser
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param string $username
     * @return JsonPlaceholderUser
     */
    public function setUsername(string $username): JsonPlaceholderUser
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @param string $email
     * @return JsonPlaceholderUser
     */
    public function setEmail(string $email): JsonPlaceholderUser
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param JsonPlaceholderAddress $address
     * @return JsonPlaceholderUser
     */
    public function setAddress(JsonPlaceholderAddress $address): JsonPlaceholderUser
    {
        $this->address = $address;
        return $this;
    }
}
