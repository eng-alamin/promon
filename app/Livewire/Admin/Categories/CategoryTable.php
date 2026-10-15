<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Categories;

use App\Livewire\Concerns\WithSearch;
use App\Models\Category;
use App\Services\Admin\CategoryService;
use DomainException;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryTable extends Component
{
    use WithPagination;
    use WithSearch;

    private const PER_PAGE = 10;

    public string $name = '';

    public string $slug = '';

    public string|int $sort_order = 0;

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
            'name' => ['required', 'string', 'max:100', Rule::unique('categories', 'name')->ignore($this->editingId)],
            'slug' => [
                'required',
                'string',
                'max:120',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('categories', 'slug')->ignore($this->editingId),
            ],
            'sort_order' => ['required', 'integer', 'min:0', 'max:65535'],
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
        $this->sort_order = min(((int) Category::max('sort_order')) + 1, 65535);
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $category = Category::findOrFail($id);

        $this->resetForm();
        $this->editingId = $category->id;
        $this->name = $category->name;
        $this->slug = $category->slug;
        $this->sort_order = $category->sort_order;
        $this->is_active = $category->is_active;
        $this->showForm = true;
    }

    public function closeForm(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    public function save(CategoryService $categories): void
    {
        $this->name = trim($this->name);
        $this->slug = Str::slug($this->slug !== '' ? $this->slug : $this->name);

        $data = $this->validate();

        $category = $this->editingId !== null ? Category::findOrFail($this->editingId) : null;
        $categories->save($category, $data);

        $message = $category === null ? 'Category created.' : 'Category updated.';

        $this->closeForm();
        $this->dispatch('toast', message: $message);
    }

    public function toggleActive(int $id, CategoryService $categories): void
    {
        $categories->toggleActive(Category::findOrFail($id));
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = Category::findOrFail($id)->id;
    }

    public function cancelDelete(): void
    {
        $this->deletingId = null;
    }

    public function delete(CategoryService $categories): void
    {
        $category = $this->deletingId !== null ? Category::find($this->deletingId) : null;
        $this->deletingId = null;

        if ($category === null) {
            return;
        }

        try {
            $categories->delete($category);
        } catch (DomainException $exception) {
            $this->dispatch('toast', message: $exception->getMessage(), type: 'err');

            return;
        }

        $this->resetPage();
        $this->dispatch('toast', message: 'Category deleted.');
    }

    public function render(): View
    {
        $term = $this->likeTerm();

        $categories = Category::query()
            ->withCount('products')
            ->when(trim($this->search) !== '', function ($query) use ($term) {
                $query->where(function ($inner) use ($term) {
                    $inner->where('name', 'like', $term)->orWhere('slug', 'like', $term);
                });
            })
            ->ordered()
            ->paginate(self::PER_PAGE);

        return view('livewire.admin.categories.category-table', [
            'categories' => $categories,
            'deletingCategory' => $this->deletingId !== null ? Category::find($this->deletingId) : null,
        ]);
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'slug', 'sort_order', 'is_active', 'editingId']);
        $this->resetValidation();
    }
}
