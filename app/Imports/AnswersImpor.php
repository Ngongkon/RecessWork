<?php

namespace App\Imports;

use App\Models\Answer;
use App\Models\Question;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AnswersImpor implements ToModel,WithHeadingRow
{
    // private $questions;
    // public function __construct(){
    //     $this->questions = Question::select('id','question_text')->get();
    // }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        //$question = $this->questions->where('question_text', $row['question_text'])->first();
        return new Answer([
            //
            'question_id' => $row['question_id'], 
            'answer_text' =>$row['answers'],
            
            
        ]);
    }
}
