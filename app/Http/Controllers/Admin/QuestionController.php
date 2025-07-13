<?php

namespace App\Http\Controllers\Admin;

use App\Events\TakeNotificationCountEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\QuestionStoreRequest;
use App\Http\Requests\QuestionUpdateRequest;
use App\Models\Notification;
use App\Models\NotificationUser;
use App\Models\Project;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuestionStoreRequest $request)
    {
        $data = $request->all();
        $question = Question::create([
            'user_id' => $data['user_id'],
            'project_id' => $data['project_id'],
            'title' => $data['title'],
            'question' => $data['question'],
            'limit_date' => $data['limit_date'],
        ]);
        // $project = Project::find($data['project_id']);

        $notification = Notification::create([
            'title' => 'Вам задали вопрос из проекта ' . $data['project_name'] .
                ' <a href="' . route('admin.projects.show', $data['project_id']) . '#question-' . $question->id . '">Перейти</a>',
            'text' => auth()->user()->fio . ': ' . $data['question'],
        ]);
        NotificationUser::create([
            'user_id' => $data['user_id'],
            'notification_id' => $notification->id,
        ]);
        broadcast(new TakeNotificationCountEvent($data['user_id']))->toOthers();
        return redirect()->back()->withSuccess('Успешно сохранено');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(QuestionUpdateRequest $request, Question $question)
    {
        $data = $request->all();
        $question->update([
            'title' => $data['title'],
            'question' => $data['question'],
            'limit_date' => $data['limit_date'],
        ]);
        return response()->json(['status' => 200]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Question $question)
    {
        $question->delete();
        return response()->json(['status' => 200]);
    }
}
