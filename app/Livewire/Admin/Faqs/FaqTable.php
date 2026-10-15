<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Faqs;

use App\Livewire\Concerns\WithSearch;
use App\Models\Faq;
use App\Services\Admin\FaqService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class FaqTable extends Component
{
    use WithPagination;
    use WithSearch;

    private const PER_PAGE = 10;

    public string $question = '';

    public string $answer = '';

    // A string on purpose: an empty number input sends '' and an int property would crash.
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
            'question' => ['required', 'string', 'max:255'],
            'answer' => ['required', 'string', 'max:5000'],
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
            'question.required' => 'Please write the question.',
            'answer.required' => 'Please write the answer.',
            'sort_order.required' => 'Enter a number. Smaller numbers appear first.',
            'sort_order.integer' => 'The order must be a whole number.',
            'sort_order.min' => 'The order cannot be less than 0.',
            'sort_order.max' => 'The order cannot be more than 65535.',
        ];
    }

    public function create(FaqService $faqs): void
    {
        $this->resetForm();
        $this->sort_order = (string) $faqs->nextSortOrder();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $faq = Faq::findOrFail($id);

        $this->resetForm();
        $this->editingId = $faq->id;
        $this->question = $faq->question;
        $this->answer = $faq->answer;
        $this->sort_order = (string) $faq->sort_order;
        $this->is_active = $faq->is_active;
        $this->showForm = true;
    }

    public function closeForm(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    public function save(FaqService $faqs): void
    {
        $this->question = trim($this->question);
        $this->answer = trim($this->answer);
        $this->sort_order = trim($this->sort_order);

        $data = $this->validate();

        $faq = $this->editingId !== null ? Faq::findOrFail($this->editingId) : null;
        $faqs->save($faq, $data);

        $message = $faq === null ? 'FAQ created.' : 'FAQ updated.';

        $this->closeForm();
        $this->dispatch('toast', message: $message);
    }

    public function toggleActive(int $id, FaqService $faqs): void
    {
        $faqs->toggleActive(Faq::findOrFail($id));
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = Faq::findOrFail($id)->id;
    }

    public function cancelDelete(): void
    {
        $this->deletingId = null;
    }

    public function delete(FaqService $faqs): void
    {
        $faq = $this->deletingId !== null ? Faq::find($this->deletingId) : null;
        $this->deletingId = null;

        if ($faq === null) {
            return;
        }

        $faqs->delete($faq);

        $this->resetPage();
        $this->dispatch('toast', message: 'FAQ deleted.');
    }

    public function render(): View
    {
        $term = $this->likeTerm();

        $faqs = Faq::query()
            ->when(trim($this->search) !== '', function ($query) use ($term) {
                $query->where(function ($inner) use ($term) {
                    $inner->where('question', 'like', $term)->orWhere('answer', 'like', $term);
                });
            })
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(self::PER_PAGE);

        return view('livewire.admin.faqs.faq-table', [
            'faqs' => $faqs,
            'deletingFaq' => $this->deletingId !== null ? Faq::find($this->deletingId) : null,
        ]);
    }

    private function resetForm(): void
    {
        $this->reset(['question', 'answer', 'sort_order', 'is_active', 'editingId']);
        $this->resetValidation();
    }
}
