<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<title>{document_h1}</title>
	[BLOCK stylesheetBlock]
	<link rel="stylesheet" href="{stylesheet_href}" />
	[END stylesheetBlock]
	[BLOCK javascriptBlock]
	<script language="javascript" src="{javascript_src}"></script>
	[END javascriptBlock]
</head>
<body>
	<div id="container">
		<div id="content">
			<div id="header">
				<h1>{LANG:site_title,PHP templates}</h1>

				[BLOCK userBlock]
				<div id="user">{username} - <a href="logoff.php">{LANG:site_logoff,logoff}</a></div>
				[END userBlock]

				[FILEBLOCK menuBlock]menu.tpl[END menuBlock]
			</div>

			<div id="body">
			{bodyBlock}
			</div>

			<div id="footer">Copyright 2005</div>
		</div>
	</div>
</body>
</html>