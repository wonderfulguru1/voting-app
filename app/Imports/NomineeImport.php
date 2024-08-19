<?php

namespace App\Imports;

use App\NomineeTemplate;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NomineeImport implements ToModel , WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new NomineeTemplate([
            'nigam_no' => $row['nigam_no'],
            'name_of_member' => $row['name_of_member'],
            'email' => $row['email'],
            'telephone' => $row['telephone'],
        ]);
    }
}


