<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Webneoo Accounting System</title>
    <style type="text/css">
    .invoice-box{
        width: 21cm;
        height: 29.7cm;
        margin: 0 auto;
        margin-bottom: 0.5cm;
        padding:30px;
        border:1px solid #eee;
        box-shadow:0 0 10px rgba(0, 0, 0, .15);
        font-size:16px;
        line-height:24px;
        font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        color:#555;
        background-color: #fff;
    }
    
    .invoice-box table{
        width:100%;
        line-height:inherit;
        text-align:left;
    }
    
    .invoice-box table td{
        padding:5px;
        vertical-align:top;
    }
    
    .invoice-box table tr td:nth-child(2){
        text-align:right;
    }
    
    .invoice-box table tr.top table td{
        padding-bottom:20px;
    }
    
    .invoice-box table tr.top table td.title{
        font-size:45px;
        line-height:45px;
        color:#333;
    }
    
    .invoice-box table tr.information table td{
        padding-bottom:40px;
    }
    
    .invoice-box table tr.heading td{
        background:#eee;
        border-bottom:1px solid #ddd;
        font-weight:bold;
    }
    
    .invoice-box table tr.details td{
        padding-bottom:20px;
    }
    
    .invoice-box table tr.item td{
        border-bottom:1px solid #eee;
    }
    
    .invoice-box table tr.item.last td{
        border-bottom:none;
    }
    
    .invoice-box table tr.total td:nth-child(2){
        border-top:2px solid #eee;
        font-weight:bold;
    }
    
    @media only screen and (max-width: 600px) {
        .invoice-box table tr.top table td{
            width:100%;
            display:block;
            text-align:center;
        }
        
        .invoice-box table tr.information table td{
            width:100%;
            display:block;
            text-align:center;
        }
    }
    </style>
</head>

<body>
    <div class="invoice-box">
        @foreach($invoiceInfo as $i)                        
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <img src="/images/logo.png" style="width:100%; max-width:300px;">
                            </td>
                            
                            <td>
                                Invoice #: {{ $i->invoice_id }}<br>
                                Created: {{ $i->created_at }}<br>
                                Due: {{ $i->due_date }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="information">
                <td colspan="2">
                    <table>
                        <tr>
                            <td>
                                {{ $i->address }}
                            </td>
                            
                            <td>
                                {{ $i->name }}<br>
                                {{ $i->contact_name }}<br>
                                {{ $i->email }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            
            <tr class="heading">
                <td>
                    Payment Method
                </td>
                
                <td>
                    Check #
                </td>
            </tr>
            
            <tr class="details">
                <td>
                    Check
                </td>
                
                <td>
                    1000
                </td>
            </tr>
            
            <tr class="heading">
                <td>
                    Item
                </td>
                
                <td>
                    Price
                </td>
            </tr>
            
            @foreach($invoiceItems as $it)
            <tr class="item">
                <td>
                    {{ $it->title }}
                </td>
                
                <td>
                    ${{ $it->item_amount }}.00
                </td>
            </tr>
            @endforeach
            
            <tr class="total">
                <td></td>
                
                <td>
                   Total: ${{ $i->amount }}.00
                </td>
            </tr>
        </table>
        @endforeach
    </div>


    <script type="text/javascript" src="/js/jquery-1.10.2.js"></script>
    <script type="text/javascript" src="/js/html2canvas.js"></script>
    <script type="text/javascript" src="/js/download.js"></script>
    <script type="text/javascript">
        html2canvas($('.invoice-box'), {
            logging: true,
            useCORS: true,
            onrendered: function (canvas) {            
                img = canvas.toDataURL("image/png");
                download(img, "modified.png", "image/png");
            }
        });
        setTimeout(function(){ window.location = "{{ route('invoices_path') }}";}, 2*1000);
    </script>

</body>
</html>