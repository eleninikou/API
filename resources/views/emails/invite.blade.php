@component('mail::message')
# You have been invited to join the project: {{ $invitation->project_name }}


@component('mail::button', ['url' => '/accept/'.$invitation->token , 'color' => 'success'])
Join Team
@endcomponent

@endcomponent
