<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Mpociot\Versionable\VersionableTrait;

class AppList extends Model
{
    use SoftDeletes, VersionableTrait;

    protected $table = 'lists';

    protected $dates = [
        'deleted_at',
    ];

    protected $fillable = [
        'system',
        'list_name',
        'item_title',
    ];

    public static function items($list_name)
    {
        return AppList::select('item_title')
            ->where(['list_name' => $list_name])
            ->get()
            ->pluck('item_title')
            ->toArray();
    }

    public function canDelete()
    {
        return $this->system ?
            (AppList::where(['list_name' => $this->list_name])->get()->count() > 1)
            : true;
    }
}
