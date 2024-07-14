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
                <h1>Add School</h1>
                <form action="{{url('add-new-school')}}" method="POST" class="mb-5">
                    @csrf
                   
                    <div class="mt-4">
                        <label for="school_name">School Name</label>
                        <input type="text" name="school_name" placeholder="" required class="w-full mt-2 text-dark">
                    </div>
                     <br> 
                    <div class="mt-4">
                        <label for="district">School District</label>
                        <input type="text" name="district" placeholder="" required class="w-full mt-2 text-dark">
                    </div>
                    <br> 
                    <div class="mt-4">
                        <label for="registration_number">School Registration  Number </label>
                        <input type="text" name="school_registration_number" placeholder="" required class="w-full mt-2 text-dark">
                    </div>
                    <br> 
                    <div class="mt-4">
                        <label for="representative_name">School Representative Name</label>
                        <input type="text" name="representative_name" placeholder="" required class="w-full mt-2 text-dark">
                    </div>
                    <br> 
                    <div class="mt-4">
                        <label for="representative_email">School Representative Email</label>
                        <input type="text" name="representative_email" placeholder="" required class="w-full mt-2 text-dark">
                    </div>
                    <br> 
                    <div class="mt-4">
                        <button class="btn btn-danger">Add School</button>
                        {{-- <input type="submit" class="btn btn-success"> --}}
                    </div>
                </form>
                @include('admin.footer')
            </div>
            </div>
        </div>
    <!-- container-scroller -->
    @include('admin.script')
    {{-- <script>
        const building = document.getElementById('building');
        const floors = document.getElementById('floor');
        building.addEventListener('blur', function(){
            floors.disabled = false;
            const name = building.value;
            const xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function(){
                if(this.readyState === 4 && this.status === 200)
                {
                    const floorNumber = parseInt(this.responseText);
                    for(var index = 1; index <= floorNumber; index++)
                    {
                        const level = document.createElement('option');
                        if(index == 1)
                        {
                            level.setAttribute('value', 'Ground');
                            level.innerHTML = 'Ground Floor';
                        }
                        else if(index == floorNumber)
                        {
                            level.setAttribute('value', 'Top');
                            level.innerHTML = 'Ground Floor';
                        }
                        else
                        {
                            level.setAttribute('value', index);
                            level.innerHTML = index;
                        }
                        floors.appendChild(level);
                    }
                }
            };
            xhr.open('GET', '/get_floors' + encodeURIComponent(name));
            xhr.send();
        });
    </script> --}}
  </body>
</html>