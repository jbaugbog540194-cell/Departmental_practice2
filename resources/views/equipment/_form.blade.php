<form method="POST" action="{{ $action }}">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif
    <div class="form-grid">
        <div class="field field-full">
            <label for="equipment_name">Equipment name</label>
            <input id="equipment_name" name="equipment_name" value="{{ old('equipment_name', $equipment->equipment_name ?? '') }}" maxlength="255" required>
            @error('equipment_name') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label for="category">Category</label>
            <select id="category" name="category" required>
                <option value="">Select a category</option>
                @foreach ($categories as $category)
                    <option value="{{ $category }}" @selected(old('category', $equipment->category ?? '') === $category)>{{ ucfirst($category) }}</option>
                @endforeach
            </select>
            @error('category') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label for="unit_price">Unit price (PHP)</label>
            <input id="unit_price" name="unit_price" type="number" step="0.01" min="0" value="{{ old('unit_price', $equipment->unit_price ?? '') }}" required>
            @error('unit_price') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="field">
            <label for="availability_status">Availability status</label>
            <select id="availability_status" name="availability_status" required>
                <option value="">Select a status</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status }}" @selected(old('availability_status', $equipment->availability_status ?? '') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
            @error('availability_status') <span class="field-error">{{ $message }}</span> @enderror
        </div>
        <div class="field field-full">
            <label for="description">Description and specifications</label>
            <textarea id="description" name="description" required>{{ old('description', $equipment->description ?? '') }}</textarea>
            @error('description') <span class="field-error">{{ $message }}</span> @enderror
        </div>
    </div>
    <div class="actions" style="margin-top:21px">
        <button class="btn" type="submit">{{ $method === 'POST' ? 'Save equipment' : 'Save changes' }}</button>
        <a class="btn btn-secondary" href="{{ route('equipment.index') }}">Cancel</a>
    </div>
</form>
