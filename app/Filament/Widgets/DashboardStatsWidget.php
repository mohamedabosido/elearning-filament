<?php

namespace App\Filament\Widgets;

use App\Enums\ApprovalStatus;
use App\Models\Course;
use App\Models\Instructor;
use App\Models\Student;
use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\Concerns\InteractsWithPageFilters;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DashboardStatsWidget extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {
        $start_date = $this->filters['start_date'] ?? now()->startOfMonth()->toDateString();
        $end_date = $this->filters['end_date'] ?? now()->toDateString();
        return [
            Stat::make('New Students', Student::whereBetween('created_at', [$start_date, $end_date])->count())
            ->label(__('main.new_students'))
            ->description(__('main.total_registered_students'))
            ->descriptionIcon('heroicon-o-user-group', IconPosition::Before)
            ->chart([5,10,2,8,12,10,1])
            ->color('warning'),
            Stat::make('New Instructors', Instructor::whereBetween('created_at', [$start_date, $end_date])->count())
            ->label(__('main.new_instructors'))
            ->description(__('main.total_registered_instructors'))
            ->descriptionIcon('heroicon-o-academic-cap', IconPosition::Before)
            ->chart([5,4,3,2,1])
            ->color('danger'),
            Stat::make('New Courses', Course::where('status', ApprovalStatus::Approved)->whereBetween('created_at', [$start_date, $end_date])->count())
            ->label(__('main.new_courses'))
            ->description(__('main.total_published_courses'))
            ->descriptionIcon('heroicon-o-book-open', IconPosition::Before)
            ->chart([1,10,5,2,8,3,15,7,10])
            ->color('success'),
        ];
    }
}
