<?php

use Lukaswhite\EmailChecker\Data\Sync;

class EmailTest extends \PHPUnit\Framework\TestCase
{
    public function test_can_set_create_from_array()
    {
        $email = \Lukaswhite\EmailChecker\Email::createFromArray([
            'address'   =>  'joe@bloggs.com',
            'blacklisted' => true,
            'disposable' => false,
            'free' => true,
        ]);

        $this->assertInstanceOf(\Lukaswhite\EmailChecker\Email::class, $email);
        $this->assertEquals('joe@bloggs.com', $email->getAddress());
        $this->assertTrue($email->isBlacklisted());
        $this->assertFalse($email->isDisposable());
        $this->assertTrue($email->isFree());

        $email2 = \Lukaswhite\EmailChecker\Email::createFromArray([
            'address'   =>  'bob@bloggs.com',
            'blacklisted' => false,
            'disposable' => true,
            'free' => false,
        ]);

        $this->assertInstanceOf(\Lukaswhite\EmailChecker\Email::class, $email);
        $this->assertEquals('bob@bloggs.com', $email2->getAddress());
        $this->assertFalse($email2->isBlacklisted());
        $this->assertTrue($email2->isDisposable());
        $this->assertFalse($email2->isFree());

    }

    public function test_creating_from_array_requires_address()
    {
        $this->expectException(\Lukaswhite\EmailChecker\Exceptions\InvalidEmailArrayDataException::class);
        $email = \Lukaswhite\EmailChecker\Email::createFromArray([
            'blacklisted' => true,
            'disposable' => false,
            'free' => true,
        ]);
    }

    public function test_can_output_as_array()
    {
        $email = new \Lukaswhite\EmailChecker\Email('joe@bloggs.com');
        $email
            ->setBlacklisted(true)
            ->setFree(true);

        $this->assertEquals([
            'address'   =>  'joe@bloggs.com',
            'blacklisted' => true,
            'disposable' => false,
            'free' => true,
        ], $email->toArray());
    }

    public function test_can_output_as_json()
    {
        $in = new \Lukaswhite\EmailChecker\Email('joe@bloggs.com');
        $in
            ->setBlacklisted(true)
            ->setFree(true);

        $email = \Lukaswhite\EmailChecker\Email::createFromArray(
            json_decode(json_encode($in),true)
        );

        $this->assertEquals('joe@bloggs.com', $email->getAddress());
        $this->assertTrue($email->isBlacklisted());
        $this->assertFalse($email->isDisposable());
        $this->assertTrue($email->isFree());
    }
}