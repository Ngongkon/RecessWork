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
        <h1>Results</h1>
          <table>
            <tr style="background-color:black;">
            <th style="padding:10px; color:rgb(165, 58, 58);">Result ID</th>
            <th style="padding:10px; color:rgb(165, 58, 58);;">Given Answer</th>
            <th style="padding:10px; color:rgb(165, 58, 58);;">Attempt ID</th>
            <th style="padding:10px; color:rgb(165, 58, 58);;">Question ID</th>
            <th style="padding:10px; color:rgb(165, 58, 58);;">Marks Obtained</th>
            <th style="padding:10px; color:rgb(165, 58, 58);;">Time Taken</th>
        </tr>
        @foreach (  $results as  $results)
        <tr style="background-color:skyblue;" align="center">
            <td>{{   $results->id }}</td>
            <td>{{   $results->given_answer}}</td>
            <td>{{   $results->attempt_id}}</td>
            <td>{{   $results->question_id}}</td>
            <td>{{   $results->marks_obtained}}</td>
            <td>{{   $results->time_taken}}</td>
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