<?php

namespace App\Filament\Resources\InstructorResource\Pages;

use App\Enums\ApprovalStatus;
use App\Filament\Resources\InstructorResource;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListInstructors extends ListRecords
{
    protected static string $resource = InstructorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')->label(__('main.all'))->modifyQueryUsing(fn ($query) => $query),
            'waiting_for_approval' => Tab::make('Waiting for Approval')->label(__('main.waiting_for_approval'))->modifyQueryUsing(fn ($query) => $query->where('status', ApprovalStatus::WaitingForApproval)),
            'approved' => Tab::make('Approved')->label(__('main.approved'))->modifyQueryUsing(fn ($query) => $query->where('status', ApprovalStatus::Approved)),
            'rejected' => Tab::make('Rejected')->label(__('main.rejected'))->modifyQueryUsing(fn ($query) => $query->where('status', ApprovalStatus::Rejected)),
        ];
    }
}
