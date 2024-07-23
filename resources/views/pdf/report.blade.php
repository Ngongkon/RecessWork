<!DOCTYPE html>
<html>
<head>
    <title>Questions and Answers Report</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
    </style>
</head>
<body>
    <h1>Questions and Answers Report</h1>
    <table>
        <thead>
            <tr>
                <th>Question</th>
                <th>Answer</th>
                <th>Marks</th>
            </tr>
        </thead>
        <tbody>
            @foreach($questions as $question)
                @foreach($question->answers as $answer)
                    <tr>
                        <td>{{ $question->question_text }}</td>
                        <td>{{ $answer->answer_text }}</td>
                        <td>{{ $answer->marks }}</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</body>
</html>
