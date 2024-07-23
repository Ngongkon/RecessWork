
<!DOCTYPE html>
<html>
<head>
  @include('admin.head')
    <style>
      .container {
          width: 640px;
          padding: 40px 40px;
      }
      .checkbox-group {
            display: flex;
            flex-direction: column;
        }
        .checkbox-group label {
          margin-bottom: 2px;
        }
  </style>
</head>
<body>
  <div class="container-scroller">
  @include('admin.sidebar')
  <div class="container-fluid page-body-wrapper">
    <div class="content-wrapper">

  @include('admin.navbar')
  <div class="container-fluid page-body-wrapper">
    <div class="container">
  @if(session()->has('success'))
  <div class="alert alert-success alert-dismissible" role="alert">
      {{session()->get('success')}}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">X</button>
  </div>
  @endif
    <h1>Send questions with answers</h1><br><br>
    <form action="{{url('sendingEmails')}}" method="POST" enctype="multipart/form-data" class="mb-5">
        @csrf
        <div class="mb-4">
        <label for="subject">Subject:</label>
        <input type="text" name="subject" required><br><br>
        </div>
        
        <div class="mb-4">
        <label for="message">Message:</label>
        <textarea name="message" required></textarea><br><br>
         </div>
        
         <div class="mb-4">
        <label for="file">Attachment:</label>
        <input type="file" name="file" accept="application/pdf" required><br><br>
        </div>
        
        <div class="mb-4">
        <label for="recipients">Select Participants:</label>
          <div class="checkbox-group">
            @foreach($participants as $participant)
                {{-- <option value="{{ $participant->email }}">{{ $participant->email }}</option> --}}
                <input type="checkbox" name="recipients[]" value="{{ $participant->email }}" id="email_{{ $loop->index }}">
                <label for="email_{{ $loop->index }}">{{ $participant->email }}</label><br><br>
            @endforeach
        </div>
        </div>
        
        <div class="mb-4">
        <button type="submit" class="btn btn-danger">Send</button>
        </div>
    </form>
  </div>
</div>
</div>
    @include('admin.script')
</body>
</html>
