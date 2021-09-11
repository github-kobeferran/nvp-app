@component('mail::message')
Hello, {{ucfirst($clientName)}} and to your lovely {{ucfirst($petName)}}!

@component('mail::panel')
Its been {{\App\Models\Setting::first()->weeks}} weeks since {{ucfirst($petName)}} last appointment at {{$last_appointment_at}}<br>
<br>
{{$petName}} will surely love a {{$services}} again!<br>
we are always open at 8am-5pm FCFS. See you!
@endcomponent

Thanks and a reminder from,<br>
NVP ANIMAL CLINIC
@endcomponent
