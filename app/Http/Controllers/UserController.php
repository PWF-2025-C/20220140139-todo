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

    public function destroy(User $user)
{
    $user->delete(); // Menghapus user
    return redirect()->route('user.index')->with('success', 'User deleted successfully!');
}

    public function edit()
    {
        return view('user.edit');
    }
}
