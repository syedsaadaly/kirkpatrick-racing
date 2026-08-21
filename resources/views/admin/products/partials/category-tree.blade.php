@foreach($categories as $category)
    <div class="category-item">
        <div class="form-check mb-2">
            <input class="form-check-input" type="checkbox"
                   name="categories[]"
                   value="{{ $category->id }}"
                   id="category_{{ $category->id }}"
                   {{ in_array($category->id, $selected) ? 'checked' : '' }}>
            <label class="form-check-label" for="category_{{ $category->id }}">
                {{ str_repeat('— ', $category->level) }}{{ $category->name }}
            </label>
        </div>
    </div>
@endforeach
