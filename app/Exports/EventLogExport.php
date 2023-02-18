<?php

namespace App\Exports;

use App\Models\Event;
use App\Models\Student;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EventLogExport implements FromCollection, WithHeadings
{
    protected $event;

    public function __construct(Event $event)
    {
        $this->event = $event;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $event = $this->event;

        $export = Student::select(
                'students.id_number',
                'students.last_name',
                'students.first_name',
                'degree_programs.abbr',
                'students.year_level',
                DB::raw('IF(timein_events.id IS NOT NULL, "1", "0") as time_in'),
                DB::raw('IF(timeout_events.id IS NOT NULL, "1", "0") as time_out'),
            )
            ->leftJoin('degree_programs', 'students.degree_program_id', '=', 'degree_programs.id')
            // left join event_logs where event_id = $this->event->id and status = $this->event->status = 'timein'
            ->leftJoin('event_logs as timein_events', function($join) use ($event){
                $join->on('students.id', '=', 'timein_events.student_id')
                    ->where('timein_events.status', '=', 'timein')
                    ->where('timein_events.event_id', '=', $event->id);
            })
            // left join event_logs where event_id = $this->event->id and status = $this->event->status = 'timeout'
            ->leftJoin('event_logs as timeout_events', function($join) use ($event){
                $join->on('students.id', '=', 'timeout_events.student_id')
                    ->where('timeout_events.status', '=', 'timeout')
                    ->where('timeout_events.event_id', '=', $event->id);
            })
            ->get();

        return $export;
    }

    /**
    * @var EventLog $log
    */
    public function map($log): array
    {
        return [
            $log->id_number,
            $log->last_name,
            $log->first_name,
            $log->abbr,
            $log->year_level,
            $log->time_in,
            $log->time_out,
        ];
    }

    public function headings(): array
    {
        return [
            'id_number',
            'last_name',
            'first_name',
            'degree_program',
            'year_level',
            'time_in',
            'time_out',
        ];
    }
}
