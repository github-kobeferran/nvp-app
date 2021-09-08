@component('mail::message')
Our dear employee, {{ucfirst($name)}} ! 

@component('mail::button', ['url' => url('/login')])
Login
@endcomponent

@component('mail::panel')
Welcome to NVP Animal Clinic! <br>
Use your email and password to login <br>
This is your password: {{$pass}}
@endcomponent

Thanks,<br>
NVP Animal Clinic
@endcomponent
