@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            @if(isset($alert) || $alert = Session::get('alert', false))
                @component('components.alert', ['type' => $alert['type']])
                {{ $alert['message'] }}
                @endcomponent
            @endif
            <div class="card">
                <div class="card-header">
                    <a href="/admin">Admin</a>
                    >
                    <a href="#">Merchandise</a>

                    <a class="btn btn-primary" href="/admin/merchandise/new" role="button" style="float:right;">+ New merchandise</a>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if ($merchandises->isEmpty())
                        @lang('No Merchandise')
                    @else
                        <div class="card-deck" id="first-deck">
                        @foreach ($merchandises as $m)
                            <div class="card">
                                <img class="card-img-top"
                                    src="{{ $m->photo ? secure_asset('storage/'.$m->photo) : secure_asset('default-merchandise.jpg')}}"
                                    alt="">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $m->name }}</h5>
                                    <p class="card-text">
                                        Price: {{ $m->price }}<br>
                                        Amount: {{ $m->amount }}<br>
                                        Status: {{ $m->status=='C' ? 'OFF' : 'Selling' }}<br>
                                        Category: {{ $m->category }}<br>
                                        id: {{ $m->id }}
                                    </p>
                                </div>
                                <div class="card-footer">
                                    <small class="text-muted" style="">
                                        created:{{ $m->created_at }}<br>
                                        updated:{{ $m->updated_at }}
                                    </small>
                                    <div style="width:100%">
                                        <a class="btn btn-primary" role="button" href="/admin/merchandise/{{ $m->id }}/edit" style="float:right;">Edit</a>
                                        <form method="POST"
                                            style="display:inline; float:right;"
                                            action="/admin/merchandise/{{ $m->id }}"
                                            onsubmit="return confirm('Are you sure to delete this item?');">
                                            {{ csrf_field() }}
                                            {{ method_field('DELETE') }}
                                            <button type="submit" class="btn btn-warning">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

<script type="text/javascript">

    $(document).ready(function() {
        let width = 3;
        let elements = $('#first-deck').children();
        let size = elements.length;
        
        for(let i=width ; i < size; i+=width) {
            console.log(i);

            let string = '<div class="card-deck">';
            for(var j=0; j<width && i+j<size; j++) {    
                string += elements.get(i+j).outerHTML;
                elements.get(i+j).remove();
            }
            for(let k=0; k<width-j; k++) {
                string += '<div class="card" style="visibility: hidden;"></div>';
            }
            string += '</div>';
            $('.card-deck:last').after(string);
        }
    });

</script>
    
@endsection
