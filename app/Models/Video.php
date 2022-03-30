<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Attachment\Attachable;
use Orchid\Attachment\Models\Attachment;
use Orchid\Filters\Filterable;
use Orchid\Screen\AsSource;

class Video extends Model
{
    use AsSource, Attachable, Filterable;

    /**
     * @var array
     */
    protected $fillable = [
        'title',
        'video'
    ];

    /**
     * Name of columns to which http sorting can be applied
     *
     * @var array
     */
    protected $allowedSorts = [
        'title',
        'created_at',
        'updated_at'
    ];

    /**
     * Name of columns to which http filter can be applied
     *
     * @var array
     */
    protected $allowedFilters = [
        'title',
    ];

    protected $cast = [
        'video' => 'array'
    ];

    public function videos()
    {
        return $this->hasMany(Attachment::class)->where('group','video');
    }

}
