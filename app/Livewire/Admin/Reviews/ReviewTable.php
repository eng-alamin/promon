<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Reviews;

use App\Livewire\Concerns\WithSearch;
use App\Models\Review;
use App\Services\Admin\ReviewService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class ReviewTable extends Component
{
    use WithPagination;
    use WithSearch;

    private const PER_PAGE = 10;

    public string $reviewer_name = '';

    public string $reviewer_title = '';

    // A string on purpose: a select and a number input send text, and an int property would crash on ''.
    public string $rating = '5';

    public string $body = '';

    public string $sort_order = '0';

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
            'reviewer_name' => ['required', 'string', 'max:120'],
            'reviewer_title' => ['nullable', 'string', 'max:160'],
            'rating' => ['required', 'integer', 'between:1,5'],
            'body' => ['required', 'string', 'max:1000'],
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
            'reviewer_name.required' => 'Please write the name of the customer.',
            'rating.required' => 'Please choose a rating from 1 to 5.',
            'rating.integer' => 'The rating must be a whole number from 1 to 5.',
            'rating.between' => 'The rating must be from 1 to 5.',
            'body.required' => 'Please write the review.',
            'sort_order.required' => 'Enter a number. Smaller numbers appear first.',
            'sort_order.integer' => 'The order must be a whole number.',
            'sort_order.min' => 'The order cannot be less than 0.',
            'sort_order.max' => 'The order cannot be more than 65535.',
        ];
    }

    public function create(ReviewService $reviews): void
    {
        $this->resetForm();
        $this->sort_order = (string) $reviews->nextSortOrder();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $review = Review::findOrFail($id);

        $this->resetForm();
        $this->editingId = $review->id;
        $this->reviewer_name = $review->reviewer_name;
        $this->reviewer_title = (string) $review->reviewer_title;
        $this->rating = (string) $review->rating;
        $this->body = $review->body;
        $this->sort_order = (string) $review->sort_order;
        $this->is_active = $review->is_active;
        $this->showForm = true;
    }

    public function closeForm(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    public function save(ReviewService $reviews): void
    {
        $this->reviewer_name = trim($this->reviewer_name);
        $this->reviewer_title = trim($this->reviewer_title);
        $this->rating = trim($this->rating);
        $this->body = trim($this->body);
        $this->sort_order = trim($this->sort_order);

        $data = $this->validate();

        $review = $this->editingId !== null ? Review::findOrFail($this->editingId) : null;
        $reviews->save($review, $data);

        $message = $review === null ? 'Review created.' : 'Review updated.';

        $this->closeForm();
        $this->dispatch('toast', message: $message);
    }

    public function toggleActive(int $id, ReviewService $reviews): void
    {
        $reviews->toggleActive(Review::findOrFail($id));
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = Review::findOrFail($id)->id;
    }

    public function cancelDelete(): void
    {
        $this->deletingId = null;
    }

    public function delete(ReviewService $reviews): void
    {
        $review = $this->deletingId !== null ? Review::find($this->deletingId) : null;
        $this->deletingId = null;

        if ($review === null) {
            return;
        }

        $reviews->delete($review);

        $this->resetPage();
        $this->dispatch('toast', message: 'Review deleted.');
    }

    public function render(): View
    {
        $term = $this->likeTerm();

        $reviews = Review::query()
            ->when(trim($this->search) !== '', function ($query) use ($term) {
                $query->where(function ($inner) use ($term) {
                    $inner->where('reviewer_name', 'like', $term)
                        ->orWhere('reviewer_title', 'like', $term)
                        ->orWhere('body', 'like', $term);
                });
            })
            ->ordered()
            ->paginate(self::PER_PAGE);

        return view('livewire.admin.reviews.review-table', [
            'reviews' => $reviews,
            'deletingReview' => $this->deletingId !== null ? Review::find($this->deletingId) : null,
        ]);
    }

    private function resetForm(): void
    {
        $this->reset(['reviewer_name', 'reviewer_title', 'rating', 'body', 'sort_order', 'is_active', 'editingId']);
        $this->resetValidation();
    }
}
