<div>
    <div class="tools">
        <input class="f" type="search" wire:model.live.debounce.300ms="search" placeholder="Search name, title and review..." aria-label="Search reviews">
        <button class="btn pri" type="button" wire:click="create"><i class="bi bi-plus-lg" aria-hidden="true"></i> Add review</button>
    </div>

    <div class="card">
        @if ($reviews->isEmpty())
            <div class="empty">
                <div class="empty-ic" aria-hidden="true"><i class="bi bi-star"></i></div>
                @if (trim($search) !== '')
                    <h3>No reviews found</h3>
                    <p>Nothing matches "{{ $search }}". Try a different word.</p>
                @else
                    <h3>No reviews yet</h3>
                    <p>Add what your customers say about you. Active reviews appear on the website.</p>
                @endif
            </div>
        @else
            <table>
                <tr>
                    <th scope="col">Order</th>
                    <th scope="col">Customer</th>
                    <th scope="col">Rating</th>
                    <th scope="col">Status</th>
                    <th scope="col"><span class="sr-only">Actions</span></th>
                </tr>
                @foreach ($reviews as $review)
                    <tr wire:key="review-{{ $review->id }}">
                        <td data-label="Order">{{ $review->sort_order }}</td>
                        <td data-label="Customer">
                            <strong>{{ $review->reviewer_name }}</strong>
                            @if ($review->reviewer_title)
                                <div class="muted">{{ $review->reviewer_title }}</div>
                            @endif
                            <div class="muted">{{ \Illuminate\Support\Str::limit($review->body, 110) }}</div>
                        </td>
                        <td data-label="Rating">
                            <span aria-label="{{ $review->rating }} out of 5">{{ $review->rating }} / 5</span>
                        </td>
                        <td data-label="Status">
                            <button type="button" @class(['pill', 'ok' => $review->is_active]) wire:click="toggleActive({{ $review->id }})" title="Click to change" aria-label="This review is {{ $review->is_active ? 'active' : 'inactive' }}. Click to change.">
                                {{ $review->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td data-label="Actions" class="acts">
                            <button class="ib" type="button" wire:click="edit({{ $review->id }})" aria-label="Edit review by {{ $review->reviewer_name }}"><i class="bi bi-pencil" aria-hidden="true"></i></button>
                            <button class="ib bad" type="button" wire:click="confirmDelete({{ $review->id }})" aria-label="Delete review by {{ $review->reviewer_name }}"><i class="bi bi-trash" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                @endforeach
            </table>

            {{ $reviews->links('livewire.admin.pagination') }}
        @endif
    </div>

    @if ($showForm)
        <div class="ov" wire:key="review-form-modal" wire:keydown.escape.window="closeForm">
            <form class="mdl" role="dialog" aria-modal="true" aria-labelledby="review-form-title" wire:submit="save" novalidate>
                <h3 id="review-form-title">{{ $editingId ? 'Edit review' : 'Add review' }}</h3>

                <label for="review-name">Customer name</label>
                <input class="f" id="review-name" type="text" wire:model="reviewer_name" maxlength="120" autofocus required @error('reviewer_name') aria-invalid="true" aria-describedby="review-name-error" @enderror>
                @error('reviewer_name')
                    <p class="err-msg" id="review-name-error"><i class="bi bi-exclamation-circle" aria-hidden="true"></i>{{ $message }}</p>
                @enderror

                <label for="review-title">Title or place (optional)</label>
                <input class="f" id="review-title" type="text" wire:model="reviewer_title" maxlength="160" placeholder="Example: Retail shop owner, Dhaka" @error('reviewer_title') aria-invalid="true" aria-describedby="review-title-error" @enderror>
                @error('reviewer_title')
                    <p class="err-msg" id="review-title-error"><i class="bi bi-exclamation-circle" aria-hidden="true"></i>{{ $message }}</p>
                @enderror

                <label for="review-rating">Rating</label>
                <select class="f" id="review-rating" wire:model="rating" @error('rating') aria-invalid="true" aria-describedby="review-rating-error" @enderror>
                    @foreach ([5, 4, 3, 2, 1] as $star)
                        <option value="{{ $star }}">{{ $star }} {{ $star === 1 ? 'star' : 'stars' }}</option>
                    @endforeach
                </select>
                @error('rating')
                    <p class="err-msg" id="review-rating-error"><i class="bi bi-exclamation-circle" aria-hidden="true"></i>{{ $message }}</p>
                @enderror

                <label for="review-body">Review</label>
                <textarea class="f" id="review-body" rows="5" wire:model="body" maxlength="1000" required @error('body') aria-invalid="true" aria-describedby="review-body-error" @enderror></textarea>
                @error('body')
                    <p class="err-msg" id="review-body-error"><i class="bi bi-exclamation-circle" aria-hidden="true"></i>{{ $message }}</p>
                @enderror

                <label for="review-order">Order</label>
                <input class="f" id="review-order" type="number" inputmode="numeric" min="0" max="65535" step="1" wire:model="sort_order" @error('sort_order') aria-invalid="true" aria-describedby="review-order-error" @enderror>
                <p class="hint">Smaller numbers appear first on the website. The first 3 active reviews show on the home page.</p>
                @error('sort_order')
                    <p class="err-msg" id="review-order-error"><i class="bi bi-exclamation-circle" aria-hidden="true"></i>{{ $message }}</p>
                @enderror

                <label class="chk" for="review-active">
                    <input id="review-active" type="checkbox" wire:model="is_active">
                    Active (visible on the website)
                </label>

                <div class="act">
                    <button class="btn" type="button" wire:click="closeForm">Cancel</button>
                    <button class="btn pri" type="submit" wire:loading.attr="disabled" wire:target="save">Save</button>
                </div>
            </form>
        </div>
    @endif

    @if ($deletingReview)
        <div class="ov" wire:key="review-delete-modal" wire:keydown.escape.window="cancelDelete">
            <div class="mdl" role="alertdialog" aria-modal="true" aria-labelledby="review-delete-title">
                <h3 id="review-delete-title">Delete review?</h3>
                <p>The review by "{{ \Illuminate\Support\Str::limit($deletingReview->reviewer_name, 80) }}" will be deleted. This cannot be undone. To hide it instead, make it inactive.</p>
                <div class="act">
                    <button class="btn" type="button" wire:click="cancelDelete">Cancel</button>
                    <button class="btn danger" type="button" wire:click="delete" wire:loading.attr="disabled" wire:target="delete">Delete</button>
                </div>
            </div>
        </div>
    @endif
</div>
