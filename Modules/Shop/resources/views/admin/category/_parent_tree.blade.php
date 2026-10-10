@php
    $selected = old('parent_id', $selected ?? null);
    $disabledIds = $disabledIds ?? [];
    $familyFilter = $familyFilter ?? null;
@endphp

@include('shop::admin.category._tree_styles')

<div class="category-tree-panel category-tree-select" id="categoryParentTree"
     data-selected-family="{{ $familyFilter }}">
    <div class="category-tree-row mb-1">
        <span class="category-tree-toggle"></span>
        <i class="bi bi-house category-tree-icon"></i>
        <input class="form-check-input"
               type="radio"
               name="parent_id"
               id="parent_category_root"
               value=""
               @checked($selected === null || $selected === '' || (int) $selected === 0)>
        <label class="category-tree-label mb-0" for="parent_category_root">
            {{ __('No Parent (Root Category)') }}
        </label>
    </div>

    @if($tree->isNotEmpty())
        @include('shop::admin.category._tree_nodes', [
            'nodes' => $tree,
            'mode' => 'select',
            'selected' => $selected,
            'disabledIds' => $disabledIds,
            'inputName' => 'parent_id',
            'familyFilter' => $familyFilter,
        ])
    @else
        <div class="text-muted fs-7 mt-3">{{ __('No categories available yet.') }}</div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tree = document.getElementById('categoryParentTree');
        if (!tree) return;

        tree.querySelectorAll('[data-category-tree-toggle]').forEach(function (button) {
            button.addEventListener('click', function () {
                button.closest('.category-tree-node')?.classList.toggle('is-collapsed');
            });
        });

        const familySelect = document.getElementById('attribute_family_id');
        if (!familySelect) return;

        const filterTree = function () {
            const familyId = familySelect.value;
            tree.querySelectorAll('.category-tree-node').forEach(function (node) {
                const matches = !familyId || String(node.dataset.familyId) === String(familyId);
                node.style.display = matches ? '' : 'none';
            });

            const selected = tree.querySelector('input[name="parent_id"]:checked');
            if (selected && selected.value && familyId && String(selected.dataset.familyId) !== String(familyId)) {
                const root = document.getElementById('parent_category_root');
                if (root) root.checked = true;
            }
        };

        familySelect.addEventListener('change', filterTree);
        filterTree();
    });
</script>
