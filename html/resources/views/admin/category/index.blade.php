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
                    {{--<table class="table table-bordered table-striped">
                        <tr>
                            <th scope="col">lev1</th>
                            <th scope="col">2</th>
                            <th scope="col">3</th>
                            <th scope="col">4</th>
                        </tr>
                        @foreach ($categories as $c)
                            <tr>
                                <td>{{ $c->lev1 }}</td>
                                <td>{{ $c->lev2.' [ '.$c->lev2_id.' ] ' }}</td>
                                <td>{{ $c->lev3.' [ '.$c->lev3_id.' ] ' }}</td>
                                <td>{{ $c->lev4.' [ '.$c->lev4_id.' ] ' }}</td>
                            </tr>                            
                        @endforeach
                    </table>--}}
                    <div>
                        <ul>
                        @for ($i = 0, $count = count($categories); $i < $count;)
                            <li>{{ $categories[$i]->lev2.' [ '.$categories[$i]->lev2_id.' ] ' }}
                                {{-- show lev3 elements --}}
                                @if (isset($categories[$i]->lev3))
                                    <ul>
                                    @for ($current_lev2=$categories[$i]->lev2_id; $i < $count && $categories[$i]->lev2_id == $current_lev2;)
                                        <li>{{ $categories[$i]->lev3.' [ '.$categories[$i]->lev3_id.' ] ' }}
                                            {{-- show lev4 elements --}}
                                            @if (isset($categories[$i]->lev4))
                                                <ul>
                                                @for ($current_lev3=$categories[$i]->lev3_id; $i < $count && $categories[$i]->lev3_id == $current_lev3; $i++)
                                                    <li>{{ $categories[$i]->lev4.' [ '.$categories[$i]->lev4_id.' ] ' }}</li>
                                                @endfor
                                                    <li>
                                                        @component('admin.category.addButton') {{ $current_lev3 }} @endcomponent
                                                    </li>
                                                </ul>
                                            {{-- add lev4 element --}}
                                            @else 
                                                <ul><li>
                                                    @component('admin.category.addButton') {{ $categories[$i++]->lev3_id }} @endcomponent
                                                </li></ul>
                                            @endif
                                        </li>
                                    @endfor
                                        <li>
                                            @component('admin.category.addButton') {{ $current_lev2 }} @endcomponent
                                        </li>
                                    </ul>
                                {{-- add lev3 element --}}
                                @else
                                    <ul><li>
                                        @component('admin.category.addButton') {{ $categories[$i++]->lev2_id }} @endcomponent
                                    </li></ul>
                                @endif
                            </li>
                        @endfor
                            {{-- add lev2 element --}}
                            <li>@component('admin.category.addButton') 0 @endcomponent</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')

<script type="text/javascript">
    function showForm(ele) {
        ele.nextElementSibling.style.display = 'inline';//show form
        ele.style.display = "none";//hide [add button]
        ele.nextElementSibling.firstElementChild.focus(); //text input
        ele.nextElementSibling.firstElementChild.onblur = ((function(btn, input, form) {
            if(input.value.trim() == '') {
                form.style.display = "none";
                btn.style.display = "inline";
            }
        }).bind(null, ele, ele.nextElementSibling.firstElementChild, ele.nextElementSibling));
    }
</script>

@endsection
