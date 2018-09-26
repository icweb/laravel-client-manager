<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Mpociot\Versionable\VersionableTrait;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laratrust\Traits\LaratrustUserTrait;

class User extends Authenticatable
{
    use LaratrustUserTrait;
    use Notifiable, VersionableTrait;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function clientIds()
    {
        $clients = Client::select('id');

        if(auth()->user()->can('need_to_know'))
        {
            $client_ids = $this->assignments()->select('client_id')->get()->pluck('id')->toArray();
            $clients = $clients->whereIn('id', $client_ids);
        }

        return (array) $clients->get()->pluck('id')->toArray();
    }

    public function assignments()
    {
        return $this->hasMany(ClientAssignment::class);
    }
}
