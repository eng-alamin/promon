@if ($paginator->hasPages())
    <nav class="pager" aria-label="Pagination">
        <span class="muted">Showing {{ $paginator->firstItem() }}-{{ $paginator->lastItem() }} of {{ $paginator->total() }}</span>
        <span class="btns">
            <button class="btn" type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')" @disabled($paginator->onFirstPage())>Previous</button>
            <button class="btn" type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')" @disabled(! $paginator->hasMorePages())>Next</button>
        </span>
    </nav>
@endif
