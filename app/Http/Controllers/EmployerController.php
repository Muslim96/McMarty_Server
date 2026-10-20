<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\Fine;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployerController extends Controller
{
    public function index(Request $request)
    {
        $u = $request->user();

        $employers = Employer::with(['restaurant', 'city'])
            ->withSum(['fines as active_fines_sum' => fn ($q) => $q->where('status', Fine::ACTIVE)], 'amount')
            ->visibleTo($u)
            ->when($request->q, fn ($q, $s) => $q->search($s))
            ->when($request->restaurant_id, fn ($q, $id) => $q->where('restaurant_id', $id))
            ->when($request->filled('status'), fn ($q) => $q->where('is_active', $request->status === '1'))
            ->latest()->paginate(15)->withQueryString();

        $restaurants = Restaurant::visibleTo($u)->orderBy('name')->get();

        return view('employers.index', compact('employers', 'restaurants'));
    }

    public function show(Request $request, Employer $employer)
    {
        $this->authorizeRestaurant($request, $employer);

        $employer->load('restaurant', 'city');
        $fines = $employer->fines()->with(['reason', 'issuer'])->latest('fined_at')->latest('id')->get();

        return view('employers.show', compact('employer', 'fines'));
    }

    public function create(Request $request)
    {
        return view('employers.form', [
            'employer'    => new Employer(['is_active' => true, 'restaurant_id' => $request->user()->restrictedRestaurantId() ?: $request->restaurant_id]),
            'restaurants' => Restaurant::visibleTo($request->user())->with('cities:id,name')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $employer = Employer::create($this->data($request));

        return redirect()->route('employers.show', $employer)->with('success', 'Сотрудник добавлен.');
    }

    public function edit(Request $request, Employer $employer)
    {
        $this->authorizeRestaurant($request, $employer);

        return view('employers.form', [
            'employer'    => $employer,
            'restaurants' => Restaurant::visibleTo($request->user())->with('cities:id,name')->orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Employer $employer)
    {
        $this->authorizeRestaurant($request, $employer);
        $employer->update($this->data($request));

        return redirect()->route('employers.show', $employer)->with('success', 'Данные сотрудника обновлены.');
    }

    public function destroy(Request $request, Employer $employer)
    {
        $this->authorizeRestaurant($request, $employer);
        $employer->delete();

        return redirect()->route('employers.index')->with('success', 'Сотрудник удалён.');
    }

    private function authorizeRestaurant(Request $request, Employer $employer): void
    {
        abort_unless($request->user()->canAccessRestaurant($employer->restaurant_id), 403);
    }

    private function data(Request $request): array
    {
        $data = $request->validate([
            'surname'       => ['required', 'string', 'max:255'],
            'name'          => ['required', 'string', 'max:255'],
            'patronymic'    => ['nullable', 'string', 'max:255'],
            'birth_date'    => ['nullable', 'date', 'before:today'],
            'phone'         => ['nullable', 'string', 'max:50'],
            'email'         => ['nullable', 'email', 'max:255'],
            'position'      => ['nullable', 'string', 'max:255'],
            'hired_at'      => ['nullable', 'date'],
            'restaurant_id' => ['required', 'exists:restaurants,id'],
            'city_id'       => ['nullable', Rule::exists('city_restaurant', 'city_id')->where('restaurant_id', $request->input('restaurant_id'))],
        ]);
        $data['is_active'] = $request->boolean('is_active');

        abort_unless($request->user()->canAccessRestaurant((int) $data['restaurant_id']), 403);

        return $data;
    }
}
