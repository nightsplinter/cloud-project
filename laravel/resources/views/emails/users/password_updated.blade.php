<x-mail::message>
Hello {{$name}},
{{ PHP_EOL }}
We wanted to let you know that your password has been successfully changed. If you made this change, no further action is required.
{{ PHP_EOL }}
If you did not request this change, please contact us immediately at {{ config('mail.from.address') }}.
{{ PHP_EOL }}
Thank you for keeping your account secure.
{{ PHP_EOL }}
Best regards,
{{ config('app.name') }}
</x-mail::message>
