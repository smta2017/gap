<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Link extends Model
{
    use HasFactory;

    protected $fillable = [
        'link',
        'type',
        'linkable_id',
        'linkable_type',
    ];

    public function linkable()
    {
        return $this->morphTo();
    }
}
