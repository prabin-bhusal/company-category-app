<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Company extends Model
{
    use HasFactory;

    protected $table = 'company';
    protected $fillable = ['category_id', 'title', 'image', 'description', 'status'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CompanyCategory::class, 'category_id');
    }
}
