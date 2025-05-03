<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $users = User::where(function ($query) use ($search) {
                    $query->where('name', 'like', '%' . $search . '%')
                          ->orWhere('email', 'like', '%' . $search . '%');
                })
                ->where('id', '!=', '1')
                ->orderBy('name')
                ->paginate(20)
                ->withQueryString();
        } else {
            $users = User::where('id', '!=', '1')
                ->orderBy('name')
                ->paginate(10);
        }

        return view('user.index', compact('users'));
    }

    public function makeadmin(User $user)
    {
        // Nonaktifkan timestamps (supaya updated_at tidak berubah)
        $user->timestamps = false;
        $user->is_admin = true;
        $user->save();

        return back()->with('success', 'Make admin successfully!');
    }

    public function removeadmin(User $user)
    {
        // Supaya user dengan id 1 (misal superadmin) tidak bisa dihapus adminnya
        if ($user->id != 1) {
            $user->timestamps = false;
            $user->is_admin = false;
            $user->save();

            return back()->with('success', 'Remove admin successfully!');
        } else {
            return redirect()->route('user.index');
        }
    }

    public function destroy(User $user)
    {
        $user->delete(); 
        return redirect()->route('user.index')->with('success', 'Delete user successfully!');
    }

    public function edit(User $user)
    {
        if (Auth::user()->id === $user->id || Auth::user()->is_admin) {
            return view('user.edit', compact('user'));
        } else {
            return redirect()
                ->route('user.index')
                ->with('danger', 'You are not authorized to edit this user!');
        }
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|max:255',
        ];

        // Kalau admin boleh validasi email & password
        if (Auth::user()->is_admin) {
            $rules['email'] = 'required|email|max:255|unique:users,email,' . $user->id;
            $rules['password'] = 'nullable|min:8';
        }

        $validated = $request->validate($rules);

        // Update name untuk semua user
        $user->name = ucfirst($validated['name']);

        // Kalau admin, update email dan password juga
        if (Auth::user()->is_admin) {
            $user->email = $validated['email'];

            if (!empty($validated['password'])) {
                $user->password = bcrypt($validated['password']);
            }
        }

        $user->save();

        return redirect()
            ->route('user.index')
            ->with('success', 'User updated successfully!');
    }
}
