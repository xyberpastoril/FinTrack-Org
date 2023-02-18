<?php

namespace App\Imports;

use App\Models\DegreeProgram;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DegreeProgramImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new DegreeProgram([
            'name' => $row['name'],
            'abbr' => $row['abbr'],
        ]);
    }
}
