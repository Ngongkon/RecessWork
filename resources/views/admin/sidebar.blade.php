<div class="sidebar pe-4 pb-3">
    <nav class="navbar bg-secondary navbar-dark">
        <a href="" class="navbar-brand mx-4 mb-3">
            <h3 class="text-primary"><i class="fa fa-user-edit me-2"></i>DarkPan</h3>
        </a>
        <div class="d-flex align-items-center ms-4 mb-4">
            <div class="position-relative">
                <img class="rounded-circle" src="/master/img/user.jpg" alt="" style="width: 40px; height: 40px;">
                <div class="bg-success rounded-circle border border-2 border-white position-absolute end-0 bottom-0 p-1"></div>
            </div>
            <div class="ms-3">
                <h6 class="mb-0">{{ Auth::user()->name }}</h6>
                <span>Admin</span>
            </div>
        </div>
        <div class="navbar-nav w-100">
            <a href="{{url('admin/dashboard')}}" class="nav-item nav-link active"><i class="fa fa-tachometer-alt me-2"></i>Dashboard</a>
            <a href="{{url('addingSchool')}}" class="nav-item nav-link"><i class="fa fa-th me-2"></i>Add School</a>
            <a href="{{url('allSchools')}}"  class="nav-item nav-link"><i class="fa fa-th me-2"></i>Registered Sch</a>
            <a href="{{url('participant')}}" class="nav-item nav-link"><i class="fa fa-th me-2"></i>Participants</a>
            <a href="{{ url('challenges') }}" class="nav-item nav-link"><i class="fa fa-th me-2"></i>Challenges</a>
            <a href="{{url('allQuestions')}}" class="nav-item nav-link"><i class="fa fa-th me-2"></i>Add Questions</a>
            <a href="{{url('allAnswers')}}"class="nav-item nav-link"><i class="fa fa-th me-2"></i>Add Answers</a>
            <a href="{{route('sendEmailsForm')}}"class="nav-item nav-link"><i class="fa fa-th me-2"></i>Send Results</a>
            {{-- <a href="widget.html" class="nav-item nav-link"><i class="fa fa-th me-2"></i>Widgets</a> --}}
            <a href="{{url('attempts')}}" class="nav-item nav-link"><i class="fa fa-keyboard me-2"></i>Most Correct Answered Questions</a>
            <a href="{{url('challenges')}}" class="nav-item nav-link"><i class="fa fa-keyboard me-2"></i>Participant Performance</a>
           
          
        </div>
    </nav>
    
</div>