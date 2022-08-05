<?php


namespace Lukaswhite\EmailChecker;


use Lukaswhite\EmailChecker\Data\Sync;
use Lukaswhite\EmailChecker\Data\Types\Blacklist;
use Lukaswhite\EmailChecker\Data\Types\Disposable;
use Lukaswhite\EmailChecker\Data\Types\Free;
use Lukaswhite\EmailChecker\Exceptions\InvalidEmailException;
use Lukaswhite\EmailChecker\Exceptions\MissingDataException;

/**
 * Class Checker
 * @package Lukaswhite\EmailChecker
 */
class Checker
{
    /**
     * @var string
     */
    protected $path;

    /**
     * Checker constructor.
     * @param string $path
     * @throws Exceptions\InvalidPathException
     * @throws MissingDataException
     */
    public function __construct(string $path = './repo')
    {
        if(!file_exists($path)) {
            throw new MissingDataException(
                'The path directory does not exist. Have you run the fetch() method?'
            );
        }

        // Whilst this doesn't appear to do anything, it will check the provided path and
        // throw an exception if it's not valid.
        new Sync($path);

        $this->path = $path;
    }

    /**
     * Check an e-mail address. The returned object will indicate whether it's from a free provider,
     * a provider of disposable/throwaway addresses, or if the domain has been blacklisted.
     *
     * @param string $address
     * @return Email|mixed
     * @throws InvalidEmailException
     */
    public function check(string $address)
    {
        if (($email = filter_var($address, FILTER_VALIDATE_EMAIL)) === false) {
            throw new InvalidEmailException('Invalid e-mail address');
        }

        $email = new Email($address);

        if ((new Disposable($this->path))->has($email->getDomain())) {
            $email->setDisposable(true);
        }

        if ((new Free($this->path))->has($email->getDomain())) {
            $email->setFree(true);
        }

        if ((new Blacklist($this->path))->has($email->getDomain())) {
            $email->setBlacklisted(true);
        }

        return $email;

    }
}