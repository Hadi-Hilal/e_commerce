@if(isset($modal) && $modal)

    <!-- Edit Attribute Modal -->
    <div class="modal fade" id="editAttributeModal{{$attribute->id}}" tabindex="-1" aria-labelledby="editAttributeModalLabel{{$attribute->id}}"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.attributes.update', $attribute) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editAttributeModalLabel{{$attribute->id}}">{{ __('Edit Attribute: ') }}{{$attribute->admin_name}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="code{{$attribute->id}}" class="form-label">{{ __('Code') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="code" id="code{{$attribute->id}}" value="{{$attribute->code}}" required>
                            <div class="form-text">{{ __('Unique identifier for the attribute') }}</div>
                        </div>

                        <div class="mb-3">
                            <label for="admin_name{{$attribute->id}}" class="form-label">{{ __('Admin Name') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="admin_name" id="admin_name{{$attribute->id}}" value="{{$attribute->admin_name}}" required>
                            <div class="form-text">{{ __('Display name in admin panel') }}</div>
                        </div>

                        <div class="mb-3">
                            <label for="type{{$attribute->id}}" class="form-label">{{ __('Type') }} <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" name="type" id="type{{$attribute->id}}" required>
                                <option value="text" {{ $attribute->type === 'text' ? 'selected' : '' }}>{{ __('shop.attributes.types.text') }}</option>
                                <option value="select" {{ $attribute->type === 'select' ? 'selected' : '' }}>{{ __('shop.attributes.types.select') }}</option>
                                <option value="boolean" {{ $attribute->type === 'boolean' ? 'selected' : '' }}>{{ __('shop.attributes.types.boolean') }}</option>
                                <option value="number" {{ $attribute->type === 'number' ? 'selected' : '' }}>{{ __('shop.attributes.types.number') }}</option>
                                <option value="date" {{ $attribute->type === 'date' ? 'selected' : '' }}>{{ __('shop.attributes.types.date') }}</option>
                                <option value="textarea" {{ $attribute->type === 'textarea' ? 'selected' : '' }}>{{ __('shop.attributes.types.textarea') }}</option>
                            </select>
                        </div>

                        <div id="optionsContainer{{$attribute->id}}" class="mb-3" style="display: {{ $attribute->type === 'select' ? 'block' : 'none' }};">
                            <label for="options{{$attribute->id}}" class="form-label">{{ __('Options (for select type)') }}</label>
                            <div id="optionsInputs{{$attribute->id}}">
                                @if($attribute->options && is_array($attribute->options))
                                    @foreach($attribute->options as $option)
                                        <div class="input-group mb-2">
                                            <input type="text" class="form-control" name="options[]" value="{{$option}}" placeholder="{{ __('Option value') }}">
                                            <button type="button" class="btn btn-outline-danger remove-option" title="{{ __('Remove') }}">
                                                <i class="ki-duotone ki-trash fs-3"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" name="options[]" placeholder="{{ __('Option value') }}">
                                        <button type="button" class="btn btn-outline-danger remove-option" title="{{ __('Remove') }}">
                                            <i class="ki-duotone ki-trash fs-3"></i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary add-option" data-target="{{ $attribute->id }}">
                                <i class="ki-duotone ki-plus fs-3 me-1"></i> {{ __('Add Option') }}
                            </button>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="is_required" id="is_required{{$attribute->id}}" {{ $attribute->is_required ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_required{{$attribute->id}}">{{ __('Required') }}</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="is_unique" id="is_unique{{$attribute->id}}" {{ $attribute->is_unique ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_unique{{$attribute->id}}">{{ __('Unique') }}</label>
                                </div>
                            </div>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="update_translations" id="update_translations{{$attribute->id}}">
                            <label class="form-check-label" for="update_translations{{$attribute->id}}">{{ __('Update Other Languages') }}</label>
                            <div class="form-text">{{ __('Use Google Translate to update all other languages.') }}</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type{{$attribute->id}}');
        const optionsContainer = document.getElementById('optionsContainer{{$attribute->id}}');
        const addOptionBtn = document.querySelector('[data-target="{{ $attribute->id }}"]');
        const optionsInputs = document.getElementById('optionsInputs{{$attribute->id}}');

        function toggleOptions() {
            if (typeSelect.value === 'select') {
                optionsContainer.style.display = 'block';
            } else {
                optionsContainer.style.display = 'none';
            }
        }

        typeSelect.addEventListener('change', toggleOptions);

        // Add option input
        if (addOptionBtn) {
            addOptionBtn.addEventListener('click', function() {
                const div = document.createElement('div');
                div.className = 'input-group mb-2';
                div.innerHTML = `
                    <input type="text" class="form-control" name="options[]" placeholder="{{ __('Option value') }}">
                    <button type="button" class="btn btn-outline-danger remove-option" title="{{ __('Remove') }}">
                        <i class="ki-duotone ki-trash fs-3"></i>
                    </button>
                `;
                optionsInputs.appendChild(div);
            });
        }

        // Remove option input (event delegation)
        if (optionsInputs) {
            optionsInputs.addEventListener('click', function(e) {
                if (e.target.closest('.remove-option')) {
                    e.target.closest('.input-group').remove();
                }
            });
        }
    });
</script>
@endpush