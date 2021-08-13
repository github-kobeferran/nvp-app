@component('mail::message')
Hello, {{ucfirst($name)}} !

@component('mail::button', ['url' => url('/login')])
Login
@endcomponent

@component('mail::panel')
Welcome to NVP Animal Clinic! :) <br>
This is your password: {{$pass}}
@endcomponent

Thanks,<br>
NVP Animal Clinic
@endcomponent
