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
                    <h1>Under construction.</h1>
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
