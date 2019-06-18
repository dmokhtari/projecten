<?php

namespace App\Imports;

use App\Models\User;
use Carbon\Carbon;
use Hash;
use Illuminate\Support\Str;
use Log;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class UserImport implements ToModel
{
    /**
     * @var int student role
     */
    private $student = 1;

    /**
     * @param array $row
     * @return User|\Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Model[]|null
     * @throws \Exception
     */
    public function model(array $row)
    {
        $user = new User([
            'email' => $row[0],
            'end_date_study' => $this->transformDate($row[1]),
            'class' => ($row[2] ?: ''),
            'forename' => ($row[3] ?: 'student'),
            'surname' => ($row[4] ?: ''),
            'remember_token' => Str::random(10),
            'password' => Hash::make(str_random(8))
        ]);
        $user->save();
        $user->roles()->attach($this->student);
        return $user;
    }

    /**
     * @param $value
     * @param string $format
     * @return Carbon
     * @throws \Exception
     */
    public function transformDate($value, $format = 'Y-m-d')
    {
        try {
            return Carbon::instance(Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            return Carbon::createFromFormat($format, $value);
        }
    }
}
