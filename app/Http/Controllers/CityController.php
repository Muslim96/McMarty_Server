<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $cities = City::withCount(['restaurants', 'employers'])
            ->when($request->q, fn ($q, $s) => $q->where('name', 'like', "%$s%"))
            ->orderBy('name')->paginate(15)->withQueryString();

        return view('cities.index', compact('cities'));
    }

    public function create()
    {
        return view('cities.form', ['city' => new City]);
    }

    public function store(Request $request)
    {
        City::create($this->data($request));

        return redirect()->route('cities.index')->with('success', 'Город добавлен.');
    }

    public function edit(City $city)
    {
        return view('cities.form', compact('city'));
    }

    public function update(Request $request, City $city)
    {
        $city->update($this->data($request, $city));

        return redirect()->route('cities.index')->with('success', 'Город обновлён.');
    }

    public function destroy(City $city)
    {
        $city->delete(); // связи с ресторанами удалятся, у сотрудников city_id станет NULL

        return redirect()->route('cities.index')->with('success', 'Город удалён.');
    }

    private function data(Request $request, ?City $city = null): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('cities', 'name')->ignore($city)],
        ]);
    }
}
