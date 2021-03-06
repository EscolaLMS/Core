<?php

namespace EscolaLms\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    use HasFactory;

    public $table = 'attachments';

    public $fillable = [
        'path',
        'filename',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'user_id');
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->path);
    }

    public function getSizeAttribute(): string
    {
        return Storage::size($this->path);
    }
}
