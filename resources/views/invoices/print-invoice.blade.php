<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Webneoo Accounting System</title>
</head>

<body>
    <div style="width:21cm;height:29.7cm;margin:0 auto;margin-bottom:0.5cm;padding:30px;border:1px solid #eee;box-shadow:0 0 10px rgba(0, 0, 0, .15);font-size:16px;line-height:24px;font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;color:#555;background-color:#fff;">
                               
        <table cellpadding="0" cellspacing="0" style="width:100%;line-height:inherit;text-align:left;">
            <tr>
                <td colspan="2" style="padding:5px;vertical-align: top;">
                    <table style="width:100%;line-height:inherit;text-align:left;">
                        <tr>
                            <td style="padding:5px;vertical-align:top;padding-bottom:20px;font-size:45px;line-height:45px;color:#333;">
                                <img src="http://accounting.dev/images/logo.png" style="width:100%;max-width:300px;">
                            </td>
                            
                            <td style="padding:5px;vertical-align:top;text-align:right;padding-bottom:20px;">
                                Invoice #: {{ $data[0]->invoice_id }}<br>
                                Created: {{ $data[0]->created_at }}<br>
                                Due: {{ $data[0]->due_date }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr>
                <td colspan="2" style="padding:5px;vertical-align:top;">
                    <table style="width:100%;line-height:inherit;text-align:left;">
                        <tr>
                            <td style="padding:5px;vertical-align:top;padding-bottom:40px;">
                                {{ $data[0]->address }}
                            </td>
                            
                            <td style="padding:5px;vertical-align:top;text-align:right;padding-bottom:40px;">
                                {{ $data[0]->client_name }}<br>
                                {{ $data[0]->contact_name }}<br>
                                {{ $data[0]->email }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr>
                <td style="padding:5px;vertical-align:top;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">
                    Item
                </td>
                
                <td style="padding:5px;vertical-align:top;text-align:right;background:#eee;border-bottom:1px solid #ddd;font-weight:bold;">
                    Price
                </td>
            </tr>

            @for($i=1; $i<sizeof($data); $i++) 
                <tr>
                    <td style="padding:5px;vertical-align:top;">
                        {{ $data[$i]->title }}
                    </td>
                    
                    <td style="padding:5px;vertical-align:top;text-align:right">
                        ${{ $data[$i]->item_amount }}.00
                    </td>
                <tr>
            @endfor
            
            <tr>
                <td style="padding:5px;vertical-align:top;"></td>
                
                <td style="padding:5px;vertical-align:top;text-align:right;border-top:2px solid #eee;font-weight:bold;">
                   Total: ${{ $data[0]->amount }}.00
                </td>
            </tr>
        </table>

    </div>

</body>
</html>