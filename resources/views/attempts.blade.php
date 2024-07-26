<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attempt Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Attempt Results</h1>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Participant</th>
                    <th>Question</th>
                    <th>Status</th>
                    <th>Count</th>
                </tr>
            </thead>
            <tbody>
                @foreach($attempts as $attempt)
                <tr>
                    <td>{{ $attempt->participant }}</td>
                    <td>{{ $attempt->question }}</td>
                    <td>{{ $attempt->status }}</td>
                    <td>{{ $attempt->count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>