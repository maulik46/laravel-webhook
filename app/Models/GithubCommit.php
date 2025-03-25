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
}
