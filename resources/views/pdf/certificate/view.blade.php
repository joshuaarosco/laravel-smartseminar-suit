<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{$data['title']}}</title>
    <style>
        .page-break {
            page-break-after: always;
        }
        @page {
            margin-top:0;
            margin-bottom:0;
            margin-left:0;
            margin-right:0;
            padding: 0;
        }
        body {
            font-family: helvetica !important;
            font-size: 10pt;
            position: relative;
        }
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background-position: center top;
            background-repeat: repeat;
            background-size: cover;
            z-index: -1;
        }
        .content{
            padding: 0cm;
        }
        .cert-title{
            font-size: 45pt;
            margin-top: 150pt;
        }
        .text-center{
            text-align: center;
        }
        .certificate-content{
            line-height: 10px; 
            margin-top: -30px;
        }
        .participant-name{
            font-size: 35pt;
            font-style: italic; 
            font-family: cursive;
        }
        .underline{
            margin-top: -50px;
        }
        table{
            width: 100%;
            /* border: 1px solid red; */
            padding-top: 75px;
        }
        .pt{
            margin-top: 100px;
            height: 50px;
        }
    </style>
</head>
<body>
<div class="page-body">
<div class="content overlay" style="
            background-image: url({!! $data['certificate']->directory.'/'.$data['certificate']->filename !!})">
    <div class="text-center">
        <h1 class="cert-title">CERTIFICATE</h1>
        <h2 class="certificate-content">OF COMPLETION</h2>
        <p>IS PRESENTED TO:</p>
        <h1 class="participant-name">{{ $data['certificate']->user_name }}</h1>
        <p class="underline">___________________________________________________________</p>
        <p>For completing the <strong>{{ $data['certificate']->category }}</strong> with the event <br>
        title of "<strong>{{ Str::title($data['certificate']->event_name) }}</strong>" that was held on <strong>{{$data['certificate']->date?date('M d, Y', strtotime($data['certificate']->date)):'---'}}.</strong></p>
        <br>
        <p>" {!!$data['certificate']->quote!!} "</p>
        <table>
            <tr class="">
                <td class="text-center "></td>
                <td></td>
                <td class="text-center "><strong>{{$data['certificate']->date?date('m-d-Y', strtotime($data['certificate']->date)):'---'}}</strong></td>
            </tr>
            <tr class="">
                <td class="text-center ">______________________________</td>
                <td></td>
                <td class="text-center ">______________________________</td>
            </tr>
            <tr class="">
                <td class="text-center "></td>
                <td></td>
                <td class="text-center "></td>
            </tr>
            <tr class="">
                <td class="text-center ">Coordinator</td>
                <td></td>
                <td class="text-center ">Date</td>
            </tr>
        </table>
    </div>
</div>
</div>
</body>
</html>