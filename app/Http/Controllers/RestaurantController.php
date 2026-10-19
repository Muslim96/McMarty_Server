<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RestaurantController extends Controller
{
    public function index(Request $request)
    {
        $restaurants = Restaurant::withCount('employers')
            ->when($request->q, fn ($q, $s) => $q->where('name', 'like', "%$s%")->orWhere('slug', 'like', "%$s%"))
            ->latest()->paginate(12)->withQueryString();

        return view('restaurants.index', compact('restaurants'));
    }

    public function create()
    {
        return view('restaurants.form', ['restaurant' => new Restaurant]);
    }

    public function store(Request $request)
    {
        Restaurant::create($this->data($request));

        return redirect()->route('restaurants.index')->with('success', 'Ресторан создан.');
    }

    public function edit(Restaurant $restaurant)
    {
        return view('restaurants.form', compact('restaurant'));
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $data = $this->data($request, $restaurant);
        if (isset($data['logo']) && $restaurant->logo) {
            Storage::disk('public')->delete($restaurant->logo);
        }
        $restaurant->update($data);

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
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('restaurants', 'slug')->ignore($restaurant)],
            'logo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        } else {
            unset($data['logo']);
        }

        return $data;
    }
}
