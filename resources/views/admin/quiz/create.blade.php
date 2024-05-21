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
                                <div class="col-md-12 mt-1">
                                    <label for="">Expired Time <font color="red">*</font></label>
                                    <input type="number" name="expired_time" placeholder="Quiz expired time in hour"   class="form-control">
                                    <small>Example: <font color="red">Quiz expired after 30 hours from published time!</font></small>
                                </div>
                                <div class="col-md-12 mt-3 d-flex justify-content-center">
                                    <button class="btn btn-dark float-center form-control m-auto"  type="submit">Submit</button>
                                </div>
                                
                                <div id="errors" class="alert alert-success d-none">
                                    
                                </div>
                                <div id="error" class="alert alert-danger d-none">
                                    
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
    $(document).ready(function() {
        $('body').on('submit', '#addQuiz', function(e) {
            e.preventDefault();
            let get_route = $(this).attr('action');
            let formData = new FormData($(this)[0]);

            // Disable the submit button to prevent multiple submissions
            $(this).find('button[type="submit"]').prop('disabled', true);

            // Clear previous error messages
            $('.error-message').text('').removeClass('d-block');

            $.ajax({
                url: get_route,
                type: 'post',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#errors').removeClass('d-none');
                    $('#errors').text(response).addClass('d-block');

                    $('#addQuiz')[0].reset();

                    // Re-enable the submit button
                    $('#addQuiz').find('button[type="submit"]').prop('disabled', false);
                },
                error: function(xhr, status, failed) {
                    let errors = xhr.responseJSON.errors;
                    
                    
                        $.each(errors, function(key, value) {
                            
                            $('#error').removeClass('d-none');
                            $('#error').text(value[0]).addClass('d-block');
                        });

                    // enable the submit button
                    $('#addQuiz').find('button[type="submit"]').prop('disabled', false);
                }
            });
        });
    });
</script>

@endpush