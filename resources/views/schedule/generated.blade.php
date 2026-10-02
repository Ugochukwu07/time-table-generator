<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $session->name }} {{ ucfirst($scheduler->semester) }} Semester Examination Timetable</title>
    <style>
        *{
            box-sizing: border-box;
            margin: 0;
        }
        body{
            padding: 10px 50px;
        }
        table, th, td {
            border: 1px solid;
        }
        table{
            width: 100%;
            border-collapse: collapse;
        }
        th{
            padding: 10px;
            font-weight: bold;
        }
        td{
            padding: 7px;
        }
        .title{
            text-align: center;
        }
        .title h1{
            text-decoration: underline;
            font-size: 17px
        }
    </style>
</head>
<body>
    <div class="title">
        <h1>NNAMDI AZIKIWE UNIVERSITY, AWKA<br />COMPUTER BASED TEST EXAM COLLATED<br />{{ $session->name }} {{ ucfirst($scheduler->semester) }} Semester Examination Timetable (Regular)</h1>
    </div>
    <table>
        <thead>
            <th>Date</th>
            <th>Time</th>
            <th>Course Code</th>
            <th>Expected Number</th>
            <th>Venue</th>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr>
                    <td>{{ $row['date'] }}</td>
                    <td>{{ $row['time'] }}</td>
                    <td>{{ $row['course'] }}</td>
                    <td>{{ $row['students'] }}</td>
                    <td>{{ $row['venue'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No courses have been uploaded for this session yet.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
