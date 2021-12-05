@extends('layouts.app')
@section('title', 'User')
@section('content')
   <div class="row mt-5 mb-5">
      <div class="col-10">
      <h4>User List</h4>
      <input type="hidden" id="url" value="{{url('users')}}"/>
      </div>
      @can('create user')
      <div class="col">
         <a href="{{url('users/create')}}"><button type="button" class="btn btn-primary">
            Create
         </button></a>
      </div>
      @endcan
   </div>
   <div class="row mt-1">
      <table id="table-user" class="table">
         <thead>
            <tr>
               <th class="text-center">Name</th>
               <th class="text-center">Email</th>
               <th class="text-center">Action</th>
            </tr>
         </thead>
         <tbody>
         </tbody>
      </table>
      
   </div>
   <div class="modal fade" id="modal_hapus" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-md">
        <div class="modal-content">
          <div class="modal-header">
              <h5 class="modal-title">Hapus Data</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
          <div class="modal-body">
              <p>Apa anda yakin ingin menghapus data ini?</p>
          </div>
          <div class="modal-footer">
            <button type="button" onclick="konfirmHapus()" class="btn btn-primary">Ya</button>
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
          </div>        
        </div>
      </div>
    </div>
   <script>
      $( document ).ready(function() {
         let baseUrl = $('#url').val();
         var dtOrg = $('#table-user').DataTable({
            //scrollX: true,
            bFilter: true,
            serverSide: true,
            processing: true,
            ordering: false,
            searching: true,
            ajax: {
                url: baseUrl+"/getdt",
            },
            columns: [
                { data: 'name', defaultContent: '-' },
                { data: 'email', defaultContent: '-'},
                {
                    className: 'dt-body-center text-nowrap',
                    sortable: false,
                    render: function (data, type, full) {
                        var button = '';
                            button += '<button' +
                                ' data-toggle="tooltip" data-placement="top" title="Edit" ' +
                                ' class="btn btn-sm btn-warning" onclick="edit('+full.id+')"> ' +
                            ' Edit </button> ';

                            button += '<button' +
                                ' data-toggle="tooltip" data-placement="top" title="Hapus" ' +
                                ' class="btn btn-sm btn-danger" onclick="hapus('+full.id+')"> ' +
                            ' Hapus </button> ';

                        return button;
                    }
                },
            ],
            initComplete: function () {
            },
            drawCallback: function () {
            }
        });
      });
      
      function edit(id){
         let baseUrl = $('#url').val();
         window.location.href = baseUrl+"/edit/"+id
      }
      let hapusId = null
      function hapus(id){
         hapusId = id
         $('#modal_hapus').modal('show')
      }
      function konfirmHapus(){
         $('#modal_hapus').modal('hide')
         let baseUrl = $('#url').val();
         $('#loading').modal('show')
         $.ajax({
            url: baseUrl+"/delete/"+hapusId,
            type: "DELETE",
            processData: false,
            contentType: false,
            dataType: 'json',
            success: (response) => {
               setTimeout(() => {
                  $('#loading').modal('hide')
                  if(response.status == 'S'){
                     $('#popup_title').text('Sukses')   
                     location.reload()
                  } else {
                     $('#popup_title').text('Gagal')
                  }
                  $('#popup_body').text(response.message)
                  $('#popup_info').modal('show')
                  
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