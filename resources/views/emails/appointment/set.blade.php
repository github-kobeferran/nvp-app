@component('mail::message')
Hello, {{ucfirst($clientName)}} and to your lovely {{ucfirst($petName)}}!

@component('mail::panel')
Appointment for {{ucfirst($petName)}}'s<br>
{{$services}}<br>
with the total fee of &#8369;{{$fee}}<br>
is set on {{$date}}, we are open 8am-5pm FCFS. See you!
@endcomponent

Thanks<br>
NVP ANIMAL CLINIC
@endcomponent
