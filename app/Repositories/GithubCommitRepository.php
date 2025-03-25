<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class GithubCommitRepository
{
    public function create(array $data)
    {
        return DB::table('github_commits')->insert($data);
    }
}