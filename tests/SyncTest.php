<?php

use Lukaswhite\EmailChecker\Data\Sync;

class SyncTest extends \PHPUnit\Framework\TestCase
{
    public function test_can_set_repo()
    {
        $sync = new Sync('./tests/fixtures/repo');
        $this->assertEquals('https://github.com/willwhite/freemail', $sync->getRepoUri());
        $sync->setRepoUri('https://github.com/forked/freemail');
        $this->assertEquals('https://github.com/forked/freemail', $sync->getRepoUri());
    }
}