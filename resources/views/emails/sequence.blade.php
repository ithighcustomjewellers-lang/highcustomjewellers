<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $sequence->subject }}</title>
    <style>
        /* Basic reset for email clients */
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, Helvetica, sans-serif;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
        }
        .content {
            padding: 20px;
        }
        .hero-image {
            width: 100%;
            max-width: 100%;
            height: auto;
            display: block;
        }
        .attachment {
            background: #f0f0f0;
            padding: 12px;
            border-radius: 6px;
            margin: 20px 0;
            text-align: center;
        }
        .social-buttons {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .btn {
            display: inline-block;
            padding: 10px 18px;
            margin: 5px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }
        .btn-whatsapp { background: #25D366; color: white; }
        .btn-telegram { background: #0088cc; color: white; }
        .btn-business { background: #007bff; color: white; }
    </style>
</head>
<body>
    <div class="container">
        {{-- Hero Image (if exists) --}}
            @if($sequence->hero_image && file_exists(public_path($sequence->hero_image)))
                <img src="{{ asset($sequence->hero_image) }}" class="hero-image" alt="Hero Image">
            @endif

        <div class="content">
            {{-- Hello message --}}
            <h3>Hello {{ $contact->name ?? 'there' }}</h3>
            <hr>

            {{-- CUSTOM HTML MESSAGE (with fonts, colors, etc.) --}}
            {!! $finalMessage !!}

            {{-- Attachment download link (if any) --}}
           @if($sequence->attachments_image && file_exists(public_path($sequence->attachments_image)))
                <div class="attachment">
                    📎 <strong>{{ $sequence->attachment_name ?? 'Attachment' }}</strong>
                    ({{ round(($sequence->attachment_size ?? 0) / 1024) }} KB)
                    <br>
                    <a href="{{ asset($sequence->attachments_image) }}" download>⬇️ Download</a>
                </div>
            @endif
        </div>

        {{-- Social buttons --}}
        <div class="social-buttons">
            @if($sequence->whatsapp_link)
                <a href="{{ $sequence->whatsapp_link }}" class="btn btn-whatsapp" target="_blank">📱 WhatsApp</a>
            @endif
            @if($sequence->telegram_link)
                <a href="{{ $sequence->telegram_link }}" class="btn btn-telegram" target="_blank">📨 Telegram</a>
            @endif
            @if($sequence->business_link)
                <a href="{{ $sequence->business_link }}" class="btn btn-business" target="_blank">💼 Business</a>
            @endif
        </div>
    </div>
</body>
</html>
