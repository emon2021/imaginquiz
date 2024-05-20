@extends('layouts.app')
@section('content')
@push('css')
<style type="text/css">
    ul.pagination{float: right !important;}
    
    .dataTables_filter{float: right;}
</style>
@endpush
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><h4>Quiz Application</h4></div>
                    <div class="card-body">
                        <form action="{{route('admin.quiz.store')}}" id="addQuiz" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 float-start">
                                    <input type="file" name="image1"  data-max-file-size="3M"  class="dropify">
                                </div>
                                <div class="col-md-6 float-end">
                                    <input type="file" name="image2"  data-max-file-size="3M" class="dropify">
                                </div>
                                <div class="col-md-12 mt-3 d-flex justify-content-center">
                                    <button class="btn btn-dark float-center form-control m-auto" style="width:20rem" type="submit">Submit</button>
                                </div>
                            </div>
                            
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-md-2"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        $(document).ready(function(){
            $('body').on('submit','#addQuiz',function(e){
                e.preventDefault();
                let get_route = $(this).attr('action');
                let formData = new FormData($(this)[0]);
                $.ajax({
                    url: get_route,
                    type: 'post',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response){
                        toastr.success(response);
                        $('#addQuiz')[0].reset();
                    },
                    error:function(xhr,status,error){
                        let errors = xhr.responseJSON.errors
                        $.each(errors,function(key,value)
                        {
                            toastr.error(value[0]);
                        });
                    }
                });
            });
        });
    </script>
@endpush