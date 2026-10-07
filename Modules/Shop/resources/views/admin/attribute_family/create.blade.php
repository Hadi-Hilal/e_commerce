@if(isset($modal) && $modal)

    <!-- Create Attribute Family Modal -->
    <div class="modal fade" id="createFamilyModal" tabindex="-1" aria-labelledby="createFamilyModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.attribute_families.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="createFamilyModalLabel">{{ __('Create Attribute Family') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <div class="mb-3">
                            <label for="code" class="form-label">{{ __('Code') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="code" id="code" required>
                            <div class="form-text">{{ __('Unique identifier for the attribute family (e.g., clothing, electronics)') }}</div>
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">{{ __('Name') }} <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="name" required>
                            <div class="form-text">{{ __('Display name in admin panel') }}</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">{{ __('Attributes') }}</label>
                            <div class="form-text">{{ __('Select attributes to include in this family') }}</div>
                            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-3" style="max-height: 300px; overflow-y: auto;">
                                @foreach($attributes as $attribute)
                                    <div class="col">
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" name="attribute_ids[]" value="{{$attribute->id}}" id="attr_{{$attribute->id}}">
                                            <label class="form-check-label" for="attr_{{$attribute->id}}">
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