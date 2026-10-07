@if(isset($modal) && $modal)

    <!-- Edit Attribute Family Modal -->
    <div class="modal fade" id="editFamilyModal{{$attribute_family->id}}" tabindex="-1" aria-labelledby="editFamilyModalLabel{{$attribute_family->id}}"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.attribute_families.update', $attribute_family) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editFamilyModalLabel{{$attribute_family->id}}">{{ __('Edit Attribute Family: ') }}{{$attribute_family->name}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="code{{$attribute_family->id}}" class="form-label">{{ __('Code') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="code" id="code{{$attribute_family->id}}" value="{{$attribute_family->code}}" required>
                            <div class="form-text">{{ __('Unique identifier for the attribute family') }}</div>
                        </div>

                        <div class="mb-3">
                            <label for="name{{$attribute_family->id}}" class="form-label">{{ __('Name') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="name{{$attribute_family->id}}" value="{{$attribute_family->name}}" required>
                            <div class="form-text">{{ __('Display name in admin panel') }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Attributes') }}</label>
                            <div class="form-text">{{ __('Select attributes to include in this family') }}</div>
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3" style="max-height: 300px; overflow-y: auto;">
                                @foreach($attributes as $attribute)
                                    <div class="col">
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" name="attribute_ids[]" value="{{$attribute->id}}" id="attr_{{$attribute->id}}_{{$attribute_family->id}}" {{ in_array($attribute->id, $selectedAttributes) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="attr_{{$attribute->id}}_{{$attribute_family->id}}">
                                                <strong>{{$attribute->code}}</strong> - {{$attribute->admin_name}}
                                                <span class="badge badge-light-{{ $attribute->type === 'select' ? 'success' : ($attribute->type === 'boolean' ? 'info' : 'primary') }} fs-7 ms-2">
                                                    {{__('shop.attributes.types.'.$attribute->type)}}
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" type="checkbox" name="update_translations" id="update_translations{{$attribute_family->id}}">
                            <label class="form-check-label" for="update_translations{{$attribute_family->id}}">{{ __('Update Other Languages') }}</label>
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