@include('email.inc.header')
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background: #ffffff;">
    <tr>
        <td width="56"></td>
        <td>
            <div style="font-weight: 400;font-size: 14px;line-height: 24px; color: #000000;">
                <div>
                    Hi, {{ $data['site']['manager'] ?? '' }}<br/>
                </div>
                <div style="height: 20px;"></div>
                <div>
                    <p>
                        Your {{  $data['site']['url'] ?? '' }} is currently down. Please take an action immediately .
                    </p>
                    <p>Last Check at : {{  $data['last_check'] ?? '' }}</p>
                    <p>Last Down at : {{  $data['down_at'] ?? '' }}</p>
                    <p>Error Code : {{  $data['code'] ?? '' }}</p>
                    <p>Message : {{  $data['message'] ?? '' }}</p>
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
