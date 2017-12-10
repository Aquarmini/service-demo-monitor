<?php

namespace App\Jobs;

use App\Biz\Github\Release;
use App\Jobs\Contract\JobInterface;

class GithubReleaseEventJob implements JobInterface
{
    public $owner;

    public $repo;

    public function __construct($owner, $repo)
    {
        $this->owner = $owner;
        $this->repo = $repo;
    }

    public function handle()
    {
        return Release::getInstance()->isRelease($this->owner, $this->repo);
    }
}

