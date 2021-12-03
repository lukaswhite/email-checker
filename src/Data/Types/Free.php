<?php


namespace Lukaswhite\EmailChecker\Data\Types;


class Free extends AbstractType
{
    /**
     * @return string
     */
    protected function filename(): string
    {
        return 'free.txt';
    }

}