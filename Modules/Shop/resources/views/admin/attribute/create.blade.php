@if(isset($modal) && $modal)

    <!-- Create Attribute Modal -->
    <div class="modal fade" id="createAttributeModal" tabindex="-1" aria-labelledby="createAttributeModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.attributes.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createAttributeModalLabel">{{ __('Create Attribute') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="code" class="form-label">{{ __('Code') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="code" id="code" required>
                            <div class="form-text">{{ __('Unique identifier for the attribute (e.g., color, size)') }}</div>
                        </div>

                        <div class="mb-3">
                            <label for="admin_name" class="form-label">{{ __('Admin Name') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="admin_name" id="admin_name" required>
                            <div class="form-text">{{ __('Display name in admin panel') }}</div>
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">{{ __('Type') }} <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" name="type" id="type" required>
                                <option value="text">{{ __('shop.attributes.types.text') }}</option>
                                <option value="select">{{ __('shop.attributes.types.select') }}</option>
                                <option value="boolean">{{ __('shop.attributes.types.boolean') }}</option>
                                <option value="number">{{ __('shop.attributes.types.number') }}</option>
                                <option value="date">{{ __('shop.attributes.types.date') }}</option>
                                <option value="textarea">{{ __('shop.attributes.types.textarea') }}</option>
                            </select>
                        </div>

                        <div id="optionsContainer" class="mb-3" style="display: none;">
                            <label for="options" class="form-label">{{ __('Options (for select type)') }}</label>
                            <div id="optionsInputs">
                                <div class="input-group mb-2">
                                    <input type="text" class="form-control" name="options[]" placeholder="{{ __('Option value') }}">
                                    <button type="button" class="btn btn-outline-danger remove-option" title="{{ __('Remove') }}">
                                        <i class="ki-duotone ki-trash fs-3"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="addOption">
                                <i class="ki-duotone ki-plus fs-3 me-1"></i> {{ __('Add Option') }}
                            </button>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="is_required" id="is_required">
                                    <label class="form-check-label" for="is_required">{{ __('Required') }}</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="is_unique" id="is_unique">
                                    <label class="form-check-label" for="is_unique">{{ __('Unique') }}</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@push('scripts')
<script>
    // Toggle options container based on type selection
    document.addEventListener('DOMContentLoaded', function() {
        const typeSelect = document.getElementById('type');
        const optionsContainer = document.getElementById('optionsContainer');
        const addOptionBtn = document.getElementById('addOption');
        const optionsInputs = document.getElementById('optionsInputs');

        function toggleOptions() {
            if (typeSelect.value === 'select') {
                optionsContainer.style.display = 'block';
            } else {
                optionsContainer.style.display = 'none';
            }
        }

        typeSelect.addEventListener('change', toggleOptions);
        toggleOptions();

        // Add option input
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

        // Remove option input (event delegation)
        optionsInputs.addEventListener('click', function(e) {
            if (e.target.closest('.remove-option')) {
                e.target.closest('.input-group').remove();
            }
        });
    });
</script>
@endpush