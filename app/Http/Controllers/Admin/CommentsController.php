<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CommentsController extends Controller
{
    public function index(): Factory|View|Application
    {
        $comments = Comment::all();

        return view('admin.comments.index', compact('comments'));
    }

    public function toggle(Comment $comment): RedirectResponse
    {
        $comment->toggleStatus();

        return redirect()->back();
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();
        return redirect()->back();
    }
}
