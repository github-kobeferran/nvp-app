@component('mail::message')
Hello, {{ucfirst($clientName)}} and to your lovely {{ucfirst($petName)}}!

@component('mail::panel')
Appointment for {{ucfirst($petName)}}'s<br>
<br>
{{$services}}<br>
with the total fee of &#8369;{{$fee}}<br>
and the previous appointment date of {{$olddate}} <br>
<br>
is rescheduled to {{$newdate}} <br>
FCFS. See you!
@endcomponent

Thanks<br>
NVP ANIMAL CLINIC
@endcomponent

