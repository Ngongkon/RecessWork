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
        <h1 > Number of Schools Registered</h1>
          <table>
            <tr style="background-color:black;">
            <th style="padding:10px; color:white;">ID</th>
            <th style="padding:10px; color:white;">School Name</th>
            <th style="padding:10px; color:white;">School District</th>
            <th style="padding:10px; color:white;">School Registration Number</th>
            <th style="padding:10px; color:white;">School Represntative Name</th>
            <th style="padding:10px; color:white;">School Representative Email</th>
        
        </tr>
        @foreach ($schools as $schools)
        <tr style="background-color:skyblue;" align="right">
            <td>{{ $schools->id }}</td>
            <td>{{ $schools->school_name }}</td>
            <td>{{ $schools->district }}</td>
            <td>{{ $schools->school_registration_number}}</td>
            <td>{{ $schools->representative_name}}</td>
            <td>{{ $schools->representative_email }}</td>
          
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