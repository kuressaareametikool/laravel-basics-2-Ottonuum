<!DOCTYPE html>
<html>
<head>
    <title>Timetable</title>
</head>
<body>
    <h1>Timetable for the week of {{ $startDate->format('Y-m-d') }} to {{ $endDate->format('Y-m-d') }}</h1>

    @foreach ($timetableEvents as $day => $events)
        <h2>{{ ucfirst($day) }}</h2>
        
        @foreach ($events as $event)
                
        <strong>{{ $event['nameRu'] ?? 'No subject' }}</strong>: 
        {{ $event['timeStart'] }} - {{ $event['timeEnd'] }} <br>
                
        @endforeach
       
    @endforeach
</body>
</html>
