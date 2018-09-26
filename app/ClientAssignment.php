<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mpociot\Versionable\VersionableTrait;

class ClientAssignment extends Model
{
    use SoftDeletes, VersionableTrait;

    protected $table = 'client_assignments';

    protected $dates = [
        'deleted_at',
//        'active_at',
//        'expires_at',
    ];

    protected $fillable = [
        'author_id',
        'client_id',
        'user_id',
        'type',
        'comments',
//        'active_at',
//        'expires_at',
    ];

    protected $appends = [
//        'active',
//        'expired',
    ];

//    public function getActiveAttribute()
//    {
//        return strtotime($this->active_at) < time();
//    }
//
//    public function getExpiredAttribute()
//    {
//        return strtotime($this->expires_at) < time();
//    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
