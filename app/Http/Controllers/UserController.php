<?php

namespace App\Http\Controllers;

use App\Models\Restaurant;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with(['role', 'restaurant'])
            ->when(! $request->user()->hasRole('super_admin'), fn ($q) => $q->whereHas('role', fn ($r) => $r->where('slug', '!=', 'super_admin')))
            ->when($request->q, fn ($q, $s) => $q->where(fn ($w) => $w->where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%")))
            ->latest()->paginate(15)->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create(Request $request)
    {
        return view('users.form', array_merge($this->formData($request), ['user' => new User(['is_active' => true])]));
    }

    public function store(Request $request)
    {
        User::create($this->data($request));

        return redirect()->route('users.index')->with('success', 'Пользователь создан.');
    }

    public function edit(Request $request, User $user)
    {
        $this->guard($request, $user);

        return view('users.form', array_merge($this->formData($request), compact('user')));
    }

    public function update(Request $request, User $user)
    {
        $this->guard($request, $user);
        $data = $this->data($request, $user);
        if ($user->id === $request->user()->id) {
            $data['is_active'] = true; // нельзя отключить самого себя
        }
        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Пользователь обновлён.');
    }

    public function destroy(Request $request, User $user)
    {
        $this->guard($request, $user);
        abort_if($user->id === $request->user()->id, 403, 'Нельзя удалить самого себя.');
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Пользователь удалён.');
    }

    private function guard(Request $request, User $target): void
    {
        abort_if($target->hasRole('super_admin') && ! $request->user()->hasRole('super_admin'), 403);
    }

    private function formData(Request $request): array
    {
        return [
            'roles' => Role::when(! $request->user()->hasRole('super_admin'), fn ($q) => $q->where('slug', '!=', 'super_admin'))->get(),
            'restaurants' => Restaurant::orderBy('name')->get(),
        ];
    }

    private function data(Request $request, ?User $user = null): array
    {
        $allowedRoles = $this->formData($request)['roles']->pluck('id')->all();

        $data = $request->validate([
            'name'          => ['required', 'string', 'max:255'],
            'email'         => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user)],
            'password'      => [$user ? 'nullable' : 'required', Password::min(8)],
            'role_id'       => ['required', Rule::in($allowedRoles)],
            'restaurant_id' => ['nullable', 'exists:restaurants,id'],
        ]);

        $role = Role::find($data['role_id']);
        if ($role->slug === 'manager' && empty($data['restaurant_id'])) {
            abort(back()->withInput()->withErrors(['restaurant_id' => 'Для менеджера нужно выбрать ресторан.']));
        }
        if ($role->slug !== 'manager') {
            $data['restaurant_id'] = null;
        }
        if (empty($data['password'])) {
            unset($data['password']);
        }
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
