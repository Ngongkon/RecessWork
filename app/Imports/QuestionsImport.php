<?php

namespace App\Imports;

use App\Models\Question;
use App\Models\Challenge;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionsImport implements ToModel,WithHeadingRow
{
    // private $challenges;
    // public function __construct(){
    //     $this->challenges = Challenge::select('id','title')->get();
    // }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        //$challenge = $this->challenges->where('title', $row['challenge_title'])->first();
        return new Question([
            //
            'question_text'=>$row['question'],
            

        ]);
    }
}
