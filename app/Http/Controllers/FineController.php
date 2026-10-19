<?php

namespace App\Http\Controllers;

use App\Models\Employer;
use App\Models\Fine;
use App\Models\FineReason;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class FineController extends Controller
{
    public function index(Request $request)
    {
        $u = $request->user();

        $base = Fine::visibleTo($u)
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->restaurant_id, fn ($q, $id) => $q->whereHas('employer', fn ($e) => $e->where('restaurant_id', $id)))
            ->when($request->q, fn ($q, $s) => $q->whereHas('employer', fn ($e) => $e->search($s)))
            ->when($request->from, fn ($q, $d) => $q->whereDate('fined_at', '>=', $d))
            ->when($request->to, fn ($q, $d) => $q->whereDate('fined_at', '<=', $d));

        $totals = (clone $base)->selectRaw('status, SUM(amount) as total')->groupBy('status')->pluck('total', 'status');

        $fines = (clone $base)->with(['employer.restaurant', 'reason', 'issuer'])
            ->latest('fined_at')->latest('id')->paginate(15)->withQueryString();

        $restaurants = Restaurant::visibleTo($u)->orderBy('name')->get();

        return view('fines.index', compact('fines', 'totals', 'restaurants'));
    }

    public function create(Request $request)
    {
        return view('fines.form', array_merge($this->formData($request), [
            'fine' => new Fine(['fined_at' => now(), 'employer_id' => $request->employer_id]),
        ]));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $fine = Fine::create($data + ['issued_by' => $request->user()->id, 'status' => Fine::ACTIVE]);

        return redirect()->route('employers.show', $fine->employer_id)->with('success', 'Штраф назначен.');
    }

    public function edit(Request $request, Fine $fine)
    {
        $this->authorizeFine($request, $fine);
        abort_unless($fine->status === Fine::ACTIVE, 403, 'Редактировать можно только неоплаченные штрафы.');

        return view('fines.form', array_merge($this->formData($request), compact('fine')));
    }

    public function update(Request $request, Fine $fine)
    {
        $this->authorizeFine($request, $fine);
        abort_unless($fine->status === Fine::ACTIVE, 403);

        $fine->update($this->validated($request));

        return redirect()->route('fines.index')->with('success', 'Штраф обновлён.');
    }

    public function pay(Request $request, Fine $fine)
    {
        $this->authorizeFine($request, $fine);
        abort_unless($fine->status === Fine::ACTIVE, 403);
        $fine->update(['status' => Fine::PAID, 'paid_at' => now()]);

        return back()->with('success', 'Штраф отмечен как оплаченный.');
    }

    public function cancel(Request $request, Fine $fine)
    {
        $this->authorizeFine($request, $fine);
        abort_unless($fine->status === Fine::ACTIVE, 403);
        $fine->update(['status' => Fine::CANCELLED, 'cancelled_at' => now()]);

        return back()->with('success', 'Штраф отменён.');
    }

    public function destroy(Request $request, Fine $fine)
    {
        $this->authorizeFine($request, $fine);
        $fine->delete();

        return back()->with('success', 'Штраф удалён.');
    }

    private function authorizeFine(Request $request, Fine $fine): void
    {
        abort_unless($request->user()->canAccessRestaurant($fine->employer->restaurant_id), 403);
    }

    private function formData(Request $request): array
    {
        return [
            'employers' => Employer::with('restaurant')->visibleTo($request->user())
                ->where('is_active', true)->orderBy('surname')->get(),
            'reasons'   => FineReason::where('is_active', true)->orderBy('name')->get(),
        ];
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'employer_id'    => ['required', 'exists:employers,id'],
            'fine_reason_id' => ['nullable', 'exists:fine_reasons,id'],
            'amount'         => ['required', 'numeric', 'min:0.01', 'max:99999999'],
            'fined_at'       => ['required', 'date'],
            'comment'        => ['nullable', 'string', 'max:2000'],
        ]);

        abort_unless($request->user()->canAccessRestaurant(Employer::find($data['employer_id'])->restaurant_id), 403);

        return $data;
    }
}
