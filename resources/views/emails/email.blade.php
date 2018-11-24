<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Cygnus Account Verification</title>
  <style type="text/css">
  @import url(http://fonts.googleapis.com/css?family=Droid+Sans);
  img {
    max-width: 600px;
    outline: none;
    text-decoration: none;
    -ms-interpolation-mode: bicubic;
  }

  a {
    text-decoration: none;
    border: 0;
    outline: none;
    color: #FFFFFF;
  }
  
  h1, h2, h3, p {
	color: #FFFFFF;
  }

  a img {
    border: none;
  }

  /* General styling */

  td, h1, h2, h3  {
    font-family: Helvetica, Arial, sans-serif;
    font-weight: 400;
  }

  td {
    text-align: center;
  }

  body {
    -webkit-font-smoothing:antialiased;
    -webkit-text-size-adjust:none;
    width: 100%;
    height: 100%;
    color: #1c3144;
    background: #1c3144;
    font-size: 16px;
  }

   table {
    border-collapse: collapse !important;
  }

  .headline {
    color: #FFFFFF;
    font-size: 36px;
  }

 .force-full-width {
  width: 100% !important;
 }
  </style>
  <style type="text/css" media="screen">
      @media screen {
        td, h1, h2, h3 {
          font-family: 'Droid Sans', 'Helvetica Neue', 'Arial', 'sans-serif' !important;
        }
      }
  </style>
  <style type="text/css" media="only screen and (max-width: 480px)">
    @media only screen and (max-width: 480px) {
      table[class="w320"] {
        width: 320px !important;
      }
    }
  </style>
</head>
<body class="body" style="padding:0; margin:0; display:block; -webkit-text-size-adjust:none">
<table align="center" cellpadding="0" cellspacing="0" width="100%" height="100%" >
  <tr>
    <td align="center" valign="top" bgcolor="#1c3144"  width="100%">
      <center>
        <table style="margin: 0 auto;" cellpadding="0" cellspacing="0" width="600" class="w320">
          <tr>
            <td align="center" valign="top">
				
				<!-- Header -->
                <table style="margin: 0 auto;" cellpadding="0" cellspacing="0" style="margin:0;">
                  <tr>
                    <td style="font-size: 30px; text-align:center;">
                        <img src="https://api.maplecygnus.com/image/mail_banner.png" alt="cygnus picture">
                    </td>
                  </tr>
                </table>
				
				<!-- Body -->
                <table style="margin: 0 auto;" cellpadding="0" cellspacing="0" width="100%" bgcolor="#13191f">
                  <tr>
                    <td>
                    <br>
                      <img src="https://api.maplecygnus.com/image/{{$title_image}}" alt="cygnus picture">
                    </td>
                  </tr>
                    <td>
                      <center>
                        <table style="margin: 0 auto;" cellpadding="0" cellspacing="0" width="60%">
                          <tr>
                            <td style="color:#187272;">
                             <p>{{$content}}</p>
							 <br />
                            </td>
                          </tr>
                        </table>
                      </center>

                    </td>
                  </tr>
                  <tr>
                    <td>
					  <br>
                      <div>
                            <a href="{{$button_url}}"
                      style="background-color:#ff9d00;border-radius:4px;color:#161616;display:inline-block;font-family:Helvetica, Arial, sans-serif;font-size:16px;font-weight:bold;line-height:50px;text-align:center;text-decoration:none;width:200px;-webkit-text-size-adjust:none;">{{$button_text}}</a></div>
                      <br>
                      <br>
                    </td>
                  </tr>
                </table>
				
				<!-- Footer -->
                <table style="margin: 0 auto;" cellpadding="0" cellspacing="0" class="force-full-width" bgcolor="#414141" style="margin: 0 auto">
                  <tr><td style="color:#bbbbbb; font-size:12px;"><br /></td></tr>
                  <tr>
                    <td style="color:#bbbbbb; font-size:12px;">
                       © Cygnus 2018 All Rights Reserved
                       <br>
                       <br>
                    </td>
                  </tr>
                </table>
				
            </td>
          </tr>
        </table>
    </center>
    </td>
  </tr>
</table>
</body>
</html>