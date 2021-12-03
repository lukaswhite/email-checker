<?php


namespace Lukaswhite\EmailChecker\Data\Types;


class Blacklist extends AbstractType
{
    /**
     * @return string
     */
    protected function filename(): string
    {
        return 'blacklist.txt';
    }

    /**
     * @return void
     */
    public function load()
    {
        parent::load();
        $this->entries = array_map(function(string $domain){
            if(substr($domain, 0, 1) === '.') {
            return substr($domain, 1);
            }
        }, $this->entries);
    }

}