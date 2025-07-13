<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Audan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Resources\Admin\AudanResource;

class AudanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $name = $request->name;

        // Запрос для получения округов с подсчетом проектов
        $audans = Audan::when($name, function($query) use ($name) {
                $query->where('name', 'like', "%$name%");
            })
            ->withCount('projects') // Подсчет проектов для каждого округа
            ->paginate($request->input('per_page', 20))
            ->appends($request->except('page'));

        // Преобразуем округи с использованием ресурса
        return Inertia::render('Admin/Audans/Index', [
            'audans' => AudanResource::collection($audans)
        ]);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Admin/Audans/Create');
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Audan::create([
            'name' => $request->name,
            'lat' => $request->lat,
            'lng' => $request->lng,
        ]);
        return redirect()->route('admin.audans.index')->with('success','Успешно добавлено');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Audan  $audan
     * @return \Illuminate\Http\Response
     */
    public function show(Audan $audan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Audan  $audan
     * @return \Illuminate\Http\Response
     */
    public function edit(Audan $audan)
    {
        return Inertia::render('Admin/Audans/Edit', [
            'audan' => $audan
        ]);
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Audan  $audan
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Audan $audan)
    {
        $audan->update([
            'name' => $request->name,
            'lat' => $request->lat,
            'lng' => $request->lng
        ]);
        return redirect()->back()->withSuccess('Успешно сохранено');
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Audan  $audan
     * @return \Illuminate\Http\Response
     */
    public function destroy(Audan $audan)
    {
        $audan->delete();
        return redirect()->route('admin.audans.index')->with('success','Успешно удалено!');
        
    }
}
