<x-mail::message>
# Hello {{ $userName }},

We hope you are doing well.

You have requested a One-Time Password (OTP) for verification. Please use the code below to proceed:

## Your OTP Code: **{{ $otp }}**

For security reasons, this OTP is valid for a limited time and should not be shared with anyone.  
If you did not request this code, please ignore this email or contact our support team immediately.

Thank you for choosing {{ config('app.name') }}.  
We appreciate your trust and are always here to assist you.

Best regards,  
The {{ config('app.name') }} Team
</x-mail::message>

{{-- <x-mail::button :url="''"> --}}
{{-- Button Text --}}



{{-- </x-mail::button> --}}