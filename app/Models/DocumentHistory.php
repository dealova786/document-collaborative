<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentHistory extends Model
{
    protected $fillable = [
        'dokumen_id',
        'user_id',
        'content',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
