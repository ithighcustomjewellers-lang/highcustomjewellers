<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $subjectLine ?? $sequence->subject }}</title>
</head>

<body style="margin:0;padding:0;background:#f4f4f4;font-family:Arial,Helvetica,sans-serif;">

    {{-- ========================================= --}}
    {{-- OPEN TRACKING PIXEL (uses tracking_token) --}}
    {{-- ========================================= --}}
    @if(isset($campaignLog) && $campaignLog)
        <img src="{{ route('track-open', $campaignLog->tracking_token) }}"
             width="1" height="1"
             style="display:block;width:1px;height:1px;border:0;"
             alt="">
    @endif

    {{-- ========================================= --}}
    {{-- PREPARE INTEREST BUTTON URLS --}}
    {{-- ========================================= --}}
    @php
        $yesUrl = '#';
        $noUrl  = '#';

        if(isset($campaignLog) && $campaignLog) {
            $yesUrl = route('lead-response', [
                'log' => $campaignLog->id,
                'status' => 'interested'
            ]);
            $noUrl = route('lead-response', [
                'log' => $campaignLog->id,
                'status' => 'not_interested'
            ]);
        }

        // Compute logo style outside the HTML attribute to avoid syntax errors
        $logoStyle = ($sequence->image_type ?? '') === 'logo'
            ? 'max-width:140px; border-radius:12px; height:auto; display:block;'
            : 'width:100%; border-radius:16px; margin-bottom:20px; height:auto; display:block;';
    @endphp

    <table width="100%" cellpadding="0" cellspacing="0" border="0"
           style="background:#f4f4f4;padding:20px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" border="0"
                       style="background:#ffffff;border-radius:8px;overflow:hidden;">

                    {{-- ========================================= --}}
                    {{-- ✅ COMPANY LOGO --}}
                    {{-- ========================================= --}}
                    @if(!empty($sequence->existing_company_logo))
                        <tr>
                            <td align="{{ $sequence->logo_position ?? 'center' }}" style="padding:20px;">
                                <img src="{{ url($sequence->existing_company_logo) }}"
                                     alt="Company Logo"
                                     style="{{ $logoStyle }}">
                            </td>
                        </tr>
                    @endif

                    {{-- ========================================= --}}
                    {{-- ✅ HERO IMAGE --}}
                    {{-- ========================================= --}}
                    {{-- @if(!empty($sequence->hero_image))
                        <tr>
                            <td>
                                <img src="{{ url($sequence->hero_image) }}"
                                     alt="Hero Image"
                                     width="100%"
                                     style="width:100%;max-width:100%;display:block;">
                            </td>
                        </tr>
                    @endif --}}

                    @if(!empty($sequence->hero_image))
                        <tr>
                            <td>
                                @if(!empty($sequence->hero_image_link))
                                    <a href="{{ $sequence->hero_image_link }}"
                                    target="_blank"
                                    style="display:block;text-decoration:none;">
                                        <img src="{{ url($sequence->hero_image) }}"
                                            alt="Hero Image"
                                            width="100%"
                                            style="width:100%;max-width:100%;display:block;border:0;outline:none;text-decoration:none;">
                                    </a>
                                @else
                                    <img src="{{ url($sequence->hero_image) }}"
                                        alt="Hero Image"
                                        width="100%"
                                        style="width:100%;max-width:100%;display:block;border:0;outline:none;">
                                @endif
                            </td>
                        </tr>
                    @endif


                    {{-- ========================================= --}}
                    {{-- ✅ CONTENT --}}
                    {{-- ========================================= --}}
                    <tr>
                        <td style="padding:20px;">
                            <div style="font-size:15px;line-height:1.8;color:#333;">
                                {!! $finalMessage !!}
                            </div>
                        </td>
                    </tr>

                    {{-- ========================================= --}}
                    {{-- ✅ SOCIAL BUTTONS --}}
                    {{-- ========================================= --}}
                    <tr>
                        <td style="padding:20px; text-align: center;">
                            @php
                                $buttonStyle = "display:inline-block; min-width:100px; line-height:42px; height:42px; padding:0 18px; border-radius:50px; text-decoration:none; font-size:14px; font-weight:600; color:#ffffff; background:#3b5bdb; margin:5px; text-align:center; vertical-align:middle; box-sizing:border-box;";
                            @endphp

                            @if($sequence->whatsapp_link)
                                <a href="{{ $sequence->whatsapp_link }}"
                                   target="_blank"
                                   style="{{ $buttonStyle }}">
                                    WhatsApp
                                </a>
                            @endif

                            @if(!empty($sequence->action_links))
                                @foreach($sequence->action_links as $link)
                                    <a href="{{ $link['platform_url'] }}"
                                       target="_blank"
                                       style="{{ $buttonStyle }}">
                                        {{ $link['platform_name'] }}
                                    </a>
                                @endforeach
                            @endif
                        </td>
                    </tr>

                    {{-- ========================================= --}}
                    {{-- ✅ INTEREST BUTTONS --}}
                    {{-- ========================================= --}}
                    @if(isset($campaignLog) && $campaignLog)
                        <tr>
                            <td align="center" style="padding:10px 20px 30px 20px;">
                                <table cellpadding="0" cellspacing="0" border="0">
                                    <tr>
                                        <td align="center" style="padding:5px;">
                                            <a href="{{ $yesUrl }}"
                                               target="_blank"
                                               style="color:#22c55e;padding:4px 5px;text-decoration:none;border-radius:6px;display:inline-block;font-weight:bold;">
                                                YES, INTERESTED
                                            </a>
                                        </td>
                                        <td align="center" style="padding:5px;">
                                            <a href="{{ $noUrl }}"
                                               target="_blank"
                                               style="color:#ef4444;padding:4px 5px;text-decoration:none;border-radius:6px;display:inline-block;font-weight:bold;">
                                                NOT INTERESTED
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    @endif

                    {{-- ========================================= --}}
                    {{-- ✅ FOOTER --}}
                    {{-- ========================================= --}}
                    <tr>
                        <td align="center"
                            style="padding:20px;background:#fafafa;font-size:12px;color:#777;">
                            © {{ date('Y') }} {{ config('app.name') }} All rights reserved.
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
