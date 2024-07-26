<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\School;

class StatisticsController extends Controller
{
 public function showStatistics(){
    $result = DB::select("
    SELECT COUNT(participants.firstname) AS em_name, schools.school_name
    FROM participants
    LEFT JOIN schools ON schools.school_registration_number = participants.school_registration_number
    GROUP BY schools.school_name
");
    $data =" ";
     foreach ($result as $val) {
        $data .= "[' {$val->school_name}', {$val->em_name}],";
     }
    
   $dataChart = $data;
  



return view('admin.statistics',Compact('dataChart'));

}
public function statistics(){
    $result = School::all();
    
     
    return view('graph.pie',['result' => $result]);
}

}
