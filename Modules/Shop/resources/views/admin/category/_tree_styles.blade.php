<style>
    .category-tree {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .category-tree .category-tree {
        padding-inline-start: 1.5rem;
        margin-top: .15rem;
    }

    .category-tree-node {
        margin: 0;
    }

    .category-tree-row {
        display: flex;
        align-items: center;
        gap: .55rem;
        padding: .35rem .25rem;
        border-radius: .475rem;
        min-height: 2rem;
    }

    .category-tree-row:hover {
        background-color: var(--bs-gray-100);
    }

    .category-tree-toggle {
        width: 1rem;
        border: 0;
        background: transparent;
        color: var(--bs-gray-500);
        padding: 0;
        line-height: 1;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .category-tree-toggle .bi {
        transition: transform .15s ease;
        font-size: .8rem;
    }

    .category-tree-node.is-collapsed > .category-tree {
        display: none;
    }

    .category-tree-node.is-collapsed > .category-tree-row .category-tree-toggle .bi {
        transform: rotate(-90deg);
    }

    [dir="rtl"] .category-tree-node.is-collapsed > .category-tree-row .category-tree-toggle .bi {
        transform: rotate(90deg);
    }

    .category-tree-icon {
        color: var(--bs-gray-500);
        font-size: 1.05rem;
        flex-shrink: 0;
    }

    .category-tree-label {
        color: var(--bs-gray-800);
        font-weight: 500;
        font-size: .95rem;
        line-height: 1.3;
    }

    .category-tree-meta {
        color: var(--bs-gray-500);
        font-size: .8rem;
    }

    .category-tree-actions {
        margin-inline-start: auto;
        display: flex;
        align-items: center;
        gap: .35rem;
        opacity: 0;
        transition: opacity .15s ease;
    }

    .category-tree-row:hover .category-tree-actions {
        opacity: 1;
    }

    .category-tree-thumb {
        width: 28px;
        height: 28px;
        object-fit: cover;
        border-radius: .35rem;
        flex-shrink: 0;
    }

    .category-tree-select .form-check-input {
        margin: 0;
        flex-shrink: 0;
    }

    .category-tree-select .category-tree-row.is-disabled {
        opacity: .45;
        pointer-events: none;
    }

    .category-tree-panel {
        border: 1px solid var(--bs-gray-200);
        border-radius: .65rem;
        padding: 1rem;
        max-height: 420px;
        overflow: auto;
        background: #fff;
    }
</style>
