<!DOCTYPE html>
<html lang="en">
  <head>
    @include('admin.head') 
  </head>
  <body>
      
      <!-- partial:partials/_sidebar.html -->
      @include('admin.sidebar')
      <!-- partial -->
      @include('admin.navbar')
        <!-- partial -->
        <div class="container" style="color:black; padding-top:50px" align="center" >
            <div align="center" style="padding-top:100px;">
        <h1>Registered Participants</h1>
          <table>
            <tr style="background-color:black;">
            <th style="padding:10px; color:white;">ID</th>
            <th style="padding:10px; color:white;">Username</th>
            <th style="padding:10px; color:white;">First Name</th>
            <th style="padding:10px; color:white;">Last Name</th>
            <th style="padding:10px; color:white;">Email</th>
            <th style="padding:10px; color:white;">Date of Birth</th>
            <th style="padding:10px; color:white;">School Registration Number</th>
            <th style="padding:10px; color:white;">Image File</th>
        </tr>
        @foreach ($participants as $participants)
        <tr style="background-color:skyblue;" align="center">
            <td>{{ $participants->id }}</td>
            <td>{{ $participants->username }}</td>
            <td>{{ $participants->firstname }}</td>
            <td>{{ $participants->lastname }}</td>
            <td>{{ $participants->email }}</td>
            <td>{{ $participants->date_of_birth }}</td>
            <td>{{ $participants->school_registration_number }}</td>
            <td>{{ $participants->image_file }}</td>
        </tr>
        @endforeach
    </table>
    </div>
    </div>
    <!-- container-scroller -->
      @include('admin.script')
    <!-- End custom js for this page -->
  </body>
</html>