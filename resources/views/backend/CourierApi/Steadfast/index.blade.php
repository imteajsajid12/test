@extends('backend.layouts.app')

@section('content')
{{--validations--}}

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <div class="aiz-titlebar text-left mt-2 mb-3">
        <div class="align-items-center">
            <h1 class="h3">{{translate('All Customers')}}</h1>
        </div>
    </div>




    <div class="row">
        <div class="col-lg-6 col-sm-6 col-12">
            <div class="card">
                <div class="card-body">

                    <div class="container">
{{--                        <h1>Current Balance</h1>--}}
{{--                        <div class="balance" id="current-balance">Current Balance: -50</div>--}}
                    </div>
                    <h3 for="" class="text-center">SteadFast Access</h3><br><br>
                    <form action="{{ route('steadfast.create') }}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row">

                            <div class="col-lg-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">API URL</label>
                                    <input type="text" name="api_url" class="form-control" value="{{$datas->api_url ?? "N/A"}}" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Url">
                                    <small id="emailHelp" class="form-text text-muted">We'll never share your key with anyone else.</small>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">API Key</label>
                                    <input type="text" class="form-control" value="{{$datas->api_key ?? "N/A"}}" name="api_key" id="exampleInputPassword1" placeholder="Key">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Secret Key</label>
                                    <input type="text" class="form-control"  value=" {{$datas->api_secret ?? "N/A"}}" name="api_secret" id="exampleInputPassword1" placeholder="secret">
                                </div>

                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection


@section('script')
    <script type="text/javascript">

        $(document).on("change", ".check-all", function () {
            if (this.checked) {
                // Iterate each checkbox
                $('.check-one:checkbox').each(function () {
                    this.checked = true;
                });
            } else {
                $('.check-one:checkbox').each(function () {
                    this.checked = false;
                });
            }

        });

        function sort_customers(el) {
            $('#sort_customers').submit();
        }

        function confirm_ban(url) {
            $('#confirm-ban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmation').setAttribute('href', url);
        }

        function confirm_unban(url) {
            $('#confirm-unban').modal('show', {backdrop: 'static'});
            document.getElementById('confirmationunban').setAttribute('href', url);
        }

        function bulk_delete() {
            var data = new FormData($('#sort_customers')[0]);
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{route('bulk-customer-delete')}}",
                type: 'POST',
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response == 1) {
                        location.reload();
                    }
                }
            });
        }
    </script>
@endsection
