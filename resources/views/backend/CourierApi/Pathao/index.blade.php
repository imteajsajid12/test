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
                    <h2 for="" class="text-center">Pathao Access</h2><br><br>
                    <form action="{{ route('pathao.create') }}" method="POST"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-12 col-sm-12 col-12">
                                <div class="form-group">
                                    <label for="exampleInputEmail1">API Base URL</label>
                                    <input type="text" name="api_url" class="form-control" value="{{$data->api_url ?? "N/A"}}" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Url">
                                    <small id="emailHelp" class="form-text text-muted">We'll never share your key with anyone else.</small>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">API Access Token</label>
                                    <input type="text" class="form-control" value="{{$data->api_token ?? "N/A"}}" name="" id="exampleInputPassword1" placeholder="Token">
                                    <small id="emailHelp" class="form-text text-muted">We'll never share your tocken with anyone else.</small>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">API Secret</label>
                                    <input type="text" class="form-control" value="{{$data->api_secret ?? "N/A"}}" name="api_secret" id="exampleInputPassword1" placeholder="secret">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">API Key</label>
                                    <input type="text" class="form-control" value="{{$data->api_secret ?? "N/A"}}" name="api_key" id="exampleInputPassword1" placeholder="key">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">Api Email</label>
                                    <input type="email" class="form-control" value="{{$data->api_email ?? "N/A"}}" name="api_email" id="exampleInputemail1" placeholder="Api Email">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputPassword1">API Password</label>
                                    <input type="text" class="form-control" value="{{$data->api_password ?? "N/A"}}" name="api_password" id="exampleInputPassword1" placeholder="Api Password">
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
@endsection
