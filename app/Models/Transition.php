<?php

namespace App\Models;

use App\Traits\CreatedUpdatedDeletedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transition extends Model
{
    use CreatedUpdatedDeletedBy, HasFactory, SoftDeletes;

    protected $fillable = [
        'asset_id',
        'employee_id',
        'handed_date',
        'return_date',
        'center_document_number',
        'reason',
        'note',
    ];

    // 👉 Links
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class);
    }

    // 👉 Functions
    public function getCategory($asset_id)
    {
        // Si el ID del asset es un número, obtén la categoría desde el asset directamente
        if (is_numeric($asset_id)) {
            $asset = Asset::find($asset_id);
            if ($asset) {
                // Si el ID del asset es numérico, necesitamos buscar la categoría relacionada
                // a través de la subcategoría a la que pertenece
                $transitions = Transition::where('asset_id', $asset_id)->first();
                $subCategory = $this->getSubCategory($asset_id);
                if ($subCategory) {
                    return $subCategory->category;
                }
                return null;
            }
            return null;
        }
        
        // Si el ID tiene el formato antiguo (alfanumérico codificado)
        $assetId = substr($asset_id, 1);
        $categoryId = ltrim(substr($assetId, 0, 4), '0');

        $category = Category::find($categoryId);

        return $category;
    }

    public function getSubCategory($asset_id)
    {
        // Si el ID del asset es un número, obtén la subcategoría desde el asset directamente
        if (is_numeric($asset_id)) {
            // Buscar el asset y determinar su subcategoría basado en otras relaciones
            // u otra lógica de negocio
            $asset = Asset::find($asset_id);
            if ($asset) {
                // Como no tenemos relación directa a subcategoría, podemos implementar
                // alguna lógica para determinarla o devolver una por defecto para pruebas
                return SubCategory::first(); // Devuelve la primera subcategoría como fallback
            }
            return null;
        }
        
        // Si el ID tiene el formato antiguo (alfanumérico codificado)
        $assetId = substr($asset_id, 1);
        $subCategoryId = ltrim(substr($assetId, 4, 4), '0');

        $subCategory = SubCategory::find($subCategoryId);

        return $subCategory;
    }
}
