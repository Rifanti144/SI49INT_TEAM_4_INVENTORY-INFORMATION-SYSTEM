<div class="field">
  <label for="serial">Serial Number</label>
  <input type="text" id="serial" name="serial" maxlength="12" value="{{ old('serial', $vehicle->serial ?? '') }}" required>
  @error('serial') <p class="field-error">{{ $message }}</p> @enderror
</div>

<div class="field">
  <label for="type">Type</label>
  <select id="type" name="type" class="has-value" required>
    @foreach ($types as $t)
      <option value="{{ $t }}" @selected(old('type', $vehicle->type ?? '') === $t)>{{ ucfirst($t) }}</option>
    @endforeach
  </select>
  @error('type') <p class="field-error">{{ $message }}</p> @enderror
</div>

<div class="field">
  <label for="status">Status</label>
  <select id="status" name="status" class="has-value" required>
    @foreach ($statuses as $s)
      <option value="{{ $s }}" @selected(old('status', $vehicle->status ?? 'available') === $s)>{{ ucfirst($s) }}</option>
    @endforeach
  </select>
  @error('status') <p class="field-error">{{ $message }}</p> @enderror
</div>
