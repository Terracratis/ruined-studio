<?php
	if ($ruinedstudio == false)
		exit;
	$header = array('title'=>'Užsakymai','type'=>'main','robots'=>'noindex,nofollow','author'=>'By Ruined Studio');
?>
<style>html, body { height: 100%; }
html, body, div, span, applet, object, iframe,
h1, h2, h3, h4, h5, h6, p, blockquote, pre,
a, abbr, acronym, address, big, cite, code,
del, dfn, em, img, ins, kbd, q, s, samp,
small, strike, strong, sub, sup, tt, var,
b, u, i, center,
dl, dt, dd, ol, ul, li,
fieldset, form, label, legend,
table, caption, tbody, tfoot, thead, tr, th, td,
article, aside, canvas, details, embed,
figure, figcaption, footer, header, hgroup,
menu, nav, output, ruby, section, summary,
time, mark, audio, video {
	margin: 0;
	padding: 0;
	border: 0;
	font-size: 100%;
	vertical-align: baseline;
}
	
article, aside, details, figcaption, figure,
footer, header, hgroup, menu, nav, section {
	display: block;
}
body {
	line-height: 1;
}
ol, ul {
	list-style: none;
}
blockquote, q {
	quotes: none;
}
blockquote:before, blockquote:after,
q:before, q:after {
	content: '';
	content: none;
}
table {
	border-collapse: collapse;
	border-spacing: 0;
}
	

body { background:#eee; margin:0; }
	
a:link, a:visited, a:active { color: #3c9632; text-decoration: none; outline:none; }
a:hover { text-decoration: none; color: #34742d; }


body { font-family:Arial, Helvetica, sans-serif; font-size: 14px; color: #555; }

h1, h2, h3, h4, p { margin:0 0 15px 0; padding:0; }
h1 { font-size:36px; font-weight:normal; font-family:arial, sans-serif; color:#333; }
h2 { font-size:28px; font-weight:normal; font-family:arial, sans-serif; color:#333; padding:15px 0 0 0; }
h3 { font-size:22px; font-weight:normal; font-family:arial, sans-serif; color:#333; padding:15px 0 0 0; }
h4 { font-size:16px; font-weight:normal; font-family:arial, sans-serif; color:#333; padding:15px 0 0 0; }
p { line-height:1.5em }

.clr {clear:both; font-size:1px; height:1px; }

/* float clearing for IE6 */
* html .clearfix{ height: 1%; overflow: visible;}
/* float clearing for IE7 */
*+html .clearfix{ min-height: 1%;}
/* float clearing for everyone else */
.clearfix:after{ clear: both; content: "."; display: block; height: 0; visibility: hidden; font-size: 0;}

#main {  width:970px; margin:0 auto; padding:20px 0;  }

footer, #footer { padding:15px 0; font-size:11px; text-align:center; color:#777 }


.mailbox { background:#fff; border:1px solid #bbb; padding:15px; margin:0 0 15px 0;  -moz-border-radius:5px; -webkit-border-radius:5px; border-radius:5px;  -moz-box-shadow:0 0 8px #bbb; -webkit-box-shadow:0 0 8px #bbb; box-shadow:0 0 8px #bbb; }

.email_item { border:1px solid #ddd; margin:0 0 3px 0; font-size:12px; font-family:arial, sans-serif; background:#f3f3f3; padding:7px 10px;  -moz-border-radius:2px; -webkit-border-radius:2px; border-radius:2px; }
.email_item.read { background:#f3f3f3 url(email_open.png) no-repeat 8px center; padding-left:34px; }
.email_item.unread { border:1px solid #ccc; background:#e2e2e2 url(email.png) no-repeat 8px center; padding-left:34px;  }
.email_item.unread .subject { font-weight:bold; }
.email_item:hover {  }

.email_item .subject { display:block; float:left; width:45%; color:#222; line-height:1.2em; white-space:nowrap; overflow:hidden; }
.email_item .from { display:block; float:left; width:29%; padding-left:1%; color:#444; line-height:1.2em;  white-space:nowrap; overflow:hidden; }
.email_item .date { display:block; float:right; width:24%; padding-left:1%; color:#555; font-size:11px; line-height:1.3em; white-space:nowrap;  overflow:hidden; }











</style>
<div class="fill_top"></div>
<div class="top_fixed">
 <div class="top_rel"><div class="top_abs">
  <div class="top_navigation">
	<a class="ruins" href="{URL}/orders"><span>Nauji</span></a>
	<a class="ruins" href="{URL}/orders/1/accepted"><span>Priimti</span></a>
	<a class="ruins" href="{URL}/orders/1/sent"><span>Išsiusti</span></a>
	<a class="ruins" href="{URL}/orders/1/delivered"><span>Pristatyti</span></a>
	<a class="logout" href="{URL}/logout" title="Atsijungti"></a>
	<a class="website" href="{ROOT}" title="Peržiūrėti svetainę" target="_blank"></a>
  </div>
 </div></div>	
</div>
<h1>Užsakymų sąrašas</h1>
<br/>
<?
##################################################################
# IMAP Inbox Notifier 
# by Nikos Tsaganos
# http://www.backslash.gr - nikos@backslash.gr 
# 
# DISCLAIMER:
# This is a demo on how to use some imap php functions. 
# It is NOT recommended to store your passwords in an 
# unencoded php file on a shared server.
##################################################################

// general password protection
// leave blank if you don't want to protect this page
$pageauth['username'] = 'demo';
$pageauth['password'] = 'demo123';


// configure your imap mailboxes
$mailboxes = array(
	array(
		'label' 	=> 'Gmail',
		'enable'	=> true,
		'mailbox' 	=> '{imap.gmail.com:993/imap/ssl}INBOX',
		'username' 	=> 'rytis.grincevicius@gmail.com',
		'password' 	=> 'kakalas12'
	)
);

if ($pageauth['username'] && $pageauth['password']) {
	if(($_SERVER["PHP_AUTH_USER"]!==$pageauth['username']) || ( $_SERVER["PHP_AUTH_PW"]!==$pageauth['password'])){
		header("WWW-Authenticate: Basic realm=Protected area" );
		header("HTTP/1.0 401 Unauthorized");
		echo "Protected area";
		exit;
	}
}


// a function to decode MIME message header extensions and get the text
function decode_imap_text($str){
    $result = '';
    $decode_header = imap_mime_header_decode($str);
    foreach ($decode_header AS $obj) {
        $result .= htmlspecialchars(rtrim($obj->text, "\t"));
	}
    return $result;
}
?>
<div id="main">

	<h1>Mailbox checker with PHP and IMAP</h1>
	
	<p>For more demos like this visit <a href="http://www.backslash.gr/">Backslash.gr</a>.</p>	
	
	<div id="mailboxes">
	<? if (!count($mailboxes)) { ?>
		<p>Please configure at least one IMAP mailbox.</p>
	<? } else { 

		foreach ($mailboxes as $current_mailbox) {
			?>
			<div class="mailbox">
			<h2><?=$current_mailbox['label']?></h2>
			<?
			if (!$current_mailbox['enable']) {
			?>
				<p>This mailbox is disabled.</p>
			<?
			} else {
				
				// Open an IMAP stream to our mailbox
				$stream = @imap_open($current_mailbox['mailbox'], $current_mailbox['username'], $current_mailbox['password']);
				
				if (!$stream) { 
				?>
					<p>Could not connect to: <?=$current_mailbox['label']?>. Error: <?=imap_last_error()?></p>
				<?
				} else {
					// Get our messages from the last week
					// Instead of searching for this week's message you could search for all the messages in your inbox using: $emails = imap_search($stream,'ALL');
					$emails = imap_search($stream, 'SINCE '. date('d-M-Y',strtotime("-1 week")));
					
					if (!count($emails)){
					?>
						<p>No e-mails found.</p>
					<?
					} else {

						// If we've got some email IDs, sort them from new to old and show them
						rsort($emails);
						
						foreach($emails as $email_id){
						
							// Fetch the email's overview and show subject, from and date. 
							$overview = imap_fetch_overview($stream,$email_id,0);						
							?>
							<div class="email_item clearfix <?=$overview[0]->seen?'read':'unread'?>"> <? // add a different class for seperating read and unread e-mails ?>
								<span class="subject" title="<?=decode_imap_text($overview[0]->subject)?>"><?=decode_imap_text($overview[0]->subject)?></span>
								<span class="from" title="<?=decode_imap_text($overview[0]->from)?>"><?=decode_imap_text($overview[0]->from)?></span>
								<span class="date"><?=$overview[0]->date?></span>
							</div>
							<?
						} 
					} 
					imap_close($stream); 
				}
				
			} 
			?>
			</div>
			<?
		} // end foreach 
	} ?>
	</div>
	
</div><!-- #main -->

</body>
</html>
</div>
