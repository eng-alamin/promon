<div>
    <div class="tools">
        <input class="f" type="search" wire:model.live.debounce.300ms="search" placeholder="Search name, email or phone..." aria-label="Search users">
        <button class="btn pri" type="button" wire:click="create"><i class="bi bi-plus-lg" aria-hidden="true"></i> Add user</button>
    </div>

    <div class="card">
        @if ($users->isEmpty())
            <div class="empty">
                <div class="empty-ic" aria-hidden="true"><i class="bi bi-person-gear"></i></div>
                <h3>No users found</h3>
                <p>Nothing matches "{{ $search }}". Try a different word.</p>
            </div>
        @else
            <table>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Role</th>
                    <th scope="col">Phone</th>
                    <th scope="col">Status</th>
                    <th scope="col"><span class="sr-only">Actions</span></th>
                </tr>
                @foreach ($users as $user)
                    <tr wire:key="user-{{ $user->id }}">
                        <td data-label="Name">
                            <strong>{{ $user->name }}</strong>@if ($user->is(auth()->user())) <span class="pill">You</span>@endif
                            <div class="muted">{{ $user->email }}</div>
                        </td>
                        <td data-label="Role"><span class="pill {{ $user->isSuperAdmin() ? 'info' : '' }}">{{ $user->role->label() }}</span></td>
                        <td data-label="Phone">{{ $user->phone ?: '—' }}</td>
                        <td data-label="Status">
                            <button type="button" @class(['pill', 'ok' => $user->is_active]) wire:click="toggleActive({{ $user->id }})" title="Click to change" aria-label="{{ $user->name }} is {{ $user->is_active ? 'active' : 'inactive' }}. Click to change.">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </button>
                        </td>
                        <td data-label="Actions" class="acts">
                            <button class="ib" type="button" wire:click="edit({{ $user->id }})" aria-label="Edit {{ $user->name }}"><i class="bi bi-pencil" aria-hidden="true"></i></button>
                        </td>
                    </tr>
                @endforeach
            </table>

            {{ $users->links('livewire.admin.pagination') }}
        @endif
    </div>

    @if ($showForm)
        @php($editingSelf = $editingId !== null && $editingId === auth()->id())
        <div class="ov" wire:key="user-form-modal" wire:keydown.escape.window="closeForm">
            <form class="mdl wide" role="dialog" aria-modal="true" aria-labelledby="user-form-title" wire:submit="save" novalidate>
                <h3 id="user-form-title">{{ $editingId ? 'Edit user' : 'Add user' }}</h3>

                <div class="fgrid">
                    <x-admin.field name="name" id="us-name" label="Name">
                        <input class="f" id="us-name" type="text" wire:model="name" maxlength="120" autofocus required @error('name') aria-invalid="true" aria-describedby="us-name-error" @enderror>
                    </x-admin.field>

                    <x-admin.field name="email" id="us-email" label="Email (used to log in)">
                        <input class="f" id="us-email" type="email" wire:model="email" maxlength="255" autocomplete="off" required @error('email') aria-invalid="true" aria-describedby="us-email-error" @enderror>
                    </x-admin.field>

                    <x-admin.field name="phone" id="us-phone" label="Phone (optional)">
                        <input class="f" id="us-phone" type="text" inputmode="tel" wire:model="phone" maxlength="30" @error('phone') aria-invalid="true" aria-describedby="us-phone-error" @enderror>
                    </x-admin.field>

                    <x-admin.field name="user_role" id="us-role" label="Role" hint="Super Admin can also manage users and settings. Staff cannot.">
                        <select class="f" id="us-role" wire:model="user_role" @disabled($editingSelf) @error('user_role') aria-invalid="true" aria-describedby="us-role-error" @enderror>
                            @foreach ($roles as $role)
                                <option value="{{ $role->value }}">{{ $role->label() }}</option>
                            @endforeach
                        </select>
                    </x-admin.field>

                    <x-admin.field name="password" id="us-password" :label="$editingId ? 'New password (optional)' : 'Password'" :hint="$editingId ? 'Leave empty to keep the current password.' : 'At least 8 characters.'" :span="true">
                        <input class="f" id="us-password" type="password" wire:model="password" maxlength="72" autocomplete="new-password" @error('password') aria-invalid="true" aria-describedby="us-password-error" @enderror>
                    </x-admin.field>
                </div>

                <label class="chk" for="us-active">
                    <input id="us-active" type="checkbox" wire:model="is_active" @disabled($editingSelf)>
                    Active (can log in)
                </label>

                @if ($editingSelf)
                    <p class="hint">You cannot change your own role or deactivate yourself.</p>
                @endif

                <div class="act">
                    <button class="btn" type="button" wire:click="closeForm">Cancel</button>
                    <button class="btn pri" type="submit" wire:loading.attr="disabled" wire:target="save">Save</button>
                </div>
            </form>
        </div>
    @endif
</div>
