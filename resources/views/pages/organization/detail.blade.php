@extends('layouts.app')
@section('title', 'Organization Detail')
@section('content')
<div class="row justify-content-md-center">
<div class="col-8 ">
   <div class="row mt-5">
      <div class="col-10">
         <h4>Organization Detail</h4>
      </div>
   </div>
   <div class="row mt-3">
      <form id="form-org">
         <div class="mb-3 row">
            <label for="name" class="col-sm-2 col-form-label">Name</label>
            <label class="col-sm-10 col-form-label" id="name">{{$data->name}}</label>
         </div>
         <div class="mb-3 row">
            <label for="email" class="col-sm-2 col-form-label">Email</label>
            <label class="col-sm-2 col-form-label" id="email">{{$data->email}}</label>
         </div>
         <div class="mb-3 row">
            <label for="phone" class="col-sm-2 col-form-label">Phone</label>
            <label class="col-sm-2 col-form-label" id="phone">{{$data->phone}}</label>
         </div>
         <div class="mb-3 row">
            <label for="website" class="col-sm-2 col-form-label">Website</label>
            <label class="col-sm-2 col-form-label" id="website">{{$data->website}}</label>
         </div>
         <div class="mb-3 row">
            <label for="logo" class="col-sm-2 col-form-label">Logo</label>
            <div class="col-sm-10">
               <img src="{{$data->logoPath}}" id="logo" width="100">
            </div>
         </div>
         <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">PIC</label>
         </div>
         <div class="mb-3 row">
            <table id="pic" class="table table-stripped">
               <thead>
                  <tr>
                     <th class="text-center">Name</th>
                     <th class="text-center">Email</th>
                     <th class="text-center">Phone</th>
                     <th class="text-center">Avatar</th>
                  </tr>
               </thead>
               <tbody>
                  @foreach ($data->persons as $pic)
                     <tr>
                        <td>{{$pic->name}}</td>
                        <td>{{$pic->email}}</td>
                        <td>{{$pic->phone}}</td>
                        <td><img src="{{$pic->avatarPath}}" width="70"></td>
                     </tr>
                  @endforeach
               </tbody>
            </table>
         </div>
      </form>
   </div>
</div>
</div>
<script>
   function store(){
      $('#loading').modal('show')
      let baseUrl = $('#url').val()
      let formData = new FormData()
      formData.append('name', $('#name').val())
      formData.append('email', $('#email').val())
      formData.append('phone', $('#phone').val())
      formData.append('website', $('#website').val())
      formData.append('logo', $('#logo')[0].files[0])
      console.log(formData)
      $.ajax({
         url: baseUrl+"/create",
         type: "POST",
         processData: false,
         contentType: false,
         data: formData,
         dataType: 'json',
         success: (response) => {
            setTimeout(() => {
               $('#loading').modal('hide')
               if(response.status == 'S'){
                  $('#popup_title').text('Sukses')   
               } else {
                  $('#popup_title').text('Gagal')
               }
               $('#popup_body').text(response.message)
               $('#popup_info').modal('show')
               $('#form-org').trigger("reset");
            }, 300);
         },
         error: (xhr, txtStatus, errThrown) => {
            setTimeout(() => {
               $('#loading').modal('hide')
               $('#popup_title').text('Error')
               $('#popup_body').text('Gagal simpan data organization')
               $('#popup_info').modal('show')
            }, 300);
         }
      })
   }
</script>
@stop