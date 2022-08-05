<?php


namespace Lukaswhite\EmailChecker\Data;

use CzProject\GitPhp\Commit;
use CzProject\GitPhp\Git;
use CzProject\GitPhp\GitException;
use Lukaswhite\EmailChecker\Exceptions\InvalidPathException;
use Lukaswhite\EmailChecker\Exceptions\MissingDataException;

/**
 * Class Sync
 * @package Lukaswhite\EmailChecker\Data
 */
class Sync
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $repoUri = 'https://github.com/willwhite/freemail';

    /**
     * Sync constructor.
     *
     * Provide a path to a local directory where you wish to store the data files.
     *
     * Subsequent calls to the update() method will overwrite the data by pulling in
     * any changes from Git.
     *
     * @param string $path
     * @param bool $checkPath
     * @throws InvalidPathException
     */
    public function __construct(string $path, bool $checkPath = true)
    {
        $this->path = $path;
        if($checkPath) {
            $this->checkPath();
        }
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
     * @codeCoverageIgnore
     */
    public function fetch()
    {
        $git = new Git;
        $git->cloneRepository($this->repoUri, $this->path);
    }

    /**
     * Update the local copy of the data.
     *
     * @throws GitException
     * @throws MissingDataException
     * @codeCoverageIgnore
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

    /**
     * @return Commit
     * @throws MissingDataException
     * @codeCoverageIgnore
     */
    public function getLastCommit(): Commit
    {
        $git = new Git;
        try {
            $repo = $git->open($this->path);
            return $repo->getLastCommit();
        } catch (GitException $e) {
            throw new MissingDataException('Cannot find the data. Have you run the fetch command yet?');
        }
    }

    /**
     * @return \DateTimeImmutable
     * @throws MissingDataException
     * @codeCoverageIgnore
     */
    public function getLastUpdated(): \DateTimeImmutable
    {
        return $this->getLastCommit()->getDate();
    }

    /**
     * @return string
     */
    public function getRepoUri(): string
    {
        return $this->repoUri;
    }

    /**
     * @param string $repo
     */
    public function setRepoUri(string $repoUri): void
    {
        $this->repoUri = $repoUri;
    }

}