<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ $subjectLine ?? $sequence->subject }}</title>
</head>

<body style="margin:0;padding:0;background:#f4f4f4;font-family:Arial,Helvetica,sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f4f4f4;padding:20px 0;">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0" border="0"
                    style="background:#ffffff;border-radius:8px;overflow:hidden;">

                    {{-- ========================================= --}}
                    {{-- ✅ COMPANY LOGO --}}
                    {{-- ========================================= --}}
                    @if (!empty($sequence->existing_company_logo))
                        <tr>
                            <td align="{{ $sequence->logo_position ?? 'center' }}" style="padding:20px;">

                                <img src="{{ url($sequence->existing_company_logo) }}" alt="Company Logo"
                                    style="max-width:180px;height:auto;display:block;">

                            </td>
                        </tr>
                    @endif


                    {{-- ========================================= --}}
                    {{-- ✅ HERO IMAGE --}}
                    {{-- ========================================= --}}
                    @if (!empty($sequence->hero_image))
                        <tr>
                            <td>

                                <img src="{{ url($sequence->hero_image) }}" alt="Hero Image" width="100%"
                                    style="width:100%;max-width:100%;display:block;">

                            </td>
                        </tr>
                    @endif


                    {{-- ========================================= --}}
                    {{-- ✅ CONTENT --}}
                    {{-- ========================================= --}}
                    <tr>
                        <td style="padding:20px;">

                            <h3 style="margin-top:0;">
                                Hello {{ $lead->name ?? ($lead->fullname ?? 'there') }}
                            </h3>

                            <hr style="border:none;border-top:1px solid #ddd;">

                            <div style="font-size:15px;line-height:1.7;color:#333;">
                                {!! $finalMessage !!}
                            </div>

                        </td>
                    </tr>


                    {{-- ========================================= --}}
                    {{-- ✅ ATTACHMENT --}}
                    {{-- ========================================= --}}
                    @if (!empty($sequence->attachments_image))
                        <tr>
                            <td style="padding:20px;">

                                <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                    style="background:#f0f0f0;border-radius:6px;">

                                    <tr>
                                        <td align="center" style="padding:15px;">

                                            <strong>
                                                📎 {{ $sequence->attachment_name ?? 'Attachment' }}
                                            </strong>

                                            <br><br>

                                            <a href="{{ url($sequence->attachments_image) }}" target="_blank"
                                                style="color:#007bff;text-decoration:none;">

                                                ⬇️ View Attachment

                                            </a>

                                        </td>
                                    </tr>

                                </table>

                            </td>
                        </tr>
                    @endif


                    {{-- ========================================= --}}
                    {{-- ✅ SOCIAL BUTTONS --}}
                    {{-- ========================================= --}}
                    <tr>
                        <td align="center" style="padding:20px;">

                            @if ($sequence->whatsapp_link)
                                <a href="{{ $sequence->whatsapp_link }}" target="_blank"
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
                                <a href="{{ $sequence->telegram_link }}" target="_blank"
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
                                <a href="{{ $sequence->business_link }}" target="_blank"
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

                </table>

            </td>
        </tr>
    </table>

</body>

</html>
