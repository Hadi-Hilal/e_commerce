@php
    $mode = $mode ?? 'select';
    $selected = $selected ?? null;
    $disabledIds = $disabledIds ?? [];
    $inputName = $inputName ?? 'parent_id';
    $familyFilter = $familyFilter ?? null;
@endphp

<ul class="category-tree">
    @foreach($nodes as $node)
        @php
            $children = $node->relationLoaded('children') ? $node->getRelation('children') : collect();
            $hasChildren = $children->isNotEmpty();
            $isDisabled = in_array($node->id, $disabledIds, true);
            $isSelected = (string) $selected === (string) $node->id;
        @endphp
        <li class="category-tree-node"
            data-family-id="{{ $node->attribute_family_id }}"
            @if($familyFilter && (int) $node->attribute_family_id !== (int) $familyFilter) style="display:none" @endif>
            <div class="category-tree-row {{ $isDisabled ? 'is-disabled' : '' }}">
                @if($hasChildren)
                    <button type="button" class="category-tree-toggle" data-category-tree-toggle aria-label="{{ __('Toggle') }}">
                        <i class="bi bi-chevron-down"></i>
                    </button>
                @else
                    <span class="category-tree-toggle"></span>
                @endif

                <i class="bi {{ $hasChildren ? 'bi-folder-fill' : 'bi-file-earmark' }} category-tree-icon"></i>

                @if($mode === 'select')
                    <input class="form-check-input"
                           type="radio"
                           name="{{ $inputName }}"
                           id="parent_category_{{ $node->id }}"
                           value="{{ $node->id }}"
                           data-family-id="{{ $node->attribute_family_id }}"
                           @checked($isSelected)
                           @disabled($isDisabled)>
                    <label class="category-tree-label mb-0" for="parent_category_{{ $node->id }}">
                        {{ $node->name }}
                    </label>
                @else
                    <img src="{{ $node->image_link }}" alt="" class="category-tree-thumb">
                    <div class="d-flex flex-column">
                        <span class="category-tree-label">{{ $node->name }}</span>
                        <span class="category-tree-meta">{{ $node->slug }}</span>
                    </div>
                    <div class="category-tree-actions">
                        <a href="{{ route('admin.categories.edit', $node) }}"
                           class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm"
                           title="{{ __('Edit Category') }}">
                            <i class="ki-duotone ki-message-edit fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </a>
                        <form action="{{ route('admin.categories.destroy', $node) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="btn btn-icon btn-bg-light btn-active-color-primary btn-sm"
                                    title="{{ __('Delete Category') }}"
                                    onclick="return confirm('{{ __('Are you sure you want to delete this category?') }}')">
                                <i class="ki-duotone ki-message-trash fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            @if($hasChildren)
                @include('shop::admin.category._tree_nodes', [
                    'nodes' => $children,
                    'mode' => $mode,
                    'selected' => $selected,
                    'disabledIds' => $disabledIds,
                    'inputName' => $inputName,
                    'familyFilter' => $familyFilter,
                ])
            @endif
        </li>
    @endforeach
</ul>
