<?php

declare(strict_types=1);

namespace App\Livewire\Admin\Users;

use App\Enums\UserRole;
use App\Livewire\Concerns\RequiresSuperAdmin;
use App\Livewire\Concerns\WithSearch;
use App\Models\User;
use App\Services\Admin\UserService;
use DomainException;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithPagination;

class UserTable extends Component
{
    use RequiresSuperAdmin;
    use WithPagination;
    use WithSearch;

    private const PER_PAGE = 10;

    public string $name = '';

    public string $email = '';

    public ?string $phone = null;

    public string $user_role = 'staff';

    public bool $is_active = true;

    public string $password = '';

    public bool $showForm = false;

    #[Locked]
    public ?int $editingId = null;

    public function mount(): void
    {
        $this->authorizeSuperAdmin();
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($this->editingId)],
            'phone' => ['nullable', 'string', 'max:30'],
            'user_role' => ['required', Rule::enum(UserRole::class)],
            'is_active' => ['boolean'],
            'password' => [$this->editingId === null ? 'required' : 'nullable', 'string', 'min:8', 'max:72'],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function messages(): array
    {
        return [
            'name.required' => 'The name is required.',
            'email.unique' => 'Another user already uses this email.',
            'password.required' => 'Set a password of at least 8 characters.',
            'password.min' => 'The password must be at least 8 characters.',
        ];
    }

    public function create(): void
    {
        $this->authorizeSuperAdmin();

        $this->resetForm();
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $this->authorizeSuperAdmin();

        $user = User::findOrFail($id);

        $this->resetForm();
        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->user_role = $user->role->value;
        $this->is_active = $user->is_active;
        $this->showForm = true;
    }

    public function closeForm(): void
    {
        $this->resetForm();
        $this->showForm = false;
    }

    public function save(UserService $users): void
    {
        $this->authorizeSuperAdmin();

        $this->name = trim($this->name);
        $this->email = mb_strtolower(trim($this->email));
        $this->phone = $this->phone === null || trim($this->phone) === '' ? null : trim($this->phone);

        $data = $this->validate();

        $user = $this->editingId !== null ? User::findOrFail($this->editingId) : null;

        try {
            $users->save($user, $data, auth()->user());
        } catch (DomainException $exception) {
            $this->dispatch('toast', message: $exception->getMessage(), type: 'err');

            return;
        }

        $message = $user === null ? 'User created.' : 'User updated.';

        $this->closeForm();
        $this->dispatch('toast', message: $message);
    }

    public function toggleActive(int $id, UserService $users): void
    {
        $this->authorizeSuperAdmin();

        try {
            $users->toggleActive(User::findOrFail($id), auth()->user());
        } catch (DomainException $exception) {
            $this->dispatch('toast', message: $exception->getMessage(), type: 'err');
        }
    }

    public function render(): View
    {
        $term = $this->likeTerm();

        $users = User::query()
            ->when(trim($this->search) !== '', function ($query) use ($term) {
                $query->where(function ($inner) use ($term) {
                    $inner->where('name', 'like', $term)
                        ->orWhere('email', 'like', $term)
                        ->orWhere('phone', 'like', $term);
                });
            })
            ->orderBy('name')
            ->orderBy('id')
            ->paginate(self::PER_PAGE);

        return view('livewire.admin.users.user-table', [
            'users' => $users,
            'roles' => UserRole::cases(),
        ]);
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'email', 'phone', 'user_role', 'is_active', 'password', 'editingId']);
        $this->resetValidation();
    }
}
