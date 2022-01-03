<?php

namespace App\Http\Requests;

use App\Models\EquipmentStation;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class StoreRentalOrderRequest extends FormRequest
{
    protected $idd = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'station_id' => [
                'required',
                'numeric',
                Rule::exists('stations', 'id'),
            ],
            'van_id' => [
                'required',
                'numeric',
                Rule::exists('vans', 'id')
                    ->where('available', 1)
                    ->when('station_id', function ($query) {
                        return $query->where('station_id', $this->station_id);
                    }),
            ],
            'equipment' => [
                'array',
            ],
            'equipment.*' => [
                'array',
                'min:2',
            ],
            'equipment.*.id' => [
                'required',
                'numeric',
                Rule::exists('equipments', 'id'),
                Rule::exists('equipment_stations', 'equipment_id')
                    ->whereNot('quantity', 0)
                    ->when('station_id', function ($query) {
                        return $query->where('station_id', $this->station_id);
                    })
                ,
            ],
            'equipment.*.quantity' => [
                'required',
                'numeric',
            ],
        ];
    }
}
