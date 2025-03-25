<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GithubCommit extends Model
{
    protected $fillable = [
        'commit_id',
        'message',
        'author_name',
        'author_email',
        'committed_at',
    ];

    /**
     * GitHub validation rules
     */
    public static function githubRules(): array
    {
        return [
            'commits' => 'required|array',
            'commits.*.id' => 'required|string',
            'commits.*.message' => 'required|string',
            'commits.*.author.name' => 'required|string',
            'commits.*.author.email' => 'required|email',
        ];
    }
}
