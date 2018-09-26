<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mpociot\Versionable\VersionableTrait;

class ClientRecordView extends Model
{
    use SoftDeletes, VersionableTrait;

    protected $table = 'client_record_views';

    protected $dates = [
        'deleted_at',
    ];

    protected $fillable = [
        'client_id',
        'author_id',
        'comments',
    ];

    public static function log(Client $client, $comments = null)
    {
        $client->recordView()->create([
            'author_id' => auth()->user()->id,
            'comments'  => $comments
        ]);
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
