<div>
    <div class="tools">
        <input class="f" type="search" wire:model.live.debounce.300ms="search" placeholder="Search questions and answers..." aria-label="Search FAQs">
        <button class="btn pri" type="button" wire:click="create"><i class="bi bi-plus-lg" aria-hidden="true"></i> Add FAQ</button>
    </div>

    <div class="card">
        @if ($faqs->isEmpty())
            <div class="empty">
                <div class="empty-ic" aria-hidden="true"><i class="bi bi-question-circle"></i></div>
                @if (trim($search) !== '')
                    <h3>No FAQs found</h3>
                    <p>Nothing matches "{{ $search }}". Try a different word.</p>
                @else
                    <h3>No FAQs yet</h3>
                    <p>Add the questions your customers ask most often. Active FAQs appear on the website.</p>
                @endif
            </div>
        @else
            <table>
                <tr>
                    <th scope="col">Order</th>
                    <th scope="col">Question</th>
                    <th scope="col">Status</th>
                    <th scope="col"><span class="sr-only">Actions</span></th>
                </tr>
                @foreach ($faqs as $faq)
                    <tr wire:key="faq-{{ $faq->id }}">
                        <td data-label="Order">{{ $faq->sort_order }}</td>
                        <td data-label="Question">
                            <strong>{{ $faq->question }}</strong>
                            <div class="muted">{{ \Illuminate\Support\Str::limit($faq->answer, 110) }}</div>
                        </td>
                        <td data-label="Status">
                            <button type="button" @class(['pill', 'ok' => $faq->is_active]) wire:click="toggleActive({{ $faq->id }})" title="Click to change" aria-label="This FAQ is {{ $faq->is_active ? 'active' : 'inactive' }}. Click to change.">
                                {{ $faq->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td data-label="Actions" class="acts">
                            <button class="ib" type="button" wire:click="edit({{ $faq->id }})" aria-label="Edit FAQ: {{ \Illuminate\Support\Str::limit($faq->question, 60) }}"><i class="bi bi-pencil" aria-hidden="true"></i></button>
                            <button class="ib bad" type="button" wire:click="confirmDelete({{ $faq->id }})" aria-label="Delete FAQ: {{ \Illuminate\Support\Str::limit($faq->question, 60) }}"><i class="bi bi-trash" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                @endforeach
            </table>

            {{ $faqs->links('livewire.admin.pagination') }}
        @endif
    </div>

    @if ($showForm)
        <div class="ov" wire:key="faq-form-modal" wire:keydown.escape.window="closeForm">
            <form class="mdl" role="dialog" aria-modal="true" aria-labelledby="faq-form-title" wire:submit="save" novalidate>
                <h3 id="faq-form-title">{{ $editingId ? 'Edit FAQ' : 'Add FAQ' }}</h3>

                <label for="faq-question">Question</label>
                <input class="f" id="faq-question" type="text" wire:model="question" maxlength="255" autofocus required @error('question') aria-invalid="true" aria-describedby="faq-question-error" @enderror>
                @error('question')
                    <p class="err-msg" id="faq-question-error"><i class="bi bi-exclamation-circle" aria-hidden="true"></i>{{ $message }}</p>
                @enderror

                <label for="faq-answer">Answer</label>
                <textarea class="f" id="faq-answer" rows="5" wire:model="answer" maxlength="5000" required @error('answer') aria-invalid="true" aria-describedby="faq-answer-error" @enderror></textarea>
                @error('answer')
                    <p class="err-msg" id="faq-answer-error"><i class="bi bi-exclamation-circle" aria-hidden="true"></i>{{ $message }}</p>
                @enderror

                <label for="faq-order">Order</label>
                <input class="f" id="faq-order" type="number" inputmode="numeric" min="0" max="65535" step="1" wire:model="sort_order" @error('sort_order') aria-invalid="true" aria-describedby="faq-order-error" @enderror>
                <p class="hint">Smaller numbers appear first on the website.</p>
                @error('sort_order')
                    <p class="err-msg" id="faq-order-error"><i class="bi bi-exclamation-circle" aria-hidden="true"></i>{{ $message }}</p>
                @enderror

                <label class="chk" for="faq-active">
                    <input id="faq-active" type="checkbox" wire:model="is_active">
                    Active (visible on the website)
                </label>

                <div class="act">
                    <button class="btn" type="button" wire:click="closeForm">Cancel</button>
                    <button class="btn pri" type="submit" wire:loading.attr="disabled" wire:target="save">Save</button>
                </div>
            </form>
        </div>
    @endif

    @if ($deletingFaq)
        <div class="ov" wire:key="faq-delete-modal" wire:keydown.escape.window="cancelDelete">
            <div class="mdl" role="alertdialog" aria-modal="true" aria-labelledby="faq-delete-title">
                <h3 id="faq-delete-title">Delete FAQ?</h3>
                <p>"{{ \Illuminate\Support\Str::limit($deletingFaq->question, 120) }}" will be deleted. This cannot be undone. To hide it instead, make it inactive.</p>
                <div class="act">
                    <button class="btn" type="button" wire:click="cancelDelete">Cancel</button>
                    <button class="btn danger" type="button" wire:click="delete" wire:loading.attr="disabled" wire:target="delete">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
