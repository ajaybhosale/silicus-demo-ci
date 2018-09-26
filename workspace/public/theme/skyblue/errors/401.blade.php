@extends($theme.'.layouts.app')

@section('content')
<section id="contact-info">
    <div class="center">
        <span class="has-error"><h2 class="help-block" >HTTP Error 401 - Unauthorized</h2></span>
        <p class="lead">
            The 401 Unauthorized error is an HTTP status code that means the page you were trying to access cannot be loaded until you first log in with a valid user ID and password.<br/>
            If you have just logged in and received the 401 Unauthorized error, it means that the credentials you entered were invalid for some reason.</p>
    </div>
</section>

@endsection