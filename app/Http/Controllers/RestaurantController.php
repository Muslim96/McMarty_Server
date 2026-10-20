<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $restaurants = Restaurant::withCount('employers')->with('cities:id,name')
            ->when($request->q, fn ($q, $s) => $q->where('name', 'like', "%$s%")->orWhere('slug', 'like', "%$s%"))
            ->latest()->paginate(12)->withQueryString();

        return view('restaurants.index', compact('restaurants'));
    }

    public function create()
    {
        return view('restaurants.form', ['restaurant' => new Restaurant, 'cities' => City::orderBy('name')->get()]);
    }

    public function store(Request $request)
    {
        $data = $this->data($request);
        $cityIds = Arr::pull($data, 'city_ids', []);

        $restaurant = Restaurant::create($data);
        $restaurant->cities()->sync($cityIds);

        return redirect()->route('restaurants.index')->with('success', 'Ресторан создан.');
    }

    public function edit(Restaurant $restaurant)
    {
        return view('restaurants.form', ['restaurant' => $restaurant->load('cities'), 'cities' => City::orderBy('name')->get()]);
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $data = $this->data($request, $restaurant);
        $cityIds = Arr::pull($data, 'city_ids', []);

        if (isset($data['logo']) && $restaurant->logo) {
            Storage::disk('public')->delete($restaurant->logo);
        }
        $restaurant->update($data);
        $restaurant->cities()->sync($cityIds);

        $restaurant->employers()->whereNotNull('city_id')->whereNotIn('city_id', $cityIds)->update(['city_id' => null]);

        return redirect()->route('restaurants.index')->with('success', 'Ресторан обновлён.');
    }

    public function destroy(Restaurant $restaurant)
    {
        if ($restaurant->logo) {
            Storage::disk('public')->delete($restaurant->logo);
        }
        $restaurant->delete(); // сотрудники удаляются каскадом

        return redirect()->route('restaurants.index')->with('success', 'Ресторан удалён вместе с сотрудниками.');
    }

    private function data(Request $request, ?Restaurant $restaurant = null): array
    {
        $request->merge(['slug' => Str::slug($request->input('slug') ?: $request->input('name', ''))]);

        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'slug'       => ['required', 'string', 'max:255', Rule::unique('restaurants', 'slug')->ignore($restaurant)],
            'logo'       => ['nullable', 'image', 'max:2048'],
            'city_ids'   => ['nullable', 'array'],
            'city_ids.*' => ['integer', 'exists:cities,id'],
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        } else {
            unset($data['logo']);
        }

        return $data;
    }
}
