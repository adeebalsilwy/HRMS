<?php

namespace App\Models;

use App\Traits\CreatedUpdatedDeletedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use CreatedUpdatedDeletedBy, HasFactory, SoftDeletes;

    protected $fillable = ['id', 'name'];

    // 👉 Links
    public function subCategories(): BelongsToMany
    {
        return $this->belongsToMany(SubCategory::class);
    }

    // La relación original con nombre diferente, mantenerla por compatibilidad
    public function subCategory(): BelongsToMany
    {
        return $this->belongsToMany(SubCategory::class);
    }

    /**
     * Relación muchos a muchos con assets
     */
    public function assets(): BelongsToMany
    {
        return $this->belongsToMany(Asset::class, 'category_sub_category_asset', 'category_id', 'asset_id');
    }
}
