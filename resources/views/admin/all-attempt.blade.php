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
        <h1>Attempts</h1>
          <table>
            <tr style="background-color:black;">
            <th style="padding:10px; color:rgb(165, 58, 58);;">Attempt ID</th>
            <th style="padding:10px; color:rgb(165, 58, 58);;">UserNmae</th>
            <th style="padding:10px; color:rgb(165, 58, 58);;">School Registration Number</th>
            <th style="padding:10px; color:rgb(165, 58, 58);;">Score</th>
            <th style="padding:10px; color:rgb(165, 58, 58);;">Challenge Name</th>
           
        </tr>
        @foreach ( $attempts as  $attempts)
        <tr style="background-color:skyblue;" align="center">
            <td>{{  $attempts->id }}</td>
            <td>{{  $attempts->username}}</td>
            <td>{{  $attempts->school_registration_number}}</td>
            <td>{{  $attempts->score}}</td>
            <td>{{  $attempts->challengeName}}</td>
            
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