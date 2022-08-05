<?php


namespace Lukaswhite\EmailChecker;



use Lukaswhite\EmailChecker\Exceptions\InvalidEmailArrayDataException;

class Email implements \JsonSerializable
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
     * @param array $data
     * @return static
     * @throws InvalidEmailArrayDataException
     */
    public static function createFromArray(array $data): self
    {
        if(!array_key_exists('address', $data) || empty($data['address'])){
            throw new InvalidEmailArrayDataException('Missing address');
        }
        $email = new self($data['address']);
        if(array_key_exists('blacklisted',$data)){
            $email->setBlacklisted((bool)$data['blacklisted']);
        }
        if(array_key_exists('free',$data)){
            $email->setFree((bool)$data['free']);
        }
        if(array_key_exists('disposable',$data)){
            $email->setDisposable((bool)$data['disposable']);
        }
        return $email;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
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
     * @return self
     */
    public function setBlacklisted(bool $blacklisted): self
    {
        $this->blacklisted = $blacklisted;
        return $this;
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
     * @return self
     */
    public function setFree(bool $free): self
    {
        $this->free = $free;
        return $this;
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
     * @return self
     */
    public function setDisposable(bool $disposable): self
    {
        $this->disposable = $disposable;
        return $this;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'address'       =>  $this->address,
            'blacklisted'   =>  $this->blacklisted,
            'disposable'    =>  $this->disposable,
            'free'          =>  $this->free,
        ];
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

}