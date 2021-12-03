<?php


namespace Lukaswhite\EmailChecker\Data;

use CzProject\GitPhp\Git;
use CzProject\GitPhp\GitException;
use Lukaswhite\EmailChecker\Exceptions\InvalidPathException;
use Lukaswhite\EmailChecker\Exceptions\MissingDataException;

class Sync
{
    /**
     * Sync constructor.
     *
     * Provide a path to a local directory where you wish to store the data files.
     *
     * Subsequent calls to the update() method will overwrite the data by pulling in
     * any changes from Git.
     *
     * @param string $path
     * @throws InvalidPathException
     */
    public function __construct(string $path)
    {
        $this->path = $path;
        $this->checkPath();
    }

    /**
     * Check the path provided. We look for a package.json to ensure that it looks like
     * the right sort of folder, then pull the name out to check whether it's what we're lookinng
     * for.
     *
     * @throws InvalidPathException
     */
    protected function checkPath()
    {
        if(!file_exists(sprintf('%s/package.json', $this->path))) {
            throw new InvalidPathException('Cannot find the repository in the folder.');
        }
        $parsed = json_decode(file_get_contents(sprintf('%s/package.json', $this->path)));
        if(!isset($parsed->name) || $parsed->name !== 'freemail') {
            throw new InvalidPathException('Looks to be the wrong repo.');
        }
    }

    /**
     * Fetch the remote data.
     *
     * @throws \CzProject\GitPhp\GitException
     */
    public function fetch()
    {
        $git = new Git;
        $git->cloneRepository('https://github.com/willwhite/freemail', $this->path);
    }

    /**
     * Update the local copy of the data.
     *
     * @throws GitException
     * @throws MissingDataException
     */
    public function update()
    {
        $git = new Git;
        try {
            $repo = $git->open($this->path);
        } catch (GitException $e) {
            throw new MissingDataException('Cannot find the data. Have you run the fetch command yet?');
        }
        $repo->pull('origin');
    }
}