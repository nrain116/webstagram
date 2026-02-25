@component('mail::message')
# Neuer Follower Alarm

Du hast einen neuen Follower: **{{ $followerName }}** ({{ $followerEmail }})

@component('mail::button', ['url' => route('profile.show', $followerName)])
Profil ansehen
@endcomponent

Danke,<br>
{{ config('app.name') }}
@endcomponent