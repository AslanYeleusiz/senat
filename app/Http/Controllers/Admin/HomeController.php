<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Audan;
use App\Models\Project;
use App\Models\Application;
use App\Models\Stage;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index() {
        $audans = Audan::withSum('projects', 'invest_sum')
            ->withCount('projects')
            ->withCount('stages')
            ->withCount([
                'stages as active_stages_count' => function ($query) {
                    $query->where('status', '!=', 0);
                },
            ])
            ->get();
        // Общая сумма инвестиций
        $totalInvest = $audans->sum('projects_sum_invest_sum');
        $endProjectCount = Project::whereHas('stages') // чтобы исключить проекты без стадий, если нужно
            ->whereDoesntHave('stages', function ($query) {
                $query->where('status', '=', 0);
            })
            ->count();
        $projectCount = Project::where('is_archived', 0)->count();
        $archivedProjectCount = Project::where('is_archived', 1)->count();
        $stageCount = Stage::count();

        // Добавим % инвестиций к каждому региону
        $audans = $audans->map(function ($audan) use ($totalInvest, $stageCount) {
            return [
                'id' => $audan->id,
                'name' => $audan->name,
                'lat' => $audan->lat,
                'lng' => $audan->lng,
                'iconUrl' => $audan->iconUrl,
                'invest_sum' => (float) $audan->projects_sum_invest_sum,
                'stages_count' => $audan->stages_count,
                'active_stages_count' => $audan->active_stages_count,
                'kpd' => $stageCount ? $audan->active_stages_count / $stageCount * 100 : 0,
                'projects_count' => $audan->projects_count,
                'percent_invest' => $totalInvest > 0 ? round(($audan->projects_sum_invest_sum / $totalInvest) * 100) : 0,
            ];
        });
        return Inertia::render('Admin/home', [
            'audans' => $audans,
            'totalInvest' => $totalInvest,
            'project_count' => $projectCount,
            'investor_count' => Application::count(),
            'project_end_count' => $endProjectCount,
            'active_project_count' => $projectCount - $endProjectCount,
            'freeze_project_count' => $archivedProjectCount,
        ]);
    }
}
