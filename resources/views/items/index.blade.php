@extends('layouts.app')

@section('title', 'Inventory Master Items List - Inventory Information Systems')

@section('content')
<main class="list-wrap">
  @include('partials.flash')

  <div class="list-card">
    <h2>Inventory Master Items List</h2>

    <div class="table-wrap">
      <div class="table-scroll">
        <table>
          <thead>
            <tr>
              <th>No</th>
              <th>Item Name</th>
              <th>Technical Specifications</th>
              <th>Unit Size</th>
              <th>Category</th>
              <th>Created By</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($items as $item)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->item_name }}</td>
                <td>{{ $item->tech_spec }}</td>
                <td>{{ $item->unit_size }}</td>
                <td>{{ $item->category }}</td>
                <td>{{ $item->user?->name ?? '-' }}</td>
                <td>
                  @canany(['update', 'delete'], $item)
                    <div class="action-buttons">
                      @can('update', $item)
                        <a href="{{ route('items.edit', $item) }}" class="btn-edit">Edit</a>
                      @endcan
                      @can('delete', $item)
                        <form method="POST" action="{{ route('items.destroy', $item) }}" data-confirm="Are you sure you want to delete this item?">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="btn-delete">Delete</button>
                        </form>
                      @endcan
                    </div>
                  @else
                    -
                  @endcanany
                </td>
              </tr>
            @empty
              <tr class="empty-row"><td colspan="7">No items added yet.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    @can('create', \App\Models\Item::class)
      <a href="{{ route('items.create') }}" class="btn-add">Add Another Item</a>
    @endcan
  </div>
</main>
@endsection