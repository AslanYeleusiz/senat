<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Services\FileService;
use App\Models\User;

class ProfileController extends Controller
{
    public function index() {
        
        $profile = Auth::guard('web')->user();
        $profile->load(['role', 'audan']);
        return Inertia::render('Admin/Profile/Index', [
            'user' => $profile
        ]);
    }
    public function uploadautobiography(Request $request) {
        $profile = Auth::guard('web')->user();
        if($request->hasFile('autobiography')){
            $autobiography_link = FileService::saveFile($request->autobiography, User::FILE_PATH);
            $autobiography_link = User::FILE_PATH.''.$autobiography_link;
        } else $autobiography_link = $request->autobiography_link;
        $profile->update([
            'autobiography_link' => $autobiography_link,
        ]);
        return redirect()->route('admin.profile.index')->with('success','Успешно добавлено');
    }
    public function uploadcertificate(Request $request) {
        $profile = Auth::guard('web')->user();
        if($request->hasFile('certificate')){
            $certificate_link = FileService::saveFile($request->certificate, User::CERTIFICATE_PATH);
            $certificate_link = User::CERTIFICATE_PATH.''.$certificate_link;
        } else $certificate_link = $request->certificate_link;
        $profile->update([
            'certificate_link' => $certificate_link,
        ]);
        return redirect()->route('admin.profile.index')->with('success','Успешно добавлено');
    }
    public function uploaddocument(Request $request) {
        $profile = Auth::guard('web')->user();
        if($request->hasFile('document')){
            $document_link = FileService::saveFile($request->document, User::document_PATH);
            $document_link = User::document_PATH.''.$document_link;
        } else $document_link = $request->document_link;
        $profile->update([
            'document_link' => $document_link,
        ]);
        return redirect()->route('admin.profile.index')->with('success','Успешно добавлено');
    }
    public function uploadavatar(Request $request) {
        $profile = Auth::guard('web')->user();
        if($request->hasFile('avatar')){
            if($profile->avatar != '/avatars/avatar.png') FileService::deleteFile($profile->avatar);
            $avatar = FileService::saveFile($request->avatar, User::AVATAR_PATH);
            $avatar = User::AVATAR_PATH.''.$avatar;
        } else $avatar = '/avatars/avatar.png';
        $profile->update([
            'avatar' => $avatar,
        ]);
        return redirect()->route('admin.profile.index')->with('success','Успешно изменен');
    }
    
    
    public function deletecertificate() {
        $profile = Auth::guard('web')->user();
        
        $profile->update([
            'certificate_link' => NULL,
        ]);
        return redirect()->route('admin.profile.index')->with('success','Успешно удалено');
    }
    public function deleteautobiography() {
        $profile = Auth::guard('web')->user();
        FileService::deleteFile($profile->autobiography_link);
        $profile->update([
            'autobiography_link' => NULL,
        ]);
        return redirect()->route('admin.profile.index')->with('success','Успешно удалено');
    }
    public function deletedocument() {
        $profile = Auth::guard('web')->user();
        $profile->update([
            'document_link' => NULL,
        ]);
        return redirect()->route('admin.profile.index')->with('success','Успешно удалено');
    }
    
    
    
}
