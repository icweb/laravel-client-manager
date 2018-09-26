<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mpociot\Versionable\VersionableTrait;

class Service extends Model
{
    use SoftDeletes, VersionableTrait;

    protected $table = 'client_services';

    protected $dates = [
        'deleted_at',
        'active_at',
        'expires_at',
    ];

    protected $fillable = [
        'client_id',
        'service_id',
        'author_id',
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

    public function rendered()
    {
        return $this->hasMany(ServicesRendered::class, 'service_id', 'id');
    }

    public function service()
    {
        return $this->belongsTo(AppList::class, 'service_id', 'id');
    }
}
