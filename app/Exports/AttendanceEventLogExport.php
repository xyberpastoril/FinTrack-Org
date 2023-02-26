<?php

namespace App\Exports;

use App\Models\AttendanceEvent;
use App\Models\Event;
use App\Models\Student;
use ESolution\DBEncryption\Encrypter;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AttendanceEventLogExport implements FromCollection, WithHeadings
{
    protected $event;
    protected $attendance;

    public function __construct(Event $event, AttendanceEvent $attendance)
    {
        $this->event = $event;
        $this->attendance = $attendance;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $attendance = $this->attendance;

        $logs = Student::select(
            'students.id_number',
            'students.last_name',
            'students.first_name',
            'degree_programs.abbr',
            'enrolled_students.year_level',
            DB::raw('IF(timein_events.id IS NOT NULL, "1", "0") as time_in'),
            DB::raw('IF(timeout_events.id IS NOT NULL, "1", "0") as time_out'),
        )
        ->leftJoin('enrolled_students', 'students.id', '=', 'enrolled_students.student_id')
        ->where('enrolled_students.semester_id', '=', $this->event->semester_id)
        ->leftJoin('degree_programs', 'enrolled_students.degree_program_id', '=', 'degree_programs.id')
        // left join event_logs where event_id = $this->event->id and status = $this->event->status = 'timein'
        ->leftJoin('attendance_event_logs as timein_events', function($join) use ($attendance){
            $join->on('enrolled_students.id', '=', 'timein_events.enrolled_student_id')
                ->where('timein_events.status', '=', 'timein')
                ->where('timein_events.attendance_event_id', '=', $attendance->id);
        })
        // left join event_logs where event_id = $this->event->id and status = $this->event->status = 'timeout'
        ->leftJoin('attendance_event_logs as timeout_events', function($join) use ($attendance){
            $join->on('enrolled_students.id', '=', 'timeout_events.enrolled_student_id')
                ->where('timeout_events.status', '=', 'timeout')
                ->where('timeout_events.attendance_event_id', '=', $attendance->id);
        })
        ->get();

        $logs = $logs->map(function($log) {
            // decrypt degree program abbr
            $log->abbr = Encrypter::decrypt($log->abbr);
            return $log;
        });

        return $logs;
    }

    /**
    * @var AttendanceEventLog $log
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
