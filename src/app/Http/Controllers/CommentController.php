<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use App\Models\Listing;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function store(CommentRequest $request)
    {
        $validated = $request->validated();

        $listing = Listing::findOrFail($request->listing_id);

        if ($listing->user_id === auth()->id()) {
            return back()->with('error', '自分の商品にはコメントできません。');
        }

        $existingComment = Comment::where('listing_id', $request->listing_id)
        ->where('user_id', auth()->id())
        ->first();

        if ($existingComment) {
            return back()->withErrors(['content' => 'この商品にはすでにコメントしています。']);
        }

        $comment = new Comment();
        $comment->listing_id = $validated['listing_id'];
        $comment->user_id = auth()->id();
        $comment->content = $validated['content'];
        $comment->save();

        return back()->with('success', 'コメントを投稿しました。');
    }

    public function destroy(Comment $comment)
    {
        //自分のコメント以外は削除できない
        if ($comment->user_id !== auth()->id()) {
            abort(403, 'このコメントは削除できません。');
        }

        $comment->delete();

        return back()->with('success', 'コメントを削除しました。');
    }
}
