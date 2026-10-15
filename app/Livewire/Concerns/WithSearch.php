<?php

declare(strict_types=1);

namespace App\Livewire\Concerns;

trait WithSearch
{
    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Search text prepared for a SQL LIKE (the characters % _ and \ are escaped).
     */
    protected function likeTerm(): string
    {
        return '%' . addcslashes(trim($this->search), '%_\\') . '%';
    }
}
