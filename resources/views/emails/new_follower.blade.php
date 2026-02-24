@component('mail::message')
# New Follower Alert

You have a new follower: **{{ $followerName }}** ({{ $followerEmail }})

@component('mail::button', ['url' => route('profile.show', $followerName)])
View Profile
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent