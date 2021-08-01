<fieldset>
	<legend>{LANG:ctrl_login,Please log in}:</legend>
	[BLOCK formBlock]
	<div>
		<form method="post" enctype="multipart/form-data">
			<div align="left">
				<table cellpadding="0" cellspacing="4">
					<tr>
						<td>{LANG:ctrl_login_msg01,Please log in below}</td>
					</tr>
				</table>
			</div>

			<table>
				<tr>
					<td>{LANG:ctrl_login_user,Username}:</td>
					<td align="right"><input type="text" name="loginName" value="{loginName}"></td>
				</tr>
				<tr>
					<td>{LANG:ctrl_login_pass,Password}:</td>
					<td align="right"><input type="text" name="loginPassword" value="{loginPassword}"></td>
				</tr>
				<tr>
					<hr />
				</tr>
				[BLOCK errorBlock]
				<tr>
					{LANG:ctrl_login_err01,Username and password do not match}
					<hr />
				</tr>
				[END errorBlock]
				<tr>
					<td colspan="2" align="right"><button type="submit">{LANG:form_submit,Submit}</button></td>
				</tr>
			</table>
		</form>
		[END formBlock]
		[BLOCK thanksBlock]Thanks[END thanksBlock]
	</div>
</fieldset>