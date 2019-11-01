<div>
    <h1>{{ $title }}</h1>

    @foreach($items() as $item)
        <li>{{ $item }}</li>
    @endforeach
</div>
