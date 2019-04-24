@component('mail::message')

# Hi, You are invited to join our team!
## Just click on the button below and follow the steps to start working on the project: {{ $invitation->project_name }}!


@component('mail::button', ['url' => '{{ env('APP.URL') }}/accept/'.$invitation->token , 'color' => 'success'])
    Accept invitation
@endcomponent

@endcomponent
