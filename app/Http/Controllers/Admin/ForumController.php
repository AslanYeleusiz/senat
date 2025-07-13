<?php

namespace App\Http\Controllers\Admin;

use App\Events\StoreCommentEvent;
use App\Events\TakeNotificationCountEvent;
use App\Http\Controllers\Controller;
use App\Models\Forum;
use App\Models\ForumComment;
use App\Models\Notification;
use App\Models\NotificationUser;
use Illuminate\Broadcasting\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Inertia\Inertia;

class ForumController extends Controller
{
    public function index(Request $request)
    {
        $title = $request->title;
        $forums = Forum::with('user')
            ->when($title, fn($q) => $q->where('title', 'like', "%$title%"))
            ->latest()
            ->paginate($request->input('per_page', 20))
            ->appends($request->except('page'));
        return Inertia::render('Admin/Forum/Index', [
            'forums' => $forums
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Forum/Create');
    }

    public function store(Request $request)
    {
        Forum::create([
            'title' => $request->title,
            'user_id' => auth()->id(),
        ]);
        return redirect()->route('admin.forums.index')->with('success', 'Успешно добавлено');
    }

    public function show(Forum $forum)
    {
        $forum->load([
            'comments' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'comments.user'
        ]);
        $canAction = $forum->user_id === auth()->id() || auth()->user()->role_id === 1;

        return Inertia::render('Admin/Forum/Show', [
            'forumId' => $forum->id,
            // 'forum' => $forum,
            // 'canAction' => $canAction,
        ]);
    }

    public function edit(Forum $forum)
    {
        return Inertia::render('Admin/Forum/Edit', [
            'forum' => $forum
        ]);
    }

    public function update(Request $request, Forum $forum)
    {
        $forum->update([
            'title' => $request->title
        ]);
        return redirect()->route('admin.forums.index')->with('success', 'Успешно сохранено');
    }

    public function destroy(Forum $forum)
    {
        ForumComment::where('forum_id', $forum->id)->delete();
        $forum->delete();
        return redirect()->route('admin.forums.index')->with('success', 'Успешно удвлено');
    }

    public function loadComments($forumId)
    {
        $forum = Forum::find($forumId);
        $forum->load([
            'comments' => function ($query) {
                $query->orderBy('created_at', 'desc');
            },
            'comments.user'
        ]);
        $canAction = $forum->user_id === auth()->id() || auth()->user()->role_id === 1;

        return response()->json([
            'forum' => $forum,
            'canAction' => $canAction,
        ]);
    }

    public function commentstore(Request $request, $forumId)
    {
        $forumComment = ForumComment::create([
            'comment' => $request->comment,
            'comment_id' => $request->comment_id,
            'forum_id' => $forumId,
            'user_id' => auth()->id(),
        ]);
        $forum = Forum::find($forumId);
        if ($request->author_id && auth()->id() != $request->author_id) {
            $notification = Notification::create([
                'title' => 'Вам ответили на ваш комментарий из форума ' . $forum->title .
                    ' <a href="' . route('admin.forums.show', $forumId) . '#comment-' . $forumComment->id . '">Перейти</a>',
                'text' => auth()->user()->fio . ': ' . $request->comment,
            ]);
            NotificationUser::create([
                'user_id' => $request->author_id,
                'notification_id' => $notification->id,
            ]);
            broadcast(new TakeNotificationCountEvent($request->author_id))->toOthers();
        }
        $forumComment->load('user');

        broadcast(new StoreCommentEvent($forumComment))->toOthers();

        return response()->json([
            'forumComment' => $forumComment
        ]);
    }

    public function toggleStatus($forumId)
    {
        $forum = Forum::find($forumId);
        $forum->is_done = !$forum->is_done;
        $forum->save();
        return redirect()->back()->with('success', 'Статус форума обновлен.');
    }
}
