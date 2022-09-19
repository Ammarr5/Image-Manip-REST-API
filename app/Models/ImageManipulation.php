<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImageManipulation extends Model
{
    use HasFactory;

    protected $updated_at = null;

    protected $fillable = [
        "created_at", "user_id", "original_name", "original_path", "output_name", "output_path", "type"
    ];
}
