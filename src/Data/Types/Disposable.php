<?php


namespace Lukaswhite\EmailChecker\Data\Types;


class Disposable extends AbstractType
{
    /**
     * @return string
     */
    protected function filename(): string
    {
        return 'disposable.txt';
    }

}