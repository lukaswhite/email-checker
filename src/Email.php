<?php


namespace Lukaswhite\EmailChecker;



class Email
{
    /**
     * @var string
     */
    protected $address;

    /**
     * @var bool
     */
    protected $blacklisted = false;

    /**
     * @var bool
     */
    protected $free = false;

    /**
     * @var bool
     */
    protected $disposable = false;

    /**
     * Email constructor.
     * @param string $address
     */
    public function __construct(string $address)
    {
        $this->address = $address;
    }

    /**
     * @return string
     */
    public function getDomain(): string
    {
        return strtolower(explode('@', $this->address)[1]);
    }

    /**
     * @return bool
     */
    public function isBlacklisted(): bool
    {
        return $this->blacklisted;
    }

    /**
     * @param bool $blacklisted
     */
    public function setBlacklisted(bool $blacklisted): void
    {
        $this->blacklisted = $blacklisted;
    }

    /**
     * @return bool
     */
    public function isFree(): bool
    {
        return $this->free;
    }

    /**
     * @param bool $free
     */
    public function setFree(bool $free): void
    {
        $this->free = $free;
    }

    /**
     * @return bool
     */
    public function isDisposable(): bool
    {
        return $this->disposable;
    }

    /**
     * @param bool $disposable
     */
    public function setDisposable(bool $disposable): void
    {
        $this->disposable = $disposable;
    }



}