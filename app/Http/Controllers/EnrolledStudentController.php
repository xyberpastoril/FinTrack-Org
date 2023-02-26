<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Student\ImportStudentsRequest;
use App\Models\Student;
use App\Imports\StudentsImport;
use App\Models\AttendanceEvent;
use App\Models\AttendanceEventLog;
use App\Models\EnrolledStudent;
use App\Models\Fee;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class EnrolledStudentController extends Controller
{
    public function index()
    {
        $students = EnrolledStudent::where('semester_id', session('semester')->id)->get();
        return view('students.enrolled.index', compact('students'));
    }

    public function import(ImportStudentsRequest $request)
    {
        $validated = $request->validated();

        Excel::import(new StudentsImport, $validated['file']);

        return redirect()->route('students.enrolled.index')->with('success', 'Students imported successfully.');
    }

    public function searchAjax($query = null)
    {
        $students = Student::select(
                'enrolled_students.id as value',
                'students.id_number',
                'students.first_name',
                'students.last_name',
                'students.middle_name',
                'degree_programs.abbr as degree_program',
                'enrolled_students.year_level',
            )
            // left join enrolled students where semester_id = session('semester')->id
            ->leftJoin('enrolled_students', function($join) {
                $join->on('students.id', '=', 'enrolled_students.student_id');
            })
            ->where('enrolled_students.id', '!=', null)
            ->where('enrolled_students.semester_id', '=', session('semester')->id)
            ->leftJoin('degree_programs', 'enrolled_students.degree_program_id', '=', 'degree_programs.id')
            ->whereEncrypted('last_name', 'like', "%$query%")
            ->orWhereEncrypted('first_name', 'like', "%$query%")
            ->orWhereEncrypted('middle_name', 'like', "%$query%")
            ->orWhereEncrypted('id_number', 'like', "%$query%")
            ->get();

        $students = $students->map(function($student) {
            $student->label = $student->last_name . ', ' . $student->first_name . ' ' . $student->middle_name . ' - ' . $student->degree_program . ' ' . $student->year_level;


            return $student;
        });

        return response()->json($students);
    }

    public function getFeesAjax(EnrolledStudent $enrollee)
    {
        $sub = Fee::select(
            'fees.id',
            'fees.name',
            'fees.amount',
            'transactions.id as transaction_id',
        )
        ->leftJoin('transactions', 'fees.id', '=', 'transactions.foreign_key_id')
        ->leftJoin('receipts', 'transactions.receipt_id', '=', 'receipts.id')
        ->where('transactions.category', 'fee')
        ->where('fees.is_required', true)
        ->where('fees.semester_id', session('semester')->id)
        ->where('receipts.enrolled_student_id', $enrollee->id);

        return Fee::select(
            'fees.id',
            'fees.name',
            'fees.amount',
            DB::raw('IF(sub_fees.transaction_id IS NULL, 0, 1) as is_paid')
        )->leftJoinSub($sub, 'sub_fees', function($join) {
            $join->on('fees.id', '=', 'sub_fees.id');
        })
        ->where('fees.semester_id', session('semester')->id)
        ->where('fees.is_required', true)
        ->get();
    }

    public function getFinesAjax(EnrolledStudent $enrollee)
    {
        // sub log count per attendance event and enrolled_student_id
        $subLogCount = AttendanceEventLog::select(
            'attendance_event_logs.attendance_event_id',
            DB::raw('COUNT(attendance_event_logs.id) as log_count')
        )
        ->where('attendance_event_logs.enrolled_student_id', $enrollee->id)
        ->groupBy('attendance_event_logs.attendance_event_id');

        $subTransactions = Transaction::select(
            "transactions.id as transaction_id",
            "transactions.foreign_key_id as attendance_event_id",
        )
        ->leftJoin('receipts', 'transactions.receipt_id', '=', 'receipts.id')
        ->where('transactions.category', 'fine')
        ->where('receipts.enrolled_student_id', $enrollee->id);

        $main = AttendanceEvent::select(
            "attendance_events.id",
            "attendance_events.name",
            "attendance_events.required_logs",
            "attendance_events.fines_amount_per_log",
            DB::raw('IFNULL(sub_log_count.log_count, 0) as log_count'),
            DB::raw('IF(sub_transactions.transaction_id IS NULL, 0, 1) as is_paid'),
            // calculate amount
            DB::raw('fines_amount_per_log * (required_logs - IFNULL(sub_log_count.log_count, 0)) as amount')
        )
        // sub log count
        ->leftJoinSub($subLogCount, 'sub_log_count', function($join) {
            $join->on('attendance_events.id', '=', 'sub_log_count.attendance_event_id');
        })
        // sub transactions
        ->leftJoinSub($subTransactions, 'sub_transactions', function($join) {
            $join->on('attendance_events.id', '=', 'sub_transactions.attendance_event_id');
        })
        ->leftJoin('events', 'attendance_events.event_id', '=', 'events.id')
        ->where('events.semester_id', session('semester')->id)
        ->where('attendance_events.required_logs', '!=', '0')
        ->where('attendance_events.fines_amount_per_log', '!=', '0')
        ->where(DB::raw('fines_amount_per_log * (required_logs - IFNULL(sub_log_count.log_count, 0))'), '!=', '0')
        ->get();


        return $main;
    }
}
