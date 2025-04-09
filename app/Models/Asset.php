<?php

namespace App\Models;

use App\Traits\CreatedUpdatedDeletedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use CreatedUpdatedDeletedBy, HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'old_id',
        'serial_number',
        'status',
        'description',
        'in_service',
        'real_price',
        'expected_price',
        'acquisition_date',
        'acquisition_type',
        'funded_by',
        'note',
    ];

    protected $casts = [
        'id' => 'string',
    ];

    // 👉 Links
    public function transitions(): HasMany
    {
        return $this->hasMany(Transition::class);
    }
    
    /**
     * Relación muchos a muchos con categorías a través de subcategorías
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_sub_category_asset', 'asset_id', 'category_id');
    }
    
    /**
     * Relación muchos a muchos con subcategorías
     */
    public function categorySubCategories()
    {
        return $this->belongsToMany(SubCategory::class, 'category_sub_category_asset', 'asset_id', 'sub_category_id');
    }
}
