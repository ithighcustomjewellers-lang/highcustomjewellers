<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'sequence_id',
        'type',
        'file_path',
        'link'
    ];

    // 🔗 belongs to sequence
    public function sequence()
    {
        return $this->belongsTo(Sequence::class);
    }
}
