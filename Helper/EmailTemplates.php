<?php
namespace Kamran\UserBundle\Helper;



class EmailTemplates
{

    public static function signupEmail($username,$email,$password,$link){
        $_html = '';
        $_html .= '<p>Dear %s</p>';
        $_html .= '<p>Here is your account information for App_Name.</p>';
        $_html .= '<br/><p>Your email is <b>%s</b></p>';
        $_html .= '<p>Your password is <b>%s</b></p><br/>';
        $_html .= '<a href="%s" style="background-color:#2FB6F4;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;line-height:35px;text-align:center;text-decoration:none;width:200px;-webkit-text-size-adjust:none;">Activate Account</a>';
        $_html .= '<br/><br/><p>Please click the link above to activate your account.</p>';
        $_html .= '<p>Thank you for joining us</p>';
        
        $emailBody = sprintf($_html, $username,$email,$password,$link );
        return self::emailLayout($emailBody);
    }

    public static function createUserEmail($username,$email,$password,$link){
        $_html = '';
        $_html .= '<p>Dear %s</p>';
        $_html .= '<p>Here is your account information for App_Name.</p>';
        $_html .= '<br/><p>Your email is <b>%s</b></p>';
        $_html .= '<p>Your password is <b>%s</b></p><br/>';
        $_html .= '<a href="%s" style="background-color:#2FB6F4;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;line-height:35px;text-align:center;text-decoration:none;width:200px;-webkit-text-size-adjust:none;">Confirm your Account</a>';
        $_html .= '<br/><br/><p>Please click the link above to activate your account.</p>';
        $_html .= '<p>Thank you for joining us</p>';

        $emailBody = sprintf($_html, $username,$email,$password,$link );
        return self::emailLayout($emailBody);
    }

    public static function resetpasswordEmail( $username , $newpassword , $loginLink='' ){
        $_html = '';
        $_html .= '<p>Dear %s</p>';
        $_html .= '<p>Your password reset now, Here is your new password for login.</p>';
        $_html .= '<p>Your password is <b>%s</b></p><br/>';
        $_html .= '<a href="%s" style="background-color:#2FB6F4;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;line-height:35px;text-align:center;text-decoration:none;width:200px;-webkit-text-size-adjust:none;">Login</a>';
        $emailBody = sprintf($_html, $username , $newpassword , $loginLink );

        return self::emailLayout($emailBody);
    }

    public static function emailLayout($emailBody=''){
      return '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title> Cogilent Directory </title>
    <style type="text/css" media="screen">
        h1 {
            font-size: 24px;
            font-weight: normal;
            line-height: 24px;
        }
        body,
        p {
            margin-bottom: 0;
            -webkit-text-size-adjust: none;
            -ms-text-size-adjust: none;
            font-family: "Arial Black", Gadget, sans-serif;
            font-size: 12px;
            color:#333;
        }
        img {
            outline: none;
            text-decoration: none;
            -ms-interpolation-mode: bicubic;
        }
        a img {
            border: none;
        }
        .background {
            background-color: #ECECEC;
        }
        table td {
            border-collapse: collapse;
        }
        td {
            vertical-align: top;
            text-align: left;
        }
        .wrap {
            width: 600px;
        }
        .wrap-cell {
            padding-top: 30px;
            padding-bottom: 30px;
        }
        .header-cell,
        .body-cell,
        .footer-cell {
            padding-left: 20px;
            padding-right: 20px;
        }
        .header-cell {
            background-color: #4fbaed;
            font-size: 24px;
            color: #ffffff;
        }
        .body-cell {
            background-color: #ffffff;
            padding-top: 30px;
            padding-bottom: 34px;
        }
        .footer-cell {
            background-color: #afd2e3;
            text-align: center;
            font-size: 13px;
            padding-top: 30px;
            padding-bottom: 30px;
        }
        .force-full-width {
            width: 100% !important;
        }

    </style>
</head>
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" bgcolor="" class="background">
<table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" class="background">
    <tr>
        <td align="center" valign="top" width="100%" class="background">
            <center>
                <table cellpadding="0" cellspacing="0" width="600" class="wrap">
                    <tr>
                        <td valign="top" class="wrap-cell" style="padding-top:30px; padding-bottom:30px;">
                            <!--#Email-Content-->
                            <table cellpadding="0" cellspacing="0" class="force-full-width">
                                <tr>
                                    <td height="60" valign="middle" class="header-cell">
                                        <h1> App_Name </h1>
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top" class="body-cell">
                                        <!--content-->
                                        '.$emailBody.'
                                        <!--@content-->
                                    </td>
                                </tr>
                                <tr>
                                    <td valign="top" class="footer-cell">
                                        © Company_name Year
                                    </td>
                                </tr>
                            </table>
                            <!--@Email-Content-->
                        </td>
                    </tr>
                </table>
            </center>
        </td>
    </tr>
</table>
</body>
</html>   	';
    }



}//@