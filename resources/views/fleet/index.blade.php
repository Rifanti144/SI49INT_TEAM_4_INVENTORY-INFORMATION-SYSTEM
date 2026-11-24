@extends('layouts.app')

@section('title', 'Operational Fleet - Inventory Information Systems')

@section('content')
<main class="list-wrap">
  @include('partials.flash')

  <div class="list-card">
    <h2>Operational Fleet Data</h2>

    <form method="GET" action="{{ route('fleet.index') }}" class="list-toolbar">
      <input type="search" name="q" value="{{ request('q') }}" placeholder="Search serial number" aria-label="Search serial number">
      <select name="status" class="table-select" aria-label="Filter by status" onchange="this.form.submit()">
        <option value="">All statuses</option>
        @foreach ($statuses as $s)
          <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst($s) }}</option>
        @endforeach
      </select>
    </form>

    <div class="table-wrap">
      <div class="table-scroll">
        <table> 
          <thead>
            <tr>
              <th>Serial Number</th>
              <th>Type</th>
              <th>Status</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($vehicles as $vehicle)
              <tr>
                <td>{{ $vehicle->serial }}</td>
                <td>{{ ucfirst($vehicle->type) }}</td>
                <td>{{ ucfirst($vehicle->status) }}</td>
                  <td>
                    <div class="action-buttons">
                      @can('update', $vehicle)
                      <a href="{{ route('fleet.edit', $vehicle) }}" class="btn-edit">Edit</a>
                      @endcan
                      @can('delete', $vehicle)
                      <form method="POST" action="{{ route('fleet.destroy', $vehicle) }}" data-confirm="Delete vehicle {{ $vehicle->serial }}?">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-delete">Delete</button>
                      </form>
                      @endcan
                    </div>
                  </td>
              </tr>
            @empty
              <tr class="empty-row"><td colspan="4">No vehicles found.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>

    @can('create', \App\Models\Vehicle::class)
    <a href="{{ route('fleet.create') }}" class="btn-add">Add Another Vehicle</a>
    @endcan

    @if ($vehicles->hasPages())
      <nav class="list-pagination" aria-label="Pagination">
        <ul>
          @if ($vehicles->onFirstPage())
            <li class="disabled"><span>&laquo; Previous</span></li>
          @else
            <li><a href="{{ $vehicles->previousPageUrl() }}" rel="prev">&laquo; Previous</a></li>
          @endif
          <li class="disabled"><span>Page {{ $vehicles->currentPage() }} of {{ $vehicles->lastPage() }}</span></li>
          @if ($vehicles->hasMorePages())
            <li><a href="{{ $vehicles->nextPageUrl() }}" rel="next">Next &raquo;</a></li>
          @else
            <li class="disabled"><span>Next &raquo;</span></li>
          @endif
        </ul>
      </nav>
    @endif

  </div>
</main>
@endsection
