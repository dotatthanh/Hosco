@extends('layout.admin.index')
@section('title')
   Change Password
@endsection
@section('extra_css')
@endsection
@section('content')
    <div class="page-header"><h3>Change Password</h3></div>
   <form id="user_address" method="post" action="{{ route('admin.address.update',$user->username) }}">
      @csrf
      @method('put')
      <div class="setup-content" id="step-2">
         <div class="row">
            <div class="col-sm-6">
               <div class="form-group required">
                  <label for="currentPass" class=" control-label">Current Password: <span>*</span></label>
                  <input type="text" value="" name="currentPass" class="form-control" id="currentPass" required>
               </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group required">
                  <label for="newPassword" class=" control-label">New Password: <span>*</span></label>
                  <input type="text" value="" name="newPassword" class="form-control" id="newPassword" required>
               </div>
            </div>
            <div class="form-group">
               <div class="btn-group btn-group-justified">
                  <div class="btn-group">
                     <input type="submit" class="btn btn-info " value="SAVE">
                  </div>
                  <div class="btn-group">
                     <a class="btn btn-danger" onclick="history.back()">BACK</a>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </form>




@endsection
@section('extra_js')
@endsection
