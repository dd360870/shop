@extends('admin.base')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            @if(isset($alert) || $alert = Session::get('alert', false))
                @component('components.alert', ['type' => $alert['type']])
                    {{ $alert['message'] }}
                @endcomponent
            @endif
            <div>
                <div class="border-bottom border-left border-right p-3">
                    <div class="mb-3">
                        <form class="form-inline" style="float:right;" method="GET">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <select class="input-group-text" name="column_name">
                                        <option value="email" @if(app('request')->input('column_name')=='email') selected @endif>Email</option>
                                        <option value="id" @if(app('request')->input('column_name')=='id') selected @endif>ID</option>
                                        <option value="name" @if(app('request')->input('column_name')=='name') selected @endif>Name</option>
                                    </select>
                                </div>
                                <input type="text" class="form-control" name="input" value="{{ app('request')->input('input') }}">
                                <div class="input-group-append">
                                    <button class="btn btn-secondary" type="submit">Search</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <table class="table table-striped table-bordered">
                        
                        <thead>
                            <tr>
                                <th scope="col">id</th>
                                <th scope="col">Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Admin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $u)
                                <tr>
                                    <th scope="row">{{ $u->id }}</th>
                                    <td>{{ $u->name }}</td>
                                    <td>{{ $u->email }}</td>
                                    <td style="max-width:60px">
                                        @if ($u->is_admin)
                                            <span style="color:red; font-weight:bold;">Admin</span>
                                            <form style="display:inline;" method="POST">
                                                @csrf
                                                <input name="method" value="remove" style="display:none;">
                                                <input name="email" value="{{ $u->email }}" style="display:none;">
                                                <button type="submit" class="btn btn-warning">Remove</button>
                                            </form>
                                        @else
                                            <form class="form-inline" method="POST">
                                                @csrf
                                                <input name="method" value="add" style="display:none;">
                                                <input name="email" value="{{ $u->email }}" style="display:none;">
                                                <button type="submit" class="btn btn-success">Add</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

<script type="text/javascript">
    
</script>

@endsection
