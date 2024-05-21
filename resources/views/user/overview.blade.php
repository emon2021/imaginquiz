@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header"><h4>Quiz Overview</h4></div>
                    <div class="card-body">
                        <table id="table" class="table table-striped">
                            <thead>
                                <th>SL</th>
                                <th>Quiz Date</th>
                                <th>Action</th>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
 
<!----------Page specific scripts ---------->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>

    $(document).ready(function() {
        //  start ajax syntax with a function()
        $(function() {
            //  getting the original table and replace it with yajra DataTable({json data});
            table = $('#table').DataTable({
                //  default data for all columns
                columnDefs: [{
                    'defaultContent': '-',
                    'targets': '_all'
                }],
                //  it's showing the processing message
                "processing": true,
                //  it's working on serverside
                "serverSide": true,
                "searching": true,
                //  getting the route using ajax and declare request type
                "ajax": {
                    "url": "{{ route('user.overview') }}",
                    "type": 'GET',
                },
                //  push data to all the table columns
                columns: [
                    //  this first column is defined the auto increment too column
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex'
                    },
                    {
                        data: 'publish_time',
                        name: 'publish_time',
                    },
                    //  here added orderable and searchable property to make table orderable and searchable
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: true
                    },
                ],
                // dom:'Bfrtip',
                // buttons:['csv','pdf'],
            });
        });
        //  here end data pushing using yajra datatables
    });
</script>

<!---------- -/ End of Page specific scripts ---------->   
