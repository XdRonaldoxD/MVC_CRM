<!DOCTYPE html>
<html>

<head>
    <style>
        @media screen and (min-width: 480px) {
            .div-background {
                width: 90% !important;
            }
        }

        @media screen and (min-width: 767px) {
            .div-background {
                width: 70% !important;
            }
        }

        @media screen and (min-width: 950px) {
            .div-background {
                width: 44.4% !important;
            }
        }
    </style>
</head>
<body>
    <div class="div-background " style="width: 100%!important;margin-top: 10px;">
        <table style="width:66%; margin:0 auto;">
            <tr>
                <td style="background:#0B98CB;text-align:center;padding: 15px 0px 15px 0px;">
                    <img height="100" width="auto" src="<?= $path_imagen ?>">
                    
                </td>
            </tr>
        </table>
    </div>
    <div class="div-background " style="width: 100%!important;margin-top: 10px;">
        <table style="width:66%; margin:0 auto;">
            <tr>
                <td style="background:#f4f4f4;padding: 15px;">
                    <?=$cuerpo?>            
                </td>
            </tr>
        </table>
    </div>
</body>
</html>