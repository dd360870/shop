@extends('admin.base')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-2">
            {{--@for ($i = 0, $count=count($categories); $i < $count;)
                @if (isset($categories[$i]->lev3))
                    <h4>{{ $categories[$i]->lev1 }}</h4>
                    <ul>
                    @for ($current_lev1=$categories[$i]->lev1_id; $i < $count && $categories[$i]->lev1_id==$current_lev1; )
                        <li>{{ $categories[$i]->lev2 }}
                            <ul>
                            @for ($current_lev2=$categories[$i]->lev2_id; $i < $count && $categories[$i]->lev2_id==$current_lev2;$i++)
                                <li><a href="{{ url()->current().'?category='.$categories[$i]->lev3_id }}" style="color:black;
                                    {{ $category==$categories[$i]->lev3_id ? 'font-weight:bold;' : NULL }}
                                ">{{ $categories[$i]->lev3 }}</a></li>
                            @endfor
                            </ul>
                        </li>
                    @endfor
                    </ul>
                @endif
            @endfor--}}
            @for ($i = 0, $count = count($categories); $i < $count;)
                <li>{{ $categories[$i]->lev1 }}
                    {{-- show lev2 elements --}}
                    @if (isset($categories[$i]->lev2))
                        <ul>
                        @for ($current_lev1=$categories[$i]->lev1_id; $i < $count && $categories[$i]->lev1_id == $current_lev1;)
                            <li>{{ $categories[$i]->lev2 }}
                                {{-- show lev3 elements --}}
                                @if (isset($categories[$i]->lev3))
                                    <ul>
                                    @for ($current_lev2=$categories[$i]->lev2_id; $i < $count && $categories[$i]->lev2_id == $current_lev2; $i++)
                                        <li>
                                            <a href="?category={{$categories[$i]->lev3_id}}"
                                                style="color:black; {{ $category==$categories[$i]->lev3_id ? 'font-weight:bold;' : NULL }}">
                                                {{ $categories[$i]->lev3 }}
                                            </a>
                                        </li>
                                    @endfor
                                    </ul>
                                {{-- add lev3 element --}}
                                @else 
                                    @php $i++ @endphp
                                @endif
                            </li>
                        @endfor
                        </ul>
                    {{-- add lev2 element --}}
                    @else
                        @php $i++ @endphp
                    @endif
                </li>
            @endfor
        </div>
        <div class="col-lg-10">
            @if(isset($alert) || $alert = Session::get('alert', false))
                @component('components.alert', ['type' => $alert['type']])
                    {{ $alert['message'] }}
                @endcomponent
            @endif
            <div>
                <div class="border-bottom border-left border-right p-3">
                    <div class="mb-3">
                        <a class="btn btn-primary" href="/admin/merchandise/new" role="button" style="">+ New merchandise</a>
                    </div>
                    @if ( $merchandises->isEmpty() )
                        @lang('No Merchandise')
                    @else
                    <div class="card-deck" id="first-deck">
                        @foreach ($merchandises as $m)
                        <div class="card">
                            <img class="card-img-top" src="{{ $m->photo ? Storage::disk('s3')->url($m->photo) : secure_asset('default-merchandise.jpg')}}"
                                alt="">
                            <div class="card-body">
                                <h5 class="card-title"><a href="{{ secure_url('/admin/merchandise/'.$m->id) }}">{{
                                        $m->name }}</a></h5>
                                <p class="card-text font-weight-bold">
                                    Price: {{ $m->price }}<br>
                                    Amount: {{ $m->amount }}<br>
                                    Status: <span style="color:{{ $m->status=='C' ? 'tomato' : 'olive'}};">{{
                                        $m->status=='C' ? 'Off' : 'Selling' }}</span><br>
                                    Category: {{ $m->category->full_name.'['.$m->category->id.']' }}<br>
                                    ID: {{ sprintf('%04d', $m->id) }}
                                </p>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted" style="">
                                    created:{{ $m->created_at }}<br>
                                    updated:{{ $m->updated_at }}
                                </small>
                                <div style="width:100%">
                                    <a class="btn btn-primary" role="button" href="/admin/merchandise/{{ $m->id }}/edit"
                                        style="float:right;">Edit</a>
                                    <form method="POST" style="display:inline; float:right;" action="/admin/merchandise/{{ $m->id }}"
                                        onsubmit="return confirm('Are you sure to delete this item?');">
                                        @csrf
                                        @method('delete')
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
    $(document).ready(function () {
        let width = 3;
        let elements = $('#first-deck').children();
        let size = elements.length;

        for (let i = (size<width ? 0 : width); i < size; i += width) {
            console.log(i);

            let string = '<div class="card-deck">';
            for (var j = 0; j < width && i + j < size; j++) {
                string += elements.get(i + j).outerHTML;
                elements.get(i + j).remove();
            }
            for (let k = 0; k < width - j; k++) {
                string += '<div class="card" style="visibility: hidden;"></div>';
            }
            string += '</div>';
            $('.card-deck:last').after(string);
        }
    });

</script>

@endsection
