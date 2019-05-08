@component('mail::message')

# You were just invited to join our team!
## Click on the link below and follow the steps to start working with us on the project: 
# {{ $invitation->project_name }}!


@component('mail::button', ['url' => 'https://itsm-frontend.herokuapp.com/accept/'.$invitation->token , 'color' => 'success'])
    Join {{ $invitation->project_name }}
@endcomponent


@endcomponent
