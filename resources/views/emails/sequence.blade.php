<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ $subjectLine ?? $sequence->subject }}</title>
</head>

<body style="margin:0;padding:0;background:#f4f4f4;font-family:Arial,Helvetica,sans-serif;">


    {{-- OPEN TRACKING PIXEL --}}
    @if(isset($campaignLog))
    <img
        src="{{ route('track-open', [
            'logId' => $campaignLog->id,
            'token' => $campaignLog->tracking_token
        ]) }}"
        width="1"
        height="1"
        style="display:block;width:1px;height:1px;border:0;"
        alt=""
    >
    @endif
@php
    $yesUrl = '#';
    $noUrl = '#';

    if(isset($campaignLog) && $campaignLog){

        $yesUrl = route('lead-response', [
            'log' => $campaignLog->id,
            'status' => 'interested'
        ]);

        $noUrl = route('lead-response', [
            'log' => $campaignLog->id,
            'status' => 'not_interested'
        ]);
    }
@endphp
<table width="100%" cellpadding="0" cellspacing="0" border="0"
    style="background:#f4f4f4;padding:20px 0;">

    <tr>
        <td align="center">

            <table width="600" cellpadding="0" cellspacing="0" border="0"
                style="background:#ffffff;border-radius:8px;overflow:hidden;">

                {{-- ========================================= --}}
                {{-- COMPANY LOGO --}}
                {{-- ========================================= --}}

                 @if(!empty($sequence->existing_company_logo))

                    <tr>
                        <td align="{{ $sequence->logo_position ?? 'center' }}"
                            style="padding:20px;">
                            <img
                                src="{{ url($sequence->existing_company_logo) }}"
                                alt="Company Logo"
                                style="
                                    @if(($sequence->image_type ?? '') === 'logo')
                                        max-width:140px;
                                        border-radius:12px;
                                    @else
                                        width:100%;
                                        border-radius:16px;
                                        margin-bottom:20px;
                                    @endif

                                    height:auto;
                                    display:block;
                                "
                            >
                        </td>
                    </tr>
                @endif
                {{-- ========================================= --}}
                {{-- HERO IMAGE --}}
                {{-- ========================================= --}}

                @if (!empty($sequence->hero_image))
                    <tr>
                        <td>

                            <img src="{{ url($sequence->hero_image) }}"
                                alt="Hero Image"
                                width="100%"
                                style="width:100%;display:block;">

                        </td>
                    </tr>
                @endif
                {{-- ========================================= --}}
                {{-- CONTENT --}}
                {{-- ========================================= --}}

                <tr>
                    <td style="padding:20px;">
                        <div style="font-size:15px;line-height:1.8;color:#333;">
                            {!! $finalMessage !!}
                        </div>
                    </td>
                </tr>

                {{-- ========================================= --}}
                {{-- SOCIAL BUTTONS --}}
                {{-- ========================================= --}}
                <tr>
                    <td align="center"
                        style="padding:20px;">

                        @if ($sequence->whatsapp_link)
                            <a href="{{ $sequence->whatsapp_link }}"
                                target="_blank"
                                style="
                                    background:#25D366;
                                    color:#ffffff;
                                    padding:12px 18px;
                                    text-decoration:none;
                                    border-radius:5px;
                                    display:inline-block;
                                    margin:5px;
                                ">

                                📱 WhatsApp

                            </a>
                        @endif


                        @if ($sequence->telegram_link)
                            <a href="{{ $sequence->telegram_link }}"
                                target="_blank"
                                style="
                                    background:#0088cc;
                                    color:#ffffff;
                                    padding:12px 18px;
                                    text-decoration:none;
                                    border-radius:5px;
                                    display:inline-block;
                                    margin:5px;
                                ">

                                📨 Telegram

                            </a>
                        @endif


                        @if ($sequence->business_link)
                            <a href="{{ $sequence->business_link }}"
                                target="_blank"
                                style="
                                    background:#007bff;
                                    color:#ffffff;
                                    padding:12px 18px;
                                    text-decoration:none;
                                    border-radius:5px;
                                    display:inline-block;
                                    margin:5px;
                                ">

                                💼 Business

                            </a>
                        @endif

                    </td>
                </tr>


                {{-- ========================================= --}}
                {{-- INTEREST BUTTONS --}}
                {{-- ========================================= --}}
                @if(isset($campaignLog) && $campaignLog)
                    <tr>
                        <td align="center" style="padding:10px 20px 30px 20px;">
                            <table cellpadding="0" cellspacing="0" border="0">
                                <tr>

                                    <td align="center" style="padding:5px;">
                                        <a href="{{ $yesUrl }}"
                                            target="_blank"
                                            style="
                                                color:#22c55e;
                                                padding:4px 5px;
                                                text-decoration:none;
                                                border-radius:6px;
                                                display:inline-block;
                                                font-weight:bold;
                                            ">

                                            YES, INTERESTED
                                        </a>
                                    </td>

                                    <td align="center" style="padding:5px;">
                                        <a href="{{ $noUrl }}"
                                            target="_blank"
                                            style="
                                                color:#ef4444;
                                                padding:4px 5px;
                                                text-decoration:none;
                                                border-radius:6px;
                                                display:inline-block;
                                                font-weight:bold;
                                            ">
                                            NOT INTERESTED
                                        </a>

                                    </td>

                                </tr>
                            </table>

                        </td>
                    </tr>

                @endif


                {{-- ========================================= --}}
                {{-- FOOTER --}}
                {{-- ========================================= --}}
                <tr>
                    <td align="center"
                        style="
                            padding:20px;
                            background:#fafafa;
                            font-size:12px;
                            color:#777;
                        ">

                        © {{ date('Y') }}
                        {{ config('app.name') }}
                        All rights reserved.

                    </td>
                </tr>

            </table>

        </td>
    </tr>

</table>

</body>
</html>
