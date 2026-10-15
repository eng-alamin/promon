<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Brands;

use App\Livewire\Concerns\WithSearch;
use App\Models\Brand;
use App\Services\Admin\BrandService;
use DomainException;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class BrandTable extends Component
{
    use WithPagination;
    use WithSearch;

    private const PER_PAGE = 10;

    public string $name = '';

    public string $slug = '';

    public bool $is_active = true;

    public bool $showForm = false;

    #[Locked]
    public ?int $editingId = null;

    #[Locked]
    public ?int $deletingId = null;

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120', Rule::unique('brands', 'name')->ignore($this->editingId)],
            'slug' => [
                'required',
                'string',
                'max:140',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('brands', 'slug')->ignore($this->editingId),
            ],
            'is_active' => ['boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'slug.required' => 'The slug is required. Type one using English letters and numbers.',
            'slug.regex' => 'The slug may contain lowercase letters, numbers and single hyphens only.',
        ];
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $brand = Brand::findOrFail($id);

        $this->resetForm();
        $this->editingId = $brand->id;
        $this->name = $brand->name;
        $this->slug = $brand->slug;
        $this->is_active = $brand->is_active;
        $this->showForm = true;
    }

    public function closeForm(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    public function save(BrandService $brands): void
    {
        $this->name = trim($this->name);
        $this->slug = Str::slug($this->slug !== '' ? $this->slug : $this->name);

        $data = $this->validate();

        $brand = $this->editingId !== null ? Brand::findOrFail($this->editingId) : null;
        $brands->save($brand, $data);

        $message = $brand === null ? 'Brand created.' : 'Brand updated.';

        $this->closeForm();
        $this->dispatch('toast', message: $message);
    }

    public function toggleActive(int $id, BrandService $brands): void
    {
        $brands->toggleActive(Brand::findOrFail($id));
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = Brand::findOrFail($id)->id;
    }

    public function cancelDelete(): void
    {
        $this->deletingId = null;
    }

    public function delete(BrandService $brands): void
    {
        $brand = $this->deletingId !== null ? Brand::find($this->deletingId) : null;
        $this->deletingId = null;

        if ($brand === null) {
            return;
        }

        try {
            $brands->delete($brand);
        } catch (DomainException $exception) {
            $this->dispatch('toast', message: $exception->getMessage(), type: 'err');

            return;
        }

        $this->resetPage();
        $this->dispatch('toast', message: 'Brand deleted.');
    }

    public function render(): View
    {
        $term = $this->likeTerm();

        $brands = Brand::query()
            ->withCount('products')
            ->when(trim($this->search) !== '', function ($query) use ($term) {
                $query->where(function ($inner) use ($term) {
                    $inner->where('name', 'like', $term)->orWhere('slug', 'like', $term);
                });
            })
            ->orderBy('name')
            ->paginate(self::PER_PAGE);

        return view('livewire.admin.brands.brand-table', [
            'brands' => $brands,
            'deletingBrand' => $this->deletingId !== null ? Brand::find($this->deletingId) : null,
        ]);
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'slug', 'is_active', 'editingId']);
        $this->resetValidation();
    }
}
