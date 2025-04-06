<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::all(); // Fetch all users
        return view('usermanagement.index', compact('users'));
    }
    public function create(): View
    {
        return view('usermanagement.create');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['nullable', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->whereNull('deleted_at'),
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->firstname . ' ' . $request->lastname,
            'email' => $request->email,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        if ($user->role === 'Vendor') {
            Vendor::create([
                'user_id' => $user->id,
                'firstname' => $request->firstname,
                'middlename' => $request->middlename,
                'lastname' => $request->lastname,
            ]);
        }

        flash()->success('Account created successfully!');

        return redirect(route('usermanagement.index'));
    }

    public function show(User $user): View
    {
        return view('usermanagement.show', compact('user'));
    }
    public function edit(User $user): View
    {
        return view('usermanagement.edit', compact('user'));
    }
    public function update(Request $request, User $user): RedirectResponse
    {
        $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['nullable', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'role' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->whereNull('deleted_at')->ignore($user->id),
            ],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
        ]);

        $data = [
            'name' => $request->firstname . ' ' . $request->lastname,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        flash()->success('Account updated successfully!');

        return redirect(route('usermanagement.index'));
    }
    public function destroy(User $user): RedirectResponse
    {
        if ($user->role === 'Vendor') {
            $user->vendor()->delete(); // Assuming a 'vendor' relationship exists
        }
        $user->delete();

        flash()->success('Account deleted successfully!');

        return redirect(route('usermanagement.index'));
    }
}
