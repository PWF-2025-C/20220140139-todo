<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Todo;
use Illuminate\Support\Facades\Auth;

class TodoController extends Controller
{
    public function index()
    {
        $todos = Todo::where('user_id', auth()->user()->id)
            ->orderBy('is_done', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
    
        return view('todo.index', compact('todos'));
    }

    public function store(Request $request, Todo $todo)
{
    
    $request->validate([
        'title' => 'required|max:255',
    ]);

    // Eloquent way - Readable
    $todo = Todo::create([
        'title' => ucfirst($request->title),
        'user_id' => auth()->user()->id,
    ]);

    return redirect()
        ->route('todo.index')
        ->with('success', 'Todo created successfully!');
}

public function destroy(Todo $todo)
{
    $todo->delete();

    return redirect()->route('todo.index')->with('success', 'Todo deleted successfully.');
}

    public function create()
    {
        return view('todo.create');
    }

    public function edit()
    {
        return view('todo.edit');
    }
}
