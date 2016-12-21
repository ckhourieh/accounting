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
            <li style="float:right; width:50%; text-align:right; display:inline-block; margin-top:15px;">
                <h1>Salary Payment</h1>
                <b>Webneoo</b> <br>
                Jal El Dib Main Road <br>
                Mallah Center - 2nd Floor <br>
                Beirut, Lebanon <br>
            </li>
        </ul>

        
        <h3 style="text-align:center; border-top:1px solid #888; border-bottom:1px solid #888;">
            <span style="font-weight:normal">Salary of </span>{{ $data[0]->firstname }} {{ $data[0]->lastname }} <span style="font-weight:normal"> for the month of </span>{{date("F  Y", strtotime($data[1]->salary_date)) }}</h3>
        
       

        <div style="width:100%; height:210px; margin:auto;"> 
            <ul style="list-style-type:none; padding: 0 30px; ">
                <li style="padding:5px; float:left; width:80%; display:inline-block; font-size:16px; border-bottom: 1px solid #888888;">
                    <b>Base Salary</b>
                </li>
                
                <li style="padding-top:10px; text-align:right; float:right; display:inline-block; font-size:14px; border-bottom: 1px solid #888888;">
                    $666
                </li>
            </ul>
       
            <ul style="list-style-type:none; padding: 0 30px;">
                <li style="padding:5px; float:left; width:80%; display:inline-block; font-size:16px; border-bottom: 1px solid #888888;">
                    <b>Days off deduction</b>
                </li>
                
                <li style="padding-top:10px; text-align:right; float:right; display:inline-block; font-size:14px; border-bottom: 1px solid #888888;">
                    ${{ number_format($data[1]->days_off_amount, 2, '.', ' ') }}
                </li>
            </ul>

            @if($data[1]->bonus_amount != 0)
            <ul style="list-style-type:none; padding: 0 30px;">
                <li style="padding:5px; float:left; width:80%; display:inline-block; font-size:16px; border-bottom: 1px solid #888888;">
                    <b>Bonus</b>
                </li>
                
                <li style="padding-top:10px; text-align:right; float:right; display:inline-block; font-size:14px; border-bottom: 1px solid #888888;">
                    ${{ number_format($data[1]->days_off_amount, 2, '.', ' ') }}
                </li>
            </ul>
            @endif

            <ul style="list-style-type:none; padding: 0 30px;">
                <li style="padding:5px; float:left; width:80%; display:inline-block; font-size:16px; border-bottom: 1px solid #888888;">
                    <b>Transportation amount</b>
                </li>
                
                <li style="padding-top:10px; text-align:right; float:right; display:inline-block; font-size:14px; border-bottom: 1px solid #888888;">
                    ${{ number_format($data[1]->transport_amount, 2, '.', ' ') }}
                </li>
            </ul>


            <ul style="list-style-type:none; padding: 0 30px;">
                    <li style="padding:5px; float:left; width:50%; display:inline-block;"></li>
                    <li style="padding:5px; text-align:right; font-weight:bold; float:right; width:50%; display:inline-block;">
                      <h1> Total: $666 </h1>
                    </li>
                </ul>
        </div>

     
            @if($data[1]->transport_amount != 0)
            <div style="width:100%; margin:auto;"> 


            <b style="text-decoration:underline;">Transportation details</b>
               <table style="width:100%; margin:auto; display:block;">
               <tr style="height:35px; background:#000; padding: 0 30px;">
                    <td style="padding:5px; font-weight:bold; width:35%; color:#fff;">Date</td>
                    <td style="padding:5px; font-weight:bold; width:35%; color:#fff;">place</td>
                    <td style="padding:5px; font-weight:bold; width:35%; color:#fff;">reason</td>
                    <td style="padding:5px; font-weight:bold; width:35%; color:#fff;">price</td>
               </tr>

                <?php for($i=2; $i<sizeof($data); $i++) { ?> 
                   <tr>
                        <td style="font-size:14px; border-bottom: 1px solid #888888;">{{$data[$i]->transport_date}}</td>
                        <td style="font-size:14px; border-bottom: 1px solid #888888;">{{$data[$i]->place}}</td>
                        <td style="font-size:14px; border-bottom: 1px solid #888888;">{{$data[$i]->reason}}</td>
                        <td style="font-size:14px; border-bottom: 1px solid #888888;">${{ number_format($data[$i]->price, 2, '.', ' ') }}</td>
                   </tr>
                <?php } ?>

                </table>  

                </div>
            @endif


            <br/><br/>

        <ul style="list-style-type:none; padding: 0 30px;">
            
            <li style="text-align:left; display:inline-block;">
                <h3>Read and Approved</h3>
                <b>Date:</b> ______________________ <br><br>
                <b>Signature:</b> _________________ <br>
            </li>
        </ul>

            
       

          

    </div>

</body>
</html>