<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaFile extends Model
{
    protected $fillable = [
        'size',
        'title',
        'description',
        'mimeType',
        'extension',
        'path',
        'originalName'
    ];
}
