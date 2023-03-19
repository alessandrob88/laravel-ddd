<x-mail::message>
# {{$job}} Error

Job failed with message {{$errorMessage}}
Job payload {{$payload}}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
