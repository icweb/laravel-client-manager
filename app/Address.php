<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mpociot\Versionable\VersionableTrait;

class Address extends Model
{
    use SoftDeletes, VersionableTrait;

    protected $table = 'client_addresses';

    protected $dates = [
        'deleted_at',
        'active_at',
        'expires_at',
    ];

    protected $fillable = [
        'author_id',
        'client_id',
        'type',
        'primary',
        'address_line_1',
        'address_line_2',
        'address_line_3',
        'city',
        'state',
        'zip_code',
        'comments',
        'active_at',
        'expires_at',
    ];

    protected $appends = [
        'address',
        'expired',
        'active',
    ];

    public function getActiveAttribute()
    {
        return strtotime($this->active_at) < time();
    }

    public function getAddressAttribute()
    {
        $address = '';
        if($this->address_line_1) $address .= $this->address_line_1 . '<br>';
        if($this->address_line_2) $address .= $this->address_line_2 . '<br>';
        if($this->address_line_3) $address .= $this->address_line_3 . '<br>';
        if($this->city) $address .= $this->city . ' ';
        if($this->state) $address .= $this->state . ' ';
        if($this->zip_code) $address .= $this->zip_code . ' ';

        return $address;
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
