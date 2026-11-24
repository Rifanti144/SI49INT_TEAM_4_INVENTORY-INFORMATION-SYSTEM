@extends('layouts.app')

@section('title', 'Edit Vehicle - Inventory Information Systems')

@section('content')
<main class="content">
  <div class="intro">
    <span class="accent-bar" aria-hidden="true"></span>
    <h1>Edit Vehicle</h1>
  </div>

  <div class="panel">
    <form method="POST" action="{{ route('fleet.update', $vehicle) }}">
      @csrf
      @method('PUT')
      @include('fleet._form')
      <div class="form-actions">
        <a href="{{ route('fleet.index') }}" class="btn btn--cancel">Cancel</a>
        <button type="submit" class="btn btn--lime">UPDATE</button>
      </div>
    </form>
  </div>
</main>
@endsection
