<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Webneoo Accounting System</title>
</head>

<body>

    <div style="font-size:14px;line-height:24px;font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;">

        <ul style="list-style-type:none; height:180px; padding: 0 30px;">
            <li style="float:left; width:50%; display:inline-block; margin-top:20px;"><img src="http://accounting.webneoodemo.com/images/logo.png" style="width:100%;max-width:300px;"></li>
            <li style="float:right; width:50%; text-align:right; display:inline-block;">
                <h1>INVOICE</h1>
                <b>Webneoo</b> <br>
                Jal El Dib Main Road <br>
                Mallah Center - 2nd Floor <br>
                Beirut, Lebanon <br>
                Financial #: 2693372
            </li>
        </ul>

        <hr>

        <ul style="list-style-type:none; height:180px; padding: 0 30px;">
            <li style="float:left; width:50%; display:inline-block;">
                <h5 style="color:#666; margin:5px 0;">BILL TO</h5>
                <b>{{ $data[0]->client_name }}</b><br>
                {{ $data[0]->address }}<br>
                {{ $data[0]->phone }} <br>
                @if(!empty($data[0]->accounting_id) || $data[0]->accounting_id != NULL )
                Financial #: {{ $data[0]->accounting_id }}
                @endif
            </li>
            <li style="float:right; width:50%; text-align:right; display:inline-block; line-height:28px">
                <b>Invoice Number: </b>{{ $data[0]->invoice_id }}<br>
                <b>Invoice Date: </b>{{ $data[0]->created_at }}<br>
                <b>Payment Due: </b></b>{{ $data[0]->due_date }}<br>
                <b>Amount Due (USD): ${{ number_format($data[0]->amount, 2, '.', ' ') }}</b>
            </li>
        </ul>

        <ul style="list-style-type:none; height:35px; background:#000; padding: 0 30px;">
            <li style="padding:5px; font-weight:bold; width:50%; float:left; display:inline-block; color:#fff;">
                Item
            </li>
            
            <li style="padding:5px; text-align:right; font-weight:bold; width:50%; float:right; display:inline-block; color:#fff;">
                Price
            </li>
        </ul>

        <?php for($i=1; $i<sizeof($data); $i++) { ?>
            <ul style="list-style-type:none; padding: 0 30px;">
                <li style="padding:5px; float:left; width:80%; display:inline-block;">
                    <b>{{ $data[$i]->service_title }}</b><br>
                     {!! $data[$i]->description !!}  
                </li>
                
                <li style="padding:5px; text-align:right; float:right; width:20%; display:inline-block;">
                    ${{ number_format($data[$i]->item_amount, 2, '.', ' ') }}
                </li>
            </ul>
        <?php } ?>
        
        <ul style="list-style-type:none; padding: 0 30px;">
            <li style="padding:5px; float:left; width:50%; display:inline-block;"></li>
            <li style="padding:5px; text-align:right; border-top:2px solid #eee; font-weight:bold; float:right; width:50%; display:inline-block;">
               Total: ${{ number_format($data[0]->amount, 2, '.', ' ') }}
            </li>
        </ul>

    </div>

</body>
</html>