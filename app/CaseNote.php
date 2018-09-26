<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mpociot\Versionable\VersionableTrait;

class CaseNote extends Model
{
    use SoftDeletes, VersionableTrait;

    protected $table = 'case_notes';

    protected $dates = [
        'deleted_at',
    ];

    protected $fillable = [
        'client_id',
        'author_id',
        'comments',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }
}
