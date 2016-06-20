@unless($errors->isEmpty())
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <ul>
            @foreach($errors->all() as $item)
                <li>{{$item}}</li>
            @endforeach
        </ul>
    </div>
@endunless