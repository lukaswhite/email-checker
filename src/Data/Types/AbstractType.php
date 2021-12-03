<?php


namespace Lukaswhite\EmailChecker\Data\Types;


abstract class AbstractType
{
    /**
     * @return string
     */
    abstract protected function filename(): string;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var array
     */
    protected $entries;

    /**
     * AbstractType constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return void
     */
    public function load()
    {
        $this->entries = $this->parse(file_get_contents(sprintf('%s/data/%s', $this->path, $this->filename())));
    }

    /**
     * @param string $domain
     * @return bool
     */
    public function has(string $domain): bool
    {
        if(!$this->entries) {
            $this->load();
        }
        return in_array($domain, $this->entries);
    }

    /**
     * @param string $data
     * @return array
     */
    protected function parse(string $data)
    {
        $lines = array_map(function(string $line){
            return trim(strtolower($line));
        }, explode("\n", $data));

        return array_filter($lines, function ($line) {
            return (0 === strlen($line) || '#' === $line[0]) ? false : $line;
        });
    }
}