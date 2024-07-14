{{-- <!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.head')
  <title>Document</title>
    <style>
        .container {
            width: 640px;
            padding: 40px 40px;
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
                  <h1>Set Challenge Parameters</h1>
   
    <form action="{{ url('Parameters') }}" method="POST" class="mb-5">
        @csrf
        <div class="form-group">
            <label for="challenge_id">Challenge</label>
            <select name="challenge_id" id="challenge_id" class="form-control">
                @foreach($challenges as $challenge)
                    <option value="{{ $challenge->id }}">{{ $challenge->title }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" name="start_date" id="start_date" class="form-control">
        </div>
        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" name="end_date" id="end_date" class="form-control">
        </div>
        <div class="form-group">
            <label for="duration">Duration (minutes)</label>
            <input type="number" name="duration" id="duration" class="form-control">
        </div>
        <div class="form-group">
            <label for="num_questions">Number of Questions</label>
            <input type="number" name="num_questions" id="num_questions" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Set Parameters</button>
    </form>
        @include('admin.footer')
    </div>
    </div>
</div>

@include('admin.script')
</body>
</html> --}}


<!DOCTYPE html>
<html lang="en">
  <head>
    @include('admin.head')
    <title>TechEducation Admin</title>
    <style>
        .container {
            width: 640px;
            padding: 40px 40px;
        }
    </style>
  </head>
  <body>
    <div class="container-scroller">
      <!-- partial:partials/_sidebar.html -->
      @include('admin.sidebar')
      <!-- partial -->
      <div class="container-fluid page-body-wrapper">
        <div class="content-wrapper">
        <!-- partial:partials/_navbar.html -->
        @include('admin.navbar')
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <div class="container">
                @if(session()->has('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    {{session()->get('success')}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">X</button>
                </div>
                @endif
                <h1>Set Challenge</h1>
                <form action="{{ url('Parameters') }}" method="POST" class="mb-5">
                    @csrf
                    {{-- <div class="mt-4">
                        <label for="id">Challenge</label>
                        <select name="id" id="id" class="w-full mt-2 text-dark">
                            @foreach($challenges as $challenge)
                                <option value="{{ $challenge->id }}">{{ $challenge->title }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                    <div class="mt-4">
                        <label for="challengeName">Challenge Name</label>
                        <input type="number" name="challengeName" id="challengeName" placeholder="" required class="w-full mt-2 text-dark">
                    </div>
                     <br> 
                    <div class="mt-4">
                        <label for="title">Challenge title</label>
                        <input type="text" name="title" id="title" placeholder="" required class="w-full mt-2 text-dark">
                    </div>
                     <br> 
                   
                    <div class="mt-4">
                        <label for="start_date">Start Date</label>
                        <input type="date" name="start_date" id="start_date" placeholder="" required class="w-full mt-2 text-dark">
                    </div>
                     <br> 
                    <div class="mt-4">
                        <label for="end_date">End Date</label>
                        <input type="date" name="end_date" id="end_date" placeholder="" required class="w-full mt-2 text-dark">
                    </div>
                    <br> 
                    <div class="mt-4">
                        <label for="duration">Duration (minutes)</label>
                        <input type="number" name="duration" id="duration" placeholder="" required class="w-full mt-2 text-dark">
                    </div>
                    <br> 
                    <div class="mt-4">
                        <label for="num_questions">Number of Questions</label>
                        <input type="number" name="num_questions" id ="num_questions" placeholder="" required class="w-full mt-2 text-dark">
                    </div>
                    <br> 
                   
                    <div class="mt-4">
                        <button class="btn btn-danger">Set Parameters</button>
                        {{-- <label for="set_parameter">Set Parameters</label>
                        <input type="submit" class="btn btn-success"> --}}
                    </div>
                </form>
                @include('admin.footer')
            </div>
            </div>
        </div>
    <!-- container-scroller -->
    @include('admin.script')

  </body>
</html>