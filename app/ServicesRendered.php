<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mpociot\Versionable\VersionableTrait;

class ServicesRendered extends Model
{
    use SoftDeletes, VersionableTrait;

    protected $table = 'client_rendered_services';

    protected $dates = [
        'deleted_at',
    ];

    protected $fillable = [
        'client_id',
        'service_id',
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

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }
}
