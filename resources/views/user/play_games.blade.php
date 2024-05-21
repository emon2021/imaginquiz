@extends('layouts.app')
@section('content')
    @push('css')
        <style type="text/css">
            ul.pagination {
                float: right !important;
            }

            .dataTables_filter {
                float: right;
            }
        </style>
    @endpush
    <div class="container">
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                @foreach ($quiz as $game)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h4>Quiz Application</h4>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-6 float-start" id="image1">
                                    <img width="100%" height="100%" src="{{ asset($game->image1) }}" alt="">
                                </div>
                                <div class="col-md-6 float-end" id="image2">
                                    <img width="100%" height="100%" src="{{ asset($game->image2) }}" alt="">
                                </div>

                                <div class="mt-3">
                                    <hr>
                                </div>
                                <form action="{{route('quiz.answer')}}" method="POST" class="quizAnswer">
                                        @csrf
                                    <div class="col-md-12 mt-1">
                                        <label for="">
                                            <b>
                                                Submit Your Answer <font color="red">(Max 5 attempts)</font>
                                            </b>
                                        </label>
                                        <input type="text" name="fun_name" placeholder="Fun username"
                                            class="form-control">

                                    </div>
                                    <input type="hidden" value="{{$game->id}}" name="quiz_id">
                                    <div class="input_group">

                                        <div class="col-md-12 mt-1 input-group">

                                            <input type="text" name="single_word[]" placeholder="Write A Word"
                                                class="form-control">
                                            <div class="input-group-append ">
                                                <div class="input-group-text bg-dark plus_button" style="cursor: pointer"
                                                    >
                                                    <span class="bg-dark text-light fs-4">+</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mt-3 d-flex justify-content-center">
                                        <button class="btn btn-dark submitBtn float-center form-control m-auto"
                                            type="submit">Submit</button>
                                    </div>
                                </form>
                                <div class="alert alert-success d-none" id="success"></div>
                                <div class="alert alert-danger d-none" id="attempt"></div>
                                <div class="alert alert-danger d-none" id="limit"></div>
                                
                            </div>


                        </div>
                    </div>
                @endforeach
            </div>
            <div class="col-md-2"></div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.plus_button').click(function() {
                let input =
                    '<div class="col-md-12 mt-1 input-group"><input type="text" name="single_word[]" placeholder="Write A Word"   class="form-control"><div class="input-group-append "><div class="input-group-text bg-danger minus_button"  style="cursor:pointer;"><span class="text-light fs-3">-</span></div></div> </div>';
                $(this).closest('.input_group').append(input);
            });

            //  delete input group
            $('.input_group').on('click', '.minus_button', function() {
                $(this).closest('.input-group').remove();
            });

            //  quiz answer form submission

            $('body').on('click','.submitBtn',function(){
                $('.quizAnswer').submit();
            });

            $('.quizAnswer').on('submit', function(e) {
                e.preventDefault();
                let url = $(this).attr('action');
                let data = new FormData($(this)[0]);
                $.ajax({
                    type: 'post',
                    url: url,
                    data: data,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if(response.success)
                        {
                            $('#success').removeClass('d-none');
                            $('#success').text(response.success).addClass('d-block');
                        }
                        if(response.attempt_error)
                        {
                            $('#attempt').removeClass('d-none');
                            $('#attempt').text(response.attempt_error).addClass('d-block');
                        }
                        if(response.limit_error)
                        {
                            $('#limit').removeClass('d-none');
                            $('#limit').text(response.limit_error).addClass('d-block');
                        }
                                          $(this)[0].reset();
                    },
                });
            });

            

        });
    </script>
@endpush
