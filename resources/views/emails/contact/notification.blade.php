<x-mail::message>
# Nieuw Contactbericht Ontvangen

Er is een nieuw bericht binnengekomen via het contactformulier op de website.

**Van:** {{ $contactMessage->name }} ({{ $contactMessage->email }})
**Onderwerp:** {{ $contactMessage->subject ?: 'Geen onderwerp opgegeven' }}
**Ontvangen op:** {{ $contactMessage->created_at->format('d-m-Y H:i:s') }}

**Bericht:**
<x-mail::panel>
{{ nl2br(e($contactMessage->message)) }}
</x-mail::panel>

U kunt dit bericht bekijken en beheren in het admin panel:
<x-mail::button :url="route('admin.contact-messages.show', $contactMessage)">
Bekijk Bericht in Admin
</x-mail::button>

Bedankt,
{{ config('app.name') }}
</x-mail::message>
