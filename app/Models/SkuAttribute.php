<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class SkuAttribute extends Model
{

    public function skuValues(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SkuValue::class);
    }

    public function syncSkuValues(array $sku_values): array
    {
        $changes = [
            'created' => [], 'deleted' => []
        ];
        /** @var Collection $children */
        $children = $this->skuValues;
        /** @var Collection $sku_values */
        $sku_values = collect($sku_values);
        $changes['deleted'] = $children->filter(function ($child) use ($sku_values) {
            return empty($sku_values->where('name', $child->name)->first());
        }
        )->map(function ($child) {
            $name = $child->name;
            $child->delete();
            return $name;
        });
        $changes['created'] = $sku_values->filter(function ($sku_value) use ($children) {
            return empty($children->where('name', $sku_value->name)->first());
        })->map(function ($sku_value) {
            return $sku_value->name;
        });

        $this->skuValues()->saveMany($changes['created']->map(function ($name) {
            return SkuValue::create([
                'name' => $name,
            ]);
        }));
        return $changes;
    }
}
