<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Audan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Services\FileService;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\User\UserSaveRequest;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $fio = $request->fio;
        $iin = $request->iin;
        $mail = $request->mail;
        $phone = $request->phone;
        $users = User::with(['role'])->when($fio, fn($q)=>$q->where('fio', 'like', "%$fio%"))
            ->when($iin, fn($q)=>$q->where('iin', 'like', "%$iin%"))
            ->when($mail, fn($q)=>$q->where('mail', 'like', "%$mail%"))
            ->when($phone, fn($q)=>$q->where('phone', 'like', "%$phone%"))
            ->paginate($request->input('per_page', 20))
            ->appends($request->except('page'));
        return Inertia::render('Admin/Users/Index', [
            'users' => $users
        ]);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::get();
        $audans = Audan::get();
        return Inertia::render('Admin/Users/Create', [
            'roles' => $roles,
            'audans' => $audans
        ]);
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserSaveRequest $request)
    {
        $autobiography_link = NULL;
        $avatar = NULL;
        $certificate_link = NULL;
        $document_link = NULL;
        if($request->hasFile('autobiography')){
            $autobiography_link = FileService::saveFile($request->autobiography, User::FILE_PATH);
            $autobiography_link = User::FILE_PATH.''.$autobiography_link;
        }
        if($request->hasFile('avatar')){
            $avatar = FileService::saveFile($request->avatar, User::AVATAR_PATH);
            $avatar = User::AVATAR_PATH.''.$avatar;
        }
        if($request->hasFile('certificate')){
            $certificate_link = FileService::saveFile($request->certificate, User::CERTIFICATE_PATH);
            $certificate_link = User::CERTIFICATE_PATH.''.$certificate_link;
        }
        if($request->hasFile('document')){
            $document_link = FileService::saveFile($request->document, User::document_PATH);
            $document_link = User::document_PATH.''.$document_link;
        }
        User::create([
            'fio' => $request->fio,
            'phone' => $request->phone,
            'mail' => $request->mail,
            'real_password' => $request->real_password,
            'password' => Hash('sha1', $request->real_password),
            'audan_id' => $request->audan_id,
            'role_id' => $request->role_id,
            'iin' => $request->iin,
            'job_title' => $request->job_title,
            'avatar' => $avatar ? $avatar : 'avatars/avatar.png',
            'autobiography_link' => $autobiography_link,
            'certificate_link' => $certificate_link,
            'document_link' => $document_link,
        ]);
        return redirect()->route('admin.users.index')->with('success','Успешно добавлено');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::get();
        $audans = Audan::get();
        return Inertia::render('Admin/Users/Edit', [
            'user' => $user,
            'roles' => $roles,
            'audans' => $audans
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if($request->hasFile('autobiography')){
            $autobiography_link = FileService::saveFile($request->autobiography, User::FILE_PATH);
            $autobiography_link = User::FILE_PATH.''.$autobiography_link;
        } else {
            $autobiography_link = $user->autobiography_link;
        }
        if($request->hasFile('avatar')){
            $avatar = FileService::saveFile($request->avatar, User::AVATAR_PATH);
            $avatar = User::AVATAR_PATH.''.$avatar;
        } else {
            $avatar = $user->avatar;
        }
        if($request->hasFile('certificate')){
            $certificate_link = FileService::saveFile($request->certificate, User::CERTIFICATE_PATH);
            $certificate_link = User::CERTIFICATE_PATH.''.$certificate_link;
        } else {
            $certificate_link = $user->certificate_link;
        }
        if($request->hasFile('document')){
            $document_link = FileService::saveFile($request->document, User::document_PATH);
            $document_link = User::document_PATH.''.$document_link;
        } else {
            $document_link = $user->document_link;
        }
        
        $user->update([
            'fio' => $request->fio,
            'phone' => $request->phone,
            'mail' => $request->mail,
            'real_password' => $request->real_password,
            'password' => Hash('sha1', $request->real_password),
            'audan_id' => $request->audan_id,
            'role_id' => $request->role_id,
            'iin' => $request->iin,
            'job_title' => $request->job_title,
            'avatar' => $avatar,
            'autobiography_link' => $autobiography_link,
            'certificate_link' => $certificate_link,
            'document_link' => $document_link,
        ]);
        return redirect()->back()->withSuccess('Успешно сохранено');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
    
    public function userSearch(Request $request) {
        $fio = $request->fio;
        $count = $request->scount ?? 5;
        $users = User::select(['id', 'fio', 'phone', 'job_title', 'autobiography_link'])
            ->where(function ($query) use ($fio, $count) {
                $query->where('fio', 'like', "%{$fio}%")
                      ->orWhere('job_title', 'like', "%{$fio}%");
            })
            ->take($count)
            ->get();

        return response()->json([
            'users' => $users
        ]);
    }
}
