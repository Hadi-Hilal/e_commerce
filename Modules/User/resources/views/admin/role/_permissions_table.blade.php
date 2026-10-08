<!--begin::Table wrapper-->
<div class="table-responsive">
    <!--begin::Table-->
    <table class="table align-middle table-row-dashed fs-6 gy-5">
        <!--begin::Table body-->
        <tbody class="text-gray-600 fw-bold">
        <!--begin::Table row - Select All-->
        <tr>
            <td class="text-gray-800">{{__('Select All Permissions')}}
                <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip"
                   title="Select or deselect all permissions"></i></td>
            <td>
                <!--begin::Checkbox-->
                <label class="form-check form-check-custom form-check-solid me-9">
                    <input class="form-check-input" type="checkbox" value=""
                           id="kt_roles_select_all"/>
                    <span class="form-check-label"
                          for="kt_roles_select_all">{{__('Select All')}}</span>
                </label>
                <!--end::Checkbox-->
            </td>
        </tr>
        <!--end::Table row-->
        @foreach($permissions as $sectionKey => $section)
            <!-- Section Header -->
            <tr class="fw-bolder bg-light">
                <td colspan="2">
                    <i class="{{ $section['icon'] }} me-2"></i>
                    {{ __($section['label']) }}
                </td>
            </tr>
            @foreach($section['groups'] as $group)
                <!-- Group Header -->
                <tr class="fw-semibold bg-gray-50">
                    <td colspan="2">
                        <span class="ms-3 text-muted">{{ __($group['label']) }}</span>
                    </td>
                </tr>
                @php
                    $actions = ['view', 'create', 'edit', 'delete'];
                    $groupPermissions = $group['permissions'];
                @endphp
                @foreach($actions as $action)
                    @if(isset($groupPermissions[$action]))
                        @php
                            $permission = $groupPermissions[$action];
                            $isChecked = in_array($permission['name'], $rolePermissions ?? []);
                        @endphp
                        <!--begin::Table row-->
                        <tr>
                            <!--begin::Label-->
                            <td class="text-gray-800 ps-5">{{ __($permission['label']) }}</td>
                            <!--end::Label-->
                            <!--begin::Options-->
                            <td>
                                <!--begin::Wrapper-->
                                <div class="d-flex">
                                    <!--begin::Checkbox-->
                                    <label
                                        class="form-check form-check-sm form-check-custom form-check-solid me-5 me-lg-20">
                                        <input class="form-check-input" type="checkbox"
                                               name="permissions[]"
                                               value="{{ $permission['name'] }}"
                                               {{ $isChecked ? 'checked' : '' }}/>
                                    </label>
                                    <!--end::Checkbox-->
                                </div>
                                <!--end::Wrapper-->
                            </td>
                            <!--end::Options-->
                        </tr>
                        <!--end::Table row-->
                    @endif
                @endforeach
            @endforeach
        @endforeach
        </tbody>
        <!--end::Table body-->
    </table>
    <!--end::Table-->
</div>
<!--end::Table wrapper-->