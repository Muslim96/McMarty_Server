<?php

namespace App\Http\Controllers;

use App\Models\FineReason;
use Illuminate\Http\Request;

class FineReasonController extends Controller
{
    public function index()
    {
        return view('fine-reasons.index', ['reasons' => FineReason::withCount('fines')->orderBy('name')->get()]);
    }

    public function create()
    {
        return view('fine-reasons.form', ['reason' => new FineReason(['is_active' => true])]);
    }

    public function store(Request $request)
    {
        FineReason::create($this->data($request));

        return redirect()->route('fine-reasons.index')->with('success', 'Причина добавлена.');
    }

    public function edit(FineReason $fine_reason)
    {
        return view('fine-reasons.form', ['reason' => $fine_reason]);
    }

    public function update(Request $request, FineReason $fine_reason)
    {
        $fine_reason->update($this->data($request));

        return redirect()->route('fine-reasons.index')->with('success', 'Причина обновлена.');
    }

    public function destroy(FineReason $fine_reason)
    {
        $fine_reason->delete(); // у штрафов fine_reason_id станет NULL

        return redirect()->route('fine-reasons.index')->with('success', 'Причина удалена.');
    }

    private function data(Request $request): array
    {
        $data = $request->validate([
            'name'           => ['required', 'string', 'max:255'],
            'default_amount' => ['nullable', 'numeric', 'min:0', 'max:99999999'],
        ]);
        $data['default_amount'] = $data['default_amount'] ?? 0;
        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
