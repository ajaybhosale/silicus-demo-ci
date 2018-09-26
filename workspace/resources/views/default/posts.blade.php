@extends($theme.'.layouts.app')

@section('content')
<div class="container">
    <form action="es" method="post">
        <input type="hidden" name="_token" value="{{{ csrf_token() }}}" />
        <br>
        <input type="text" id="query" name="query">
        <input type="submit">
    </form>

    @foreach($posts as $post)
    <div>
        <h2>{{{ $post->title }}}</h2>
        <div>{{{ $post->content }}}</div>
        <div><small>{{{ $post->tags }}}</small></div>
    </div>
    @endforeach
</div>
@endsection
