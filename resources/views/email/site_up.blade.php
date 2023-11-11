@include('email.inc.header')
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background: #ffffff;">
    <tr>
        <td width="56"></td>
        <td>
            <div style="font-weight: 400;font-size: 14px;line-height: 24px; color: #000000;">
                <div>
                    Hello, {{ $data['site']['manager'] ?? '' }}<br/>
                </div>
                <div style="height: 20px;"></div>
                <div>
                    <p style="font-size: 12px;">
                        The latest incident has been resolved and your <span style="font-weight: bold;">{{  $data['site']['url'] ?? '' }} </span> is up again. Good job!
                    </p>
                    <p style="font-size: 10px;">Checked URL: <br/>
                        <span style="font-size: 12px; font-weight: bold;"><a href="{{ $data['site']['url']}}"> {{  $data['site']['url'] ?? '' }} </a></span>
                    </p>
                    <p style="font-size: 8px;">Resolved at: <br/>
                        <span style="font-size: 12px; font-weight: bold;"> {{  $data['up_at'] ?? '' }} </span>
                    </p>
                    <p style="font-size: 10px;">Incident started at: <br/>
                        <span style="font-size: 12px; font-weight: bold;">{{  $data['site']['down_at'] ?? '' }} </span>
                    </p>
                    <p style="font-size: 10px;">Error Code : <br/>
                        <span style="font-size: 12px; font-weight: bold;"> {{  $data['site']['code'] ?? '' }} </span>
                    </p>
                    <p style="font-size: 10px;">Root cause : <br/>
                        <span style="font-size: 12px; font-weight: bold;"> {{  $data['site']['message'] ?? '' }} </span>
                    </p>
                    <p style="font-size: 10px;">Duration : <br/>
                        <span style="font-size: 12px; font-weight: bold;">{{  $data['duration'] ?? '' }} </span>
                    </p>
                </div>

                <div style="height: 35px;"></div>
                <div style="font-weight: 400;font-size: 14px;line-height: 24px;">Thank you, <br>{{ config('app.name') }}.</div>
                <div style="height: 92px;"></div>
            </div>
        </td>
        <td width="56"></td>
    </tr>
</table>
@include('email.inc.footer')
