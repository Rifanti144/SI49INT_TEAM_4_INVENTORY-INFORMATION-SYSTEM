@extends('layouts.app')

@section('title', 'Add Operational - Inventory Information Systems')

@section('content')
<main class="content">
  <div class="intro">
    <span class="accent-bar" aria-hidden="true"></span>
    <h1>Operational Fleet</h1>
  </div>

  <div class="panel">
    <form method="POST" action="{{ route('fleet.store') }}">
      @csrf
      @include('fleet._form')
      <div class="form-actions">
        <button type="submit" class="btn btn--lime">SAVE</button>
      </div>
    </form>
  </div>
</main>
@endsection
