<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Database columns
 * @property int         id
 * @property string      name
 * @property string|null  created_at
 * @property string|null updated_at
 */
class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
}
