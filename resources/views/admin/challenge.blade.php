
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
                @if(session()->has('message'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    {{session()->get('message')}}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">X</button>
                </div>
                @endif
                <h1>Create Challenge</h1>
                <form action="{{url('Challenge')}}" method="POST" class="mb-5">
                    @csrf
                   
                    <div class="mt-4">
                        <label for="challenge_name">Challenge Name</label>
                        <input type="text" name="challenge_name" id="challenge_name" required class="w-full mt-2 text-dark">
                    </div>
                     <br> 
                    <div class="mt-4">
                        <label for="start_date">Start Date</label>
                        <input type="date" name="start_date" id="start_date" required class="w-full mt-2 text-dark">
                    </div>
                    <br> 
                    <div class="mt-4">
                        <label for="end_date">End Date</label>
                        <input type="date" name="end_date" id="end_date" required class="w-full mt-2 text-dark">
                    </div>
                    <br> 
                    <div class="mt-4">
                        <label for="duration">Duration(in minutes)</label>
                        <input type="number" name="duration" id="duration" required class="w-full mt-2 text-dark">
                    </div>
                    <br> 
                    <div class="mt-4">
                        <label for="num_questions">Number Of Questions</label>
                        <input type="number" required max="10" name="num_questions" id="num_questions" required class="w-full mt-2 text-dark" required max="10">
                    </div>
                    <br> 
                    <div class="mt-4">
                        <button class="btn btn-danger">Set Challenge</button>
                        {{-- <input type="submit" class="btn btn-success"> --}}
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