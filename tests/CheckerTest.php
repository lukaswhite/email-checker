<?php

use Lukaswhite\EmailChecker\Checker;
use Lukaswhite\EmailChecker\Email;

class CheckerTest extends \PHPUnit\Framework\TestCase
{
    public function test_checks_for_disposable_email_addresses()
    {
        $checker = new \Lukaswhite\EmailChecker\Checker('./tests/fixtures/repo');
        $result = $checker->check('hello@mailinator.com');
        $this->assertInstanceOf(Email::class, $result);
        $this->assertTrue($result->isDisposable());
    }

    public function test_checks_for_free_email_addresses()
    {
        $checker = new \Lukaswhite\EmailChecker\Checker('./tests/fixtures/repo');
        $result = $checker->check('dfdsfds@11mail.com');
        $this->assertInstanceOf(Email::class, $result);
        $this->assertTrue($result->isFree());
    }

    public function test_checks_for_blacklisted_email_addresses()
    {
        $checker = new \Lukaswhite\EmailChecker\Checker('./tests/fixtures/repo');
        $result = $checker->check('hello@zzn.com');
        $this->assertInstanceOf(Email::class, $result);
        $this->assertTrue($result->isBlacklisted());
    }

    public function test_requires_valid_email_address()
    {
        $this->expectException(\Lukaswhite\EmailChecker\Exceptions\InvalidEmailException::class);
        $checker = new \Lukaswhite\EmailChecker\Checker('./tests/fixtures/repo');
        $checker->check('not an email');
    }

    public function test_checks_folder_exists()
    {
        $this->expectException(\Lukaswhite\EmailChecker\Exceptions\MissingDataException::class);
        $checker = new \Lukaswhite\EmailChecker\Checker('./tests/fixtures/not-there');
        $result = $checker->check('hello@mailinator.com');
    }

    public function test_checks_for_package_json()
    {
        $this->expectException(\Lukaswhite\EmailChecker\Exceptions\InvalidPathException::class);
        $this->expectExceptionMessage('Cannot find the repository in the folder.');
        $checker = new \Lukaswhite\EmailChecker\Checker('./tests/fixtures/repo-no-package-json');
        $result = $checker->check('hello@mailinator.com');
    }

    public function test_checks_package_json_has_correct_name()
    {
        $this->expectException(\Lukaswhite\EmailChecker\Exceptions\InvalidPathException::class);
        $this->expectExceptionMessage('Looks to be the wrong repo.');
        $checker = new \Lukaswhite\EmailChecker\Checker('./tests/fixtures/repo-wrong-package-json');
        $result = $checker->check('hello@mailinator.com');
    }
}