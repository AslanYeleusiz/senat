<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Audan;
use App\Models\ProjectType;
use App\Models\Application;
use Illuminate\Http\Request;
use App\Services\FileService;
use App\Http\Requests\Admin\ProjectStoreRequest;
use App\Http\Requests\Admin\ProjectUpdateRequest;
use App\Models\Stage;
use App\Services\PdfGenerateService;
use Inertia\Inertia;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ProjectController extends Controller
{
    public $pdfGenService;
    public function __construct(PdfGenerateService $pdfGenService)
    {
        $this->pdfGenService = $pdfGenService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $country = $request->country;
        $audan_id = $request->audan_id;
        $project_type = $request->project_type;
        $projects = Project::with(['audan', 'projectType'])
            ->when($country, fn($q) => $q->where('country', 'like', "%$country%"))
            ->when($audan_id, fn($q) => $q->where('audan_id', $audan_id))
            ->when($project_type, fn($q) => $q->where('project_type', 'like', "%$project_type%"))
            ->latest('id')
            ->paginate($request->input('per_page', 20))
            ->appends($request->except('page'));
        $audans = Audan::get();
        $projectTypes = ProjectType::get();
        return Inertia::render('Admin/Projects/Index', [
            'projects' => $projects,
            'audans' => $audans,
            'projectTypes' => $projectTypes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $audans = Audan::get();
        $projectTypes = ProjectType::get();
        return Inertia::render('Admin/Projects/Create', [
            'audans' => $audans,
            'projectTypes' => $projectTypes,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProjectStoreRequest $request)
    {

        if ($request->hasFile('file')) {
            $file_path = FileService::saveFile($request->file, Project::FILE_PATH);
        } else {
            $file_path = $request->file;
        }
        $imagePaths = [];
        if (count($request->images)) {
            foreach ($request->images as $image) {
                $path = FileService::saveFile($image, Project::IMAGES_PATH);
                $imagePaths[] = $path;
            }
        }

        Project::create([
            'user_id' => Auth::guard('web')->user()->id,
            'name' => $request->name,
            'description' => $request->description,
            'country' => $request->country,
            'audan_id' => $request->audan_id,
            'project_type' => $request->project_type,
            'invest_sum' => (int) preg_replace('/[^\d]/', '', $request->invest_sum),
            'people_count' => $request->people_count,
            'curator_id' => $request->curator_id,
            'phone' => $request->phone,
            'curator' => $request->curator,
            'file' => $file_path,
            'images' => $imagePaths,
        ]);
        return redirect()->route('admin.projects.index')->with('success', 'Успешно добавлено');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project)
    {
        $audans = Audan::get();
        $project->load(['audan:id,name','curator:id,fio,job_title','projectType', 'stages' => fn ($query) => $query->with(['user:id,fio,job_title'])->orderBy('date'), 'questions.user:id,fio']);
        return Inertia::render('Admin/Projects/details', [
            'project' => $project,
            'audans' => $audans
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project)
    {
        $audans = Audan::get();
        $projectTypes = ProjectType::get();
        return Inertia::render('Admin/Projects/Edit', [
            'project' => $project,
            'projectTypes' => $projectTypes,
            'audans' => $audans
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(ProjectUpdateRequest $request, Project $project)
    {
        if ($request->hasFile('file')) {
            $file_path = FileService::saveFile($request->file, Project::FILE_PATH);
        } else {
            $file_path = $project->file;
        }
        $project->update([
            'name' => $request->name,
            'description' => $request->description,
            'country' => $request->country,
            'audan_id' => $request->audan_id,
            'project_type' => $request->project_type,
            'invest_sum' => $request->invest_sum,
            'people_count' => $request->people_count,
            'curator_id' => $request->curator_id,
            'phone' => $request->phone,
            'curator' => $request->curator,
            'file' => $file_path
        ]);
        return redirect()->back()->withSuccess('Успешно сохранено');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project)
    {
        $project->update([
            'is_archived' => $project->is_archived ? 0 : 1
        ]);
        return redirect()->back()->withSuccess('Успешно заархивирован');
    }

    public function stagestore(Request $request, $id)
    {
        Stage::create([
            'project_id' => $id,
            'date' => $request->date,
            'user_id' => $request->user_id,
            'name' => $request->name,
            'status' => $request->status ? $request->status : 0,
        ]);
        return redirect()->back()->withSuccess('Успешно сохранено');
        
    }
    
    public function stageConfirm(Request $request, $id)
    {
        $stage = Stage::findOrFail($id);
        $colorStatus = $stage->color_status;
        $stage->update([
            'status' => $colorStatus['status'] ? $colorStatus['status'] : 0,
            'comment' => $request->value
        ]);
        return redirect()->back()->withSuccess('Успешно сохранено');
        
    }
    
    public function createPasport($id){
        $certificateName = $this->pdfGenService->getPdfGen($id);
        return $this->downloadPDF($certificateName);
    }
    
    protected function downloadPDF($url){
        $data = [
            'url' => $url
        ];
             // Создание PDF с альбомной ориентацией
        $pdf = PDF::loadView('pdf.certificate', $data);
        $pdf->setPaper('A4', 'landscape'); // Установка альбомной ориентации
//        return $pdf->stream('certificate.pdf'); // Открыть в браузере
        return $pdf->download('invoice.pdf');
    }
    
    public function applicationStore(Request $request) {
        $project = Project::findOrFail($request->project_id);
        $application = Application::where('project_id', $project->id)->first();
        if($application) {
            return redirect()->back()->withErrors('Уже существует!');
            
        }
        
        if($request->hasFile('autobiography_link')){
            $file_path = FileService::saveFile($request->autobiography_link, Application::FILE_PATH);
            $file_path = Application::FILE_PATH.''.$file_path;
        } else $file_path = null;
        if($request->hasFile('certificate_link')){
            $certificate_path = FileService::saveFile($request->certificate_link, Application::CERTIFICATE_PATH);
            $certificate_path = Application::CERTIFICATE_PATH.''.$certificate_path;
        } else $certificate_path = null;
        if($request->hasFile('other_link')){
            $other_path = FileService::saveFile($request->other_link, Application::DOCUMENT_PATH);
            $other_path = Application::DOCUMENT_PATH.''.$other_path;
        } else $other_path = null;
        
        
        Application::create([
            'project_id' => $project->id,
            'name' => $request->name,
            'send_from' => $request->send_from,
            'send_to' => $request->send_to,
            'investor' => $request->investor,
            'file_link' => $project->file,
            'document_link' => env('APP_URL') . '/project/' . $project->id . '/createPasport',
            'autobiography_link' => $file_path,
            'certificate_link' => $certificate_path,
            'other_link' => $other_path,
            'date' => Carbon::now()->toDateString(),
            'time' => Carbon::now()->toTimeString(),
        ]);
        return redirect()->back()->withSuccess('Успешно отправлено');
        
    }
}
