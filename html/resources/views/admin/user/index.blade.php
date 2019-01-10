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
                                    <td>
                                        @if ($u->admin)
                                            <span style="color:red; font-weight:bold;">Admin</span>
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
