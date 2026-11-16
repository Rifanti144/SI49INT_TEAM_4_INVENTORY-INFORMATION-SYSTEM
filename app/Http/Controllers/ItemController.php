<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    public function index()
    {
        Gate::authorize('viewAny', Item::class);

        return view('items.index', ['items' => Item::with('user')->orderBy('id')->get()]);
    }

    public function create()
    {
        Gate::authorize('create', Item::class);

        return view('items.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Item::class);

        Item::create($this->validated($request));

        return redirect()->route('items.index')->with('status', 'Item added.');
    }

    public function edit(Item $item)
    {
        Gate::authorize('update', $item);

        return view('items.edit', compact('item'));
    }

    public function update(Request $request, Item $item): RedirectResponse
    {
        Gate::authorize('update', $item);

        $item->update($this->validated($request));

        return redirect()->route('items.index')->with('status', 'Item updated.');
    }

    public function destroy(Item $item): RedirectResponse
    {
        Gate::authorize('delete', $item);

        $item->delete();

        return redirect()->route('items.index')->with('status', 'Item deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'item_name' => ['required', 'string', 'max:255'],
            'tech_spec' => ['nullable', 'string', 'max:5000'],
            'unit_size' => ['nullable', 'string', 'max:255'],
            'category' => ['required', Rule::in(Item::CATEGORIES)],
        ]);
    }
}