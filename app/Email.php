<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mpociot\Versionable\VersionableTrait;

class Email extends Model
{
    use SoftDeletes, VersionableTrait;

    protected $table = 'client_emails';

    protected $dates = [
        'deleted_at',
        'active_at',
        'expires_at',
    ];

    protected $fillable = [
        'author_id',
        'client_id',
        'type',
        'verified',
        'primary',
        'email',
        'comments',
        'active_at',
        'expires_at',
    ];

    protected $appends = [
        'active',
        'expired',
    ];

    public function getActiveAttribute()
    {
        return strtotime($this->active_at) < time();
    }

    public function getExpiredAttribute()
    {
        return strtotime($this->expires_at) < time();
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }
}
