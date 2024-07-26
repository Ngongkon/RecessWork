<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
</head>
<div class="container">
    <h1>Participant Challenges</h1>
    <table class="table">
        <thead>
            <tr>
                
                <th>Participant</th>
                <th>Challenge</th>
                <th>Marks</th>
                
            </tr>
        </thead>
        <tbody>
            @foreach($participantChallenges as $pc)
            <tr>
                
                <td>{{ $pc->participant   }}</td>
                <td>{{ $pc->challenge }}</td>
                <td>{{ $pc->marks }}</td>
                
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
