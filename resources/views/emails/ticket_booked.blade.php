<!DOCTYPE html>
<html>
<head><meta charset="UTF-8"><title>Your Ticket</title></head>
<body style="font-family:Arial,sans-serif;background:#f5f7fb;padding:20px;color:#222;">
<div style="max-width:600px;margin:auto;background:white;padding:30px;border-radius:10px;">
<h2 style="color:#0aa;">🎉 Your GateKeeper Ticket is Confirmed!</h2>
<p>Hi {{ $ticket->user->name }},</p>
<p>Your payment for <strong>{{ $ticket->event->title }}</strong> has been received. Your ticket details:</p>

<table style="width:100%;border-collapse:collapse;margin:15px 0;">
<tr><td style="padding:6px;color:#666;">Event</td><td style="padding:6px;font-weight:bold;">{{ $ticket->event->title }}</td></tr>
<tr><td style="padding:6px;color:#666;">Date</td><td style="padding:6px;">{{ $ticket->event->date->format('d M Y · H:i') }}</td></tr>
<tr><td style="padding:6px;color:#666;">Location</td><td style="padding:6px;">{{ $ticket->event->location }}</td></tr>
<tr><td style="padding:6px;color:#666;">Category</td><td style="padding:6px;">{{ $ticket->category->name }}</td></tr>
<tr><td style="padding:6px;color:#666;">Ticket Code</td><td style="padding:6px;font-family:monospace;background:#f0f0f0;">{{ $ticket->unique_code }}</td></tr>
</table>

<div style="text-align:center;margin:20px 0;">
<img src="data:image/png;base64,{{ $qrBase64 }}" alt="QR" style="width:180px;height:180px;">
<div style="font-size:12px;color:#666;">Show this QR at the gate.</div>
</div>

<p style="font-size:12px;color:#888;">Need help? Reply to this email.</p>
<p style="font-size:12px;color:#888;">© {{ date('Y') }} GateKeeper Bangladesh.</p>
</div>
</body>
</html>
